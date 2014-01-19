function OC(id_oc){

	$.post("compra.php", { action: 'OC', id_oc: id_oc }, function(data) {
		refresh();
		var obj = jQuery.parseJSON(data);
		addTabOC(obj);
	});

}

function GraficoES(id_produto){

	$("#oc_grafico").html("");

	$.post("saida_produto.php", { id_produto: id_produto }, function(data) {
		refresh();

		var obj = jQuery.parseJSON(data);
console.log(data);
		var plot2 = $.jqplot ('oc_grafico', [ obj.entrada, obj.saida, obj.saida2 ], {
		      // Give the plot a title.
	    	title: obj.produto,
		      //series:[{renderer:$.jqplot.BarRenderer}],
		    seriesColors: ['#5FAB78', '#DDDD00', '#FF6600'],

			series:[ 
				{
					label: 'Compras'
				},
				{
					label: 'Vendas'
				},
				{
					label: 'Vendas Acumuladas'
				}
		   //        {
		   //          // Change our line width and use a diamond shaped marker.
		   //          lineWidth:2, 
		   //          markerOptions: { style:'dimaond' }
		   //        }, 
		   //        {
		   //          // Don't show a line, just show markers.
		   //          // Make the markers 7 pixels with an 'x' style
		   //          showLine:false, 
		   //          markerOptions: { size: 7, style:"x" }
		   //        }	    
	        ],  
		      // You can specify options for all axes on the plot at once with
		      // the axesDefaults object.  Here, we're using a canvas renderer
		      // to draw the axis label which allows rotated text.
		      // axesDefaults: {
		      // 	labelRenderer: $.jqplot.CanvasAxisLabelRenderer
		      // },
		      // An axes object holds options for all axes.
		      // Allowable axes are xaxis, x2axis, yaxis, y2axis, y3axis, ...
		      // Up to 9 y axes are supported.
		    axes: {
		     	xaxis:{
		      		renderer:$.jqplot.DateAxisRenderer,
		      		tickOptions:{ formatString:'%#d&nbsp;%b' },
		      	},
		        // options for each axis are specified in seperate option objects.
		        // xaxis: {
		        //   label: "X Axis",
		        //   // Turn off "padding".  This will allow data point to lie on the
		        //   // edges of the grid.  Default padding is 1.2 and will keep all
		        //   // points inside the bounds of the grid.
		        //   pad: 0
		        // },
		        yaxis: {
		        	//label: obj.tipo,
		        	tickOptions:{ formatString:'%.3f ' + obj.tipo }
		        }
		    },
		    highlighter: {
		    	show: true,
		    	sizeAdjust: 7.5
		    },
		    cursor: {
		    	show:false
		    },
		    legend: { 
		    	show:true, 
		    	location: 'e',
		    	rendererOptions: {placement: "outsideGrid"},
		    }
		});
	});
}

function addTabOC(obj){

	if($("#tab_compra tbody tr").length==0)
		$("#tab_compra tbody").append(obj.data);	
	else
		$("#tab_compra tbody tr:eq(0)").before(obj.data);

	fnOC();

}

function cadOC() {

	$.post("compra.php", { action: 'add', descricao: $("#descricao").val(), obs: $("#obs").val(), data: $("#data").val() }, function(data) {
		refresh();
		obj = jQuery.parseJSON(data);

		abaItemOC(obj.data);

		// $("#descricao").val('');
		// $("#obs").val('');
		// fnOC();
		// $("#data").focus();
	});

	return false;
}

function listaOC() {

	var obj;

	$.post("compra.php", { action: 'lista' }, function(data) {
		refresh();
		obj = jQuery.parseJSON(data);
		$("#tab_compra tbody").append(obj.data);
		fnOC();
	});

}

function delOC(){

	var par = $(this).parent().parent(); //tr

	var id_oc = $(this).parent().attr('id');//par.children("td:nth-child(6)").children()[0];

	$.post("compra.php", { action: 'delOC', id_oc: id_oc }, function(data) {
		refresh();
		var obj = jQuery.parseJSON(data);
		par.remove();
	});

}

function editOC() {

	var par = $(this).parent().parent(); //tr

	var id_oc = $(this).parent().attr('id');//par.children("td:nth-child(6)").children()[0];

	$.post("compra.php", { action: 'editOC', id_oc: id_oc }, function(data) {
		refresh();
		var obj = jQuery.parseJSON(data);
		par.html(obj.data);
		fnOC();
	});

}

function saveOC() {

	var par = $(this).parent().parent(); //tr

	var id_oc = $(this).parent().attr('id');//par.children("td:nth-child(6)").children()[0];
	var data = par.children("td:nth-child(1)").children()[0];
	var desc = par.children("td:nth-child(2)").children()[0];
	var obs = par.children("td:nth-child(5)").children()[0];

	if( data.value=="" ) {
		data.focus();
		return;
	}

	if( !desc.validity.valid ) {
		desc.value="";
		desc.focus();
		return;
	}


	$.post("compra.php", { action: 'saveOC', id_oc: id_oc, data: data.value, desc: desc.value, obs: obs.value }, function(data) {
		refresh();
		var obj = jQuery.parseJSON(data);
		par.html(obj.data);
		fnOC();
	});

}

function cancelOC() {

	var par = $(this).parent().parent(); //tr

	var id_oc = $(this).parent().attr('id');//par.children("td:nth-child(6)").children()[0];

	$.post("compra.php", { action: 'cancelOC', id_oc: id_oc }, function(data) {
		refresh();
		var obj = jQuery.parseJSON(data);
		par.html(obj.data);
		fnOC();
	});

}

function addItemOC(frm) {

	var id_oc = frm.id.value;
	var id_produto = frm.id_produto.value;
	var tab = $(frm).parent().children("table");

	$.post("compra.php", { action: 'addItem', id_oc: id_oc, id_produto: id_produto }, function(data) {
	 	refresh();
	 	var obj = jQuery.parseJSON(data);

		if($(tab).children("tbody").children("tr").length==0)
			$(tab).children("tbody").append(obj.data);	
		else
			$(tab).children("tbody").children("tr:eq(0)").before(obj.data);

		frm.id_produto.value="";
		frm.id_produto.focus();

		// $(tab).append(obj.data);
	// 	if ( data=="<!--erro-->" ){
	// 		alert("Produto não encontrado!");
	// 		$("#id_produto").val("");
	// 	}else{
	// 		$("#content").html(data);
	// 		$("#id_produto").focus();
	// 	}
	});

	return false;
}

function itemOC() {

	// $.post("oc_item.php", { id: id }, function(data) {
	// 	refresh();
	// 	$("#content").html(data);
	// });
	var par = $(this).parent().parent(); //tr

	var id_oc = $(this).parent().attr('id');//par.children("td:nth-child(6)").children()[0];

	par.remove();

	abaItemOC(id_oc);
}

function abaItemOC(id_oc){

	$.post("compra.php", { action: 'item', id_oc: id_oc }, function(data) {
		refresh();
		var obj = jQuery.parseJSON(data);
		// $("#content").html(obj.data);
		$("#head ul").append(obj.data2);
		$("#lista").append(obj.data);

	// 	var obj = jQuery.parseJSON(data);

	// 	console.log(obj.data);
		fnOC();
		fnAba();

		$(".abas li:last div").click();      

	});

	//par.html("<td>-</td><td colspan='6'>teste</td>");
}

function closeOC(){

	var par = $(this).parent().parent();	

	var indice = par.index() + 1;

	var id_oc = $("#lista div:nth-child("+indice+") #id").val();

	OC(id_oc);

    $("#lista div:nth-child("+indice+")").remove();
    par.remove();

	$(".abas li:first div").click();        
}

function fnAba(){

	$(".aba").unbind('click');
	$(".aba").unbind('hover');

    $(".aba").click(function(){
		$(".aba").removeClass("selected");
		$(this).addClass("selected");
    	var indice = $(this).parent().index();
    	indice++;
    	$("#lista div").hide();
    	$("#lista div:nth-child("+indice+")").show();
	});
    
    $(".aba").hover(
        function(){$(this).addClass("ativa")},
        function(){$(this).removeClass("ativa")}
    );             
}

function DelItemOC(bt) {

	var par = $(bt).parent();

	var id_oci = $(par).children()[0].value;

	// console.log(par.html());

	$.post("compra.php", { action: 'delItem', id_oci: id_oci }, function(data) {
		refresh();
		// var obj = jQuery.parseJSON(data);
		par.parent().remove();
	});

}

function ChangeOCI(campo) {

	if( !campo.validity.valid ) {
		campo.placeholder="erro";
		campo.value="";
		return;
	}

	valor = campo.value;
	id = campo.id;
	id_oci = $(campo).parent().parent().children("td:nth-child(6)").children()[0].value;

	// index do campo input na tela
	// id = $("input").index(campo) + 1;
// console.log($(campo).parents("table").find("#total").html());

	$.post("compra.php", { action: 'changeOCI', id: id, valor: valor, id_oci: id_oci }, function(data) {
		refresh();

		var obj = jQuery.parseJSON(data);

	 	campo.placeholder=obj.data;
	 	campo.value='';
	});

	// return false;
}

function AddLista(bt){

	id_oc = $(bt).parent().children('#id').val();

	// TINY.box.show({url:'add_lc.php',opacity:20,topsplit:3, post: 'id=$id_oc'})

	$.post("compra.php", { action: 'loadLC', id_oc: id_oc }, function(data) {
		refresh();

		var obj = jQuery.parseJSON(data);

		TINY.box.show({html:obj.data,opacity:20,topsplit:3});

	});


	
}

function AddLC(sel) {

	TINY.box.fill("Adicionando lista, aguarde...");

	var id_oc = $(sel).parents("table").attr('id');
	var id_lc = $(sel).attr('id');

	$.post("compra.php", { action: 'addLC', id_oc: id_oc, id_lc: id_lc }, function(data) {
		refresh();

		var obj = jQuery.parseJSON(data);

		$("#oc" + id_oc + " tbody").append(obj.data);

		TINY.box.hide();
	});
}

function lancaOC(){

	var par = $(this).parent().parent();
	var id_oc = $(this).parent().attr('id');

	$.post("compra.php", { action: 'lancaOC', id_oc: id_oc }, function(data) {
		refresh();

		var obj = jQuery.parseJSON(data);

		if(obj.data=="erro"){
			TINY.box.show({html:'Verifique se não há produtos com<br />custo 0,00 ou <br />volume 0,000 ou <br />qtvvol 0,000!',opacity:20,topsplit:3});
		}else{
			par.remove();
		}

	});

}

function printOC() {

	$.post("compra.php", { action: 'printOC' }, function(data) {
		refresh();

		var obj = jQuery.parseJSON(data);
	});

}

function fnOC(){
	$(".delOC").unbind('click');
	$(".delOC").bind('click',delOC);
	$(".saveOC").unbind('click');
	$(".saveOC").bind('click',saveOC);	
	$(".editOC").unbind('click');
	$(".editOC").bind('click',editOC);	
	$(".cancelOC").unbind('click');
	$(".cancelOC").bind('click',cancelOC);	
	$(".itemOC").unbind('click');
	$(".itemOC").bind('click',itemOC);	
	$(".closeOC").unbind('click');
	$(".closeOC").bind('click',closeOC);	
	$(".lancaOC").unbind('click');
	$(".lancaOC").bind('click',lancaOC);	
	$(".printOC").unbind('click');
	$(".printOC").bind('click',printOC);	
}










//###############################################################################

function ProdutoLC() {

	$.post("lc_item.php", { id: $("#id").val(), id_produto: $("#id_produto").val() }, function(data) {
		refresh();
		if ( data=="<!--erro-->" ){
			alert("Produto não encontrado!");
			$("#id_produto").val("");
		}else{
			$("#content").html(data);
			$("#id_produto").focus();
		}
	});

	return false;
}

function InsumoLC() {

	$.post("lc_item.php", { id: $("#id").val(), cat: $("#cat").val(), produto: $("#produto").val() }, function(data) {
		refresh();
		$("#content").html(data);
	});

	return false;
}

function ItemLC(id) {

	$.post("lc_item.php", { id: id }, function(data) {
		refresh();
		$("#content").html(data);
	});

}


function DelItemLC(id_lc, id_item) {

	$.post("lc_item.php", { del: id_item, id: id_lc }, function(data) {
		refresh();
		$("#content").html(data);
	});

}

function NovaLC() {

	$.post("nova_lc.php", { nova_lc: $("#nova_lc").val() }, function(data) {
		refresh();
		ItemLC(data);
		TINY.box.hide();
	});

	return false;
}

function EditLC() {

	$.post("nova_lc.php", { descricao: $("#descricao").val(), id_lc: $("#id_lc").val() }, function(data) {
		refresh();
		$("footer").html(data);
		$("#content").load("lista_compra.php");
		TINY.box.hide();
	});

	return false;
}
