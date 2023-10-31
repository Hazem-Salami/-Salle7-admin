<?php

namespace App\Services\Admin;

use App\Http\Requests\admins\AddAdminRequest;
use App\Http\Requests\admins\UpdateAdminRequest;
use App\Http\Traits\ResponseTrait;
use App\Models\Admin;
use App\Models\Role;
use App\Services\BaseService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AdminService extends BaseService
{

    public function getAdmins(): Response
    {
        $admins = Admin::paginate(request('size'));
        $admins->getCollection()->transform(function ($admin) {
            $admin->role;
            return $admin;
        });
        return $this->customResponse(true, 'roles', $admins);
    }

    public function addAdmin(AddAdminRequest $request): Response
    {
        $role = Role::find($request->get('role_id'));
        if ($role == null)
            return $this->customResponse(true, 'role not found');

        $admin = $role->admin()->create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'phone_number' => $request->get('phone_number'),
            'password' => Hash::make($request->get('password')),
        ]);
        return $this->customResponse(true, 'admin created successfully', $admin);
    }

    public function updateAdmin(UpdateAdminRequest $request,
                                Admin              $admin): Response
    {
        $data = $request->all();
        $record = Admin::where('email', $data["email"])->first();

        if ($record == null || $record->id == $admin->id) {
            $admin->update([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'phone_number' => $request->get('phone_number'),
                'password' => Hash::make($request->get('password')),
                'role_id' => $request->get('role_id'),
            ]);
            return $this->customResponse(true, 'admin created successfully', $admin);
        } else {
            return $this->customResponse(false, 'The email has already been taken .');
        }
    }

    public function showAdmin(Admin $admin): Response
    {
        return $this->customResponse(true, 'admin details', $admin);
    }

    public function deleteAdmin(Admin $admin): Response
    {
        $admin->delete();
        return $this->customResponse(true, 'admin deleted successfully');
    }
}
