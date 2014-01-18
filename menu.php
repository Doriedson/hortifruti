<script type="text/JavaScript">
	$(document).ready(function() {
		$("#menu").find("ul li ul li a").click(function() {
			$("#content").load(this.href);
			$('nav ul ul').css('display','none');
			refresh();
			return false;
		});

		$("#menu").find("ul li").hover(function() {

			$(this).find('ul').css('display','block');

		}, function (){

			$(this).find('ul').css('display','none');

		});
	});
</script>

<ul>
	<li><a href="#">Produto</a>
		<ul>
			<li><a href="cad_produto.php">Novo Cadastro</a></li>
			<?php 
			if($_SESSION['buscprod']==1) { 
				echo "<li><a href='busca_produto.php'>Consulta</a></li>";
			} else {
				echo "<span class='disable'>Consulta</span>";
			}

			if($_SESSION['editpreco']==1) { 
				echo "<li><a href='altera_preco.php'>Alteração de Preço</a></li>";
			} else {
				echo "<span class='disable'>Alteração de Preço</span>";
			}
			?>
		</ul>
	</li>

	<li><a href="#">Cliente / Funcionário</a>
		<ul>
			<?php 
			if($_SESSION['cadcli']==1) { 
				echo "<li><a href='cad_cliente.php'>Novo Cadastro</a></li>";
			} else {
				echo "<span class='disable'>Novo Cadastro</span>";
			}

			if($_SESSION['showcli']==1) { 
				echo "<li><a href='clientes.php'>Lista</a></li>";
			} else {
				echo "<span class='disable'>Lista</span>";
			}
			?>
		</ul>
	</li>

	<li><a href="#">Compra</a>
		<ul>
			<li><a href="frm_compra.php">Ordem de Compra</a></li>
			<li><a href="lista_compra.php">Lista de Compra</a></li>
		</ul>
	</li>

	<li><a href="#">Venda</a>
		<ul>
			<li><a href="pedidos.php">Pedidos em Aberto</a></li>
			<li><a href="novo_pedido.php">Novo Pedido</a></li>
		</ul>
	</li>

	<li><a href="#">Despesa</a>
		<ul>
			<li><a href="frm_despesa.php">Lançamento</a></li>
		</ul>
	</li>

	<li><a href="#">Relatório</a>
		<ul>
			<li><a href="rel_vendatotal.php">Total de Vendas</a></li>
			<li><a href="rel_vendaproduto.php">Produtos Vendidos</a></li>
			<li><a href="rel_sangria.php">Sangrias</a></li>
			<li><a href="rel_custo.php">Custo</a></li>
			<li><a href="rel_estent.php">Entrada de Estoque</a></li>
			<li><a href="rel_quebracaixa.php">Quebra de Caixa</a></li>
			<li><a href="etiqueta.php">Impressão de Etiquetas</a></li>
		</ul>
	</li>

	<li><a href="javascript:Logout();">Sair (<span id='tempo'>5:00</span>)</a></li>

</ul>

