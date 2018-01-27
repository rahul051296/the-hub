<?php
    session_start();
    if( !isset($_SESSION['username']) ) {
            header("Location: login.php");
            exit;
     }
    include 'dbconnect.php';
    $username = $_SESSION['username'];
    $name = $_SESSION['name'];
    if(!$dbcon){
         die('Error Connecting to database');
    }

    $sql = "SELECT DISTINCT users.*, (SELECT COUNT(followers.Follower) FROM followers WHERE users.Username = followers.Follower AND followers.Username = '$username') as FollowTest FROM users,followers WHERE followers.Username='$username'";
    $result=$dbcon->query($sql);
 
      
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="theme-color" content="#243447">
        <title>Search</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/styles.css">
         <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    </head>

    <body>
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

                            <form action="search.php" method="get" name="searchForm">
                                <div class="input-group">
                                    <input type="text" name="query" id="" class="form-control search-1" placeholder="Search">
                                    <span class="input-group-btn">
                                    <button type="submit" class="btn btn-custom search-2" id="" name="search">
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
        <article >
            <div class="col-12 text-center" id="settings-title">
                    <h1 class="title">Other Users</h1>
                </div>
            <section class="container"id="search-results">
                     <div class="row">
                         <?php
                            while($srow=$result->fetch_assoc()){
                                
                                if($srow["FollowTest"] == 1){
                                    $btn = '<h5><span class="badge badge-primary" style="font-weight: 100 !important;">Following <i style="font-size:1em;" class="fas fa-1x fa-check-circle"></i>
                                    </span></h5>';
                                }
                                else{
                                    $btn = '<h5 style="font-weight:normal;"><span class="badge badge-secondary" style="font-weight: 100 !important;">Follow <i style="font-size:1em;" class="fas fa-1x fa-plus-circle"></i>
                                    </span></h5> ';
                                }
                                $profilename = $srow["Username"];
                                $profilepics = mysqli_query($dbcon,"SELECT * FROM `pictures` WHERE Username = '$profilename'");
                                $picrow = mysqli_fetch_array($profilepics); 
                                $url = $picrow['Profile'];
                                if($url!=''){
                                    $url = $picrow['Profile']; 
                                }
                                else{
                                    $url = "img/profile_pic/default.png";
                                }
                    echo ' <div class="col-lg-4 col-md-6 col-12  ">
                    <div class="boxes">
                       <a href="profile.php?user='.$srow["Username"].'"><div class="row mars-btm-20">
                       <div class="col-lg-4 col-md-4 col-4" >
                        <img src="'.$url.'" id="search-pic" class="img-responsive" alt="">
                           </div>
                        <div class="col-lg-8 col-md-8 col-8 pads-top-10">
                        <h5 class="" style="margin-bottom:0">'.$srow["Name"].'</h5>
                        <p style="font-size:0.8em; margin-bottom:10px;">@'.$srow["Username"].'</p>
                        '.$btn.'
                           </div>
                        </div></a>
                        </div>
                        </div>';
                            }
                              ?>
                        </div>
            </section>
        </article>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js"></script>
        <script defer src="https://use.fontawesome.com/releases/v5.0.4/js/all.js"></script>
        <!--        <script src="js/index.js"></script>-->
    </body>

    </html>
