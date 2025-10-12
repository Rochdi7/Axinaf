@extends('layouts.main')

@section('title', 'Créer une entreprise')
@section('breadcrumb-item', 'Administration')
@section('breadcrumb-item-active', 'Nouvelle entreprise')
@section('page-animation', 'animate__rollIn')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/css/plugins/style.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('build/css/plugins/datepicker-bs5.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('build/css/plugins/animate.min.css') }}">
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        {{-- Global error alert --}}
        @if ($errors->any())
            <div class="alert alert-danger animate__animated animate__shakeX">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('backoffice.companies.store') }}" method="POST" enctype="multipart/form-data"
            class="needs-validation" novalidate>
            @csrf

            <div id="company-form-card" class="card animate__animated animate__rollIn">
                <div class="card-header">
                    <h5>Formulaire nouvelle entreprise</h5>
                </div>

                <div class="card-body">
                    <div class="row">

                        {{-- Company fields --}}
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Nom de l'entreprise</label>
                            <input type="text" name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" required>
                            <div class="invalid-feedback">
                                @error('name') {{ $message }} @else Veuillez entrer le nom. @enderror
                            </div>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="email" class="form-label">Adresse email</label>
                            <input type="email" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}">
                            <div class="invalid-feedback">
                                @error('email') {{ $message }} @else Veuillez entrer une adresse email valide. @enderror
                            </div>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="phone" class="form-label">Téléphone</label>
                            <input type="text" name="phone"
                                class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone') }}">
                            <div class="invalid-feedback">
                                @error('phone') {{ $message }} @else Veuillez entrer un numéro de téléphone. @enderror
                            </div>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="industry" class="form-label">Secteur d'activité</label>
                            <input type="text" name="industry"
                                class="form-control @error('industry') is-invalid @enderror"
                                value="{{ old('industry') }}">
                            <div class="invalid-feedback">
                                @error('industry') {{ $message }} @else Veuillez indiquer un secteur. @enderror
                            </div>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="address" class="form-label">Adresse</label>
                            <input type="text" name="address"
                                class="form-control @error('address') is-invalid @enderror"
                                value="{{ old('address') }}">
                            <div class="invalid-feedback">
                                @error('address') {{ $message }} @else Veuillez indiquer une adresse. @enderror
                            </div>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="logo" class="form-label">Logo de l'entreprise</label>
                            <input type="file" name="logo" accept="image/*"
                                class="form-control @error('logo') is-invalid @enderror">
                            <div class="invalid-feedback">
                                @error('logo') {{ $message }} @else Veuillez ajouter un fichier image valide. @enderror
                            </div>
                        </div>

                        {{-- Active checkbox --}}
                        <div class="mb-3 col-md-6 form-check mt-4">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" class="form-check-input" name="is_active" id="is_active"
                                value="1" @checked(old('is_active', $company->is_active ?? true))>
                            <label class="form-check-label" for="is_active">Entreprise active</label>
                        </div>

                        {{-- Domains multi-select --}}
                        <div class="mb-3 col-md-12">
                            <label for="domains" class="form-label">Domaines attribués</label>
                            <select name="domains[]" id="domains" class="form-control" multiple>
                                @foreach($domains as $domain)
                                    <option value="{{ $domain->id }}"
                                        {{ in_array($domain->id, old('domains', [])) ? 'selected' : '' }}>
                                        {{ $domain->title }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Maintenez la touche Ctrl (ou Cmd) pour sélectionner plusieurs domaines.</small>
                        </div>

                    </div>
                </div>

                <div class="card-footer text-end">
                    <a href="{{ route('backoffice.companies.index') }}" class="btn btn-secondary"
                        onclick="rollOutCard(event, this, 'company-form-card')">Annuler</a>
                    <button type="submit" class="btn btn-primary">Créer l'entreprise</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ URL::asset('build/js/plugins/datepicker-full.min.js') }}"></script>

<script>
    // Bootstrap validation
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            const forms = document.getElementsByClassName('needs-validation');
            Array.prototype.forEach.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();

    // RollOut animation on cancel
    function rollOutCard(event, link, cardId = 'company-form-card') {
        event.preventDefault();
        const card = document.getElementById(cardId);
        if (!card) return;

        card.classList.remove('animate__rollIn', 'animate__zoomIn', 'animate__fadeInUp');
        card.classList.add('animate__animated', 'animate__rollOut');

        setTimeout(() => {
            window.location.href = link.href;
        }, 1000);
    }
</script>
@endsection
