<?php
//ini_set('mssql.charset', 'utf-8');

$serverName = "187.35.157.70"; //serverName\instanceName
$cnInfo = array( "Database"=>"sacolao", "UID"=>"sa", "PWD"=>"8511965");

//$cn = sqlsrv_connect($serverName, $cnInfo);
$cn = mssql_connect($serverName, "sa", "8511965");

if ( !$cn || ! mssql_select_db('sacolao', $cn) ) {
	echo "Connection could not be established.<br />";
	die( print_r( mssql_errors(), true));
}

//echo phpinfo();
?>
