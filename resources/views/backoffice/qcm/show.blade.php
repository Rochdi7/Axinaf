@extends('layouts.main')

@section('title', "QCM - {$domain->title}")
@section('breadcrumb-item', 'QCM')
@section('breadcrumb-item-active', $domain->title)
@section('page-animation', 'animate__fadeIn')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/css/plugins/style.css') }}">
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">QCM pour <strong>{{ $domain->title }}</strong> - {{ $company->name }}</h3>
            <a href="{{ route('backoffice.company-qcm.listDomains', $company->id) }}" class="btn btn-secondary btn-sm">Retour
                aux domaines</a>
        </div>

        <div class="card-body">

            {{-- Checklist filter --}}
            @if ($checklists->count() > 1)
                <form method="GET" class="mb-3">
                    <label>Filtrer par checklist :</label>
                    <select name="checklist_id" class="form-select w-auto d-inline-block" onchange="this.form.submit()">
                        @foreach ($checklists as $checklist)
                            <option value="{{ $checklist->id }}" {{ $checklistId == $checklist->id ? 'selected' : '' }}>
                                {{ $checklist->title }}
                            </option>
                        @endforeach
                    </select>
                </form>
            @endif

            @if ($questions->isEmpty())
                <div class="alert alert-warning">
                    Aucune question trouvée pour ce domaine.
                </div>
            @else
                <form action="{{ route('backoffice.company-qcm.store', [$company->id, $domain->id]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="checklist_id" value="{{ $checklistId }}">

                    {{-- QCM Questions Loop --}}
                    @foreach ($questions as $question)
                        @php
                            $oldStatus = $existingAnswers[$question->id]->status ?? '';
                        @endphp

                        <div class="mb-3 border p-3 rounded">
                            <p><strong>Question:</strong> {{ $question->question_text }}</p>
                            <div class="d-flex flex-wrap gap-3">
                                @foreach (['Conforme', 'Non conforme', 'Non applicable', 'En attente'] as $status)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio"
                                            name="answers[{{ $question->id }}][status]"
                                            id="{{ strtolower(str_replace(' ', '_', $status)) }}_{{ $question->id }}"
                                            value="{{ $status }}" {{ $oldStatus === $status ? 'checked' : '' }}>
                                        <label class="form-check-label"
                                            for="{{ strtolower(str_replace(' ', '_', $status)) }}_{{ $question->id }}">
                                            {{ $status }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <input type="hidden" name="answers[{{ $question->id }}][question_id]"
                                value="{{ $question->id }}">
                        </div>
                    @endforeach

                    <button type="submit" class="btn btn-primary mt-3">Sauvegarder</button>
                </form>

                {{-- Display Database-backed Score and Count --}}
                <div class="card p-3 mt-4 bg-light">
                    <h4>Statistiques du QCM</h4>
                    <p>
                        Questions prises en compte : <strong>{{ $attempt->total_countable_questions ?? 0 }}</strong> / <strong>{{ $questions->count() }}</strong>
                    </p>
                    <p>
                        Score actuel : <strong class="text-primary">{{ $attempt->score ?? 0 }}%</strong>
                    </p>
                </div>
            @endif
        </div>
    </div>
@endsection