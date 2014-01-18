<?php
if( !isset($_SESSION) ) {
	session_start();
}

include "cn.php";

$sql="select * from tab_catctspag order by catctspag";

if ( $rs=$cn->query($sql) ) {
	if ($row=$rs->fetch_assoc()) {

		$catdespesa = "<select id='setor'>";

		do {
			$selected = ($setor==$row['id_catctspag'])?"selected":"";
			$catdespesa .= "<option value='" . $row['id_catctspag'] . "' $selected >" . $row['catctspag'] . "</option>";
		} while ($row=$rs->fetch_assoc());

		$catdespesa .= "</select>";
		
	} else {
		$erro .= "Para lançar despesas, cadastre uma categoria em Despesas.";
		//exit();
	}
}

$cn->close();

?>