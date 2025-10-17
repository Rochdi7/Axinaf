<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DomainRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Superadmin ou admin peut gérer les domaines
        return auth()->user()->hasAnyRole(['superadmin', 'admin']);
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'company_ids' => 'nullable|array',             // array of company IDs
            'company_ids.*' => 'exists:companies,id',      // each ID must exist
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Le titre du domaine est obligatoire.',
            'title.string' => 'Le titre doit être une chaîne de caractères.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'description.string' => 'La description doit être une chaîne de caractères.',
            'is_active.boolean' => 'Le statut doit être vrai ou faux.',
            'company_ids.array' => 'La liste des entreprises doit être un tableau.',
            'company_ids.*.exists' => 'L’entreprise sélectionnée est invalide.',
        ];
    }
}
