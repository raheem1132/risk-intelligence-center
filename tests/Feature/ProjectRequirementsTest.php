<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\User;
use App\Models\UserPreference;
use App\Models\RiskScore;
use App\Models\Watchlist;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectRequirementsTest extends TestCase
{
    use RefreshDatabase;

    public function test_required_rest_endpoints_are_available(): void
    {
        $country = Country::create([
            'name' => 'Indonesia', 'code_iso2' => 'ID', 'region' => 'Asia',
            'currency_code' => 'IDR', 'inflation_rate' => 3.2,
        ]);

        $this->getJson('/api/countries')->assertOk()->assertJsonPath('status', 'success');
        $this->getJson('/api/countries/ID')->assertOk()->assertJsonPath('data.id', $country->id);
        $this->getJson('/api/risk?country=ID')->assertOk()->assertJsonPath('status', 'success');
        $this->getJson('/api/ports')->assertOk()->assertJsonPath('status', 'success');
        $this->getJson('/api/news?country=ID')->assertOk()->assertJsonPath('status', 'success');
        $this->getJson('/api/map/ports')->assertOk()->assertJsonPath('status', 'success');
    }

    public function test_registration_creates_an_account_then_requires_login(): void
    {
        $this->post('/register', [
            'name' => 'Supply Analyst', 'email' => 'analyst@example.com', 'password' => 'password123', 'password_confirmation' => 'password123',
        ])->assertRedirect('/');

        $this->assertGuest();
        $this->assertDatabaseHas('users', ['email' => 'analyst@example.com']);
    }

    public function test_authenticated_user_can_manage_watchlist(): void
    {
        $user = User::factory()->create();
        $country = Country::create(['name' => 'Germany', 'code_iso2' => 'DE']);

        $this->actingAs($user)->post('/watchlist', ['country_id' => $country->id])->assertRedirect();
        $this->assertDatabaseHas('watchlists', ['user_id' => $user->id, 'country_id' => $country->id]);
    }

    public function test_admin_dashboard_is_protected_by_role(): void
    {
        $this->actingAs(User::factory()->create())->get('/admin')->assertForbidden();
        $this->actingAs(User::factory()->create(['is_admin' => true]))->get('/admin')->assertOk();
    }

    public function test_authenticated_user_can_update_operational_preferences(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->patch('/settings/preferences', [
            'risk_threshold'=>70,'refresh_interval'=>15,'timezone'=>'Asia/Jakarta',
            'base_currency'=>'USD','density'=>'compact','email_alerts'=>1,'weekly_digest'=>1,
        ])->assertRedirect();
        $this->assertDatabaseHas('user_preferences', [
            'user_id'=>$user->id, 'risk_threshold'=>70, 'email_alerts'=>true, 'density'=>'compact',
        ]);
        $this->assertTrue(UserPreference::whereBelongsTo($user)->first()->weekly_digest);
    }

    public function test_password_reset_pages_are_available(): void
    {
        $this->get('/forgot-password')->assertOk()->assertSee('Reset password');
        $this->get('/reset-password/test-token?email=analyst@example.com')->assertOk()->assertSee('Buat password baru');
    }

    public function test_threshold_alerts_are_dispatched_for_high_risk_watchlists(): void
    {
        config(['services.resend.key'=>'test-key','mail.from.address'=>'onboarding@resend.dev']);
        Http::fake(['api.resend.com/*'=>Http::response(['id'=>'mail_123'],200)]);
        $user = User::factory()->create();
        $country = Country::create(['name'=>'Indonesia','code_iso2'=>'ID']);
        UserPreference::create(['user_id'=>$user->id,'risk_threshold'=>65,'email_alerts'=>true]);
        Watchlist::create(['user_id'=>$user->id,'country_id'=>$country->id]);
        RiskScore::create([
            'country_id'=>$country->id, 'weather_risk'=>80, 'inflation_risk'=>70,
            'currency_risk'=>40, 'news_risk'=>75, 'total_score'=>72, 'status'=>'High Risk',
        ]);

        $this->artisan('alerts:dispatch')->assertSuccessful();
        Http::assertSent(fn ($request) => $request->url()==='https://api.resend.com/emails'
            && $request['to']===[$user->email]
            && str_contains($request['subject'], 'risk alert'));
    }

    public function test_country_report_can_render_and_export_csv(): void
    {
        Country::create(['name'=>'Indonesia','code_iso2'=>'ID','region'=>'Asia','currency_code'=>'IDR']);
        $this->get('/reports')->assertOk();
        $this->get('/reports/country/ID')->assertOk()->assertSee('Indonesia');
        $this->get('/reports/country/ID/csv')->assertOk()->assertHeader('content-type','text/csv; charset=UTF-8');
    }

    public function test_main_demo_pages_render_successfully(): void
    {
        foreach (['/dashboard','/countries','/ports','/weather','/currency','/map','/risk-scores','/compare','/news','/api-docs','/watchlist','/settings','/reports'] as $uri) {
            $this->get($uri)->assertOk();
        }
    }
}
