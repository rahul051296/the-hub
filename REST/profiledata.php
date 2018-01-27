<?php
     include '../dbconnect.php';
    $profilename = $_REQUEST["user"];
    
    if(!$dbcon){
         die('Error Connecting to database');
    }
        $result = mysqli_query($dbcon,"SELECT users.Name,users.Username,users.Email,users.Bio,users.Website,pictures.Profile,pictures.Cover FROM `users`, `pictures` WHERE users.Username = '$profilename' AND pictures.Username = '$profilename'");

        $data = array();
	       while($row = mysqli_fetch_assoc($result)){
		  $data[] = $row;
	   }
        header('Content-Type:application/json');
        print json_encode($data);

?>
