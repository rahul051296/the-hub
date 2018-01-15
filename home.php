<?php
    session_start();
if( !isset($_SESSION['username']) ) {
        header("Location: login.php");
        exit;
 }
    include 'dbconnect.php';
    $username = $_SESSION['username'];
    if(!$dbcon){
         die('Error Connecting to database');
    }
        $res = mysqli_query($dbcon,"SELECT * FROM `users` WHERE Username = '$username'");
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
        <nav class="navbar navbar-expand-md fixed-top bg-custom-2 navbar-dark">
            <!-- Brand -->
            <div class="container">
            <a class="navbar-brand" href="index.php">The Hub</a>

            <!-- Toggler/collapsibe Button -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>

            <!-- Navbar links -->
            <div class="collapse navbar-collapse" id="collapsibleNavbar">
                <ul class="navbar-nav ml-auto">

                    <li class="nav-item">
                        <a class="nav-link" href="home.php">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php
            echo $name;
            ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="profile.php?user=<?php echo $username; ?>">Profile</a>
                            <a class="dropdown-item" href="settings.php">Settings</a>
                            <a class="dropdown-item" href="signout.php">Sign Out</a>
                        </div>
                    </li>
                    <li class="nav-item">

                        <form action="search.php" method="post" name="searchForm">
                       <div class="input-group">
                        <input type="text" name="query" id="search" class="form-control" placeholder="Search">
                        <span class="input-group-btn"></span>
                                <button type="submit" class="btn btn-custom" id="search" name="search">
								<i class="fas fa-search"></i>
                        </button>
                        </span>
                    </div>
                        </form>
                    </li>
                </ul>
            </div>
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
