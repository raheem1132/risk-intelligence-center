import { createServer } from 'node:http';
import { readFileSync } from 'node:fs';
import WebSocket from 'ws';

const env = Object.fromEntries(
    readFileSync(new URL('../.env', import.meta.url), 'utf8')
        .split(/\r?\n/)
        .filter(line => line && !line.trimStart().startsWith('#') && line.includes('='))
        .map(line => {
            const separator = line.indexOf('=');
            return [line.slice(0, separator).trim(), line.slice(separator + 1).trim().replace(/^['"]|['"]$/g, '')];
        })
);

const apiKey = process.env.AISSTREAM_API_KEY || env.AISSTREAM_API_KEY;
const port = Number(process.env.PORT || process.env.AIS_BRIDGE_PORT || env.AIS_BRIDGE_PORT || 8787);

if (!apiKey) {
    console.error('AISSTREAM_API_KEY is missing from .env');
    process.exit(1);
}

const server = createServer((request, response) => {
    response.setHeader('Access-Control-Allow-Origin', '*');
    if (request.method === 'OPTIONS') return response.end();
    const url = new URL(request.url, `http://${request.headers.host}`);
    if (url.pathname === '/health') {
        response.setHeader('Content-Type', 'application/json');
        return response.end(JSON.stringify({ status: 'ok' }));
    }
    if (url.pathname !== '/stream') {
        response.statusCode = 404;
        return response.end('Not found');
    }

    let boundingBox;
    try {
        boundingBox = JSON.parse(url.searchParams.get('bbox'));
        if (!Array.isArray(boundingBox) || boundingBox.length !== 2) throw new Error();
    } catch {
        response.statusCode = 422;
        return response.end('Invalid bounding box');
    }

    response.writeHead(200, {
        'Content-Type': 'text/event-stream',
        'Cache-Control': 'no-cache, no-transform',
        'Connection': 'keep-alive',
        'X-Accel-Buffering': 'no'
    });
    const send = payload => response.write(`data: ${JSON.stringify(payload)}\n\n`);
    send({ type: 'status', status: 'connecting' });

    const upstream = new WebSocket('wss://stream.aisstream.io/v0/stream', { rejectUnauthorized: false });
    upstream.addEventListener('open', () => {
        upstream.send(JSON.stringify({
            APIKey: apiKey,
            BoundingBoxes: [boundingBox],
            FilterMessageTypes: ['PositionReport', 'StandardClassBPositionReport', 'ExtendedClassBPositionReport']
        }));
        send({ type: 'status', status: 'connected' });
    });
    upstream.addEventListener('message', event => {
        try {
            const message = JSON.parse(event.data);
            const metadata = message.MetaData || {};
            const report = message.Message?.PositionReport || message.Message?.StandardClassBPositionReport || message.Message?.ExtendedClassBPositionReport || {};
            const latitude = Number(metadata.latitude ?? metadata.Latitude ?? report.Latitude);
            const longitude = Number(metadata.longitude ?? metadata.Longitude ?? report.Longitude);
            if (!Number.isFinite(latitude) || !Number.isFinite(longitude)) return;
            send({type:'vessel',vessel:{
                mmsi:String(metadata.MMSI ?? report.UserID ?? report.UserId ?? ''),
                name:String(metadata.ShipName || 'Unknown vessel').trim(), latitude, longitude,
                speed:Number(report.Sog ?? report.SpeedOverGround ?? 0),
                course:Number(report.Cog ?? report.CourseOverGround ?? report.TrueHeading ?? 0),
                status:report.NavigationalStatus ?? null, receivedAt:new Date().toISOString()
            }});
        } catch {}
    });
    upstream.addEventListener('error', event => {
        const message = event.error?.message || event.message || 'AIS upstream connection failed';
        console.error(`AIS upstream error: ${message}`);
        send({ type:'status', status:'error', message });
    });
    upstream.addEventListener('close', () => { send({ type:'status', status:'disconnected' }); response.end(); });
    request.on('close', () => upstream.close());
});

server.listen(port, '0.0.0.0', () => console.log(`AIS bridge listening on http://127.0.0.1:${port}`));
const shutdown = () => server.close(() => process.exit(0));
process.on('SIGINT', shutdown);
process.on('SIGTERM', shutdown);
