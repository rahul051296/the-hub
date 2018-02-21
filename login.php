<?php 
 session_start();
if(isset($_SESSION['username'])!=''){
       header("Location: home.php");

}
if(isset($_POST['login'])){
    include 'dbconnect.php';
    
    if(!$dbcon){
         die('Error Connecting to database');
    }
            $email = mysqli_real_escape_string($dbcon, $_POST['email']);
            $pwd = mysqli_real_escape_string($dbcon, $_POST['password']);
            $password = hash('md5',$pwd);
    
        $result = mysqli_query($dbcon,"SELECT * FROM `users` WHERE Email = '$email'");
        $row=mysqli_fetch_array($result);
        if($row['Email']==$email && $row['Password']==$password){
            $_SESSION['username'] = $row['Username'];
            $_SESSION['name'] = $row['Name'];
            header("Location: home.php");
        }   
        else{
            $loginErr = 'Email or Password is incorrect.';
        }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="theme-color" content="#243447">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
     <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <link rel="icon" href="favicon.ico" type="image/x-icon">
</head>

<body class="back1">
         <nav class="navbar navbar-expand-md bg-custom-2 navbar-dark">
            <!-- Brand -->
                           <div class="container">

                <a class="navbar-brand" href="home.php">The Hub</a>

            <!-- Toggler/collapsibe Button -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>

            <!-- Navbar links -->
            <div class="collapse navbar-collapse" id="collapsibleNavbar">
                <ul class="navbar-nav ml-auto">

                    <li class="nav-item">
                        <a class="nav-link text-center" href="signup.php">Sign Up</a>
                    </li>
                </ul>
            </div>
             </div>
        </nav>
        <div class="col-12 text-center" id="settings-title">
                    <h1 class="title">Login</h1>
                </div>
    <section class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <form  method="post" name="login" action="login.php">
                   <div id="login">
                    <div class="row mars-btm-10">
                        <input class="col-12 text-box1" type="text" placeholder="Enter Email Id" name="email" required>
                    </div>
                    <div class="row mars-btm-10">
                        <input class="col-12 text-box1" type="password" placeholder="Enter Password" name="password" required>
                    </div>
                    </div>
                    <div>
                        <input type="submit" name="login" class="btn btn-block btn-primary mars-top-10 shadow" value="Sign In">
                    </div>
                    <p class="text-center text-danger">
                        <?php 
                    if(isset($loginErr)){
                    echo $loginErr; 
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
