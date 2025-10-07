{{-- backoffice/action_plans/show.blade.php --}}
@extends('layouts.main')

@section('title', 'Plan d\'action pour la tentative #{{ $attempt->id }}')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Plan d'action - Tentative #{{ $attempt->id }}</h3>
            <a href="{{ route('backoffice.action-plans.index', $company->id) }}" class="btn btn-secondary btn-sm">Retour aux plans d'action</a>
        </div>
        <div class="card-body">
            <form action="{{ route('backoffice.action-plans.store', $company->id) }}" method="POST">
                @csrf
                <input type="hidden" name="attempt_id" value="{{ $attempt->id }}">
                
                @if ($questions->isEmpty())
                    <div class="alert alert-success">
                        Ce QCM ne contient aucune question "Non applicable".
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-danger text-white">
                                <tr>
                                    <th>Question</th>
                                    <th>Action</th>
                                    <th>Responsable</th>
                                    <th>Délai</th>
                                    <th>Évaluation de l’efficacité</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($questions as $index => $answer)
                                    @php
                                        $plan = $answer->actionPlan;
                                    @endphp
                                    <tr>
                                        <td class="align-middle">
                                            {{ $answer->question->question_text }}
                                            <input type="hidden" name="plans[{{ $index }}][answer_id]" value="{{ $answer->id }}">
                                        </td>
                                        <td>
                                            <textarea name="plans[{{ $index }}][action_text]" class="form-control" rows="3">{{ $plan->action_text ?? '' }}</textarea>
                                        </td>
                                        <td>
                                            <input type="text" name="plans[{{ $index }}][responsible_name]" class="form-control" value="{{ $plan->responsible_name ?? '' }}" placeholder="Nom">
                                        </td>
                                        <td>
                                            <input type="date" name="plans[{{ $index }}][deadline]" class="form-control" value="{{ $plan->deadline ?? '' }}">
                                        </td>
                                        <td>
                                            <textarea name="plans[{{ $index }}][evaluation]" class="form-control" rows="3">{{ $plan->evaluation ?? '' }}</textarea>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <button type="submit" class="btn btn-success mt-3">Sauvegarder le plan d'action</button>
                @endif
            </form>
        </div>
    </div>
@endsection