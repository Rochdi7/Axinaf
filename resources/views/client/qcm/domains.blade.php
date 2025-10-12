{{-- resources/views/backoffice/qcm/domains.blade.php --}}
@extends('layouts.main')

@section('title', "QCM de la société : {$company->name}")
@section('breadcrumb-item', 'Administration')
@section('breadcrumb-item-active', 'Sélection du domaine')
@section('page-animation', 'animate__fadeIn')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/css/plugins/style.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('build/css/plugins/animate.min.css') }}">
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Sélectionner un domaine pour : <strong>{{ $company->name }}</strong></h3>
        <a href="{{ route('backoffice.companies.index') }}" class="btn btn-secondary btn-sm">Retour aux sociétés</a>
    </div>

    <div class="card-body">
        @if($domains->isEmpty())
            <div class="alert alert-warning">
                Aucun domaine actif trouvé pour cette société.
            </div>
        @else
            <div class="row">
                @foreach($domains as $domain)
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm h-100">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <h5 class="card-title">{{ $domain->title }}</h5>
                                <p class="card-text">{{ $domain->description ?? 'Pas de description' }}</p>
                                <a href="{{ route('backoffice.company-qcm.show', [$company->id, $domain->id]) }}" 
                                   class="btn btn-primary mt-2">
                                    Accéder au QCM
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

@section('js')
{{-- JS spécifique si nécessaire --}}
@endsection
