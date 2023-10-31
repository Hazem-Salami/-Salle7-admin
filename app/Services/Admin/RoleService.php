<?php

namespace App\Services\Admin;

use App\Http\Requests\admins\ExceptPermissionRequest;
use App\Http\Requests\admins\RoleAssignmentRequest;
use App\Http\Requests\admins\RoleRequest;
use App\Http\Requests\admins\UpdateRoleRequest;
use App\Http\Traits\ResponseTrait;
use App\Models\Permission;
use App\Models\Role;
use App\Services\BaseService;
use Illuminate\Http\Response;

class RoleService extends BaseService
{
    public function getRoles(): Response
    {
        if (request('isPaginate') == 1)
            $roles = Role::paginate(request('size'));
        else {
            $roles = Role::all();
        }
        return $this->customResponse(true, 'roles', $roles);
    }

    public function getPermissions(): Response
    {
        return $this->customResponse(true, 'permissions', Permission::all());
    }

    public function getPermissionsExcept(ExceptPermissionRequest $request): Response
    {
        if ($request->has('except')) {
            $except = $request->get('except');
            $permissions = Permission::whereNotIn('id', $except)->get();
        } else {
            $permissions = Permission::all();
        }
        return $this->customResponse(true, 'permissions except some elements', $permissions);
    }

    public function addRole(RoleRequest $request): Response
    {
        $role = Role::create([
            'name' => $request->get('name')
        ]);
        return $this->customResponse(true, 'role created successfully', $role);
    }

    public function updateRole(UpdateRoleRequest $request,
                               Role              $role): Response
    {
        $name = $request->get('name');
        $record = Role::where('name', $name)->first();

        if ($record == null || $record->id == $role->id) {
            $role->update([
                'name' => $name
            ]);
            return $this->customResponse(true, 'role created successfully', $role);
        } else {
            return $this->customResponse(false, 'The name has already been taken .');
        }
    }

    public function showRole(Role $role): Response
    {
        $role->permissions;
        return $this->customResponse(true, 'role details', $role);
    }

    public function deleteRole(Role $role): Response
    {
        $role->delete();
        return $this->customResponse(true, 'role deleted successfully');
    }

    public function rolePermissionAssignment(RoleAssignmentRequest $request,
                                             Role                  $role): Response
    {
        $permissionsToAdd = $request->get('permission');
        $permissionsToDelete = $request->get('delete_permission');
        $rolePermissions = $role->permissions;
        $permissions = [];

        foreach ($rolePermissions as $rolePermission) {
            $permissions[] = $rolePermission->pivot->permission_id;
        }

        if ($permissionsToAdd !== null && count($permissionsToAdd) >= 0)
            foreach ($permissionsToAdd as $permission) {
                if (!in_array($permission, $permissions))
                    $role->permissions()->attach($permission);
            }

        if ($permissionsToDelete !== null && count($permissionsToDelete) >= 0)
            foreach ($permissionsToDelete as $permission) {
                if (in_array($permission, $permissions))
                    $role->permissions()->detach($permission);
            }
        $role->save();
        return $this->customResponse(true, 'role assignment action success', $role->permissions);
    }
}
