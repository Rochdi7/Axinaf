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

                <!-- Domain Selector -->
                <div class="mb-4">
                    <label class="form-label">Domaine</label>
                    <select name="domain_id" class="form-control" required>
                        <option value="">-- Sélectionner un Domaine --</option>
                        @foreach ($domains as $domain)
                            <option value="{{ $domain->id }}"
                                {{ (int) old('domain_id') === $domain->id ? 'selected' : '' }}>
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
                        value="{{ old('title') }}" required>
                    @error('title')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" placeholder="Description">{{ old('description') }}</textarea>
                </div>

                <!-- Familles Container -->
                <div id="familles-accordion">
                    <h4>Familles</h4>
                    <div class="famille-item mb-2 card" data-index="0">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Famille 1</span>
                            <div>
                                <button type="button" class="btn btn-sm btn-danger remove-famille">Supprimer</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <label>Titre Famille</label>
                                <input type="text" name="familles[0][title]" class="form-control"
                                    placeholder="Titre Famille" required>
                            </div>
                            <div class="mb-2">
                                <label>Description Famille</label>
                                <textarea name="familles[0][description]" class="form-control" placeholder="Description Famille"></textarea>
                            </div>

                            <!-- Sous-familles -->
                            <div class="sous-familles-accordion">
                                <h5>Sous-Familles</h5>
                                <div class="sous-famille-item mb-2 card" data-index="0">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <span>Sous-Famille 1</span>
                                        <div>
                                            <button type="button"
                                                class="btn btn-sm btn-danger remove-sous-famille">Supprimer</button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-2">
                                            <label>Titre Sous-Famille</label>
                                            <input type="text" name="familles[0][sous_familles][0][title]"
                                                class="form-control" placeholder="Titre Sous Famille" required>
                                        </div>
                                        <div class="mb-2">
                                            <label>Description Sous-Famille</label>
                                            <textarea name="familles[0][sous_familles][0][description]" class="form-control" placeholder="Description Sous Famille"></textarea>
                                        </div>

                                        <!-- Questions -->
                                        <div class="questions-container">
                                            <h6>Questions</h6>
                                            <div class="question-item mb-2 d-flex gap-2 align-items-center">
                                                <input type="text"
                                                    name="familles[0][sous_familles][0][questions][0][question_text]"
                                                    class="form-control" placeholder="Question" required>
                                                <button type="button"
                                                    class="btn btn-danger btn-sm remove-question">Supprimer</button>
                                            </div>
                                            <button type="button" class="btn btn-primary btn-sm add-question mt-1">Ajouter
                                                Question</button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary btn-sm add-sous-famille mt-2">Ajouter
                                    Sous-Famille</button>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" id="add-famille" class="btn btn-primary mt-3">Ajouter Famille</button>
                <button type="submit" class="btn btn-success mt-3">Enregistrer Checklist</button>
            </form>
        </div>
    </div>

    <style>
        .famille-item,
        .sous-famille-item {
            background-color: #f9f9f9;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            padding: 10px;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Question add/remove
            function bindQuestionEvents(container) {
                container.querySelectorAll('.add-question').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        let qContainer = this.closest('.questions-container');
                        let items = qContainer.querySelectorAll('.question-item');
                        let lastIndex = items.length;
                        let template = items[0].cloneNode(true);
                        let input = template.querySelector('input');
                        input.value = '';
                        input.name = input.name.replace(/\d+(?=\]\[question_text\]$)/, lastIndex);
                        template.querySelector('.remove-question').addEventListener('click',
                            function(ev) {
                                ev.preventDefault();
                                template.remove();
                            });
                        qContainer.insertBefore(template, this);
                    });
                });

                container.querySelectorAll('.remove-question').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        let qContainer = this.closest('.questions-container');
                        if (qContainer.querySelectorAll('.question-item').length > 1) {
                            this.closest('.question-item').remove();
                        }
                    });
                });
            }

            bindQuestionEvents(document);

            // You can add similar JS for dynamic add/remove of familles and sous-familles if needed
        });
    </script>

@endsection
