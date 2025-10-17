<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only superadmin can create/update companies
        return auth()->user()->hasRole('superadmin');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email,' . $this->company?->id,
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'sector' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

            // New: domains linked to this company
            'domains' => 'nullable|array',
            'domains.*' => 'exists:domains,id',
        ];

        return $rules;
    }

    /**
     * Custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Company name is required.',
            'email.required' => 'Company email is required.',
            'email.email' => 'Enter a valid email address.',
            'email.unique' => 'This email is already used by another company.',
            'logo.image' => 'Logo must be an image file.',
            'logo.mimes' => 'Allowed logo formats: jpeg, png, jpg, gif, svg.',
            'logo.max' => 'Logo size must not exceed 2MB.',
            'domains.array' => 'The list of domains must be an array.',
            'domains.*.exists' => 'The selected domain is invalid.',
        ];
    }
}
