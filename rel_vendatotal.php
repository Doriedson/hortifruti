<?php

if (isset ($_POST['datai']) ){

	include "cn.php";

	$str= "<p>De " . date_format( date_create($_POST['datai']." 00:00:00"), 'd/m/Y H:i:s') . " à " . date_format( date_create($_POST['dataf']." 23:59:59"), 'd/m/Y H:i:s') . "</p>";

	if ( $_POST['caixa'] ) {

		$sql="select tab_caixa.id_caixa, tab_acesso.usuario, sum(tab_vendapay.valor) as valor, tab_especie.especie from tab_venda inner join tab_vendapay on tab_vendapay.id_venda=tab_venda.id_venda inner join tab_especie on tab_especie.id_especie=tab_vendapay.id_especie inner join tab_caixa on tab_caixa.id_caixa=tab_venda.id_caixa inner join tab_acesso on tab_acesso.id_entidade=tab_caixa.id_entidade where tab_caixa.dataini between '" . $_POST['datai'] . " 00:00:00' and '" . $_POST['dataf'] . " 23:59:59' and tab_venda.id_status in (1,3) group by tab_especie.especie, tab_caixa.id_caixa order by tab_caixa.id_caixa, tab_especie.especie";

		if ($rs=$cn->query($sql)) {
			if( $row=$rs->fetch_assoc() ) {

				$id_caixa=$row['id_caixa'];
				$total=0;
				//if ( $row['especie']!='Desconto' ){
					$total+=$row['valor'];
				//}
		
				$str.= "<div class='divrelatorio'><table><thead><tr><th colspan='2' align='left'>" . $row['usuario'] . "</th></tr></thead>";			
				$str.= "<tr><td></td><td>" . $row['especie'] . "</td><td align='right'>R$ " . number_format($row['valor'],2,",",".") . "</td></tr>";

				while( $row=$rs->fetch_assoc() ) {
			
					if( $id_caixa!=$row['id_caixa'] ) {
						$id_caixa=$row['id_caixa'];

						$str.= "<tfoot><td colspan='2' align='right'>Total</td><td align='right'>R$ " . number_format($total,2,",",".") . "</td></tfoot></table>";
						$str.= "</div><div class='divrelatorio'><table><thead><tr><th colspan='2' align='left'>" . $row['usuario'] . "</th></tr></thead>";			

						$total=0;
					}

					$total += $row['valor'];

					$str.= "<tr><td></td><td>" . $row['especie'] . "</td><td align='right'>R$ " . number_format($row['valor'],2,",",".") . "</td></tr>";

				}

				$str.= "<tfoot><tr><td colspan='2' align='right'>Total</td><td align='right'>R$ " . number_format($total,2,",",".") . "</td></tr></tfoot></table></div>";

			}

		}

	}

	$sql="select sum(tab_vendapay.valor) as valor, tab_especie.especie from tab_venda inner join tab_vendapay on tab_vendapay.id_venda=tab_venda.id_venda inner join tab_especie on tab_especie.id_especie=tab_vendapay.id_especie inner join tab_caixa on tab_caixa.id_caixa=tab_venda.id_caixa where tab_caixa.dataini between '" . $_POST['datai'] . " 00:00:00' and '" . $_POST['dataf'] . " 23:59:59' and tab_venda.id_status in (1,3) group by tab_especie.especie order by tab_especie.especie";

	if ($rs=$cn->query($sql)) {
		if( $row=$rs->fetch_assoc() ) {

			$total=0;
			if ($row['especie']!='Desconto'){
				$total+=$row['valor'];
			}

			$str.= "<div class='divrelatorio'><table><thead><tr><th colspan=2 align='left'>TOTAL GERAL</th></tr></thead>";			
			$str.= "<tr><td></td><td>" . $row['especie'] . "</td><td align='right'>R$ " . number_format($row['valor'],2,",",".") . "</td></tr>";

			while( $row=$rs->fetch_assoc() ) {
		
				$total += $row['valor'];
				$str.= "<tr><td></td><td>" . $row['especie'] . "</td><td align='right'>R$ " . number_format($row['valor'],2,",",".") . "</td></tr>";

			}

			$str.= "<tfoot><tr><td colspan='2' align='right'>Total</td><td align='right'>R$ " . number_format($total,2,",",".") . "</td></tr></tfoot></table></div>";
		} else {
			echo "<!--erro-->Nenhum relatório encontrado no intervalo!";
			$cn->close();
			return;
		}
	}


	$cn->close();
	echo $str;
	return;
}
?>

<h1>:: Relatório de Total de Vendas por Período</h1>
<form method="post" onsubmit="return RelVendaTotal();">
	de <input type="date" id="datai" size="10" value="<? echo date('Y-m-d'); ?>" required autofocus />
	até <input type="date" id="dataf" size="10" value="<? echo date('Y-m-d'); ?>" required />
	<input type="submit" value="Exibir" />
	<input type="checkbox" value=1 id="caixa" /> por Caixa
	<p><label class="erro" id="relerro"></label></p>
</form>

<div id="relatorio"></div>
