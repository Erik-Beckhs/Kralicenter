<?php
 ob_start();
// (c) Xavier Nicolay
// Exemple de g�n�ration de devis/facture PDF

require('Traspaso.php');

session_start();

$lo = $_SESSION["logo"];

require_once "../model/Configuracion.php";

      $objConf = new Configuracion();

      $query_conf = $objConf->Listar();

      $regConf = $query_conf->fetch_object();

require_once "../model/Traspaso.php";

$objTraspaso = new Traspaso();

$query_cli = $objTraspaso->GetSucursalTraspaso($_GET["id"]);

        $reg_cli = $query_cli->fetch_object();

        $f = "";

              if ($_SESSION["superadmin"] == "S") {
                $f = $regConf->logo;
              } else {
                $f = $reg_cli->logoso;
              }
$archivo = $f;
$trozos = explode(".", $archivo);
$extension = end($trozos);

//$pdf = new PDF_Traspaso( 'P', 'mm', 'A4' );
$pdf = new PDF_Traspaso( 'P', 'mm', 'LETTER' );
$pdf->AddPage();
$pdf->addSociete( $reg_cli->sucursalorigen,
                  "$reg_cli->numdocumentoso\n" .
                  "Direccion: $reg_cli->direccionso\n".
                  "Telefono: $reg_cli->telefonoso\n" .
                  "email : $reg_cli->emailso ","../$reg_cli->logoso","$extension",8);
$pdf->fact_dev( "TRASPASO ENTRE SUCURSALES ", "$reg_cli->idtraspaso" );
$pdf->temporaire( "" );
$pdf->addDate( $reg_cli->fecha, $reg_cli->nombreusuario);
//$pdf->addClient("CL01");
//$pdf->addPageNumber("1");

$pdf->addSociete( $reg_cli->sucursaldestino,
                  "$reg_cli->numdocumentosd\n" .
                  "Direccion: $reg_cli->direccionsd\n".
                  "Telefono: $reg_cli->telefonosd\n" .
                  "email : $reg_cli->emailsd ","../$reg_cli->logosd","$extension",38);


$cols=array("Nro." => 10,
            "CODIGO"    => 20,
             "DESCRIPCION"  => 64,
             "CODIGO" => 39,
             "MARCA" => 39,
             "CANTIDAD"     => 24 );
$pdf->addCols( $cols);
$cols=array( "Nro."  => "C",
             "CODIGO"    => "L",
             "DESCRIPCION"  => "L",
             "CODIGO" => "C",
             "MARCA" => "C",
             "CANTIDAD"     => "C" );
$pdf->addLineFormat( $cols);
$pdf->addLineFormat($cols);

$y    = 89;

$query_ped = $objTraspaso->ImprimirDetalleTraspaso($_GET["id"]);
$i=1;
        while ($reg = $query_ped->fetch_object()) {

            $line = array( "Nro."  =>  "$i",
                           "CODIGO"    => "'$reg->codigo_interno'",
                           "DESCRIPCION"  => "$reg->nombre",
                           "CODIGO" => "$reg->numero",
                           "MARCA" => "$reg->marca",
                           "CANTIDAD"     => "$reg->stock_traspaso");
            $size = $pdf->addLine( $y, $line );
            $y   += $size + 2;
            $i++;
        }

$pdf->addMotivo( $reg_cli->motivo);

require_once "../model/Configuracion.php";

$objConfiguracion = new Configuracion();

$query_global = $objConfiguracion->Listar();

$reg_igv = $query_global->fetch_object();

$pdf->Output('Reporte de Traspaso','I');
   ob_end_flush();
?>
