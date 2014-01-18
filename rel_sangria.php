<?php

if (isset ($_POST['datai']) ){

	include "cn.php";

	$str= "<p>De " . date_format( date_create($_POST['datai']." 00:00:00"), 'd/m/Y H:i:s') . " à " . date_format( date_create($_POST['dataf']." 23:59:59"), 'd/m/Y H:i:s') . "</p>";


	$sql="select tab_sangria.*, tab_acesso.usuario, tab_especie.especie from tab_sangria inner join tab_acesso on tab_acesso.id_entidade=tab_sangria.id_entidade inner join tab_especie on tab_especie.id_especie=tab_sangria.id_especie where tab_sangria.data between '" . $_POST['datai'] . " 00:00:00' and '" . $_POST['dataf'] . " 23:59:59'";

	if ($rs=$cn->query($sql)) {
		if( $row=$rs->fetch_assoc() ) {

			$str.= "<table><thead><tr><th>Data</th><th>Usuário</th><th>Espécie</th><th>Valor</th><th>Motivo</th></tr></thead>";			
			$str.= "<tr><td>" . date_format( date_create($row['data']), "d/m/Y H:i") . "</td><td>" . $row['usuario'] . "</td><td align='center'>" . $row['especie'] . "</td><td align='right'>R$ " . number_format($row['valor'],2,",",".") . "</td><td>" . $row['obs'] . "</td></tr>";

			while( $row=$rs->fetch_assoc() ) {
		
			$str.= "<tr><td>" . date_format( date_create($row['data']), "d/m/Y H:i") . "</td><td>" . $row['usuario'] . "</td><td align='center'>" . $row['especie'] . "</td><td align='right'>R$ " . number_format($row['valor'],2,",",".") . "</td><td>" . $row['obs'] . "</td></tr>";

			}

			$str.= "</table>";

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

<h1>:: Relatório de Sangrias por Período</h1>
<form method="post" onsubmit="return RelSangria();">
	de <input type="date" id="datai" size="10" value="<? echo date('Y-m-d'); ?>" required autofocus />
	até <input type="date" id="dataf" size="10" value="<? echo date('Y-m-d'); ?>" required />
	<input type="submit" value="Exibir" />
	<p><label class="erro" id="relerro"></label></p>
</form>

<div id="relatorio"></div>
