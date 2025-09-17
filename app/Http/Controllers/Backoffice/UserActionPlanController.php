<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\UserQcmAttempt;
use App\Models\Company;
use App\Models\Checklist;
use App\Models\User;
use App\Models\ActionPlan;
use Illuminate\Http\Request;

class UserActionPlanController extends Controller
{
    /**
     * Display a list of QCM attempts that need an action plan.
     */
    public function index(Company $company, Request $request)
    {
        $checklists = Checklist::latest()->get();
        $selectedChecklistId = $request->get('checklist_id');

        $attempts = UserQcmAttempt::where('company_id', $company->id)
            ->whereHas('answers', function ($query) {
                $query->where('status', 'Non applicable');
            })
            ->when($selectedChecklistId, function ($query, $selectedChecklistId) {
                return $query->where('checklist_id', $selectedChecklistId);
            })
            ->with(['checklist', 'domain', 'user'])
            ->latest()
            ->get();

        return view('backoffice.action_plans.index', compact('attempts', 'company', 'checklists', 'selectedChecklistId'));
    }

    /**
     * Show the form to create or edit an action plan for a specific attempt.
     */
    public function show(Company $company, UserQcmAttempt $attempt)
    {
        $questions = $attempt->answers()
            ->where('status', 'Non applicable')
            ->with(['question.sousFamille.famille', 'actionPlan'])
            ->get();
        
        // No need to fetch users if you're using a text field
        return view('backoffice.action_plans.show', compact('company', 'attempt', 'questions'));
    }

    /**
     * Store the action plan.
     */
    public function store(Request $request, Company $company)
    {
        $data = $request->validate([
            'attempt_id' => 'required|exists:user_qcm_attempts,id',
            'plans' => 'required|array',
            'plans.*.answer_id' => 'required|exists:user_qcm_answers,id',
            'plans.*.action_text' => 'nullable|string',
            'plans.*.responsible_name' => 'nullable|string|max:255', // Changed to responsible_name
            'plans.*.deadline' => 'nullable|date',
            'plans.*.evaluation' => 'nullable|string',
        ]);

        foreach ($data['plans'] as $planData) {
            $dataToCheck = $planData;
            unset($dataToCheck['answer_id']);

            if (empty(array_filter($dataToCheck))) {
                continue;
            }
            
            ActionPlan::updateOrCreate(
                ['user_qcm_answer_id' => $planData['answer_id']],
                [
                    'action_text' => $planData['action_text'] ?? null,
                    'responsible_name' => $planData['responsible_name'] ?? null, // Changed to responsible_name
                    'deadline' => $planData['deadline'] ?? null,
                    'evaluation' => $planData['evaluation'] ?? null,
                ]
            );
        }

        return redirect()->route('backoffice.action-plans.show', [
            'company' => $company->id,
            'attempt' => $data['attempt_id']
        ])->with('success', 'Plan d\'action mis à jour avec succès!');
    }
}