<?php
session_start();
$name = $_SESSION["name"];  
$username = $_SESSION["username"];
$postId = $_REQUEST["postId"];

include 'dbconnect.php';
if(!$dbcon){
         die('Error Connecting to database');
    }
$result = mysqli_query($dbcon,"SELECT * from posts WHERE posts.Id = '$postId'");
$data = mysqli_fetch_array($result);
$posted = $data["Post"];
if(isset($_POST['update'])){
            $p = mysqli_real_escape_string($dbcon, $_POST['posted']);
            $update="UPDATE posts SET posts.Post='$p' WHERE posts.Id='$postId'";
            mysqli_query($dbcon,$update);
            header('Location: profile.php?user='.$username);
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
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/bot.css">
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
        <section id="edit">
            <div class="col-md-8 offset-md-2 col-sm-12 mars-btm-20 text-center text-lg-center">
                <form action="editPost.php?postId=<?php echo $postId; ?>" method="post" name="postForm">
                    <textarea id="home-textarea" class="form-control" type="text" placeholder="Share your thoughts" name="posted" style="resize:none; height:120px; box-shadow: -1px 1px 10px 0.1px rgba(0, 0, 0, 0.35);" required></textarea>
                    <button type="submit" name="update" class="btn btn-block btn-primary mars-top-10" style="box-shadow: -1px 1px 10px 0.1px rgba(0, 0, 0, 0.35);">UPDATE</button>
                </form>
            </div>
            <div id="chat-open">
                <div class="col-12" id="main-box">
                    <header class="header">
                        <h5 class="text-left">Hub Bot (AI Chatbot)</h5>
                        <h6><span><i class="fas fa-circle"></i></span> Online</h6>
                        <span class="closer" onclick="closechat()"><i class="fas fa-times"></i></span>
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
                <div id="fab" class="shadow" onclick="openchat()"><i class="fas fa-1x fa-envelope-open"></i></div>
            </div>
        </section>
        <script>
            document.getElementById('home-textarea').value = `<?php echo $posted; ?>`

        </script>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js"></script>
        <script defer src="https://use.fontawesome.com/releases/v5.0.4/js/all.js"></script>
        <script src="js/chat.js"></script>
        <script src="js/bot.js"></script>
    </body>

    </html>
