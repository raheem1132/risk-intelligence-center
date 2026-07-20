<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Blade;

// Tampilan Awal Login & Register
Route::get('/', function () { return view('login'); });
Route::get('/register', function () { return view('register'); });

// Aksi Registrasi & Login Session
Route::post('/register', function (Request $request) {
    cookie()->queue('registered_name', $request->name, 120);
    cookie()->queue('registered_email', $request->email, 120);
    session(['user_name' => $request->name]); 
    return redirect('/');
});

Route::post('/login', function (Request $request) {
    $registeredEmail = request()->cookie('registered_email');
    $registeredName = request()->cookie('registered_name');
    if ($registeredEmail && $request->email === $registeredEmail) {
        session(['user_name' => $registeredName]);
    } else {
        session(['user_name' => 'dodo mas']);
    }
    return redirect('/dashboard');
});

// DATA MASTER NEGARA GLOBAL - BERSIH TOTAL
$globalCountriesList = [
    ['name' => 'Afghanistan', 'code' => 'af'], ['name' => 'Albania', 'code' => 'al'], ['name' => 'Algeria', 'code' => 'dz'], 
    ['name' => 'Andorra', 'code' => 'ad'], ['name' => 'Angola', 'code' => 'ao'], ['name' => 'Antigua and Barbuda', 'code' => 'ag'], 
    ['name' => 'Argentina', 'code' => 'ar'], ['name' => 'Armenia', 'code' => 'am'], ['name' => 'Australia', 'code' => 'au'], 
    ['name' => 'Austria', 'code' => 'at'], ['name' => 'Azerbaijan', 'code' => 'az'], ['name' => 'Bahamas', 'code' => 'bs'], 
    ['name' => 'Bahrain', 'code' => 'bh'], ['name' => 'Bangladesh', 'code' => 'bd'], ['name' => 'Barbados', 'code' => 'bb'], 
    ['name' => 'Belarus', 'code' => 'by'], ['name' => 'Belgium', 'code' => 'be'], ['name' => 'Belize', 'code' => 'bz'], 
    ['name' => 'Benin', 'code' => 'bj'], ['name' => 'Bhutan', 'code' => 'bt'], ['name' => 'Bolivia', 'code' => 'bo'], 
    ['name' => 'Bosnia and Herzegovina', 'code' => 'ba'], ['name' => 'Botswana', 'code' => 'bw'], ['name' => 'Brazil', 'code' => 'br'], 
    ['name' => 'Brunei', 'code' => 'bn'], ['name' => 'Bulgaria', 'code' => 'bg'], ['name' => 'Burkina Faso', 'code' => 'bf'], 
    ['name' => 'Burundi', 'code' => 'bi'], ['name' => 'Cabo Verde', 'code' => 'cv'], ['name' => 'Cambodia', 'code' => 'kh'], 
    ['name' => 'Cameroon', 'code' => 'cm'], ['name' => 'Canada', 'code' => 'ca'], ['name' => 'Central African Republic', 'code' => 'cf'], 
    ['name' => 'Chad', 'code' => 'td'], ['name' => 'Chile', 'code' => 'cl'], ['name' => 'China', 'code' => 'cn'], 
    ['name' => 'Colombia', 'code' => 'co'], ['name' => 'Comoros', 'code' => 'km'], ['name' => 'Congo', 'code' => 'cg'], 
    ['name' => 'Costa Rica', 'code' => 'cr'], ['name' => 'Croatia', 'code' => 'hr'], ['name' => 'Cuba', 'code' => 'cu'], 
    ['name' => 'Cyprus', 'code' => 'cy'], ['name' => 'Czechia', 'code' => 'cz'], ['name' => 'Denmark', 'code' => 'dk'], 
    ['name' => 'Djibouti', 'code' => 'dj'], ['name' => 'Dominica', 'code' => 'dm'], ['name' => 'Dominican Republic', 'code' => 'do'], 
    ['name' => 'Ecuador', 'code' => 'ec'], ['name' => 'Egypt', 'code' => 'eg'], ['name' => 'El Salvador', 'code' => 'sv'], 
    ['name' => 'Equatorial Guinea', 'code' => 'gq'], ['name' => 'Eritrea', 'code' => 'er'], ['name' => 'Estonia', 'code' => 'ee'], 
    ['name' => 'Eswatini', 'code' => 'sz'], ['name' => 'Ethiopia', 'code' => 'et'], ['name' => 'Fiji', 'code' => 'fj'], 
    ['name' => 'Finland', 'code' => 'fi'], ['name' => 'France', 'code' => 'fr'], ['name' => 'Gabon', 'code' => 'ga'], 
    ['name' => 'Gambia', 'code' => 'gm'], ['name' => 'Georgia', 'code' => 'ge'], ['name' => 'Germany', 'code' => 'de'], 
    ['name' => 'Ghana', 'code' => 'gh'], ['name' => 'Greece', 'code' => 'gr'], ['name' => 'Grenada', 'code' => 'gd'], 
    ['name' => 'Guatemala', 'code' => 'gt'], ['name' => 'Guinea', 'code' => 'gn'], ['name' => 'Guinea-Bissau', 'code' => 'gw'], 
    ['name' => 'Guyana', 'code' => 'gy'], ['name' => 'Haiti', 'code' => 'ht'], ['name' => 'Honduras', 'code' => 'hn'], 
    ['name' => 'Hong Kong', 'code' => 'hk'], ['name' => 'Hungary', 'code' => 'hu'], ['name' => 'Iceland', 'code' => 'is'], 
    ['name' => 'India', 'code' => 'in'], ['name' => 'Indonesia', 'code' => 'id'], ['name' => 'Iran', 'code' => 'ir'], 
    ['name' => 'Iraq', 'code' => 'iq'], ['name' => 'Ireland', 'code' => 'ie'], ['name' => 'Israel', 'code' => 'il'], 
    ['name' => 'Italy', 'code' => 'it'], ['name' => 'Jamaica', 'code' => 'jm'], ['name' => 'Japan', 'code' => 'jp'], 
    ['name' => 'Jordan', 'code' => 'jo'], ['name' => 'Kazakhstan', 'code' => 'kz'], ['name' => 'Kenya', 'code' => 'ke'], 
    ['name' => 'Kiribati', 'code' => 'ki'], ['name' => 'Kuwait', 'code' => 'kw'], ['name' => 'Kyrgyzstan', 'code' => 'kg'], 
    ['name' => 'Laos', 'code' => 'la'], ['name' => 'Latvia', 'code' => 'lv'], ['name' => 'Lebanon', 'code' => 'lb'], 
    ['name' => 'Lesotho', 'code' => 'ls'], ['name' => 'Liberia', 'code' => 'lr'], ['name' => 'Libya', 'code' => 'ly'], 
    ['name' => 'Liechtenstein', 'code' => 'li'], ['name' => 'Lithuania', 'code' => 'lt'], ['name' => 'Luxembourg', 'code' => 'lu'], 
    ['name' => 'Macao', 'code' => 'mo'], ['name' => 'Madagascar', 'code' => 'mg'], ['name' => 'Malawi', 'code' => 'mw'], 
    ['name' => 'Malaysia', 'code' => 'my'], ['name' => 'Maldives', 'code' => 'mv'], ['name' => 'Mali', 'code' => 'ml'], 
    ['name' => 'Malta', 'code' => 'mt'], ['name' => 'Marshall Islands', 'code' => 'mh'], ['name' => 'Mauritania', 'code' => 'mr'], 
    ['name' => 'Mauritius', 'code' => 'mu'], ['name' => 'Mexico', 'code' => 'mx'], ['name' => 'Micronesia', 'code' => 'fm'], 
    ['name' => 'Moldova', 'code' => 'md'], ['name' => 'Monaco', 'code' => 'mc'], ['name' => 'Mongolia', 'code' => 'mn'], 
    ['name' => 'Montenegro', 'code' => 'me'], ['name' => 'Morocco', 'code' => 'ma'], ['name' => 'Mozambique', 'code' => 'mz'], 
    ['name' => 'Myanmar', 'code' => 'mm'], ['name' => 'Namibia', 'code' => 'na'], ['name' => 'Nauru', 'code' => 'nr'], 
    ['name' => 'Nepal', 'code' => 'np'], ['name' => 'Netherlands', 'code' => 'nl'], ['name' => 'New Zealand', 'code' => 'nz'], 
    ['name' => 'Nicaragua', 'code' => 'ni'], ['name' => 'Niger', 'code' => 'ne'], ['name' => 'Nigeria', 'code' => 'ng'], 
    ['name' => 'North Korea', 'code' => 'kp'], ['name' => 'North Macedonia', 'code' => 'mk'], ['name' => 'Norway', 'code' => 'no'], 
    ['name' => 'Oman', 'code' => 'om'], ['name' => 'Pakistan', 'code' => 'pk'], ['name' => 'Palau', 'code' => 'pw'], 
    ['name' => 'Palestine', 'code' => 'ps'], ['name' => 'Panama', 'code' => 'pa'], ['name' => 'Papua New Guinea', 'code' => 'pg'], 
    ['name' => 'Paraguay', 'code' => 'py'], ['name' => 'Peru', 'code' => 'pe'], ['name' => 'Philippines', 'code' => 'ph'], 
    ['name' => 'Poland', 'code' => 'pl'], ['name' => 'Portugal', 'code' => 'pt'], ['name' => 'Qatar', 'code' => 'qa'], 
    ['name' => 'Romania', 'code' => 'ro'], ['name' => 'Russia', 'code' => 'ru'], ['name' => 'Rwanda', 'code' => 'rw'], 
    ['name' => 'Saint Kitts and Nevis', 'code' => 'kn'], ['name' => 'Saint Lucia', 'code' => 'lc'], ['name' => 'Samoa', 'code' => 'ws'], 
    ['name' => 'San Marino', 'code' => 'sm'], ['name' => 'Sao Tome and Principe', 'code' => 'st'], ['name' => 'Saudi Arabia', 'code' => 'sa'], 
    ['name' => 'Senegal', 'code' => 'sn'], ['name' => 'Serbia', 'code' => 'rs'], ['name' => 'Seychelles', 'code' => 'sc'], 
    ['name' => 'Sierra Leone', 'code' => 'sl'], ['name' => 'Singapore', 'code' => 'sg'], ['name' => 'Slovakia', 'code' => 'sk'], 
    ['name' => 'Slovenia', 'code' => 'si'], ['name' => 'Solomon Islands', 'code' => 'sb'], ['name' => 'Somalia', 'code' => 'so'], 
    ['name' => 'South Africa', 'code' => 'za'], ['name' => 'South Korea', 'code' => 'kr'], ['name' => 'South Sudan', 'code' => 'ss'], 
    ['name' => 'Spain', 'code' => 'es'], ['name' => 'Sri Lanka', 'code' => 'lk'], ['name' => 'Sudan', 'code' => 'sd'], 
    ['name' => 'Suriname', 'code' => 'sr'], ['name' => 'Sweden', 'code' => 'se'], ['name' => 'Switzerland', 'code' => 'ch'], 
    ['name' => 'Syria', 'code' => 'sy'], ['name' => 'Taiwan', 'code' => 'tw'], ['name' => 'Tajikistan', 'code' => 'tj'], 
    ['name' => 'Tanzania', 'code' => 'tz'], ['name' => 'Thailand', 'code' => 'th'], ['name' => 'Timor-Leste', 'code' => 'tl'], 
    ['name' => 'Togo', 'code' => 'tg'], ['name' => 'Tonga', 'code' => 'to'], ['name' => 'Trinidad and Tobago', 'code' => 'tt'], 
    ['name' => 'Tunisia', 'code' => 'tn'], ['name' => 'Turkey', 'code' => 'tr'], ['name' => 'Turkmenistan', 'code' => 'tm'], 
    ['name' => 'Tuvalu', 'code' => 'tv'], ['name' => 'Uganda', 'code' => 'ug'], ['name' => 'Ukraine', 'code' => 'ua'], 
    ['name' => 'United Arab Emirates', 'code' => 'ae'], ['name' => 'United Kingdom', 'code' => 'gb'], ['name' => 'United States', 'code' => 'us'], 
    ['name' => 'Uruguay', 'code' => 'uy'], ['name' => 'Uzbekistan', 'code' => 'uz'], ['name' => 'Vanuatu', 'code' => 'vu'], 
    ['name' => 'Venezuela', 'code' => 've'], ['name' => 'Vietnam', 'code' => 'vn'], ['name' => 'Yemen', 'code' => 'ye'], 
    ['name' => 'Zambia', 'code' => 'zm'], ['name' => 'Zimbabwe', 'code' => 'zw']
];

// ROUTE DASHBOARD (GABUNGAN UTUH)
Route::get('/dashboard', function () use ($globalCountriesList) {
    $dashboardCountries = $globalCountriesList;
    usort($dashboardCountries, function($a, $b) { return strcmp($a['name'], $b['name']); });

    $historyLogs = [
        [
            'id' => 'EXP-2026-001', 'carrier' => 'MV Blue Wave Ocean', 
            'origin' => 'Tanjung Priok (ID)', 'destination' => 'Port of Singapore (SG)', 
            'tonnage' => '45,000 Tons', 'value' => '$1,250,000', 'status' => 'Archived / Clear'
        ],
        [
            'id' => 'EXP-2026-002', 'carrier' => 'Evergreen Intercept', 
            'origin' => 'Port of Rotterdam (NL)', 'destination' => 'Shanghai Port (CN)', 
            'tonnage' => '82,500 Tons', 'value' => '$3,400,000', 'status' => 'Archived / Clear'
        ],
        [
            'id' => 'EXP-2026-003', 'carrier' => 'Pacific Titan', 
            'origin' => 'Port of LA (US)', 'destination' => 'Tokyo Bay (JP)', 
            'tonnage' => '61,200 Tons', 'value' => '$2,150,000', 'status' => 'Archived / Clear'
        ]
    ];

    $htmlContent = "
    @extends(view()->exists('layouts.app') ? 'layouts.app' : (view()->exists('welcome') ? 'welcome' : 'dashboard'))
    @section('content')
    <div class='container mx-auto px-4 py-6'>
        <h2 class='text-xl font-bold text-white mb-2 flex items-center gap-2'>
            <span class='p-2 bg-blue-500/10 text-blue-400 rounded-lg'>🛡️</span> Risk Intelligence Command Center
        </h2>
        <p class='text-xs text-gray-400 mb-6'>Overview of global logistics threats and operational security indices.</p>

        <div class='grid grid-cols-1 md:grid-cols-4 gap-4 mb-6'>
            <div class='bg-[#111827] border border-gray-800 p-4 rounded-xl'>
                <span class='text-[10px] uppercase font-bold text-gray-500 tracking-wider'>Global Countries</span>
                <div class='flex items-baseline gap-2 mt-1'>
                    <span class='text-2xl font-black text-white'>247</span>
                    <span class='text-[10px] bg-emerald-500/10 text-emerald-400 px-1.5 py-0.5 rounded border border-emerald-500/20'>Mapped</span>
                </div>
            </div>
            <div class='bg-[#111827] border border-gray-800 p-4 rounded-xl'>
                <span class='text-[10px] uppercase font-bold text-gray-500 tracking-wider'>Monitored Ports</span>
                <div class='flex items-baseline gap-2 mt-1'>
                    <span class='text-2xl font-black text-white'>1,840</span>
                    <span class='text-[10px] text-gray-400'>Hubs</span>
                </div>
            </div>
            <div class='bg-[#111827] border border-gray-800 p-4 rounded-xl'>
                <span class='text-[10px] uppercase font-bold text-gray-500 tracking-wider'>System Incidents</span>
                <div class='flex items-baseline gap-2 mt-1'>
                    <span class='text-2xl font-black text-red-500'>14</span>
                    <span class='text-[10px] bg-red-500/10 text-red-400 px-1.5 py-0.5 rounded border border-red-500/20'>Active Alerts</span>
                </div>
            </div>
            <div class='bg-[#111827] border border-gray-800 p-4 rounded-xl'>
                <span class='text-[10px] uppercase font-bold text-gray-500 tracking-wider'>Average Risk Index</span>
                <div class='flex items-baseline gap-2 mt-1'>
                    <span class='text-2xl font-black text-amber-500'>24.50</span>
                    <span class='text-[10px] bg-amber-500/10 text-amber-400 px-1.5 py-0.5 rounded border border-amber-500/20'>Medium</span>
                </div>
            </div>
        </div>

        <div class='grid grid-cols-1 lg:grid-cols-5 gap-6 mb-6'>
            <div class='lg:col-span-2 bg-[#111827] border border-gray-800 rounded-xl p-5 flex flex-col gap-4'>
                <div>
                    <h3 class='text-sm font-bold text-gray-200 uppercase tracking-wider mb-1'>Comparison Engine</h3>
                    <p class='text-[11px] text-gray-400'>Quickly compare metrics between key trading nodes.</p>
                </div>
                <div class='space-y-3 pt-2 border-t border-gray-800/60'>
                    <div>
                        <label class='text-[10px] uppercase font-bold text-gray-400 mb-1 block'>Country A</label>
                        <select class='w-full bg-gray-900 border border-gray-800 text-gray-300 rounded-lg p-2 text-xs focus:outline-none focus:border-emerald-500'>
                            @foreach(\$dashboardCountries as \$c)
                                <option value='{{ \$c[\"code\"] }}' {{ \$c[\"code\"] == 'id' ? 'selected' : '' }}>
                                    {{ strtoupper(\$c['code']) }} {{ \$c['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class='text-[10px] uppercase font-bold text-gray-400 mb-1 block'>Country B</label>
                        <select class='w-full bg-gray-900 border border-gray-800 text-gray-300 rounded-lg p-2 text-xs focus:outline-none focus:border-emerald-500'>
                            @foreach(\$dashboardCountries as \$c)
                                <option value='{{ \$c[\"code\"] }}' {{ \$c[\"code\"] == 'sg' ? 'selected' : '' }}>
                                    {{ strtoupper(\$c['code']) }} {{ \$c['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button class='w-full bg-emerald-600 font-bold text-xs text-white p-2.5 rounded-lg mt-2'>Run Analysis</button>
            </div>

            <div class='lg:col-span-3 bg-[#111827] border border-gray-800 rounded-xl p-5 flex flex-col gap-3'>
                <div class='flex justify-between items-center'>
                    <h3 class='text-sm font-bold text-gray-200 uppercase tracking-wider'>Live Supply Chain Threat Feed</h3>
                    <span class='text-[9px] bg-emerald-500/10 text-emerald-400 px-2 py-0.5 rounded-full font-bold animate-pulse'>Live Streaming</span>
                </div>
                <div class='space-y-3'>
                    <div class='bg-gray-900/60 border border-gray-800/80 p-3 rounded-lg flex justify-between items-start'>
                        <div>
                            <h4 class='text-xs font-bold text-gray-200'>Suez Canal congestion increases container freight transit delays by 48 hours</h4>
                            <span class='text-[10px] text-gray-500 block mt-1'>Source: Maritime Intelligence</span>
                        </div>
                        <div class='text-right flex-shrink-0'><span class='text-[9px] font-bold px-1.5 py-0.5 rounded bg-red-500/10 text-red-400 uppercase'>Negative</span></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABEL LOG RIWAYAT KARGO YANG MENYATU -->
        <div class='bg-[#111827] border border-gray-800 rounded-xl overflow-hidden shadow-lg'>
            <div class='bg-gray-900/50 px-4 py-3 border-b border-gray-800 flex justify-between items-center'>
                <div class='flex items-center gap-2'>
                    <span class='text-xs text-cyan-400'>🗂️</span>
                    <span class='text-[11px] uppercase font-bold text-gray-300 tracking-wider'>MANIFEST AUDIT PELAYARAN SELESAI (ARCHIVED LOGS)</span>
                </div>
                <span class='text-[9px] bg-emerald-500/10 text-emerald-400 px-2 py-0.5 rounded border border-emerald-500/20 font-mono'>🔒 Data Authenticated</span>
            </div>
            <div class='overflow-x-auto'>
                <table class='w-full text-left border-collapse'>
                    <thead>
                        <tr class='border-b border-gray-800 text-[11px] text-gray-400 uppercase tracking-wider bg-gray-900/20'>
                            <th class='p-4 font-bold'>ID Ekspedisi</th>
                            <th class='p-4 font-bold'>Nama Kapal / Carrier</th>
                            <th class='p-4 font-bold'>Pelabuhan Keberangkatan</th>
                            <th class='p-4 font-bold'>Pelabuhan Tujuan</th>
                            <th class='p-4 font-bold'>Kargo Tonase</th>
                            <th class='p-4 font-bold'>Valuasi Finansial</th>
                            <th class='p-4 font-bold text-center'>Status Pelaporan</th>
                        </tr>
                    </thead>
                    <tbody class='divide-y divide-gray-800/60 text-xs text-gray-300'>
                        @foreach(\$historyLogs as \$log)
                            <tr class='hover:bg-gray-900/40 transition'>
                                <td class='p-4 font-mono font-bold text-gray-400'>{{ \$log['id'] }}</td>
                                <td class='p-4 font-bold text-white'>{{ \$log['carrier'] }}</td>
                                <td class='p-4 text-gray-400'>{{ \$log['origin'] }}</td>
                                <td class='p-4 text-gray-400'>{{ \$log['destination'] }}</td>
                                <td class='p-4 font-semibold text-emerald-400'>{{ \$log['tonnage'] }}</td>
                                <td class='p-4 font-bold text-amber-500'>{{ \$log['value'] }}</td>
                                <td class='p-4 text-center'>
                                    <span class='px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 uppercase'>
                                        {{ \$log['status'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endsection";

    return response(Blade::render($htmlContent, compact('dashboardCountries', 'historyLogs')));
});

// ROUTE COUNTRIES
Route::get('/countries', function () use ($globalCountriesList) {
    $countries = [];
    foreach ($globalCountriesList as $item) {
        $score = crc32($item['name']) % 80 + 10; 
        if ($score > 70) { $risk = 'High Risk'; $text = 'text-red-400'; $border = 'border-red-500/20'; }
        elseif ($score > 40) { $risk = 'Medium Risk'; $text = 'text-amber-400'; $border = 'border-amber-500/20'; }
        else { $risk = 'Low Risk'; $text = 'text-emerald-400'; $border = 'border-emerald-500/20'; }
        
        $countries[] = ['name' => $item['name'], 'code' => $item['code'], 'risk' => $risk, 'score' => $score, 'text' => $text, 'border' => $border];
    }
    usort($countries, function($a, $b) { return strcmp($a['name'], $b['name']); });

    $htmlContent = "
    @extends(view()->exists('layouts.app') ? 'layouts.app' : (view()->exists('welcome') ? 'welcome' : 'dashboard'))
    @section('content')
    <div class='container mx-auto px-4 py-6'>
        <h2 class='text-xl font-bold text-white mb-2'>🌍 Country Risk Intelligence Hub</h2>
        <p class='text-xs text-gray-400 mb-6'>Displaying security status for all 247 mapped global trade entities</p>
        <div class='grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 max-h-[70vh] overflow-y-auto pr-2'>
            @foreach(\$countries as \$country)
                <div class='bg-[#111827] border border-gray-800 rounded-xl p-4 flex items-center justify-between hover:border-gray-700 transition'>
                    <div class='flex items-center gap-3'>
                        <img src='https://flagcdn.com/w40/{{ \$country[\"code\"] }}.png' class='w-8 h-6 rounded object-cover shadow-sm'>
                        <div>
                            <h3 class='text-xs font-bold text-gray-200 leading-tight'>{{ \$country['name'] }}</h3>
                            <span class='text-[9px] uppercase font-bold tracking-wider {{ \$country[\"text\"] }} mt-1 block'>{{ \$country['risk'] }}</span>
                        </div>
                    </div>
                    <div class='text-right'><span class='text-sm font-black text-gray-400'>{{ \$country['score'] }}</span></div>
                </div>
            @endforeach
        </div>
    </div>
    @endsection";
    return response(Blade::render($htmlContent, compact('countries')));
});

// ROUTE NEWS & EVENTS
Route::get('/news', function () {
    $newsList = [
        [
            'title' => 'Suez Canal Congestion Reaches Critical Levels Amid Infrastructure Delays',
            'summary' => 'More than 40 container vessels are currently bottlenecked near the southern entrance, forcing shipping lines to calculate costly re-routing schedules around Africa.',
            'category' => 'Maritime', 'sentiment' => 'Negative', 'time' => '12 mins ago', 'source' => 'Global Logistics Press',
            'image' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?auto=format&fit=crop&w=600&q=80'
        ],
        [
            'title' => 'Port of Singapore Deploys Automated AI Berth Planning System',
            'summary' => 'Next-generation AI frameworks are shrinking vessel turnaround rates by up to 18%. Terminal operations report significantly optimized container yard stacking.',
            'category' => 'Technology', 'sentiment' => 'Positive', 'time' => '1 hour ago', 'source' => 'TechLogistics Asia',
            'image' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=600&q=80'
        ]
    ];

    $htmlContent = "
    @extends(view()->exists('layouts.app') ? 'layouts.app' : (view()->exists('welcome') ? 'welcome' : 'dashboard'))
    @section('content')
    <div class='container mx-auto px-4 py-6'>
        <h2 class='text-xl font-bold text-white mb-6 flex items-center gap-2'>📰 News & Global Events Intelligence</h2>
        <div class='grid grid-cols-1 lg:grid-cols-3 gap-6'>
            <div class='lg:col-span-2 space-y-4 max-h-[75vh] overflow-y-auto pr-2'>
                @foreach(\$newsList as \$news)
                    <div class='bg-[#111827] border border-gray-800 rounded-xl flex flex-col md:flex-row gap-4 overflow-hidden hover:border-gray-700 transition p-4'>
                        <div class='w-full md:w-48 h-32 flex-shrink-0 bg-gray-900 rounded-lg overflow-hidden'>
                            <img src='{{ \$news[\"image\"] }}' class='w-full h-full object-cover opacity-80 hover:opacity-100 transition duration-300' alt='News Image'>
                        </div>
                        <div class='flex flex-col justify-between flex-1 gap-2'>
                            <div>
                                <div class='flex justify-between items-center gap-4 mb-1.5'>
                                    <span class='text-[10px] bg-blue-500/10 text-blue-400 px-2 py-0.5 rounded-full font-bold uppercase border border-blue-500/10'>{{ \$news['category'] }}</span>
                                    <span class='text-[10px] font-bold px-2 py-0.5 rounded uppercase {{ \$news[\"sentiment\"] == \"Negative\" ? \"bg-red-500/10 text-red-400 border border-red-500/20\" : \"bg-emerald-500/10 text-emerald-400 border border-emerald-500/20\" }}'>{{ \$news['sentiment'] }}</span>
                                </div>
                                <h3 class='text-xs md:text-sm font-bold text-gray-100 leading-snug hover:text-blue-400 transition cursor-pointer'>{{ \$news['title'] }}</h3>
                                <p class='text-[11px] md:text-xs text-gray-400 leading-relaxed mt-1'>{{ \$news['summary'] }}</p>
                            </div>
                            <div class='flex justify-between items-center pt-2 border-t border-gray-800/50 text-[10px] md:text-[11px] text-gray-500'>
                                <span>Source: <strong class='text-gray-400'>{{ \$news['source'] }}</strong></span>
                                <span>{{ \$news['time'] }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class='bg-[#111827] border border-gray-800 p-5 rounded-xl h-fit'>
                <h3 class='text-xs font-bold text-gray-400 uppercase tracking-wider mb-4'>Global Node Sentiment (24H)</h3>
                <div class='space-y-4'>
                    <div><div class='flex justify-between text-xs font-bold mb-1'><span class='text-emerald-400'>Positive Sentiment</span><span class='text-gray-300'>58%</span></div><div class='w-full bg-gray-900 h-2 rounded-full overflow-hidden'><div class='bg-emerald-500 h-full w-[58%]'></div></div></div>
                </div>
            </div>
        </div>
    </div>
    @endsection";

    return response(Blade::render($htmlContent, compact('newsList')));
});

// Sisa rute pelengkap aplikasi
Route::get('/risk-scores', function () { return view('risk_scores'); });
Route::get('/watchlist', function () { return view('watchlist'); });
Route::get('/compare', function () { return view('compare'); });
Route::get('/map', function () { return view('map'); });
Route::get('/economy', function () { return view('economy'); });
Route::get('/ports', function () { return view('ports'); });
Route::get('/settings', function () { return view('settings'); });
Route::get('/weather', function () { return view('weather'); });
Route::get('/api/ports', [App\Http\Controllers\Api\PortApiController::class, 'index']);