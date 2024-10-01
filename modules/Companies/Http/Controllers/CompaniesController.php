<?php

namespace Modules\Companies\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Modules\Companies\Entities\Company;
use Modules\Companies\DataTables\CompaniesDataTable;

use Illuminate\Support\Facades\Log;

class CompaniesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'permission:company_management']);
        $this->middleware('strip_scripts_tag')->only(['store', 'update']);
        //set the request middleware for the controller
        $this->middleware('request:ajax', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
        \cs_set('theme', [
            'title' => 'Setting',
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Company Setting',
                    'link' => false,
                ],
            ],
            'rprefix' => 'admin.companies',
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(CompaniesDataTable $dataTable)
    {
        return $dataTable->render('companies::index');
    }

/**
     * Create the specified resource.
     */
    public function create()
    {
        cs_set('theme', [
            'title' => 'Create New Language',
        ]);

        return view('companies::create_edit')->render();
    }

/**
     * Show the show page for showing the specified resource.
     */
    public function edit(Company $company)
    {
        //Log::info('Ура: '. $company);

        cs_set('theme', [
            'title' => 'Edit Existing Company',
            'update' => route(config('theme.rprefix') . '.update', $company->id),
        ]);

        // return the response
        return view('companies::create_edit')->with('item', $company)->render();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'ogrn' => 'required',
            'inn' => 'required',
            'kpp' => 'required',
            'address_legal' => 'required',
            'address_phys' => 'required',
            'phone_number' => 'required',
        ]);

        $company = Company::create($request);

        return response()->success($company, 'Local Company created successfully.', 200);
        // return redirect()->route('companies::index');
    }

    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required',
            'ogrn' => 'required',
            'inn' => 'required',
            'kpp' => 'required',
            'address_legal' => 'required',
            'address_phys' => 'required',
            'phone_number' => 'required',
        ]);

        $company->update($request->all());
        return response()->success($company, 'Local Company update successfully.', 200);
        //return redirect()->route('companies::index');
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return response()->success($company, 'Local Company deleted successfully.', 200);
        //return redirect()->route('companies::index');
    }
}