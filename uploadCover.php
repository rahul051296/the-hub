<?php
session_start();
 ob_start();
header("Access-Control-Allow-Origin: *");
$target_dir = "img/cover_pic/";
$target_file = $target_dir . basename($_FILES["coverToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["upload"])) {
    $check = getimagesize($_FILES["coverToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["coverToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["coverToUpload"]["name"]). " has been uploaded.";
        $_SESSION['cover_url'] = $target_file;
        header('Location: settings.php');
        
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>