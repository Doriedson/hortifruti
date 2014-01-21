<?php
$codigo=0;
$acesso=2;

if( isset($_POST['codigo']) ){
	$codigo=$_POST['codigo'];
	if( $codigo>0 && !isset($_POST['nome']) ) $acesso=3;
}

include "check_session.php";

if( isset($_POST['nome']) ){

	$cpf=$_POST['cpf'];
	$nome=$_POST['nome'];
	$endereco=$_POST['endereco'];
	$bairro=$_POST['bairro'];
	$cidade=$_POST['cidade'];
	$telefone=$_POST['telefone'];
	$prazo=($_POST['prazo'])?1:0;
	$limite=str_replace(",",".",$_POST['limite']);
	$obs=$_POST['obs'];

	if( $codigo==0 ) {
		$sql="insert into tab_entidade (datacad, cpf, nome, endereco, bairro, cidade, telefone, prazo, limite, obs) values (now(),'$cpf','$nome','$endereco','$bairro','$cidade','$telefone',$prazo,$limite,'$obs')";
	} else {
		$sql="update tab_entidade set cpf='$cpf', nome='$nome', endereco='$endereco', bairro='$bairro', cidade='$cidade', telefone='$telefone', prazo=$prazo, limite=$limite, obs='$obs' where id_entidade=$codigo";
	}

	$cn->query($sql);
	include "clientes.php";

	$cn->close();
	exit;
} else if( $codigo>0 ) {

	$sql="select * from tab_entidade where id_entidade=$codigo";

	if( $rs=$cn->query($sql) ) {
		if( $row=$rs->fetch_assoc() ) {
			$cpf=$row['cpf'];
			$nome=$row['nome'];
			$endereco=$row['endereco'];
			$bairro=$row['bairro'];
			$cidade=$row['cidade'];
			$telefone=$row['telefone'];
			$prazo=$row['prazo'];
			$limite=$row['limite'];
			$obs=$row['obs'];
		}
	}
}

?>
