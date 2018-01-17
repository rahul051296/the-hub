<?php
 include 'dbconnect.php';

$sql = "SELECT * FROM posts;";

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