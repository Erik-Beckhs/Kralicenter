<?php

session_start();

switch ($_GET["op"]) {

  case 'SaveTicket':

  require_once "../model/Pedido.php";

  $obj=new Pedido();

      $idUsuario = $_POST["idUsuario"];
      $idSucursal = $_POST["idSucursal"];
      $tipo_comprobante =$_POST["tipo_comprobante"];
      $Fecha_emision_factura = date("d/m/Y");
      $Total_venta =$_POST["totalVenta"];

      $nombre_cliente =isset($_POST["nombre_cliente"])?$_POST["nombre_cliente"]:"";
      $Documento_cliente =isset($_POST["Documento_cliente"])?$_POST["Documento_cliente"]:"";
      //$numero_TF =isset($_POST["Numero_TF"])?$_POST["Numero_TF"]:0;
      $numero_TF = $_POST["Numero_TF"];
      $recibi =$_POST["recibi"];
      $cambio =$_POST["cambio"];
      $idclientet =$_POST["idClientet"];
      $tipo_pago =$_POST["tipo_pago"];
      $tipo_venta =$_POST["tipo_venta"];
      $descuento =$_POST["descuento"];

     $hosp = $obj->Registrar_Ticket($idUsuario, $idSucursal, $tipo_comprobante,$Fecha_emision_factura, $Total_venta,$_POST["detalle"],
     $nombre_cliente,$Documento_cliente,$numero_TF,$recibi,$cambio,$idclientet,$tipo_pago,$tipo_venta,$descuento);
             if (true) {

                  echo $hosp;
              } else {
                 echo "No se ha podido registrar el Pedido";



              }

          /*
          if($obj->Modificar($_POST["idPedido"], $idCategoria, $titulo, $descripcion, $slide, $imagen_principal)){
              echo "Pedido Modificada";
          } else {
              echo "No se ha podido modificar la Pedido";
          }*/

      break;







    case 'SaveFactura':
require_once "../CodigoControl/ControlCode.php";
    require_once "../model/Pedido.php";

    $obj= new Pedido();


        $idUsuario = $_POST["idUsuario"];
        $idSucursal = $_POST["idSucursal"];
        $tipo_comprobante =$_POST["tipo_comprobante"];
        $Fecha_emision_factura = date("d/m/Y");
        $Total_venta =$_POST["totalVenta"];

        $nombre_cliente =isset($_POST["nombre_cliente"])?$_POST["nombre_cliente"]:"";
        $Documento_cliente =$_POST["Documento_cliente"]; /// obligatorio
        $numero_TF =$_POST["Numero_TF"];
        $recibi =$_POST["recibi"];
        $cambio =$_POST["cambio"];
        $idcliente =isset($_POST["idCliente"])?$_POST["idCliente"]:"";
        $tipo_documento_cliente = isset($_POST["tipo_documento_de_cliente"])?$_POST["tipo_documento_de_cliente"]:"NIT/CI";

        // si tiene algun $valor
        date_default_timezone_set('America/La_Paz');

$recibo = $obj->Get_autorizacion_dosificacion($idSucursal);
$getad = $recibo->fetch_object();


        $Numero_Factura =$numero_TF;
        $Numero_Autorizacion= $getad->numero_autorizacion;// NUMERO AUTORIZACION Cons
        $Nit_Cliente= $Documento_cliente;// NIT
        $Fecha_emision_factura=date("Y/m/d"); //FECHA DE TRANSACCION
        $Fecha_emision_factura= 	str_replace('/','',$Fecha_emision_factura);
        $Total_Transaccion= round($Total_venta); // MONTO DE TRANSACCION
        $Llave_autorizacion = $getad->llave_dosificacion;




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





        if ($idcliente != "") {



$hosp = $obj->Registrar_Factura($idUsuario, $idSucursal, $tipo_comprobante,$Fecha_emision_factura, $Total_venta,$_POST["detalle"],
$nombre_cliente,$Documento_cliente,$numero_TF,$recibi,$cambio,$Codigo_Control,$idcliente);
      if (true) {
           echo $hosp;

       } else {
          echo "No se ha podido registrar el Pedido";



       }





  // code...
}

else {
  //esta vacio
 $Objeto = new Pedido();

// registrar nuevo clientes
$tipo_persona= "Cliente";

$idcliente = $Objeto->registrarCliente_Para_Factura($tipo_persona,$nombre_cliente,$tipo_documento_cliente,$Documento_cliente);
// recuperar id clientes




  $hosp = $obj->Registrar_Factura($idUsuario, $idSucursal, $tipo_comprobante,$Fecha_emision_factura, $Total_venta,$_POST["detalle"],
  $nombre_cliente,$Documento_cliente,$numero_TF,$recibi,$cambio,$Codigo_Control,$idcliente);
  if (true) {
       echo $hosp;

   } else {
      echo "No se ha podido registrar el Pedido";



   }






}









        break;



        // LISTAMOS LA INFORMACION QUE SE VE CUANDO ENTRAMOS AL AREA VENTAS LO PRIMERO QUE SE VE

       case "list":
        require_once "../model/Pedido.php";
        $data= Array();
        $objPedido = new Pedido();
        if (!isset($_SESSION['idsucursal']))
        {
            $_SESSION['idsucursal'] = 1;
        }
        $query_Pedido = $objPedido->Listar($_SESSION["idsucursal"]);
        $numero = 5;
        $email ="";
        $i = 1;
            while ($reg = $query_Pedido->fetch_object()) {


                $data[] = array("0"=>$i,
                    "1"=>$reg->nombre ,
                    "2"=>($reg->tipo_comprobante =="TICKET")?'<span class="badge bg-blue">TICKET</span>':(($reg->tipo_comprobante=="FACTURA")?'<span class="badge bg-aqua">FACTURA</span>':'<span class="badge bg-green">Proforma</span>'),
                    "3"=>$reg->fecha,
                    "4"=>$reg->total,
                    "5"=>($reg->estado_pedido=="A")?'<span class="badge bg-green">ACEPTADO</span>':'<span class="badge bg-red">ANULADO</span>',




           "6"=>($reg->estado_pedido=="A")?'<button class="btn btn-success" data-toggle="tooltip" title="Ver Detalle" onclick="cargarDataPedido('.$reg->idventa.',\''.$reg->tipo_comprobante.'\',\''.$numero.'\',\''.$reg->nombre.'\',\''.$reg->total.'\',\''.$email.'\')" ><i class="fa fa-eye"></i> </button>&nbsp'.
                          '<button class="btn btn-warning" data-toggle="tooltip" title="Anular Venta" onclick="cancelarPedido('.$reg->idventa.')" ><i class="fa fa-times-circle"></i> </button>&nbsp'.
                          '<a href="./Reportes/exTicket.php?id='.$reg->idventa.'" class="btn btn-primary" data-toggle="tooltip" title="Imprimir" target="blanck" ><i class="fa fa-file-text"></i> </a>':
                          '<button class="btn btn-success" data-toggle="tooltip" title="Ver Detalle" onclick="cargarDataPedido('.$reg->idventa.',\''.$reg->tipo_comprobante.'\',\''.$numero.'\',\''.$reg->nombre.'\',\''.$reg->total.'\')" ><i class="fa fa-eye"></i> </button>&nbsp'.
                          '<a href="./Reportes/exTicket.php?id='.$reg->idventa.'" class="btn btn-primary" data-toggle="tooltip" title="Imprimir" target="blanck" ><i class="fa fa-file-text"></i> </a>&nbsp;');


              $i++;
            }
            $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData"=>$data);
            echo json_encode($results);

        break;

    case "GetVenta":

            require_once "../model/Pedido.php";

            $objPedido = new Pedido();

            $idpedido = $_REQUEST["idPedido"];

            $query = $objPedido->VerVenta($idpedido);

            $reg = $query->fetch_object();

            echo json_encode($reg);

            break;

    case "GetDetalleCantStock":
        require_once "../model/Pedido.php";

        $objPedido = new Pedido();

        $query_Pedido = $objPedido->GetDetalleCantStock($_REQUEST["idPedido"]);

            while ($reg = $query_Pedido->fetch_object()) {
                $data[] = array($reg->iddetalle_ingreso,
                    $reg->stock_actual,
                    $reg->cantidad
                    );
            }
        echo json_encode($data);

        break;

    case "GetNextNumero":
        require_once "../model/Pedido.php";

        $objPedido = new Pedido();

        $query_Pedido = $objPedido->GetNextNumero($_SESSION["idsucursal"]);

        $reg = $query_Pedido->fetch_object();

        echo json_encode($reg);

        break;

    case "GetPrimerCliente":
        require_once "../model/Pedido.php";

        $objPedido = new Pedido();

        $query_Pedido = $objPedido->GetPrimerCliente();

        $reg = $query_Pedido->fetch_object();

        echo json_encode($reg);

        break;
    case "GetDetallePedido":
        require_once "../model/Pedido.php";

        $objPedido = new Pedido();

        $idPedido = $_POST["idPedido"];

        $query_prov = $objPedido->GetDetallePedido($idPedido);
$descuento = "";
        $i = 1;
            while ($reg = $query_prov->fetch_object()) {
                 echo '<tr>
                        <td>'.$reg->articulo.'</td>
                        <td>'.$reg->codigo.'</td>
                        <td>'.$reg->serie.'</td>
                        <td>'.$reg->precio_venta.'</td>
                        <td>'.$reg->cantidad.'</td>
                        <td>'.$descuento.'</td>
                       </tr>';
                 $i++;
            }

        break;
    case "GetDetalleProformaPed":
        require_once "../model/Pedido.php";

        $objVenta = new Pedido();

        $idVenta = $_POST["idVenta"];

        $query_prov = $objVenta->GetDetalleProformaPed($idVenta);

        $arreglo = array();

        while ($reg = $query_prov->fetch_object()) {
          $arreglo[] = $reg;
        }
        echo json_encode($arreglo);

        break;

    case "TraerCantidad" :
        require_once "../model/Pedido.php";

        $objPedido = new Pedido();

        $query_Pedido = $objPedido->TraerCantidad($_REQUEST["idPedido"]);

            while ($reg = $query_Pedido->fetch_object()) {
                $data[] = array($reg->iddetalle_ingreso,
                    $reg->cantidad
                    );
            }
        echo json_encode($data);

        break;

    case "CambiarEstado" :
        require_once "../model/Pedido.php";

        $obj= new Pedido();

        $idPedido = $_POST["idPedido"];
/*
        foreach($_POST["detalle"] as $indice => $valor){
            echo $valor[0]. " - ";
        }
        */

        $hosp = $obj->CambiarEstado($idPedido, $_POST["detalle"]);
                if ($hosp) {
                    echo "Venta Anulada";
                } else {
                    echo "No se ha podido Anular la Venta";
                }
        break;

    case "ProformaaFactura" :
        require_once "../model/Pedido.php";

        $obj= new Pedido();

        $idPedido = $_POST["idPedido"];

        $hosp = $obj->ProformaaFactura($idPedido);
/*
                if ($hosp) {
                    echo "Proforma Actualizada ...";
                } else {
                    echo "No se ha podido Actualizar la Proforma ...";
                }
*/
        break;






    case "EliminarPedido" :
        require_once "../model/Pedido.php";

        $obj= new Pedido();

        $idPedido = $_POST["idPedido"];

        $hosp = $obj->EliminarPedido($idPedido);
                if ($hosp) {
                    echo "Pedido Eliminado";
                } else {
                    echo "No se ha podido eliminar el Pedido";
                }
        break;





    case "listClientesP":
        require_once "../model/Pedido.php";

        $objPedido = new Pedido();

        $query_cli = $objPedido->ListarClientes();

        $i = 1;
            while ($reg = $query_cli->fetch_object()) {
                 echo '<tr>

                 <td>
            						<button type="button" class="btn btn-warning" name="optDetIngBusqueda[]"
                                data-toggle="tooltip" title="Agregar cliente"

                                onclick="AgregarCliente(\''.$reg->nombre.'\',\''.$reg->num_documento.'\',\''.$reg->idpersona.'\')" >
                                <i class="fa fa-plus" ></i> </button>

            					  </td>


                        <td>'.$i.'</td>
                        <td>'.$reg->tipo_persona.'</td>
                        <td>'.$reg->nombre.'</td>
                        <td>'.$reg->num_documento.'</td>
                        <td>'.$reg->email.'</td>
                       </tr>';
                 $i++;
            }

        break;

    case "listDetIng":
    require_once "../model/Pedido.php";
      $objPedido = new Pedido();
      $query_cli = $objPedido->ListarDetalleIngresos($_SESSION["idsucursal"]);


          //date_default_timezone_set('America/La_Paz');
          //$fecha_actual = date("Y-m-d");

      $data= Array();
      $i = 1;
          while ($reg = $query_cli->fetch_object()) {



                //$fecha_caducidad = $reg->caducidad;


                //$datetime1 = new DateTime($fecha_actual);
                //$datetime2 = new DateTime($fecha_caducidad);
                //$interval = $datetime1->diff($datetime2);
                //$resultado= $interval->format('%R%a ');

              //  if ($resultado < +30 & $resultado<= +180) {
              $stock_actual = $reg->stock_actual;
              $stock_minimo = $reg->minima;

                if ($stock_actual <= +$stock_minimo) {
                  //$ven= "Vence dentro : ";
                  //  $resultado = intval(preg_replace('/[^0-9]+/', '', $resultado), 10);
                  // falta para la expiracion mas de  30 dias
                  $mensaje= "Cantidad Baja! :";
                  $data[] = array(
                  "0"=>'
                  <script type="text/javascript">
    							function changeColor(x)

    							{

    									if(x.style.background=="rgb(247, 211, 88)")

    									{

    											x.style.background="#5cb85c";

    									}else{

    											x.style.background="#5cb85c";

    									}

    									return false;

    							}
    							</script>
                  <button type="button" style="backghround-color: #7fffd4 !important;" class="btn btn-warning" name="optDetIngBusqueda[]" data-codigo="'.$reg->codigo.'"
                  data-serie="'.$reg->serie.'" data-nombre="'.$reg->Articulo.'" data-precio-venta="'.$reg->precio_ventapublico.'"
                  data-stock-actual="'.$reg->stock_actual.'" id="'.$reg->iddetalle_ingreso.'" value="'.$reg->iddetalle_ingreso.'"
                  data-toggle="tooltip" title="Precio U"

                  onclick="AgregarPedCarrito('.$reg->iddetalle_ingreso.',\''.$reg->stock_actual.'\',\''.$reg->Articulo.'\',\''.$reg->codigo_interno.'\',\''.$reg->serie.'\',\''.$reg->precio_ventapublico.'\');changeColor(this);" >
                  <i>'.$reg->precio_ventapublico.'</i> </button>
                  
                  <button type="button" class="btn btn-warning" name="optDetIngBusqueda[]" data-codigo="'.$reg->codigo.'"
                  data-serie="'.$reg->serie.'" data-nombre="'.$reg->Articulo.'" data-precio-venta="'.$reg->precio2.'"
                  data-stock-actual="'.$reg->stock_actual.'" id="'.$reg->iddetalle_ingreso.'" value="'.$reg->iddetalle_ingreso.'"
                  data-toggle="tooltip" title="Precio C"

                  onclick="AgregarPedCarrito('.$reg->iddetalle_ingreso.',\''.$reg->stock_actual.'\',\''.$reg->Articulo.'\',\''.$reg->codigo_interno.'\',\''.$reg->serie.'\',\''.$reg->precio2.'\');changeColor(this);" >
                  <i>'.$reg->precio2.'</i> </button>
                  
                  <button type="button" class="btn btn-warning" name="optDetIngBusqueda[]" data-codigo="'.$reg->codigo.'"
                  data-serie="'.$reg->serie.'" data-nombre="'.$reg->Articulo.'" data-precio-venta="'.$reg->precio3.'"
                  data-stock-actual="'.$reg->stock_actual.'" id="'.$reg->iddetalle_ingreso.'" value="'.$reg->iddetalle_ingreso.'"
                  data-toggle="tooltip" title="Precio M"

                  onclick="AgregarPedCarrito('.$reg->iddetalle_ingreso.',\''.$reg->stock_actual.'\',\''.$reg->Articulo.'\',\''.$reg->codigo_interno.'\',\''.$reg->serie.'\',\''.$reg->precio3.'\');changeColor(this);" >
                  <i>'.$reg->precio3.'</i> </button>
                  
                  <button type="button" class="btn btn-warning" name="optDetIngBusqueda[]" data-codigo="'.$reg->codigo.'"
                  data-serie="'.$reg->serie.'" data-nombre="'.$reg->Articulo.'" data-precio-venta="'.$reg->precio4.'"
                  data-stock-actual="'.$reg->stock_actual.'" id="'.$reg->iddetalle_ingreso.'" value="'.$reg->iddetalle_ingreso.'"
                  data-toggle="tooltip" title="Precio D"

                  onclick="AgregarPedCarrito('.$reg->iddetalle_ingreso.',\''.$reg->stock_actual.'\',\''.$reg->Articulo.'\',\''.$reg->codigo_interno.'\',\''.$reg->serie.'\',\''.$reg->precio4.'\');changeColor(this);" >
                  <i>'.$reg->precio4.'</i> </button>
                  
                  <button type="button" class="btn btn-warning" name="optDetIngBusqueda[]" data-codigo="'.$reg->codigo.'"
                  data-serie="'.$reg->serie.'" data-nombre="'.$reg->Articulo.'" data-precio-venta="'.$reg->precio5.'"
                  data-stock-actual="'.$reg->stock_actual.'" id="'.$reg->iddetalle_ingreso.'" value="'.$reg->iddetalle_ingreso.'"
                  data-toggle="tooltip" title="Precio B"

                  onclick="AgregarPedCarrito('.$reg->iddetalle_ingreso.',\''.$reg->stock_actual.'\',\''.$reg->Articulo.'\',\''.$reg->codigo_interno.'\',\''.$reg->serie.'\',\''.$reg->precio5.'\');changeColor(this);" >
                  <i>'.$reg->precio5.'</i> </button>',

                  "1"=>$reg->Articulo,
                  "2"=>$reg->idarticulo,
                  "3"=>$reg->numero,
                  "4"=>$reg->codigo_interno,
                  "5"=>$reg->presentacion,
                "6"=>$reg->unidad_medida,
                  "7"=>$reg->prefijo,
                "8"=>$reg->instruccion,
                "9"=>$reg->vrestringida,
                "10"=>"<span class='rojo'>$mensaje</span><br><span class='rojo'>$reg->stock_actual</span>",

                "11"=>$reg->precio_ventapublico);
								/*		"13"=>'	<link rel="stylesheet" type="text/css" href="public/shadowbox/shadowbox.css">
											<script type="text/javascript" src="public/shadowbox/shadowbox.js"></script>
											<script type="text/javascript">
											Shadowbox.init();
											</script>
											<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=100px height=100px src="./'.$reg->imagen.'" /></a>'
	);*/
                }

                if ($stock_actual > +$stock_minimo) {
                  //$ven= "Vence dentro : ";
                  //  $resultado = intval(preg_replace('/[^0-9]+/', '', $resultado), 10);
                  // falta para la expiracion mas de  30 dias

                  $data[] = array(
                    "0"=>'
                    <script type="text/javascript">
      							function changeColor(x)

      							{

      									if(x.style.background=="rgb(247, 211, 88)")

      									{

      											x.style.background="#5cb85c";

      									}else{

      											x.style.background="#5cb85c";

      									}

      									return false;

      							}
      							</script>

                    <button type="button" style="backghround-color: #7fffd4 !important;" class="btn btn-warning" name="optDetIngBusqueda[]" data-codigo="'.$reg->codigo.'"
                    data-serie="'.$reg->serie.'" data-nombre="'.$reg->Articulo.'" data-precio-venta="'.$reg->precio_ventapublico.'"
                    data-stock-actual="'.$reg->stock_actual.'" id="'.$reg->iddetalle_ingreso.'" value="'.$reg->iddetalle_ingreso.'"
                    data-toggle="tooltip" title="Precio U"

                    onclick="AgregarPedCarrito('.$reg->iddetalle_ingreso.',\''.$reg->stock_actual.'\',\''.$reg->Articulo.'\',\''.$reg->codigo.'\',\''.$reg->serie.'\',\''.$reg->precio_ventapublico.'\');changeColor(this);" >
                    <i>'.$reg->precio_ventapublico.'</i> </button>
                    
                    <button type="button" class="btn btn-warning" name="optDetIngBusqueda[]" data-codigo="'.$reg->codigo.'"
                    data-serie="'.$reg->serie.'" data-nombre="'.$reg->Articulo.'" data-precio-venta="'.$reg->precio2.'"
                    data-stock-actual="'.$reg->stock_actual.'" id="'.$reg->iddetalle_ingreso.'" value="'.$reg->iddetalle_ingreso.'"
                    data-toggle="tooltip" title="Precio C"

                    onclick="AgregarPedCarrito('.$reg->iddetalle_ingreso.',\''.$reg->stock_actual.'\',\''.$reg->Articulo.'\',\''.$reg->codigo.'\',\''.$reg->serie.'\',\''.$reg->precio2.'\');changeColor(this);" >
                    <i>'.$reg->precio2.'</i> </button>
                    
                    <button type="button" class="btn btn-warning" name="optDetIngBusqueda[]" data-codigo="'.$reg->codigo.'"
                    data-serie="'.$reg->serie.'" data-nombre="'.$reg->Articulo.'" data-precio-venta="'.$reg->precio3.'"
                    data-stock-actual="'.$reg->stock_actual.'" id="'.$reg->iddetalle_ingreso.'" value="'.$reg->iddetalle_ingreso.'"
                    data-toggle="tooltip" title="Precio "

                    onclick="AgregarPedCarrito('.$reg->iddetalle_ingreso.',\''.$reg->stock_actual.'\',\''.$reg->Articulo.'\',\''.$reg->codigo.'\',\''.$reg->serie.'\',\''.$reg->precio3.'\');changeColor(this);" >
                    <i>'.$reg->precio3.'</i> </button>
                    
                    <button type="button" class="btn btn-warning" name="optDetIngBusqueda[]" data-codigo="'.$reg->codigo.'"
                    data-serie="'.$reg->serie.'" data-nombre="'.$reg->Articulo.'" data-precio-venta="'.$reg->precio4.'"
                    data-stock-actual="'.$reg->stock_actual.'" id="'.$reg->iddetalle_ingreso.'" value="'.$reg->iddetalle_ingreso.'"
                    data-toggle="tooltip" title="Precio D"

                    onclick="AgregarPedCarrito('.$reg->iddetalle_ingreso.',\''.$reg->stock_actual.'\',\''.$reg->Articulo.'\',\''.$reg->codigo.'\',\''.$reg->serie.'\',\''.$reg->precio4.'\');changeColor(this);" >
                    <i>'.$reg->precio4.'</i> </button>
                    
                    <button type="button" class="btn btn-warning" name="optDetIngBusqueda[]" data-codigo="'.$reg->codigo.'"
                    data-serie="'.$reg->serie.'" data-nombre="'.$reg->Articulo.'" data-precio-venta="'.$reg->precio5.'"
                    data-stock-actual="'.$reg->stock_actual.'" id="'.$reg->iddetalle_ingreso.'" value="'.$reg->iddetalle_ingreso.'"
                    data-toggle="tooltip" title="Precio B"

                    onclick="AgregarPedCarrito('.$reg->iddetalle_ingreso.',\''.$reg->stock_actual.'\',\''.$reg->Articulo.'\',\''.$reg->codigo.'\',\''.$reg->serie.'\',\''.$reg->precio5.'\');changeColor(this);" >
                    <i>'.$reg->precio5.'</i> </button>',


                    "1"=>$reg->Articulo,
                    "2"=>$reg->idarticulo,
                    "3"=>$reg->numero,
                    "4"=>$reg->codigo_interno,
                    "5"=>$reg->presentacion,
                  "6"=>$reg->unidad_medida,
                    "7"=>$reg->prefijo,

                  "8"=>$reg->instruccion,
                  "9"=>$reg->vrestringida,
                  "10"=>"<span class='verde'>$reg->stock_actual</span>",

                  "11"=>$reg->precio_ventapublico);
								/*		"13"=>'	<link rel="stylesheet" type="text/css" href="public/shadowbox/shadowbox.css">
											<script type="text/javascript" src="public/shadowbox/shadowbox.js"></script>
											<script type="text/javascript">
											Shadowbox.init();
											</script>
											<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=100px height=100px src="./'.$reg->imagen.'" /></a>'
	);*/
                }


              $i++;
          }

          $results = array(
          "sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData"=>$data);
          echo json_encode($results);
            break;

     case "listTipoDoc":

            require_once "../model/Pedido.php";

            $objPedido = new Pedido();

            $query_Categoria = $objPedido->ListarTipoDocumento($_SESSION["idsucursal"]);


            while ($reg = $query_Categoria->fetch_object()) {
                echo '<option value=' . $reg->nombre . '>' . $reg->nombre . '</option>';
            }

            break;

    case "GetTipoDocSerieNum":

            require_once "../model/Pedido.php";

            $objPedido = new Pedido();

            $nombre = $_REQUEST["nombre"];

            $query_Categoria = $objPedido->GetTipoDocSerieNum($nombre);

            $reg = $query_Categoria->fetch_object();

            echo json_encode($reg);

            break;


            case "GetPrimerIDTicket":

                    require_once "../model/Pedido.php";

                    $objPedido = new Pedido();

                    $txtSucursal = $_POST["txtIdSucursal"];

                    $query_idTicket = $objPedido->Get_id_ticket($txtSucursal);

                    echo json_encode((int)$query_idTicket);
                    //echo json_encode($query_idTicket);

                    break;

    case "GetIdPedido":

            require_once "../model/Pedido.php";

            $objPedido = new Pedido();

            $query_Categoria = $objPedido->GetIdPedido();

            $reg = $query_Categoria->fetch_object();

            echo json_encode($reg);

            break;

    case "GetTotal":

            require_once "../model/Pedido.php";

            $objPedido = new Pedido();

            $query_total = $objPedido->TotalPedido($_REQUEST["idPedido"]);

            $reg_total = $query_total->fetch_object();

            echo json_encode($reg_total);

            break;
      case "listProformas":
          require_once "../model/Pedido.php";
          $objProforma = new Pedido();
          $query_PF = $objProforma->ListarProformas($_SESSION["idsucursal"]);

          $i = 1;
              while ($reg = $query_PF->fetch_object()) {

                   echo '<tr>
                    <td><input type="radio" name="optProformaBusqueda[]" data-nombre="'.$reg->nombre.'" data-tipo_pago="' . $reg->tipo_pago . '" data-tipo_venta="' . $reg->tipo_venta . '" data-descuento="' . $reg->descuento . '" data-tipo_documento="' . $reg->tipo_documento . '" data-num_documento="' . $reg->num_documento . '" data-id_cliente="' . $reg->idcliente . '" id="'.$reg->idventa.'"value="'.$reg->idventa.'" /></td>

                          <td>'.$i.'</td>
                          <td>'.$reg->nombre.'</td>
                          <td>'.$reg->fecha.'</td>
                          <td>'.$reg->total.'</td>
                          <td>'.$reg->tipo_venta.'</td>
                          <td>'.$reg->tipo_pago.'</td>
                          <td>'.$reg->descuento.'</td>
                          <td>'.$reg->tipo_documento.'</td>
                          <td>'.$reg->num_documento.'</td>
                         </tr>';
                   $i++;
              }

          break;


}
