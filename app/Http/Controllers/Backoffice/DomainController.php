<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\DomainRequest;
use App\Models\Domain;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DomainController extends Controller
{
    public function __construct()
    {
        // Optional: Ensure only superadmin can access domain management
        $this->middleware('role:superadmin');
    }

    /**
     * Display a listing of the domains.
     */
    public function index()
    {
        // Eager load companies to avoid N+1 problem
        $domains = Domain::with('companies')->latest()->paginate(10);

        return view('backoffice.domains.index', compact('domains'));
    }

    /**
     * Show the form for creating a new domain.
     */
    public function create()
    {
        $companies = Company::where('is_active', true)->get();

        return view('backoffice.domains.create', compact('companies'));
    }

    /**
     * Store a newly created domain in storage.
     */
    public function store(DomainRequest $request)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            $domain = Domain::create($data);

            // Sync companies safely
            $domain->companies()->sync($data['company_ids'] ?? []);
        });

        return redirect()->route('backoffice.domains.index')
                         ->with('success', 'Le domaine a été créé avec succès.');
    }

    /**
     * Show the form for editing the specified domain.
     */
    public function edit(Domain $domain)
    {
        $companies = Company::where('is_active', true)->get();
        $selectedCompanies = $domain->companies->pluck('id')->toArray();

        return view('backoffice.domains.edit', compact('domain', 'companies', 'selectedCompanies'));
    }

    /**
     * Update the specified domain in storage.
     */
    public function update(DomainRequest $request, Domain $domain)
    {
        $data = $request->validated();

        DB::transaction(function () use ($domain, $data) {
            $domain->update($data);

            // Sync companies safely
            $domain->companies()->sync($data['company_ids'] ?? []);
        });

        return redirect()->route('backoffice.domains.index')
                         ->with('success', 'Le domaine a été mis à jour avec succès.');
    }

    /**
     * Remove the specified domain from storage.
     */
    public function destroy(Domain $domain)
    {
        DB::transaction(function () use ($domain) {
            // Optional: detach companies to avoid orphaned pivot entries
            $domain->companies()->detach();

            // Soft delete if enabled, otherwise hard delete
            $domain->delete();
        });

        return redirect()->route('backoffice.domains.index')
                         ->with('success', 'Le domaine a été supprimé avec succès.');
    }
}
