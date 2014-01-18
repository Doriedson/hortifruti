<?php 

if( !isset($_SESSION) ){
    session_start();
}

session_unset();
session_destroy(); 
//echo "redirect";
//header("Location: index.php");
session_start();
?>
