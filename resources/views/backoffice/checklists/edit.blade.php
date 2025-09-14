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

                <div class="mb-3">
                    <label for="name" class="form-label">Nom de la checklist</label>
                    <input type="text" class="form-control" name="name" id="name"
                        value="{{ old('name', $checklist->name) }}" required>
                </div>

                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <a href="{{ route('checklists.index') }}" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
@endsection
