
<?php
 ob_start();

// (c) Xavier Nicolay

// Exemple de g�n�ration de devis/facture PDF

require('CierredeCaja.php');

session_start();



$lo = $_SESSION["logo"];



require_once "../model/Configuracion.php";



      $objConf = new Configuracion();



      $query_conf = $objConf->Listar();



      $regConf = $query_conf->fetch_object();



require_once "../model/Sucursal.php";



$objSucursal = new Sucursal();



$query_cli = $objSucursal->ListarSucursalCompleta($_SESSION['idsucursal']);



        $reg_cli = $query_cli->fetch_object();



        $f = "";



              if ($_SESSION["superadmin"] == "S") {

                $f = $regConf->logo;

              } else {

                $f = $reg_cli->logo;

              }

$archivo = $f;

$trozos = explode(".", $archivo);

$extension = end($trozos);



$pdf = new PDF_CierreCaja( 'P', 'mm', 'A4' );

$pdf->AddPage();

$pdf->addSociete( $reg_cli->razon_social,

                  "$reg_cli->num_documento\n" .

                  "Direccion: $reg_cli->direccion\n".

                  "Telefono: $reg_cli->telefono\n" .

                  "email : $reg_cli->email ","../$reg_cli->logo","$extension");






$pdf->addDate(  date("Y-m-d") );

$pdf -> TituloPDF();




$cols=array("DESCRIPCION" => 110, "MONTO" => 40, "TOTAL" => 40 );

$pdf->addCols( $cols);

$cols=array( "DESCRIPCION" => "L", "MONTO" => "R", "TOTAL" => "R");

$pdf->addLineFormat($cols);


$y    = 65;



//$query_ped = $objPedido->ImprimirDetallePedido($_GET["id"]);

// monto de apertura
require_once "../model/ingresos_arqueo.php";
$objCajaAbierta = new DocSucursal();
$montoapertura = $objCajaAbierta->MontoCajaAbierta($_SESSION["idsucursal"], $_SESSION['idusuario']);

// otros ingresos
require_once "../model/generar_arqueodb.php";
$objIngresosEgresos = new generar_arqueo();
$objOtrosIngresos = $objIngresosEgresos->get_total_ingresos($_SESSION['idusuario'], $_SESSION["idsucursal"]);
$reg = $objOtrosIngresos->fetch_object();
if (is_null($reg)) {
  $otrosingresos = 0;
} else {
  $otrosingresos = $reg->total_ingreso;
}

// otros egresos
$objOtrosIngresos = $objIngresosEgresos->get_total_salida_ma($_SESSION['idusuario'], $_SESSION["idsucursal"]);
$reg = $objOtrosIngresos->fetch_object();
if (is_null($reg)) {
  $egresos = 0;
} else {
  $egresos = $reg->total_salida;
}

// retiros
//$retiros = "0";

// total egresos
//$totalegresos = (floatval($reg->total_salida) + floatval($retiros));
$totalegresos = (floatval($reg->total_salida));

// ventas efectivo
$objVentas = $objIngresosEgresos->get_total_ventas_ingresos_tipo_pago($_SESSION['idusuario'], $_SESSION["idsucursal"],'efectivo','contado');
$reg = $objVentas->fetch_object();
if (is_null($reg)) {
  $ventaefectivo = 0;
} else {
  $ventaefectivo = $reg->total_venta;
}

$objVentas = $objIngresosEgresos->get_total_ventas_ingresos_tipo_pago($_SESSION['idusuario'], $_SESSION["idsucursal"],'deposito','contado');
$reg = $objVentas->fetch_object();
if (is_null($reg)) {
  $ventadeposito = 0;
} else {
  $ventadeposito = $reg->total_venta;
}


$objVentas = $objIngresosEgresos->get_total_ventas_ingresos_tipo_pago($_SESSION['idusuario'], $_SESSION["idsucursal"],'tarjeta','contado');
$reg = $objVentas->fetch_object();
if (is_null($reg)) {
  $ventatarjeta = 0;
} else {
  $ventatarjeta = $reg->total_venta;
}

$objVentas = $objIngresosEgresos->get_total_ventas_ingresos_tipo_pago($_SESSION['idusuario'], $_SESSION["idsucursal"],'','credito');
$reg = $objVentas->fetch_object();
if (is_null($reg)) {
  $ventaacredito = 0;
} else {
  $ventaacredito = $reg->total_venta;
}

$objVentas = $objIngresosEgresos->get_total_ventas_ingresos_credito($_SESSION['idusuario'], $_SESSION["idsucursal"]);
$reg = $objVentas->fetch_object();
if (is_null($reg)) {
  $ventapagosacredito = 0;
} else {
  $ventapagosacredito = $reg->total_recibido;
}

$ventatotal = $ventaefectivo + $ventadeposito + $ventatarjeta + $ventapagosacredito;
$totalingresos = $ventatotal + $otrosingresos;
$totalencaja = $montoapertura + $totalingresos - $totalegresos;


$montoapertura = number_format ($montoapertura,2);

$ventaefectivo = number_format ($ventaefectivo,2);
$ventatarjeta = number_format ($ventatarjeta,2);
$ventadeposito = number_format ($ventadeposito,2);
$ventaacredito = number_format ($ventaacredito,2);
$ventapagosacredito = number_format ($ventapagosacredito,2);

$ventatotal = number_format ($ventatotal,2);

$otrosingresos = number_format ($otrosingresos,2);

$totalingresos = number_format ($totalingresos,2);

$egresos = number_format ($egresos,2);

//$retiros = number_format ( $retiros,2);
$totalegresos = number_format($totalegresos,2);

$totalencaja = number_format($totalencaja,2);


$line = array( "DESCRIPCION"  => "MONTO DE APERTURA DE LA CAJA",

 "MONTO"        => "$montoapertura", "TOTAL" => "$montoapertura");

$size = $pdf->addLine( $y, $line );

$y   += $size + 6;


$line = array( "DESCRIPCION"  => "INGRESOS",

 "MONTO"        => " ", "TOTAL" => " ");

$size = $pdf->addLine( $y, $line );

$y   += $size + 2;

$line = array( "DESCRIPCION"  => "  Ventas al Contado",

 "MONTO"        => " ", "TOTAL" => " ");

$size = $pdf->addLine( $y, $line );

$y   += $size + 2;

$line = array( "DESCRIPCION"  => "     Efectivo",

 "MONTO"        => "$ventaefectivo", "TOTAL" => " ");

$size = $pdf->addLine( $y, $line );

$y   += $size + 2;

$line = array( "DESCRIPCION"  => utf8_decode("     Tarjeta de Crédito"),

 "MONTO"        => "$ventatarjeta", "TOTAL" => " ");

$size = $pdf->addLine( $y, $line );

$y   += $size + 2;

$line = array( "DESCRIPCION"  => utf8_decode("     Depósito Bancario"),

 "MONTO"        => "$ventadeposito", "TOTAL" => " ");

$size = $pdf->addLine( $y, $line );

$y   += $size + 2;

$line = array( "DESCRIPCION"  => utf8_decode("  Ventas a Crédito"),

 "MONTO"        => " ", "TOTAL" => " ");

$size = $pdf->addLine( $y, $line );

$y   += $size + 2;

$line = array( "DESCRIPCION"  => utf8_decode("     Monto Total de Ventas                                (") . $ventaacredito . ")",

 "MONTO"        => " ", "TOTAL" => " ");

$size = $pdf->addLine( $y, $line );

$y   += $size + 2;

$line = array( "DESCRIPCION"  => utf8_decode("     Pagos Recibidos Ventas a Crédito"),

 "MONTO"        => "$ventapagosacredito", "TOTAL" => " ");

$size = $pdf->addLine( $y, $line );

$y   += $size + 2;

$line = array( "DESCRIPCION"  => "  Total de Ventas",

 "MONTO"        => "$ventatotal", "TOTAL" => " ");

$size = $pdf->addLine( $y, $line );

$y   += $size + 2;

$line = array( "DESCRIPCION"  => "  Otros Ingresos",

 "MONTO"        => "$otrosingresos", "TOTAL" => " ");

$size = $pdf->addLine( $y, $line );

$y   += $size + 2;

$line = array( "DESCRIPCION"  => "TOTAL INGRESOS",

 "MONTO"        => "$totalingresos", "TOTAL" => "$totalingresos");

$size = $pdf->addLine( $y, $line );

$y   += $size + 6;

$line = array( "DESCRIPCION"  => "EGRESOS",

 "MONTO"        => " ", "TOTAL" => " ");

$size = $pdf->addLine( $y, $line );

$y   += $size + 2;

$line = array( "DESCRIPCION"  => "  Egresos",

 "MONTO"        => "$egresos", "TOTAL" => " ");

$size = $pdf->addLine( $y, $line );

$y   += $size + 2;

/*
$line = array( "DESCRIPCION"  => "  Retiros",

 "MONTO"        => "$retiros", "TOTAL" => " ");

$size = $pdf->addLine( $y, $line );

$y   += $size + 2;
*/

$line = array( "DESCRIPCION"  => "TOTAL EGRESOS",

 "MONTO"        => "$totalegresos", "TOTAL" => "$totalegresos");

$size = $pdf->addLine( $y, $line );

$y   += $size + 6;

$line = array( "DESCRIPCION"  => "TOTAL EN CAJA",

 "MONTO"        => "$totalencaja", "TOTAL" => "$totalencaja");

$size = $pdf->addLine( $y, $line );

$y   += $size + 2;

//$pdf->Output('I','Reporte Cierre de Caja.PDF');
$pdf->Output('I','Reporte Cierre de Caja.PDF');
   ob_end_flush(); 
?>

