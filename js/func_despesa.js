function saveDespesa() {

	var obj;

	var par = $(this).parent().parent(); //tr

	var data = par.children("td:nth-child(1)").children()[0];
	var setor = par.children("td:nth-child(2)").children()[0];
	var setort = setor.options[setor.selectedIndex].text;
	var desc = par.children("td:nth-child(3)").children()[0];
	var valor = par.children("td:nth-child(4)").children()[0];
	var id = par.children("td:nth-child(5)").children()[0];

	$.post("despesa.php", { action: "save", id: id.value, data: data.value, setor: setor.value, setort: setort, desc: desc.value, valor: valor.value }, function(data) {
		refresh();

		obj = jQuery.parseJSON(data);

		valor = parseFloat($("#total_despesa").text().replace(",",".")) + parseFloat(obj.valor);
		$("#total_despesa").text( valor.formatMoney(2,"",",") );

		par.html(obj.data);
		fnDespesa();
	});

}

function delDespesa() {

	var par = $(this).parent().parent(); //tr
    var id = par.children("td:nth-child(5)").children()[0];

	$.post("despesa.php", { action: "del", id: id.value }, function(data) {
		refresh();
		obj = jQuery.parseJSON(data);
		valor = parseFloat($("#total_despesa").text().replace(",",".")) + parseFloat(obj.valor);
		$("#total_despesa").text( valor.formatMoney(2,"",",") );
		par.remove();
	});

}

function editDespesa() {

	var obj;

	var par = $(this).parent().parent(); //tr
    var id = par.children("td:nth-child(5)").children()[0];

	$.post("despesa.php", { action: "edit", id: id.value }, function(data) {
		refresh();

		obj = jQuery.parseJSON(data);

		valor = parseFloat($("#total_despesa").text().replace(",",".")) + parseFloat(obj.valor);
		$("#total_despesa").text( valor.formatMoney(2,"",",") );

		par.html(obj.data);
		fnDespesa();
	});
	
}

function addDespesa(data){

	var obj;

	obj = jQuery.parseJSON(data);
	
	valor = parseFloat($("#total_despesa").text().replace(",",".")) + parseFloat(obj.valor);
	$("#total_despesa").text( valor.formatMoney(2,"",",") );

	if($("#tab_despesa tbody tr").length==0)
		$("#tab_despesa tbody").append(obj.data);	
	else
		$("#tab_despesa tbody tr:eq(0)").before(obj.data);

	fnDespesa();
}

function cadDespesa(frm) {

	var data = frm.data.value;
	var setor = frm.setor.value;
	var setort = frm.setor.options[frm.setor.selectedIndex].text;
	var desc = frm.desc.value;
	var valor = frm.valor.value;

	var regex = /^[A-Za-z0-9 ]{3,}$/;

	if(!regex.test(desc)){
		frm.desc.focus();
		return false;
	}

	$.post("despesa.php", { action:"add", data: data, setor: setor, setort: setort, desc: desc, valor: valor }, function(data) {
		refresh();
// par.html(data);
		addDespesa(data);
		frm.valor.value="";
		frm.desc.value="";
		frm.data.focus();
//newDespesa();
	});

	return false;

}

function searchDespesa(frm,tipo){

	var obj,valor;
	// frm.submit.disabled=true;

	if(tipo=='desc'){
		desc=frm.desc.value;

		$.post("despesa.php", { action: "sdesc", desc: desc }, function(data) {
			refresh();
			obj = jQuery.parseJSON(data);
			valor = parseFloat(obj.valor);
			$("#tab_despesa tbody").html(obj.data);
			$("#tab_despesa tfoot td:nth-child(2)").html("R$ " + valor.formatMoney(2,"",",") );
			$("#err_despesa").val(obj.erro);
			fnDespesa();
			frm.desc.select();
		});
	}else if(tipo=='data'){
		data=frm.data.value;

		$.post("despesa.php", { action: "sdata", data: data }, function(data) {
			refresh();
			obj = jQuery.parseJSON(data);
			valor = parseFloat(obj.valor);
			$("#tab_despesa tbody").html(obj.data);
			$("#total_despesa").text( valor.formatMoney(2,"",",") );
			$("#err_despesa").text(obj.erro);
			fnDespesa();
			frm.data.select();
		});
	}

	return false;
}

function fnDespesa(){
	$(".delDespesa").unbind('click');
	$(".delDespesa").bind('click',delDespesa);
	$(".editDespesa").unbind('click');
	$(".editDespesa").bind('click',editDespesa);	
	$(".saveDespesa").unbind('click');
	$(".saveDespesa").bind('click',saveDespesa);	
}