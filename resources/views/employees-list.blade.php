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
            <h2>Employees List</h2>
            <div style="float: right"><a href="{{url('addemployees')}}"><button type="button" class="btn btn-info">Add</button></a>
                
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Employee id</th>
                        <th>FirstName</th>
                        <th>MiddleName</th>
                        <th>LastName</th>
                        <th>Email</th>
                        <th>Position</th>
                        <th>Rate per day</th>
                        <th>Contact</th>
                        <th>Department</th>
                        
                        
                    </tr>
                </thead>
                <tbody>
                  @foreach ($employees as $emp)
                  <tr>
                      <td>{{ $emp->EmployeeID }}</td>
                      <td>{{ $emp->FirstName }}</td>
                      <td>{{ $emp->MiddleName }}</td>
                      <td>{{ $emp->LastName }}</td>
                      <td>{{ $emp->Email }}</td>
                      <td>{{ $emp->Position }}</td>
                      <td>{{ $emp->rate_per_day}}</td>
                      <td>{{ $emp->Contact }}</td>
                      <td>{{ $emp->Department }}</td>
                      
                      <td>
                        <a href="{{url('edit-employees/'. $emp->id)}}" class="btn btn-primary btn-sm">Edit</a>
                    </td>
                    <td>
                        <a href="{{url('delete-employees/'. $emp->id)}}" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
                  </tr>
              @endforeach
                  
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $employees->links() }}
            </div>
        </div>
    </div>

    <div class="modal fade" id="dataPrivacyModal" tabindex="-1" role="dialog" aria-labelledby="dataPrivacyModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dataPrivacyModalLabel">Data Privacy Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                        Certainly! Here's a condensed version of a privacy policy:
                        
                        Privacy Policy: WJM DREAM GRAPHIX SERVICING collects personal and non-personal information, such as names, email addresses, and browsing data, to improve and maintain our website, respond to inquiries, and send relevant communications. We do not sell or share your information with third parties, except trusted service providers. We implement security measures to protect your data. This policy may be updated, and the last revision date is provided. If you have questions, contact us at jacinto.139812@marikina.sti.edu.ph.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="acceptPrivacyBtn">I Accept</button>
                </div>
            </div>
        </div>
    </div>

</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function () {
       if (localStorage.getItem('privacyAccepted') !== 'true') {
       $('#dataPrivacyModal').modal('show');
       }
       $('#acceptPrivacyBtn').click(function () {
        localStorage.setItem('privacyAccepted', 'true');
});
});
</script>
</html>
