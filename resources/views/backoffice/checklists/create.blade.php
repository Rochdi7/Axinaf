@extends('layouts.main')

@section('title', 'Créer Checklist')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Créer une Checklist</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('checklists.store') }}" method="POST">
            @csrf

            <!-- Checklist Title & Description -->
            <div class="mb-4">
                <label class="form-label">Titre de la Checklist</label>
                <input type="text" name="title" class="form-control" placeholder="Titre" required>
            </div>
            <div class="mb-4">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" placeholder="Description"></textarea>
            </div>

            <!-- Familles Accordion -->
            <div id="familles-accordion">
                <h4>Familles</h4>

                <div class="famille-item mb-2 card">
                    <div class="card-header d-flex justify-content-between align-items-center" id="famille-header-0">
                        <span>Famille 1</span>
                        <div>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="collapse" data-bs-target="#famille-collapse-0">
                                Ouvrir / Fermer
                            </button>
                            <button type="button" class="btn btn-sm btn-danger remove-famille">Supprimer</button>
                        </div>
                    </div>
                    <div id="famille-collapse-0" class="collapse show" data-bs-parent="#familles-accordion">
                        <div class="card-body">
                            <div class="mb-2">
                                <label>Titre Famille</label>
                                <input type="text" name="familles[0][title]" class="form-control" placeholder="Titre Famille" required>
                            </div>
                            <div class="mb-2">
                                <label>Description Famille</label>
                                <textarea name="familles[0][description]" class="form-control" placeholder="Description Famille"></textarea>
                            </div>

                            <!-- Sous-familles Accordion -->
                            <div class="sous-familles-accordion">
                                <h5>Sous-Familles</h5>

                                <div class="sous-famille-item mb-2 card">
                                    <div class="card-header d-flex justify-content-between align-items-center" id="sousfamille-header-0">
                                        <span>Sous-Famille 1</span>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="collapse" data-bs-target="#sousfamille-collapse-0">
                                                Ouvrir / Fermer
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger remove-sous-famille">Supprimer</button>
                                        </div>
                                    </div>
                                    <div id="sousfamille-collapse-0" class="collapse show">
                                        <div class="card-body">
                                            <div class="mb-2">
                                                <label>Titre Sous-Famille</label>
                                                <input type="text" name="familles[0][sous_familles][0][title]" class="form-control" placeholder="Titre Sous Famille" required>
                                            </div>
                                            <div class="mb-2">
                                                <label>Description Sous-Famille</label>
                                                <textarea name="familles[0][sous_familles][0][description]" class="form-control" placeholder="Description Sous Famille"></textarea>
                                            </div>

                                            <!-- Questions -->
                                            <div class="questions-container">
                                                <h6>Questions</h6>
                                                <div class="question-item mb-2 d-flex gap-2 align-items-center">
                                                    <input type="text" name="familles[0][sous_familles][0][questions][0][question_text]" class="form-control" placeholder="Question" required>
                                                    <button type="button" class="btn btn-danger btn-sm remove-question">Supprimer</button>
                                                </div>
                                                <button type="button" class="btn btn-primary btn-sm add-question mt-1">Ajouter Question</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-primary btn-sm add-sous-famille mt-2">Ajouter Sous-Famille</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <button type="button" id="add-famille" class="btn btn-primary mt-3">Ajouter Famille</button>
            <button type="submit" class="btn btn-success mt-3">Enregistrer Checklist</button>
        </form>
    </div>
</div>

<!-- Optional: collapse icon rotation -->
<style>
    .famille-item, .sous-famille-item {
        background-color: #f9f9f9;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
</style>

<!-- Bootstrap 5 Collapse requires JS -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Add Question
    document.querySelectorAll('.add-question').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault(); // prevent page refresh

            let container = this.closest('.questions-container');
            let questionItems = container.querySelectorAll('.question-item');
            let lastIndex = questionItems.length;

            // Clone last question item
            let template = questionItems[0].cloneNode(true);
            let input = template.querySelector('input');
            input.value = ''; // clear value

            // Update input name index
            input.name = input.name.replace(/\d+(?=\]\[question_text\]$)/, lastIndex);

            // Add remove event for the new button
            template.querySelector('.remove-question').addEventListener('click', function(ev){
                ev.preventDefault();
                template.remove();
            });

            container.insertBefore(template, this);
        });
    });

    // Remove Question
    document.querySelectorAll('.remove-question').forEach(btn => {
        btn.addEventListener('click', function(e){
            e.preventDefault();
            let container = this.closest('.questions-container');
            if(container.querySelectorAll('.question-item').length > 1){
                this.closest('.question-item').remove();
            }
        });
    });
});
</script>

@endsection
