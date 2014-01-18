<?php

if (isset( $_POST['datai'] ) ) {

	include "cn.php";
	include "funcoes.php";

	echo "<p>De " . date_format( date_create($_POST['datai']." 00:00:00"), 'd/m/Y H:i:s') . " à " . date_format( date_create($_POST['dataf']." 23:59:59"), 'd/m/Y H:i:s') . "</p>";
	
	$sql="select p.produto, sum(i.qtd) as qtd, p.tipo, sum(i.qtd*i.preco) as valor, s.setor from tab_venda v inner join tab_vendaitem i on v.id_venda=i.id_venda inner join tab_produto p on p.id_produto=i.id_produto inner join tab_setor s on s.id_setor=p.id_setor where v.data between '" . $_POST['datai'] . " 00:00:00' and '" . $_POST['dataf'] . " 23:59:59' group by p.produto order by s.setor, qtd desc;";

	if ($rs=$cn->query($sql)) {
		if( $row=$rs->fetch_assoc() ) {
			$setor="";
			$total=0;
			
			do {
				if( $setor!=$row['setor'] ) {
					if( $setor!="") {
						echo "<tfoot><tr><td align='right' colspan='4'>R$ " . number_format($subtotal,2,",",".") . "</td></tr></tfoot>";
						echo "</table>";
					}
					$setor=$row['setor'];
					echo "<h1>$setor</h1>" ;
					echo "<table><tr><th width='300' align='left'>Produto</th><th width='50'>Qtd</th><th width='30'>Tipo</th><th width='80'>Preço Médio</th></tr>";
					$total+=$subtotal;
					$subtotal=0;
				}
				
				$subtotal += $row['valor'];
				echo "<tr><td>" . $row['produto'] . "</td><td align='center'>" . number_format($row['qtd'],3,",",".") . "</td><td align='center'>" . $row['tipo'] . "</td><td align='right'>R$ " . number_format($row['valor'],2,",",".") . "</td></tr>";
			} while( $row=$rs->fetch_assoc() );
			
			echo "<tfoot><tr><td align='right' colspan='4'>R$ " . number_format($subtotal,2,",",".") . "</td></tr></tfoot>";
			echo "</table>";
			
			echo "<br /><table width='500'><tfoot><tr><td width='370' align='right'>Total</td><td align='right'>R$ " . number_format($total,2,",",".") . "</td></tr></tfoot></table>";
			
			$cn->close();
			return;

		} else {
			echo "<!--erro-->Nenhum relatório encontrado no intervalo!";
			$cn->close();
			return;
		}
	}

	$cn->close();
}
?>

<h1>:: Relatório de Produtos Vendidos por Período</h1>
<form method="post" onsubmit="return RelVendaProduto();">
	de <input type="date" id="datai" size="10" value="<? echo date('Y-m-d'); ?>" required autofocus />
	até <input type="date" id="dataf" size="10" value="<? echo date('Y-m-d'); ?>" required />
	<input type="submit" value="Exibir" />
	<p><label class="erro" id="relerro"></label></p>
</form>

<div id="relatorio"></div>
