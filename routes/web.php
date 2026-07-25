<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\EconomyController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\PasswordResetController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::view('/', 'login')->name('login');
Route::view('/register', 'register')->name('register');
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [PasswordResetController::class, 'request'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'email'])->middleware('throttle:5,1')->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'reset'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])->name('password.update');
});

Route::post('/register', function (Request $request) {
    $data = $request->validate(['name'=>['required','string','max:255'],'email'=>['required','email','max:255','unique:users,email'],'password'=>['required','string','min:8','confirmed']]);
    User::create($data);

    return redirect()->route('login')->with('status', 'Account created successfully. Please sign in to continue.');
});
Route::post('/login', function (Request $request) {
    $credentials=$request->validate(['email'=>['required','email'],'password'=>['required','string']]);
    if(!Auth::attempt($credentials,$request->boolean('remember'))) return back()->withErrors(['email'=>'Email atau password salah.'])->onlyInput('email');
    $request->session()->regenerate(); return redirect()->intended('/dashboard');
});
Route::post('/logout', function(Request $request){Auth::logout();$request->session()->invalidate();$request->session()->regenerateToken();return redirect('/');})->name('logout');

Route::get('/dashboard',[PortalController::class,'dashboard'])->name('dashboard');
Route::get('/countries',[PortalController::class,'countries'])->name('countries');
Route::get('/weather',[PortalController::class,'weather'])->name('weather');
Route::get('/economy',[EconomyController::class,'index'])->name('economy.index');
Route::get('/currency',[CurrencyController::class,'index'])->name('currency.index');
Route::get('/news',[PortalController::class,'news'])->name('news');
Route::get('/ports',[PortalController::class,'ports'])->name('ports.index');
Route::get('/map',[PortalController::class,'map'])->name('map');
Route::get('/risk-scores',[PortalController::class,'risks'])->name('risk');
Route::view('/compare','compare')->name('compare');
Route::get('/settings',[SettingsController::class,'index'])->name('settings');
Route::get('/api-docs',[PortalController::class,'docs'])->name('api.docs');
Route::get('/reports',[ReportController::class,'index'])->name('reports.index');
Route::get('/reports/country/{code}',[ReportController::class,'show'])->name('reports.country');
Route::get('/reports/country/{code}/csv',[ReportController::class,'csv'])->name('reports.country.csv');

Route::get('/watchlist',[WatchlistController::class,'index'])->name('watchlist');
Route::middleware('auth')->group(function(){
    Route::patch('/settings/profile',[SettingsController::class,'profile'])->name('settings.profile');
    Route::patch('/settings/preferences',[SettingsController::class,'preferences'])->name('settings.preferences');
    Route::patch('/settings/password',[SettingsController::class,'password'])->name('settings.password');
    Route::post('/watchlist',[WatchlistController::class,'store'])->name('watchlist.store');
    Route::delete('/watchlist/{watchlist}',[WatchlistController::class,'destroy'])->name('watchlist.destroy');
    Route::prefix('admin')->name('admin.')->group(function(){
        Route::get('/',[AdminController::class,'index'])->name('index');
        Route::post('/articles',[AdminController::class,'storeArticle'])->name('articles.store');
        Route::delete('/articles/{article}',[AdminController::class,'destroyArticle'])->name('articles.destroy');
        Route::delete('/ports/{port}',[AdminController::class,'destroyPort'])->name('ports.destroy');
    });
});
