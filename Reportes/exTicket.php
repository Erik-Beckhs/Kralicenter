<?php 
error_reporting (0);
date_default_timezone_set("America/La_Paz"); 
?>

<script type="text/javascript">
/*===============================ATAJO TECLADO ===========================*/


	var eventoControlado = false;

window.onload = function() { document.onkeypress = mostrarInformacionCaracter;

document.onkeyup = mostrarInformacionTecla; }




function mostrarInformacionCaracter(evObject) {

                var msg = ''; var elCaracter = String.fromCharCode(evObject.which);

                if (evObject.which!=0 && evObject.which!=13) {

                msg = 'Tecla pulsada: ' + elCaracter;

                control.innerHTML += msg + '-----------------------------<br/>'; }

                else { msg = 'Pulsada tecla especial';

                control.innerHTML += msg + '-----------------------------<br/>';}

                eventoControlado=true;

}



function mostrarInformacionTecla(evObject) {

                var msg = ''; var teclaPulsada = evObject.keyCode;



                eventoControlado = false;
  if(teclaPulsada == 112){

printPantalla();

  }


}

</script>


<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link href="../public/css/ticket.css" rel="stylesheet" type="text/css">
<script>
    function printPantalla()
{
   document.getElementById('cuerpoPagina').style.marginRight  = "0";
   document.getElementById('cuerpoPagina').style.marginTop = "1";
   document.getElementById('cuerpoPagina').style.marginLeft = "1";
   document.getElementById('cuerpoPagina').style.marginBottom = "0";
   document.getElementById('botonPrint').style.display = "none";
   window.print();
}
</script>
</head>
<body id="cuerpoPagina">
<?php
require_once "../model/Pedido.php";
// recuperamos la informacion basica para el TICKET

$objPedido = new Pedido();
$query_cli = $objPedido->Recuperar_informacion_tf($_GET["id"]);
$rp = $query_cli->fetch_object();
$razon_social =$rp->razon_social;
$direccion =$rp->direccion;
$telefono = $rp->telefono;
$nit_sucursal = $rp->nit_sucursal;
$num_factura = $rp->num_factura;
$telefono = $rp->telefono;
$nombre_cliente = $rp->nombre_cliente;

$query_emp = $objPedido->GetEmpleadoPedido($_GET["id"]);
$response_emp = $query_emp->fetch_object();
$nombre_empleado = $response_emp->apellidos;
/// aqui listamos los productos vendidos

?>


<div class="zona_impresion">
        <!-- codigo imprimir -->
<br>
<table border="0" align="center" width="300px">
    <tr>
        <td align="center">
        .::<strong> <?php echo $razon_social; ?></strong>::.<br>
        <?php echo $direccion; ?><br>
        </td>
    </tr>
    <tr>
        <td align="center">Tel: <?php echo $telefono; ?></td>
    </tr>
    <tr>
        <td align="center"><strong>Cliente:</strong> <?php echo $nombre_cliente; ?></td>
    </tr>
    <tr>
        <!--Y-m-d-g:i a-->
        <td align="center"><?php echo "Fecha/Hora: ".date("d/m/Y H:i:s"); ?></td>
    </tr>
    <tr>
      <td align="center"><strong>NOTA DE ENTREGA</strong></td>
      <td align="rigth">Nº <?php echo $num_factura; ?></td>
</tr>


  </table>
<br>
<table border="0" align="center" width="300px">
    <tr>
        <td>CANT.</td>
        <td>DESCRIPCIÓN</td>
        <td>P/U</td>
        <td align="right">IMPORTE</td>
    </tr>
    <tr>
      <td colspan="4">==========================================</td>
    </tr>
    <?php
    $query_ped = $objPedido->ImprimirDetallePedido($_GET["id"]);

        while ($reg = $query_ped->fetch_object()) {
        echo "<tr>";
        echo "<td>".$reg->cantidad."</td>";
        echo "<td>".$reg->nombre."</td>";
        echo "<td align='right'>".$reg->precio_venta."</td>";
        $importe = $reg->cantidad * $reg->precio_venta;
        echo "<td align='right'>$importe</td>";
        echo "</tr>";
        $cantidad+=$reg->cantidad;
				$total = $reg->total;
    }
    ?>
		<tr>
			<td colspan="4">==========================================</td>
		</tr>
    <tr>
    <td>&nbsp;</td>
    <td align="right"><b>TOTAL:</b></td>
    <td  align="right"><b><?php echo "Bs"?>  <?php echo $total;  ?></b></td>
    </tr>
    <tr>
      <td colspan="4">Usuario: <?php echo $nombre_empleado ?></td>
    </tr>
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" align="center">¡Gracias por su compra!</td>
    </tr>
    <tr>
      <td colspan="4" align="center"></td>
    </tr>
    <tr>
      <!--<td colspan="3" align="center">Bolivia</td>-->
    </tr>

</table>
<br>
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

<p>

<div style="margin-left:245px;"><a href="#" id="botonPrint" onClick="printPantalla();"><img src="../img/printer.png" border="0" style="cursor:pointer" title="Imprimir"></a></div>
</body>
</html>
