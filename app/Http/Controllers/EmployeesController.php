<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employees;
use App\Models\Payroll;
use App\Models\Timekeeping;
use Spatie\ActivityLog\Models\Activity;
use Illuminate\Support\Facades\Auth;

class EmployeesController extends Controller
{
    public function index()
    {
        $employees = Employees::paginate(7); // Retrieve all employees from the database
        return view('employees-list', compact('employees'));
    }
    public function addEmployee(){
        return view('addemployees'); 
    }
    public function saveEmployee(Request $request){
        $request->validate([
            'first_name' => 'required|regex:/^[a-zA-Z\s]+$/',
            'middle_name' => 'nullable|regex:/^[a-zA-Z\s]*$/',
            'last_name' => 'required|regex:/^[a-zA-Z\s]+$/',
            'Email' => 'required|email|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/|unique:employees,Email',
            'position' =>'required',
            'EmployeeID' =>'required|digits:10|unique:employees,EmployeeID|unique:user_management,EmployeeID',
            'Contact'=>'required|min:11|unique:employees,Contact|numeric',
            'Department'=>'required',
        ]);
        

        $first_name = ucwords(strtolower($request->first_name));
        $middle_name = ucwords(strtolower($request->middle_name));
        $last_name = ucwords(strtolower($request->last_name));
        $email = $request->Email;
        $Position = $request->position;
        $ratePerDay = $request->Rate;
        $empid = $request->EmployeeID;
        $contact = $request->Contact;
        $department = $request->Department;
    
        $emp = new Employees();
        $emp->FirstName = $first_name;
        $emp->MiddleName = $middle_name;
        $emp->LastName = $last_name;
        $emp->Email = $email;
        $emp->Position = $Position;
        $emp->rate_per_day = $ratePerDay;
        $emp->EmployeeID =$empid;
        $emp->Contact = $contact;
        $emp->Department = $department;
        $emp->save();
    

        activity()
            ->performedOn($emp)
            ->causedBy(auth()->user())
            ->withProperties(['action' => 'employee_saved'])
            ->log('Employee Added Successfully');

        return redirect()->back()->with('success', 'Employee Added Successfully');
    
    
    

    }
    
    public function editEmployee($id){
        $data = Employees::where('id','=', $id)->first();
        return view ('editemployees', compact('data'));
    }

    public function updateEmployees(Request $request){
        $request->validate([
            'first_name' => 'required|regex:/^[a-zA-Z\s]+$/',
            'middle_name' => 'required|regex:/^[a-zA-Z\s]+$/',
            'last_name' => 'regex:/^[a-zA-Z\s]+$/',
            'Email' => 'required|email',
            'position' =>'required',
            'EmployeeID' =>'required',
            'Contact'=>'required|min:11',
            'Department'=>'required',
            
        ]);
        $id = $request->id;
        $first_name = ucwords(strtolower($request->first_name));
        $middle_name = ucwords(strtolower($request->middle_name));
        $last_name = ucwords(strtolower($request->last_name));
        $email = $request->Email;
        $Position = $request->position;
        $ratePerDay = $request->Rate;
        $empid = $request->EmployeeID;
        $contact = $request->Contact;
        $department =$request->Department;


        Employees::where('id', '=', $id)->update([
            'FirstName' =>$first_name,
            'MiddleName' =>$middle_name,
            'LastName' =>$last_name,
            'email' =>$email,
            'position' =>$Position,
            'ratePerday' => $ratePerDay,
            'Contact' =>$contact,
            'EmployeeID' =>$empid,
            'Department' =>$department,
            
        ]);
        
        $employee = Employees::find($id);
        activity()
        ->performedOn($employee)

        ->causedBy(Auth::user())
        ->withProperties(['action' => 'employee_update_after'])
        ->log('Employee Updated Successfully');


        return redirect()->back()->with('success', 'Updated Employee Successfully');
    
    }
    public function deleteEmployee($id){
        Employees::where('id', '=',$id)->delete();
        return redirect()->back()->with('success', 'Deleted Employee Successfully');
    }

    
}

