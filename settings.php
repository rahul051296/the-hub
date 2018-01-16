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
    if(isset($_SESSION['url'])){
        $url = $_SESSION['url'];
        mysqli_query($dbcon,"UPDATE pictures SET Profile = '$url' WHERE Username = '$username'");
    }
    if(isset($_SESSION['cover_url'])){
        $url = $_SESSION['cover_url'];
        mysqli_query($dbcon,"UPDATE pictures SET Cover = '$url' WHERE Username = '$username'");
    }
    if(isset($_POST['save'])){
        $name = mysqli_real_escape_string($dbcon,$_POST['name']);
        $email = mysqli_real_escape_string($dbcon,$_POST['email']);
        $bio = mysqli_real_escape_string($dbcon, $_POST['bio']);
        $website = mysqli_real_escape_string($dbcon, $_POST['website']);
        $password = mysqli_real_escape_string($dbcon, $_POST['password']);

        if($bio!=''){
            $sql = "UPDATE users SET bio = '$bio' WHERE Username='$username'";
            mysqli_query($dbcon,$sql);
            $response = "Changes have been Saved.";
        }
        if($name!=''){
            $sql = "UPDATE users SET name = '$name' WHERE Username='$username'";
            mysqli_query($dbcon,$sql);
            $response = "Changes have been Saved.";
        }
        if($email!=''){
            $sql = "UPDATE users SET email = '$email' WHERE Username='$username'";
            mysqli_query($dbcon,$sql);
            $response = "Changes have been Saved.";
        }
        if($website!=''){
            $sql = "UPDATE users SET website = '$website' WHERE Username='$username'";
            mysqli_query($dbcon,$sql);
            $response = "Changes have been Saved.";
        }
        if($password!=''){
            $pass = hash('md5',$password);
            $sql = "UPDATE users SET password = '$pass' WHERE Username='$username'";
            mysqli_query($dbcon,$sql);
            $response = "Changes have been Saved.";
        }
    }
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="theme-color" content="#243447">
        <title>Settings</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/styles.css">
    </head>

    <body id="sets">
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
                            <a class="nav-link" href="home.php">Home</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle " href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php
            echo $name;
            ?>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="profile.php?user=<?php echo $username; ?>">Profile</a>
                                <a class="dropdown-item" href="#">Settings</a>
                                <a class="dropdown-item" href="signout.php">Sign Out</a>
                            </div>
                        </li>
                        <li class="nav-item">

                            <form action="search.php" method="get" name="searchForm">
                                <div class="input-group">
                                    <input type="text" name="query" id="search" class="form-control" placeholder="Search">
                                    <span class="input-group-btn">
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
        <section class="container-fluid" id="settings">
            <div class="row">
                <div class="col-12 text-center" id="settings-title">
                    <h1 class="title">Settings</h1>
                </div>
                <div class="col-12 text-left container" id="settings-content">
                    <div class="mars-btm-20">
                        <form action="uploadPic.php" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-4">
                                    <label>Edit Profile Picture</label>
                                </div>
                                <div class="col-8">
                                    <input type="file" name="fileToUpload" id="fileToUpload">
                                    <input type="submit" value="Upload Image" name="upload">
                                </div>
                            </div>
                        </form>

                    </div>
                    <div class="mars-btm-20">
                        <form action="uploadCover.php" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-4">
                                    <label>Edit Cover Picture</label>
                                </div>
                                <div class="col-8">
                                    <input type="file" name="fileToUpload" id="fileToUpload">
                                    <input type="submit" value="Upload Image" name="upload">
                                </div>
                            </div>
                        </form>
                    </div>
                    <form action="settings.php" name="settings" method="post">
                        <div class="row mars-btm-20">
                            <label class="col-4">Change Name</label>
                            <input type="text" style="border: 3px solid #f1f1f1;" class="col-8 text-box" placeholder="Enter a Name" name="name">
                        </div>
                        <div class="row mars-btm-20">
                            <label class="col-4 ">Change E-Mail</label>
                            <input class="col-8 text-box" type="text" placeholder="Enter an Email Id" name="email">
                        </div>
                        <div class="row mars-btm-20">
                            <label class="col-4 ">Change Password</label>
                            <input class="col-8 text-box"  type="password" placeholder="Enter your new Password" name="password">
                        </div>
                        <div class="row mars-btm-20">
                            <label class="col-4 ">Edit Website</label>
                            <input class="col-8 text-box" type="text" placeholder="Enter your Website URL" name="website">
                        </div>
                        <div class="row mars-btm-20">
                            <label class="col-4">Edit Bio</label>
                            <textarea class="col-8 text-box" type="text" placeholder="Add your Bio" name="bio" style="resize:none; height:150px;"></textarea>
                        </div>
                        <div class="row mars-btm-20 text-center">
                            <div class="col-4 offset-4 mars-btm-20">
                                <input type="submit" class="btn btn-primary mars-top-30" value="Save Changes" id="save" name="save">
                            </div>
                        </div>
                    </form>
                    <div class="mars-btm-20 text-center text-success">
                        <?php 
                        if(isset($response)){
                            echo $response;
                        } 
                        ?>
                    </div>


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
