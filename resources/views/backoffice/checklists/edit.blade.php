@extends('layouts.main')

@section('title', 'Modifier Checklist')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Modifier Checklist</h3>
    </div>

    <div class="card-body">
        <form action="{{ route('checklists.update', $checklist->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Domain Selector -->
            <div class="mb-4">
                <label class="form-label">Domaine</label>
                <select name="domain_id" class="form-control" required>
    <option value="">-- Sélectionner un Domaine --</option>
    @foreach($domains as $domain)
        <option value="{{ $domain->id }}" 
            {{ (int) old('domain_id', $checklist->domain_id ?? 0) === $domain->id ? 'selected' : '' }}>
            {{ $domain->title }}
        </option>
    @endforeach
</select>

                @error('domain_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Checklist Title & Description -->
            <div class="mb-4">
                <label class="form-label">Titre de la Checklist</label>
                <input type="text" name="title" class="form-control" placeholder="Titre"
                       value="{{ old('title', $checklist->title) }}" required>
                @error('title')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" placeholder="Description">{{ old('description', $checklist->description) }}</textarea>
            </div>

            <!-- Familles Accordion -->
            <div id="familles-accordion">
                <h4>Familles</h4>

                @foreach(old('familles', $checklist->familles) as $fIndex => $famille)
                <div class="famille-item mb-2 card">
                    <div class="card-header d-flex justify-content-between align-items-center" id="famille-header-{{ $fIndex }}">
                        <span>Famille {{ $fIndex + 1 }}</span>
                        <div>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="collapse" data-bs-target="#famille-collapse-{{ $fIndex }}">
                                Ouvrir / Fermer
                            </button>
                            <button type="button" class="btn btn-sm btn-danger remove-famille">Supprimer</button>
                        </div>
                    </div>

                    <div id="famille-collapse-{{ $fIndex }}" class="collapse show" data-bs-parent="#familles-accordion">
                        <div class="card-body">
                            <div class="mb-2">
                                <label>Titre Famille</label>
                                <input type="text" name="familles[{{ $fIndex }}][title]" class="form-control"
                                    value="{{ old("familles.$fIndex.title", $famille->title) }}" required>
                            </div>
                            <div class="mb-2">
                                <label>Description Famille</label>
                                <textarea name="familles[{{ $fIndex }}][description]" class="form-control">{{ old("familles.$fIndex.description", $famille->description) }}</textarea>
                            </div>

                            <!-- Sous-familles -->
                            <div class="sous-familles-accordion">
                                <h5>Sous-Familles</h5>

                                @foreach(old("familles.$fIndex.sous_familles", $famille->sousFamilles) as $sfIndex => $sousFamille)
                                <div class="sous-famille-item mb-2 card">
                                    <div class="card-header d-flex justify-content-between align-items-center" id="sousfamille-header-{{ $fIndex }}-{{ $sfIndex }}">
                                        <span>Sous-Famille {{ $sfIndex + 1 }}</span>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="collapse" data-bs-target="#sousfamille-collapse-{{ $fIndex }}-{{ $sfIndex }}">
                                                Ouvrir / Fermer
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger remove-sous-famille">Supprimer</button>
                                        </div>
                                    </div>
                                    <div id="sousfamille-collapse-{{ $fIndex }}-{{ $sfIndex }}" class="collapse show">
                                        <div class="card-body">
                                            <div class="mb-2">
                                                <label>Titre Sous-Famille</label>
                                                <input type="text" name="familles[{{ $fIndex }}][sous_familles][{{ $sfIndex }}][title]" class="form-control" value="{{ old("familles.$fIndex.sous_familles.$sfIndex.title", $sousFamille->title) }}" required>
                                            </div>
                                            <div class="mb-2">
                                                <label>Description Sous-Famille</label>
                                                <textarea name="familles[{{ $fIndex }}][sous_familles][{{ $sfIndex }}][description]" class="form-control">{{ old("familles.$fIndex.sous_familles.$sfIndex.description", $sousFamille->description) }}</textarea>
                                            </div>

                                            <!-- Questions -->
                                            <div class="questions-container">
                                                <h6>Questions</h6>
                                                @foreach(old("familles.$fIndex.sous_familles.$sfIndex.questions", $sousFamille->questions) as $qIndex => $question)
                                                <div class="question-item mb-2 d-flex gap-2 align-items-center">
                                                    <input type="text" name="familles[{{ $fIndex }}][sous_familles][{{ $sfIndex }}][questions][{{ $qIndex }}][question_text]" class="form-control" value="{{ old("familles.$fIndex.sous_familles.$sfIndex.questions.$qIndex.question_text", $question->question_text) }}" required>
                                                    <button type="button" class="btn btn-danger btn-sm remove-question">Supprimer</button>
                                                </div>
                                                @endforeach
                                                <button type="button" class="btn btn-primary btn-sm add-question mt-1">Ajouter Question</button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @endforeach

                                <button type="button" class="btn btn-primary btn-sm add-sous-famille mt-2">Ajouter Sous-Famille</button>
                            </div>

                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <button type="button" id="add-famille" class="btn btn-primary mt-3">Ajouter Famille</button>
            <button type="submit" class="btn btn-success mt-3">Enregistrer Checklist</button>
            <a href="{{ route('checklists.index') }}" class="btn btn-secondary mt-3">Annuler</a>

        </form>
    </div>
</div>

<style>
    .famille-item, .sous-famille-item {
        background-color: #f9f9f9;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Add Question
    document.querySelectorAll('.add-question').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            let container = this.closest('.questions-container');
            let questionItems = container.querySelectorAll('.question-item');
            let lastIndex = questionItems.length;
            let template = questionItems[0].cloneNode(true);
            let input = template.querySelector('input');
            input.value = '';
            input.name = input.name.replace(/\d+(?=\]\[question_text\]$)/, lastIndex);
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
