<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChecklistRequest;
use App\Models\Checklist;

class ChecklistController extends Controller
{
    /**
     * Display a listing of the checklists.
     */
    public function index()
    {
        $checklists = Checklist::latest()->paginate(10);
        return view('backoffice.checklists.index', compact('checklists'));
    }

    /**
     * Show the form for creating a new checklist.
     */
    public function create()
    {
        return view('backoffice.checklists.create');
    }

    /**
     * Store a newly created checklist with nested familles, sous-familles, questions.
     */
    public function store(ChecklistRequest $request)
    {
        $data = $request->validated();

        $checklist = Checklist::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'created_by' => auth()->id(),
            'is_active' => true,
        ]);

        $this->createNested($checklist, $data['familles'] ?? []);

        return redirect()->route('checklists.index')->with('success', 'Checklist created successfully!');
    }

    /**
     * Show the form for editing a checklist.
     */
    public function edit(Checklist $checklist)
    {
        $checklist->load('familles.sousFamilles.questions');
        return view('backoffice.checklists.edit', compact('checklist'));
    }

    /**
     * Update a checklist and its nested items.
     */
    public function update(ChecklistRequest $request, Checklist $checklist)
    {
        $data = $request->validated();

        $checklist->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
        ]);

        // Delete old nested items
        $checklist->familles()->each(function ($famille) {
            $famille->sousFamilles()->each(fn($sf) => $sf->questions()->delete());
            $famille->sousFamilles()->delete();
        });
        $checklist->familles()->delete();

        $this->createNested($checklist, $data['familles'] ?? []);

        return redirect()->route('checklists.index')->with('success', 'Checklist updated successfully!');
    }

    /**
     * Remove the specified checklist from storage.
     */
    public function destroy(Checklist $checklist)
    {
        $checklist->delete();
        return redirect()->route('checklists.index')->with('success', 'Checklist deleted successfully!');
    }

    /**
     * Helper to create nested familles, sous-familles, questions
     */
    /**
     * Helper to create nested familles, sous-familles, questions
     */
    private function createNested($checklist, $familles)
    {
        foreach ($familles as $familleData) {
            // Create famille
            $famille = $checklist->familles()->create([
                'title' => $familleData['title'],
                'description' => $familleData['description'] ?? null,
            ]);

            if (!empty($familleData['sous_familles'])) {
                foreach ($familleData['sous_familles'] as $sousFamilleData) {
                    // Create sous-famille
                    $sousFamille = $famille->sousFamilles()->create([
                        'title' => $sousFamilleData['title'],
                        'description' => $sousFamilleData['description'] ?? null,
                    ]);

                    if (!empty($sousFamilleData['questions'])) {
                        foreach ($sousFamilleData['questions'] as $questionData) {
                            // Create question with both checklist_id and sous_famille_id
                            $sousFamille->questions()->create([
                                'question_text' => $questionData['question_text'],
                                'checklist_id' => $checklist->id, // make sure it's saved
                            ]);
                        }
                    }
                }
            }
        }
    }
}
