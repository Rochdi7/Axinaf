{{-- backoffice/action_plans/index.blade.php --}}
@extends('layouts.main')

@section('title', 'Plans d\'action')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Plans d'action pour {{ $company->name }}</h3>
        </div>
        <div class="card-body">
            
            {{-- Filter Form --}}
            <form method="GET" class="mb-3">
                <div class="row g-3 align-items-end">
                    <div class="col-auto">
                        <label for="checklist-filter" class="form-label">Filtrer par Checklist :</label>
                        <select name="checklist_id" id="checklist-filter" class="form-select">
                            <option value="">Toutes les checklists</option>
                            @foreach($checklists as $checklist)
                                <option value="{{ $checklist->id }}" {{ $selectedChecklistId == $checklist->id ? 'selected' : '' }}>
                                    {{ $checklist->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Filtrer</button>
                    </div>
                </div>
            </form>

            @if ($attempts->isEmpty())
                <div class="alert alert-info mt-3">
                    Aucun QCM ne nécessite un plan d'action pour le moment.
                </div>
            @else
                <table class="table table-bordered table-striped mt-3">
                    <thead>
                        <tr>
                            <th>ID Tentative</th>
                            <th>Domaine</th>
                            <th>Checklist</th>
                            <th>Date de la tentative</th>
                            <th>Créé par</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($attempts as $attempt)
                            <tr>
                                <td>{{ $attempt->id }}</td>
                                <td>{{ $attempt->domain->title }}</td>
                                <td>{{ $attempt->checklist->title ?? 'N/A' }}</td>
                                <td>{{ $attempt->created_at->format('d/m/Y') }}</td>
                                <td>{{ $attempt->user->name }}</td>
                                <td>
                                    <a href="{{ route('backoffice.action-plans.show', ['company' => $company->id, 'attempt' => $attempt->id]) }}" class="btn btn-primary btn-sm">
                                        Voir le plan d'action
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection