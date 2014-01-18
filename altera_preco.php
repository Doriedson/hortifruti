<?php
$acesso=5;
include "check_session.php";

if( isset( $_POST['gravar'] ) ) {

	$sql="select id_produto, venda from tab_tmppreco where venda>0";
	if( $rs=$cn->query($sql) ) {

		$sql="insert into tab_logpreco (id_produto, id_entidade, data, oldpreco, newpreco) select p.id_produto," . $_SESSION['id'] . ",now(),p.preco,t.venda from tab_tmppreco t inner join tab_produto p on p.id_produto=t.id_produto where t.venda>0";
		$cn->query($sql);

		$sql="insert into tab_print (id_produto) select id_produto from tab_tmppreco where venda>0";
		$cn->query($sql);
		
		while( $row=$rs->fetch_assoc() ) {
		
			$sql="update tab_produto set preco=" . str_replace(",",".",$row['venda']) . " where id_produto=" . $row['id_produto'];
			$cn->query($sql);
		}
		
		$sql="delete from tab_tmppreco";
		$cn->query($sql);
	}
}

if( isset( $_POST['id'] ) ) {

	if( is_numeric($_POST['preco']) ) {
	
		$sql="update tab_tmppreco set venda=" . str_replace(",",".",$_POST['preco']) . " where id_tmppreco=" . $_POST['id'];
		$cn->query($sql);
		echo number_format($_POST['preco'],2,",",".");
	}

	$cn->close();
	return;
}

echo "<h1>:: Alteração de Preço</h1>";

$sql="select * from tab_tmppreco p inner join tab_produto on tab_produto.id_produto=p.id_produto";
	
if ( $rs=$cn->query($sql) ) {
	if ($row=$rs->fetch_assoc()) {
			
		echo "<table><tr><th>Produto</th><th>Custo</th><th>Tipo</th><th>Preço</th><th>%</th><th>Alteração</th><th>%</th><th>30%</th><th>40%</th><th>60%</th></tr>";
		
		do {
			echo "<tr><td>" . $row['produto'] . "</td>";
			echo "<td>R$ <label id='c" . $row['id_tmppreco'] . "'>" . number_format($row['custo'],2,',','.') . "</label></td>";
			echo "<td>" . $row['tipo'] . "</td>";
			echo "<td>R$ " . number_format($row['preco'],2,',','.') . "</td>";
			echo "<td>" . number_format($row['preco'] / $row['custo'] * 100 - 100,0) . "%</td>";
			echo "<td><input type='text' id='preco' size='6' maxlength='6' pattern='\d+(,\d{0,2})?' onchange='AlteraPreco(this," . $row['id_tmppreco'] . ");' style='text-align:center;' placeholder='" . number_format($row['venda'],2,',','.') . "' /></td>";
			echo "<td><label id=p" . $row['id_tmppreco'] . ">" . number_format($row['venda'] / $row['custo'] * 100 - 100,0) . "</label>%</td>";
			echo "<td>R$ " . number_format($row['custo'] * 1.3,2,",",".") . "</td>";
			echo "<td>R$ " . number_format($row['custo'] * 1.4,2,",",".") . "</td>";
			echo "<td>R$ " . number_format($row['custo'] * 1.6,2,",",".") . "</td>";
			echo "</tr>";

		} while ($row=$rs->fetch_assoc());
			
		echo "</table><br /><input type='button' value='Gravar' onclick='GravarPreco()' /> <label class='erro' style='display:inline'>(Produtos com Alteração para R$ 0,00 serão desconsiderados.)</label>";
		
	} else {
		echo "Não há itens para alterar preço.";
	}
}
$cn->close();
?>
