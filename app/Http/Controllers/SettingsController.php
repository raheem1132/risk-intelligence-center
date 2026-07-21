<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\NewsCache;
use App\Models\Port;
use App\Models\RiskScore;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(Request $request): View
    {
        return view('settings', [
            'preferences' => array_merge(['risk_threshold'=>65,'refresh_interval'=>10,'timezone'=>'Asia/Jakarta','base_currency'=>'USD','density'=>'comfortable','email_alerts'=>true,'browser_alerts'=>false,'weekly_digest'=>true], $request->session()->get('preferences', [])),
            'integrations' => [
                ['name'=>'GNews Intelligence','description'=>'Realtime news & sentiment','ready'=>filled(config('services.gnews.key')),'label'=>filled(config('services.gnews.key'))?'Configured':'API key required'],
                ['name'=>'Open-Meteo Weather','description'=>'Weather and hazard snapshots','ready'=>true,'label'=>'Public API ready'],
                ['name'=>'Exchange Rate API','description'=>'Global USD conversion feed','ready'=>true,'label'=>'Public API ready'],
                ['name'=>'Country Data Registry','description'=>'ISO country master data','ready'=>Country::count() >= 200,'label'=>Country::count().' records loaded'],
            ],
            'systemStats' => ['countries'=>Country::count(),'ports'=>Port::count(),'risks'=>RiskScore::count(),'news'=>NewsCache::count()],
        ]);
    }

    public function profile(Request $request): RedirectResponse
    {
        $user=$request->user();
        $data=$request->validate(['name'=>['required','string','max:255'],'email'=>['required','email','max:255',Rule::unique('users','email')->ignore($user->id)]]);
        $user->update($data);
        return back()->with('success','Profil pengguna berhasil diperbarui.');
    }

    public function preferences(Request $request): RedirectResponse
    {
        $data=$request->validate(['risk_threshold'=>['required','integer','between:35,90'],'refresh_interval'=>['required','integer',Rule::in([5,10,15,30,60])],'timezone'=>['required',Rule::in(['Asia/Jakarta','Asia/Singapore','UTC','Europe/London','America/New_York'])],'base_currency'=>['required',Rule::in(['USD','EUR','IDR','SGD','GBP'])],'density'=>['required',Rule::in(['comfortable','compact'])],'email_alerts'=>['nullable','boolean'],'browser_alerts'=>['nullable','boolean'],'weekly_digest'=>['nullable','boolean']]);
        foreach(['email_alerts','browser_alerts','weekly_digest'] as $key)$data[$key]=$request->boolean($key);
        $request->session()->put('preferences',$data);
        return back()->with('success','Preferensi operasional berhasil disimpan.');
    }

    public function password(Request $request): RedirectResponse
    {
        $data=$request->validate(['current_password'=>['required','current_password'],'password'=>['required','confirmed',Password::min(8)->letters()->numbers()]]);
        $request->user()->update(['password'=>Hash::make($data['password'])]);
        return back()->with('success','Password berhasil diperbarui.');
    }
}
