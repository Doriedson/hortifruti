<?php

require_once("cn.php");

if( !isset($_SESSION) )	session_start();

if( isset($_SESSION['sessao']) ){

	$sql="select * from tab_acesso where sessao='" . $_SESSION['sessao'] . "'";

	if( $rs=$cn->query($sql) ) {
		if( $row=$rs->fetch_assoc() ) {

		} else {
			echo "<script>Logout();</script>";
			exit;
		}
	}

} else {
	echo "<script>Logout();</script>";
	exit;
}

include "check_acesso.php";
?>
