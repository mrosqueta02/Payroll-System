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

        th,
        td {
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
            background-color: #FFD580;
            /* Change this color to your desired background color */
            border: none;
            /* Remove the default border */


        }

        .navbar-brand {
            color: white;
            /* Change the brand text color */
        }

        .navbar-nav li a {
            color: white;
            /* Change the navigation links text color */
            font-weight: bolder;
        }

        .navbar-nav li a:hover {
            background-color: #d36905;
            /* Change the color on hover */
        }

        .navbar-right {
            margin-right: 20px;
            /* Adjust the right margin for the right-aligned links */
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
    @if(Session::has('success'))
    <div class="alert alert-success" role="alert">
        {{Session::get('success')}}
    </div>
    @endif
    @if(Session::has('failed'))
    <div class="alert alert-danger" role="alert">
        {{Session::get('failed')}}
    </div>
    @endif
    <div class="container" style="margin-top:20px">
        <div class="row">
            <div style="float: right">

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
                        <th>Rate Per Day</th>
                        <th>Total Work Hours</th>
                        <th>Gross Income</th>
                        <th>Total Deductions</th>
                        <th>NetIncome</th>
                        <th>Action</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($payroll as $pay)
                    <tr>
                        <td>{{ $pay->created_at->format('Y-m-d') }}</td>
                        <td>{{ $pay->EmployeeID }}</td>
                        <td>{{ $pay->EmployeeName }}</td>
                        <td>{{ $pay->RPH }}</td>
                        <td>{{ $pay->TotalHrs }}</td>
                        <td>{{ $pay->GrossIncome }}</td>
                        <td>{{ $pay->TotalDeduction }}</td>
                        <td>{{ $pay->NetIncome }}</td>
                        <td>
                            <a href="{{ route('generatePayslip', ['id' => $pay->id]) }}"
                                class="btn btn-primary btn-sm">Generate Payslip</a>

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

                        <div class="form-group">
                            <script>

                            </script>

                            <label for="start_date">Start Date:</label>
                            <input type="date" name="start_date" id="start_date" style="padding-right: 20px;" required>

                            <label for="end_date">End Date:</label>
                            <input type="date" name="end_date" id="end_date" required>

                            <script>

                                var startDateInput = document.getElementById('start_date');
                                var endDateInput = document.getElementById('end_date');
                                startDateInput.value = new Date().toISOString().split('T')[0].slice(0, 10);
                                endDateInput.value = new Date().toISOString().split('T')[0].slice(0, 10);

                                function updateEndDate() {
                                    var endWeek = new Date(document.getElementById('start_date').value).getTime() + 432000000;
                                    var formattedEndDate = new Date(endWeek).toISOString().split('T')[0].slice(0, 10);

                                    endDateInput.min = startDateInput.value;
                                    endDateInput.value = formattedEndDate;
                                }

                                // Attach the event listener to the start_date input
                                document.getElementById('start_date').addEventListener('input', updateEndDate);
                            </script>
                        </div>


                        <div class="form-group">
                            <label class="form-label" for="dropdown">Employee Name</label>
                            <select id="dropdown" name="employeeName">
                                <option>------------Select Employee------------</option>
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
                            <input type="text" class="form-control" name="employeeID" id="employeeID"
                                value="{{ old('employeeID') }}" readonly>

                            @error('employeeID')
                            <div class="alert alert-warning" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Rate Per Day</label>
                            <input type="text" class="form-control" name="rph" value="{{ old('rph') }}" readonly>
                            @error('rph')
                            <div class="alert alert-warning" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <label class="form-label">Salary</label>
                        <input type="text" class="form-control" name="salary" value="{{ old('salary') }}" readonly>
                        @error('salary')
                        <div class="alert alert-warning" role="alert">
                            {{ $message }}
                        </div>
                        @enderror

                        <div class="form-group">

                            <input type="hidden" class="form-control" name="totalgrossincome" readonly>
                            @error('rph')
                            <div class="alert alert-warning" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">

                            <input type="hidden" class="form-control" name="otrate" readonly>
                            @error('otrate')
                            <div class="alert alert-warning" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">

                            <input type="hidden" class="form-control" name="otpay" readonly>
                            @error('otpay')
                            <div class="alert alert-warning" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">

                            <input type="hidden" class="form-control" name="totaldays" readonly>
                            @error('totaldays')
                            <div class="alert alert-warning" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">

                            <input type="hidden" class="form-control" name="basicsalary" readonly>
                            @error('basicsalary')
                            <div class="alert alert-warning" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Total Overtime Hours</label>
                            <input type="text" class="form-control" name="toh" value="{{ old('toh') }}" readonly>
                            @error('toh')
                            <div class="alert alert-warning" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Total Work Hours</label>
                            <input type="text" class="form-control" name="twh" value="{{ old('twh') }}" readonly>
                            @error('twh')
                            <div class="alert alert-warning" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <label>Deductions</label>
                        <br>

                        <div class="form-group">
                            <label class="form-label">Tax</label>
                            <input type="text" class="form-control" name="tax" value="{{ old('tax') }}" readonly>
                            @error('tax')
                            <div class="alert alert-warning" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">SSS</label>
                            <input type="text" class="form-control" name="sss" value="{{ old('sss') }}" readonly>
                            @error('sss')
                            <div class="alert alert-warning" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">PHILHEALTH</label>
                            <input type="text" class="form-control" name="philhealth" value="{{ old('philhealth') }}"
                                readonly>
                            @error('philhealth')
                            <div class="alert alert-warning" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">PAG-IBIG</label>
                            <input type="text" class="form-control" name="pagibig" value="{{ old('pagibig') }}"
                                readonly>
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
                            <input type="text" class="form-control" name="benefits" placeholder="Enter Benefits"
                                value="{{ old('benefits') }}">
                            @error('benefits')
                            <div class="alert alert-warning" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <hr>

                        <!----<div class="form-group">
                                <label class="form-label">Total Income</label>
                                <input type="text" class="form-control" name="total_income" value="{{ old('total_income') }}" readonly>
                                @error('total_income')
                                    <div class="alert alert-warning" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            -------->



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
            $('#dropdown, input[name="employeeName"]').change(function () {
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

                        $('input[name="twh"]').val(response.totalHours);
                        $('input[name="employeeID"]').val(response.employeeID);
                        $('input[name="late"]').val(response.totalLate);
                        $('input[name="toh"]').val(response.totalOvertime);
                        $('input[name="rph"]').val(response.rateperday);
                        $('input[name="salary"]').val(response.salary);
                        $('input[name="tax"]').val(response.tax);
                        $('input[name="total_income"]').val(response.totalincome);
                        $('input[name="pagibig"]').val(response.pagibig);
                        $('input[name="philhealth"]').val(response.ph);
                        $('input[name="sss"]').val(response.sss);
                        $('input[name="totalgrossincome"]').val(response.totallastweek);
                        $('input[name="otrate"]').val(response.OTrate);
                        $('input[name="otpay"]').val(response.OTpay);
                        $('input[name="totaldays"]').val(response.numberofDays);
                        $('input[name="basicsalary"]').val(response.basicsalary);
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