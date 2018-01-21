<?php
session_start();
$username = $_SESSION['username'];
$name = $_SESSION['name'];
$postId = $_REQUEST["postId"];
    include 'dbconnect.php';
    if(!$dbcon){
         die('Error Connecting to database');
    }
    $postQuery = mysqli_query($dbcon,"SELECT * FROM posts WHERE ID = '$postId'");
    $posted = mysqli_fetch_array($postQuery);
    $uname = $posted["Username"];
    $postedData = $posted["Post"]; 

    $commentQuery = "SELECT * FROM `comments` WHERE PostId ='$postId' ORDER BY CommentId DESC";
    $allcomments=$dbcon->query($commentQuery);
    $commentcountquery = mysqli_query($dbcon,"SELECT COUNT(COMMENT) FROM comments
        WHERE postId = '$postId'");
        $commentcount = mysqli_fetch_array($commentcountquery);
    $userQuery = mysqli_query($dbcon,"SELECT users.Name, pictures.Profile FROM users,pictures WHERE pictures.Username = '$uname' AND users.Username = '$uname'");
    $userRow = mysqli_fetch_array($userQuery);
    $postedname = $userRow["Name"]; 
    $postedprofile = $userRow["Profile"];

    if(isset($_POST["comment"])){
        $commented = mysqli_real_escape_string($dbcon, $_POST['commentbox']);
        $insert = "INSERT INTO `comments` (`PostId`, `Username`, `Comment`,`Time`) VALUES ('$postId','$username','$commented','".date('U')."')";
        mysqli_query($dbcon,$insert);
        header("Location: comments.php?postId=".$postId);
    }

?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="theme-color" content="#243447">
        <title>
            <?php echo "$name's Post"; ?>
        </title>
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
        <section id="comments">
            <div class="container">
                <?php
                    if($postedprofile!="") {$picurl=$postedprofile;}
                        else {$picurl = 'img/profile_pic/default.png';}
                        $unix_timestamp = $posted["Time"];
                        $datetime = new DateTime("@$unix_timestamp");
                        $datetime->setTimezone(new DateTimeZone('Asia/Kolkata'));
                 echo'
                    <div id="post-box" class="col-12 col-md-8 offset-md-2">
                        <div class="row" id="pro-info">
                            <div class="col-2 col-md-2 col-lg-1 ">
                               <a id="links" href="profile.php?user='.$uname.'" > <img src="'.$picurl.'" class=" rounded-circle " width="50px" id="post-circle" /></a>
                            </div>
                            <div class="col-8 col-md-9 col-lg-10" id="pro-name" style="text-align:left">
                                <a id="links" href="profile.php?user='.$uname.'"> <h5 style="margin-bottom:0;">'.$postedname.'</h5></a>
                                <p style="font-size:0.75em;">'.$datetime->format('d M Y').' at '.$datetime->format('H:i').' hrs</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-11 offset-lg-1 col-md-10 offset-md-2">
                                <p class="text-left text-md-left" >'.$postedData.'</p>
                            </div>
                        </div>
                        <div class="row text-center ">
                            <div class="col-6 comm-box-1"><a href="#"><div onclick="like();"><i class="fas fa-heart"></i> Like </div></a></div>
                            <div class="col-6 comm-box-1"><a href="comments.php?postId=${response[i].Id}"><div><i class="fas fa-comment"></i> Comment  ('.$commentcount["COUNT(COMMENT)"].')</div></a></div>
                        </div>
                        </div>
                        <div class ="">
                        <div class="col-12 col-md-8 offset-md-2  text-box">
                            <form class="form-control cb" action="comments.php?postId='.$postId.'" method="post">
                                <div class="">
                                <textarea placeholder="Share your comments" name="commentbox" style="width:100%; resize:none; height:60px; padding:10px;"></textarea>
                                <button type="submit" name="comment" class="btn btn-primary btn-block">Comment</button
                                </div>
                            </form>
                        </div>
                        </div>
                    </div>';
                
                    
                    ?>
                    <div class="main-comments">
                        <?php
                    while($srow=$allcomments->fetch_assoc()){ 
                        
                        $userid = $srow["Username"];
                        $unix = $srow["Time"];
                        $date = new DateTime("@$unix");
                        $date->setTimezone(new DateTimeZone('Asia/Kolkata'));
                $assets = mysqli_query($dbcon,"SELECT users.Name, pictures.Profile FROM users,pictures WHERE pictures.Username='$userid' AND users.Username = '$userid'");
                        $pic = mysqli_fetch_array($assets);
                        
                        $picurl = $pic["Profile"];
                        if($picurl!="") {$picurl=$picurl;}
                        else {$picurl = 'img/profile_pic/default.png';}
                        
                        echo '
                        
                        <div class="comments-box col-12 col-md-8 offset-md-2">
                        <div class="row">
                            <div class="col-2 col-md-2 col-lg-1 ">
                               <a id="links" href="profile.php?user='.$srow["Username"].'" > <img src="'.$picurl.'" class=" rounded-circle comment-circle" /></a>
                            </div>
                            <div class="col-8 col-md-9 col-lg-10" id="pro-name" style="text-align:left">
                                <a id="links" href="profile.php?user='.$srow["Username"].'"> <h6 style="margin-bottom:0;">'.$pic["Name"].'</h6></a>
                                <p style="font-size:0.65em;">'.$date->format('d M Y').' at '.$date->format('H:i').'hrs</p>
                            </div>

                        </div>
                        <div class="row">
                                <div class="col-11 offset-lg-1 col-md-10 offset-md-2">
                                <p class="text-left text-md-left" style="margin-top:10px; margin-bottom:10px;">'.$srow["Comment"].'</p>
                                </div>
                            </div>
                        </div>
                        ';
                    }
                ?>
                    </div>
            </div>
        </section>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js"></script>
        <script defer src="https://use.fontawesome.com/releases/v5.0.4/js/all.js"></script>
    </body>

    </html>
