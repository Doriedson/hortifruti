<?php
$cn = new mysqli("localhost", "root", "8511965");

if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());   
    exit();
}

if (!$cn->set_charset("utf8")) {
   printf("Error loading character set utf8: %s\n", $cn->error);
}

if (!$cn->select_db("hortifruti")){
	echo "Banco de Dados não instalado!";
	exit();
	//include "install.php";
}

?>
