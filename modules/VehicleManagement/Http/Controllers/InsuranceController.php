<?php

namespace Modules\VehicleManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\VehicleManagement\DataTables\InsuranceDataTable;
use Modules\VehicleManagement\Entities\Insurance;
use Modules\VehicleManagement\Entities\Vehicle;
use Modules\VehicleManagement\Entities\VehicleInsuranceCompany;
use Modules\VehicleManagement\Entities\VehicleInsuranceRecurringPeriod;

use Modules\Companies\Entities\Company;

class InsuranceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'permission:insurance_management']);
        $this->middleware('request:ajax', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
        $this->middleware('strip_scripts_tag')->only(['store', 'update']);
        \cs_set('theme', [
            'title' => 'Insurance Details',
            'back' => \back_url(),
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Insurance Details',
                    'link' => false,
                ],
            ],
            'rprefix' => 'admin.vehicle.insurance',
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(InsuranceDataTable $dataTable)
    {
        \cs_set('theme', [
            'description' => 'Display a listing of Insurance in Database.',
        ]);

        return $dataTable->render('vehiclemanagement::insurance.index', [
            'insurance_companies' => VehicleInsuranceCompany::where('is_active', 1)->get(),
            'vehicles' => Vehicle::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $is_creatable = true;

        if(canManageSettings())
        {
            $companies = Company::all();
            return view('vehiclemanagement::insurance.create_edit', [
                'insurance_companies' => VehicleInsuranceCompany::where('is_active', 1)->get(),
                'vehicles' => Vehicle::all(),
                'recurring_periods' => VehicleInsuranceRecurringPeriod::where('is_active', 1)->get(),
                'companies' => $companies,
                'is_creatable' => $is_creatable,
            ])->render();
        }
        else
        {
            $company = getFirstCompanyUser();
            return view('vehiclemanagement::insurance.create_edit', [
                'insurance_companies' => VehicleInsuranceCompany::where('is_active', 1)->get(),
                'vehicles' => Vehicle::all(),
                'recurring_periods' => VehicleInsuranceRecurringPeriod::where('is_active', 1)->get(),
                'company' => $company,
                'is_creatable' => $is_creatable,
            ])->render();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'insurance_company_id' => 'required|integer',
            'vehicle_id' => 'required|integer',
            'policy_number' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'charge_payable' => 'required|numeric',
            'deductible' => 'required|numeric',
            'recurring_date' => 'nullable|date',
            'recurring_period_id' => 'required|integer',
            'status' => 'nullable|boolean',
            'add_reminder' => 'nullable|boolean',
            'remarks' => 'nullable|string',
            'policy_document' => 'nullable|file',
        ]);

        if ($request->hasFile('policy_document')) {
            $data['policy_document_path'] = upload_file($request, 'policy_document', 'insurance_policy_documents');
        }

        if (canManageSettings()) {
            $data['company_id'] = $request->validate([
                'company_id' => 'required|integer|exists:companies,id',
            ])['company_id'];
        } else {
            $company = getFirstCompanyUser();
            if ($company) {
                $data['company_id'] = $company->id;
            } else {
                return response()->json(['error' => 'No associated company found.'], 400);
            }
        }

        $item = Insurance::create($data);

        if(!canManageSettings()) {
            $company = getFirstCompanyUser();
            $item->companies()->sync($company->id);
        }
        else {
            $item->companies()->sync($data['company_id']);
        }

        return response()->success($item, localize('Insurance Added Successfully'), 201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     */
    public function edit(Insurance $insurance)
    {
        $is_creatable = false;
        $company = $insurance->primaryCompany();

        return view('vehiclemanagement::insurance.create_edit', [
            'insurance_companies' => VehicleInsuranceCompany::where('is_active', 1)->get(),
            'vehicles' => Vehicle::all(),
            'recurring_periods' => VehicleInsuranceRecurringPeriod::where('is_active', 1)->get(),
            'item' => $insurance,
            'company' => $company,
            'is_creatable' => $is_creatable,
        ])->render();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Insurance $insurance)
    {

        $data = $request->validate([
            'insurance_company_id' => 'required|integer',
            'vehicle_id' => 'required|integer',
            'policy_number' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'charge_payable' => 'required|numeric',
            'deductible' => 'required|numeric',
            'recurring_date' => 'nullable|date',
            'recurring_period_id' => 'required|integer',
            'status' => 'nullable|boolean',
            'add_reminder' => 'nullable|boolean',
            'remarks' => 'nullable|string',
        ]);

        if ($request->hasFile('policy_document')) {
            $data['policy_document_path'] = upload_file($request, 'policy_document', 'insurance_policy_documents');

            if ($insurance->policy_document_path) {
                delete_file($insurance->policy_document_path);
            }
        }

        if (canManageSettings()) {
            $data['company_id'] = $request->validate([
                'company_id' => 'required|integer|exists:companies,id',
            ])['company_id'];
        } else {
            $company = getFirstCompanyUser();
            if ($company) {
                $data['company_id'] = $company->id;
            } else {
                return response()->json(['error' => 'No associated company found.'], 400);
            }
        }

        if(!canManageSettings()) {
            $company = getFirstCompanyUser();
            $insurance->companies()->sync($company->id);
        }
        else {
            $insurance->companies()->sync($data['company_id']);
        }

        $insurance->update($data);

        return response()->success($insurance, localize('Insurance Updated Successfully'), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Insurance $insurance)
    {
        if ($insurance->policy_document_path) {
            delete_file($insurance->policy_document_path);
        }

        $insurance->delete();
        return response()->success($insurance, localize('Insurance Deleted Successfully'), 200);
    }
}
