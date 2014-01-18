<?php
if( !isset($_SESSION) )	session_start();

if (!isset($_SESSION['id']) ) {
    session_destroy();
    header("Location:index.php");
    exit;
}

include "cn.php";
include "funcoes.php";

$sql="select v.id_venda, v.total, v.data, e.nome from tab_venda v inner join tab_entidade e on e.id_entidade=v.id_entidade where v.id_entidade>0 and v.id_caixa=0 order by e.nome";

echo "<h1>:: Pedidos em Aberto</h1>";

if ($rs=$cn->query($sql)) {
	if ( $row=$rs->fetch_assoc() ) {
	
		$cliente=$row['nome'];
		$total=0;

		echo "<h1>$cliente</h1><table><tr><th>Venda</th><th>Data</th><th>Cliente</th><th>Total</th><th>";
		
		do {
			if( $cliente!=$row['nome'] ) {
				echo "<tfoot><tr><td colspan='4' align='right'>R$ " . number_format($total,2,",",".") . "</td></tr></tfoot></table>";
				$total=0;
				$cliente=$row['nome'];
				echo "<h1>$cliente</h1><table><tr><th>Venda</th><th>Data</th><th>Cliente</th><th>Total</th><th>";
			}
			
			$total+=$row['total'];
			echo "<tr><td align='center'>" . $row['id_venda'] . "</td><td>" . date_format( date_create($row['data']), 'd/m/Y H:i:s') . "</td><td>" . $row['nome'] . "</td><td align='right'>R$ " . number_format($row['total'],2,",",".") . "</td></tr>";
		} while( $row=$rs->fetch_assoc() );

		echo "<tfoot><tr><td colspan='4' align='right'>R$ " . number_format($total,2,",",".") . "</td></tr></tfoot></table>";
	}
}

?>
