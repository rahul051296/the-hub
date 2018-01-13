<?php
     session_start();
 if (!isset($_SESSION['userEmail'])) { 
  header("Location: login.php");
 }
  unset($_SESSION['userEmail']); 
  session_unset();
  session_destroy();
  header("Location: index.php");
  exit;
  
?>