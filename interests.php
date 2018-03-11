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
    if(isset($_POST['add'])){
           $tag = mysqli_real_escape_string($dbcon, $_POST['interests']);
            $insert="INSERT INTO tags (Username,Tag) values('$username','$tag')";
            $result=$dbcon->query($insert);    
            header('Location:interests.php');
    }

    $interests = $dbcon->query("SELECT Id, LOWER(Tag) as Tag FROM `tags` WHERE Username='$username'");

      
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
        <article >
            <div class="col-12 text-center" id="interests-title">
                    <h1 class="title">Interests</h1>
                </div>
                <section class="container">
                <div class="col-md-8 offset-md-2 col-12">
                     <form action="interests.php" method="post" name="postForm">
                            <textarea id="home-textarea" class="form-control" type="text" placeholder="Add your interests" name="interests" style="margin-top:30px; resize:none; height:50px; box-shadow: -1px 1px 10px 0.1px rgba(0, 0, 0, 0.35);" required></textarea>
                            <button type="submit" name="add" class="btn btn-block btn-primary mars-top-10" style="box-shadow: -1px 1px 10px 0.1px rgba(0, 0, 0, 0.35);">ADD</button>
                        </form>
                </div>                    
                </section>
                <section class="container mars-top-30">
                   <div class="col-md-8 offset-md-2 col-12">
                   <div class="row">
                    <?php 
                         while($row=$interests->fetch_assoc()){
                             $tagId = $row['Id'];
                             echo '
                            <div class="col-6 col-md-3 mars-top-10">
                             <span class="tag-box">
                             <span>'.$row['Tag'].'</span>
                             <span class="close-tag"><a href="removeinterest.php?tag='.$tagId.'&user='.$username.'"><i class="fas fa-times"></i></a></span>
                             </span>
                             </div>
                             ';
                         }
                    ?>   
                    </div>
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
