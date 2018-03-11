<?php
    session_start();
    $session_user = $_SESSION['username'];
    $user = $_REQUEST['user'];
    $tagId = $_REQUEST['tag'];
    
    include 'dbconnect.php';
    if($session_user==$user){
    mysqli_query($dbcon,"DELETE FROM `tags` WHERE Id='$tagId'");
    header('Location:interests.php');
    }
    else{
        echo 'Invalid Request';
    }
?>
