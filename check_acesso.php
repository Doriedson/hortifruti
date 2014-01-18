<?php

switch ($acesso) {

	case 1: //Consulta de Produto
		if( $_SESSION['buscprod']<>1 ) {
			echo "Permissão Negada!";
			exit;
		}
		break;

	case 2: //Cadastro de Cliente
		if( $_SESSION['cadcli']<>1 ) {
			echo "Permissão Negada!";
			exit;
		}
		break;

	case 3: //Edição de Cliente
		if( $_SESSION['editcli']<>1 ) {
			echo "Permissão Negada!";
			exit;
		}
		break;

	case 4: //Ver Cliente
		if( $_SESSION['showcli']<>1 ) {
			echo "Permissão Negada!";
			exit;
		}
		break;

	case 5: //Alteração de Preço
		if( $_SESSION['editpreco']<>1 ) {
			echo "Permissão Negada!";
			exit;
		}
		break;

}

?>
