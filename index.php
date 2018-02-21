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
        $count = mysqli_num_rows($result);
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
    <title>The Hub</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <link rel="icon" href="favicon.ico" type="image/x-icon">
</head>

<body>
    <div id="index">
        <div>
            <section class="col-12 col-md-6 d-none d-md-block" id="left-box">
              <div class="text-center">
              <img class="flipper" src="img/favicon.png" style="margin-bottom:30px; border-radius:100%; text-align:center; border: 5px solid rgba(255, 255, 255, 0.12); " width="250px" alt="">
               <h1 >The Hub</h1>
               </div>
            </section>
            <section class="col-12 col-md-6" id="right-box">
                   <div id="mb-title" class="d-block d-md-none">
                   <img src="img/favicon.png" class="flipper" style="margin-bottom:10px; border-radius:100px; text-align:center; border: 3px solid #fff; " width="100px" alt="">
                   <h2>The Hub</h2>
                   </div>
                <div class="col-lg-10 offset-lg-1 col-md-10 offset-md-1 col-sm-10 offset-sm-1 col-12" id="logs">
                   
                   <div id="index-login">
                    <h3>Share your thoughts with the world right now</h3>
                    <h5 class="mars-top-30">Login to The Hub</h5>
                    <form method="post" name="login" action="login.php">
                        <div id="login-2" class="container">
                            <div class="row mars-btm-10">
                                <input class="col-12 text-box1" type="text" placeholder="Email Id" name="email" required>
                            </div>
                            <div class="row mars-btm-10">
                                <input class="col-12 text-box1" type="password" placeholder="Password" name="password" required>
                            </div>
                        </div>
                        <div>
                            <input type="submit" name="login" class="btn btn-block btn-primary mars-top-10 shadow" value="Login">
                        </div>
                         <p class="text-center text-danger">
                        <?php 
                    if(isset($loginErr)){
                    echo $loginErr; 
                    }
                        ?>
                    </p>
                    </form>
                    <p class="text-right mars-top-30">New user? <a class="text-primary" href="signup.php"> Click here to register</a></p>
                </div>
                </div>
            </section>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js">
    </script>
</body>

</html>
