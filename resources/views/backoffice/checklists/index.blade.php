@extends('layouts.main')

@section('title', 'Checklists')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Liste des Checklists</h3>
        <a href="{{ route('checklists.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i> Créer Checklist
        </a>
    </div>

    <div class="card-body">
        @if($checklists->count())
            <ul class="list-group">
                @foreach($checklists as $checklist)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $checklist->name }}
                        <div>
                            <a href="{{ route('checklists.edit', $checklist->id) }}" class="btn btn-sm btn-warning">
                                <i class="fa fa-edit"></i> Modifier
                            </a>

                            <form action="{{ route('checklists.destroy', $checklist->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fa fa-trash"></i> Supprimer
                                </button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <p>Aucune checklist trouvée.</p>
        @endif
    </div>
</div>
@endsection
