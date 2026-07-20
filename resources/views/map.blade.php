@extends(view()->exists('layouts.app') ? 'layouts.app' : (view()->exists('dashboard') ? 'dashboard' : 'welcome'))

@section('content')
<!-- CSS Peta, Marker Cluster & FontAwesome -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

<style>
    .peta-flex-container {
        display: flex !important;
        flex-direction: row !important;
        gap: 20px !important;
        width: 100% !important;
        align-items: flex-start !important;
    }
    
    .peta-sidebar-kiri {
        width: 350px !important;
        flex-shrink: 0 !important;
    }
    
    .peta-box-kanan {
        flex-grow: 1 !important;
        width: 100% !important;
        position: relative;
    }

    .custom-card {
        background-color: #11101d !important;
        border: 1px solid #222133 !important;
        border-radius: 10px;
        color: #f8fafc;
    }
    .custom-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        color: #94a3b8;
        font-weight: 600;
        margin-bottom: 5px;
    }
    .custom-select {
        background-color: #1e1b2e !important;
        color: #f1f5f9 !important;
        border: 1px solid #332f4c !important;
        padding: 10px;
        border-radius: 8px;
        font-size: 0.9rem;
        width: 100% !important;
        display: block;
    }
    
    .transport-group {
        display: flex;
        gap: 10px;
    }
    .btn-transport {
        flex: 1;
        background-color: #1e1b2e;
        border: 1px solid #332f4c;
        color: #94a3b8;
        border-radius: 8px;
        padding: 12px 5px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-transport.active, .btn-transport:hover {
        background-color: #4f46e5 !important;
        border-color: #6366f1 !important;
        color: #ffffff !important;
    }

    .btn-calc {
        background-color: #4f46e5;
        color: white;
        border: none;
        padding: 12px;
        border-radius: 8px;
        font-weight: 600;
        width: 100%;
    }

    .result-box {
        background-color: #1e1b2e;
        border-radius: 8px;
        padding: 15px;
    }

    #map {
        height: 650px !important;
        width: 100% !important;
        border-radius: 10px;
        border: 1px solid #222133;
        z-index: 1;
    }

    /* SEARCH BAR REALTIME DI ATAS PETA */
    .map-search-container {
        position: absolute;
        top: 15px;
        left: 60px;
        z-index: 1000;
        width: 280px;
    }
    .map-search-input {
        width: 100%;
        padding: 10px 15px;
        background-color: #11101d !important;
        border: 2px solid #4f46e5 !important;
        color: #ffffff !important;
        border-radius: 25px;
        font-size: 0.85rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.3);
    }
    .map-search-input::placeholder {
        color: #94a3b8;
    }

    /* PANEL BUTTON DI MAP */
    .custom-map-btn {
        background: #11101d !important;
        color: #ffffff !important;
        border: 1px solid #332f4c !important;
        width: 34px;
        height: 34px;
        line-height: 34px;
        text-align: center;
        cursor: pointer;
        font-size: 14px;
        display: block;
        border-radius: 4px;
        box-shadow: 0 1px 5px rgba(0,0,0,0.4);
    }
    .custom-map-btn:hover {
        background: #4f46e5 !important;
        color: #fff !important;
    }
</style>

<div class="peta-flex-container">
    <!-- PANEL SISI KIRI -->
    <div class="peta-sidebar-kiri">
        <div class="card custom-card p-4 mb-3">
            <h5 class="mb-3 text-white fw-bold"><i class="fas fa-map-signs me-2" style="color: #6366f1;"></i> Route Planner</h5>
            
            <div class="mb-3">
                <label class="custom-label">Origin Port</label>
                <select id="origin-port" class="custom-select">
                    <option value="">Select origin...</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="custom-label">Destination Port</label>
                <select id="destination-port" class="custom-select">
                    <option value="">Select destination...</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="custom-label">Transport Mode</label>
                <div class="transport-group">
                    <div class="btn-transport" data-mode="Ship">
                        <i class="fas fa-ship"></i><br><span>Ship</span>
                    </div>
                    <div class="btn-transport active" data-mode="Air">
                        <i class="fas fa-plane"></i><br><span>Air</span>
                    </div>
                    <div class="btn-transport" data-mode="Truck">
                        <i class="fas fa-truck"></i><br><span>Truck</span>
                    </div>
                </div>
            </div>

            <button id="btn-calculate" class="btn-calc mt-2">Calculate Route</button>
        </div>

        <div class="card custom-card p-4">
            <h6 class="text-warning mb-2 fw-bold"><i class="fas fa-lightbulb me-2"></i> Routing Notes</h6>
            <p class="text-muted small mb-3">Transit estimations are calculated instantly using geographic coordinates.</p>
            
            <div class="d-flex justify-content-between border-bottom border-dark pb-2 mb-2 small text-muted">
                <span><i class="fas fa-ship me-1"></i> Sea Speed</span> <span>30 km/h</span>
            </div>
            <div class="d-flex justify-content-between border-bottom border-dark pb-2 mb-2 small text-muted">
                <span><i class="fas fa-plane me-1"></i> Air Speed</span> <span>800 km/h</span>
            </div>
            <div class="d-flex justify-content-between border-bottom border-dark pb-3 mb-3 small text-muted">
                <span><i class="fas fa-truck me-1"></i> Road Speed</span> <span>70 km/h</span>
            </div>

            <div class="result-box text-center mt-2">
                <div class="small text-muted fw-bold mb-1 text-uppercase">Result Estimation</div>
                <h3 id="route-distance" class="text-info my-1 fw-bold">0 KM</h3>
                <span id="route-duration" class="badge p-2 mt-1" style="background-color: #4f46e5; font-size: 0.8rem;">0 Jam 0 Menit</span>
            </div>
        </div>
    </div>

    <!-- BOX PETA DAN SEARCH BAR -->
    <div class="peta-box-kanan" id="map-wrapper">
        <div class="map-search-container">
            <input type="text" id="map-search" class="map-search-input" placeholder="🔍 Cari nama pelabuhan di sini lek...">
        </div>
        <div id="map"></div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let selectedMode = 'Air'; // Default aktif pesawat sesuai screenshot abang
        let currentRouteLine = null;
        let allMarkersMap = {}; 

        const defaultLat = -2.5489;
        const defaultLon = 118.0149;
        const defaultZoom = 4;

        const map = L.map('map', {
            zoomControl: true
        }).setView([defaultLat, defaultLon], defaultZoom);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap &copy; CARTO',
            maxZoom: 20
        }).addTo(map);

        const markerClusterGroup = L.markerClusterGroup();
        map.addLayer(markerClusterGroup);

        // ENGINE SYNC DATA DATABASE SECARA REALTIME (TIAP 5 DETIK)
        function loadPortsRealtime() {
            fetch('/api/ports')
                .then(response => response.json())
                .then(res => {
                    const originSelect = document.getElementById('origin-port');
                    const destSelect = document.getElementById('destination-port');
                    const ports = res.data || [];
                    
                    const currentOrigin = originSelect.value;
                    const currentDest = destSelect.value;

                    originSelect.innerHTML = '<option value="">Select origin...</option>';
                    destSelect.innerHTML = '<option value="">Select destination...</option>';
                    markerClusterGroup.clearLayers();

                    ports.forEach(port => {
                        let optionText = `${port.port_name} (${port.country_code || '??'})`;
                        let optionValue = JSON.stringify({ lat: parseFloat(port.latitude), lon: parseFloat(port.longitude) });
                        
                        originSelect.options[originSelect.options.length] = new Option(optionText, optionValue);
                        destSelect.options[destSelect.options.length] = new Option(optionText, optionValue);

                        let markerColor = '#10b981';
                        if (port.risk_score >= 70) markerColor = '#ef4444';
                        else if (port.risk_score >= 40) markerColor = '#f59e0b';

                        let marker = L.circleMarker([parseFloat(port.latitude), parseFloat(port.longitude)], {
                            radius: 8,
                            fillColor: markerColor,
                            color: '#ffffff',
                            weight: 1.5,
                            opacity: 1,
                            fillOpacity: 0.8
                        }).bindPopup(`<b>${port.port_name}</b><br>Country: ${port.country_name || '-'}<br>Risk Score: ${port.risk_score}`);

                        markerClusterGroup.addLayer(marker);
                        
                        allMarkersMap[port.port_name.toLowerCase()] = {
                            marker: marker,
                            lat: parseFloat(port.latitude),
                            lon: parseFloat(port.longitude)
                        };
                    });

                    if(currentOrigin) originSelect.value = currentOrigin;
                    if(currentDest) destSelect.value = currentDest;
                })
                .catch(err => console.error('Gagal sinkronisasi data:', err));
        }

        loadPortsRealtime();
        setInterval(loadPortsRealtime, 5000); // Polling background running

        // BUTTON NAVIGASI: RESET VIEW (HOME)
        const HomeControl = L.Control.extend({
            options: { position: 'topleft' },
            onAdd: function () {
                const container = L.DomUtil.create('div', 'leaflet-bar');
                const button = L.DomUtil.create('a', 'custom-map-btn', container);
                button.innerHTML = '<i class="fa-solid fa-house"></i>';
                L.DomEvent.on(button, 'click', function (e) {
                    L.DomEvent.stopPropagation(e);
                    map.setView([defaultLat, defaultLon], defaultZoom);
                });
                return container;
            }
        });
        map.addControl(new HomeControl());

        // BUTTON NAVIGASI: FULLSCREEN LAYAR
        const FullscreenControl = L.Control.extend({
            options: { position: 'topleft' },
            onAdd: function () {
                const container = L.DomUtil.create('div', 'leaflet-bar');
                const button = L.DomUtil.create('a', 'custom-map-btn', container);
                button.innerHTML = '<i class="fa-solid fa-expand"></i>';
                L.DomEvent.on(button, 'click', function (e) {
                    L.DomEvent.stopPropagation(e);
                    const wrapper = document.getElementById('map-wrapper');
                    if (!document.fullscreenElement) {
                        wrapper.requestFullscreen();
                        button.innerHTML = '<i class="fa-solid fa-compress"></i>';
                    } else {
                        document.exitFullscreen();
                        button.innerHTML = '<i class="fa-solid fa-expand"></i>';
                    }
                });
                return container;
            }
        });
        map.addControl(new FullscreenControl());

        // LOGIKA PENYARINGAN LIVE SEARCH BAR
        document.getElementById('map-search').addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase().trim();
            if(!query) return;
            for (let name in allMarkersMap) {
                if (name.includes(query)) {
                    const target = allMarkersMap[name];
                    map.setView([target.lat, target.lon], 9, { animate: true, duration: 1 });
                    target.marker.openPopup();
                    break;
                }
            }
        });

        // HANDLE TOMBOL TRANSPORT MODE
        document.querySelectorAll('.btn-transport').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.btn-transport').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                selectedMode = this.getAttribute('data-mode');
            });
        });

        // HITUNG RUMUS HAVERSINE DISTANCE
        function calculateHaversine(lat1, lon1, lat2, lon2) {
            const R = 6371;
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                      Math.sin(dLon/2) * Math.sin(dLon/2);
            return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        }

        // AKSI TOMBOL CALCULATE ROUTE (WAKTU LEBIH DINAMIS & JALAN TERUS)
        document.getElementById('btn-calculate').addEventListener('click', function() {
            const originVal = document.getElementById('origin-port').value;
            const destVal = document.getElementById('destination-port').value;

            if (!originVal || !destVal) {
                alert('Pilih pelabuhan asal dan tujuan terlebih dahulu lek!');
                return;
            }

            const origin = JSON.parse(originVal);
            const dest = JSON.parse(destVal);
            const distance = calculateHaversine(origin.lat, origin.lon, dest.lat, dest.lon);

            let speed = 30; 
            if (selectedMode === 'Air') speed = 800;
            if (selectedMode === 'Truck') speed = 70;

            const totalHours = distance / speed;
            let displayDuration = "";
            
            // Logika pemisah: jika waktu perjalanan di bawah 24 jam lek
            if (totalHours < 24) {
                const hours = Math.floor(totalHours);
                const minutes = Math.round((totalHours - hours) * 60);
                displayDuration = `${hours} Jam ${minutes} Menit`;
            } else {
                // Jika perjalanan berhari-hari
                const days = Math.floor(totalHours / 24);
                const remainingHours = Math.round(totalHours % 24);
                displayDuration = `${days} Hari ${remainingHours} Jam`;
            }

            document.getElementById('route-distance').innerText = Math.round(distance).toLocaleString('id-ID') + " KM";
            document.getElementById('route-duration').innerText = displayDuration;

            if (currentRouteLine) map.removeLayer(currentRouteLine);

            currentRouteLine = L.polyline([[origin.lat, origin.lon], [dest.lat, dest.lon]], {
                color: '#6366f1',
                weight: 4,
                dashArray: '6, 10',
                opacity: 0.9
            }).addTo(map);

            map.fitBounds(currentRouteLine.getBounds(), { padding: [50, 50] });
        });
    });
</script>
@endsection