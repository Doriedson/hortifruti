<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title></title>
	<link rel="stylesheet" href="../css/fonts/stylesheet.css" type="text/css" charset="utf-8" />
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="js/scripts.js"></script>
	<script type="text/javascript" src="../js/ajax.js"></script>

	<script language="javascript">
	function AtualizaMenu() {

		$.post ( "menu.php", {}, function(data) {

			document.getElementById("nav-sidebar-inner").innerHTML=data;
		});

	}

	function Login( val )
	{

		$.post( "header.php", {pass: val, bt: "valida"}, function(data) {
		        if( data == 'ok' )
		        {
		                top.location = 'index.php';
		        }else{
		                document.getElementById('header').innerHTML=data;

		        }
		});
	}

	function Navega( page, stat, target, npg) {

		$.post ( page, {status: stat, pg: npg}, function(data) {

			document.getElementById(target).innerHTML=data;
	
			AtualizaMenu();
		});
	}

	function Admin( page, target, _antiga, _nova, _confirma) {


		$.post ( page, {antiga: _antiga, nova: _nova, confirma: _confirma, bt: "1"}, function(data) {

			document.getElementById(target).innerHTML=data;
	
		});
	}

	function Change( _tipo, _idlook ) {

		$.post ( "descricao.php", {tipo: _tipo, id_look: _idlook}, function(data) {

			document.getElementById("content-inner").innerHTML=data;
				
			AtualizaMenu();

		});


	}
</script>
</head>

<body>
	<?php include '../cn.php'; ?>

	<div id="wrapper">

		<div id=header>
			<?php include 'sessao.php'; ?>
			<?php include 'header.php'; ?>
		</div>
    
		<div class="clear"></div>

		<div>	   
				<div id="nav-sidebar">
				    	<div id="nav-sidebar-inner">
						<?php include 'menu.php'; ?>
					</div>
				</div>
			 
				<div id="content">
					<div id="content-inner">
						<?php include 'tabela.php'; ?>			
					</div>
				</div>
		</div>		

		<div class="clear"></div>

	 
	   	<?php include 'footer.php'; ?>
    	</div>
 
	<?php $cn->close(); ?>
</body>
</html>
