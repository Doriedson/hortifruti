<?php
$acesso=4;
include "check_session.php";

//include "funcoes.php";

echo "<h1>:: Clientes / Funcionários</h1>";

$compl="";

if( isset($nome) )	$compl=" where e.nome='$nome' ";

$sql="select e.id_entidade, e.nome, e.datacad, e.telefone, a.usuario  from tab_entidade e left join tab_acesso a on a.id_entidade=e.id_entidade $compl order by e.nome";


echo "<table><tr><th>Data</th><th>Nome</th><th>Telefone</th><th></th></tr>";

if ($rs=$cn->query($sql)) {
	while( $row=$rs->fetch_assoc() ) {
		
		echo "<tr><td>" . date_format( date_create($row['datacad']), 'd/m/Y H:i:s') . "</td><td>" . $row['nome'] . "</td><td>" . $row['telefone'] . "</td><td>";
		echo($_SESSION['editcli'])?"<input type='button' value='edit' onclick='EditCli(" . $row['id_entidade'] . ")' />":"";
		echo "</td></tr>";

	}
}
echo "</table>";

?>

