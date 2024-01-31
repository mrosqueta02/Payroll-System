<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\Payroll;
use App\Http\Controllers\TimekeepingController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ActivityLogsController;


//DASHBOARD FOR ALL THE USERS
Route::get('/dashboard', function () {
    return view('dashboard');
});
Route::get('/pmdashboard', function () {
    return view('pdashboard');
});

Route::get('/owdashboard', function () {
    return view('ownersdashboard');
});

Route::get('/aboutus', function () {
    return view('home');
});

//VIEW FOR PAYROLL MASTER
Route::get('/payroll', function () {
    return view('Payroll');
});
Route::get('/Timekeeping', function () {
    return view('TimeKeeping');
});
Route::get('/UserManagement', function(){
    return view ('UserManagement');
});


Route::get('/login', function(){
    return view ('login');
});

Route::get('/reports', function(){
    return view ('reports');
});
Route::get('/ownerreports', function(){
    return view ('ownerreports');
});

Route::get('/activitylogs',[ActivityLogsController::class, 'index']);

Route::get('employees', [EmployeesController::class, 'index']);

Route::get('attendancetable',[TimekeepingController::class, 'addAttendance']);
Route::get('addemployees', [EmployeesController::class, 'addEmployee']);
Route::get('payroll', [Payroll::class, 'index1'])->name('employees.index');
Route::get('reports', [Payroll::class, 'index2']);
Route::get('ownerreports',[Payroll::class, 'index3']);
Route::get('Timekeeping', [TimekeepingController::class, 'index']);
Route::get('UserManagement',[UserManagementController::class, 'index']);

Route::get('edit-employees/{id}', [EmployeesController::class, 'editEmployee'])->name('employees.edit');
Route::get('delete-employees/{id}', [EmployeesController::class, 'deleteEmployee'])->name('employees.edit');

//Employees Management
Route::post('save-employee', [EmployeesController::class, 'saveEmployee']);
Route::post('update-employee', [EmployeesController::class, 'updateEmployees']);
//UserManagment
Route::post('saveUser', [UserManagementController::class, 'adduser']);
Route::post('login', [UserManagementController::class, 'LoginM']);
//Payroll
Route::post('save-payroll', [Payroll::class, 'savePayroll'])->name('save-payroll');
Route::get('/getTotalHours', [Payroll::class, 'getTotalHours'])->name('getTotalHours');
Route::get('/generate-payslip/{id}', [Payroll::class, 'generatePayslip'])->name('generatePayslip');
Route::get('/generate-summaryrep/{id}', [Payroll::class, 'generatesummaryreport'])->name('generatesummaryreport');



//Timekeeping
Route::post('/',[TimekeepingController::class, 'scan']);
Route::get('/pmdashboard', [TimekeepingController::class, 'GetPDashboard']);
Route::get('/dashboard', [TimekeepingController::class, 'GetDashboard']);
Route::get('/owdashboard', [TimekeepingController::class, 'GetODashboard']);
//Reports
Route::get('/filter', [Payroll::class, 'filter']);
Route::get('/filter2', [Payroll::class, 'filter2']);


Route::post('/submit-form', [Payroll::class, 'submitForm'])->name('submit-form');

Route::post('/register', [UserController::class, 'register']);
Route::post('/logout', [UserManagementController::class, 'logout']);

