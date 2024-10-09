<?php

namespace Modules\Employee\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Employee\DataTables\EmployeeDataTable;
use Modules\Employee\Entities\Department;
use Modules\Employee\Entities\Employee;
use Modules\Employee\Entities\Position;
use Modules\Employee\Http\Requests\EmployeeRequest;

use Modules\Companies\Entities\Company;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'permission:employee_management', 'status_check']);
        $this->middleware('strip_scripts_tag')->only(['store', 'update']);
        \cs_set('theme', [
            'title' => 'Employee List',
            'description' => 'Displaying all Employees.',
            'back' => \back_url(),
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Employee List',
                    'link' => false,
                ],
            ],
            'rprefix' => 'admin.employee',
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(EmployeeDataTable $dataTable)
    {

        $departments = Department::all();
        $positions = Position::all();

        return $dataTable->render('employee::employee.index', [
            'departments' => $departments,
            'positions' => $positions,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::all();
        $positions = Position::all();

        $is_creatable = true;

        if(canManageSettings())
        {
            $companies = Company::all();
            return view('employee::employee.create_edit', [
                'companies' => $companies,
                'is_creatable' => $is_creatable,
                'departments' => $departments,
                'positions' => $positions,
            ])->render();
        }
        else
        {
            $company = getFirstCompanyUser();
            return view('employee::employee.create_edit', [
                'company' => $company,
                'is_creatable' => $is_creatable,
                'departments' => $departments,
                'positions' => $positions,
            ])->render();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     */
    public function store(EmployeeRequest $request)
    {

        $data = $request->validated();

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

        if ($request->hasFile('picture')) {
            $data['avatar_path'] = upload_file($request, 'picture', 'employee');
        }

        $item = Employee::create($data);

        if(!canManageSettings()) {
            $company = getFirstCompanyUser();
            $item->companies()->sync($company->id);
        }
        else {
            $item->companies()->sync($data['company_id']);
        }

        return response()->success($item, localize('Employee Added Successfully'), 201);
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
    public function edit(Employee $employee)
    {
        $departments = Department::all();
        $positions = Position::all();

        $is_creatable = false;
        $company = $employee->primaryCompany();

        return view('employee::employee.create_edit', [
            'item' => $employee,
            'departments' => $departments,
            'positions' => $positions,
            'company' => $company,
            'is_creatable' => $is_creatable,
        ])->render();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     */
    public function update(EmployeeRequest $request, Employee $employee)
    {

        $data = $request->validated();

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

        if ($request->hasFile('picture')) {
            $data['avatar_path'] = upload_file($request, 'picture', 'employee');

            if ($employee->avatar_path) {
                delete_file($employee->avatar_path);
            }
        } else {
            $data['avatar_path'] = $employee->avatar_path;
        }

        if(!canManageSettings()) {
            $company = getFirstCompanyUser();
            $employee->companies()->sync($company->id);
        }
        else {
            $employee->companies()->sync($data['company_id']);
        }

        $employee->update($data);

        return response()->success($employee, localize('Employee Updated Successfully'), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return response()->success(null, localize('Employee Deleted Successfully'), 200);
    }
}
