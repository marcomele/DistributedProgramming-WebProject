<?php
    session_start();
    //MySql_close();
    session_destroy();
        //or die("Error 4: Unable to log out - please try again reloading this page.");
  //echo"<center><h2>Logged out</h2>";
  //echo"Go to: <a href='intranet.php'>Log in page</a> or <a href='index.html'>Site home page</a>";
  header('location:index.php');
  die();
?>