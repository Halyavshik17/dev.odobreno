<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Entities\Expense;
use Modules\Inventory\Entities\InventoryParts;
use Modules\Purchase\Entities\PurchaseDetail;
use Modules\VehicleMaintenance\Entities\VehicleMaintenance;
use Modules\VehicleMaintenance\Entities\VehicleMaintenanceDetail;
use Modules\VehicleManagement\Entities\LegalDocumentation;
use Modules\VehicleManagement\Entities\PickupAndDrop;
use Modules\VehicleManagement\Entities\VehicleRequisition;
use Modules\VehicleRefueling\Entities\FuelRequisition;

class DashboardController extends Controller
{
    /**
     * Constructor for the controller.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'status_check'])->except(['redirectToDashboard']);
        \cs_set('theme', [
            'title' => 'Dashboard',
            'back' => \back_url(),
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => false,
                ],

            ],
            'rprefix' => 'admin.dashboard',
        ]);
    }

    public function index()
    {
        $data = [];
        if (can('maintenance_report')) {
            $data['line_chart'] = $this->getLineChartData();
        }
        if (can('expense_report')) {
            $data['doughnut_chart'] = $this->getPieChartData();
        }
        if (can('vehicle_requisition_report')) {
            $data['venn_diagram'] = $this->getVennDiagramData();
            $data['multi_axis_line'] = $this->getMultiAxisLineData();
        }
        $total_requisitions = VehicleRequisition::where('status', 0)->count();
        $total_maintenances = VehicleMaintenance::where('status', 'pending')->count();

        $available_requisitions = $total_requisitions - $total_maintenances >= 0 ? $total_requisitions - $total_maintenances : 0;

        $todays_requisitions = VehicleRequisition::whereDate('requisition_date', date('Y-m-d'))->where('status', 0)->count();
        $todays_pick_drops = PickupAndDrop::whereDate('effective_date', date('Y-m-d'))->where('status', 0)->count();
        $todays_maintenances = VehicleMaintenance::whereDate('date', date('Y-m-d'))->where('status', 0)->count();
        $todays_fuel_requisitions = FuelRequisition::whereDate('date', date('Y-m-d'))->count();

        $doc_expire_soon = LegalDocumentation::where('expiry_date', '>', date('Y-m-d'))->where('expiry_date', '<', date('Y-m-d', strtotime('+30 days')))->count();
        $doc_expired = LegalDocumentation::where('expiry_date', '<', date('Y-m-d'))->count();

        $totalStockIn = InventoryParts::where('is_active', true)->sum('qty') + PurchaseDetail::whereHas('purchase', function ($query) {
            $query->where('status', 'approved');
        })->sum('qty');
        $totalStockOut = VehicleMaintenanceDetail::whereHas('maintenance', function ($query) {
            $query->where('status', 'approved');
        })->sum('qty');

        $reminders = LegalDocumentation::with(['vehicle', 'document_type'])->paginate(15);

        return view('dashboard', [
            'total_requisitions' => $total_requisitions,
            'total_maintenances' => $total_maintenances,
            'available' => $available_requisitions,
            'todays_requisitions' => $todays_requisitions,
            'todays_pick_drops' => $todays_pick_drops,
            'todays_maintenances' => $todays_maintenances,
            'todays_fuel_requisitions' => $todays_fuel_requisitions,
            'reminders' => $reminders,
            'doc_expire_soon' => $doc_expire_soon,
            'doc_expired' => $doc_expired,
            'totalStockIn' => $totalStockIn,
            'totalStockOut' => $totalStockOut,
            'data' => $data,
        ]);
    }

    public function redirectToDashboard()
    {
        return redirect()->route('admin.dashboard');
    }

    public function getLineChartData()
    {
        $endDate = Carbon::now();

        // Subtract 11 months from the current date to get the starting date for the last 12 months
        $startDate = $endDate->copy()->subMonths(11);

        // Initialize an empty array to store formatted data
        $data = [];

        // Query to retrieve monthly maintenance cost for the last 12 months
        $monthlyCosts = DB::table('vehicle_maintenances')
            ->select(DB::raw('YEAR(date) as year'), DB::raw('MONTH(date) as month'), DB::raw('SUM(total) as total_cost'))
            ->whereBetween('date', [$startDate->startOfMonth(), $endDate->endOfMonth()])
            ->groupBy(DB::raw('YEAR(date)'), DB::raw('MONTH(date)'))
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Iterate over each month of the last 12 months
        for ($i = 0; $i < 12; $i++) {
            // Initialize cost to 0 for each month
            $cost = 0;

            // Check if there's data available for the current month
            $currentMonthData = $monthlyCosts->where('year', $endDate->year)->where('month', $endDate->month)->first();

            $cost = $currentMonthData->total_cost ?? 0;

            // Format the date and store the month and cost in the data array

            $data[] = [
                'label' => $endDate->format('M'),
                'value' => (int) $cost,
                // if even then #FF5733 else #000000
                'color' => $i % 2 == 0 ? '#FF5733' : '#000000',
            ];
            // Move to the previous month for the next iteration
            $endDate->subMonth();
        }
        // Reverse the data array to maintain chronological order
        $data = array_reverse($data);

        return $data;
    }

    public function getPieChartData()
    {
        // Get all expense types
        $types = Expense::getTypes();

        // Get the current date and date 12 months ago
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subMonths(12);

        // Fetch all expenses within the last 12 months
        $expenses = Expense::whereBetween('date', [$startDate, $endDate])->get();

        // Initialize an array to store data
        $data = [];

        // Loop through each expense type
        foreach ($types as $type => $typeName) {
            // Calculate the total sum for the current type
            $total = $expenses->where('type', $type)->sum('total');

            // Add data to the array
            $data[] = [
                'category' => $typeName,
                'value' => $total,
            ];
        }

        return $data;
    }

    public function getVennDiagramData()
    {
        $statues = [
            'pending' => [
                'name' => localize('Pending'),
                'value' => 0,
                'color' => '#dfd7d7',
            ],
            'approved' => [
                'name' => localize('Approved'),
                'value' => 0,
                'color' => '#17c653',
            ],
            'rejected' => [
                'name' => localize('Rejected'),
                'value' => 0,
                'color' => '#dc3545e0',
                // "sets" => [localize('Pending'),  localize('Approved')],
            ],
        ];

        $endDate = Carbon::now();
        $startDate = $endDate->copy()->subMonths(11);
        // Get last 12 months data for vehicle requisitions status in percentage
        $data = VehicleRequisition::select(DB::raw('status'), DB::raw('COUNT(*) as total'))
            ->whereBetween('requisition_date', [$startDate->startOfMonth(), $endDate->endOfMonth()])
            ->groupBy('status')
            ->get();

        foreach ($data as $item) {
            $statues[$item->status]['value'] = $item->total;
        }

        return $statues;
    }

    public function getMultiAxisLineData()
    {
        $endDate = Carbon::now();

        // Subtract 11 months from the current date to get the starting date for the last 12 months
        $startDate = $endDate->copy()->subMonths(11);

        // Initialize an empty array to store formatted data
        $data = [];

        // Query to retrieve monthly maintenance cost for the last 12 months
        $monthlyRequisitions = VehicleRequisition::select(DB::raw('YEAR(requisition_date) as year'), DB::raw('MONTH(requisition_date) as month'), DB::raw('COUNT(*) as total'), DB::raw('status'))
            ->whereBetween('requisition_date', [$startDate->startOfMonth(), $endDate->endOfMonth()])
            ->groupBy(DB::raw('YEAR(requisition_date)'), DB::raw('MONTH(requisition_date)'), 'status')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        // Iterate over each month of the last 12 months
        for ($i = 0; $i < 12; $i++) {
            $pending = $monthlyRequisitions->where('year', $endDate->year)->where('month', $endDate->month)->where('status', 'pending')->first()->total ?? 0;
            $approved = $monthlyRequisitions->where('year', $endDate->year)->where('month', $endDate->month)->where('status', 'approved')->first()->total ?? 0;
            $data[] = [
                // date: new Date(2021, 0, 1).getTime(),

                'date' => $endDate->startOfMonth()->timestamp * 1000,
                'pending' => $pending,
                'approved' => $approved,
            ];
            // Move to the previous month for the next iteration
            $endDate->subMonth();
        }
        // Reverse the data array to maintain chronological order
        $data = array_reverse($data);

        return $data;
    }
}
