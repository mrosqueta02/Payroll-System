<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="icon" type="image/png" href="{{ asset('WJMLOGO.png') }}">
    <title>Payroll System</title>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: auto;
            margin-top: 50px; 
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">Payroll System</a>
            </div>
        </div>
    </nav>

    <div class="container login-container">
        <form method="post" action="{{ url('login') }}">
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
            @csrf
            <label class="form-label">Email:</label>
            <input type="text" class="form-control" name="email" placeholder="Enter your email.">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="pass" placeholder="Enter your password.">
            <button type="submit" class="btn btn-primary btn-lg btn-block">Sign in</button>
        </form>
    </div>

</body>
</html>
