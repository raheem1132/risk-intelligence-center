@extends('layouts.app')

@section('content')
<style>
    .weather-page{max-width:1540px;margin:auto;color:#edf5ff}.weather-head{display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:18px}.weather-head h1{font-size:2.3rem;font-weight:850;letter-spacing:-.04em;margin:6px 0}.weather-head p{color:#899bb3;margin:0}.kicker{color:#37c8ff;font-size:.68rem;font-weight:800;letter-spacing:.2em}.live{padding:9px 14px;border:1px solid #294159;border-radius:99px;font-size:.67rem}.live i{display:inline-block;width:8px;height:8px;background:#35e6b1;border-radius:50%;box-shadow:0 0 12px #35e6b1;margin-right:8px}
    .location-bar{display:grid;grid-template-columns:minmax(220px,.75fr) minmax(300px,1.25fr) auto;gap:9px;padding:13px;background:#0d1725;border:1px solid #26384f;border-radius:15px;margin-bottom:8px}.search-wrap{position:relative}.search-wrap svg{position:absolute;left:14px;top:50%;width:17px;height:17px;color:#6f829a;transform:translateY(-50%);pointer-events:none}.location-bar input,.location-bar select{width:100%;height:46px;border:1px solid #2b3f57;background:#09131f;color:#fff;border-radius:10px;padding:0 13px;outline:none;transition:.2s}.location-bar input{padding-left:42px}.location-bar input::placeholder{color:#60738c}.location-bar input:focus,.location-bar select:focus{border-color:#35e6b1;box-shadow:0 0 0 3px rgba(53,230,177,.1)}.location-bar button{border:0;border-radius:10px;background:#35e6b1;color:#05140f;padding:0 18px;font-weight:850}.location-bar button:disabled{cursor:not-allowed;opacity:.5}.search-status{min-height:20px;color:#7f93ac;font-size:.69rem;margin:0 4px 8px}.search-status.error{color:#ff7a91}
    .weather-hero{display:grid;grid-template-columns:1.1fr 1.9fr;gap:14px;margin-bottom:14px}.current-weather,.forecast-panel,.weather-chart{border:1px solid #26384f;background:linear-gradient(145deg,#111d2e,#0b1421);border-radius:18px}.current-weather{position:relative;overflow:hidden;padding:22px;background:linear-gradient(145deg,#17314a,#10182a)}.current-weather:after{content:"";position:absolute;width:180px;height:180px;border-radius:50%;right:-45px;top:-75px;background:#37c8ff;filter:blur(80px);opacity:.22}.weather-icon{font-size:3rem}.current-weather strong{font-size:3rem;display:block;margin:8px 0}.current-weather p{font-size:.72rem;color:#91a4bc}.risk-line{display:flex;justify-content:space-between;padding-top:14px;border-top:1px solid #2b4057;font-size:.69rem}.risk-line b{color:#35e6b1}.forecast-panel{padding:18px}.forecast-panel h2,.weather-chart h2{font-size:.9rem;font-weight:800}.weather-metrics{display:grid;grid-template-columns:repeat(3,1fr);gap:9px;margin-top:15px}.weather-metric{padding:13px;background:#09131f;border-radius:11px}.weather-metric span{display:block;color:#71849d;font-size:.58rem}.weather-metric strong{display:block;font-size:1rem;margin-top:5px}.hourly-strip{display:grid;grid-template-columns:repeat(6,1fr);gap:7px;margin-top:14px}.hour{padding:9px;text-align:center;background:#101d2e;border-radius:9px}.hour span{display:block;color:#73869f;font-size:.55rem}.hour strong{font-size:.68rem}.weather-chart{padding:19px}.chart-box{height:310px}
    @media(max-width:950px){.weather-hero{grid-template-columns:1fr}.hourly-strip{grid-template-columns:repeat(3,1fr)}.location-bar{grid-template-columns:1fr 1fr}.location-bar button{grid-column:1/-1;height:44px}}@media(max-width:600px){.location-bar{grid-template-columns:1fr}.location-bar button{grid-column:auto}.weather-metrics{grid-template-columns:1fr}.weather-head{align-items:flex-start;flex-direction:column}}
</style>

<div class="weather-page">
    <header class="weather-head">
        <div><div class="kicker">GLOBAL WEATHER OPERATIONS</div><h1>Global City Weather Command</h1><p>Cuaca aktual kota dan pelabuhan dunia melalui Open-Meteo.</p></div>
        <div class="live"><i></i>Live forecast feed</div>
    </header>

    <div class="location-bar">
        <div class="search-wrap">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
            <input id="citySearch" type="search" placeholder="Cari kota besar di seluruh dunia..." autocomplete="off" aria-label="Cari kota di seluruh dunia">
        </div>
        <select id="location" aria-label="Pilih kota atau pelabuhan">
            <option value="5.5483,95.3238" data-name="Banda Aceh" data-country="Aceh — Indonesia">Banda Aceh — Aceh — Indonesia</option>
            <option value="5.2030,96.7009" data-name="Bireuen" data-country="Aceh — Indonesia">Kabupaten Bireuen — Aceh — Indonesia</option>
            <option value="5.1801,97.1507" data-name="Lhokseumawe" data-country="Aceh — Indonesia">Lhokseumawe — Aceh — Indonesia</option>
            @foreach($ports as $port)
                <option value="{{ $port->latitude }},{{ $port->longitude }}" data-name="{{ $port->port_name }}" data-country="{{ $port->country_name }}">{{ $port->port_name }} — {{ $port->country_name }}</option>
            @endforeach
        </select>
        <button id="refreshWeather" type="button">↻ Refresh weather</button>
    </div>
    <div class="search-status" id="searchStatus">Ketik minimal 3 huruf untuk mencari kota mana pun di dunia.</div>

    <section class="weather-hero">
        <article class="current-weather"><div class="weather-icon" id="weatherIcon">◌</div><span id="weatherLocation">Selected city</span><strong id="temperature">—</strong><p id="condition">Mengambil kondisi terbaru...</p><div class="risk-line"><span>Operational weather risk</span><b id="risk">—</b></div></article>
        <article class="forecast-panel"><h2>Current Operational Metrics</h2><div class="weather-metrics"><div class="weather-metric"><span>PRECIPITATION</span><strong id="rain">—</strong></div><div class="weather-metric"><span>WIND SPEED</span><strong id="wind">—</strong></div><div class="weather-metric"><span>RELATIVE HUMIDITY</span><strong id="humidity">—</strong></div></div><div class="hourly-strip" id="hourlyStrip"></div></article>
    </section>
    <section class="weather-chart"><h2>24-Hour Wind & Precipitation Outlook</h2><div class="chart-box"><canvas id="weatherChart"></canvas></div></section>
</div>

<script>
    let weatherChart, searchTimer, searchController;
    const locationSelect = document.getElementById('location');
    const citySearch = document.getElementById('citySearch');
    const refreshButton = document.getElementById('refreshWeather');
    const searchStatus = document.getElementById('searchStatus');
    const defaultLocations = [...locationSelect.options].map(option => option.cloneNode(true));
    const weatherCodes = {0:['☀','Cerah'],1:['🌤','Cerah berawan'],2:['⛅','Berawan'],3:['☁','Mendung'],45:['🌫','Berkabut'],51:['🌦','Gerimis'],61:['🌧','Hujan'],63:['🌧','Hujan sedang'],65:['⛈','Hujan lebat'],80:['🌦','Hujan lokal'],95:['⛈','Badai petir']};

    function setStatus(message, isError = false) {
        searchStatus.textContent = message;
        searchStatus.classList.toggle('error', isError);
    }

    async function loadWeather() {
        if (!locationSelect.value) return;
        const [latitude, longitude] = locationSelect.value.split(',');
        const option = locationSelect.options[locationSelect.selectedIndex];
        document.getElementById('weatherLocation').textContent = `${option.dataset.name} · ${option.dataset.country}`;
        try {
            const response = await fetch(`https://api.open-meteo.com/v1/forecast?latitude=${latitude}&longitude=${longitude}&current=temperature_2m,relative_humidity_2m,precipitation,weather_code,wind_speed_10m&hourly=temperature_2m,wind_speed_10m,precipitation_probability,precipitation&forecast_days=1&timezone=auto`);
            if (!response.ok) throw new Error();
            const data = await response.json(), current = data.current;
            const weather = weatherCodes[current.weather_code] || ['◌', `Kondisi kode ${current.weather_code}`];
            const score = Math.min(100, Math.round(current.wind_speed_10m * 1.5 + current.precipitation * 4));
            document.getElementById('weatherIcon').textContent = weather[0];
            document.getElementById('condition').textContent = `${weather[1]} · diperbarui ${current.time.replace('T',' ')}`;
            document.getElementById('temperature').textContent = `${current.temperature_2m} °C`;
            document.getElementById('rain').textContent = `${current.precipitation} mm`;
            document.getElementById('wind').textContent = `${current.wind_speed_10m} km/h`;
            document.getElementById('humidity').textContent = `${current.relative_humidity_2m}%`;
            document.getElementById('risk').textContent = `${score}/100 · ${score >= 65 ? 'High' : score >= 35 ? 'Medium' : 'Low'}`;
            document.getElementById('hourlyStrip').innerHTML = [0,4,8,12,16,20].map(i => `<div class="hour"><span>${data.hourly.time[i].slice(11)}</span><strong>${data.hourly.temperature_2m[i]}°</strong><span>${data.hourly.precipitation_probability[i]}% rain</span></div>`).join('');
            weatherChart?.destroy();
            weatherChart = new Chart(document.getElementById('weatherChart'), {type:'line',data:{labels:data.hourly.time.map(time=>time.slice(11)),datasets:[{label:'Wind km/h',data:data.hourly.wind_speed_10m,borderColor:'#35e6b1',backgroundColor:'rgba(53,230,177,.12)',fill:true,tension:.38},{label:'Rain mm',data:data.hourly.precipitation,borderColor:'#37c8ff',backgroundColor:'rgba(55,200,255,.1)',fill:true,tension:.38,yAxisID:'y1'}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{labels:{color:'#9bacc1',usePointStyle:true}}},scales:{x:{grid:{display:false},ticks:{color:'#72849d',maxTicksLimit:12}},y:{grid:{color:'rgba(130,150,175,.09)'},ticks:{color:'#72849d'}},y1:{position:'right',grid:{display:false},ticks:{color:'#72849d'}}}}});
        } catch {
            setStatus('Data cuaca tidak dapat dihubungi. Coba lagi.', true);
        }
    }

    async function searchCities(query) {
        searchController?.abort();
        searchController = new AbortController();
        setStatus('Mencari kota di seluruh dunia...');
        refreshButton.disabled = true;
        try {
            const normalizedQuery = query.toLocaleLowerCase('id');
            const localMatches = defaultLocations.filter(option =>
                `${option.dataset.name} ${option.dataset.country}`.toLocaleLowerCase('id').includes(normalizedQuery)
            );
            const [response, portResponse] = await Promise.all([
                fetch(`https://geocoding-api.open-meteo.com/v1/search?name=${encodeURIComponent(query)}&count=100&language=id&format=json`, {signal: searchController.signal}),
                fetch(`/api/ports?q=${encodeURIComponent(query)}&per_page=100`, {signal: searchController.signal}),
            ]);
            if (!response.ok || !portResponse.ok) throw new Error();
            const data = await response.json();
            const portData = await portResponse.json();
            const cities = (data.results || []).filter(place => place.feature_code?.startsWith('PPL')).sort((a,b) => (b.population || 0) - (a.population || 0)).slice(0,50);
            const globalOptions = cities.map(place => {
                const option = document.createElement('option');
                option.value = `${place.latitude},${place.longitude}`;
                option.dataset.name = place.name;
                option.dataset.country = place.country || place.country_code || '';
                option.textContent = [place.name, place.admin1, place.country].filter(Boolean).join(' — ');
                return option;
            });
            const portOptions = (portData.data || []).map(port => {
                const option = document.createElement('option');
                option.value = `${port.latitude},${port.longitude}`;
                option.dataset.name = port.port_name;
                option.dataset.country = port.country_name || port.country_code || '';
                option.textContent = `${port.port_name} — ${option.dataset.country}`;
                return option;
            });
            const seen = new Set();
            const options = [...localMatches.map(option => option.cloneNode(true)), ...portOptions, ...globalOptions]
                .filter(option => {
                    const key = `${option.dataset.name}|${option.dataset.country}`.toLocaleLowerCase('id');
                    if (seen.has(key)) return false;
                    seen.add(key);
                    return true;
                })
                .slice(0, 100);
            locationSelect.replaceChildren(...options);
            refreshButton.disabled = !options.length;
            if (!options.length) return setStatus('Kota tidak ditemukan. Coba nama atau ejaan lain.', true);
            setStatus(`${options.length} lokasi ditemukan`);
            await loadWeather();
        } catch (error) {
            if (error.name !== 'AbortError') setStatus('Pencarian kota tidak dapat dihubungi. Coba lagi.', true);
        }
    }

    citySearch.addEventListener('input', () => {
        clearTimeout(searchTimer);
        const query = citySearch.value.trim();
        if (query.length < 3) {
            searchController?.abort();
            locationSelect.replaceChildren(...defaultLocations.map(option => option.cloneNode(true)));
            refreshButton.disabled = !locationSelect.options.length;
            setStatus(query ? 'Ketik minimal 3 huruf untuk mencari kota dunia.' : 'Ketik minimal 3 huruf untuk mencari kota mana pun di dunia.');
            return;
        }
        searchTimer = setTimeout(() => searchCities(query), 450);
    });
    locationSelect.addEventListener('change', loadWeather);
    refreshButton.addEventListener('click', loadWeather);
    loadWeather();
</script>
@endsection
