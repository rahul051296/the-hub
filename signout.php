<?php
     session_start();
 if (!isset($_SESSION['username'])) { 
  header("Location: login.php");
 }
  unset($_SESSION['username']); 
  unset($_SESSION['name']); 
  session_unset();
  session_destroy();
  header("Location: index.php");
  exit;
  
?>