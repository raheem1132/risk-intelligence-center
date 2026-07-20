<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Country;
use App\Models\Port;
use App\Models\SentimentDictionary;

class GlobalSupplyChainSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Bersihkan data lama agar tidak bentrok saat seeding ulang
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Country::truncate();
        Port::truncate();
        SentimentDictionary::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Seed Kamus Kata AI Analisis Sentimen (Lexicon)
        $words = [
            ['word' => 'crisis', 'type' => 'negative'],
            ['word' => 'delay', 'type' => 'negative'],
            ['word' => 'war', 'type' => 'negative'],
            ['word' => 'disaster', 'type' => 'negative'],
            ['word' => 'inflation', 'type' => 'negative'],
            ['word' => 'blocked', 'type' => 'negative'],
            ['word' => 'strike', 'type' => 'negative'],
            ['word' => 'growth', 'type' => 'positive'],
            ['word' => 'stable', 'type' => 'positive'],
            ['word' => 'improve', 'type' => 'positive'],
            ['word' => 'smooth', 'type' => 'positive'],
            ['word' => 'efficient', 'type' => 'positive'],
            ['word' => 'boost', 'type' => 'positive'],
        ];
        foreach ($words as $w) {
            SentimentDictionary::create($w);
        }

        // 3. Data Master Negara Dunia (Format ISO resmi & basis data makro)
        $rawCountries = [
            ['AF', 'Afghanistan', 'Asia', 'AFN', 5.2], ['AL', 'Albania', 'Europe', 'ALL', 2.1],
            ['DZ', 'Algeria', 'Africa', 'DZD', 4.5], ['AD', 'Andorra', 'Europe', 'EUR', 1.2],
            ['AO', 'Angola', 'Africa', 'AOA', 12.3], ['AG', 'Antigua and Barbuda', 'Americas', 'XCD', 2.5],
            ['AR', 'Argentina', 'Americas', 'ARS', 98.4], ['AM', 'Armenia', 'Asia', 'AMD', 4.1],
            ['AU', 'Australia', 'Oceania', 'AUD', 3.6], ['AT', 'Austria', 'Europe', 'EUR', 7.8],
            ['AZ', 'Azerbaijan', 'Asia', 'AZN', 3.9], ['BS', 'Bahamas', 'Americas', 'BSD', 4.0],
            ['BH', 'Bahrain', 'Asia', 'BHD', 2.5], ['BD', 'Bangladesh', 'Asia', 'BDT', 8.2],
            ['BB', 'Barbados', 'Americas', 'BBD', 5.0], ['BY', 'Belarus', 'Europe', 'BYN', 11.0],
            ['BE', 'Belgium', 'Europe', 'EUR', 5.6], ['BZ', 'Belize', 'Americas', 'BZD', 4.2],
            ['BJ', 'Benin', 'Africa', 'XOF', 3.1], ['BT', 'Bhutan', 'Asia', 'BTN', 4.5],
            ['BO', 'Bolivia', 'Americas', 'BOB', 5.2], ['BA', 'Bosnia and Herzegovina', 'Europe', 'BAM', 3.8],
            ['BW', 'Botswana', 'Africa', 'BWP', 6.0], ['BR', 'Brazil', 'Americas', 'BRL', 4.6],
            ['BN', 'Brunei', 'Asia', 'BND', 2.1], ['BG', 'Bulgaria', 'Europe', 'BGN', 6.4],
            ['BF', 'Burkina Faso', 'Africa', 'XOF', 14.2], ['BI', 'Burundi', 'Africa', 'BIF', 18.0],
            ['KH', 'Cambodia', 'Asia', 'KHR', 3.0], ['CM', 'Cameroon', 'Africa', 'XAF', 6.2],
            ['CA', 'Canada', 'Americas', 'CAD', 3.1], ['CV', 'Cape Verde', 'Africa', 'CVE', 5.4],
            ['CF', 'Central African Republic', 'Africa', 'XAF', 10.5], ['TD', 'Chad', 'Africa', 'XAF', 9.1],
            ['CL', 'Chile', 'Americas', 'CLP', 7.2], ['CN', 'China', 'Asia', 'CNY', 2.0],
            ['CO', 'Colombia', 'Americas', 'COP', 10.2], ['KM', 'Comoros', 'Africa', 'KMF', 4.0],
            ['CG', 'Congo', 'Africa', 'XAF', 5.0], ['CR', 'Costa Rica', 'Americas', 'CRC', 4.8],
            ['HR', 'Croatia', 'Europe', 'EUR', 6.0], ['CU', 'Cuba', 'Americas', 'CUP', 40.0],
            ['CY', 'Cyprus', 'Europe', 'EUR', 3.2], ['CZ', 'Czech Republic', 'Europe', 'CZK', 8.5],
            ['DK', 'Denmark', 'Europe', 'DKK', 3.4], ['DJ', 'Djibouti', 'Africa', 'DJF', 4.2],
            ['DM', 'Dominica', 'Americas', 'XCD', 3.8], ['DO', 'Dominican Republic', 'Americas', 'DOP', 6.1],
            ['EC', 'Ecuador', 'Americas', 'USD', 3.4], ['EG', 'Egypt', 'Africa', 'EGP', 24.1],
            ['SV', 'El Salvador', 'Americas', 'USD', 2.8], ['GQ', 'Equatorial Guinea', 'Africa', 'XAF', 5.2],
            ['ER', 'Eritrea', 'Africa', 'ERN', 8.0], ['EE', 'Estonia', 'Europe', 'EUR', 4.2],
            ['ET', 'Ethiopia', 'Africa', 'ETB', 28.5], ['FJ', 'Fiji', 'Oceania', 'FJD', 4.1],
            ['FI', 'Finland', 'Europe', 'EUR', 2.5], ['FR', 'France', 'Europe', 'EUR', 4.8],
            ['GA', 'Gabon', 'Africa', 'XAF', 4.0], ['GM', 'Gambia', 'Africa', 'GMD', 12.1],
            ['GE', 'Georgia', 'Asia', 'GEL', 6.0], ['DE', 'Germany', 'Europe', 'EUR', 5.9],
            ['GH', 'Ghana', 'Africa', 'GHS', 26.4], ['GR', 'Greece', 'Europe', 'EUR', 3.0],
            ['GD', 'Grenada', 'Americas', 'XCD', 2.9], ['GT', 'Guatemala', 'Americas', 'GTQ', 4.5],
            ['GN', 'Guinea', 'Africa', 'GNF', 9.2], ['GW', 'Guinea-Bissau', 'Africa', 'XOF', 6.1],
            ['GY', 'Guyana', 'Americas', 'GYD', 1.5], ['HT', 'Haiti', 'Americas', 'HTG', 15.0],
            ['HN', 'Honduras', 'Americas', 'HNL', 6.4], ['HU', 'Hungary', 'Europe', 'HUF', 12.2],
            ['IS', 'Iceland', 'Europe', 'ISK', 6.1], ['IN', 'India', 'Asia', 'INR', 5.2],
            ['ID', 'Indonesia', 'Asia', 'IDR', 4.3], ['IR', 'Iran', 'Asia', 'IRR', 35.0],
            ['IQ', 'Iraq', 'Asia', 'IQD', 5.0], ['IE', 'Ireland', 'Europe', 'EUR', 3.9],
            ['IL', 'Israel', 'Asia', 'ILS', 4.1], ['IT', 'Italy', 'Europe', 'EUR', 5.3],
            ['JM', 'Jamaica', 'Americas', 'JMD', 5.8], ['JP', 'Japan', 'Asia', 'JPY', 2.8],
            ['JO', 'Jordan', 'Asia', 'JOD', 3.0], ['KZ', 'Kazakhstan', 'Asia', 'KZT', 10.2],
            ['KE', 'Kenya', 'Africa', 'KES', 6.8], ['KI', 'Kiribati', 'Oceania', 'AUD', 2.5],
            ['KP', 'North Korea', 'Asia', 'KPW', 5.0], ['KR', 'South Korea', 'Asia', 'KRW', 3.1],
            ['KW', 'Kuwait', 'Asia', 'KWD', 2.5], ['KG', 'Kyrgyzstan', 'Asia', 'KGS', 10.0],
            ['LA', 'Laos', 'Asia', 'LAK', 22.0], ['LV', 'Latvia', 'Europe', 'EUR', 4.0],
            ['LB', 'Lebanon', 'Asia', 'LBP', 150.0], ['LS', 'Lesotho', 'Africa', 'LSL', 6.5],
            ['LR', 'Liberia', 'Africa', 'LRD', 8.2], ['LY', 'Libya', 'Africa', 'LYD', 4.0],
            ['LI', 'Liechtenstein', 'Europe', 'CHF', 1.0], ['LT', 'Lithuania', 'Europe', 'EUR', 3.8],
            ['LU', 'Luxembourg', 'Europe', 'EUR', 2.9], ['MK', 'North Macedonia', 'Europe', 'MKD', 5.0],
            ['MG', 'Madagascar', 'Africa', 'MGA', 8.1], ['MW', 'Malawi', 'Africa', 'MWK', 20.2],
            ['MY', 'Malaysia', 'Asia', 'MYR', 3.4], ['MV', 'Maldives', 'Asia', 'MVR', 2.3],
            ['ML', 'Mali', 'Africa', 'XOF', 9.4], ['MT', 'Malta', 'Europe', 'EUR', 4.1],
            ['MH', 'Marshall Islands', 'Oceania', 'USD', 2.0], ['MR', 'Mauritania', 'Africa', 'MRU', 6.2],
            ['MU', 'Mauritius', 'Africa', 'MUR', 5.0], ['MX', 'Mexico', 'Americas', 'MXN', 4.8],
            ['FM', 'Micronesia', 'Oceania', 'USD', 1.8], ['MD', 'Moldova', 'Europe', 'MDL', 9.5],
            ['MC', 'Monaco', 'Europe', 'EUR', 1.5], ['MN', 'Mongolia', 'Asia', 'MNT', 8.0],
            ['ME', 'Montenegro', 'Europe', 'EUR', 4.5], ['MA', 'Morocco', 'Africa', 'MAD', 3.9],
            ['MZ', 'Mozambique', 'Africa', 'MZN', 7.1], ['MM', 'Myanmar', 'Asia', 'MMK', 15.0],
            ['NA', 'Namibia', 'Africa', 'NAD', 5.4], ['NR', 'Nauru', 'Oceania', 'AUD', 2.0],
            ['NP', 'Nepal', 'Asia', 'NPR', 6.5], ['NL', 'Netherlands', 'Europe', 'EUR', 3.2],
            ['NZ', 'New Zealand', 'Oceania', 'NZD', 4.0], ['NI', 'Nicaragua', 'Americas', 'NIO', 6.0],
            ['NE', 'Niger', 'Africa', 'XOF', 5.5], ['NG', 'Nigeria', 'Africa', 'NGN', 24.2],
            ['NO', 'Norway', 'Europe', 'NOK', 4.5], ['OM', 'Oman', 'Asia', 'OMR', 2.1],
            ['PK', 'Pakistan', 'Asia', 'PKR', 28.0], ['PW', 'Palau', 'Oceania', 'USD', 2.2],
            ['PA', 'Panama', 'Americas', 'PAB', 2.0], ['PG', 'Papua New Guinea', 'Oceania', 'PGK', 4.5],
            ['PY', 'Paraguay', 'Americas', 'PYG', 4.0], ['PE', 'Peru', 'Americas', 'PEN', 3.5],
            ['PH', 'Philippines', 'Asia', 'PHP', 5.4], ['PL', 'Poland', 'Europe', 'PLN', 6.2],
            ['PT', 'Portugal', 'Europe', 'EUR', 2.8], ['QA', 'Qatar', 'Asia', 'QAR', 2.0],
            ['RO', 'Romania', 'Europe', 'RON', 5.8], ['RU', 'Russia', 'Europe', 'RUB', 7.4],
            ['RW', 'Rwanda', 'Africa', 'RWF', 8.0], ['KN', 'Saint Kitts and Nevis', 'Americas', 'XCD', 2.2],
            ['LC', 'Saint Lucia', 'Americas', 'XCD', 3.0], ['VC', 'Saint Vincent', 'Americas', 'XCD', 3.5],
            ['WS', 'Samoa', 'Oceania', 'WST', 4.1], ['SM', 'San Marino', 'Europe', 'EUR', 2.0],
            ['ST', 'Sao Tome and Principe', 'Africa', 'STN', 10.2], ['SA', 'Saudi Arabia', 'Asia', 'SAR', 2.5],
            ['SN', 'Senegal', 'Africa', 'XOF', 5.0], ['RS', 'Serbia', 'Europe', 'RSD', 6.5],
            ['SC', 'Seychelles', 'Africa', 'SCR', 3.0], ['SL', 'Sierra Leone', 'Africa', 'SLL', 40.0],
            ['SG', 'Singapore', 'Asia', 'SGD', 1.0], ['SK', 'Slovakia', 'Europe', 'EUR', 5.5],
            ['SI', 'Slovenia', 'Europe', 'EUR', 4.2], ['SB', 'Solomon Islands', 'Oceania', 'SBD', 4.5],
            ['SO', 'Somalia', 'Africa', 'SOS', 6.0], ['ZA', 'South Africa', 'Africa', 'ZAR', 5.4],
            ['SS', 'South Sudan', 'Africa', 'SSP', 30.0], ['ES', 'Spain', 'Europe', 'EUR', 3.1],
            ['LK', 'Sri Lanka', 'Asia', 'LKR', 12.0], ['SD', 'Sudan', 'Africa', 'SDG', 60.0],
            ['SR', 'Suriname', 'Americas', 'SRD', 45.0], ['SZ', 'Eswatini', 'Africa', 'SZL', 5.8],
            ['SE', 'Sweden', 'Europe', 'SEK', 4.1], ['CH', 'Switzerland', 'Europe', 'CHF', 1.6],
            ['SY', 'Syria', 'Asia', 'SYP', 80.0], ['TW', 'Taiwan', 'Asia', 'TWD', 2.0],
            ['TJ', 'Tajikistan', 'Asia', 'TJS', 7.2], ['TZ', 'Tanzania', 'Africa', 'TZS', 4.8],
            ['TH', 'Thailand', 'Asia', 'THB', 2.5], ['TG', 'Togo', 'Africa', 'XOF', 6.0],
            ['TO', 'Tonga', 'Oceania', 'TOP', 5.0], ['TT', 'Trinidad and Tobago', 'Americas', 'TTD', 4.2],
            ['TN', 'Tunisia', 'Africa', 'TND', 9.1], ['TR', 'Turkey', 'Asia', 'TRY', 65.0],
            ['TM', 'Turkmenistan', 'Asia', 'TMT', 8.5], ['TV', 'Tuvalu', 'Oceania', 'AUD', 3.2],
            ['UG', 'Uganda', 'Africa', 'UGX', 5.2], ['UA', 'Ukraine', 'Europe', 'UAH', 15.0],
            ['AE', 'United Arab Emirates', 'Asia', 'AED', 2.8], ['GB', 'United Kingdom', 'Europe', 'GBP', 4.2],
            ['US', 'United States', 'Americas', 'USD', 3.2], ['UY', 'Uruguay', 'Americas', 'UYU', 6.8],
            ['UZ', 'Uzbekistan', 'Asia', 'UZS', 9.2], ['VU', 'Vanuatu', 'Oceania', 'VUV', 4.0],
            ['VE', 'Venezuela', 'Americas', 'VES', 200.0], ['VN', 'Vietnam', 'Asia', 'VND', 3.2],
            ['YE', 'Yemen', 'Asia', 'YER', 25.0], ['ZM', 'Zambia', 'Africa', 'ZMW', 12.5],
            ['ZW', 'Zimbabwe', 'Africa', 'ZWL', 100.0]
        ];

        $index = 1;
        foreach ($rawCountries as $cData) {
            $population = rand(2, 1400) * 1000000;
            $gdp = $population * rand(2000, 55000);

            $country = Country::create([
                'name' => $cData[1],
                'code_iso2' => $cData[0],
                'region' => $cData[2],
                'currency_code' => $cData[3],
                'language' => 'Official Language',
                'population' => $population,
                'gdp' => $gdp,
                'inflation_rate' => $cData[4],
            ]);

            $lat = rand(-35, 55) + (rand(0, 999) / 1000);
            $lng = rand(-100, 135) + (rand(0, 999) / 1000);

            Port::create([
                'country_id' => $country->id,
                'port_name' => 'Gateway Port of ' . $country->name,
                'port_code' => $country->code_iso2 . 'PRT',
                'latitude' => $lat,
                'longitude' => $lng,
            ]);

            $index++;
        }

        // 4. JAMINAN UTUH 2 KARAKTER TANPA DUPLIKAT
        // Menggunakan nomor urut 10-99 langsung, dipadu huruf acak non-ISO jika butuh lebih.
        $startNumber = 10;
        for ($i = $index; $i <= 255; $i++) {
            $code = (string)$startNumber; // Mengonversi angka 10, 11, 12... jadi kode string 2 karakter

            $country = Country::create([
                'name' => 'Territory Hub Zone ' . $i,
                'code_iso2' => $code,
                'region' => 'Global Logistics Zone',
                'currency_code' => 'USD',
                'language' => 'English',
                'population' => 500000,
                'gdp' => 1500000000,
                'inflation_rate' => 2.5,
            ]);

            Port::create([
                'country_id' => $country->id,
                'port_name' => 'Logistics Depot ' . $code,
                'port_code' => $code . 'DPT',
                'latitude' => rand(-10, 40),
                'longitude' => rand(10, 100),
            ]);

            $startNumber++;
        }
    }
}