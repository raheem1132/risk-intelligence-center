<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\TransactionalMailService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    public function request(): View
    {
        return view('auth.forgot-password');
    }

    public function email(Request $request, TransactionalMailService $mailer): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);
        $user = User::where('email', $request->string('email'))->first();
        if (! $user) return back()->withErrors(['email' => __(Password::INVALID_USER)]);

        try {
            $token = Password::broker()->createToken($user);
            $url = route('password.reset', ['token'=>$token, 'email'=>$user->email]);
            $mailer->send($user->email, 'Reset password SupplyGuard', "<h2>Reset password</h2><p>Klik tautan berikut untuk membuat password baru. Tautan ini memiliki masa berlaku terbatas.</p><p><a href=\"{$url}\">Reset password SupplyGuard</a></p><p>Jika Anda tidak meminta reset, abaikan email ini.</p>");
            return back()->with('status', __(Password::RESET_LINK_SENT));
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withErrors(['email' => $exception->getMessage()]);
        }
    }

    public function reset(Request $request, string $token): View
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->query('email')]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $status = Password::reset($data, function (User $user, string $password): void {
            $user->forceFill(['password' => Hash::make($password), 'remember_token' => Str::random(60)])->save();
            event(new PasswordReset($user));
        });

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }
}
