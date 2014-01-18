<?php
if( !isset($_SESSION) )	session_start();

if (!isset($_SESSION['id']) ) {
    session_destroy();
    header("Location:index.php");
    exit;
}

include "cn.php";
include "funcoes.php";

echo "<h1>:: Clientes / Usuários</h1>";

$sql="select e.id_entidade, e.nome, a.usuario  from tab_entidade e left join tab_acesso a on a.id_entidade=e.id_entidade order by e.nome";


echo "<table><tr><th>Nome</th></tr>";

if ($rs=$cn->query($sql)) {
	while( $row=$rs->fetch_assoc() ) {
		
		echo "<tr><td align='center'>" . $row['nome'] . "</td></tr>";

	}
}
echo "</table>";

?>

