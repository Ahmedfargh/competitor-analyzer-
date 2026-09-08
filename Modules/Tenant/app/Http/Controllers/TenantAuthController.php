<?php

namespace Modules\Tenant\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TenantAuthController extends Controller
{
    /**
     * Synchronize and apply locale from request query or session.
     */
    protected function applyLocale(): void
    {
        $requestedLocale = request()->query('locale') ?? request()->query('lang');
        if ($requestedLocale && in_array($requestedLocale, ['en', 'ar'], true)) {
            session()->put('locale', $requestedLocale);
        }

        if (session()->has('locale')) {
            app()->setLocale(session()->get('locale'));
        }
    }

    /**
     * Switch language and redirect back.
     */
    public function switchLocale(string $locale): RedirectResponse
    {
        if (in_array($locale, ['en', 'ar'], true)) {
            session()->put('locale', $locale);
            app()->setLocale($locale);
        }

        return back();
    }

    /**
     * Display the tenant login form.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        $this->applyLocale();

        if (Auth::guard('tenant')->check()) {
            return redirect()->route('tenant.dashboard');
        }

        return view('tenant::auth.login', [
            'tenant' => tenant(),
        ]);
    }

    /**
     * Handle tenant user login.
     */
    public function login(Request $request): RedirectResponse
    {
        $this->applyLocale();

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::guard('tenant')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('tenant.dashboard'));
        }

        return back()->withErrors([
            'email' => __('auth.failed'),
        ])->withInput($request->only('email', 'remember'));
    }

    /**
     * Handle tenant user logout.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('tenant')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('tenant.login');
    }

    /**
     * Display tenant authenticated dashboard / home.
     */
    public function dashboard(): View
    {
        $this->applyLocale();

        return view('tenant::dashboard', [
            'tenant' => tenant(),
            'user' => Auth::guard('tenant')->user(),
        ]);
    }
}
