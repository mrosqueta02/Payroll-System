<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserManagement;
use Illuminate\Support\Facades\Auth;
use Hash;
use Spatie\ActivityLog\Models\Activity;
use Session;

class UserManagementController extends Controller
{
    public function index(Request $request){
        $user = UserManagement::all();
        return view ('usermanagement', compact('user')); 
    }

    public function adduser(Request $request){
            $request->validate([
            'empid' => 'required|min:10|unique:user_management,EmployeeID|unique:employees,EmployeeID',
            'fname' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'mname' => 'regex:/^[a-zA-Z0-9\s]+$/',
            'lname' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'userrole' => 'required',
            'email' => 'required|email|unique:user_management,Email',
            'password' => 'required|min:5|max:10',
           
        ]);
        $id = $request->id;
        $empid = $request->empid;
        $fname = ucwords(strtolower($request->fname));
        $mname = ucwords(strtolower($request->mname));
        $lname = ucwords(strtolower($request->lname));
        $userrole = $request->userrole;
        $email = $request->email;
        $password = bcrypt($request->password);

        $user = new UserManagement();
        $user->EmployeeID = $empid;
        $user->FirstName = $fname;
        $user->MiddleName = $mname;
        $user->LastName = $lname;
        $user->UserRole = $userrole;
        $user->Email = $email;
        $user->Password = $password;
        $user->save();
        
        activity()
        ->performedOn($user)
        ->causedBy(auth()->user())
        ->withProperties(['action' => 'user_saved'])
        ->log('New User Added Successfully');

        return redirect()->back()->with('success', 'New User Added Successfully');
       
        
    }
    public function edituser($id){
        $data = UserManagement::where('id', '=', $id)-first();

        return view ('', compact('data'));
    }
    public function UpdateUser(Request $request){
        $request->validate([
            'empid' => 'required|min:10|unique:user_management,EmployeeID',
            'fname' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'mname' => 'regex:/^[a-zA-Z0-9\s]+$/',
            'lname' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'userrole' => 'required',
            'email' => 'required|email|ends_with:gmail.com,yahoo.com.|unique:user_management,Email',
            'password' => 'required|min:5|max:10',
           
        ]);
        $id = $request->id;
        $empid = $request->empid;
        $fname = ucwords(strtolower($request->fname));
        $mname = ucwords(strtolower($request->mname));
        $lname = ucwords(strtolower($request->lname));
        $userrole = $request->userrole;
        $email = $request->email;
        $password = bcrypt($request->password);


        UserManagement::where('id', $id)->update([
            'EmployeeID' => $empid,
            'FirstName' => $fname,
            'MiddleName' => $mname,
            'LastName' => $lname,
            'UserRole' => $userrole,
            'Email' => $email,
            'Password' => $password,
        ]);
    

        activity()
            ->performedOn(UserManagement::find($id))
            ->causedBy(auth()->user())
            ->withProperties(['action' => 'user_updated'])
            ->log('User Updated Successfully');
    
        return redirect()->back()->with('success', 'User Updated Successfully');
    }


    public function LoginM(Request $request)
{
    $request->validate([
        'email' => 'required',
        'pass' => 'required',
    ]);

    // Find the user by email
    $user = UserManagement::where('email', $request->email)->first();

    // Check if the user exists and the provided password is correct
    if ($user && Hash::check($request->pass, $user->Password)) {
        // Log in the user
        Auth::login($user);

        // Fetch the logged-in user using auth()
        $loggedInUser = auth()->user();

        session(['user_name' => $loggedInUser->FirstName . ' ' . $loggedInUser->LastName]);
        // Log the welcome message based on the user's role
        if ($loggedInUser) {
            $welcomeMessage = "Welcome, $loggedInUser->UserRole!";
            $fullName =  $loggedInUser->FirstName . ' ' . $loggedInUser->LastName;
            activity()
                ->performedOn($loggedInUser)
                ->causedBy($loggedInUser)
                ->withProperties(['action' => 'user_log'])
                ->log($welcomeMessage);

            // Redirect based on the user's role
            switch ($loggedInUser->UserRole) {
                case 'Admin':
                    return redirect('/dashboard')->with('success', $welcomeMessage);
                case 'Payroll Master':
                    return redirect('/pmdashboard')->with('success', $welcomeMessage);
                case 'Owner':
                    return redirect('/owdashboard')->with('success', $welcomeMessage);
                default:
                    return redirect()->back()->with('failed', 'Invalid User Role');
            }
        } else {
            return redirect()->back()->with('failed', 'User not found.');
        }
    } else {
        // Authentication failed
        return redirect()->back()->with('failed', 'Invalid Credentials');
    }
}
    private function getLoggedInUser()
    {
        return Auth::user();
    }
}