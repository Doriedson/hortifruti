<?php
include "cn.php";

$sub_produto=$_POST['sub_produto'];

if( is_numeric($sub_produto) ) {

	$sql="select tab_produto.* from tab_produto left join tab_codbar on tab_codbar.id_produto=tab_produto.id_produto where tab_produto.id_produto=" . $_POST['sub_produto'] . " or tab_codbar.codbar=" . $_POST['sub_produto'];

	if( $rs=$cn->query($sql) ) {
		if( $row=$rs->fetch_assoc() ) {
			echo "Produto: " . $row['produto'];
			$cn->close();
			return;
		}
	}
	
} else {
	$cn->close();
	return;
}
$cn->close();
?>
Produto não encontrado!