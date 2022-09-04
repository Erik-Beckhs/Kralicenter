<?php

session_start();

switch ($_GET["op"]) {


    case 'verificar':
require_once "../CodigoControl/ControlCode.php";
    require_once "../Reportes/phpqrcode/qrlib.php";



            //    $idUsuario = $_POST["idUsuario"];
      //  $idSucursal = $_POST["idSucursal"];
      //  $tipo_comprobante =$_POST["tipo_comprobante"];

      //  $recibi =$_POST["recibi"];
    //    $cambio =$_POST["cambio"];
    //    $idcliente =isset($_POST["idCliente"])?$_POST["idCliente"]:"";
    //    $tipo_documento_cliente = isset($_POST["tipo_documento_de_cliente"])?$_POST["tipo_documento_de_cliente"]:"NIT/CI";

        // si tiene algun $valor
        date_default_timezone_set('America/La_Paz');

$Numero_Autorizacion = $_POST["txt_autorizacion"];
$Numero_Factura = $_POST["txt_factura"];
$Nit_Cliente = $_POST["txt_nit"];
$Fecha_emision_factura = $_POST["txt_fecha"];
$Fecha_emision_factura= 	str_replace('/','',$Fecha_emision_factura);
$Total_Transaccion =$_POST["txt_total"];
$Llave_autorizacion = $_POST["txt_dosificacion"];


        	/*======================================Generar Codigo de autorizacion=============================*/


        	$controlCode = new controlCode(); // instaciamos nuestro objeto para acceder a nuestra funcion
        	$Codigo_Control= $controlCode->generate($Numero_Autorizacion,$Numero_Factura,$Nit_Cliente,$Fecha_emision_factura,$Total_Transaccion,$Llave_autorizacion);
        	//Recibimos 6 parametros
        	/*
        	1 Numero_Autorizacion
        	2 Numero_Factura
        	3 Nit_Cliente
        	4 Fecha_emision_factura
        	5 Total_Transaccion
        	6 Llave_autorizacion
        	*/
    //      $dir = 'temp/';

          	//Si no existe la carpeta la creamos
    //      	if (!file_exists($dir))
      //            mkdir($dir);

                  //Declaramos la ruta y nombre del archivo a generar
    //      	$filename = $dir.'test.png';

                  //Parametros de Condiguración

      //    	$tamaño = 5; //Tamaño de Pixel
      //    	$level = 'L'; //Precisión Baja
      //    	$framSize = 3; //Tamaño en blanco



//$Nit_Mi_Empresa= "dasdasda";
          //	$contenido ="$Nit_Mi_Empresa|$Numero_Factura|$Numero_Autorizacion|$Fecha_emision_factura|$Total_Transaccion|$Codigo_Control"; //Texto

                  //Enviamos los parametros a la Función para generar código QR
          //	QRcode::png($contenido, $filename, $level, $tamaño, $framSize);

                  //Mostramos la imagen generada

//$ubicacion = $dir.basename($filename);
echo $Codigo_Control ;










        break;


}
