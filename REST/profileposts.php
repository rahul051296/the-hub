<?php
   include '../dbconnect.php';
    $username = $_REQUEST["username"];
    $profilename = $_REQUEST["user"];
    
    if(!$dbcon){
         die('Error Connecting to database');
    }
        
        $followquery = mysqli_query($dbcon,"SELECT COUNT(follower) as Following FROM followers WHERE Username = '$username' AND Follower = '$profilename'");
        $following = mysqli_fetch_array($followquery);
        $fcheck = $following["Following"];

            $posts = "SELECT (SELECT likes.Liked FROM likes WHERE likes.postId=posts.id AND likes.Username='$username') as Liked,(SELECT COUNT(comments.Comment) FROM comments WHERE comments.PostId = posts.Id) as CommentCount,(SELECT COUNT(likes.Liked) FROM likes WHERE likes.Liked = 1 AND posts.Id = likes.postId) as LikeCount, posts.* FROM `posts` WHERE posts.Username = '$profilename' ORDER BY Id DESC";
            $allposts=$dbcon->query($posts);
            $result = array();
        while($srow=$allposts->fetch_assoc()){ 
            $result[] = $srow;
        }
        $result["Following"] = $fcheck;
        header('Content-Type:application/json');
        print json_encode($result);

?>
