<?php
include "cn.php";
include "funcoes.php";

if( !isset($_SESSION) ) {
	session_start();
}

if ( isset($_POST['preco']) ) {

	$sql="select * from tab_produto where id_produto=" . $_POST['produto'];

	if ($rs=$cn->query($sql)) {

		$row=$rs->fetch_assoc();

		$preco = str_replace( ",",".",$_POST['preco']);

		$sql="insert into tab_logpreco (data,id_entidade,oldpreco,id_produto) values (now()," . $_SESSION['id'] . ",$preco," . $_POST['produto'] . ")";

		$rs=$cn->query($sql);

	    $sql="update tab_produto set preco=$preco, ativo=1 where id_produto=" . $_POST['produto'];

		$rs=$cn->query($sql);

	    $sql="insert into tab_print (id_produto) values (" . $_POST['produto'] . ")";

		$rs=$cn->query($sql);

		echo "Alteração de Preço: " . $row['id_produto'] . " - " . $row['produto'] . " R$ " . number_format($row['preco'],2,',','.') . " -> R$ " . number_format($preco,2,',','.') . " (" . date('d/m/Y H:i:s') . ")";

	}

} elseif ( isset($_POST['produto']) ) {

	$produto = $_POST['produto'] ;

	$sql="select * from tab_produto where id_produto=$produto";

	if ($rs=$cn->query($sql)) {
		while( $row=$rs->fetch_assoc() ) {
			?>
			<form method="post" onsubmit="return Preco();">
			<?php echo $row['id_produto']; ?> - <?php echo $row['produto']; ?><br />
			<input type="hidden" id="produto" value="<?php echo $row['id_produto']; ?>" />
			R$ <input type="text" style="text-align:right;" id="preco" size="6" maxlength="6" pattern="\d+(,\d{0,2})?" autofocus required placeholder="<?php echo number_format($row['preco'],2,',','.') ?>" /> <?php echo $row['tipo']; ?> <input type="submit" align="center" value="Alterar" /><br />
			</form>
			<?php
			
		}
	}

}
?>
