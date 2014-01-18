<?php
include "cn.php";
include "funcoes.php";

if ( isset($_POST['produto']) ) {

	$sql="update tab_produto set ativo=not ativo where id_produto=" . $_POST['produto'];

	$rs=$cn->query($sql);
}
?>
