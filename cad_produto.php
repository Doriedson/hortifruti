<?php
include "cn.php";


if( isset( $_POST['produto'] ) ) {

	$sub_produto=$_POST['sub_produto'];
	$sub_qtd=str_replace(",",".",$_POST['sub_qtd']);
	
	if( is_numeric($sub_produto) ) {
		if (!is_numeric($sub_qtd)) {
			echo "<!--erro-->Qtd de SubProduto não deve ser zero!";
			$cn->close();
			return;
		} else {
			$sql="select * from tab_produto where id_produto=$sub_produto and sub_produto=0";
			$rs=$cn->query($sql);
			if ( !($row=$rs->fetch_assoc()) ){
				echo "<!--erro-->Não é possível cadastrar um SubProduto de outro SubProduto!";
				$cn->close();
				return;
			} 
		}
	} else {
		$sub_produto=0;
		$sub_qtd=0;
	}
	
	$id_setor=$_POST['id_setor'];
	$produto=$_POST['produto'];
	$tipo=$_POST['tipo'];
	$preco=str_replace(",",".",$_POST['preco']);
	$imagem="none";
	if( $_POST['ativo'] ) {
		$ativo=1;
	} else {
		$ativo=0;
	}

	$sql="insert into tab_produto (id_setor, produto, tipo, preco, imagem, ativo, sub_produto, sub_qtd) values ($id_setor,'$produto','$tipo',$preco,'$imagem',$ativo,$sub_produto,$sub_qtd)";
	$cn->query($sql);
	$sql="select LAST_INSERT_ID() as id";
	$rs=$cn->query($sql);
	$row=$rs->fetch_assoc();
	
	echo $row['id'];
	$cn->close();
	return;

}
?>
<h1>:: Cadastro de Produto</h1>

<form method="post" onsubmit="return NovoProduto();">
	<input type="text" id="produto" pattern="[A-Z a-z0-9]+" required placeholder="Descrição" size="50" maxlength="50" /><input type="checkbox" id="ativo" checked />Ativo<br /><br />
	Setor: <select id="id_setor">
		<?php
		$sql="select * from tab_setor order by setor";
		
		if( $rs=$cn->query($sql) ) {
			while( $row=$rs->fetch_assoc() ) {
				echo "<option value='" . $row['id_setor'] . "'>" . $row['setor'] . "</option>";
			}
		}
		?>
	</select>
	Tipo: <select id="tipo">
		<option value="KG">KG</option>
		<option value="UN">UN</option>
	</select> R$ <input type="text" id="preco" style="text-align:center" required pattern="\d+(,\d{0,2})?" placeholder="Preço" size="6" maxlength="6" /><br />
	<h1>SubProduto</h1>
	Código: <input type="text" id="sub_produto" onchange='ShowSubProduto();' pattern="\d+" maxlength="13" size="13" />
	Qtd: <input type="text" style="text-align:center;" id="sub_qtd" size="7" maxlength="7" pattern="\d+(,\d{0,3})?" placeholder="<?php //echo number_format($row['preco'],2,',','.') ?>" />
	<input type="submit" value="Salvar" /><br /><br />
	<label id="subproduto" /><br /><br />
	<label class="erro" id="produtoerro"></label>
</form>