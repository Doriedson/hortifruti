<?php
function Clean($dirty){
	global $cn;
	return $cn->real_escape_string($dirty);
}

function HtmlChar($str){
	return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}

function converter_data_sql($strData) {
	// Recebemos a data no formato: dd/mm/aaaa
	// Convertemos a data para o formato: aaaa-mm-dd
	if ( preg_match("#/#",$strData) == 1 ) {		
		$strDataFinal = implode('-', array_reverse(explode('/',$strData)));		
	}
	return $strDataFinal;
}

function converter_data_br($strData) {
	// Recebemos a data no formato: aaaa-mm-dd
	// Convertemos a data para o formato: dd/mm/aaaa
	if ( preg_match("#-#",$strData) == 1 ) {		
		$strDataFinal = implode('/', array_reverse(explode('-',$strData)));		
	}
	return $strDataFinal;
}

function erro($msg){
	global $cn;
	echo $msg;
	$cn->close();
	exit;
}
?>
