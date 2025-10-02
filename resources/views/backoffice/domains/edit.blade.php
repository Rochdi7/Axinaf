@extends('layouts.main')

@section('title', 'Modifier un domaine')
@section('breadcrumb-item', 'Administration')
@section('breadcrumb-item-active', 'Modifier un domaine')
@section('page-animation', 'animate__rollIn')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/css/plugins/style.css') }}">
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

        <form action="{{ route('domains.update', $domain->id) }}"
              method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')

            <div id="domain-form-card" class="card animate__animated animate__rollIn">
                <div class="card-header">
                    <h5>Modifier le domaine métier</h5>
                </div>

                <div class="card-body">
                    <div class="row">

                        {{-- Nom --}}
                        <div class="mb-3 col-md-6">
                            <label for="title" class="form-label">Nom du domaine</label>
                            <input type="text" name="title" value="{{ old('title', $domain->title) }}"
                                   class="form-control @error('title') is-invalid @enderror" required>
                            <div class="invalid-feedback">
                                @error('title') {{ $message }} @else Veuillez entrer un nom. @enderror
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="mb-3 col-md-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" rows="3"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Décrivez brièvement ce domaine">{{ old('description', $domain->description) }}</textarea>
                            <div class="invalid-feedback">
                                @error('description') {{ $message }} @else Facultatif. @enderror
                            </div>
                        </div>

                        {{-- Actif --}}
                        <div class="mb-3 col-md-3 d-flex align-items-center">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
       value="1" {{ old('is_active', $domain->is_active) ? 'checked' : '' }}>

                                <label class="form-check-label" for="is_active">Actif</label>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card-footer text-end">
                    <a href="{{ route('domains.index') }}" class="btn btn-secondary"
                       onclick="rollOutCard(event, this, 'domain-form-card')">Annuler</a>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Bootstrap validation
    (function () {
        'use strict';
        window.addEventListener('load', function () {
            const forms = document.getElementsByClassName('needs-validation');
            Array.prototype.forEach.call(forms, function (form) {
                form.addEventListener('submit', function (event) {
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
    function rollOutCard(event, link, cardId = 'domain-form-card') {
        event.preventDefault();
        const card = document.getElementById(cardId);
        if (!card) return;

        card.classList.remove('animate__rollIn', 'animate__fadeInUp', 'animate__zoomIn');
        card.classList.add('animate__animated', 'animate__rollOut');

        setTimeout(() => {
            window.location.href = link.href;
        }, 1000);
    }
</script>
@endsection
