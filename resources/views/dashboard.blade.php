<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('WJMLOGO.png') }}">

    <style>
    body {
  font-family: Arial, sans-serif;
  margin: 0;
}

.dashboard {
  padding: 20px;
}

.employee-status {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
}

.status {
  text-align: center;
  padding: 10px;
  border: 1px solid #070101;
  border-radius: 5px;
}

.status i {
  margin-bottom: 5px;
}

/* Responsive Styles */
@media (max-width: 768px) {
  .employee-status {
    grid-template-columns: 1fr; /* Display one column for small screens */
  }
 
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
    <title>Home</title>
</head>
<body>
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
    <nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand" href="#">Payroll System</a>
          </div>
          <ul class="nav navbar-nav">
            <li><a href="/dashboard">Home</a></li>
            <li><a href="/UserManagement">User Management</a></li>
            <li><a href="/activitylogs">Activty Logs</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="/login"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        </div>
      </nav>
      <div>
        <div class="dashboard">
          <div class="employee-status">
              <div class="status">
                  <i class="fas fa-clock"></i>
                  <span>On Time: <span>{{$onTimeCount}}</span></span>
              </div>
              <div class="status">
                  <i class="fas fa-exclamation-triangle"></i>
                  <span>Late: <span>{{$lateCount}}</span></span>
              </div>
              <div class="status">
                  <i class="fas fa-users"></i>
                  <span>Total Employees: <span>{{$totalEmployeesCount}}</span></span>
              </div>
          </div>
      </div>
</body>
</html>
