@extends('layouts.main')

@section('title', 'Mes QCM')

@section('content')
<h1>Mes QCM</h1>

@if($checklists->count())
    <ul class="list-group">
        @foreach($checklists as $checklist)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $checklist->name }}
                <a href="{{ route('client.qcm.show', $checklist->id) }}" class="btn btn-sm btn-primary">Prendre QCM</a>
            </li>
        @endforeach
    </ul>
@else
    <p>Aucun QCM disponible.</p>
@endif
@endsection
