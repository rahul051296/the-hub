<?php
    session_start();
    $username = $_SESSION['username'];
    $postId = $_REQUEST["postId"];
    include 'dbconnect.php';
    if(!$dbcon){
         die('Error Connecting to database');
    }
    mysqli_query($dbcon, "DELETE FROM `posts` WHERE Id='$postId'");
    mysqli_query($dbcon, "DELETE FROM `comments` WHERE PostId='$postId'");
    mysqli_query($dbcon, "DELETE FROM `likes` WHERE postId='$postId'");
    header("Location: profile.php?user=$username");
?>