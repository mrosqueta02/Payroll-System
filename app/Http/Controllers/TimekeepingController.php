<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employees;
use App\Models\Timekeeping;
use App\Models\PayrollE;
use Carbon\Carbon;
use Spatie\ActivityLog\Models\Activity;

class TimekeepingController extends Controller
{
    public function index()
    {
        $employees = Employees::all();
        $timekeeping = Timekeeping::paginate(5);
        return view('timekeeping', compact('employees', 'timekeeping'));
    }

    public function addAttendance()
    {
        $timekeeping = Timekeeping::all();

        return view('attendancetable', compact('timekeeping'));
    }


    public function scan(Request $request)
    {
        $rfidValue = $request->input('rfid');
        $currentTime = Carbon::now('Asia/Manila');

        $currentTime->setTimezone('Asia/Manila');

        $employee = Employees::where('EmployeeID', $rfidValue)->first();

        if (!$employee) {
            return redirect()->back()->with('failed', 'Employee Not Found.');
        }

        $timekeeping = Timekeeping::where('EmployeeID', $rfidValue)->latest()->first();
        $dateIn = date('Y-m-d', strtotime($timekeeping->TimeIn));
        $dateNow = date('Y-m-d', strtotime($currentTime));


        if ($timekeeping && !$timekeeping->TimeOut) {
            $delayMinutes = 1;

            $timediff = $currentTime->diffInMinutes($timekeeping->TimeIn);
            

            if ($timediff < $delayMinutes) {
                return redirect()->back()->with('failed', "You cannot Time Out wait for {$delayMinutes} minute.");

            } else if ($dateIn == $dateNow) {
                $expectedTimeOut = Carbon::parse('18:00:00', 'Asia/Manila');
                if ($currentTime > $expectedTimeOut) {

                    $overtimeminute = $currentTime->diffInMinutes($expectedTimeOut);
                    $overtimehours = floor($overtimeminute / 60);
                    $overtimeminute %= 60;
                    $overdec = $overtimehours + $overtimeminute / 60;

                    $timekeeping->update([
                        'TimeOut' => $currentTime,
                        'Overtime' => $overdec,
                    ]);
                    activity()
                        ->performedOn($timekeeping)
                        ->causedBy(auth()->user())
                        ->withProperties(['action' => 'timekeeping_log'])
                        ->log('Time Out recorded successfully.');


                    return redirect()->back()->with('success', "Time Out recorded successfully.");
                } else {

                    $timekeeping->update([
                        'TimeOut' => $currentTime,
                    ]);
                    activity()
                        ->performedOn($timekeeping)
                        ->causedBy(auth()->user())
                        ->withProperties(['action' => 'timekeeping_log'])
                        ->log('Time Out recorded successfully.');
                    return redirect()->back()->with('success', "Time Out recorded successfully.");
                }
            } else {

                $autoTimeOut = date('Y-m-d', strtotime($timekeeping->TimeIn . '+1 day'));

                $defaltTimeOut = $autoTimeOut . " 01:00:00";

                $timekeeping->update([
                    'TimeOut' => $defaltTimeOut,
                ]);

                activity()
                    ->performedOn($timekeeping)
                    ->causedBy(auth()->user())
                    ->withProperties(['action' => 'timekeeping_log'])
                    ->log('Time Out recorded successfully.');

                return $this->createNew($employee, $currentTime);
            }

        }
        $lateArrival = $this->checkLateArrival($employee->EmployeeName, $currentTime);


        if (!$timekeeping || $lateArrival > 0) {

            $newTimekeeping = Timekeeping::create([
                'EmployeeID' => $employee->EmployeeID,
                'EmployeeName' => $employee->FirstName . ' ' . $employee->LastName,
                'LateArrival' => $currentTime,
                'TimeIn' => $currentTime,
            ]);
            activity()
                ->performedOn($newTimekeeping)
                ->causedBy(auth()->user())
                ->withProperties(['action' => 'timekeeping_log'])
                ->log('Late Arrival recorded successfully.');
            return redirect()->back()->with('success', 'Late Arrival recorded successfully. ('.$dateIn.') ('.$dateNow.')');
        } else {
            $newTimekeeping = Timekeeping::create([
                'EmployeeID' => $employee->EmployeeID,
                'EmployeeName' => $employee->FirstName . ' ' . $employee->LastName,
                'TimeIn' => $currentTime,
            ]);
            activity()
                ->performedOn($newTimekeeping)
                ->causedBy(auth()->user())
                ->withProperties(['action' => 'timekeeping_log'])
                ->log('Time In recorded successfully');
            return redirect()->back()->with('success', 'Time In recorded successfully.');
        }

    }

    public function createNew($employeeDetails, $currentTimeIn)
    {
        $newTimekeeping = Timekeeping::create([
            'EmployeeID' => $employeeDetails->EmployeeID,
            'EmployeeName' => $employeeDetails->FirstName . ' ' . $employeeDetails->LastName,
            'TimeIn' => $currentTimeIn,
        ]);
        activity()
            ->performedOn($newTimekeeping)
            ->causedBy(auth()->user())
            ->withProperties(['action' => 'timekeeping_log'])
            ->log('Time In recorded successfully');
        return redirect()->back()->with('success', 'Time In recorded successfully.');
    }

    public function checkLateArrival($employeeName, $timeIn)
    {

        $expectedTimeIn = Carbon::parse('15:30:00', 'Asia/Manila');


        $minutesLate = max(0, $expectedTimeIn->diffInMinutes($timeIn, false));


        $lateArrivalThreshold = 15;

        return $minutesLate > $lateArrivalThreshold;
    }

    public function GetPDashboard()
    {

        $currentDayOfWeek = Carbon::now()->dayOfWeek + 1;

        // Fetch data from the database for the current day
        $employees = Timekeeping::whereRaw("DAYOFWEEK(created_at) = ?", [$currentDayOfWeek])->get();

        // Initialize counts
        $onTimeCount = 0;
        $lateCount = 0;

        // Calculate counts based on 'Late Arrival'
        foreach ($employees as $employee) {
            // Check if 'Late Arrival' has a value, otherwise consider it 'ontime'
            if ($employee->LateArrival) {
                $lateCount++;
            } else {
                $onTimeCount++;
            }
        }

        // Total number of employees
        $totalEmployeesCount = count($employees);

        return view('pdashboard', compact('onTimeCount', 'lateCount', 'totalEmployeesCount'));
    }
    public function GetDashboard()
    {
        // Get the current day of the week (1 = Monday, 2 = Tuesday, ..., 7 = Sunday)
        $currentDayOfWeek = Carbon::now()->dayOfWeek + 1;

        // Fetch data from the database for the current day
        $employees = Timekeeping::whereRaw("DAYOFWEEK(created_at) = ?", [$currentDayOfWeek])->get();

        // Initialize counts
        $onTimeCount = 0;
        $lateCount = 0;

        // Calculate counts based on 'Late Arrival'
        foreach ($employees as $employee) {

            if ($employee->LateArrival) {
                $lateCount++;
            } else {
                $onTimeCount++;
            }
        }

        // Total number of employees
        $totalEmployeesCount = count($employees);

        return view('dashboard', compact('onTimeCount', 'lateCount', 'totalEmployeesCount'));
    }
    public function GetODashboard()
    {

        $currentDayOfWeek = Carbon::now()->dayOfWeek + 1;

        // Fetch data from the database for the current day
        $employees = Timekeeping::whereRaw("DAYOFWEEK(created_at) = ?", [$currentDayOfWeek])->get();


        $onTimeCount = 0;
        $lateCount = 0;

        // Calculate counts based on 'Late Arrival'
        foreach ($employees as $employee) {
            // Check if 'Late Arrival' has a value, otherwise consider it 'ontime'
            if ($employee->LateArrival) {
                $lateCount++;
            } else {
                $onTimeCount++;
            }
        }

        // Total number of employees
        $totalEmployeesCount = count($employees);

        return view('owdashboard', compact('onTimeCount', 'lateCount', 'totalEmployeesCount'));
    }
}
