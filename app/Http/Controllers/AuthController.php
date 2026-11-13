<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', strtolower($credentials['email']))->first();
        if ($user && ! $user->is_active) {
            return back()->withErrors([
                'email' => 'Your account has been deactivated by an administrator.',
            ])->onlyInput('email');
        }

        if (Auth::attempt(['email' => strtolower($credentials['email']), 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();
            Auth::user()->update(['last_login_at' => now()]);

            if (Auth::user()->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'))->with('success', 'Welcome back to the SAVAGEXO Atelier Command Center.');
            }

            return redirect()->intended(route('account.dashboard'))->with('success', 'Welcome back to SAVAGEXO.');
        }

        return back()->withErrors([
            'email' => 'The credentials provided do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'email', 'max:180', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
            'is_active' => true,
            'last_login_at' => now(),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('account.dashboard')->with('success', 'Welcome to SAVAGEXO CLOTHINGS. Your private client profile is active.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been signed out.');
    }

    public function showForgotPassword(): View
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', strtolower($validated['email']))->first();
        $plainToken = Str::random(48);

        if ($user) {
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                [
                    'token' => Hash::make($plainToken),
                    'created_at' => now(),
                ]
            );
        }

        return back()
            ->with('success', 'If an account matches that address, a password reset link has been generated.')
            ->with('dev_reset_link', $user ? route('password.reset', ['token' => $plainToken, 'email' => $user->email]) : null);
    }

    public function showResetPassword(Request $request, string $token): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', strtolower($validated['email']))
            ->first();

        if (! $record || ! Hash::check($validated['token'], $record->token)) {
            return back()->withErrors(['email' => 'This password reset token is invalid or has expired.']);
        }

        $user = User::where('email', strtolower($validated['email']))->firstOrFail();
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        DB::table('password_reset_tokens')->where('email', $user->email)->delete();

        Auth::login($user);

        return redirect()->route('account.dashboard')->with('success', 'Your password has been updated.');
    }
}
