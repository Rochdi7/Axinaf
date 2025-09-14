@extends('layouts.main')

@section('title', $checklist->name)

@section('content')
<h1>{{ $checklist->name }}</h1>

<form action="{{ route('client.qcm.submit', $checklist->id) }}" method="POST">
    @csrf

    @foreach($questions as $question)
        <div class="row align-items-center mb-3 border-bottom py-2">
            <!-- Question Text on the Left -->
            <div class="col-md-8">
                <strong>{{ $question->sousFamille->title ?? 'Général' }}:</strong> {{ $question->question_text }}
            </div>

            <!-- Answer Options on the Right -->
            <div class="col-md-4 d-flex justify-content-around">
                @foreach(['Conforme', 'Non conforme', 'Non applicable', 'En attente'] as $status)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" 
                               name="answers[{{ $question->id }}]" 
                               id="q{{ $question->id }}_{{ $status }}" 
                               value="{{ $status }}" required>
                        <label class="form-check-label" for="q{{ $question->id }}_{{ $status }}">
                            {{ $status }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <button type="submit" class="btn btn-success mt-3">Soumettre</button>
</form>
@endsection
