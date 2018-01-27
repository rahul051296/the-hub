<?php
 
    include '../dbconnect.php';
    $username = $_REQUEST['user'];
    if(!$dbcon){
         die('Error Connecting to database');
    }

    $sql = "SELECT DISTINCT users.Name,users.Username, (SELECT COUNT(followers.Follower) FROM followers WHERE users.Username = followers.Follower AND followers.Username = '$username') as FollowTest, (SELECT pictures.Profile from pictures where users.Username = pictures.Username) as Profile FROM users,followers WHERE followers.Username='$username'";
    $result=$dbcon->query($sql);
 
       $data = array();
	   while($row = mysqli_fetch_assoc($result)){
		  $data[] = $row;
	   }
        header('Content-Type:application/json');
        print json_encode($data);


?>