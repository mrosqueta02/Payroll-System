<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('WJMLOGO.png') }}">
    <title>Payroll System</title>
    <style>
        .navbar {
        background-color: #FFD580; /* Change this color to your desired background color */
        border: none; /* Remove the default border */
    

    }

    .navbar-brand {
        color: white; /* Change the brand text color */
    }

    .navbar-nav li a {
        color: white; /* Change the navigation links text color */
        font-weight: bolder;
    }

    .navbar-nav li a:hover {
        background-color: #d36905; /* Change the color on hover */
    }

    .navbar-right {
        margin-right: 20px; /* Adjust the right margin for the right-aligned links */
    }

    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
    }

    /* Firefox */
    input[type=number] {
    -moz-appearance: textfield;
    }

    </style>
</head>
<body>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">Payroll System</a>
            </div>
            <ul class="nav navbar-nav">
            <li><a href="/pmdashboard">Home</a></li>
            <li><a href="/Timekeeping">Timekeeping</a></li>
            <li><a href="/employees">Employees Management</a></li>
            <li><a href="/payroll">Payroll</a></li>
            <li><a href="/reports">Reports</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="/login"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="container" style="margin-top:30px"> 
        <div class="row">
            <div class="col-md-12">
                <h2>Edit Employee</h2>
                @if(Session::has('success'))
                <div class="alert alert-success" role="alert">
                    {{Session::get('success')}}
                </div>
                @endif
                <form method="post" action="{{url('update-employee')}}">
                    @csrf
                    <input type="hidden" name="id" value="{{$data->id}}">
                    <div class="form-group">
                        <label class="form-label">First Name</label>
                        <input type="text" class="form-control" name="first_name" placeholder="Enter your First Name" value = {{$data->FirstName}}>
        
                        @error('first_name')
                        <div class="alert alert-warning" role="alert">
                            {{$message}}
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Middle Name</label>
                        <input type="text" class="form-control" name="middle_name" placeholder="Enter your Middle Name" value = {{$data->MiddleName}}>
                        @error('middle_name')
                        <div class="alert alert-warning" role="alert">
                            {{$message}}
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Last Name</label>
                        <input type="text" class="form-control" name="last_name" placeholder="Enter your Last Name" value = {{$data->LastName}}>
                        @error('last_name')
                        <div class="alert alert-warning" role="alert">
                            {{$message}}
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="Email" placeholder="Enter your Email" value = {{$data->Email}}>
                        @error('Email')
                        <div class="alert alert-warning" role="alert">
                            {{$message}}
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Job Position</label>
                        <input type="text" class="form-control" name="position" placeholder="Enter your Job Position" value = {{$data->Position}}>
                        @error('position')
                        <div class="alert alert-warning" role="alert">
                            {{$message}}
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Rate per day*</label>
                        <input type="number" class="form-control" name="Rate" placeholder="Enter rate per day" value = {{$data->RatePerDay}}>
                        @error('rate_per_day')
                        <div class="alert alert-warning" role="alert">
                            {{$message}}
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Contact No.</label>
                        <input type="text" class="form-control" name="Contact" placeholder="Enter your Contact No" value = {{$data->Contact}}>
                        @error('contact')
                        <div class="alert alert-warning" role="alert">
                            {{$message}}
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Department</label>
                        <input type="text" class="form-control" name="Department" placeholder="Enter your Contact No" value = {{$data->Department}}>
                        @error('Department')
                        <div class="alert alert-warning" role="alert">
                            {{$message}}
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">RFID</label>
                        <input type="text" class="form-control" name="EmployeeID" placeholder="Enter your RFID" value = {{$data->EmployeeID}}>
                        @error('EmployeeID')
                        <div class="alert alert-warning" role="alert">
                            {{$message}}
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>

                    <a href="{{url('employees')}}" class="btn btn-danger">Cancel</a>


                </form>
    
            </div>
    </div>
</body>
</html>
