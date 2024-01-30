<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="icon" type="image/png" href="{{ asset('WJMLOGO.png') }}">
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
    <title>Payroll System</title>
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
                <h2>Add Employee</h2>
                @if(Session::has('success'))
                <div class="alert alert-success" role="alert">
                    {{Session::get('success')}}
                </div>
                @endif
                <form method="post" action="{{url('save-employee')}}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">First Name*</label>
                        <input type="text" class="form-control" name="first_name" placeholder="Enter your First Name" value = {{old('first_name')}}>
        
                        @error('first_name')
                        <div class="alert alert-warning" role="alert">
                            {{$message}}
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Middle Name</label>
                        <input type="text" class="form-control" name="middle_name" placeholder="Enter your Middle Name">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Last Name*</label>
                        <input type="text" class="form-control" name="last_name" placeholder="Enter your Last Name">
                        @error('last_name')
                        <div class="alert alert-warning" role="alert">
                            {{$message}}
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email*</label>
                        <input type="email" class="form-control" name="Email" placeholder="Enter your Email">
                        @error('Email')
                        <div class="alert alert-warning" role="alert">
                            {{$message}}
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="departmentDropdown">Department*</label>
                        <select id="departmentDropdown" name="Department" onchange="handleDepartmentChange()">
                                <option value= "Accounting">Accounting Department</option>
                                <option value= "Purchasing">Purchasing Department</option>
                                <option value="Graphic">Graphic Artist Department</option>
                                <option value="Production">Production Department</option>
                        
                        </select>
                        @error('Department')
                        <div class="alert alert-warning" role="alert">
                            {{$message}}
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="positionDropdown">Job Position*</label>
                        <select id="positionDropdown" name="position">
                                <option value ="Accounting_officer">Accounting Officer</option>
                                <option value ="Accounting_manager">Accounting Manager</option>
                                <option value ="Purchasing_officer">Purchasing Officer</option>
                                <option value ="Graphic_artist">Graphic Artist</option>
                                <option value = "installers">Installers</option>
                                <option value = "painter">Painter</option>
                                <option value = "printing">Printing</option>
                                <option value = "lamination">Lamination</option>
                                <option value = "lighting">Lighting</option>
                        
                        </select>
                        @error('Department')
                        <div class="alert alert-warning" role="alert">
                            {{$message}}
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Rate per day*</label>
                        <input type="number" class="form-control" name="Rate" placeholder="Enter rate per day">
                        @error('RatePerDay')
                        <div class="alert alert-warning" role="alert">
                            {{$message}}
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Contact No.*</label>
                        <input type="number" class="form-control" name="Contact" placeholder="Enter Contact No.">
                        @error('Contact')
                        <div class="alert alert-warning" role="alert">
                            {{$message}}
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">RFID*</label>
                        <input type="text" class="form-control" name="EmployeeID" placeholder="Scan your RFID">
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
    <script>
        function handleDepartmentChange() {
            var departmentDropdown = document.getElementById("departmentDropdown");
            var positionDropdown = document.getElementById("positionDropdown");
    
            // Reset positionDropdown to default state
            positionDropdown.selectedIndex = 0;
    
            // Get the selected department value
            var selectedDepartment = departmentDropdown.value;
    
            // Show/hide options based on the selected department
            switch (selectedDepartment) {
                case "Accounting":
                    // Show only Accounting positions
                    showOptions(positionDropdown, ["Accounting_officer", "Accounting_manager"]);
                    break;
                case "Purchasing":
                    // Show only Purchasing positions
                    showOptions(positionDropdown, ["Purchasing_officer"]);
                    break;
                case "Graphic":
                    // Show only Graphic Artist positions
                    showOptions(positionDropdown, ["Graphic_artist"]);
                    break;
                case "Production":
                    // Show only Production positions
                    showOptions(positionDropdown, ["installers", "painter", "printer", "lamination", "lighting"]);
                    break;
                default:
                    // Show all options
                    showOptions(positionDropdown, ["accounting_officer", "accounting_manager", "graphic_artist", "installers", "painter", "printing", "lamination", "lighting"]);
            }
        }
    
        function showOptions(select, optionsToShow) {
            // Hide all options
            for (var i = 0; i < select.options.length; i++) {
                select.options[i].style.display = "none";
            }
    
           
            for (var j = 0; j < optionsToShow.length; j++) {
                var option = select.querySelector('option[value="' + optionsToShow[j] + '"]');
                if (option) {
                    option.style.display = "block";
                }
            }
        }
    </script>
</body>
</html>
