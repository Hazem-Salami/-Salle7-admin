<?php

namespace App\Http\Requests\admins;

use App\Http\Requests\BaseRequest;

class AddAdminRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|min:3|max:35',
            'email' => 'required|string|min:5|max:35|email|unique:admins,email',
            'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:admins,phone_number',
            'password' => 'required|string|min:3|max:35',
        ];
    }
}
