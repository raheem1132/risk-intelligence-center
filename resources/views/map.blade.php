@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css">
<style>
    #map { background:#071018; }
    .leaflet-control-layers,.leaflet-bar a{background:#111827!important;color:#d1d5db!important;border-color:#334155!important}
    .leaflet-control-layers label{color:#d1d5db}.leaflet-popup-content-wrapper,.leaflet-popup-tip{background:#111827;color:#d1d5db}
    .country-badge{background:#0f172a;border:1px solid #34d399;border-radius:8px;color:#e5e7eb;padding:4px 7px;box-shadow:0 4px 14px #0009;font-size:11px;font-weight:700;white-space:nowrap;width:max-content!important;height:auto!important}
    .port-dot{width:12px;height:12px;border:2px solid #dbeafe;border-radius:50%;background:#3b82f6;box-shadow:0 0 0 3px #2563eb55,0 3px 8px #000}
    .port-dot.large{background:#f43f5e;box-shadow:0 0 0 4px #f43f5e44}.port-dot.medium{background:#f59e0b}.marker-cluster div{color:#fff;font-weight:800}.marker-cluster-small,.marker-cluster-medium,.marker-cluster-large{background:#10b98144}.marker-cluster-small div,.marker-cluster-medium div,.marker-cluster-large div{background:#059669}
    .route-planner{position:absolute;z-index:1001;left:16px;top:16px;width:390px;max-height:calc(100% - 32px);overflow:auto;background:rgba(7,13,23,.96);backdrop-filter:blur(15px);border:1px solid #30425b;border-radius:17px;box-shadow:0 22px 55px #0008;color:#eaf2fc}.planner-head{display:flex;justify-content:space-between;align-items:flex-start;padding:17px;border-bottom:1px solid #223149}.planner-kicker{color:#35e6b1;font-size:9px;font-weight:800;letter-spacing:.18em;text-transform:uppercase}.planner-head h3{font-size:15px;font-weight:800;margin:4px 0 0}.planner-toggle{border:1px solid #31445e;background:#121f31;color:#9fb0c5;border-radius:8px;width:30px;height:30px}.planner-body{padding:15px}.route-field{margin-bottom:10px}.route-field label{display:block;color:#8294ac;font-size:10px;margin-bottom:6px}.route-field input{width:100%;height:42px;background:#0b1523;border:1px solid #293d56;color:#fff;border-radius:10px;padding:0 11px;outline:none;font-size:12px}.route-field input:focus{border-color:#35e6b1}.cargo-row{display:grid;grid-template-columns:1fr 1fr;gap:9px}.calculate-route{width:100%;border:0;border-radius:10px;background:linear-gradient(90deg,#25d6a1,#32bcec);color:#041410;font-size:11px;font-weight:900;padding:12px;margin-top:3px}.planner-note{color:#64778f;font-size:9px;line-height:1.45;margin:9px 0 0}.route-results{display:none;padding:0 15px 15px}.route-summary{padding:11px;background:#101c2d;border:1px solid #263a52;border-radius:11px;margin-bottom:10px}.route-summary strong{display:block;font-size:14px}.route-summary span{font-size:9px;color:#8295ae}.mode-grid{display:flex;flex-direction:column;gap:8px}.mode-card{border:1px solid #273a52;border-radius:11px;padding:10px;background:#0d1827;position:relative}.mode-card.recommended{border-color:#35e6b1;box-shadow:0 0 0 1px #35e6b133}.recommend{position:absolute;right:8px;top:8px;background:#123d31;color:#48e8b6;border-radius:99px;padding:3px 6px;font-size:8px;font-weight:800}.mode-title{display:flex;align-items:center;gap:8px;font-size:11px;font-weight:800}.mode-title i{font-style:normal;font-size:17px}.mode-metrics{display:grid;grid-template-columns:repeat(3,1fr);gap:5px;margin-top:8px}.mode-metrics span{display:block;color:#6f829b;font-size:8px}.mode-metrics strong{font-size:10px}.route-error{display:none;color:#ff7c91;font-size:10px;margin-top:8px}.route-planner.collapsed{width:auto}.route-planner.collapsed .planner-body,.route-planner.collapsed .route-results,.route-planner.collapsed .planner-kicker{display:none}.route-planner.collapsed .planner-head{border:0;gap:15px}.route-line-label{background:#091522;color:#fff;border:1px solid #35e6b1;border-radius:7px;padding:3px 6px;font-size:10px;box-shadow:0 3px 10px #0008}
    #mapShell:fullscreen{background:#070b12;padding:12px}#mapShell:fullscreen #map{height:calc(100vh - 24px)!important}
    .ais-panel{position:absolute;z-index:1002;right:16px;top:76px;width:220px;padding:12px;background:rgba(7,13,23,.94);backdrop-filter:blur(12px);border:1px solid #30425b;border-radius:13px;color:#dbe7f5;box-shadow:0 14px 35px #0007}.ais-panel-head{display:flex;align-items:center;justify-content:space-between;gap:8px}.ais-panel strong{font-size:11px}.ais-status{display:flex;align-items:center;gap:7px;color:#8294ac;font-size:9px;margin-top:6px}.ais-status i{width:7px;height:7px;border-radius:50%;background:#64748b}.ais-status.connected i{background:#35e6b1;box-shadow:0 0 10px #35e6b1}.ais-status.error i{background:#ff7189}.ais-toggle{border:0;border-radius:7px;padding:6px 8px;background:#35e6b1;color:#061510;font-size:9px;font-weight:850}.vessel-icon{width:18px;height:22px;display:grid;place-items:center;filter:drop-shadow(0 3px 5px #000)}.vessel-icon span{display:block;color:#39dcb0;font-size:17px;line-height:1;transform:rotate(var(--course));transition:transform .4s ease}.vessel-popup b{color:#fff}.vessel-popup span{color:#35e6b1}.ais-note{margin-top:7px;color:#5f728a;font-size:8px;line-height:1.4}
    #mapShell{display:flex;flex-direction:column}.route-planner{position:static;order:2;width:100%;max-height:none;margin-top:14px;overflow:visible}.route-planner .planner-body{display:grid;grid-template-columns:1fr 1fr;gap:10px 12px}.route-planner .route-field{margin:0}.route-planner .cargo-row,.route-planner .calculate-route,.route-planner .route-error,.route-planner .planner-note{grid-column:1/-1}.route-planner .calculate-route{margin-top:0}.route-planner .route-results{padding-top:0}.route-planner .mode-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));align-items:start}.route-planner.collapsed{width:100%}#map{order:1}.map-legend,.ais-panel{order:1}
    @media(max-width:1000px){.route-planner .mode-grid{grid-template-columns:1fr}}@media(max-width:768px){.map-legend{display:none}.route-planner{width:100%;max-height:none}.route-planner .planner-body{grid-template-columns:1fr}.route-planner .cargo-row,.route-planner .calculate-route,.route-planner .route-error,.route-planner .planner-note{grid-column:auto}.ais-panel{right:10px;top:10px;width:200px}}
</style>
<div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 mb-4">
    <div><span class="text-xs uppercase tracking-[.25em] text-emerald-400 font-bold">Geospatial intelligence</span><h2 class="text-3xl font-bold text-white mt-1">Global Port & Country Map</h2><p class="text-sm text-slate-400 mt-1">World Port Index — marker pelabuhan dikelompokkan dan negara ditandai terpisah.</p></div>
    <div class="flex gap-3 text-xs"><div class="px-3 py-2 rounded-lg border border-slate-700 bg-slate-900"><b class="text-white" id="portCount">0</b> Pelabuhan</div><div class="px-3 py-2 rounded-lg border border-slate-700 bg-slate-900"><b class="text-white" id="countryCount">0</b> Negara</div><button onclick="toggleFullscreen()" class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold">⛶ Fullscreen</button></div>
</div>
<div id="mapShell" class="relative rounded-2xl">
    <aside class="ais-panel"><div class="ais-panel-head"><strong>Live AIS Vessels · <span id="vesselCount">0</span></strong><button class="ais-toggle" id="aisToggle" type="button">START</button></div><div class="ais-status" id="aisStatus"><i></i><span>Tracking belum aktif</span></div><div class="ais-note">Kapal ditampilkan sesuai area peta. Geser atau zoom untuk mengganti cakupan AIS.</div></aside>
    <div id="map" class="h-[calc(100vh-175px)] min-h-[720px] w-full rounded-2xl border border-slate-700 shadow-2xl overflow-hidden"></div>
    <aside class="route-planner" id="routePlanner">
        <div class="planner-head"><div><div class="planner-kicker">Multimodal Logistics</div><h3>Port-to-Port Route Planner</h3></div><button class="planner-toggle" type="button" onclick="togglePlanner()">−</button></div>
        <div class="planner-body">
            <div class="route-field"><label>Pelabuhan asal</label><input id="originPort" list="portOptions" placeholder="Ketik nama pelabuhan asal..."></div>
            <div class="route-field"><label>Pelabuhan tujuan</label><input id="destinationPort" list="portOptions" placeholder="Ketik nama pelabuhan tujuan..."></div>
            <datalist id="portOptions"></datalist>
            <div class="cargo-row"><div class="route-field"><label>Berat kargo (ton)</label><input id="cargoWeight" type="number" min="0.1" value="10" step="0.1"></div><div class="route-field"><label>Prioritas</label><input id="routePriority" list="priorityOptions" value="Balanced"><datalist id="priorityOptions"><option value="Balanced"><option value="Fastest"><option value="Lowest cost"><option value="Lowest emission"></datalist></div></div>
            <button class="calculate-route" type="button" onclick="calculateRoute()">HITUNG 3 ALTERNATIF RUTE</button><div class="route-error" id="routeError"></div><p class="planner-note">Estimasi berbasis jarak geodesik, kecepatan rata-rata, handling, biaya, dan faktor emisi. Bukan jadwal atau quotation carrier.</p>
        </div>
        <div class="route-results" id="routeResults"><div class="route-summary"><strong id="routeDistance">—</strong><span id="routePair">—</span></div><div class="mode-grid" id="modeGrid"></div></div>
    </aside>
    <aside class="map-legend absolute z-[1000] left-4 bottom-4 w-56 bg-slate-950/95 backdrop-blur border border-slate-700 rounded-xl p-4 shadow-2xl">
        <h3 class="text-sm font-bold text-white mb-4">Legenda Peta</h3>
        <div class="space-y-3 text-xs text-slate-400"><div class="flex items-center gap-3"><i class="port-dot large block"></i> Pelabuhan besar</div><div class="flex items-center gap-3"><i class="port-dot medium block"></i> Pelabuhan medium</div><div class="flex items-center gap-3"><i class="port-dot block"></i> Pelabuhan lainnya</div><div class="flex items-center gap-3"><span class="px-2 py-1 border border-emerald-500 rounded text-white">🇮🇩 ID</span> Pusat negara</div></div>
        <div class="border-t border-slate-800 mt-4 pt-3 text-[11px] text-slate-500">Klik cluster untuk memperbesar dan marker untuk melihat detail.</div>
    </aside>
</div>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
<script>
(async()=>{
const mobileMap=window.matchMedia('(max-width: 768px)').matches;
const mapResponse=await fetch(`/api/map/ports?limit=${mobileMap?1500:15000}`);
if(!mapResponse.ok)throw new Error('Port map data failed to load');
const mapPayload=await mapResponse.json(),ports=mapPayload.data||[];
const map=L.map('map',{zoomControl:false,minZoom:2,worldCopyJump:true}).setView([12,20],2);
L.control.zoom({position:'bottomright'}).addTo(map);
L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png',{maxZoom:19,attribution:'OpenStreetMap · CARTO'}).addTo(map);
const clusters=L.markerClusterGroup({chunkedLoading:true,maxClusterRadius:48,showCoverageOnHover:false});
const countries=L.layerGroup();
const countryData={};
const flag=code=>code&&code.length===2?[...code.toUpperCase()].map(c=>String.fromCodePoint(127397+c.charCodeAt())).join(''):'🌐';
ports.forEach(p=>{
    if(!Number.isFinite(p.lat)||!Number.isFinite(p.lng))return;
    const size=(p.size||'').toLowerCase(), cls=size.includes('large')?'large':(size.includes('medium')?'medium':'');
    const icon=L.divIcon({className:'',html:`<div class="port-dot ${cls}"></div>`,iconSize:[14,14],iconAnchor:[7,7]});
    L.marker([p.lat,p.lng],{icon}).bindPopup(`<div style="min-width:210px"><b style="color:#fff;font-size:14px">⚓ ${p.name}</b><div style="color:#34d399;margin:5px 0">${flag(p.code)} ${p.country||'Unknown country'}</div><div>Harbor: ${p.size||'-'} · ${p.type||'-'}</div><small style="color:#94a3b8">ID: ${p.wpi||'-'}<br>Source: ${p.source||'-'}</small></div>`).addTo(clusters);
    const key=p.code||p.country;if(key){countryData[key]??={name:p.country,code:p.code,lat:0,lng:0,n:0};countryData[key].lat+=p.lat;countryData[key].lng+=p.lng;countryData[key].n++;}
});
Object.values(countryData).forEach(c=>{const icon=L.divIcon({className:'country-badge',html:`${flag(c.code)} ${c.code||c.name}`});L.marker([c.lat/c.n,c.lng/c.n],{icon,zIndexOffset:1000}).bindTooltip(`${c.name} · ${c.n} ports`,{direction:'top'}).addTo(countries)});
clusters.addTo(map);countries.addTo(map);L.control.layers(null,{'⚓ Pelabuhan':clusters,'🌍 Negara':countries},{collapsed:false,position:'topright'}).addTo(map);
portCount.textContent=(mapPayload.meta?.total||ports.length).toLocaleString();countryCount.textContent=Number(@json($mapCountryCount)).toLocaleString();
const portOptions=document.getElementById('portOptions');
const portLookup=new Map();
const portEntries=[];
ports.forEach((p,index)=>{if(!Number.isFinite(p.lat)||!Number.isFinite(p.lng))return;const label=`${p.name} — ${p.country||p.code||'Unknown'} [${index}]`;portLookup.set(label,p);portEntries.push({label,search:label.toLocaleLowerCase(),port:p})});
async function refreshPortOptions(value=''){
 const term=value.trim().toLocaleLowerCase();
 let matches=(term?portEntries.filter(entry=>entry.search.includes(term)):portEntries).slice(0,100);
 if(term.length>=2){
  try{
   const response=await fetch(`/api/ports?q=${encodeURIComponent(value.trim())}&per_page=100`),payload=await response.json();
   matches=(payload.data||[]).filter(port=>port.latitude!==null&&port.longitude!==null).map(port=>{
    const mapped={name:port.port_name,country:port.country_name,code:port.country_code,lat:Number(port.latitude),lng:Number(port.longitude),size:port.harbor_size,type:port.harbor_type,wpi:port.wpi_number,source:port.source};
    const label=`${mapped.name} — ${mapped.country||mapped.code||'Unknown'} [${port.id}]`;
    portLookup.set(label,mapped);
    return {label,search:label.toLocaleLowerCase(),port:mapped};
   });
  }catch{}
 }
 portOptions.replaceChildren(...matches.map(entry=>{const option=document.createElement('option');option.value=entry.label;return option}));
}
let portSearchTimer;
['originPort','destinationPort'].forEach(id=>{const input=document.getElementById(id);input.addEventListener('focus',()=>refreshPortOptions(input.value));input.addEventListener('input',()=>{clearTimeout(portSearchTimer);portSearchTimer=setTimeout(()=>refreshPortOptions(input.value),250)})});
refreshPortOptions();
let plannedRoute=L.layerGroup().addTo(map);
const money=value=>new Intl.NumberFormat('en-US',{style:'currency',currency:'USD',maximumFractionDigits:0}).format(value);
const distance=value=>new Intl.NumberFormat('id-ID',{maximumFractionDigits:0}).format(value)+' km';
function duration(hours){const days=Math.floor(hours/24),remaining=Math.round(hours%24);return days?`${days} hari ${remaining} jam`:`${remaining} jam`}
function haversine(a,b){const rad=n=>n*Math.PI/180,R=6371,dLat=rad(b.lat-a.lat),dLon=rad(b.lng-a.lng),x=Math.sin(dLat/2)**2+Math.cos(rad(a.lat))*Math.cos(rad(b.lat))*Math.sin(dLon/2)**2;return 2*R*Math.asin(Math.sqrt(x))}
function togglePlanner(){const planner=document.getElementById('routePlanner');planner.classList.toggle('collapsed');planner.querySelector('.planner-toggle').textContent=planner.classList.contains('collapsed')?'+':'−';setTimeout(()=>map.invalidateSize(),200)}
function calculateRoute(){
 const origin=portLookup.get(document.getElementById('originPort').value),destination=portLookup.get(document.getElementById('destinationPort').value),error=document.getElementById('routeError');error.style.display='none';
 if(!origin||!destination){error.textContent='Pilih pelabuhan dari daftar yang tersedia.';error.style.display='block';return}if(origin===destination){error.textContent='Pelabuhan asal dan tujuan harus berbeda.';error.style.display='block';return}
 const direct=haversine(origin,destination),tons=Math.max(.1,Number(document.getElementById('cargoWeight').value)||10),priority=document.getElementById('routePriority').value;
 const modes=[
  {key:'truck',icon:'🚚',name:'Truck / Road Freight',color:'#35e6b1',km:direct*1.25,hours:direct*1.25/62+6,cost:direct*1.25*tons*.11+180,co2:direct*1.25*tons*.062,capacity:'24 ton',note:'Termasuk buffer jalan & 6 jam handling'},
  {key:'air',icon:'✈️',name:'Air Cargo',color:'#9878ff',km:direct*1.05,hours:direct*1.05/750+10,cost:tons*1000*4.2+800,co2:direct*1.05*tons*.602,capacity:'120 ton',note:'Termasuk transfer bandara & 10 jam handling'},
  {key:'ship',icon:'🚢',name:'Ocean Freight',color:'#37c8ff',km:direct*1.08,hours:direct*1.08/30+30,cost:direct*1.08*tons*.035+350,co2:direct*1.08*tons*.011,capacity:'20K+ TEU',note:'Termasuk deviasi laut & 30 jam port handling'}
 ];
 const max=(field)=>Math.max(...modes.map(m=>m[field]));modes.forEach(m=>{m.score=priority==='Fastest'?m.hours:(priority==='Lowest cost'?m.cost:(priority==='Lowest emission'?m.co2:(m.hours/max('hours')*.4+m.cost/max('cost')*.35+m.co2/max('co2')*.25)))});const recommended=[...modes].sort((a,b)=>a.score-b.score)[0];
 document.getElementById('routeDistance').textContent=distance(direct)+' jarak langsung';document.getElementById('routePair').textContent=`${origin.name} → ${destination.name} · ${tons.toLocaleString('id-ID')} ton kargo`;
 document.getElementById('modeGrid').innerHTML=modes.map(m=>`<article class="mode-card ${m===recommended?'recommended':''}">${m===recommended?'<span class="recommend">REKOMENDASI</span>':''}<div class="mode-title"><i>${m.icon}</i><div>${m.name}<div style="color:#657991;font-size:8px;font-weight:400">${m.note}</div></div></div><div class="mode-metrics"><div><span>Waktu tempuh</span><strong>${duration(m.hours)}</strong></div><div><span>Estimasi biaya</span><strong>${money(m.cost)}</strong></div><div><span>Emisi CO₂</span><strong>${Math.round(m.co2).toLocaleString('id-ID')} kg</strong></div><div><span>Jarak efektif</span><strong>${distance(m.km)}</strong></div><div><span>Kapasitas tipikal</span><strong>${m.capacity}</strong></div><div><span>Kecepatan asumsi</span><strong>${m.key==='truck'?'62 km/j':m.key==='air'?'750 km/j':'30 km/j'}</strong></div></div></article>`).join('');document.getElementById('routeResults').style.display='block';
 plannedRoute.clearLayers();const points=[[origin.lat,origin.lng],[destination.lat,destination.lng]];L.polyline(points,{color:'#071018',weight:8,opacity:.8}).addTo(plannedRoute);L.polyline(points,{color:recommended.color,weight:4,dashArray:'10 8',opacity:.95}).bindTooltip(`${recommended.icon} ${recommended.name} · ${duration(recommended.hours)}`,{permanent:false,className:'route-line-label'}).addTo(plannedRoute);L.circleMarker(points[0],{radius:8,color:'#fff',weight:2,fillColor:'#35e6b1',fillOpacity:1}).bindPopup(`<b>Asal</b><br>${origin.name}`).addTo(plannedRoute);L.circleMarker(points[1],{radius:8,color:'#fff',weight:2,fillColor:'#ff6079',fillOpacity:1}).bindPopup(`<b>Tujuan</b><br>${destination.name}`).addTo(plannedRoute);map.fitBounds(points,{padding:[80,80],maxZoom:7});
}
const vesselLayer=L.layerGroup();
const vesselMarkers=new Map();
const aisStatus=document.getElementById('aisStatus'),aisToggle=document.getElementById('aisToggle');
const configuredAisBridge=@json(rtrim((string) config('services.ais.bridge_url'), '/'));
let aisSource=null,aisActive=false,aisBoundsTimer=null,aisReconnectTimer=null;
const escapeHtml=value=>String(value).replace(/[&<>'"]/g,char=>({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#39;','"':'&quot;'}[char]));
function setAisStatus(state,label){aisStatus.className=`ais-status ${state}`;aisStatus.querySelector('span').textContent=label}
function currentAisBounds(){const bounds=map.getBounds();return [[bounds.getSouth(),bounds.getWest()],[bounds.getNorth(),bounds.getEast()]]}
function connectAis(){
 if(map.getZoom()<4){setAisStatus('error','Perbesar peta minimal ke zoom 4');return}
 clearTimeout(aisReconnectTimer);setAisStatus('','Menghubungkan ke AIS bridge...');
 aisSource?.close();const protocol=location.protocol==='https:'?'https':'http';const bridgeBase=configuredAisBridge||`${protocol}://${location.hostname}:8787`;const endpoint=`${bridgeBase}/stream?bbox=${encodeURIComponent(JSON.stringify(currentAisBounds()))}`;aisSource=new EventSource(endpoint);
 aisSource.addEventListener('message',event=>{let payload;try{payload=JSON.parse(event.data)}catch{return}if(payload.type==='status'){if(payload.status==='connected')setAisStatus('connected','Live · menerima posisi kapal');else if(payload.status==='error')setAisStatus('error',payload.message?.includes('503')?'Provider AIS sedang tidak tersedia (503)':'Koneksi AIS bermasalah');return}if(payload.type==='vessel')updateVessel(payload.vessel)});
 aisSource.addEventListener('error',()=>{aisSource?.close();if(!aisActive)return;setAisStatus('error','AIS bridge belum dijalankan / terputus');clearTimeout(aisReconnectTimer);aisReconnectTimer=setTimeout(connectAis,3000)});
}
function updateVessel(vessel){
 if(!vessel.mmsi)return;const position=[vessel.latitude,vessel.longitude],course=Number.isFinite(vessel.course)?vessel.course:0;
 let marker=vesselMarkers.get(vessel.mmsi);
 if(!marker){const icon=L.divIcon({className:'vessel-icon',html:`<span style="--course:${course}deg">▲</span>`,iconSize:[18,22],iconAnchor:[9,11]});marker=L.marker(position,{icon,zIndexOffset:800}).addTo(vesselLayer);vesselMarkers.set(vessel.mmsi,marker)}else{marker.setLatLng(position);const arrow=marker.getElement()?.querySelector('span');if(arrow)arrow.style.setProperty('--course',`${course}deg`)}
 marker.lastSeen=Date.now();marker.bindPopup(`<div class="vessel-popup"><b>🚢 ${escapeHtml(vessel.name||'Unknown vessel')}</b><br><span>MMSI ${escapeHtml(vessel.mmsi)}</span><br>Speed: ${Number(vessel.speed||0).toFixed(1)} knots<br>Course: ${Math.round(course)}°<br><small>Updated ${new Date(vessel.receivedAt).toLocaleTimeString('id-ID')}</small></div>`);document.getElementById('vesselCount').textContent=vesselMarkers.size.toLocaleString('id-ID');
}
function stopAis(){aisActive=false;clearTimeout(aisReconnectTimer);aisSource?.close();aisSource=null;vesselLayer.remove();aisToggle.textContent='START';setAisStatus('','Tracking dihentikan')}
aisToggle.addEventListener('click',()=>{if(aisActive)return stopAis();if(map.getZoom()<4){setAisStatus('error','Zoom peta lebih dekat dahulu');return}aisActive=true;vesselLayer.addTo(map);aisToggle.textContent='STOP';connectAis()});
map.on('moveend',()=>{if(!aisActive)return;clearTimeout(aisBoundsTimer);aisBoundsTimer=setTimeout(connectAis,600)});
setInterval(()=>{const expiry=Date.now()-300000;for(const[mmsi,marker]of vesselMarkers)if(marker.lastSeen<expiry){vesselLayer.removeLayer(marker);vesselMarkers.delete(mmsi)}document.getElementById('vesselCount').textContent=vesselMarkers.size.toLocaleString('id-ID')},30000);
function toggleFullscreen(){const shell=document.getElementById('mapShell');if(!document.fullscreenElement)shell.requestFullscreen();else document.exitFullscreen()}
document.addEventListener('fullscreenchange',()=>setTimeout(()=>map.invalidateSize(),150));
window.togglePlanner=togglePlanner;
window.calculateRoute=calculateRoute;
window.toggleFullscreen=toggleFullscreen;
})().catch(error=>{console.error(error);document.getElementById('portCount').textContent='Error'});
</script>
@endsection
