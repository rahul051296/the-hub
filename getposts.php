<?php
 include 'dbconnect.php';

$sql = "SELECT posts.Id, posts.Username, posts.Name, posts.Post, posts.Time, pictures.profile FROM pictures, posts WHERE pictures.Username = posts.Username ORDER BY Id";
$result = $dbcon->query($sql);
$data = array();
$k =0;
while($row = $result->fetch_assoc()){
    $k++;
	$data[$k] = $row;
    $data['length'] = $k;
}

header('Content-Type:application/json');
echo json_encode($data);
?>