var timeout;

// $(document).ready(function(e) {
// 	$.post("logout.php",{}, function(data) {}
// });

function timeCount(){
	timeout--;
	if(timeout==0){
		Logout();
	}
	show(timeout);
}

function refresh(){
	timeout=300;
	show(timeout);
}

function show(timeout){
	var min = parseInt(timeout/60);
	var sec = timeout%60;

	$("#tempo").html( ((min<10)?"0":"") + min + ":" + ((sec<10)?"0":"") + sec );
}

Number.prototype.formatMoney = function(decPlaces, thouSeparator, decSeparator) {
    var n = this,
    decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
    decSeparator = decSeparator == undefined ? "," : decSeparator,
    thouSeparator = thouSeparator == undefined ? "." : thouSeparator,
    sign = n < 0 ? "-" : "",
    i = parseInt(n = Math.abs(+n || 0).toFixed(decPlaces)) + "",
    j = (j = i.length) > 3 ? j % 3 : 0;
    return sign + (j ? i.substr(0, j) + thouSeparator : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thouSeparator) + (decPlaces ? decSeparator + Math.abs(n - i).toFixed(decPlaces).slice(2) : "");
};

function isNumber(e) {

	var tecla=(window.event)?event.keyCode:e.which;	  
	if((tecla>47 && tecla<58)) {			
	  	return true;      		
	}else{
	  	if (tecla==8 || tecla==0) {	  			
	  		return true;
	  	}else {  
	  		return false;
	  	}
	}
}
function isMoney(e) {

	var tecla=(window.event)?event.keyCode:e.which;	

	if((tecla>47 && tecla<58)) {			
	  	return true;      		
	}else{
	  	if (tecla==8 || tecla==0 || tecla==44) {	  			
	  		return true;
	  	}else {  
	  		return false;
	  	}
	}
}

function Login() {

	$("#loginerro").hide();

	$.post("login.php", { login: $("#login").val(), pass: $("#pass").val() }, function(data) {
		if( data=="erro"){
			$("#loginerro").html("Login ou senha inválida!");
			$("#loginerro").fadeIn(90);
		} else {
			$("#menu").html(data);
			refresh();
			setInterval(timeCount,1000);
		}
	});

	return false;
}

function PrintEtiqueta(){

	$.post("etiqueta.php",{codigo: $("#codigo").val() }, function(data) {
		refresh();
		$("#relatorio").html(data);
		$("#codigo").val("");
	});

	return false;
}

function DelEtiqueta(id){
	$.post("etiqueta.php",{del: id}, function(data) {
		refresh();
		$("#relatorio").html(data);
	});
}

function GravarPreco(){
	$.post("altera_preco.php",{gravar: 'true'}, function(data) {
		refresh();
		$("#content").load("etiqueta.php");
	});
}

function AlteraPreco(campo,id){

	if( !campo.validity.valid ) {
		campo.placeholder="erro";
		campo.value="";
		return;
	}

	custo = $("#c"+id).html();
	custo=parseFloat( custo.replace(",",".") );
	preco=parseFloat( (campo.value).replace(",",".") );

	$.post("altera_preco.php",{id: id, preco: preco}, function(data) {
		refresh();
		$("#p"+id).html( ((preco/custo * 100) - 100).toFixed(0) );
		
		campo.placeholder=data;
		campo.value='';
	});
}

function BuscaProduto() {

	$.post("busca_produto.php", { busca: $("#busca").val() }, function(data) {
		refresh();
//		$("#produto").val("");
		$("#div_busca").html(data);
		$("#busca").select();
	});

	return false;
}

function Ativa(produto) {

	$.post("ativa_produto.php", { produto: produto }, function(data) {
		refresh();
		BuscaProduto();
	});

	return false;
}

function Preco() {

	$.post("preco.php", { produto: $("#produto").val(), preco: $("#preco").val() }, function(data) {
		refresh();
		TINY.box.fill("Preço Alterado!");
		$("footer").html(data);
		setTimeout(TINY.box.hide,1000);
		BuscaProduto();
	});

	return false;
}

function ShowSubProduto(){

	$.post("show_subproduto.php", { sub_produto: $("#sub_produto").val() }, function(data) {
		refresh();
		$("#subproduto").html(data);
	});

}

function NovoProduto() {

	$("#produtoerro").hide();
	$("#produtoerro").html("");

	$.post("cad_produto.php", { produto: $("#produto").val(), preco: $("#preco").val(), ativo: $("#ativo").attr('checked'), tipo: $("#tipo").val(), id_setor: $("#id_setor").val(), sub_produto: $("#sub_produto").val(), sub_qtd: $("#sub_qtd").val()  }, function(data) {
		refresh();
		if( data.substr(0,11)=="<!--erro-->" ){
			$("#produtoerro").html(data);
			$("#produtoerro").fadeIn(90);

		} else {
			$.post("busca_produto.php",function(data2){
				$("#content").html(data2);
				$("#busca").val(data);
				BuscaProduto();
			});
		}
		//BuscaProduto();
	});

	return false;
}

function CadCli() {

//	$("#produtoerro").hide();
//	$("#produtoerro").html("");
	$.post("cad_cliente.php", { codigo: $("#codigo").val(), cpf: $("#cpf").val(), nome: $("#nome").val(), prazo: $("#prazo").attr('checked'), endereco: $("#endereco").val(), bairro: $("#bairro").val(), cidade: $("#cidade").val(), telefone: $("#telefone").val(), obs: $("#obs").val(), limite: $("#limite").val()  }, function(data) {
		refresh();
		$("#content").html(data);
	});

	return false;
}

function EditCli(codigo) {

	$.post("cad_cliente.php", { codigo: codigo }, function(data) {
		refresh();
		$("#content").html(data);
	});

}

function Logout() {
	clearInterval(timeCount);

	$.post("logout.php",{}, function(data) {
		$(window.document.location).attr('href','index.php');
	});
}

function RelQuebraCaixa() {

	$("#relerro").hide();
	$("#relatorio").html("");

	$.post("rel_quebracaixa.php", { datai: $("#datai").val(), dataf: $("#dataf").val() }, function(data) {
		refresh();
		if( data.substr(0,11)=="<!--erro-->" ){
			$("#relerro").html(data);
			$("#relerro").fadeIn(90);
		} else {
			$("#relatorio").html(data);
		}
	});

	return false;
}

function RelVendaTotal() {

	$("#relerro").hide();
	$("#relatorio").html("");

	$.post("rel_vendatotal.php", { datai: $("#datai").val(), dataf: $("#dataf").val(), caixa: $("#caixa").attr('checked') }, function(data) {
		refresh();
		if( data.substr(0,11)=="<!--erro-->" ){
			$("#relerro").html(data);
			$("#relerro").fadeIn(90);
		} else {
			$("#relatorio").html(data);
		}
	});

	return false;
}

function RelVendaProduto() {

	$("#relerro").hide();
	$("#relatorio").html("");

	$.post("rel_vendaproduto.php", { datai: $("#datai").val(), dataf: $("#dataf").val() }, function(data) {
		refresh();
		if( data.substr(0,11)=="<!--erro-->" ){
			$("#relerro").html(data);
			$("#relerro").fadeIn(90);
		} else {
			$("#relatorio").html(data);
		}
	});

	return false;
}

function RelCusto() {

	$("#relerro").hide();
	$("#relatorio").html("");

	$.post("rel_custo.php", { datai: $("#datai").val(), dataf: $("#dataf").val() }, function(data) {
		refresh();
		if( data.substr(0,11)=="<!--erro-->" ){
			$("#relerro").html(data);
			$("#relerro").fadeIn(90);
		} else {
			$("#relatorio").html(data);
		}
	});

	return false;
}

function RelEstEnt() {

	$("#relerro").hide();
	$("#relatorio").html("");

	$.post("rel_estent.php", { datai: $("#datai").val(), dataf: $("#dataf").val() }, function(data) {
		refresh();
		if( data.substr(0,11)=="<!--erro-->" ){
			$("#relerro").html(data);
			$("#relerro").fadeIn(90);
		} else {
			$("#relatorio").html(data);
		}
	});

	return false;
}

function RelSangria() {

	$("#relerro").hide();
	$("#relatorio").html("");

	$.post("rel_sangria.php", { datai: $("#datai").val(), dataf: $("#dataf").val() }, function(data) {
		refresh();
		if( data.substr(0,11)=="<!--erro-->" ){
			$("#relerro").html(data);
			$("#relerro").fadeIn(90);
		} else {
			$("#relatorio").html(data);
		}
	});

	return false;
}

/*
function LoadRelTotalVenda() {
	$.post("form_reltotalvenda.php", function(data) {
		if( data=="redirect"){
			Redirect();
		} else {
			$("#content").html(data);
		}
	});
}

function LoadRelMaisVendidos() {
	$.post("form_relmaisvendido.php", function(data) {
		if( data=="redirect"){
			Redirect();
		} else {
			$("#content").html(data);
		}
	});
}

function LoadRelMaisVendidosPesquisa() {

	$("#relaterror").hide();

	if( !isDate($("#data_i").val()) || !isDate($("#data_f").val()) ){
		$("#relaterror").html("Digite uma Data válida!");
		$("#relaterror").fadeIn(90);
		return;
	}

	$.post("form_relmaisvendido.php", { data_i: $("#data_i").val(), data_f: $("#data_f").val() }, function(data) {
		if( data=="redirect"){
			Redirect();
		} else {
			$("#content").html(data);
		}
	});
}

function PesquisaRelTotalVenda() {

	$("#relaterror").hide();

	if( !isDate($("#data_i").val()) || !isDate($("#data_f").val()) ){
		$("#relaterror").html("Digite uma Data válida!");
		$("#relaterror").fadeIn(90);
		return;
	}

	$.post("form_reltotalvenda.php", { data_i: $("#data_i").val(), data_f: $("#data_f").val() }, function(data) {
		if( data=="redirect"){
			Redirect();
		} else {
			$("#content").html(data);
		}
	});
}

function PesquisaRelEntEst() {

	$("#relaterror").hide();

	if( !isDate($("#data_i").val()) || !isDate($("#data_f").val()) ){
		$("#relaterror").html("Digite uma Data válida!");
		$("#relaterror").fadeIn(90);
		return;
	}

	$.post("form_relentrada.php", { data_i: $("#data_i").val(), data_f: $("#data_f").val() }, function(data) {
		if( data=="redirect"){
			Redirect();
		} else {
			$("#content").html(data);
		}
	});
}

function isDate(dateStr) {

	var datePat = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/;
	var matchArray = dateStr.match(datePat); // is the format ok?

	if (matchArray == null) {
		//alert("Please enter date as either mm/dd/yyyy or mm-dd-yyyy.");
		return false;
	}

	month = matchArray[3]; // p@rse date into variables
	day = matchArray[1];
	year = matchArray[5];

	if (month < 1 || month > 12) { // check month range
		//alert("Month must be between 1 and 12.");
		return false;
	}

	if (day < 1 || day > 31) {
		//alert("Day must be between 1 and 31.");
		return false;
	}

	if ((month==4 || month==6 || month==9 || month==11) && day==31) {
		//alert("Month "+month+" doesn`t have 31 days!")
		return false;
	}

	if (month == 2) { // check for february 29th
	var isleap = (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0));
	if (day > 29 || (day==29 && !isleap)) {
		//alert("February " + year + " doesn`t have " + day + " days!");
		return false;
	}
}
return true; // date is valid
}

function dataMask(inputData, e){
	if(document.all) // Internet Explorer
		var tecla = event.keyCode;
	else //Outros Browsers
		var tecla = e.which;

	if( tecla != 9 ) {
		if(tecla >= 47 && tecla < 58){ // numeros de 0 a 9 e "/"
			var data = inputData.value;
			if( data.length < 10 ) {
				if (data.length == 2 || data.length == 5){
					data += '/';
				
				}
				inputData.value = data;
			}else{
				return false;
			}
		}else if(tecla == 8 || tecla == 0) // Backspace, Delete e setas direcionais(para mover o cursor, apenas para FF)
			return true;
		else
			return false;
	}else {
		return true;
	}
}

function Redirect() {
	$(window.document.location).attr('href','index.php');
}

function Login() {  
	$("#loginerror").fadeOut(90);

	usuario_post = $("input#usuario").val(); 
	senha_post = $("input#senha").val(); 

	setTimeout(function(){
		$.post("login.php",{usuario: usuario_post, senha: senha_post},function(data){
			if (data=="ok") {
				Redirect();
			} else {
				//$("#loginerror").show();
				$("#loginerror").fadeIn(90);
				$("input#senha").val("");
			}
		});
	},100);
}

function Busca() {
	$("#buscaerror").fadeOut(90);

	setTimeout( function(){
		$.post("busca.php",{ busca: $("#busca").val() }, function(data) {
			if (data=="redirect"){
				Redirect();
			} else if (data=="erro") {
				$("#buscaerror").fadeIn(90);
				$("#bodyBusca").html("");
			}else{
				$("#bodyBusca").html(data);
			}
		});
	},100);
}

function BuscaPage(_pg) {
	$("#buscaerror").fadeOut(90);

		$.post("busca.php",{ pg: _pg, busca: $("#buscah").val() }, function(data) {
			if (data=="redirect"){
				Redirect();
			} else if (data=="erro") {
				$("#buscaerror").fadeIn(90);
				$("#bodyBusca").html("");
			}else{
				$("#bodyBusca").html(data);
			}
		});

}

function LoadLogin() {
	$.post("form_login.php", function(data) {
		$("#content").html(data);
	});
}

function LoadBusca() {
	$.post("form_busca.php", function(data) {
		if( data=="redirect"){
			Redirect();
		} else {
			$("#content").html(data);
		}
	});
}

function LoadCadEnt() {
	$.post("form_cadent.php", function(data) {
		if( data=="redirect"){
			Redirect();
		} else {
			$("#content").html(data);
		}
	});
}

function PagVenda() {

	var qtd=[];
	var produto=[];
	var valor=[];
	var id_prod=[];
	var erro=false;

	$("#vendaserror").hide();

	$.each($('input#qtd'), function() {
		
		if( Number($(this).val())==0 ) {    	
			$("#vendaserror").html("Não é possível Registrar Venda com produto(s) sem Qtd.");
			$("#vendaserror").fadeIn(90);
			erro=true;
			return;
		}else{
			qtd.push($(this).val()); 
		}
    });

	if( erro ) {
		return;
	}

	$.each($('select#produto'), function() {
    	produto.push($(this).val()); 
    });

	$.each($('input#valor'), function() {
    	valor.push($(this).val()); 
    });

	$.each($('input#id_prod'), function() {
    	id_prod.push($(this).val()); 
    });

	$.post("cadprodvenda.php",{ qtd: qtd, produto: produto, valor: valor, id_prod: id_prod }, function(data) {
		if( data=="redirect"){
			Redirect();
		} else {
			$("#content").html(data);
		}
	});
}

function FechaVenda() {

	$("#vendaserror").hide();

	$.post("fechavenda.php",{	id_venda: $("#id_venda").val(), obs: $("#obs").val(), pagto: $("#pagto").val() }, function(data) {
		if( data=="redirect"){
			Redirect();
		} else {
			$("#content").html(data);
		}
	});
}

function FecharNota() {

	$("#nfproderror").hide();

	$.post("fechanf.php", function(data) {
		if( data=="redirect"){
			Redirect();
		} else if ( data.substr(0,9)=="<!--ok-->" ) {
			$("#content").html(data);
		} else {
			$("#nfproderror").html(data);
			$("#nfproderror").fadeIn(90);
		}
	});
}

function CancelarNota() {

	$.post("cancelanf.php", function(data) {
		if( data=="redirect"){
			Redirect();
		} else {
			$("#content").html(data);
		}
	});
}

function LoadCadEmp() {
	$.post("form_cademp.php", function(data) {
		if( data=="redirect"){
			Redirect();
		} else {
			$("#content").html(data);
		}
	});
}

function LoadCadProd() {
	$.post("form_cadprod.php", function(data) {
		if( data=="redirect"){
			Redirect();
		} else {
			$("#content").html(data);
		}
	});
}

function LoadCadTema() {
	$.post("form_cadtema.php", function(data) {
		if( data=="redirect"){
			Redirect();
		} else {
			$("#content").html(data);
		}
	});
}

function LoadCadPass() {
	$.post("form_cadpass.php", function(data) {
		if( data=="redirect"){
			Redirect();
		} else {
			$("#content").html(data);
		}
	});
}

function LoadCadEst() {
	$.post("form_cadest.php", function(data) {
		if( data=="redirect"){
			Redirect();
		} else {
			$("#content").html(data);
		}
	});
}

function LoadEditNF(_id_comp) {
	$.post("form_cadnf.php", { id_comp: _id_comp }, function(data) {
		if( data=="redirect"){
			Redirect();
		} else {
			$("#estnota").html(data);
		}
	});
}

function NovaNF(_id_ent) {
	$.post("cadnf.php", { id_ent: _id_ent }, function(data) {
		if( data=="redirect"){
			Redirect();
		} else  {
			LoadCadEst();
		}
	});
}

function ShowListProdEst() {

	$.post("form_listprodest.php", function(data) {

		if( data=="redirect"){
			Redirect();
		} else  {
			$("#estprod").html(data);
		}
	});
}

function DelProdEst(id_det) {
	$.post("delprodest.php", { id_det: id_det }, function(data) {
		if( data=="redirect"){
			Redirect();
		} else  {
			$("#estprod").html(data);
		}
	});
}

function EditProdEst(id_det) {
	$.post("form_prodest.php", { id_det: id_det }, function(data) {
		if( data=="redirect"){
			Redirect();
		} else  {
			$("#estprod").html(data);
		}
	});
}

function SalvarNF() {
	$("#notaerror").hide();

	if( !isDate($("#data").val()) ){
		$("#notaerror").html("Digite uma Data válida!");
		$("#notaerror").fadeIn(90);
		return;
	}

	$.post("cadnf.php", { nota: $("#nota").val(), data: $("#data").val(), obs: $("#obs").val()  }, function(data) {

		if( data=="redirect"){
			Redirect();
		} else if ( data.substr(0,9)=="<!--ok-->" ) {
			$("#estnota").html(data);
		} else {
			$("#notaerror").html(data);
			$("#notaerror").fadeIn(90);
		}
	});
}

function LoadVenda() {
	$.post("form_vendas.php", function(data) {
		if( data=="redirect"){
			Redirect();
		} else {
			$("#content").html(data);
		}
	});
}

function LoadRelProd() {
	$.post("form_relprod.php", function(data) {
		if( data=="redirect"){
			Redirect();
		} else {
			$("#content").html(data);
		}
	});
}


function LoadRelEntEst() {
	$.post("form_relentrada.php", function(data) {
		if( data=="redirect"){
			Redirect();
		} else {
			$("#content").html(data);
		}
	});
}

function EditCadEnt(id) {
	$.post("form_cadent.php", { id_ent: id}, function(data) {
		if( data=="redirect"){
			Redirect();
		} else {
			$("#content").html(data);
		}
	});
}

function MudaProdVenda() {
	$.post("mudaprodvenda.php", function(data) {
		if( data=="redirect"){
			Redirect();
		} else {
			$("#content").html(data);
		}
	});
}

function EditCadProd(id) {
	$.post("form_cadprod.php", { id_prod: id}, function(data) {
		if( data=="redirect"){
			Redirect();
		} else {
			$("#content").html(data);
		}
	});
}

function SalvaEnt() {
	var _tipo=0;

	$("#enterror").fadeOut(90);

	_tipo +=	( $('#chkuser').attr('checked') )?1:0;
	_tipo +=	( $('#chkfunc').attr('checked') )?2:0;
	_tipo +=	( $('#chkforn').attr('checked') )?4:0;
	_tipo +=	( $('#chkcli').attr('checked') )?8:0;

	var _acesso = $('[name="acesso"]:checked').val();

	if( !isDate($("#dataadm").val()) && ($('#chkfunc').attr('checked')) ){
		$("#enterror").html("Digite uma Data de Admissão válida!");
		$("#enterror").fadeIn(90);
		$("#dataadm").focus();
		return;
	}


	setTimeout(function(){
		$.post("cadent.php", { id_ent: $("#id_ent").val(), nome: $("#nome").val(), cpf_cnpj: $("#cpf").val(), cep: $("#cep").val(), end: $("#endereco").val(), bairro: $("#bairro").val(), cidade: $("#cidade").val(), uf: $("#uf").val(), telefone: $("#tel").val(), contato: $("#contato").val(), obs: $("#obs").val(), data_adm: $("#dataadm").val(), user: $("#login").val(), pass: $("#pass").val(), cpass: $("#cpass").val(), obs: $("#obs").val(), tipo: _tipo, acesso: _acesso}, function(data) {
			if( data=="redirect"){
				Redirect();
			} else if ( data.substr(0,9)=="<!--ok-->" ) {
				$("#content").html(data);
			} else {
				$("#enterror").html(data);
				$("#enterror").fadeIn(90);
			}
		});
	},100);
}

function SalvaEmp() {

	$("#emperror").fadeOut(90);

	setTimeout(function(){
		$.post("cademp.php", { empresa: $("#empresa").val(), nome_fant: $("#nome_fant").val(), cpf_cnpj: $("#cpf_cnpj").val(), distrib: $("#distrib").val(), tipo: $("#tipo").val(), resp: $("#resp").val(), end: $("#end").val(), bairro: $("#bairro").val(), cidade: $("#cidade").val(), uf: $("#uf").val(), cep: $("#cep").val(), home_page: $("#home_page").val(), email: $("#email").val(), telefones: $("#telefones").val()}, function(data) {
			if( data=="redirect"){
				Redirect();
			} else if ( data.substr(0,9)=="<!--ok-->" ) {
				$("#content").html(data);
			} else {
				$("#emperror").html(data);
				$("#emperror").fadeIn(90);
			}
		});
	},100);
}

function SalvaProd() {

	$("#proderror").fadeOut(90);

	setTimeout(function(){
		$.post("cadprod.php", { id_prod: $("#id_prod").val(), cod_barra: $("#cod_barra").val(), descr: $("#descr").val(), unidade: $("#unidade").val(), peso: $("#peso").val(), venda: $("#venda").val(), qtd_min: $("#qtd_min").val()}, function(data) {
			if( data=="redirect"){
				Redirect();
			} else if ( data.substr(0,9)=="<!--ok-->" ) {
				$("#content").html(data);
			} else {
				$("#proderror").html(data);
				$("#proderror").fadeIn(90);
			}
		});
	},100);
}

function SalvaPass() {

	$("#passerror").fadeOut(90);

	setTimeout(function(){
		$.post("cadpass.php", { newpass: $("#newpass").val(), confpass: $("#confpass").val(), oldpass: $("#oldpass").val() }, function(data) {
			if( data=="redirect"){
				Redirect();
			} else if ( data.substr(0,9)=="<!--ok-->" ) {
				$("#content").html(data);
			} else {
				$("#passerror").html(data);
				$("#passerror").fadeIn(90);
			}
		});
	},100);
}

function SalvaTema(_tema) {

	$.post("cadtema.php", { tema: _tema }, function(data) {
		if( data.substr(0,9)=="<!--ok-->" ) {
			$("#css").attr("href", "css/" + _tema + ".css");
			$("#content").html(data);
		}
	});
}

function AbrirNota() {
	$("#notaerror").fadeOut(90);

	setTimeout(function(){
		$.post("cadnota.php", { nome: $("#nome").val(), cpf_cnpj: $("#cpf_cnpj").val(), nota: $("#nota").val(), data: $("#data").val(), obs: $("#obs").val() }, function(data) {
			if( data=="redirect"){
				Redirect();
			} else if ( data.substr(0,9)=="<!--ok-->" ) {
				$("#content").html(data);
			} else {
				$("#notaerror").html(data);
				$("#notaerror").fadeIn(90);
			}
		});
	},100);
}

function WaitMsgBusca(){
	$("#msgBusca").css('left','354px');
	$("#msgBusca").css('top','251px');

	$("#msgBusca").html("<img width=\"100px\" src=\"img/LoadingBar.gif\" />");
	$("#msgBusca").show();
}

function ShowProd(id){
	WaitMsgBusca();

	$.post("show_cadprod.php", { id_prod: id, cons: true}, function(data) {
		if( data=="redirect"){
			Redirect();
		} else {
			//$("#msgBusca").hide();
			//$("#msgBusca").fadeIn(100);
			$("#msgBusca").html(data);
			$("#msgBusca").css('left','183px');
			$("#msgBusca").css('top','181px');
		}
	});

}

function SaveProdNF(){

	$("#prodesterror").hide();

	if( Number( $("#qtd").val() )==0 ){
		$("#prodesterror").html("Digite um valor para Qtd.");
		$("#prodesterror").fadeIn(90);
		$("#qtd").focus();
		return
	}

	if( $("#custo").val() =="" ){
		$("#prodesterror").html("Digite um valor para Custo.");
		$("#prodesterror").fadeIn(90);
		$("#custo").focus();
		return
	}

	if( $("#venda").val() =="" ){
		$("#prodesterror").html("Digite um valor para Venda.");
		$("#prodesterror").fadeIn(90);
		$("#venda").focus();
		return
	}

	$.post("addprodest.php", { id_prod: $("#id_prod").val(), descr: $("#descr").val(), qtd: $("#qtd").val(), custo: $("#custo").val(), venda: $("#venda").val() }, function(data) {

		if( data=="redirect"){
			Redirect();
		} else if ( data.substr(0,9)=="<!--ok-->" ) {
			$("#content").html(data);
		} else{
			$("#prodesterror").html(data);
			$("#prodesterror").fadeIn(90);
		}
	});

}

function SaveEditProdNF(){
	$("#prodesterror").hide();

	if( Number( $("#qtd").val() )==0 ){
		$("#prodesterror").html("Digite um valor para Qtd.");
		$("#prodesterror").fadeIn(90);
		$("#qtd").focus();
		return
	}

	if( $("#custo").val() =="" ){
		$("#prodesterror").html("Digite um valor para Custo.");
		$("#prodesterror").fadeIn(90);
		$("#custo").focus();
		return
	}

	if( $("#venda").val() =="" ){
		$("#prodesterror").html("Digite um valor para Venda.");
		$("#prodesterror").fadeIn(90);
		$("#venda").focus();
		return
	}

	$.post("addprodest.php", { id_det: $("#id_det").val(), descr: $("#descr").val(), qtd: $("#qtd").val(), custo: $("#custo").val(), venda: $("#venda").val() }, function(data) {

		if( data=="redirect"){
			Redirect();
		} else if ( data.substr(0,9)=="<!--ok-->" ) {
			$("#estprod").html(data);
		} else{
			$("#prodesterror").html(data);
			$("#prodesterror").fadeIn(90);
		}
	});

}

function AddProdNF(_id){
	WaitMsgBusca();
	$("#notaerror").hide();

	$.post("form_prodest.php", { id_prod: _id}, function(data) {

		if( data=="redirect"){
			Redirect();
		} else if ( data.substr(0,9)=="<!--ok-->" ) {
			//$("#msgBusca").hide();
			//$("#msgBusca").fadeIn(100);
			$("#msgBusca").html(data);
			$("#msgBusca").css('left','183px');
			$("#msgBusca").css('top','181px');
		} else{
			$("#prodesterror").html(data);
			$("#prodesterror").fadeIn(90);
		}
	});
}

function NewVenda(_cli_nome){

	$.post("newvenda.php", { cli_nome: _cli_nome}, function(data) {
		if( data=="redirect"){
			Redirect();
		} else {
			$("#content").html(data);
		}
	});
}

function ShowEnt(id){
	WaitMsgBusca();

	//setTimeout(function(){
	$.post("show_cadent.php", { id_ent: id, cons: true}, function(data) {
		if( data=="redirect"){
			Redirect();
		} else {
			//$("#msgBusca").hide();
			//$("#msgBusca").fadeIn(100);
			$("#msgBusca").html(data);
			$("#msgBusca").css('left','45px');
			$("#msgBusca").css('top','94px');
		}
	});
	//},500);

}

function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
*/
