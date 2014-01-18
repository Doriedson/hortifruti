<?php

session_start();

include '../cn.php';
include '../funcoes.php';

$senha=HtmlChar( clean($_POST['pass']) ); 

$bt=$_POST['bt'];

if ( $bt=='valida' ) {

	if ( trim($senha)=='' ) {
		$erro="senha inválida.";
	} else {

		$senha=hash( 'md5', $senha );
		$sql="select * from tab_usuario where usuario='admin' and senha='$senha'";

		if ($rs=$cn->query($sql)) {

			if($row = $rs->fetch_array(MYSQLI_ASSOC)){

				$hash=md5(uniqid(time()));
			
				$sql="update tab_usuario set sessao='$hash'";
				$cn->query($sql);

				$_SESSION['admin'] = $hash;

				echo "ok";
				//echo json_encode( array( 'response' => $val ) ); 
				$cn->close();
				exit;
			} else {
				$erro="senha inválida";
			}
		}
	}
}

?>
<div >
	<div class="topoLogo">
		<a href="index.php">
			<img border="0" width="146" height="40" align="middle" src="images/imglogo.png" />
		</a>
    </div>
    
    <div class="topoDialogo">    	
    	Bem vindo, Admin !  
	<?php 
	if ($_SESSION['admin']=='') { ?>
		&nbsp;&nbsp;<input id=pass type=password value=123456 size=10 maxlenght=10>&nbsp;<a href="javascript:Login(document.getElementById('pass').value);" title="Entrar">login</a>&nbsp;&nbsp;<span style="color:#FF5555;"><?php echo ($erro)?$erro:""; ?></span>
	<?php	
	} else {
		echo '<a href="logout.php" title="Sair">logout</a>';
	} ?>
    </div>    
    
    <div class="topoMenu">
    
	</div>
     
    <br class="clear" />
    
</div>
<?php $cn->close(); ?>
