<?php

if (isset( $_POST['datai'] ) ) {

	include "cn.php";
	include "funcoes.php";

	echo "<p>De " . date_format( date_create($_POST['datai']." 00:00:00"), 'd/m/Y H:i:s') . " à " . date_format( date_create($_POST['dataf']." 23:59:59"), 'd/m/Y H:i:s') . "</p>";

	$sql="select s.setor, sum(e.qtdvol*e.vol) as qtd, p.produto, p.tipo, sum(e.vol*e.custo) as custo from tab_estent e inner join tab_produto p on p.id_produto=e.id_produto inner join tab_setor s on s.id_setor=p.id_setor where e.data between '" . $_POST['datai'] . " 00:00:00' and '" . $_POST['dataf'] . " 23:59:59' group by p.produto, p.tipo, s.setor order by s.setor";
	
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
					echo "<table><tr><th>Produto</th><th>Qtd</th><th>Tipo</th><th>Custo</th></tr>";
					$total+=$subtotal;
					$subtotal=0;
				}
				
				$subtotal += $row['custo'];
				echo "<tr><td>" . $row['produto'] . "</td><td align='center'>" . number_format($row['qtd'],3,",",".") . "</td><td align='center'>" . $row['tipo'] . "</td><td align='right'>R$ " . number_format($row['custo'],2,",",".") . "</td></tr>";
			} while( $row=$rs->fetch_assoc() );
			
			echo "<tfoot><tr><td align='right' colspan='4'>R$ " . number_format($subtotal,2,",",".") . "</td></tr></tfoot>";
			echo "</table>";

			$total+=$subtotal;
			
			echo "<br />Total R$ " . number_format($total,2,",",".") . "";
			
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

<h1>:: Relatório de Custo por Período</h1>
<form method="post" onsubmit="return RelCusto();">
	de <input type="date" id="datai" size="10" value="<? echo date('Y-m-d'); ?>" required autofocus />
	até <input type="date" id="dataf" size="10" value="<? echo date('Y-m-d'); ?>" required />
	<input type="submit" value="Exibir" />
	<p><label class="erro" id="relerro"></label></p>
</form>

<div id="relatorio"></div>
