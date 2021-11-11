<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class AdminRequest extends FormRequest
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
        $rules = [
            'name' => 'bail|required',
            'email' => ['bail', 'required', 'email', 'unique:admins'],
        ];

        if ($this->isMethod('post')) {
            $rules['role_ids'] = ['required', 'array'];
            $rules['password'] = 'bail|required|min:6|max:16';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => __('请输入管理员昵称'),
            'email.required' => __('请输入邮箱'),
            'email.email' => __('请输入合法邮箱'),
            'email.unique' => __('邮箱已经存在'),
            'password.required' => __('请输入密码'),
            'password.min' => __('密码长度不能少于:size个字符', ['size' => 6]),
            'password.max' => __('密码长度不能多于:size个字符', ['size' => 16]),
            'password.confirmed' => __('两次输入密码不一致'),
        ];
    }

    /**
     * @return array
     */
    public function filldata()
    {
        $data = [
            'name' => $this->input('name'),
            'status' => $this->input('status', null) == null ? 1 : 0,
        ];

        // 编辑
        $this->input('password') && $data['password'] = Hash::make($this->input('password'));

        if ($this->isMethod('post')) {
            $data['email'] = $this->input('email');
        }

        return $data;
    }
}