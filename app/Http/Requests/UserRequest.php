<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        // Both superadmin and admin can manage users
        return auth()->check() && (auth()->user()->hasRole('superadmin') || auth()->user()->hasRole('admin'));
    }

    public function rules()
    {
        $userId = $this->route('user')?->id;

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userId,
            'password' => $this->isMethod('POST')
                ? 'required|string|min:8|confirmed'
                : 'nullable|string|min:8|confirmed',
            'site_id' => 'nullable|exists:sites,id',
            'is_active' => 'boolean',
        ];

        if (auth()->user()->hasRole('superadmin')) {
            // Superadmin must choose company and role
            $rules['company_id'] = 'required|exists:companies,id';
            $rules['role'] = 'required|string|exists:roles,name';
        } else {
            // Admin cannot assign company_id or role
            // company_id is forced in controller
            unset($rules['company_id']);
            unset($rules['role']);
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'User name is required.',
            'email.required' => 'User email is required.',
            'email.email' => 'Enter a valid email address.',
            'email.unique' => 'This email is already used by another user.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'company_id.required' => 'Company is required.',
            'company_id.exists' => 'Selected company does not exist.',
            'role.required' => 'Role is required.',
            'role.exists' => 'Selected role does not exist.',
            'site_id.exists' => 'Selected site does not exist.',
        ];
    }
}
