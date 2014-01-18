<!DOCTYPE HTML>

<html lang="pt-br">

	<head>
		<title>Hortifruti - Retaguarda</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="css/style.css" />
		<link rel="stylesheet" type="text/css" href="css/styletiny.css" />
		<link rel="stylesheet" type="text/css" href="plugins/jquery.jqplot.min.css" />

		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/tinybox.js"></script>

		<script type="text/javascript" src="js/funcoes.js"></script>
		<script type="text/javascript" src="js/func_despesa.js"></script>
		<script type="text/javascript" src="js/func_compra.js"></script>

		<script type="text/javascript" src="plugins/jquery.jqplot.min.js"></script>
		<!--script type="text/javascript" src="plugins/jqplot.canvasTextRenderer.min.js"></script>
		<script type="text/javascript" src="plugins/jqplot.canvasAxisLabelRenderer.min.js"></script-->
		<script type="text/javascript" src="plugins/jqplot.dateAxisRenderer.min.js"></script>
		<!--script type="text/javascript" src="plugins/jqplot.barRenderer.min.js"></script-->
		<script type="text/javascript" src="plugins/jqplot.highlighter.min.js"></script>
		<script type="text/javascript" src="plugins/jqplot.cursor.min.js"></script>

	<!--	<?php include "tema.php"; ?>
		<link rel="stylesheet" type="text/css" href="css/print.css" media="print" />
		
		<script src="js/print.js" type="text/javascript"></script>


TINY BOX ***********************************************
html - HTML content for window (string) - false
iframe - URL for embedded iframe (string) - false
url - path for AJAX call (string) - false
post - post variable string, used in conjunction with url (string) - false
image - image path (string) - false
width - preset width (int) - false
height - preset height (int) - false
animate - toggle animation (bool) - true
close - toggle close button (bool) - true
openjs - generic function executed on open (string) - null
closejs - generic function executed on close (string) - null
autohide - number of seconds to wait until auto-hiding (int) - false
boxid - ID of box div for style overriding purposes (string) - ''
maskid - ID of mask div for style overriding purposes (string) - ''
fixed - toggle fixed position vs static (bool) - true
opacity - set the opacity of the mask from 0-100 (int) - 70
mask - toggle mask display (bool) - true
top - absolute pixels from top (int) - null
left - absolute pixels from left (int) - null
topsplit - 1/x where x is the denominator in the split from the top (int) - 2

		 -->

	</head>

	<body>
		<header>
			<nav id="menu">
				<?php include "logout.php"; 
					include "login.php"; ?>
			</nav>
		</header>

		<section id="content"></section>

		<footer>
			Copyright 2014
		</footer>

	</body>

</html>

<!--body>
	<div class="page">

		<div class="body">
			<div class="menu">
				<div class="head"><?php include "head.php"; ?></div>
				<div class="menu_inner"><?php include "menu.php"; ?></div>
			</div>

			<div class="content" id="content"><?php 
												if( isset($install) ){
													include "form_msg.php";
												} else {
													(!isset($_SESSION['user']))? include 'form_login.php': include 'form_busca.php'; 
												} ?>
			</div>
		</div>

		<div class="clear"></div>

		<div class="footer"><?php include "footer.php"; ?></div>		
	</div>
</body-->


