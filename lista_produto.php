<?php
include "cn.php";
include "funcoes.php";

$sql="select * from tab_produto order by produto";

echo "<table><tr><th>Código</th><th>Produto</th><th>Preço</th><th>Ativo</th></tr>";

if ($rs=$cn->query($sql)) {
	while( $row=$rs->fetch_assoc() ) {

		echo "<tr><td align='center'>" . $row['id_produto'] . "</td><td>" . $row['produto'] . "</td><td align='right'>" . number_format($row['preco'],2,",",".") . "</td><td align='center'><input type='checkbox' " . ($row['ativo']?"checked":"") . " /></td></tr>";

	}
}
?>
</table>
