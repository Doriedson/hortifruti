<?php
if( !isset($_SESSION) ) {
	session_start();
}

include "cn.php";
include "funcoes.php";

$btDel="<input type='button' class='delOC' value='apagar' />";
$btEdit="<input type='button' class='editOC' value='alterar' />";
$btItem="<input type='button' class='itemOC' value='item' />";
$btSave="<input type='button' class='saveOC' value='salvar' />";
$btCancel="<input type='button' class='cancelOC' value='cancelar' />";
$btDelItem = "<input type='button' title='Apagar Item' onclick='DelItemOC(this)' value='x' />";
$btLancar = "<input type='button' class='lancaOC' value='Lançar' />";
$btPrint = "<input type='button' class='printOC' value='Imprimir' />";

$result="";
$result2="";
$valor=0;
$erro="";

$action=$_POST['action'];

if($action=='lista'){

	$sql="select oc.*, sum(oci.vol1*oci.custo) as vol1, a.usuario from tab_ordemcompra oc natural join tab_acesso a left join tab_ordemcompraitem oci on oci.id_oc=oc.id_oc group by oc.id_oc order by oc.data desc";

	if ($rs=$cn->query($sql)) {
		if( $row=$rs->fetch_assoc() ) {
			do {
				$result .= "<tr><td align='center'>" . date_format( date_create($row['data']), 'd/m/Y') . "</td>
						<td>" . $row['descricao'] . "</td>
						<td>R$ " . number_format($row['vol1'],2,',','.') . "</td>
						<td>" . $row['usuario'] . "</td>
						<td>" . $row['obs'] . "</td>
						<td id='" . $row['id_oc'] . "'>
							$btDel $btEdit $btItem $btLancar $btPrint
						</td></tr>";
			} while( $row=$rs->fetch_assoc() );
		}
	}

} else if ($action=='lancaOC'){

	$id_oc = $_POST['id_oc'];

	$sql = "select * from tab_ordemcompraitem where id_oc=$id_oc and ( vol1=0 or custo=0 or qtdvol=0 )";

	$rs=$cn->query($sql);

	if( $row=$rs->fetch_assoc() ){

		$result .= "erro";

	} else {

		$sql="insert into tab_estent (id_entidade, id_produto, qtdvol, vol, data, custo, tipo) select " . $_SESSION['id'] . ",oci.id_produto,oci.qtdvol,oci.vol1,oc.data,oci.custo,oci.tipo from tab_ordemcompraitem oci inner join tab_ordemcompra oc on oc.id_oc=oci.id_oc where oci.id_oc=$id_oc and oci.id_produto>0";
		$cn->query($sql);

		$sql="insert into tab_tmppreco (id_produto, custo) select id_produto, custo/qtdvol from tab_ordemcompraitem where id_oc=$id_oc and id_produto>0";
		$cn->query($sql);

		$sql="insert into tab_tmppreco (id_produto, custo) select p.id_produto,c.custo/c.qtdvol*p.sub_qtd from tab_ordemcompraitem c inner join tab_produto p on p.sub_produto=c.id_produto where c.id_oc=$id_oc and c.id_produto>0";
		$cn->query($sql);
		
		$sql="delete from tab_ordemcompraitem where id_oc=$id_oc";
		$cn->query($sql);

		$sql="delete from tab_ordemcompra where id_oc=$id_oc";
		$cn->query($sql);

		$result .= "ok";

	}

} else if ($action=='addLC'){

	$id_oc = $_POST['id_oc'];
	$id_lc = $_POST['id_lc'];

	$sql="select * from tab_listacompraitem where id_lc=$id_lc order by id_lcitem desc";

	if ($rs=$cn->query($sql)) {
		while( $row=$rs->fetch_assoc() ) {	
			
			$sql="select * from tab_estent natural join tab_produto where tab_estent.id_produto=" . $row['id_produto'] . " order by tab_estent.data desc limit 1";
			
			if ($rs2=$cn->query($sql)) {
				if( $row2=$rs2->fetch_assoc() ) {				
					
					$sql="insert into tab_ordemcompraitem (id_oc,id_produto,qtdvol,vol1,vol2,tipo,custo,obs) values ($id_oc," . $row['id_produto'] . "," . number_format($row2['qtdvol'],3,".",",") . ",0,0,'" . $row2['tipo'] . "',0,'')";
					$cn->query($sql);					

					$result .= "<tr><td>" . $row2['produto'] . "</td>
									<td><input type='number' id='vol' min='0' max='999.999' step='0.001' size='7' maxlength='7' onchange='ChangeOCI(this);' style='text-align:center;' placeholder='0,000' /></td>
									<td>x <input type='number' id='qtdvol' min='0' max='999.999' step='0.001' size='7' maxlength='7' onchange='ChangeOCI(this);' style='text-align:center;' placeholder='" . number_format($row2['qtdvol'],3,',','.') . "' /></td>
									<td align='center'>" . $row2['tipo'] . "</td>
									<td><input type='number' id='custo' min='0' max='999.99' step='0.01' size='6' maxlength='6' onchange='ChangeOCI(this);' style='text-align:center;' placeholder='0,00' /></td>
									<td>
										<input type='hidden' value='" . $cn->insert_id . "' />
										<input type='button' title='Ver Última Compra' value='$' onclick=\"TINY.box.show({url:'saida_produto.php',opacity:20,topsplit:3, post: 'id_produto=" . $row2['id_produto'] . "'})\" />
										$btDelItem
									</td>
								</tr>";

				}
			}

		}
	}

} else if ($action=='loadLC'){

	$id_oc = $_POST['id_oc'];

	//$result .= "<h1>:: Lista de Compra</h1>";

	$sql="select * from tab_listacompra";

	if ($rs=$cn->query($sql)) {
		if( $row=$rs->fetch_assoc() ) {

			$result .= "<table id='$id_oc'>
							<thead>
								<tr><th align='left'>Lista de Compra</th></tr>
							</thead>
							<tbody>";

			do {
				$result .= "<tr>
							<td id='" . $row['id_lc'] . "' style='cursor:pointer;' onclick='AddLC(this)'>" . $row['descricao'] . "</td>
							</tr>";
			} while( $row=$rs->fetch_assoc() );
			
			$result .= "</tbody></table>";

		} else {
			$result .= "Não há lista(s) de Compra(s) cadastrada.";
		}
	}

} else if ($action=='OC'){

	$id_oc = $_POST['id_oc'];

	$sql="select oc.*, sum(oci.vol1*oci.custo) as vol1, a.usuario from tab_ordemcompra oc natural join tab_acesso a left join tab_ordemcompraitem oci on oci.id_oc=oc.id_oc where oc.id_oc=$id_oc group by oc.id_oc";

	if ($rs=$cn->query($sql)) {
		if( $row=$rs->fetch_assoc() ) {
			$result .= "<tr><td align='center'>" . date_format( date_create($row['data']), 'd/m/Y') . "</td>
					<td>" . $row['descricao'] . "</td>
					<td>R$ " . number_format($row['vol1'],2,',','.') . "</td>
					<td>" . $row['usuario'] . "</td>
					<td>" . $row['obs'] . "</td>
					<td id='" . $row['id_oc'] . "'>
						$btDel $btEdit $btItem $btLancar $btPrint
					</td></tr>";
		}
	}

} else if ($action=='cancelOC'){

	$id_oc=$_POST['id_oc'];

	$sql="select oc.*, sum(oci.vol1*oci.custo) as vol1, a.usuario from tab_ordemcompra oc natural join tab_acesso a left join tab_ordemcompraitem oci on oci.id_oc=oc.id_oc where oc.id_oc=$id_oc group by oc.id_oc order by oc.data desc";

	if ($rs=$cn->query($sql)) {
		if( $row=$rs->fetch_assoc() ) {
			$result .= "<td align='center'>" . date_format( date_create($row['data']), 'd/m/Y') . "</td>
					<td>" . $row['descricao'] . "</td>
					<td>R$ " . number_format($row['vol1'],2,',','.') . "</td>
					<td>" . $row['usuario'] . "</td>
					<td>" . $row['obs'] . "</td>
					<td id='" . $row['id_oc'] . "'>
						$btDel $btEdit $btItem $btLancar $btPrint
					</td>";
		}
	}

} else if ($action=='saveOC'){

	$id_oc=$_POST['id_oc'];
	$data=$_POST['data'];
	$desc=$_POST['desc'];
	$obs=$_POST['obs'];

	$sql="update tab_ordemcompra set data='$data', descricao='$desc', obs='$obs' where id_oc=$id_oc";
	$cn->query($sql);

	$sql="select oc.*, sum(oci.vol1*oci.custo) as vol1, a.usuario from tab_ordemcompra oc natural join tab_acesso a left join tab_ordemcompraitem oci on oci.id_oc=oc.id_oc where oc.id_oc=$id_oc group by oc.id_oc order by oc.data desc";

	if ($rs=$cn->query($sql)) {
		if( $row=$rs->fetch_assoc() ) {
			$result .= "<td align='center'>" . date_format( date_create($row['data']), 'd/m/Y') . "</td>
					<td>" . $row['descricao'] . "</td>
					<td>R$ " . number_format($row['vol1'],2,',','.') . "</td>
					<td>" . $row['usuario'] . "</td>
					<td>" . $row['obs'] . "</td>
					<td id='" . $row['id_oc'] . "'>
						$btDel $btEdit $btItem $btLancar $btPrint
					</td>";
		}
	}

} else if ($action=='editOC'){

	$id_oc=$_POST['id_oc'];

	$sql="select * from tab_ordemcompra natural join tab_acesso where id_oc=$id_oc";

	if ($rs=$cn->query($sql)) {
		if( $row=$rs->fetch_assoc() ) {

			$result .= "<td><input type='date' id='data' value='" . date_format( date_create($row['data']), 'Y-m-d') . "' /></td>
						<td><input type='text' id='descricao' placeholder='Descrição' autofocus required pattern='[A-Z a-z0-9]+' size='20' maxlength='100' value='" . $row['descricao'] . "' /></td>
						<td>-</td>
						<td>" . $row['usuario'] . "</td>
						<td><input type='text' id='obs' placeholder='Observação' pattern='[A-Z a-z0-9]+' size='20' maxlength='255' value='" . $row['obs'] . "' /></td>
						<td id='" . $row['id_oc'] . "'>
						$btSave $btCancel
						</td>";

		}
	}

} else if ($action=='delOC'){

	$id_oc=$_POST['id_oc'];

	$sql="delete from tab_ordemcompraitem where id_oc=$id_oc";
	$cn->query($sql);

	$sql="delete from tab_ordemcompra where id_oc=$id_oc";
	$cn->query($sql);

} else if ($action=='delItem'){

	$id_oci = $_POST['id_oci'];

	$sql="delete from tab_ordemcompraitem where id_ocitem=$id_oci";
	$rs=$cn->query($sql);

} else if ($action=='item'){

	$id_oc=$_POST['id_oc'];

	$sql="select * from tab_ordemcompra where id_oc=$id_oc";

	$rs=$cn->query($sql);
	$row=$rs->fetch_assoc();

	$result2 .= "<li>
                <div class='aba'>
					<img height='10px' width='10px' src='img/close.png' class='closeOC' /><span> " . date_format( date_create($row['data']), 'd/m/Y') . " - " . $row['descricao'] . "</span>
                </div>
            </li>";
	
	$result .= "<div class='conteudo'>
				<form method='post' onsubmit='return addItemOC(this);' >
					<input type='hidden' id='id' value='$id_oc' />
					<input type='text' id='id_produto' size='13' maxlength='13' required autofocus placeholder='Código Produto' pattern='\d+' />
					<input type='submit' value='adicionar' />
					<input type='button' value='listas' onclick='AddLista(this);' />
				</form>
				<br />
				<table id='oc$id_oc'>
					<thead>
						<tr>
							<th>Produto</th>
							<th>Vol</th>
							<th>Qtd/Vol</th>
							<th>Tipo</th>
							<th>Custo</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						";
				

	$sql="select * from tab_ordemcompraitem natural join tab_produto where id_oc=$id_oc";

	if ( $rs=$cn->query($sql) ) {
		if ( $row=$rs->fetch_assoc() ) {

			do {
				$result .= "<tr><td>" . $row['produto'] . "</td>
								<td><input type='text' onfocus='GraficoES(" . $row['id_produto'] . ")' id='vol' min='0' max='999.999' step='0.001' size='7' maxlength='7' onchange='ChangeOCI(this);' style='text-align:center;' placeholder='" . number_format($row['vol1'],3,',','.') . "' /></td>
								<td>x <input type='text' id='qtdvol' min='0' max='999.999' step='0.001' size='7' maxlength='7' onchange='ChangeOCI(this);' style='text-align:center;' placeholder='" . number_format($row['qtdvol'],3,',','.') . "' /></td>
								<td align='center'>" . $row['tipo'] . "</td>
								<td><input type='text' id='custo' min='0' max='999.99' step='0.01' size='6' maxlength='6' onchange='ChangeOCI(this);' style='text-align:center;' placeholder='" . number_format($row['custo'],2,',','.') . "' /></td>
								<td>
									<input type='hidden' value='" . $row['id_ocitem'] . "' />
									<input type='button' title='Ver Última Compra' value='$' onclick='GraficoES(" . $row['id_produto'] . ")' />
									$btDelItem
								</td>
							</tr>";

			} while ($row=$rs->fetch_assoc());

		}
	}

	$result .= "</tbody></table></div>";

} else if ($action=='addItem'){

	$id_oc = $_POST['id_oc'];
	$id_produto = $_POST['id_produto'];

	$sql="select tab_produto.* from tab_produto left join tab_codbar on tab_codbar.id_produto=tab_produto.id_produto where tab_produto.id_produto=$id_produto or tab_codbar.codbar=$id_produto";
	
	if ( $rs=$cn->query($sql) ) {
		if ($row=$rs->fetch_assoc()) {
			
			$tipo = $row['tipo'];
			$qtd = 0;
			$id_produto = $row['id_produto'];
			$produto = $row['produto'];
			
			$sql = "select * from tab_estent where id_produto=$id_produto order by data desc limit 1;";
		
			if ( $rs = $cn->query($sql) ) {
				if ($row = $rs->fetch_assoc()) {
					$qtd = $row['qtdvol'];
				}
			}
			
			$sql = "insert into tab_ordemcompraitem (id_oc,id_produto,qtdvol,vol1,vol2,tipo,custo,obs) values ($id_oc,$id_produto,$qtd,0,0,'$tipo',0,'')";
			$rs = $cn->query($sql);

			$result .= "<tr>
					<td>$produto</td>
					<td><input type='text' onfocus='GraficoES($id_produto)' id='vol' min='0' max='999.999' step='0.001' size='7' maxlength='7' pattern='\d+(,\d{0,3})?' onchange='ChangeOCI(this);' style='text-align:center;' placeholder='0,000' /></td>
					<td>x <input type='text' id='qtdvol' min='0' max='999.999' step='0.001' size='7' maxlength='7' pattern='\d+(,\d{0,3})?' onchange='ChangeOCI(this);' style='text-align:center;' placeholder='" . number_format($qtd,3,',','.') . "' /></td>
					<td align='center'>$tipo</td>
					<td><input type='text' id='custo' min='0' max='999.99' step='0.01'  size='6' maxlength='6' pattern='\d+(,\d{0,2})?' onchange='ChangeOCI(this);' style='text-align:center;' placeholder='0,00' /></td>
					<td>
						<input type='hidden' value='" . $cn->insert_id . "' />
						<input type='button' title='Ver Última Compra' value='$' onclick='GraficoES($id_produto)' />
						$btDelItem
					</td>
					</tr>";

		} else {
			$result .= "<tr><td>erro</td></tr>";
		}
	}


} else if ($action=='changeOCI'){

	$id = $_POST['id'];
	$valor = $_POST['valor'];
	$id_oci = $_POST['id_oci'];

	switch($id) {

		case 'vol':
			$sql="update tab_ordemcompraitem set vol1=$valor where id_ocitem=$id_oci";
			$result .= number_format($_POST['valor'],3,",",".");
			break;

		case 'custo':
			$sql="update tab_ordemcompraitem set custo=$valor where id_ocitem=$id_oci";
			$result .= number_format($_POST['valor'],2,",",".");
			break;

		case 'qtdvol':
			$sql="update tab_ordemcompraitem set qtdvol=$valor where id_ocitem=$id_oci";
			$result .= number_format($_POST['valor'],3,",",".");
			break;

	}

	$cn->query($sql);

} else if ($action=='add'){

	$desc=$_POST['descricao'];
	$data=$_POST['data'];
	$obs=$_POST['obs'];

	$sql="insert into tab_ordemcompra (descricao,data,obs,id_entidade) values ('$desc','$data 00:00:00','$obs'," . $_SESSION['id'] . ")";

	if ( $cn->query($sql) ) {
		// $data .= "<tr>
		// 	<td align='center'>" . date_format( date_create($data), 'd/m/Y') . "</td>
		// 	<td>$desc</td>
		// 	<td>R$ 0,00</td>
		// 	<td>" . $_SESSION['usuario'] . "</td>
		// 	<td>$obs</td>
		// 	<td><input type='hidden' value='" . $cn->insert_id . "' />
		// 		$btDel $btEdit $btItem
		// 	</td></tr>";
		$result .= $cn->insert_id;
	}

}

$cn->close();

$arr = array ( 'data' => $result, 'data2' => $result2, 'valor' => $valor, 'erro' => $erro );

echo json_encode($arr);

?>