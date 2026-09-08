<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Modules\Admin\Services\Contracts\RoleServiceInterface;

class RoleController extends Controller
{
    public function __construct(
        protected RoleServiceInterface $roleService
    ) {}

    public function index(): View
    {
        return view('admin::roles.index');
    }
}
