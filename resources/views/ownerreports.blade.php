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
        color: white; 
        font-weight: bolder;
    }

    .navbar-nav li a:hover {
        background-color: #d36905;
    }

    .navbar-right {
        margin-right: 20px; 
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
            <li><a href="/owdashboard">Home</a></li>
            <li><a href="/ownerreports">Reports</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="/login"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        </div>
    </nav>
    
    <div class="container" style="margin-top:20px">
        <div class="row">
            <h2>Reports</h2>
            <form method="GET" action="{{url('/filter2')}}">
            <div class="form-group">
            
            <div class ="col-md-1"style="float: right">
                    <label style="visibility: hidden">SPACE</label>
                    <button type="submit" class="btn btn-info">Filter</button>
             </div>
             <div class ="col-md-3"style="float: right">
                <label> End Date: </label>
                <input type ="date" name="end_date" class="form-control">
                @error('end_date')
                     <div class="alert alert-warning" role="alert">
                    {{ $message }}
                    </div>
                 @enderror
            </div>
            <div class ="col-md-3" style="float: right">
                <label> Start Date: </label>
                <input type ="date" name="start_date" class="form-control">
                @error('start_date')
                     <div class="alert alert-warning" role="alert">
                    {{ $message }}
                    </div>
                 @enderror
            </div>
        </div>
            </form>
            <table class="table table-striped">
                <thead style="background-color: #f5f5f5; color: #333;">
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
            
            
        </div>
        <div class="container">
            <div class="text-center mt-3">
                @if ($payroll->isNotEmpty())
                    <a href="{{ route('generatesummaryreport', ['id' => $payroll->first()->id, 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="btn btn-primary btn-sm">Generate Summary Report</a>
                @else
                    <button class="btn btn-primary btn-sm" disabled>Generate Summary Report</button>
                @endif
            </div>
        </div>
        
    
</div>
    
    
        
</body>
</html>
