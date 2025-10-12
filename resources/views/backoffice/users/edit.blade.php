@extends('layouts.main')

@section('title', 'Modifier un utilisateur')
@section('breadcrumb-item', 'Administration')
@section('breadcrumb-item-active', 'Modifier un utilisateur')
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

        <form action="{{ route('backoffice.users.update', $user->id) }}" method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')

            <div id="user-form-card" class="card animate__animated animate__rollIn">
                <div class="card-header">
                    <h5>Modifier les informations de l’utilisateur</h5>
                </div>

                <div class="card-body">
                    <div class="row">

                        {{-- Name --}}
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Nom</label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}" required>
                            <div class="invalid-feedback">
                                @error('name') {{ $message }} @else Veuillez saisir un nom. @enderror
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="mb-3 col-md-6">
                            <label for="email" class="form-label">Adresse e-mail</label>
                            <input type="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}" required>
                            <div class="invalid-feedback">
                                @error('email') {{ $message }} @else Veuillez saisir une adresse e-mail valide. @enderror
                            </div>
                        </div>

                        {{-- Password --}}
                        <div class="mb-3 col-md-6">
                            <label for="password" class="form-label">Nouveau mot de passe</label>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror">
                            <div class="invalid-feedback">
                                @error('password') {{ $message }} @else Laisser vide pour ne pas modifier. @enderror
                            </div>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>

                        {{-- Role --}}
                        <div class="mb-3 col-md-6">
                            <label for="role" class="form-label">Rôle</label>
                            <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="">Choisir un rôle...</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role }}"
                                        @selected(old('role', $user->getRoleNames()->first()) === $role)>
                                        {{ ucfirst($role) }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                @error('role') {{ $message }} @else Veuillez sélectionner un rôle. @enderror
                            </div>
                        </div>

                        {{-- Company --}}
                        <div class="mb-3 col-md-6">
                            <label for="company_id" class="form-label">Entreprise</label>
                            @if(auth()->user()->hasRole('superadmin'))
                                <select name="company_id" class="form-select @error('company_id') is-invalid @enderror" required>
                                    <option value="">Sélectionner une entreprise...</option>
                                    @foreach ($companies as $id => $name)
                                        <option value="{{ $id }}"
                                            @selected(old('company_id', $user->company_id) == $id)>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    @error('company_id') {{ $message }} @else Veuillez sélectionner une entreprise. @enderror
                                </div>
                            @else
                                <input type="hidden" name="company_id" value="{{ auth()->user()->company_id }}">
                            @endif
                        </div>

                    </div>
                </div>

                <div class="card-footer text-end">
                    <a href="{{ route('backoffice.users.index') }}" class="btn btn-secondary"
                       onclick="rollOutCard(event, this, 'user-form-card')">Annuler</a>
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
    function rollOutCard(event, link, cardId = 'user-form-card') {
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
