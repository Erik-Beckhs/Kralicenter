<?php
// (c) Xavier Nicolay
// Exemple de g�n�ration de devis/facture PDF

require('Pago.php');

session_start();

$lo = $_SESSION["logo"];

require_once "../model/Configuracion.php";

      $objConf = new Configuracion();

      $query_conf = $objConf->Listar();

      $regConf = $query_conf->fetch_object();

require_once "../model/Credito.php";

$objPago = new credito();

$query_pag = $objPago->GetProveedorSucursalCompraPago($_GET["id"]);

        $reg_pag = $query_pag->fetch_object();

        $f = "";

              if ($_SESSION["superadmin"] == "S") {
                $f = $regConf->logo;
              } else {
                $f = $reg_pag->logo;
              }
$archivo = $f;
$trozos = explode(".", $archivo);
$extension = end($trozos);

$pdf = new PDF_Invoice( 'P', 'mm', 'A4' );
$pdf->AddPage();
$pdf->addSociete( $reg_pag->razon_social,
                  "$reg_pag->num_documento\n" .
                  "Direccion: $reg_pag->direccion\n".
                  "Telefono: $reg_pag->telefono\n" .
                  "email : $reg_pag->email ","../$reg_pag->logo","$extension");
$pdf->fact_dev( "$reg_pag->tipo_comprobante ", "$reg_pag->serie_comprobante" );
$pdf->temporaire( "" );
$pdf->addDate( $reg_pag->fecha);
//$pdf->addClient("CL01");
//$pdf->addPageNumber("1");
$pdf -> TituloPDF();
$pdf->addClientAdresse($reg_pag->nombre,"Domicilio: ".$reg_pag->direccion_calle." - ".$reg_pag->direccion_departamento,$reg_pag->tipo_documento.": ".$reg_pag->num_documento,"Email: ".$reg_pag->email,"Telefono: ".$reg_pag->telefono);
//$pdf->addReglement("Soluciones Innovadoras Per� S.A.C.");
//$pdf->addEcheance("RUC","2147715777");
//$pdf->addNumTVA("Chongoyape, Jos� G�lvez 1368");
//$pdf->addReference("Devis ... du ....");
//*****************SACAMOS EL VALOR DEL TOTAL PAGADO***************
$query_totalp = $objPago->GetMontoTotalcreditoC($_GET["id"]);
      $reg_totalp = $query_totalp->fetch_object();
      $totalpagado=$reg_totalp->total_pago;
  //**************************************************************
$cols=array("Nro." => 30,
            "FECHA DE PAGOS"    => 60,
            "MONTOS PAGADOS"  => 60,
            "SALDO"    => 60);
$pdf->addCols( $cols);
$cols=array( "Nro."  => "C",
             "FECHA DE PAGOS"  => "C",
             "MONTOS PAGADOS"  => "C",
             "SALDO"  => "C");
$pdf->addLineFormat( $cols);
$pdf->addLineFormat($cols);

$y    = 95;//*****POSICION DEL CONTENIDO DE LA TABLA

$query_ped = $objPago->ImprimirDetalleCobroC($_GET["id"]);
$i=1;
$saldo=0;
$sumatoria=0;
        while ($reg = $query_ped->fetch_object()) {
$totalVentaDescuento = ($reg->totalIngreso);

$saldo = ($totalVentaDescuento-$reg->total_pago)-$sumatoria; //*******CALCULAMOS EL SALDO A LA FECHA*********************************
$sumatoria=$reg->total_pago+$sumatoria;
            $line = array( "Nro."  =>  "$i",
                           "FECHA DE PAGOS"  =>  "$reg->fecha_pago",
                           "MONTOS PAGADOS"  =>  "$reg->total_pago",
                           "SALDO"  => "$saldo");

            $size = $pdf->addLine( $y, $line );
            $y   += $size + 2;
            $i++;
        }

$query_total = $objPago->TotalIngreso($_GET["id"]);
$reg_total = $query_total->fetch_object();

$query_ultimo_pago = $objPago->GetUltimoPagoC($_GET["id"]);
$reg_ultimo_pago = $query_ultimo_pago->fetch_object();

require_once "../ajax/Letras.php";

 $V=new EnLetras();


$con_letra=strtoupper($V->ValorEnLetras($reg_ultimo_pago->total_pago,"Bolivianos con"));
//$pdf->addCadreTVAs("---TRES MILLONES CUATROCIENTOS CINCUENTA Y UN MIL DOSCIENTOS CUARENTA PESOS 00/100 M.N.");
$pdf->addCadreTVAs("La suma de: ".$con_letra,$reg_ultimo_pago->total_pago,$regConf->simbolo_moneda,$reg_pag->nombre);

require_once "../model/Credito.php";

$objConfiguracion = new Configuracion();
$query_global = $objConfiguracion->Listar();
$reg_igv = $query_global->fetch_object();

$pdf->addTVAs($reg_total->total,"$reg_igv->simbolo_moneda ",$totalpagado);
$pdf->addCadreEurosFrancs("$reg_igv->nombre_impuesto"." $reg_igv->porcentaje_impuesto%");
$pdf->Output('Reporte de Pedido','I');
?>
