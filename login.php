<?php

include 'cn.php';
include 'funcoes.php';

if( !isset($_SESSION) ){
//	include "logout.php";    
	session_start();
}

if ( isset($_SESSION['sessao']) ){
	$sql="select * from tab_acesso where sessao='" . $_SESSION['sessao'] . "'";

	if ($rs=$cn->query($sql)) {
		if(!($row = $rs->fetch_assoc()) ){
			include "logout.php";
		}
	}

}

if ( isset($_SESSION['id']) ) {
	include 'menu.php';
	return;
}

if ( isset($_POST['login']) ) {

	$login=HtmlChar( Clean( $_POST['login'] ) );
	$pass=HtmlChar( Clean( $_POST['pass'] ) );

	if ( trim($login)=='' || trim($pass=='') ) {

		echo "$login $pass erro";
		$cn->close();
		return;

	} else {

		$pass=hash("md5",$pass);

		$sql="select * from tab_acesso where id_entidade=$login and senha='$pass'";

		if ($rs=$cn->query($sql)) {
			if($row = $rs->fetch_array(MYSQLI_ASSOC)){

				$hash=md5(uniqid(time()));

				$sql="update tab_acesso set sessao='$hash' where id_entidade=$login";
				if( $cn->query($sql) ){
					if( !isset($_SESSION) ) {
						session_start();
					}

					$_SESSION['id'] = $row['id_entidade'];
					$_SESSION['usuario'] = $row['usuario'];
					$_SESSION['sessao'] = $hash;

					$_SESSION['cancelaitem'] = $row['cancelaitem'];
					$_SESSION['cancelacupom'] = $row['cancelacupom'];
					$_SESSION['sangria'] = $row['sangria'];
					$_SESSION['fechacaixa'] = $row['fechacaixa'];
					$_SESSION['desconto'] = $row['desconto'];
					$_SESSION['servidor'] = $row['servidor'];

					$_SESSION['buscprod'] = 1;
					$_SESSION['cadcli'] = 1;
					$_SESSION['editcli'] = 1;
					$_SESSION['showcli'] = 1;
					$_SESSION['editpreco'] = 1;

					$cn->close();
					include "menu.php";
					return;
				}
			} else {
				echo "erro";
				$cn->close();
				return;
			}
		}
	}
}

$cn->close();
?>
<form method="post" onsubmit="return Login();">

<input type="text" id="login" maxlength="10" size="10" pattern="[0-9]+" autofocus required placeholder="Login" />

<input type="password" id="pass" maxlength="10" size="10" pattern="[a-zA-Z0-9]+" required placeholder="Senha" />

<input type="submit" value="Entrar" />

<label class="erro" id="loginerro"></label>

</form>
