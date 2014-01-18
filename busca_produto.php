<?php
$acesso=1;
include "check_session.php";
include "funcoes.php";

if ( isset($_POST['busca']) ) {

	$busca=trim( HtmlChar( Clean( $_POST['busca'] ) ) );

	$length=strlen($busca);

	$space=0;
	$_busca="";

	//remover espaços excedentes no meio da palavra
	for($x = 0; $x < $length; $x++){

		$letter = substr($busca, $x, 1);

		if($letter == " "){
			if($space==0){
				$space=1;
					$_busca = $_busca . $letter;
			}
		} else {
			$space=0;
				$_busca = $_busca . $letter;
		}    
	}

	$busca= str_replace(" ","|", $_busca);

	if( is_numeric($busca) ) {
		$sql="select tab_produto.*, tab_setor.setor, s.produto as sub from tab_produto left join tab_codbar on tab_codbar.id_produto=tab_produto.id_produto inner join tab_setor on tab_setor.id_setor=tab_produto.id_setor left join tab_produto s on tab_produto.sub_produto=s.id_produto where tab_produto.id_produto=$busca or tab_codbar.codbar=$busca";
	} else {
		$sql="select tab_produto.*, tab_setor.setor, s.produto as sub from tab_produto inner join tab_setor on tab_setor.id_setor=tab_produto.id_setor left join tab_produto s on tab_produto.sub_produto=s.id_produto where tab_produto.produto REGEXP '$busca' order by tab_produto.produto";
	}

	echo "<table><tr><th>Código</th><th align='left'>Produto</th><th>Setor</th><th>Tipo</th><th>Preço</th><th>Ativo</th><th>SubProduto</th><th></th><th></th></tr>";

	if ($rs=$cn->query($sql)) {
		while( $row=$rs->fetch_assoc() ) {
			$sub=($row['sub'])?$row['sub']:"";
			echo "<tr><td align='center'>" . $row['id_produto'] . "</td><td>" . $row['produto'] . "</td><td>" . $row['setor'] . "</td><td align='center'>" . $row['tipo'] . "</td><td align='right'>R$ " . number_format($row['preco'],2,",",".") . "</td><td align='center'><input type='checkbox' onclick='Ativa(" . $row['id_produto'] . ");' " . ($row['ativo']?"checked":"") . " /></td><td>$sub</td><td><input type='button' value='Preço' onclick=\"TINY.box.show({url:'preco.php',opacity:20,topsplit:3, post: 'produto=" . $row['id_produto'] . "'})\" /><input type='button' value='Cadastro' /></td></tr>";

		}
	}
	echo "</table>";
} else {
?>

<h1>:: Consulta de Produtos</h1>

<form method="post" onsubmit="return BuscaProduto();">
	<input type="text" id="busca" size="50" maxlength="50" pattern="[a-z A-Z0-9,+_\.]+" autofocus required placeholder="Produto" />
	<input type="submit" value="Busca" />
</form>
<br />
<div id="div_busca"></div>

<?php
}
?>
