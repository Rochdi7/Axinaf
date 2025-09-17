<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyQcmAnswerRequest;
use App\Models\Company;
use App\Models\Domain;
use App\Models\Question;
use App\Models\UserQcmAttempt;
use App\Models\UserQcmAnswer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompanyQcmController extends Controller
{
    public function __construct()
    {
        // Only superadmin or admin can manage QCM
        $this->middleware('role:superadmin|admin');
    }

    /**
     * List active domains for a company.
     */
    public function listDomains(Company $company)
    {
        $domains = $company->domains()->where('is_active', true)->get();
        return view('backoffice.qcm.domains', compact('company', 'domains'));
    }

    /**
     * Show QCM questions for a domain and checklist.
     */
    public function show(Company $company, Domain $domain)
    {
        $user = Auth::user();
        if (!$domain->companies->contains($company->id)) {
            abort(403, "Vous n'avez pas accès à ce QCM.");
        }

        $checklistId = request('checklist_id');
        $checklists = $domain->checklists()->get();
        $firstChecklist = $checklists->first();
        $checklistId = $checklistId ?? ($firstChecklist->id ?? null);

        $questions = $checklistId
            ? Question::where('checklist_id', $checklistId)
                ->with('sousFamille.famille.checklist')
                ->get()
            : collect();
        
        // Pass the total count of questions for the selected checklist
        $totalQuestions = $questions->count();

        $attempt = UserQcmAttempt::firstOrCreate(
            [
                'user_id' => $user->id,
                'checklist_id' => $checklistId,
                'status' => 'in_progress',
            ],
            [
                'company_id' => $company->id,
                'domain_id' => $domain->id,
            ]
        );

        $existingAnswers = $attempt->answers->keyBy('question_id');

        return view('backoffice.qcm.show', compact(
            'company',
            'domain',
            'checklists',
            'questions',
            'existingAnswers',
            'checklistId',
            'attempt',
            'totalQuestions' // Pass the total question count
        ));
    }

    public function store(CompanyQcmAnswerRequest $request, Company $company, Domain $domain)
{
    $user = Auth::user();
    $answers = $request->validated()['answers'] ?? [];
    $checklistId = $request->input('checklist_id');

    DB::transaction(function () use ($answers, $user, $company, $domain, $checklistId) {
        $attempt = UserQcmAttempt::firstOrCreate(
            [
                'user_id' => $user->id,
                'checklist_id' => $checklistId,
                'status' => 'in_progress',
            ],
            [
                'company_id' => $company->id,
                'domain_id' => $domain->id,
            ]
        );

        foreach ($answers as $answer) {
            UserQcmAnswer::updateOrCreate(
                [
                    'user_qcm_attempt_id' => $attempt->id,
                    'question_id' => $answer['question_id'],
                ],
                [
                    'status' => $answer['status'] ?? null
                ]
            );
        }
        
        // --- The fix is here: reload the model after saving all answers ---
        $attempt->refresh(); 
        // ------------------------------------------------------------------

        $savedAnswers = $attempt->answers;
        $countableAnswers = $savedAnswers->where('status', '!=', 'Non applicable');
        $correctAnswers = $countableAnswers->where('status', 'Conforme')->count();
        $totalCountable = $countableAnswers->count();
        
        $score = $totalCountable > 0 ? round(($correctAnswers / $totalCountable) * 100, 2) : 0;
        
        $attempt->update([
            'score' => $score,
            'total_countable_questions' => $totalCountable
        ]);
    });

    return redirect()->route(
        'backoffice.company-qcm.show',
        [$company->id, $domain->id, 'checklist_id' => $checklistId]
    )->with('success', 'Vos réponses ont été sauvegardées.');
}

    public function results(Company $company, Domain $domain)
    {
        $questions = Question::whereHas('sousFamille.famille.checklist', function ($q) use ($domain) {
            $q->where('domain_id', $domain->id);
        })->pluck('id');

        $answers = UserQcmAnswer::whereHas('attempt', function ($q) use ($company) {
                $q->where('company_id', $company->id);
            })
            ->whereIn('question_id', $questions)
            ->get();

        $totalQuestions = $answers->where('status', '!=', 'Non applicable')->count();
        $score = $totalQuestions > 0
            ? round($answers->where('status', 'Conforme')->count() / $totalQuestions * 100, 2)
            : 0;

        return view('backoffice.qcm.results', compact(
            'company',
            'domain',
            'answers',
            'totalQuestions',
            'score'
        ));
    }
}