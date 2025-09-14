<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChecklistRequest extends FormRequest
{
    public function authorize()
    {
        // Only superadmin can create/update checklists
        return auth()->user()->hasRole('superadmin');
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
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

    public function messages()
    {
        return [
            'title.required' => 'Checklist title is required.',
            'familles.*.title.required' => 'Each famille must have a title.',
            'familles.*.sous_familles.*.title.required' => 'Each sous-famille must have a title.',
            'familles.*.sous_familles.*.questions.*.question_text.required' => 'Each question must have text.',
        ];
    }
}
