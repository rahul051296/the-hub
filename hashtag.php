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
    if(isset($_REQUEST["hashtag"])){
        $hashtag = $_REQUEST["hashtag"];
    }


$sql = "SELECT DISTINCT (SELECT likes.Liked FROM likes WHERE likes.postId=posts.id AND likes.Username='$username') as Liked,(SELECT COUNT(Liked) FROM likes WHERE likes.postId = posts.Id AND likes.Liked=1) as LikeCount, (SELECT COUNT(COMMENT) FROM comments WHERE postId = posts.id) as CommentCount, posts.Id, posts.Username, posts.Name, posts.Post, posts.Time, pictures.profile FROM pictures, posts,tags WHERE pictures.Username = posts.Username AND LOWER(posts.Post) LIKE LOWER(CONCAT(CONCAT('%','$hashtag'),'%'))ORDER BY Id";
$result = $dbcon->query($sql);

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="theme-color" content="#243447">
        <title>Discover</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/bot.css">
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
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Interests</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="interests.php">Add Interests</a>
                                <a class="dropdown-item" href="findusers.php">Find Users</a>
                                <a class="dropdown-item" href="discover.php">Discover</a>
                            </div>
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
        <article>
            <div class="col-12 text-center" id="interests-title">
                <h1 class="title"><?php echo ("#".$hashtag); ?></h1>
            </div>
            <section class="container mars-top-30">
                <div class="col-md-8 offset-md-2 col-12">
                    <div>
                        <div id="discover">
                    <?php
                        $a =0;
                    while($row=$result->fetch_assoc()){ 
                        if($row["profile"]!="") {$picurl=$row["profile"];}
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
                                var repl = pd.replace(/#(\w+)/g, `<a id="hashtag" href="hashtag.php?hashtag=$1">#$1</a>`);
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
                    ?></div>
                    </div>
                </div>
                <div id="chat-open">
                    <div class="col-12" id="main-box">
                        <header class="header">
                            <h5 class="text-left">Hub Bot (AI Chatbot)</h5>
                            <h6 id="status"><span><i class="fas fa-circle" style="color:grey"></i></span> Checking...</h6>
                            <span class="closer">
                                <span class="voice-box" title="Listen to the HubBot"><input type="checkbox" onclick="voice();" id="voice" name="voice">
                                <label for="voice" class="label-color-voice">
                                <i class="fas fa-volume-up"></i>
                                </label>
                                </span>
                            </span> 
                        </header>
                        <div id="chat-container">
                            <ul id="conversation">
                            </ul>
                            <div id="bottom"></div>
                        </div>
                        <section class="row" id="input">
                            <div class="col-9 col-md-10" style="padding-right: 0">
                                <input type="text" id="chat-input" placeholder="Enter your message" class="form-control">
                            </div>
                            <div class="col-3 col-md-2" style="padding-left: 5px">
                                <button id="btn" class="btn btn-primary btn-block" onclick="send()"><i class="fas fa-paper-plane"></i></button>
                            </div>
                        </section>
                    </div>
                    <div id="fab" class="shadow" onclick="openchat()"><i class="fas fa-1x fa-envelope"></i></div>
                <div id="fab-close" class="shadow" onclick="closechat()"><i class="fas fa-1x fa-envelope-open"></i></div>
                </div>
            </section>
        </article>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js"></script>
        <script defer src="https://use.fontawesome.com/releases/v5.0.4/js/all.js"></script>
        <!-- <script src="js/hashtag.js"></script> -->
        <script src="js/bot.js"></script>

    </body>

    </html>
