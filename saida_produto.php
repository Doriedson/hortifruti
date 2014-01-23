<?php

include "cn.php";

$id_produto=$_POST['id_produto'];

$entrada = array();
$saida = array();
$saida2 = array();
$produto = "";
$tipo = "";
$totalC = 0;
$totalV = 0;

$sql="select * from tab_estent inner join tab_produto on tab_produto.id_produto=tab_estent.id_produto where tab_estent.id_produto=$id_produto order by tab_estent.data desc limit 1";


if ($rs=$cn->query($sql)) {
	if ( $row=$rs->fetch_assoc() ) {
	
// 		// echo "<table>";
// 		// echo "<tr><td colspan=2>" . $row['id_produto'] . " - " . $row['produto'] . "</td></tr>";
// 		// echo "<tr><td>Data:</td><td>" . date_format( date_create($row['data']), 'd/m/Y') . "</td></tr>";
// 		// echo "<tr><td>Custo/Vol:</td><td>R$ " . number_format($row['custo'],2,',','.') . "</td></tr>";
// 		// echo "<tr><td>Entrada:</td><td>" . number_format($row['vol'] * $row['qtdvol'],3,',','.') . " " . $row['tipo'] . "</td></tr>";
// 		// $tipo=$row['tipo'];

		$produto = $row['produto'];
		$tipo = $row['tipo'];

		//do {
			array_push( $entrada, array( date_format( date_create($row['data']), 'Y-m-d') , $row['vol'] * $row['qtdvol'] ) );
			$data = $row['data'];

		//} while ( $row=$rs->fetch_assoc() );

		$sql="select sum(tab_vendaitem.qtd) as qtd, tab_venda.data from tab_vendaitem inner join tab_venda on tab_venda.id_venda=tab_vendaitem.id_venda where tab_vendaitem.id_produto=$id_produto and tab_venda.data between '" . date_format( date_create($data), 'Y-m-d H:i:s') . "' and now() group by year(tab_venda.data), month(tab_venda.data), day(tab_venda.data) order by tab_venda.data";

		if ($rs=$cn->query($sql)) {
			if ( $row=$rs->fetch_assoc() ) {
				do {
					// echo "<tr><td>Saída:</td><td>" . number_format($row['qtd'],3,',','.') . " $tipo</td></tr>";
					$totalV += $row['qtd'];
					array_push( $saida, array( date_format( date_create($row['data']), 'Y-m-d'), $row['qtd']+0 ) );
					array_push( $saida2, array( date_format( date_create($row['data']), 'Y-m-d'), $totalV ) );

				} while ( $row=$rs->fetch_assoc() );
			}
		}

		array_push( $entrada, array( $saida[count($saida)-1][0] ,$entrada[0][1] ) );		
// 	// 	// echo "</table>";
	}
}

$cn->close();

$arr = array ( 'entrada' => $entrada, 'saida' => $saida, 'saida2' => $saida2, 'produto' => $produto, 'tipo' => $tipo );

echo json_encode($arr);

// <!--script>
// $(document).ready(function(){
//   var plot2 = $.jqplot ('oc_grafico', [[3,7,9,1,4,6,8,2,5],[1,5,8,3,6,9]], {
//       // Give the plot a title.
//       title: 'Plot With Options',
//       // You can specify options for all axes on the plot at once with
//       // the axesDefaults object.  Here, we're using a canvas renderer
//       // to draw the axis label which allows rotated text.
//       axesDefaults: {
//         labelRenderer: $.jqplot.CanvasAxisLabelRenderer
//       },
//       // An axes object holds options for all axes.
//       // Allowable axes are xaxis, x2axis, yaxis, y2axis, y3axis, ...
//       // Up to 9 y axes are supported.
//       axes: {
//         // options for each axis are specified in seperate option objects.
//         xaxis: {
//           label: "X Axis",
//           // Turn off "padding".  This will allow data point to lie on the
//           // edges of the grid.  Default padding is 1.2 and will keep all
//           // points inside the bounds of the grid.
//           pad: 0
//         },
//         yaxis: {
//           label: "Y Axis"
//         }
//       }
//     });
// });
// </script-->

?>