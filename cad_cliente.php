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
<h1>:: Cadastro de Cliente</h1>

<form method="post" onsubmit="return CadCli();">
<input type="hidden" id="codigo" value="<?php echo $codigo; ?>" />
CPF/CNPJ: <input type="text" id="cpf" size="14" maxlenght="14" autofocus pattern="\d{0,14}" value="<?php echo $cpf; ?>" />
Nome: <input type="text" id="nome" size="20" maxlenght="50" required pattern="[A-Z a-z0-9,\.]+" value="<?php echo $nome; ?>" /><br />
Endereço: <input type="text" id="endereco" size="20" pattern="[A-Z a-z0-9,\.]+" maxlenght="50" value="<?php echo $endereco; ?>" />
Bairro: <input type="text" id="bairro" size="20" pattern="[A-Z a-z0-9,\.]+" maxlenght="50" value="<?php echo $bairro; ?>" /><br />
Cidade: <input type="text" id="cidade" size="20" pattern="[A-Z a-z0-9,\.]+" maxlenght="50" value="<?php echo $cidade; ?>" />
Telefone: <input type="text" id="telefone" size="20" pattern="\d{0,14}" maxlenght="20" value="<?php echo $telefone; ?>" /><br />
Venda à Prazo: <input type="checkbox" id="prazo" <?php echo($prazo)?'checked':''; ?> />
Limite:<input type="text" id="limite" size="7"  pattern="\d+(,\d{0,2})?" maxlenght="7" value="<?php echo number_format($row['limite'],2,",","."); ?>" /><br />
Obs.: <input type="text" id="obs" size="40" pattern="[A-Z a-z0-9,\.]+" maxlenght="255" value="<?php echo $obs; ?>" /><br />
<input type="submit" value="Salvar" />
</form>
