<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use App\Models\Domain;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of companies.
     */
    public function index()
    {
        $companies = Company::latest()->paginate(20);
        return view('backoffice.companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new company.
     */
    public function create()
    {
        // Get active domains for selection
        $domains = Domain::where('is_active', true)->get();

        return view('backoffice.companies.create', compact('domains'));
    }

    /**
     * Store a newly created company in storage.
     */
    public function store(CompanyRequest $request)
    {
        $data = $request->validated();

        $company = Company::create($data);

        // Handle logo upload if provided
        if ($request->hasFile('logo')) {
            $company->addMediaFromRequest('logo')->toMediaCollection('logo');
        }

        // Sync domains if any
        if (!empty($data['domains'])) {
            $company->domains()->sync($data['domains']);
        }

        return redirect()->route('backoffice.companies.index')
                         ->with('success', 'Company created successfully.');
    }

    /**
     * Show the form for editing the specified company.
     */
    public function edit(Company $company)
    {
        // Get active domains for selection
        $domains = Domain::where('is_active', true)->get();
        $selectedDomains = $company->domains->pluck('id')->toArray();

        return view('backoffice.companies.edit', compact('company', 'domains', 'selectedDomains'));
    }

    /**
     * Update the specified company in storage.
     */
    public function update(CompanyRequest $request, Company $company)
    {
        $data = $request->validated();

        $company->update($data);

        // Replace logo if new file uploaded
        if ($request->hasFile('logo')) {
            $company->clearMediaCollection('logo');
            $company->addMediaFromRequest('logo')->toMediaCollection('logo');
        }

        // Sync domains
        $company->domains()->sync($data['domains'] ?? []);

        return redirect()->route('backoffice.companies.index')
                         ->with('success', 'Company updated successfully.');
    }

    /**
     * Remove the specified company.
     */
    public function destroy(Company $company)
    {
        $company->delete();

        return redirect()->route('backoffice.companies.index')
                         ->with('success', 'Company deleted successfully.');
    }
}
