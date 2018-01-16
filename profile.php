<?php
    session_start();
if( !isset($_SESSION['username']) ) {
        header("Location: login.php");
        exit;
 }
    include 'dbconnect.php';
    $username = $_SESSION['username'];
    $profilename = $_REQUEST["user"];
    if(!$dbcon){
         die('Error Connecting to database');
    }
        $userdata = mysqli_query($dbcon,"SELECT * FROM `users` WHERE Username = '$username'");
        $profiledata = mysqli_query($dbcon,"SELECT * FROM `users` WHERE Username = '$profilename'");
        $profilepics = mysqli_query($dbcon,"SELECT * FROM `pictures` WHERE Username = '$profilename'");
        $urow=mysqli_fetch_array($userdata);
        $prow = mysqli_fetch_array($profiledata);
        $picrow = mysqli_fetch_array($profilepics);
        $uname = $urow['Name'];

        $pname = $prow['Name'];
        $pusername = $prow['Username'];
        $pprofilepic = $picrow['Profile'];
        $pcoverpic = $picrow['Cover'];
        $pbio = $prow['Bio'];
        $pweb = $prow['Website'];
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="theme-color" content="#243447">
        <title>
            <?php echo $pname;  ?>
        </title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/styles.css">
    </head>

    <body>
        <nav class="navbar navbar-expand-md bg-custom-2  fixed-top  navbar-dark navbar-fixed">
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
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php
            echo $uname;
            ?>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="profile.php?user=<?php echo $username; ?>">Profile</a>
                                <a class="dropdown-item" href="settings.php">Settings</a>
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
        <article id="cover">
            <div class="container">
                <div id="profile-head">
                    <img src=<?php if($pprofilepic!='' ) { echo "'$pprofilepic'"; } else{ echo "'img/profile_pic/default.png'"; } ?> width="200px" class="img-responsive img-rounded" id="profile-pic" />
                </div>
                <div id="profile-details">
                    <h2>
                        <?php echo $pname; ?>
                    </h2>
                    <h5>&#64;
                        <?php echo $pusername; ?>
                    </h5>
                    <p class="col-md-6 offset-md-3 col-xs-12 col-sm-12">
                        <?php 
                            if(isset($pbio)){
                            echo $pbio;
                            }
                        ?>
                    </p>
                    <?php
                    if(isset($pweb)){
                        echo '<a href="'.$pweb.'" target="_blank">'.$pweb.'</a>';
                    }
                          ?>
                </div>
            </div>
        </article>
        <?php 
        if($pcoverpic!=''){
            echo 
    "<script>
        document.getElementById('cover').style.background = 'url($pcoverpic)';
    </script>"; 
        }
        ?>
        <section class="container" id="home">


        </section>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js"></script>
        <script defer src="https://use.fontawesome.com/releases/v5.0.4/js/all.js"></script>
        <!--        <script src="js/index.js"></script>-->
    </body>

    </html>
