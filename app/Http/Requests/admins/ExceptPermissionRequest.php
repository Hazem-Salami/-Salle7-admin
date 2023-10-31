<?php

namespace App\Http\Requests\admins;

use App\Http\Requests\BaseRequest;

class ExceptPermissionRequest extends BaseRequest
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
            'except' => 'array',
            'except.*' => 'required|integer|min:1|exists:permissions,id',
        ];
    }
}
