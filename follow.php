<?php


$username = $_REQUEST["username"];
$follow = $_REQUEST["follow"];
$b = $_REQUEST["b"];

include 'dbconnect.php';

if($b==1){
$insert = "INSERT INTO followers (Username,Follower) VALUES ('$username','$follow')";
$dbcon->query($insert);
}
else{
    $delete = "DELETE FROM `followers` WHERE `followers`.`Username` = '$username' AND `followers`.`Follower` = '$follow'";
    $dbcon->query($delete);
}

?>
