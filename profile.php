<?php
        session_start();
        if( !isset($_SESSION['username']) ) {
                header("Location: login.php");
                exit;
         }
        include 'dbconnect.php';
        $username = $_SESSION['username'];
        $name = $_SESSION["name"];
        $profilename = $_REQUEST["user"];

        if(!$dbcon){
             die('Error Connecting to database');
        }
        $rowi = "";
        $profiledata = mysqli_query($dbcon,"SELECT users.Name,users.Username,users.Email,users.Bio,users.Website,pictures.Profile,pictures.Cover FROM `users`, `pictures` WHERE users.Username = '$profilename' AND pictures.Username = '$profilename'");

        $data = mysqli_fetch_array($profiledata);
    
        $pname = $data['Name'];
        $pusername = $data['Username'];
        $pprofilepic = $data['Profile'];
        $pcoverpic = $data['Cover'];
        $pbio = $data['Bio'];
        $pweb = $data['Website'];

        $followdata = mysqli_query($dbcon,"SELECT COUNT(follower) as Following FROM followers WHERE Username = '$username' AND Follower = '$pusername'");
        $following = mysqli_fetch_array($followdata);
        $fcheck = $following["Following"];

        $posts = "SELECT (SELECT likes.Liked FROM likes WHERE likes.postId=posts.id AND likes.Username='$username') as Liked,(SELECT COUNT(comments.Comment) FROM comments WHERE comments.PostId = posts.Id) as CommentCount,(SELECT COUNT(likes.Liked) FROM likes WHERE likes.Liked = 1 AND posts.Id = likes.postId) as LikeCount, posts.* FROM `posts` WHERE posts.Username = '$profilename' ORDER BY Id DESC";
        $allposts=$dbcon->query($posts);
        $pcount = mysqli_num_rows($allposts);

        $friends = "SELECT followers.Follower, pictures.Profile, users.Name, users.Username FROM `followers`,pictures,users WHERE followers.Username = '$profilename' AND followers.Username <> followers.Follower AND pictures.Username = followers.Follower AND followers.Follower = users.Username LIMIT 5";
        $friendlist = $dbcon->query($friends);
        $counter = mysqli_num_rows($friendlist);

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
        <link rel="stylesheet" href="css/animate.css">
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
                    <ul class="navbar-nav">
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
                    <ul class="navbar-nav ml-auto">

                        <li class="nav-item">
                            <a class="nav-link" href="home.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="interests.php">Interests</a>
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
                    </ul>
                </div>
            </div>
        </nav>
        <article id="cover">
            <div class="container wow animated fadeIn">
                <div id="profile-head" class="">
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
                        <?php 
                    if($fcheck!=1){
                        $colors = '
                        <button id="follow-btn" class="btn btn-outline-primary" name="follow">
                        <span style="font-size:1.1em;">Follow </span> <i style="font-size:0.8em;" class="fas fa-1x fa-plus-circle"></i>
                        </button>';
                    }
                    else if($fcheck==1){
                        $colors = '
                        <button id="unfollow-btn" class="btn btn-primary" name="follow">
                        <span style="font-size:1.1em;">Following </span> <i style="font-size:0.8em;" class="fas fa-1x fa-check-circle"></i>
                        </button>';
                    }
                        if($username == $pusername){
                            $followbtn = "";
                        }
                    else{
                        $followbtn = '
                        <div class="mars-top-30">
                        '.$colors.'
                         </div>';  
                    }
                    echo $followbtn;
                         ?>

                        <script>
                            if (document.getElementById('follow-btn') != null) {
                                let follow = document.getElementById('follow-btn');
                                follow.addEventListener("click", function() {


                                    let u = "<?php echo $username; ?>";
                                    let p = "<?php echo $pusername; ?>";
                                    fetch(`follow.php?username=${u}&follow=${p}&b=1`, {
                                        method: 'GET'
                                    });
                                    follow.className = "btn btn-primary";
                                    follow.innerHTML = '<span style="font-size:1.1em;">Following </span> <i style="font-size:0.8em;" class="fas fa-1x fa-check-circle"></i>';
                                    setTimeout(() => {
                                        location.reload(true);

                                    }, 1000);

                                });
                            }
                            if (document.getElementById('unfollow-btn') != null) {
                                let unfollow = document.getElementById('unfollow-btn');
                                unfollow.addEventListener("click", function() {

                                    let u = "<?php echo $username; ?>";
                                    let p = "<?php echo $pusername; ?>";
                                    fetch(`follow.php?username=${u}&follow=${p}&b=0`, {
                                        method: 'GET'
                                    });
                                    unfollow.className = "btn btn-outline-primary";
                                    unfollow.innerHTML = '<span style="font-size:1.1em;">Follow </span> <i style="font-size:0.8em;" class="fas fa-1x fa-plus-circle"></i>';
                                    setTimeout(() => {
                                        location.reload(true);
                                    }, 1000);

                                });
                            }

                        </script>


                </div>

                <div class="row" style="margin-top:50px; margin-bottom:50px;">
                    <div class="col-md-4 d-none d-md-block">
                        <div class="row">
                            <div class="col-7">
                                <h5 class="text-left">Follows</h5>
                            </div>
                            <div class="col-5 linked text-right">
                                <a href="#" style="color:blue">See All</a>
                            </div>
                        </div>

                        <div id="other-user" class="container wow animated fadeIn">
                            <div id="friends-err" class="text-center">
                                <h5 class="text-muted">No Users Found.</h5>
                            </div>
                            <?php
                            while($row1=$friendlist->fetch_assoc()){
                                
                                $url1 = $row1['Profile'];
                                if($url1!=''){
                                    $url1 = $row1['Profile']; 
                                }
                                else{
                                    $url1 = "img/profile_pic/default.png";
                                }
                                echo 
                                    '<a href="profile.php?user='.$row1["Username"].'"><div class="row text-left mars-btm-20" id="other-lines">
                                        <div class="col-3">
                                            <img src="'.$url1.'" class="rounded"  alt="">
                                        </div>
                                        <div class="col-9">
                                            <h6 style="margin-bottom:0px; font-size:1.1em;">'.$row1["Name"].'</h6>
                                            <p style="font-size:0.8em;">@'.$row1["Username"].'</p>
                                        </div>
                                    </div></a>';
                            }
                       ?>
                        </div>
                    </div>
                    <script>
                        let c = "<?php echo $counter; ?>";
                        if (c != 0) {
                            document.getElementById('friends-err').style.display = "none";
                        }
                    
                    </script>
                    <div class="col-12 col-md-8 wow animated fadeIn" style="margin-top:25px;">
                        <?php
                        $a =0;
                    while($row=$allposts->fetch_assoc()){ 
                        if($pprofilepic!="") {$picurl=$pprofilepic;}
                        else {$picurl = 'img/profile_pic/default.png';}
                        $unix_timestamp = $row["Time"];
                        $a++;
                        $datetime = new DateTime("@$unix_timestamp");
                        $datetime->setTimezone(new DateTimeZone('Asia/Kolkata'));
                        if($username == $row["Username"]){
                            $option = '<span class="dropdown">
                                <a href="#" data-toggle="dropdown"><i style="color: #737373;" class="fas fa-ellipsis-v"></i></a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="editPost.php?postId='.$row["Id"].'">Edit</a>
                                    <a class="dropdown-item" href="deletePost.php?postId='.$row["Id"].'">Delete</a>
                                </div>
                            </span>';
                        }
                        else{
                            $option = '';
                        }
                        
                    echo'
                    
                        <div id="post-box" >
                            <div class="row" id="pro-info">
                            <div class="col-2 col-md-2 col-lg-1 ">
                               <a id="links" href="profile.php?user='.$row["Username"].'" > <img src="'.$picurl.'" class=" rounded-circle " width="50px" id="post-circle" /></a>
                            </div>
                            <div class="col-8 col-md-9 col-lg-10" id="pro-name" style="text-align:left">
                                <a id="links" href="profile.php?user='.$row["Username"].'"> <h5 style="margin-bottom:0;">'.$row["Name"].'</h5></a>
                                <p style="font-size:0.75em;">'.$datetime->format('d M Y').' at '.$datetime->format('H:i').' hrs</p>
                            </div>
                            <div class="text-right" style="" id="drop-circles">
                                '.$option.'
                            </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-11 offset-lg-1 col-md-10 offset-md-2">
                                <p class="text-left text-md-left" id="postdata'.$a.'"></p>
                                </div>
                            </div>
                            <script>
                                pd = `'.$row["Post"].'`
                                var repl = pd.replace(/#(\w+)/g, `<a id="hashtag" href="#">#$1</a>`);
                                var res = repl.replace(/@(\w+)/g, `<a id="hashtag" href="profile.php?user=$1">@$1</a>`);
                                document.getElementById("postdata'.$a.'").innerHTML = res;
                            </script>
                           
                            <div class="row text-center ">
                                <div class="col-6 comm-box" >
                                <input type="checkbox"  onclick="like('.$a.','.$row["Id"].','.$row["Liked"].');" id="like'.$a.'">
                                <label for="like'.$a.'" class="label-color">
                                <i class="fas fa-heart" ></i> Like (<span id="count'.$a.'">'.$row["LikeCount"].'</span>)
                                </label>
                                </div>
                                <div class="col-6 comm-box"><a href="comments.php?postId='.$row["Id"].'">
                                <div><i class="fas fa-comment"></i> Comment ('.$row["CommentCount"].')
                                </div></a>
                                </div>
                            </div>
                        </div>
                            <script>
                                function like(i,id,likes){
                                    let user = "'.$row["Username"].'";
                                    var liked = document.getElementById(`like${i}`).checked;
                                    if(liked==true){
                                        let t = document.getElementById("count"+i).innerText;
                                        let l = parseInt(t)+1;
                                        document.getElementById("count"+i).innerText = l ;
                                        fetch(`like.php?postId=${id}&username=${user}&liked=1`,{method:"GET"});
                                    }
                                    else{
                                        let t = document.getElementById("count"+i).innerText;
                                        let l = parseInt(t)-1;
                                        document.getElementById("count"+i).innerText = l ;
                                        fetch(`like.php?postId=${id}&username=${user}&liked=2`,{method:"GET"});
                                    }
                                }
                                
                                
                            
                            </script>
                            
                             <script>
                             if('.$row["Liked"].' == 1)
                        document.getElementById("like'.$a.'").setAttribute("checked","checked");
                            </script>
                            ';
                    }
                    ?>
                            <div id="error-err" class="text-center" style="margin-top:48px">
                                <h5 class="text-muted">No Posts Found.</h5>
                            </div>
                            <script>
                                let pc = "<?php echo $pcount; ?>";
                                if (pc != 0) {
                                    document.getElementById('error-err').style.display = "none";
                                }

                            </script>
                    </div>
                </div>
            </div>
        </article>
        <?php 
        if($pcoverpic!=""){
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
        <script src="js/wow.js"></script>
        <script>
        new WOW().init();
        </script>
    </body>

    </html>
