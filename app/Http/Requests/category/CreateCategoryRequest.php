<?php

namespace App\Http\Requests\category;

use App\Http\Requests\BaseRequest;

class CreateCategoryRequest extends BaseRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:25|unique:categories,name',
            'description' => 'required|string',
            'category_id' => 'numeric|exists:categories,id',
            'category_photo' => 'required|max:20000|mimes:bmp,jpg,png,jpeg,svg',
        ];
    }
}
