# Railway deployment

Deploy this repository as three Railway services: `App`, `MySQL`, and `AIS Bridge`.

## App service

- Source: this GitHub repository
- Build command: `npm run build`
- Pre-deploy command: `sh railway/init-app.sh`
- Generate a public domain
- Healthcheck path: `/`

Variables:

```text
APP_ENV=production
APP_DEBUG=false
APP_KEY=<the existing local APP_KEY>
APP_URL=https://${{RAILWAY_PUBLIC_DOMAIN}}
LOG_CHANNEL=stderr
DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
AIS_BRIDGE_URL=https://${{AIS Bridge.RAILWAY_PUBLIC_DOMAIN}}
```

After the first successful deployment only, open the App service shell and seed/import the required master data. Do not run the existing seeders on every deploy because they truncate master tables.

## AIS Bridge service

- Source: the same GitHub repository
- Start command: `npm run ais`
- Generate a public domain
- Healthcheck path: `/health`

Variables:

```text
AISSTREAM_API_KEY=<your AISStream key>
```

Railway supplies `PORT` automatically. The bridge reads it at runtime.
