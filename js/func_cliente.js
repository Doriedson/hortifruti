function CadCli(codigo) {

//	$("#produtoerro").hide();
//	$("#produtoerro").html("");
	if( $("#wCadCli").is(':visible') ){
		if( !confirm('Cancelar registro aberto?') ) {
			return;
		}
	}

	$.post("frm_cad_cliente.php", { codigo: codigo }, function(data) {
		refresh();
		$("#wCadCli").html(data);
		$("#wCadCli").offset({ left: 10+$('#tab_cliente').width() + $('#tab_cliente').offset().left });
		$("#wCadCli").show();
	});

	return false;
	//tipo: "$("#codigo").val()", cpf: $("#cpf").val(), nome: $("#nome").val(), prazo: $("#prazo").attr('checked'), endereco: $("#endereco").val(), bairro: $("#bairro").val(), cidade: $("#cidade").val(), telefone: $("#telefone").val(), obs: $("#obs").val(), limite: $("#limite").val()
}

function SalvaCli() {

	$.post("cliente.php", { tipo: 'cadastro', codigo: $("#codigo").val(), cpf: $("#cpf").val(), nome: $("#nome").val(), prazo: $("#prazo").attr('checked'), endereco: $("#endereco").val(), bairro: $("#bairro").val(), cidade: $("#cidade").val(), telefone: $("#telefone").val(), obs: $("#obs").val(), limite: $("#limite").val() }, function(data) {
		refresh();
		ListaCli();
		$("#wCadCli").hide();
		// $("#content").html(data);
	});

	return false;

}

function ListaCli() {

	$.post("cliente.php", { tipo: 'lista' }, function(data) {
		refresh();

		//if($("#tab_cliente tbody tr").length==0)
			$("#tab_cliente tbody").html(data);	

			//$("#tab_cliente tbody").append(data);	
		//else
		//	$("#tab_cliente tbody tr:eq(0)").before(data);

	});

}