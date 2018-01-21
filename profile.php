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
    

            $posts = "SELECT * FROM `posts` WHERE Username = '$profilename' ORDER BY Id DESC";
            $allposts=$dbcon->query($posts);
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
         <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <link rel="icon" href="favicon.ico" type="image/x-icon">
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
                                    <input type="text" name="query" id="" class="form-control search" placeholder="Search">
                                    <span class="input-group-btn">
                                    <button type="submit" class="btn btn-custom search" id="" name="search">
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
                <div class="container">
                    <div class="row" style="margin-top:50px; margin-bottom:50px;">
                        <?php
                    while($srow=$allposts->fetch_assoc()){ 
                        if($pprofilepic!="") {$picurl=$pprofilepic;}
                        else {$picurl = 'img/profile_pic/default.png';}
                        $unix_timestamp = $srow["Time"];
                
                        $datetime = new DateTime("@$unix_timestamp");
                        $datetime->setTimezone(new DateTimeZone('Asia/Kolkata'));
                        if($username == $srow["Username"]){
                            $option = '<span class="dropdown">
                                <a href="#" data-toggle="dropdown"><i style="color: #737373;" class="fas fa-ellipsis-v"></i></a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#">Edit</a>
                                    <a class="dropdown-item" href="deletePost.php?postId='.$srow["Id"].'">Delete</a>
                                </div>
                            </span>';
                        }
                        else{
                            $option = '';
                        }
                    echo'
                    <div id="post-box" class="col-12 col-md-8 offset-md-2">
                            <div class="row" id="pro-info">
                            <div class="col-2 col-md-2 col-lg-1 ">
                               <a id="links" href="profile.php?user='.$srow["Username"].'" > <img src="'.$picurl.'" class=" rounded-circle " width="50px" id="post-circle" /></a>
                            </div>
                            <div class="col-8 col-md-9 col-lg-10" id="pro-name" style="text-align:left">
                                <a id="links" href="profile.php?user='.$srow["Username"].'"> <h5 style="margin-bottom:0;">'.$srow["Name"].'</h5></a>
                                <p style="font-size:0.75em;">'.$datetime->format('d M Y').' at '.$datetime->format('H:i').' hrs</p>
                            </div>
                            <div class="col-1 col-md-1 col-lg-1 text-right" style="float:right;" id="drop-circles">
                                '.$option.'
                            </div>
                            </div>
                            <div class="row">
                                <div class="col-11 offset-lg-1 col-md-10 offset-md-2">
                                <p class="text-left text-md-left" >'.$srow["Post"].'</p>
                                </div>
                            </div></div>';
                    }
                    ?>
                    </div>
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

        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js"></script>
        <script defer src="https://use.fontawesome.com/releases/v5.0.4/js/all.js"></script>
        <!--        <script src="js/index.js"></script>-->
    </body>

    </html>
