@extends('layouts.main')

@section('title', 'Gestion des checklists')
@section('breadcrumb-item', 'Administration')
@section('breadcrumb-item-active', 'Checklists')

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
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Liste des checklists</h5>
                <a href="{{ route('checklists.create') }}" class="btn btn-primary">Nouvelle checklist</a>
            </div>
            <div class="card-body pt-3">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Titre</th>
                                <th>Domaine</th>
                                <th>Créée par</th>
                                <th>Statut</th>
                                <th>Créée le</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($checklists as $checklist)
                                <tr>
                                    <td>{{ $loop->iteration + ($checklists->currentPage()-1)*$checklists->perPage() }}</td>
                                    <td>{{ $checklist->title }}</td>
                                    <td>{{ $checklist->domain->title ?? '—' }}</td>
                                    <td>{{ $checklist->creator?->name ?? '—' }}</td>
                                    <td>
                                        @if ($checklist->is_active)
                                            <span class="badge bg-light-success text-success">✔ Active</span>
                                        @else
                                            <span class="badge bg-light-danger text-danger">⛔ Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $checklist->created_at?->format('d/m/Y') ?? '—' }}</td>
                                    <td>
                                        <a href="{{ route('checklists.edit', $checklist) }}"
                                           class="btn btn-sm btn-outline-secondary" title="Modifier">
                                            <i class="ti ti-edit"></i>
                                        </a>

                                        <form action="{{ route('checklists.destroy', $checklist) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Supprimer cette checklist ?')" title="Supprimer">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Aucune checklist trouvée.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    <div class="mt-3">
                        {{ $checklists->links() }}
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
