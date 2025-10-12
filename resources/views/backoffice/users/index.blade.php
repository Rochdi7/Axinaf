@extends('layouts.main')

@section('title', 'Gestion des utilisateurs')
@section('breadcrumb-item', 'Administration')
@section('breadcrumb-item-active', 'Utilisateurs')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/css/plugins/style.css') }}">
@endsection

@section('content')

    {{-- Toast notification --}}
    @if(session('toast') || session('success') || session('error'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 99999">
            <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <img src="{{ asset('favicon.svg') }}" class="img-fluid me-2" alt="favicon" style="width: 17px">
                    <strong class="me-auto">Axinaf</strong>
                    <small>Just now</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    {{ session('toast') ?? session('success') ?? session('error') }}
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card table-card">
                <div class="card-header">
                    <div class="d-sm-flex align-items-center justify-content-between">
                        <h5 class="mb-3 mb-sm-0">Liste des utilisateurs</h5>
                        <div>
                            <a href="{{ route('backoffice.users.create') }}" class="btn btn-primary">
                                Ajouter un utilisateur
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body pt-3">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Rôle(s)</th>
                                    <th>Entreprise</th>
                                    <th>Créé le</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->getRoleNames()->join(', ') }}</td>
                                        <td>{{ $user->company?->name ?? '—' }}</td>
                                        <td>{{ $user->created_at?->format('d/m/Y') ?? '—' }}</td>
                                        <td>
                                            <a href="{{ route('backoffice.users.edit', $user) }}"
                                               class="avtar avtar-xs btn-link-secondary me-2" title="Modifier">
                                                <i class="ti ti-edit f-20"></i>
                                            </a>

                                            <form action="{{ route('backoffice.users.destroy', $user) }}"
                                                  method="POST" style="display:inline-block;">
                                                @csrf @method('DELETE')
                                                <button class="avtar avtar-xs btn-link-secondary border-0 bg-transparent p-0"
                                                        onclick="return confirm('Supprimer cet utilisateur ?')" title="Supprimer">
                                                    <i class="ti ti-trash f-20"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Aucun utilisateur trouvé.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="mt-3">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script type="module">
        import { DataTable } from "/build/js/plugins/module.js";
        window.dt = new DataTable("#pc-dt-simple");
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const toastEl = document.getElementById('liveToast');
            if (toastEl) {
                const toast = new bootstrap.Toast(toastEl);
                toast.show();
            }
        });
    </script>
@endsection
