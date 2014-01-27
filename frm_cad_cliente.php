<?php
include "check_session.php";

$codigo = number_format( $_POST['codigo'], 0 );

if( $codigo==0 ) {
	$title = ":: Novo Registro";
	$datacad = date('Y-m-d');
} else {
	$title = ":: Edição de Registro";

	$sql = "select * from tab_entidade where id_entidade=$codigo";

	if ($rs=$cn->query($sql)) {
		if( $row=$rs->fetch_assoc() ){
			do{
				$cpf = $row['cpf'];
				$nome = $row['nome'];
				$endereco = $row['endereco'];
				$bairro = $row['bairro'];
				$cidade = $row['cidade'];
				$telefone = $row['telefone'];
				$prazo = $row['prazo'];
				$limite = $row['limite'];
				$obs = $row['obs'];
				$datacad = date_format( date_create($_POST['datacad']." 00:00:00"), 'Y-m-d');
			} while( $row=$rs->fetch_assoc() ) ;
		}
	}

}
?>

<div class='title'><? echo $title; ?> <img class='btClose' onclick="$('#wCadCli').hide();" title='Fechar' width='15px' src='img/close.png'></div>

<br />
<form id="frm_cad_cliente" method="post" onsubmit="return SalvaCli();">
	<fielset>
	<input type="hidden" id="codigo" value="<?php echo $codigo; ?>" />

	<label for='cpf'>data cadastro:</label>
	<input type="date" disabled  value="<? echo $datacad; ?>"/>

	<label for='cpf'>CPF/CNPJ:</label>
	<input type="text" id="cpf" maxlength="14" autofocus pattern="\d{0,14}" value="<?php echo $cpf; ?>" />

	<label for='nome'>*nome:</label>
	<input type="text" id="nome" maxlength="50" required pattern="[A-Z a-z0-9,\.]+" value="<?php echo $nome; ?>" />

	<label for='endereco'>endereço:</label>
	<input type="text" id="endereco" pattern="[A-Z a-z0-9,\.]+" maxlength="50" value="<?php echo $endereco; ?>" />

	<label for='bairro'>bairro:</label>
	<input type="text" id="bairro" pattern="[A-Z a-z0-9,\.]+" maxlength="50" value="<?php echo $bairro; ?>" />

	<label for='cidade'>cidade:</label>
	<input type="text" id="cidade" pattern="[A-Z a-z0-9,\.]+" maxlength="50" value="<?php echo $cidade; ?>" />

	<label for='telefone'>telefone:</label>
	<input type="tel" id="telefone" maxlength="20" value="<?php echo $telefone; ?>" />

	<label for='prazo'>venda à prazo:</label>
	<input type="checkbox" id="prazo" <?php echo($prazo)?'checked':''; ?> />

	<label for='limite'>limite:</label>
	<input type="number" id="limite" size="6" min='0' max='999.99' step='0.01' pattern="\d+(,\d{0,2})?" maxlength="6" placeholder='0,00' value="<?php echo $limite; ?>" />

	<label for='obs'>obs.:</label>
	<input type="text" id="obs" size="40" pattern="[A-Z a-z0-9,\.]+" maxlength="255" value="<?php echo $obs; ?>" />

	<input type="submit" value="Salvar" />
	</fieldset>
</form>