<?php
include "cn.php";

$ctr=false;

if (isset ($_POST['codigo']) ){
	$sql="insert tab_print (id_produto) values (" . $_POST['codigo'] . ")";
	$cn->query($sql);
	$ctr=true;
}

if (isset ($_POST['del']) ) {
	$sql="delete from tab_print where id_print=" . $_POST['del'];
	$cn->query($sql);
	$ctr=true;
}

$sql="select * from tab_print inner join tab_produto on tab_produto.id_produto=tab_print.id_produto";

if ($rs=$cn->query($sql)) {

	$str = "<table><tr><th>Produto</th><th>Preço</th><th></th></tr>";			

	while( $row=$rs->fetch_assoc() ) {

		$str = "$str<tr><td>" . $row['produto'] . "</td>";
		$str = "$str<td>R$ " . number_format($row['preco'],2,",",".") . "</td>";
		$str = "$str<td><input type='button' value='x' title='Excluir' onclick='DelEtiqueta(" . $row['id_print'] . ")' /></td></tr>";
	}

	$str = "$str</table>";

} else {
	echo "erro";
	$cn->close();
	exit;
}

$cn->close();

if( $ctr ) {
	echo $str;
	exit;
}

?>
<h1>:: Impressão de Etiquetas</h1>
<form method="post" onsubmit="return PrintEtiqueta();">
	<input type="text" id="codigo" pattern="[0-9]+" size="13" maxlength="13" required autofocus />
	<input type="submit" value="Adicionar" />
	<label class="erro" id="relerro"></label>
</form>
<br />
<div id="relatorio"><?php echo $str; ?></div>