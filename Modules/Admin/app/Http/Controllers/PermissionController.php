<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Modules\Admin\Services\Contracts\PermissionServiceInterface;

class PermissionController extends Controller
{
    public function __construct(
        protected PermissionServiceInterface $permissionService
    ) {}

    public function index(): View
    {
        return view('admin::permissions.index');
    }
}
