<?php
//$codigo=0;
$acesso=2;

$tipo = $_POST['tipo'];
//if( isset($_POST['codigo']) ){
//}

switch ($tipo){

	case 'cadastro':
		$codigo=$_POST['codigo'];
		if( $codigo>0 && !isset($_POST['nome']) ) $acesso=3;

		include "check_session.php";

		$cpf=$_POST['cpf'];
		$nome=$_POST['nome'];
		$endereco=$_POST['endereco'];
		$bairro=$_POST['bairro'];
		$cidade=$_POST['cidade'];
		$telefone=$_POST['telefone'];
		$prazo=($_POST['prazo'])?1:0;
		$limite="0" . $_POST['limite'];
		$obs=$_POST['obs'];

		if( $codigo==0 ) {
			$sql="insert into tab_entidade (datacad, cpf, nome, endereco, bairro, cidade, telefone, prazo, limite, obs) values (now(),'$cpf','$nome','$endereco','$bairro','$cidade','$telefone',$prazo,$limite,'$obs')";
		} else {
			$sql="update tab_entidade set cpf='$cpf', nome='$nome', endereco='$endereco', bairro='$bairro', cidade='$cidade', telefone='$telefone', prazo=$prazo, limite=$limite, obs='$obs' where id_entidade=$codigo";
		}
echo $sql;
		$cn->query($sql);
		//include "clientes.php";

		$cn->close();
		break;

	case 'lista':
		$acesso=4;
		include "check_session.php";

		$str="";

		$sql="select e.id_entidade, e.nome, e.datacad, e.telefone, a.usuario  from tab_entidade e left join tab_acesso a on a.id_entidade=e.id_entidade order by e.nome";

		if ($rs=$cn->query($sql)) {
			if ( $row=$rs->fetch_assoc() ) {
				do{
					
					$str .= "<tr><td align='center'>" . $row['id_entidade'] . "</td><td>" . $row['nome'] . "</td><td>" . $row['telefone'] . "</td><td>";
					$str .= ($_SESSION['editcli'])?"<input type='button' value='edit' onclick='CadCli(" . $row['id_entidade'] . ")' />":"";
					$str .= "</td></tr>";

				} while( $row=$rs->fetch_assoc() ) ;
			}
		}
		echo $str;
		break;
} 
?>
