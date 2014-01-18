<?php
if( !isset($_SESSION) ) {
	session_start();
}

include "cn.php";

$result="";
$valor=0;
$erro="";

$action=$_POST['action'];

if ($action=='sdesc'){
	
	$desc = $_POST['desc'];

	$sql="select * from tab_ctspag natural join tab_catctspag where descricao like '%$desc%' order by datacad desc";
	
	if ( $rs = $cn->query($sql) ) {
		if( $row=$rs->fetch_assoc() ){
			do {
				$result .= "<tr><td align='center'>" . date_format( date_create($row['datacad']), 'd/m/Y') . "</td>
						<td align='center'>" . $row['catctspag'] . "</td>
						<td>" . $row['descricao'] . "</td>
						<td align='center'>R$ " . number_format($row['valor'],2,",",".") . "</td>
						<td><input type='hidden' value='" . $row['id_ctspag'] . "' />
							<input type='button' class='delDespesa' value='apagar' />
							<input type='button' class='editDespesa' value='editar' /></td>
						</tr>";
				$valor += $row['valor'];			
			}while($row=$rs->fetch_assoc());
		} else {
			$erro .= "Nenhum registro localizado para a descrição: $desc";
		}

	}

} else if ($action=='sdata'){
	
	$data = $_POST['data'];

	$sql="select * from tab_ctspag natural join tab_catctspag where datacad between '$data 00:00:00' and '$data 23:59:59' order by datacad desc";
	
	if ( $rs = $cn->query($sql) ) {
		if( $row = $rs->fetch_assoc() ){
		
			do{
				$result .= "<tr><td align='center'>" . date_format( date_create($row['datacad']), 'd/m/Y') . "</td>
						<td align='center'>" . $row['catctspag'] . "</td>
						<td>" . $row['descricao'] . "</td>
						<td align='center'>R$ " . number_format($row['valor'],2,",",".") . "</td>
						<td><input type='hidden' value='" . $row['id_ctspag'] . "' />
							<input type='button' class='delDespesa' value='apagar' />
							<input type='button' class='editDespesa' value='editar' /></td>
						</tr>";
				$valor += $row['valor'];
			}while( $row=$rs->fetch_assoc() );
			
		} else {
			$erro .= "Nenhum registro localizado para a data " . date_format( date_create($data), 'd/m/Y');
		}
	}

} else if ($action=='add'){

	$data = $_POST['data'];
	$setor = $_POST['setor'];
	$setort = $_POST['setort'];
	$desc = $_POST['desc'];
	$valor = $_POST['valor'];

	$sql="insert into tab_ctspag (id_entidade,datacad,id_catctspag,descricao,valor) values (" . $_SESSION['id'] . ",'$data',$setor,'$desc',$valor)";

	if ( $cn->query($sql) ) {
		$result .= "<tr><td align='center'>" . date_format( date_create($data), 'd/m/Y') . "</td>
		<td align='center'>$setort</td>
		<td>$desc</td>
		<td align='center'>R$ " . number_format($valor,2,",",".") . "</td>
		<td><input type='hidden' value='" . $cn->insert_id . "' />
		<input type='button' class='delDespesa' value='apagar' />
		<input type='button' class='editDespesa' value='editar' /></td></tr>";
	}

} else if ($action=='save'){

	$data = $_POST['data'];
	$setor = $_POST['setor'];
	$setort = $_POST['setort'];
	$desc = $_POST['desc'];
	$valor = $_POST['valor'];
	$id = $_POST['id'];

	$sql="update tab_ctspag set id_entidade=" . $_SESSION['id'] . ", datacad='$data', id_catctspag=$setor, descricao='$desc', valor=$valor where id_ctspag=$id";

	if ( $cn->query($sql) ) {
		$result .= "<td align='center'>" . date_format( date_create($data), 'd/m/Y') . "</td>
		<td align='center'>$setort</td>
		<td>$desc</td>
		<td align='center'>R$ " . number_format($valor,2,",",".") . "</td>
		<td><input type='hidden' value='$id' />
		<input type='button' class='delDespesa' value='apagar' />
		<input type='button' class='editDespesa' value='editar' /></td>";
	}

} else if ($action=='del'){

	$id = $_POST['id'];

	$sql = "select valor from tab_ctspag where id_ctspag=$id";

	if ( $rs = $cn->query($sql) ) {
		if ( $row = $rs->fetch_assoc() ){
			$valor -= $row['valor'];

			$sql="delete from tab_ctspag where id_ctspag=$id";

			$cn->query($sql);
		}
	}

} else if ($action=='edit'){

	$id = $_POST['id'];

	$sql="select * from tab_ctspag where id_ctspag=$id";

	if ( $rs = $cn->query($sql) ) {
		if( $row=$rs->fetch_assoc() ){
		
			$result .= "<td align='center'><input type='date' id='data' size='10' value='" . date_format( date_create($row['datacad']), 'Y-m-d') . "' required autofocus /></td><td align='center'>";
			
			$comp= "</td><td><input type='text' id='desc' size='25' maxlength='100' value='" . $row['descricao'] . "' required placeholder='Descrição' pattern='[A-Z a-z0-9]+' /></td>
				<td align='center'><input type='number' id='valor' value='" . $row['valor'] . "' style='text-align:center;' placeholder='0,00' size='7' min='0.01' max='9999.99' step='0.01' maxlength='7' pattern='\d+(,\d{0,2})?' required /></td>
				<td><input type='hidden' value='" . $row['id_ctspag'] . "' /><input type='button' class='saveDespesa' value='salvar' /></td>";
			$valor -= $row['valor'];
			
			$setor=$row['id_catctspag'];
			include 'select_despesa.php';

			$result .= $catdespesa . $comp;

		}
	}

}

$cn->close();

$arr = array ( 'data' => $result, 'valor' => $valor, 'erro' => $erro );

echo json_encode($arr);

?>