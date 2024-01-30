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

        
        tbody tr:hover {
            background-color: #f5f5f5;
        }
        .navbar {
        background-color: #FFD580; 
        border: none; 
    

    }

    .navbar-brand {
        color: white;
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
    .resizable-input {
    width: 200px; /* Set your desired width */
    /* You can adjust the width as needed */
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
            <h2>Attendance Table</h2>
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
                <input type="text" class="form-control resizable-input" name="employeeID" id="employeeID" value="{{ old('employeeID') }}" readonly>

                @error('employeeID')
                    <div class="alert alert-warning" role="alert">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">EmployeeID</label>
                <input type="text" class="form-control resizable-input" name="bhr" id="employeeID" value="{{ old('employeeID') }}" readonly>

                @error('employeeID')
                    <div class="alert alert-warning" role="alert">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class ="form-group">
                <label for="start_date">Start Date:</label>
                <input type="date" name="start_date" style="padding-right: 20px;" required>

                <label for="end_date">End Date:</label>
                <input type="date" name="end_date" required>
            </div>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>TimeIn</th>
                        <th>TimeOut</th>
                        <th>Basic Hour</th>
                        <th>OT Hour</th>
                        <th>Benefits</th>
                        <th>OT</th>
                        <th>Rest Day</th>
                        <th>Holiday</th>
                        <th>Special Holiday</th>
                        
                    </tr>
                </thead>
                <tbody>
                  @foreach ($timekeeping as $time)
                  <tr>
                      <td>{{ $time->created_at->format('Y-m-d') }}</td>
                      <td>{{ $time->TimeIn }}</td>
                      <td>{{ $time->TimeOut }}</td>
                      <td> <input type="text" class="form-control resizable-input" name="bhr" id="employeeID" value="{{ old('employeeID') }}" readonly> </td>
                      <td>{{ $time->Overtime }}</td>
                      <td><input type="checkbox" name="" id=""></td>
                      <td><input type="checkbox" name="" id=""></td>
                      <td><input type="checkbox" name="" id=""></td>
                      <td><input type="checkbox" name="" id=""></td>
                      <td><input type="checkbox" name="" id=""></td>
                      
                </tr>
                  </tr>
              @endforeach
                  
                </tbody>
            </table>
        </div>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


<script>
    $(document).ready(function () {
        $('#dropdown, input[name="employeeName"]').change(function () {
            updateEmployeeData();
        });

        function updateEmployeeData() {
            var employeeName = $('#dropdown').val();
            var startDate = $('input[name="start_date"]').val();
            var endDate = $('input[name="end_date"]').val();

            // Make an AJAX request to get the total hours and timekeeping data
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
                    $('input[name="tax"]').val(response.tax);
                    $('input[name="total_income"]').val(response.totalincome);
                    $('input[name="bhr"]').val(response.totalHours);
                    
                    // Update the table content
                    updateTable(response.timekeepingData, response);
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }

        function updateTable(timekeepingData, response) {
            var tableBody = $('.table tbody');
            tableBody.empty(); // Clear existing table rows
            var counter = 0;
            var totalhrpd = JSON.parse(response.totalhoursperday);
            // Loop through the timekeeping data and append rows to the table
            $.each(timekeepingData, function (index, time) {
                
                
                var newRow = '<tr>';
                newRow += '<td>' + new Date(time.created_at).toISOString().slice(0, 10) + '</td>';
                newRow += '<td>' + time.TimeIn + '</td>';
                newRow += '<td>' + time.TimeOut + '</td>';
                newRow += '<td><input type="text" class="form-control resizable-input" name="bhr' + index +   '" readonly></td>';
                newRow += '<td>' + time.TimeOut + '</td>';
                newRow += '<td><input type="checkbox" name="basicHour" id="basicHour' + index + '"></td>';
                newRow += '<td><input type="checkbox" name="basicHour" id="basicHour' + index + '"></td>';
                newRow += '<td><input type="checkbox" name="basicHour" id="basicHour' + index + '"></td>';
                newRow += '<td><input type="checkbox" name="basicHour" id="basicHour' + index + '"></td>';
                newRow += '<td><input type="checkbox" name="basicHour" id="basicHour' + index + '"></td>';
                tableBody.append(newRow);


                console.log(response.totalhoursperday);




                

                $('input[name="bhr' + index + '"]').val(totalhrpd[counter]);
                counter += 1;


            });
        }
    });
</script>

</html>
