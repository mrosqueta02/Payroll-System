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
           .modal-body {
        max-height: 400px;
        overflow-y: auto;
    }
        /* Add some basic styling to the table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Add some hover effect to the table rows */
        tbody tr:hover {
            background-color: #f5f5f5;
        }
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
    
    <div class="container" style="margin-top:20px">
        <div class="row">
            <div style="float: right">
                <!-- Add data-toggle and data-target attributes to open the modal -->
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">
                    Add
                </button>
            </div>
            <h2>Payroll</h2>
            
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>EmployeeID</th>
                        <th>EmployeeName</th>
                        <th>Salary</th>
                        <th>Rate Per Hour</th>
                        <th>Total Work Hours</th>
                        <th>Gross Income</th>
                        <th>Total Deductions</th>
                        <th>NetIncome</th>
                        
                    </tr>
                </thead>
                <tbody>
                  @foreach ($payroll as $pay)
                  <tr>
                    <td>{{ $pay->created_at->format('Y-m-d') }}</td>
                      <td>{{ $pay->EmployeeID }}</td>
                      <td>{{ $pay->EmployeeName }}</td>
                      <td>{{ "₱" .$pay->Salary  }}</td>
                      <td>{{ $pay->RPH }}</td>
                      <td>{{ $pay->TotalHrs }}</td>
                      <td>{{ $pay->GrossIncome }}</td>
                      <td>{{ $pay->TotalDeduction }}</td>
                      <td>{{ $pay->NetIncome }}</td>
                    <td>
                        <a href="{{ route('generatePayslip', ['id' => $pay->id]) }}" class="btn btn-primary btn-sm">Generate Payslip</a>

                    </td>
                </tr>
                  </tr>
              @endforeach
                  
                </tbody>
                
            </table>
            <div class="d-flex justify-content-center">
                {{ $payroll->links() }}
            </div>
        </div>
    </div>
</div>
    
        <!-- Modal -->
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content -->
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add Employee Payroll</h4>
                    </div>
    
                    <!-- Modal Body -->
                    <div class="modal-body">
                        
                        <form action="{{ route('save-payroll') }}" method="POST">
                            @csrf
                        
                                <div class ="form-group">
                                    <label for="start_date">Start Date:</label>
                                    <input type="date" name="start_date" style="padding-right: 20px;" required>

                                    <label for="end_date">End Date:</label>
                                    <input type="date" name="end_date" required>
                                </div>
                            

                            <div class="form-group">
                                <label class="form-label" for="dropdown">Employee Name</label>
                                <select id="dropdown" name="employeeName">
                                    @foreach ($timekeeping->pluck('EmployeeName')->unique() as $employeeName)
                                        <option>
                                            {{ $employeeName }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('employeeName')
                                    <div class="alert alert-warning" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">EmployeeID</label>
                                <input type="text" class="form-control" name="employeeID" id="employeeID" placeholder="Enter Employee ID" value="{{ old('employeeID') }}" readonly>

                                @error('employeeID')
                                    <div class="alert alert-warning" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Rate Per Day</label>
                                <input type="text" class="form-control" name="rph" value="{{ old('rph') }}" readonly>
                                @error('rpd')
                                    <div class="alert alert-warning" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Salary</label>
                                <input type="text" class="form-control" name="salary"  value="{{ old('salary') }}" readonly>
                                @error('salary')
                                    <div class="alert alert-warning" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Total Overtime Hours</label>
                                <input type="text" class="form-control" name="toh"  value="{{ old('toh') }}" readonly>
                                @error('toh')
                                    <div class="alert alert-warning" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Total Work Hours</label>
                                <input type="text" class="form-control" name="twh" placeholder="Enter the Total Work Hours" value="{{ old('twh') }}" readonly>
                                @error('twh')
                                    <div class="alert alert-warning" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <label>Deductions</label>
                            <br>                            
                            <div class="form-group">
                                <label class="form-label">SSS</label>
                                <select class="form-control" name="sss">
                                    <option value="" {{ old('sss') == '0' ? 'selected' : '' }}>0</option>
                                    <option value="607" {{ old('sss') == '607' ? 'selected' : '' }}>607</option>
                                </select>
                                @error('sss')
                                    <div class="alert alert-warning" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">PHILHEALTH</label>
                                <select class="form-control" name="philhealth">
                                    <option value="" {{ old('philhealth') == '0' ? 'selected' : '' }}>0</option>
                                    <option value="200" {{ old('philhealth') == '200' ? 'selected' : '' }}>200</option>
                                </select>
                                @error('philhealth')
                                    <div class="alert alert-warning" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">PAG-IBIG</label>
                                <select class="form-control" name="pagibig">
                                    <option value="" {{ old('pagibig') == '0' ? 'selected' : '' }}>0</option>
                                    <option value="200" {{ old('pagibig') == '200' ? 'selected' : '' }}>200</option>
                                </select>
                                @error('pagibig')
                                    <div class="alert alert-warning" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                           <div class="form-group">
                                <label class="form-label">Total Late of Hours</label>
                                <input type="text" class="form-control" name="late" value="{{ old('late') }}" readonly>
                                @error('late')
                                    <div class="alert alert-warning" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Benefits (Allowance, Incentives, Bonus, etc. . .)</label>
                                <input type="text" class="form-control" name="benefits" placeholder="Enter Benefits" value="{{ old('benefits') }}">
                                @error('benefits')
                                    <div class="alert alert-warning" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                    
    
                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Save</button>
                                
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            //$('#dropdown, input[name="start_date"], input[name="end_date"]').change(function () {
            //    updateTotalHours();
            
            //});

            $('#dropdown, input[name="EmployeeName"]').change(function () {
                updateTotalHours();
            
            });
    
            function updateTotalHours() {
                var employeeName = $('#dropdown').val();
                var startDate = $('input[name="start_date"]').val();
                var endDate = $('input[name="end_date"]').val();
                
    
                // Make an AJAX request to get the total hours
                $.ajax({
                    url: '{{ route('getTotalHours')}}',
                    type: 'GET',
                    data: {
                        employeeName: employeeName,
                        start_date: startDate,
                        end_date: endDate
                    },
                    success: function (response) {
                        // Update the total work hours field in the form
                        $('input[name="twh"]').val(response.totalHours);    
                        $('input[name="employeeID"]').val(response.employeeID);
                        $('input[name="late"]').val(response.totalLate);
                        $('input[name="toh"]').val(response.totalOvertime);
                        $('input[name="rph"]').val(response.rateperday);
                        $('input[name="salary"]').val(response.salary);
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            }
        });
    </script>
        
</body>
</html>
