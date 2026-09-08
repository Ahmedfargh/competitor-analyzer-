<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Modules\Admin\Services\Contracts\AdminUserServiceInterface;

class AdminUserController extends Controller
{
    public function __construct(
        protected AdminUserServiceInterface $adminUserService
    ) {}

    public function index(): View
    {
        return view('admin::users.index');
    }
}
