<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Employees;
use App\Models\PayrollE;
use App\Models\Timekeeping;
use App\Models\Taxation;
use Spatie\ActivityLog\Models\Activity;
use Carbon\Carbon;
use PDF;

class Payroll extends Controller
{
    public function index1()
    {
        $employees = Employees::all(); // Retrieve all employees from the database
        $payroll = PayrollE::paginate(6);
        $timekeeping = Timekeeping::all();
        
        return view('payroll', compact('employees', 'payroll', 'timekeeping'));
        
    }
    public function index2()
    {
        $employees = Employees::all(); // Retrieve all employees from the database
        $payroll = PayrollE::paginate(9);
        $timekeeping = Timekeeping::all();
        
        return view('reports', compact('employees', 'payroll', 'timekeeping'));
        
    }
    public function index3()
    {
        $employees = Employees::all(); // Retrieve all employees from the database
        $payroll = PayrollE::paginate(9);
        $timekeeping = Timekeeping::all();
        
        return view('ownerreports', compact('employees', 'payroll', 'timekeeping'));
        
    }
    public function savePayroll(Request $request)
    {
        $request->validate([
            'employeeID' => 'required',
            'employeeName' => 'required',
            'rph' => 'required',
            'salary' => 'required',
            'twh' => 'required',
        ]);
        $id = $request->iD;
        $employeeID = $request->employeeID;
        $employeeName = $request->employeeName;
        $rph = $request->rph;
        $salary = $request->salary;
        $twh = $request->twh;
        $sss = $request->sss;
        $philhealth = $request->philhealth;
        $pagibig = $request->pagibig;
        $late = $request->late;
        $benefits = $request->benefits;
        
       
        $s3 = $request->sss;
        $ph = $request->philhealth;
        $trulab = $request->pagibig;
        $totalLate = $request->late;
        $totalovertime = $request->toh;
        $totalovertimecalculation = $rph * $totalovertime;
        $totalHoursWorked = $twh;
        $totalHoursWorkedwithLate = $totalHoursWorked - $totalLate;
        $grossincome = ($rph * $totalHoursWorkedwithLate) + $benefits + $totalovertimecalculation;
        $totaldeduction = $s3 + $ph + $trulab;
        $netincome = $grossincome - $totaldeduction;

        $tax = new Taxation();
        $tax->EmployeeID = $employeeID;
        $tax->EmployeeName = $employeeName;
        $tax->SSS = $sss;
        $tax->PHILHEALTH = $philhealth;
        $tax->PAGIBIG = $pagibig;
        $tax->save();

        $pay = new PayrollE();
        $pay->employeeID = $employeeID; 
        $pay->employeeName = $employeeName;
        $pay->rph = $rph;
        $pay->Salary = $salary;
        $pay->TotalHrs = $twh;
        $pay->Overtime = $totalovertime;
        $pay->GrossIncome = $grossincome;
        $pay->SSS = $sss;
        $pay->PHILHEALTH = $philhealth;
        $pay->PAGIBIG = $pagibig;
        $pay->TotalDeduction =$totaldeduction;
        $pay->Benefits = $benefits;
        $pay->NetIncome = $netincome;
        $pay->save();
    
        return redirect()->back()->with('success', 'Employee Payroll Added Successfully');
    }
    
    public function calculateTotalHours($timeIn, $timeOut)
    {
    $timeIn = strtotime($timeIn);
    $timeOut = strtotime($timeOut);

    $timeDifference = $timeOut - $timeIn;

    return $timeDifference / 3600;
}
    public function getTotalHours(Request $request){
        $employeeName = $request->input('employeeName');
        $startDate = $request->input('start_date');
        $endDate =$request->input('end_date');

        $employeeID = Timekeeping::where('EmployeeName', $employeeName)
        ->value('EmployeeID');
        
        $totalHours =$this->calculateTotalHoursForDateRange($employeeName, $startDate, $endDate);
        $totalLate =$this->calculateTotalLateArrival($employeeName, $startDate, $endDate);
        $totalOvertime = $this->calculateTotalOvertime($employeeName, $startDate, $endDate);
        $rateperday = $this->GetRatePerDay($employeeID);
        $rph = $rateperday /8;
        $totalhourswithLate = $totalHours - $totalLate;
    

        $salary = $totalhourswithLate * $rph;
        
        return response()->json(['totalHours' => $totalHours, 'totalLate' => $totalLate, 'employeeID' =>$employeeID,
         'totalOvertime' => $totalOvertime, 'rateperday' => $rateperday, 'salary'=> $salary]);
    }
    public function calculateTotalHoursForDateRange($employeeName, $startDate, $endDate)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate)->endOfDay();

        
        $timekeeping = Timekeeping::where('EmployeeName', $employeeName)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $totalHours = 0;

        
        
        foreach ($timekeeping as $time) {
            $totalHours += $this->calculateTotalHours($time->TimeIn, $time->TimeOut);
        }
        $totalBreak = count($timekeeping); // it count 1hr break per day
        $totalHours -= $totalBreak;


        return round($totalHours, 2);
    }
  
    public function calculateTotalLateArrival($employeeName, $startDate, $endDate)
    {
        $startDate = Carbon::parse($startDate, 'Asia/Manila');
        $endDate = Carbon::parse($endDate, 'Asia/Manila')->endOfDay();
    
        $timekeeping = Timekeeping::where('EmployeeName', $employeeName)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
    
        $totalLateMinutes = 0;
    
        foreach ($timekeeping as $time) {
    
            if ($time->LateArrival && $time->LateArrival !== '0000-00-00 00:00:00' && $time->LateArrival !== null) {
    
                $actualArrival = Carbon::parse($time->LateArrival, 'Asia/Manila');
        

                // Set the expected time on the same day as actual arrival
                $expectedTimeinOnSameDay = $actualArrival->copy()->startOfDay()->addHours(9);
    
                // Calculate the late arrival duration in minutes
                $lateArrivalDurationMinutes = max(0, $actualArrival->diffInMinutes($expectedTimeinOnSameDay));
    
                // Add the late arrival duration to the total late minutes
                $totalLateMinutes += $lateArrivalDurationMinutes;
    
            }
        }
    
        $totalLateHoursDecimal = $totalLateMinutes / 60;
    
        return round($totalLateHoursDecimal, 2);
    }
    public function calculateTotalOvertime($employeeName, $startDate, $endDate)
{
    $startDate = Carbon::parse($startDate);
    $endDate = Carbon::parse($endDate)->endOfDay();

    $overtimeRecords = Timekeeping::where('EmployeeName', $employeeName)
        ->whereBetween('created_at', [$startDate, $endDate])
        ->whereNotNull('Overtime')
        ->get();

    $totalOvertime = 0;

    foreach ($overtimeRecords as $record) {
        $totalOvertime += $record->Overtime;
    }

    return round($totalOvertime, 2);
}
    public function generatePayslip($id)
{
    $payroll = PayrollE::where('id', $id)->first();


    $pdf = PDF::loadView('payslip', compact('payroll'));

    activity()
    ->performedOn($payroll)
    ->causedBy(auth()->user())
    ->withProperties(['action' => 'payroll_generateslip'])
    ->log('Succesfully Download payslip as pdf');
    return $pdf->download('payslip.pdf');
}
public function filter(Request $request){
    $request->validate([
        'start_date' => 'required|date|before_or_equal:today',
        'end_date' => 'required|date|after_or_equal:start_date',
    ]);

    $start_date = $request->start_date;
    $end_date = $request->end_date;

    $payroll = PayrollE::whereDate('created_at', '>=', $start_date)
        ->whereDate('created_at', '<=', $end_date)
        ->get();
        session(['filteredPayroll' => $payroll]);
    return view('reports', compact('payroll'));
}
public function filter2(Request $request){
    $request->validate([
        'start_date' => 'required|date|before_or_equal:today',
        'end_date' => 'required|date|after_or_equal:start_date',
    ]);

    $start_date = $request->start_date;
    $end_date = $request->end_date;

    $payroll = PayrollE::whereDate('created_at', '>=', $start_date)
        ->whereDate('created_at', '<=', $end_date)
        ->get();
        session(['filteredPayroll' => $payroll]);
    return view('ownerreports', compact('payroll'));
}
public function generatesummaryreport(Request $request)
{
    // Retrieve the filtered data from session or request
    $filteredPayroll = $request->session()->get('filteredPayroll');

    // Check if there is no data
    if (empty($filteredPayroll)) {
        return redirect()->back()->with('error', 'No data found for the selected date range.');
    }

    $pdf = PDF::loadView('reportsummary', compact('filteredPayroll'));
    activity()
    ->performedOn($payroll)
    ->causedBy(auth()->user())
    ->withProperties(['action' => 'payroll_generatesummary'])
    ->log('Succesfully Download summaryreport as pdf');
    return $pdf->download('payslip.pdf');
    return $pdf->download('summaryreports.pdf');
}
public function GetRatePerDay($employeeID){

    $employee = Employees::where('EmployeeID', $employeeID)->first();



    $rpd = $employee->rate_per_day;

  
    return $rpd;

}

}