@extends('layouts.main')

@section('title', "Résultats QCM - {$domain->title}")
@section('breadcrumb-item', 'QCM')
@section('breadcrumb-item-active', $domain->title)
@section('page-animation', 'animate__fadeIn')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/css/plugins/style.css') }}">
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Résultats QCM pour {{ $company->name }} - {{ $domain->title }}</h3>
        <span class="badge bg-primary">Score: {{ $score }}%</span>
    </div>
    <div class="card-body">
        <p>Total questions prises en compte: {{ $totalQuestions }}</p>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Question</th>
                    <th>Réponse</th>
                </tr>
            </thead>
            <tbody>
                @foreach($answers as $index => $answer)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $answer->question->question_text ?? 'Question supprimée' }}</td>
                        <td>
                            @php
                                $status = $answer->status;
                                $badgeClass = match($status) {
                                    'Confirmer' => 'success',
                                    'Non confirmer' => 'danger',
                                    'En cours' => 'warning',
                                    'Non applicable' => 'secondary',
                                    default => 'light',
                                };
                            @endphp
                            <span class="badge bg-{{ $badgeClass }}">{{ $status }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <a href="{{ route('backoffice.company-qcm.show', [$company->id, $domain->id]) }}" class="btn btn-secondary mt-3">Retour au QCM</a>
    </div>
</div>
@endsection

@section('js')
<script>
    // Optional: Add JS for table interactivity if needed
</script>
@endsection
