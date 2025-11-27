<!DOCTYPE html>
<html lang="en">
 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
 
<body>
    <!-- Navigation Bar -->
    <nav>
        <div class="logo">StudyMate</div>
        <div class="nav-buttons">
            <a href="#" class="nav-btn login-btn">Login</a>
            <a href="#" class="nav-btn signup-btn">Sign Up</a>
        </div>
    </nav>

    <div class="signin">
        <div class="container">
            <img src="{{ asset('images/login.png') }}" alt="logo" class="logo-image">
            
            <div class="login">
                <!-- Display validation errors -->
                @if ($errors->any())
                    <div class="error-messages">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <!-- Form to submit login credentials -->
                <form method="POST" action="{{ route('login.submit') }}" class="login-form">
                    @csrf
                    <h2 style="color: white;">Login to Your Account</h2>
                    
                    <input id="email" type="email" name="email" placeholder="Enter Your Email" required>
                    
                    <input id="password" type="password" name="password" placeholder="Enter Your Password" required>
                    
                    <button class="btn" type="submit">Login</button>
                    
                    <p>Don't have an account? <a href="#">Sign up</a></p>
                </form>
            </div>
        </div>
    </div>
</body>

</html>