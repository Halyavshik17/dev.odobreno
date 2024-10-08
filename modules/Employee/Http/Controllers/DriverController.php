<?php

namespace Modules\Employee\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Employee\DataTables\DriverDataTable;
use Modules\Employee\Entities\Driver;
use Modules\Employee\Entities\LicenseType;
use Modules\Companies\Entities\Company;

use App\Models\User;

class DriverController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'permission:employee_management', 'status_check']);
        $this->middleware('strip_scripts_tag')->only(['store', 'update']);
        \cs_set('theme', [
            'title' => 'Driver List',
            'description' => 'Displaying all Drivers.',
            'back' => \back_url(),
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Driver List',
                    'link' => false,
                ],
            ],
            'rprefix' => 'admin.driver',
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(DriverDataTable $dataTable)
    {
        return $dataTable->render('employee::driver.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $is_creatable = true;

        $license_types = LicenseType::all();
        if(canManageSettings())
        {
            $companies = Company::all();
            return view('employee::driver.create_edit', [
                'license_types' => $license_types,
                'companies' => $companies,
                'is_creatable' => $is_creatable,
            ])->render();
        }
        else
        {
            $company = getFirstCompanyUser();
            return view('employee::driver.create_edit', [
                'license_types' => $license_types,
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
        // Валидация входящих данных
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'license_type_ids' => 'required|array',
            'license_type_ids.*' => 'integer|exists:license_types,id',
            'license_num' => 'required|string|max:255',
            'license_issue_date' => 'required|date',
            'nid' => 'required|string|max:255',
            'dob' => 'required|date',
            'joining_date' => 'required|date',
            'working_time_slot' => 'nullable|string|max:255',
            'present_address' => 'nullable|string|max:255',
            'permanent_address' => 'nullable|string|max:255',
            'leave_status' => 'required|boolean',
            'is_active' => 'nullable|boolean',
            'picture' => 'nullable|image|max:2048',
        ]);
    
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
    
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
    
        if ($request->hasFile('picture')) {
            $data['avatar_path'] = upload_file($request, 'picture', 'driver');
        }
    
        // Создаем нового водителя
        $driver = Driver::create($data);
    
        $driver->licenseTypes()->sync($data['license_type_ids']);
        if(!canManageSettings()) {
            $company = getFirstCompanyUser();
            $driver->companies()->sync($company->id);
        }
        else {
            $driver->companies()->sync($data['company_id']);
        }
    
        return response()->json(['success' => 'Driver created successfully.']);
    }
    

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     */
    public function show($id)
    {
        return view('employee::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     */
    public function edit(Driver $driver)
    {
        $is_creatable = false;

        $license_types = LicenseType::all();
        $selectedLicenseTypes = $driver->licenseTypes()->pluck('id')->toArray();

        $company = $driver->primaryCompany();

        return view('employee::driver.create_edit', [
            'item' => $driver,
            'company' => $company,
            'license_types' => $license_types,
            'selectedLicenseTypes' => $selectedLicenseTypes,
            'is_creatable' => $is_creatable,
        ])->render();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     */
    public function update(Request $request, Driver $driver)
    {

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'license_type_ids' => 'required|array', 
            'license_type_ids.*' => 'integer|exists:license_types,id',
            'license_num' => 'required|string|max:255',
            'license_issue_date' => 'required|date',
            'nid' => 'required|string|max:255',
            'dob' => 'required|date',
            'joining_date' => 'required|date',
            'working_time_slot' => 'nullable|string|max:255',
            'present_address' => 'nullable|string|max:255',
            'permanent_address' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'leave_status' => 'required|boolean'
        ]);

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

        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('picture')) {
            $data['avatar_path'] = upload_file($request, 'picture', 'driver');
        }

        $driver->licenseTypes()->sync($data['license_type_ids']);

        // если не админ, обновляем с текущей компанией
        if(!canManageSettings()) {
            $company = getFirstCompanyUser();
            $driver->companies()->sync($company->id);
        }
        else {
            $driver->companies()->sync($data['company_id']);
        }

        $driver->update($data);

        return response()->json(['success' => 'Driver updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy(Driver $driver)
    {
        $driver->delete();
        return response()->success(null, localize('Driver Deleted Successfully'), 200);
    }
}
