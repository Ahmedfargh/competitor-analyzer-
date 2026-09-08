<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Modules\Admin\Http\Requests\Contracts\AdminLoginRequestInterface;
use Modules\Admin\Services\Contracts\ActivityLogServiceInterface;

class AdminAuthController extends Controller
{
    public function __construct(
        protected ActivityLogServiceInterface $activityLogService
    ) {}

    /**
     * Show the admin login form.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin::auth.login');
    }

    /**
     * Handle an admin login request.
     */
    public function login(AdminLoginRequestInterface $request): RedirectResponse
    {
        $credentials = $request->getCredentials();
        $remember = $request->isRemember();

        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            request()->session()->regenerate();

            $admin = Auth::guard('admin')->user();
            $this->activityLogService->log(
                action: 'admin.login',
                description: "Admin '{$admin->name}' ({$admin->email}) logged in successfully."
            );

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => __('auth.failed'),
        ])->withInput($request->only('email', 'remember'));
    }

    /**
     * Log the admin out.
     */
    public function logout(Request $request): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();
        if ($admin) {
            $this->activityLogService->log(
                action: 'admin.logout',
                description: "Admin '{$admin->name}' logged out."
            );
        }

        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
