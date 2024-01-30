<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('WJMLOGO.png') }}">
    <title>User Management</title>
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
    </style>
</head>
<body>
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

    <div class="container" style="margin-top:20px">
        <div class="row">
            <h2>User Management</h2>
            <div style="float: right">
                <!-- Add data-toggle and data-target attributes to open the modal -->
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#UserModal">
                    Add New User
                </button>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Employee id</th>
                        <th>FirstName</th>
                        <th>MiddleName</th>
                        <th>LastName</th>
                        <th>Email</th>
                        
                    
                        
                    </tr>
                </thead>
                <tbody>
                  @foreach ($user as $users)
                  <tr>
                      <td>{{ $users->EmployeeID }}</td>
                      <td>{{ $users->FirstName }}</td>
                      <td>{{ $users->MiddleName }}</td>
                      <td>{{ $users->LastName }}</td>
                      <td>{{ $users->Email }}</td>
                      <td>
                        <a href="{{url('#')}}" class="btn btn-primary btn-sm">Edit</a>
                    </td>
                    <td>
                        <a href="{{url('#')}}" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
                  </tr>
              @endforeach
                  
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="modal fade" id="UserModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content -->
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add New User</h4>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <form action="{{ url('saveUser') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Employee ID</label>
                            <input type="text" class="form-control" name="empid" placeholder="Enter your Employees ID">
            
                            @error('empid')
                            <div class="alert alert-warning" role="alert">
                                {{$message}}
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" name="fname" placeholder="Enter your First Name">
            
                            @error('fname')
                            <div class="alert alert-warning" role="alert">
                                {{$message}}
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Middle Name</label>
                            <input type="text" class="form-control" name="mname" placeholder="Enter your Middle Name">
            
                            @error('mname')
                            <div class="alert alert-warning" role="alert">
                                {{$message}}
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control" name="lname" placeholder="Enter your Last Name">
            
                            @error('lname')
                            <div class="alert alert-warning" role="alert">
                                {{$message}}
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="dropdown">User Role</label>
                            <select id="dropdown" name="userrole">
                                    <option>Admin</option>
                                    <option>Owner</option>
                                    <option>Payroll Master</option>
                            
                            </select>
                            @error('userrole')
                                <div class="alert alert-warning" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="text" class="form-control" name="email" placeholder="Enter your Email">
            
                            @error('email')
                            <div class="alert alert-warning" role="alert">
                                {{$message}}
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Enter your password">
            
                            @error('password')
                            <div class="alert alert-warning" role="alert">
                                {{$message}}
                            @enderror
                        </div>

                        <!-- Modal Footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Add</button>
                            
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

</body>
</html>
