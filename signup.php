<?php

session_start();
  if( isset($_SESSION['username'])!="" )
  {
   header("Location: home.php");
  }

     if(isset($_POST['submit']))
{  
           include 'dbconnect.php';
         
            if(!$dbcon)
                {
                    die('Error Connecting to database');
                }
                $name =mysqli_real_escape_string($dbcon, $_POST['name']);
                $email = mysqli_real_escape_string($dbcon, $_POST['email']);
                $username = mysqli_real_escape_string($dbcon, $_POST['username']);
                $pwd = mysqli_real_escape_string($dbcon, $_POST['password']);
                $pwd2 = mysqli_real_escape_string($dbcon, $_POST['password2']);
                $password = hash('md5',$pwd);
         $sqlinsert1="INSERT INTO users (Name,Username,Email,Password) values('$name','$username','$email','$password')";
          $sqlinsert2="INSERT INTO pictures (Username) values('$username')";        
                    if(!mysqli_query($dbcon, $sqlinsert1))
                    {
                    
                    $msg = "Email ID already exists.";
                    }
                else
                {
                    mysqli_query($dbcon, $sqlinsert2);
                    $msg ="Account created successfully. Click <a href='login.php'>here</a> to Login.";
                }
         
     }
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>Sign Up</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/styles.css">
    </head>

    <body class="back1">
        <nav class="navbar navbar-expand-md bg-custom navbar-dark">
            <!-- Brand -->
            <a class="navbar-brand" href="index.php">The Hub</a>

            <!-- Toggler/collapsibe Button -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>

            <!-- Navbar links -->
            <div class="collapse navbar-collapse" id="collapsibleNavbar">
                <ul class="navbar-nav ml-auto">

                    <li class="nav-item">
                        <a class="nav-link text-center" href="login.php">Login</a>
                    </li>
                </ul>
            </div>
        </nav>
        <section class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <form id="register" method="post" name="register" action="signup.php">
                        <h1 class="text-center title">SIGN-UP</h1>
                        <div class="row mars-btm-10">
                            <label class="col-4" for="name">Name</label><input type="text" class="col-8" placeholder="Enter a Name" name="name" required>
                        </div>
                        <div class="row mars-btm-10">
                            <label class="col-4" for="email">E-Mail</label><input class="col-8" type="text" placeholder="Enter an Email Id" name="email" required>
                        </div>
                        <div class="row mars-btm-10">
                            <label class="col-4" for="tel">Username</label><input class="col-8" type="text" placeholder="Enter a Username" name="username" required>
                            
                        </div>
                        <div class="row mars-btm-10">
                            <label class="col-4" for="pass">Password</label><input class="col-8" type="password" placeholder="Enter a Password" name="password" required>
                        </div>
                        <div class="row mars-btm-10">
                            <label class="col-4" for="password2">Re-Enter Password</label><input class="col-8" type="password" placeholder="Enter a Password again" name="password2" required>
                        </div>
                        
                        <div class="text-center" id="passErr"></div>
                        <input type="submit" class="btn btn-block btn-success mars-top-30" value="Submit" id="submit" name="submit">
                        <p class="text-center">
                            <?php 
                    if(isset($msg)){
                    echo $msg; 
                    }
                        ?>
                        </p>
                    </form>
                </div>
            </div>
        </section>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js"></script>
        <script src="js/index.js"></script>
    </body>

    </html>
