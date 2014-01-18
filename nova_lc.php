<?php
if( !isset($_SESSION) ) {
	session_start();
}

include "cn.php";

if( isset( $_POST['id_lc'] ) ) {

	$sql="update tab_listacompra set descricao='" . $_POST['descricao'] . "' where id_lc=" . $_POST['id_lc'];

	$cn->query($sql);
	
	echo "Alteração de Lista de Compra: " . $_POST['descricao'] . " (" . date('d/m/Y H:i:s') . ")";

	$cn->close();
	return;
}

if (isset ($_POST['nova_lc']) ){

	$sql="insert into tab_listacompra (descricao) values ('" . $_POST['nova_lc'] . "')";

	if ( $rs=$cn->query($sql) ) {
		$sql="SELECT LAST_INSERT_ID() as id";
		if ( $rs=$cn->query($sql) ) {
			$row=$rs->fetch_assoc();
			echo $row['id'];
		}
	}

	$cn->close();
	return;
}

if (isset ($_POST['id']) ){
	$sql="select * from tab_listacompra where id_lc=" . $_POST['id'];

	if ( $rs=$cn->query($sql) ) {
		$row=$rs->fetch_assoc();
	
		?>
		<h1>:: Alteração de Lista de Compra</h1>

		<form method="post" onsubmit="return EditLC();">
			<input type="hidden" id="id_lc" value="<?php echo $row['id_lc']; ?>" />
			<input type="text" id="descricao" value="<?php echo $row['descricao']; ?>" placeholder="Descrição" autofocus required pattern="[A-Z a-z0-9]+" size="50" maxlength="100" /><br />
			<input type="submit" value="Salvar" />
		</form>
		<?php
	}
	$cn->close();
} else {
?>
	<h1>:: Nova Lista de Compra</h1>

	<form method="post" onsubmit="return NovaLC();">
		<input type="text" id="nova_lc" placeholder="Descrição" autofocus required pattern="[A-Z a-z0-9]+" size="50" maxlength="100" /><br />
		<input type="submit" value="Cadastrar" />
	</form>
<?php
}
?>