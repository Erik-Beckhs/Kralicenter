<?php
 ob_start();

// (c) Xavier Nicolay

// Exemple de g�n�ration de devis/facture PDF



require('Pedido.php');



session_start();



$lo = $_SESSION["logo"];



require_once "../model/Configuracion.php";



      $objConf = new Configuracion();



      $query_conf = $objConf->Listar();



      $regConf = $query_conf->fetch_object();



require_once "../model/Pedido.php";



$objPedido = new Pedido();



$query_cli = $objPedido->GetClienteSucursalPedido($_GET["id"]);



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



$pdf = new PDF_Invoice( 'P', 'mm', 'A4' );

$pdf->AddPage();

$pdf->addSociete( $reg_cli->razon_social,

                  "$reg_cli->num_documento\n" .

                  "Direccion: $reg_cli->direccion\n".

                  "Telefono: $reg_cli->telefono\n" .

                  "email : $reg_cli->email ","../$reg_cli->logo","$extension");

$pdf->fact_dev( "$reg_cli->tipo_comprobante ", "$reg_cli->num_comprobante" );

$pdf->temporaire( "" );

$pdf->addDate( $reg_cli->fecha);

//$pdf->addClient("CL01");

//$pdf->addPageNumber("1");

$pdf -> TituloPDF();

$pdf->addClientAdresse($reg_cli->nombre,"Domicilio: ".$reg_cli->direccion_calle." - ".$reg_cli->direccion_departamento,$reg_cli->tipo_documento.": ".$reg_cli->num_documento,"Email: ".$reg_cli->email,"Telefono: ".$reg_cli->telefono);

//$pdf->addReglement("Soluciones Innovadoras Per� S.A.C.");

//$pdf->addEcheance("RUC","2147715777");

//$pdf->addNumTVA("Chongoyape, Jos� G�lvez 1368");

//$pdf->addReference("Devis ... du ....");

$cols=array("Nro." => 10,

             "DESCRIPCION"  => 56,

             "MARCA" => 25,

             "CODIGO" => 15,

             "COLOR"    => 20,

             "CANTIDAD"     => 20,

             "P.U."      => 23,

             "SUBTOTAL"          => 21 );

$pdf->addCols( $cols);

$cols=array( "Nro."  => "C",

             "DESCRIPCION"  => "L",

             "MARCA" => "R",

             "CODIGO" => "R",

             "COLOR"    => "L",

             "CANTIDAD"     => "C",

             "P.U."      => "R",

             "SUBTOTAL"          => "C" );

$pdf->addLineFormat( $cols);

$pdf->addLineFormat($cols);



$y    = 89;



$query_ped = $objPedido->ImprimirDetallePedido($_GET["id"]);

$i=1;

        while ($reg = $query_ped->fetch_object()) {



            $subtotal=($reg->precio_venta)*($reg->cantidad);



            $line = array( "Nro."  =>  "$i",

                           "DESCRIPCION"  => "$reg->nombre",

                           "MARCA" => "$reg->marca",

                           "CODIGO" => "$reg->numero",

                           "COLOR"    => "$reg->codigo_interno",

                           "CANTIDAD"     => "$reg->cantidad",

                           "P.U."      => "$reg->precio_venta",

                           "SUBTOTAL"          => "$subtotal");

            $size = $pdf->addLine( $y, $line );

            $y   += $size + 2;

            $i++;

        }



$query_total = $objPedido->TotalVenta($_GET["id"]);



$reg_total = $query_total->fetch_object();



require_once "../ajax/Letras.php";



 $V=new EnLetras();

 $totalc = ($reg_total->total)-(($reg_total->total*$reg_total->descuento)/100);

 $con_letra=strtoupper($V->ValorEnLetras($totalc,"Bolivianos con"));

//$pdf->addCadreTVAs("---TRES MILLONES CUATROCIENTOS CINCUENTA Y UN MIL DOSCIENTOS CUARENTA PESOS 00/100 M.N.");

$pdf->addCadreTVAs("Son: ".$con_letra);





require_once "../model/Configuracion.php";



$objConfiguracion = new Configuracion();





$query_global = $objConfiguracion->Listar();



$reg_igv = $query_global->fetch_object();



$pdf->addTVAs( $reg_total->descuento, $reg_total->total,"$reg_igv->simbolo_moneda ");

$pdf->addCadreEurosFrancs("$reg_igv->nombre_impuesto"." $reg_igv->porcentaje_impuesto%");

$pdf->Output('Reporte de Pedido','I');
   ob_end_flush();

?>
