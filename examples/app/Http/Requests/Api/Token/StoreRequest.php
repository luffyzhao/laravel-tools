<?php

namespace App\Http\Requests\Api\Token;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'email'    => ['required',],
            'password' => ['required', 'string', 'between:6,16'],
        ];
    }

    public function attributes()
    {
        return [
            'name' => '用户名',
            'password' => '登录密码'
        ];
    }
}
