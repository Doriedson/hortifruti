<?php

if (isset ($_POST['datai']) ){

	include "cn.php";

	$str= "<p>De " . date_format( date_create($_POST['datai']." 00:00:00"), 'd/m/Y H:i:s') . " à " . date_format( date_create($_POST['dataf']." 23:59:59"), 'd/m/Y H:i:s') . "</p>";


	$sql=" select c.dataini, c.id_caixa, a.usuario, sum(p.valor) as total,(c.trocofim-c.trocoini) as troco from tab_venda v inner join tab_vendapay p on p.id_venda=v.id_venda inner join tab_caixa c on c.id_caixa=v.id_caixa inner join tab_acesso a on a.id_entidade=c.id_entidade where c.dataini between '" . $_POST['datai'] . " 00:00:00' and '" . $_POST['dataf'] . " 23:59:59' and p.id_especie<>4 and v.id_status=1 group by c.id_caixa order by a.usuario, c.dataini";

	if ($rs=$cn->query($sql)) {
		if( $row=$rs->fetch_assoc() ) {

			$usuario='';

			do {
		
				if( $usuario!=$row['usuario'] ) {
					if( $usuario!='' ) {
						echo "<tfoot><tr><td colspan='3' align='right'><span class='red'>Faltou R$ " . number_format($quebraP,2,",",".") . "</span></td></tr>";
						echo "<tr><td colspan='3' align='right'><span class='green'>Sobrou R$ " . number_format($quebraN,2,",",".") . "</span></td></tr></tfoot></table>";
					}
					$usuario=$row['usuario'];
					$quebraP=0;
					$quebraN=0;
					echo "<table><thead><tr><th colspan='3' align='left'><h1>$usuario</h1></th></tr><tr><th>Data</th><th>Venda</th><th>Quebra</th></thead></tr>";
				}
			
				$sql="select sum(valor) as sangria from tab_sangria where id_caixa=" . $row['id_caixa'];

				$rs2=$cn->query($sql);
				$row2=$rs2->fetch_assoc();

				$subQuebra = $row['total'] - $row['troco'];
				if( !is_null($row2['sangria']) )
					$subQuebra -= $row2['sangria'];
	
				echo "<tr><td>" . date_format( date_create($row['dataini']), 'd/m/Y') . "</td><td align='right'>R$ " . number_format($row['total'],2,",",".") . "</td><td align='right'>";

				if($subQuebra>0) {
					echo "<span class='red'>R$ " . number_format($subQuebra,2,",",".") . "</span>";
					$quebraP += $subQuebra;
				}else{
					echo "<span class='green'>R$ " . number_format(-$subQuebra,2,",",".") . "</span>";
					$quebraN += -$subQuebra;
				}

				echo "</td></tr>";

			} while( $row=$rs->fetch_assoc() );

			echo "<tfoot><tr><td colspan='3' align='right'><span class='red'>Faltou R$ " . number_format($quebraP,2,",",".") . "</span></td></tr>";
		echo "<tr><td colspan='3' align='right'><span class='green'>Sobrou R$ " . number_format($quebraN,2,",",".") . "</span></td></tr></tfoot></table>";

		} else {
			echo "<!--erro-->Nenhum relatório encontrado no intervalo!";
			$cn->close();
			return;
		}

	}

	$cn->close();
	return;
}
?>

<h1>:: Relatório de Quebra de Caixa por Período</h1>
<form method="post" onsubmit="return RelQuebraCaixa();">
	de <input type="date" id="datai" size="10" value="<? echo date('Y-m-d'); ?>" required autofocus />
	até <input type="date" id="dataf" size="10" value="<? echo date('Y-m-d'); ?>" required />
	<input type="submit" value="Exibir" />
	<p><label class="erro" id="relerro"></label></p>
</form>

<div id="relatorio"></div>
