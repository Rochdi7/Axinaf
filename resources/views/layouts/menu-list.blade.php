{{-- Dashboard --}}
<li class="pc-item {{ request()->routeIs('backoffice.dashboard') ? 'active' : '' }}">
    <a href="{{ route('backoffice.dashboard') }}" class="pc-link">
        <span class="pc-micon"><i class="ph-duotone ph-gauge"></i></span>
        <span class="pc-mtext">Tableau de bord</span>
    </a>
</li>

{{-- Checklists --}}
<li class="pc-item {{ request()->routeIs('checklists.*') ? 'active' : '' }}">
    <a href="{{ route('checklists.index') }}" class="pc-link">
        <span class="pc-micon"><i class="ph-duotone ph-list-checks"></i></span>
        <span class="pc-mtext">Checklists</span>
    </a>
</li>

{{-- Domains --}}
<li class="pc-item {{ request()->routeIs('domains.*') ? 'active' : '' }}">
    <a href="{{ route('domains.index') }}" class="pc-link">
        <span class="pc-micon"><i class="ph-duotone ph-columns"></i></span>
        <span class="pc-mtext">Domaines</span>
    </a>
</li>

{{-- Client QCM --}}
@role('client')
<li class="pc-item {{ request()->routeIs('client.qcm.*') ? 'active' : '' }}">
    <a href="{{ route('client.qcm.index') }}" class="pc-link">
        <span class="pc-micon"><i class="ph-duotone ph-list-checks"></i></span>
        <span class="pc-mtext">Mes QCM</span>
    </a>
</li>
@endrole

{{-- Admin/Superadmin menu items --}}
@role('superadmin|admin')
<li class="pc-item {{ request()->routeIs('backoffice.company-qcm.*') ? 'active' : '' }}">
    @if(Auth::user()->company_id)
        <a href="{{ route('backoffice.company-qcm.listDomains', Auth::user()->company_id) }}" class="pc-link">
    @else
        <a href="#" class="pc-link disabled">
    @endif
        <span class="pc-micon"><i class="ph-duotone ph-clipboard-text"></i></span>
        <span class="pc-mtext">QCM Entreprises</span>
    </a>
</li>

{{-- Action Plans --}}
<li class="pc-item {{ request()->routeIs('backoffice.action-plans.*') ? 'active' : '' }}">
    @if(Auth::user()->company_id)
        <a href="{{ route('backoffice.action-plans.index', Auth::user()->company_id) }}" class="pc-link">
    @else
        <a href="#" class="pc-link disabled">
    @endif
        <span class="pc-micon"><i class="ph-duotone ph-file-text"></i></span>
        <span class="pc-mtext">Plans d'action</span>
    </a>
</li>

{{-- Users menu --}}
<li class="pc-item {{ request()->routeIs('backoffice.users.*') ? 'active' : '' }}">
    <a href="{{ route('backoffice.users.index') }}" class="pc-link">
        <span class="pc-micon"><i class="ph-duotone ph-users-three"></i></span>
        <span class="pc-mtext">Utilisateurs</span>
    </a>
</li>
@endrole

{{-- Companies menu (superadmin only) --}}
@role('superadmin')
<li class="pc-item {{ request()->routeIs('backoffice.companies.*') ? 'active' : '' }}">
    <a href="{{ route('backoffice.companies.index') }}" class="pc-link">
        <span class="pc-micon"><i class="ph-duotone ph-buildings"></i></span>
        <span class="pc-mtext">Entreprises</span>
    </a>
</li>
@endrole