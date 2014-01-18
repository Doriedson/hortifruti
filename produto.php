<?php
include 'cn.php';

$sql="select * from tab_caixa";

$rs=mssql_query($sql);

while ($row=mssql_fetch_array($rs,MSSQL_ASSOC)){
	echo $row['dataini'] . " - R$ " . $row['trocoini'] . "<br>";
}

mssql_free_reult( $rs);
mssql_close($cn);

?>
