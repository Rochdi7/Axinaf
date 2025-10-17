<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyQcmAnswerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only superadmin or company admin can submit QCM answers
        return auth()->user()->hasAnyRole(['superadmin', 'admin']);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'answers' => 'required|array', // array of answers
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.status' => 'required|in:Conforme,Non conforme,En cours,Non applicable',
        ];
    }

    /**
     * Custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'answers.required' => 'You must submit at least one answer.',
            'answers.array' => 'Answers must be provided as an array.',
            'answers.*.question_id.required' => 'Question ID is required.',
            'answers.*.question_id.exists' => 'The selected question is invalid.',
            'answers.*.status.required' => 'Status is required for each question.',
            'answers.*.status.in' => 'Status must be one of: Conforme, Non conforme, En cours, Non applicable.',
        ];
    }
}
