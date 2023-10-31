<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Http\Requests\admins\AddAdminRequest;
use App\Http\Requests\admins\RoleRequest;
use App\Http\Requests\admins\UpdateAdminRequest;
use App\Models\Admin;
use App\Models\Role;
use App\Services\Admin\AdminService;
use Illuminate\Http\Response;

class AdminsController extends Controller
{
    /**
     * @var AdminService
     */
    protected AdminService $adminService;

    // singleton pattern, service container
    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    /**
     * @return Response
     */
    public function index(): Response
    {
        return $this->adminService->getAdmins();
    }

    /**
     * @param AddAdminRequest $request
     * @return Response
     */
    public function store(AddAdminRequest $request): Response
    {
        return $this->adminService->addAdmin($request);
    }

    /**
     * @param UpdateAdminRequest $request
     * @param Admin $admin
     * @return Response
     */
    public function update(UpdateAdminRequest $request,
                           Admin $admin): Response
    {
        return $this->adminService->updateAdmin($request, $admin);
    }

    /**
     * @param Admin $admin
     * @return Response
     */
    public function show(Admin $admin): Response
    {
        return $this->adminService->showAdmin($admin);
    }

    /**
     * @param Admin $admin
     * @return Response
     */
    public function delete(Admin $admin): Response
    {
        return $this->adminService->deleteAdmin($admin);
    }
}
