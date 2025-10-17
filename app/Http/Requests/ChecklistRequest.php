<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChecklistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Only superadmin can create/update checklists.
     */
    public function authorize()
    {
        return auth()->user()->hasRole('superadmin');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'domain_id' => 'required|exists:domains,id', // must select a valid domain
            'description' => 'nullable|string',
            'familles' => 'nullable|array',
            'familles.*.title' => 'required|string|max:255',
            'familles.*.description' => 'nullable|string',
            'familles.*.sous_familles' => 'nullable|array',
            'familles.*.sous_familles.*.title' => 'required|string|max:255',
            'familles.*.sous_familles.*.description' => 'nullable|string',
            'familles.*.sous_familles.*.questions' => 'nullable|array',
            'familles.*.sous_familles.*.questions.*.question_text' => 'required|string',
        ];
    }

    /**
     * Custom messages for validation errors.
     */
    public function messages()
    {
        return [
            'title.required' => 'Checklist title is required.',
            'domain_id.required' => 'You must select a domain.',
            'domain_id.exists' => 'The selected domain does not exist.',
            'familles.*.title.required' => 'Each famille must have a title.',
            'familles.*.sous_familles.*.title.required' => 'Each sous-famille must have a title.',
            'familles.*.sous_familles.*.questions.*.question_text.required' => 'Each question must have text.',
        ];
    }
}
