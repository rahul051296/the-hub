<?php
        session_start();

        if(!isset($_SESSION['username']) ) {
                header("Location: login.php");
                exit;
         }

        include 'dbconnect.php';
        $username = $_SESSION['username'];
        if(!$dbcon){
             die('Error Connecting to database');
        }

        $profiledata = mysqli_query($dbcon,"SELECT users.Name,users.Username,users.Email,users.Bio,users.Website,pictures.Profile,pictures.Cover FROM `users`, `pictures` WHERE users.Username='$username' AND pictures.Username = '$username'");
        $data = mysqli_fetch_array($profiledata);
        $name = $data['Name'];
        $bio = $data['Bio'];
        $web = $data['Website'];
        $profilepic = $data['Profile'];

        if(isset($_POST['update'])){
            $posted = mysqli_real_escape_string($dbcon, $_POST['posted']);
            $insert="INSERT INTO posts (Username,Name,Post,Time) values('$username','$name','$posted','".date('U')."')";
            $result=$dbcon->query($insert);
            header('Location: home.php');
        }

        $all = "SELECT DISTINCT users.Name,users.Username,(SELECT pictures.Profile from pictures where users.Username = pictures.Username) as Profile FROM users,followers  ORDER BY RAND() LIMIT 5";
        
        $allusers=$dbcon->query($all);

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="theme-color" content="#243447">
        <title>Home</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/styles.css">
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
        <link rel="icon" href="favicon.ico" type="image/x-icon">
    </head>

    <body>
        <nav class="navbar navbar-expand-md fixed-top bg-custom-2 navbar-dark">
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

                            <form action="search.php?" method="get" name="searchForm">
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
        <section id="home">
            <article class="container">
                <div class="row ">
                    <div class="col-md-4 d-md-none d-block  text-left">
                        <div class="container">
                            <div class="row shadow" id="mb-view">
                                <div>
                                    <a href="profile.php?user=<?php echo $username; ?>">
                          <img src=<?php if($profilepic!='' ) { echo "'$profilepic'"; } else{ echo "'img/profile_pic/default.png'"; } ?> class="rounded small-pic">
                          </a>
                                </div>
                                <div class="text-left mars-lft-5">
                                    <h5 class="mars-lft-5" style="margin-bottom:0; margin-top:5px">
                                        <?php echo $name; ?>
                                    </h5>
                                    <h6 class="mars-lft-5">@
                                        <?php echo $username; ?>
                                    </h6>
                                    <?php
                    if(isset($web)){
                        echo '<p><a class=" mars-lft-5" href="'.$web.'" target="_blank">'.$web.'</a></p>';
                    }
                          ?>
                                </div>
                            </div>
                            <?php if($bio!=""){
                     echo '<div class="row shadow" id="mb-view-2">
                       <p>'.$bio.'</p>
                  </div>';
                           } ?>
                        </div>
                    </div>
                    <div class="col-md-4 d-none d-md-block mars-btm-20 text-center">
                        <div id="exp"> 
                        <a href=<?php echo "profile.php?user=$username" ?>>
                    <img src=<?php if($profilepic!='' ) { echo "'$profilepic'"; } else{ echo "'img/profile_pic/default.png'"; } ?> class="img-responsive home-pic">
                    <h3 class="mars-top-10" style="margin-bottom:0px;"><?php echo $name; ?></h3>
                    <h6>@<?php echo $username; ?></h6>
                    <p style="margin-bottom:5px; padding:10px;" class="container"><?php if(isset($bio)){echo $bio;} ?></p>
                    <?php
                    if(isset($web)){
                        echo '<p><a href="'.$web.'" target="_blank">'.$web.'</a></p>'; } ?>
                            </a>
                        </div>
                        <div class="row" style="margin-top:30px; ">
                            <div class="col-7">
                                <h5 class="text-left">Other Users</h5>
                            </div>
                            <div class="col-5 linked text-right">
                                <a href="users.php" style="color:blue">See All</a>
                            </div>
                        </div>
                        <div id="other-user" class="container">

                            <?php
                            while($row=$allusers->fetch_assoc()){
                                
                                $url = $row['Profile'];
                                if($url!=''){
                                    $url = $row['Profile']; 
                                }
                                else{
                                    $url = "img/profile_pic/default.png";
                                }
                                echo 
                                    '<a href="profile.php?user='.$row["Username"].'"><div class="row text-left mars-btm-20" id="other-lines">
                                        <div class="col-3">
                                            <img src="'.$url.'" class="rounded"  alt="">
                                        </div>
                                        <div class="col-9">
                                            <h6 style="margin-bottom:0px; font-size:1.1em;">'.$row["Name"].'</h6>
                                            <p>@'.$row["Username"].'</p>
                                        </div>
                                    </div></a>';
                            }
                       ?>
                        </div>
                    </div>
                    <div class="col-md-8 col-sm-12 mars-btm-20 text-md-left text-center text-lg-center">
                        <form action="home.php" method="post" name="postForm">
                            <textarea id="home-textarea" class="form-control" type="text" placeholder="Share your thoughts" name="posted" style="resize:none; height:120px; box-shadow: -1px 1px 10px 0.1px rgba(0, 0, 0, 0.35);" required></textarea>
                            <button type="submit" name="update" class="btn btn-block btn-primary mars-top-10" style="box-shadow: -1px 1px 10px 0.1px rgba(0, 0, 0, 0.35);">POST</button>
                        </form>
                        <div id="posts" class="mars-top-30">

                        </div>
                    </div>
                </div>
            </article>
        </section>

        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js"></script>
        <script defer src="https://use.fontawesome.com/releases/v5.0.4/js/all.js"></script>
        <script src="js/posts.js"></script>
    </body>

    </html>
