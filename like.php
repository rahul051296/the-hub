
<?php
//alter table likes add unique index(postId, Username);

$postId = $_REQUEST["postId"];
$user = $_REQUEST["username"];
$liked = $_REQUEST["liked"];
include 'dbconnect.php';
if($liked==1){
mysqli_query($dbcon,"INSERT INTO `likes`(`postId`, `Username`, `Liked`) VALUES ('$postId','$user','$liked')");
mysqli_query($dbcon,"UPDATE `likes` SET `Liked` = '1' WHERE `likes`.`postId` = '$postId' AND `likes`.`Username` = '$user';");
}
if($liked==2){
    mysqli_query($dbcon,"UPDATE `likes` SET `Liked` = '0' WHERE `likes`.`postId` = '$postId' AND `likes`.`Username` = '$user';");
}
?>