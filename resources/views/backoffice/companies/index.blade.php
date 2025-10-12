@extends('layouts.main')

@section('title', 'Gestion des entreprises')
@section('breadcrumb-item', 'Administration')
@section('breadcrumb-item-active', 'Entreprises')

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
                    <h5 class="mb-3 mb-sm-0">Liste des entreprises</h5>
                    <div>
                        <a href="{{ route('backoffice.companies.create') }}" class="btn btn-primary">
                            Ajouter une entreprise
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
                                <th>Description</th>
                                <th>Domaines</th>
                                <th>Statut</th>
                                <th>Créé le</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($companies as $company)
                            <tr>
                                <td>{{ $company->name }}</td>
                                <td>{{ $company->description ?? '—' }}</td>
                                <td>
                                    @if($company->domains->isNotEmpty())
                                        @foreach($company->domains as $domain)
                                            <span class="badge bg-info text-dark">{{ $domain->title }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">Aucun</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($company->is_active)
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-secondary">Inactif</span>
                                    @endif
                                </td>
                                <td>{{ $company->created_at?->format('d/m/Y') ?? '—' }}</td>
                                <td>
                                    <a href="{{ route('backoffice.companies.edit', $company) }}"
                                       class="avtar avtar-xs btn-link-secondary me-2" title="Modifier">
                                        <i class="ti ti-edit f-20"></i>
                                    </a>

                                    <form action="{{ route('backoffice.companies.destroy', $company) }}"
                                          method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="avtar avtar-xs btn-link-secondary border-0 bg-transparent p-0"
                                                onclick="return confirm('Supprimer cette entreprise ?')" title="Supprimer">
                                            <i class="ti ti-trash f-20"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Aucune entreprise trouvée.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-3">
                        {{ $companies->links() }}
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
    document.addEventListener("DOMContentLoaded", function() {
        const toastEl = document.getElementById('liveToast');
        if (toastEl) {
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        }
    });
</script>
@endsection
