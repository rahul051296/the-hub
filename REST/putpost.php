<?php
    include '../dbconnect.php';
    $username = $_REQUEST['username'];
    $name = $_REQUEST['name'];
    if(!$dbcon){
         die('Error Connecting to database');
    }

if(isset($_POST['update'])){
    $posted = mysqli_real_escape_string($dbcon, $_POST['posted']);
    $insert="INSERT INTO posts (Username,Name,Post,Time) values('$username','$name','$posted','".date('U')."')";
    $result=$dbcon->query($insert);
    header('Location: home.php');
}

?>