<?php
    session_start();
    $user = $_SESSION['username'];

 include 'dbconnect.php';



$sql = "SELECT DISTINCT (SELECT likes.Liked FROM likes WHERE likes.postId=posts.id AND likes.Username='$user') as Liked,(SELECT COUNT(Liked) FROM likes WHERE likes.postId = posts.Id AND likes.Liked=1) as LikeCount, (SELECT COUNT(COMMENT) FROM comments WHERE postId = posts.id) as CommentCount, posts.Id, posts.Username, posts.Name, posts.Post, posts.Time, pictures.profile FROM pictures, posts,tags WHERE pictures.Username = posts.Username AND LOWER(posts.Post) LIKE LOWER(CONCAT(CONCAT('%',tags.Tag),'%')) AND tags.Username = '$user' AND posts.Username <> '$user' ORDER BY Id";

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
