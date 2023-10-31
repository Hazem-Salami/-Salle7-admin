<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Http\Requests\admins\ExceptPermissionRequest;
use App\Http\Requests\admins\RoleAssignmentRequest;
use App\Http\Requests\admins\RoleRequest;
use App\Http\Requests\admins\UpdateRoleRequest;
use App\Models\Role;
use App\Services\Admin\RoleService;
use Illuminate\Http\Response;

class RolesController extends Controller
{
    /**
     * @var RoleService
     */
    protected RoleService $roleService;

    // singleton pattern, service container
    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * @return Response
     */
    public function index(): Response
    {
        return $this->roleService->getRoles();
    }

    /**
     * @return Response
     */
    public function getPermissions(): Response
    {
        return $this->roleService->getPermissions();
    }

    /**
     * @param ExceptPermissionRequest $request
     * @return Response
     */
    public function getPermissionsExcept(ExceptPermissionRequest $request): Response
    {
        return $this->roleService->getPermissionsExcept($request);
    }

    /**
     * @param RoleRequest $request
     * @return Response
     */
    public function store(RoleRequest $request): Response
    {
        return $this->roleService->addRole($request);
    }

    /**
     * @param UpdateRoleRequest $request
     * @param Role $role
     * @return Response
     */
    public function update(UpdateRoleRequest $request,
                           Role        $role): Response
    {
        return $this->roleService->updateRole($request, $role);
    }

    /**
     * @param Role $role
     * @return Response
     */
    public function show(Role $role): Response
    {
        return $this->roleService->showRole($role);
    }

    /**
     * @param Role $role
     * @return Response
     */
    public function delete(Role $role): Response
    {
        return $this->roleService->deleteRole($role);
    }

    /**
     * @param RoleAssignmentRequest $request
     * @param Role $role
     * @return Response
     */
    public function rolePermissionAssignment(RoleAssignmentRequest $request,
                                             Role                  $role): Response
    {
        return $this->roleService->rolePermissionAssignment($request, $role);
    }
}
