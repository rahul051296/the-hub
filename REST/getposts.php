<?php

    $user = $_REQUEST['user'];

 include '../dbconnect.php';

$sql = "SELECT (SELECT likes.Liked FROM likes WHERE likes.postId=posts.id AND likes.Username='$user') as Liked,(SELECT COUNT(Liked) FROM likes WHERE likes.postId = posts.Id AND likes.Liked=1) as LikeCount, (SELECT COUNT(COMMENT) FROM comments WHERE postId = posts.id) as CommentCount, posts.Id, posts.Username, posts.Name, posts.Post, posts.Time, pictures.profile FROM pictures, posts,followers WHERE pictures.Username = posts.Username AND followers.Username ='$user' AND posts.Username = followers.Follower ORDER BY Id";
$result = $dbcon->query($sql);
$data = array();
$k =0;
while($row = $result->fetch_assoc()){
    $k++;
	$data[$k] = $row; 
}
    $data['length'] = $k;
    $data['user'] = $user;

header('Content-Type:application/json');
echo json_encode($data);
?>
