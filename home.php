<?php
    session_start();
if( !isset($_SESSION['userEmail']) ) {
        header("Location: login.php");
        exit;
 }
    include 'dbconnect.php';
    $email = $_SESSION['userEmail'];
    if(!$dbcon){
         die('Error Connecting to database');
    }
        $res = mysqli_query($dbcon,"SELECT * FROM `users` WHERE Email = '$email'");
        $row=mysqli_fetch_array($res);

        $name = $row['Name'];
       
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

        <title>Home</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/styles.css">
    </head>

    <body>
        <nav class="navbar navbar-expand-md bg-custom-2 navbar-dark">
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
                        <a class="nav-link text-center" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-center" href="#">About</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-center" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php
            echo $name;
            ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="profile.php">Profile</a>
                            <a class="dropdown-item" href="#">Settings</a>
                            <a class="dropdown-item" href="signout.php">Sign Out</a>
                        </div>
                    </li>
                    <li class="nav-item">

                        <form class="form-inline">
                            <input class="form-control mr-sm-2" type="text" placeholder="Search">
                            <button class="btn btn-outline-warning my-2 my-sm-0" type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
        <section class="container" id="home">
            <div class="row">
                <div class="col-4">
                    <img src=<?php echo "'img/profile_pic/01.jpg'" ?> width="200px" class="img-responsive" />
                   <!-- <form action="uploadPic.php" method="post" name="upload" enctype="multipart/form-data">
                        <input type="file" name="fileToUpload" id="fileToUpload">
                        <input type="submit" value="Upload Image" name="submit">
                    </form>-->
                </div>
                <div class="col-8">

                </div>
            </div>
        </section>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js"></script>
        <script defer src="https://use.fontawesome.com/releases/v5.0.4/js/all.js"></script>
        <!--        <script src="js/index.js"></script>-->
    </body>

    </html>
