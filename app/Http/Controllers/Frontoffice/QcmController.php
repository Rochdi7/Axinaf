<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Checklist;
use App\Models\Question;
use App\Models\UserQcmAttempt;
use App\Models\UserQcmAnswer;
use Illuminate\Support\Facades\Auth;

class QcmController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // TODO: filter checklists assigned to this user if needed
        $checklists = Checklist::all(); 
        return view('client.qcm.index', compact('checklists'));
    }

    public function show(Checklist $checklist)
    {
        $questions = $checklist->questions()->with('sousFamille')->get();
        return view('client.qcm.show', compact('checklist', 'questions'));
    }

    public function submit(Request $request, Checklist $checklist)
    {
        $user = Auth::user();

        $request->validate([
            'answers' => 'required|array',
        ]);

        $attempt = UserQcmAttempt::create([
            'user_id' => $user->id,
            'checklist_id' => $checklist->id,
            'started_at' => now(),
            'status' => 'completed',
        ]);

        foreach ($request->answers as $questionId => $status) {
            UserQcmAnswer::create([
                'user_qcm_attempt_id' => $attempt->id,
                'question_id' => $questionId,
                'status' => $status,
            ]);
        }

        return redirect()->route('client.qcm.index')->with('success', 'QCM soumis avec succès !');
    }
}
