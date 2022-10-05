<?php

	session_start();

	require_once "../model/ConsultasVentas.php";

	$objCategoria = new ConsultasVentas();

	switch ($_GET["op"]) {


		          case "libro_ventas":

		               $fecha_desde = $_REQUEST["fecha_desde"];
		               $fecha_hasta = $_REQUEST["fecha_hasta"];
		               $idsucursal = $_REQUEST["idsucursal"];
		               $data = Array();
		               $query_Tipo = $objCategoria->listas_libro_ventas($idsucursal, $fecha_desde, $fecha_hasta);


		               while ($reg = $query_Tipo->fetch_object()) {
$conta =1;
$Bimporte_ice=0;
$Cexportaciones_exentas=0;
$Dventas_tasa0= 0;

$Fdescuentos_bonificaciones= 0;


$total =$reg->total;
$ESubtotal = $total-$Bimporte_ice-$Cexportaciones_exentas-$Dventas_tasa0;
$Gimporte_base_debito_fiscal = $ESubtotal-$Fdescuentos_bonificaciones;
$Hdebito_fiscal =$Gimporte_base_debito_fiscal *13/100;

		                    $data[] = array(
		                         "0"=>'3',
		                         "1"=>$conta,
		                         "2"=>$reg->fecha_emision,
		                         "3"=>$reg->numero_factura,
		                         "4"=>$reg->numero_autorizacion,
		                         "5"=>$reg->estado,
		                         "6"=>$reg->nit_cliente,
		                         "7"=>$reg->nombre,
		                         "8"=>$total,
		                         "9"=>$Bimporte_ice,
		                         "10"=>$Cexportaciones_exentas,
														 "11"=>$Dventas_tasa0,
														 "12"=>$ESubtotal,
														 "13"=>$Fdescuentos_bonificaciones,
														 "14"=>$Gimporte_base_debito_fiscal,
														 "15"=>$Hdebito_fiscal,
														 "16"=>$reg->codigo_control
		                    );
												$conta=$conta+1;
		               }
		               $results = array(
		               "sEcho" => 1,
		                "iTotalRecords" => count($data),
		               "iTotalDisplayRecords" => count($data),
		               "aaData"=>$data);
		               echo json_encode($results);

		               break;


          case "listVentasFechas":

               $fecha_desde = $_REQUEST["fecha_desde"];
               $fecha_hasta = $_REQUEST["fecha_hasta"];
               $idsucursal = $_REQUEST["idsucursal"];
               $data = Array();
               $query_Tipo = $objCategoria->ListarVentasFechas($idsucursal, $fecha_desde, $fecha_hasta);

               while ($reg = $query_Tipo->fetch_object()) {

                    $data[] = array(
                         "0"=>$reg->fecha,
                         "1"=>$reg->sucursal,
                         "2"=>$reg->empleado,
                         "3"=>$reg->cliente,
                         "4"=>$reg->comprobante,

                         "5"=>$reg->impuesto,
                         "6"=>$reg->subtotal,
                         "7"=>$reg->totalimpuesto,
                         "8"=>$reg->total,
                         "9"=>'<button class="btn btn-success" data-toggle="tooltip" title="Ver Detalle" onclick="cargarDataPedido('.$reg->idventa.',\''.$reg->tipo_venta.'\',\''.$reg->numero.'\',\''.$reg->cliente.'\',\''.$reg->total.'\')" ><i class="fa fa-eye"></i> </button>&nbsp'.
                    '<a href="./Reportes/exVenta.php?id='.$reg->idventa.'" class="btn btn-primary" data-toggle="tooltip" title="Imprimir" target="blanck" ><i class="fa fa-file-text"></i> </a>&nbsp;'
                    );
               }
               $results = array(
               "sEcho" => 1,
                "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);

               break;

          case "listVentasDetalladas":

               $fecha_desde = $_REQUEST["fecha_desde"];
               $fecha_hasta = $_REQUEST["fecha_hasta"];
               $idsucursal = $_REQUEST["idsucursal"];
               $data = Array();
               $query_Tipo = $objCategoria->ListarVentasDetalladas($idsucursal, $fecha_desde, $fecha_hasta);
                $total_venta =0;
             
              
               while ($reg = $query_Tipo->fetch_object()) {
                 $precio_venta += $reg->total;
                    $data[] = array(
                         "0"=>$reg->fecha,
                         "1"=>$reg->sucursal,
                         "2"=>$reg->empleado,
                         "3"=>$reg->cliente,
                         "4"=>$reg->comprobante,

                         "5"=>$reg->numero,
                         "6"=>$reg->impuesto,
                         "7"=>$reg->articulo,


                         "8"=>$reg->cantidad,
                         "9"=>$reg->precio_venta,
                         "10"=>$reg->descuento,
                         "11"=>$reg->total,
                          "12"=>round($precio_venta,2)
                    );
               }

               $results = array(
                "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);

               break;

          case "listVentasPendientes":

               $fecha_desde = $_REQUEST["fecha_desde"];
               $fecha_hasta = $_REQUEST["fecha_hasta"];
               $idsucursal = $_REQUEST["idsucursal"];
               $data = Array();
               $query_Tipo = $objCategoria->ListarVentasPendientes($idsucursal, $fecha_desde, $fecha_hasta);

               while ($reg = $query_Tipo->fetch_object()) {

                    $data[] = array(
                         "0"=>$reg->fecha,
                         "1"=>$reg->sucursal,
                         "2"=>$reg->empleado,
                         "3"=>$reg->cliente,
                         "4"=>$reg->comprobante,
                         "5"=>$reg->serie,
                         "6"=>$reg->numero,
                         "7"=>$reg->impuesto,
                         "8"=>$reg->subtotal,
                         "9"=>$reg->totalimpuesto,
                         "10"=>$reg->totalpagar,
                         "11"=>$reg->totalpagado,
                         "12"=>$reg->totaldeuda
                    );
               }

               $results = array(
                "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);

               break;

          case "listVentasContado":

               $fecha_desde = $_REQUEST["fecha_desde"];
               $fecha_hasta = $_REQUEST["fecha_hasta"];
               $idsucursal = $_REQUEST["idsucursal"];
               $data = Array();
               $query_Tipo = $objCategoria->ListarVentasContado($idsucursal, $fecha_desde, $fecha_hasta);

               while ($reg = $query_Tipo->fetch_object()) {

                    $data[] = array(
                         "0"=>$reg->fecha,
                         "1"=>$reg->sucursal,
                         "2"=>$reg->empleado,
                         "3"=>$reg->cliente,
                         "4"=>$reg->comprobante,
                         "5"=>$reg->tipo_pago,
												 "6"=>$reg->tipo_venta,
                         "7"=>$reg->numero,
                         "8"=>$reg->impuesto,
                         "9"=>$reg->subtotal,
                         "10"=>$reg->totalimpuesto,
                         "11"=>$reg->total
                    );
               }
               $results = array(
                "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);

               break;

          case "listVentascredito":

               $fecha_desde = $_REQUEST["fecha_desde"];
               $fecha_hasta = $_REQUEST["fecha_hasta"];
               $idsucursal = $_REQUEST["idsucursal"];
               $data = Array();
               $query_Tipo = $objCategoria->ListarVentascredito($idsucursal, $fecha_desde, $fecha_hasta);

               while ($reg = $query_Tipo->fetch_object()) {

                    $data[] = array(
                         "0"=>$reg->fecha,
                         "1"=>$reg->sucursal,
                         "2"=>$reg->empleado,
                         "3"=>$reg->cliente,
                         "4"=>$reg->comprobante,
                         "5"=>$reg->tipo_venta,
                         "6"=>$reg->numero,
                         "7"=>$reg->impuesto,
                         "8"=>$reg->subtotal,
                         "9"=>$reg->totalimpuesto,
                         "10"=>$reg->totalpagar,
                         "11"=>$reg->totalpagado,
                         "12"=>$reg->totaldeuda
                    );
               }

                $results = array(
                "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);

               break;

          case "listVentasCliente":

               $idCliente = $_REQUEST["idCliente"];
               $fecha_desde = $_REQUEST["fecha_desde"];
               $fecha_hasta = $_REQUEST["fecha_hasta"];
               $idsucursal = $_REQUEST["idsucursal"];
               $data= Array();
               $query_Tipo = $objCategoria->ListarVentasCliente($idsucursal, $idCliente, $fecha_desde, $fecha_hasta);

               while ($reg = $query_Tipo->fetch_object()) {

                    $data[] = array(
                         "0"=>$reg->fecha,
                         "1"=>$reg->sucursal,
                         "2"=>$reg->empleado,
                         "3"=>$reg->cliente,
                         "4"=>$reg->comprobante,
                         "5"=>$reg->numero,
												 "6"=>$reg->tipo_pago,
												 "7"=>$reg->tipo_venta,
                         "8"=>$reg->impuesto,
                         "9"=>$reg->subtotal,
                         "10"=>$reg->totalimpuesto,
                         "11"=>$reg->total
                    );
               }

               $results = array(
                "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);

               break;

          case "listComprasDetProveedor":

               $idProveedor = $_REQUEST["idProveedor"];
               $fecha_desde = $_REQUEST["fecha_desde"];
               $fecha_hasta = $_REQUEST["fecha_hasta"];
               $idsucursal = $_REQUEST["idsucursal"];
               $data= Array();
               $query_Tipo = $objCategoria->ListarComprasDetProveedor($idsucursal, $idProveedor, $fecha_desde, $fecha_hasta);

               while ($reg = $query_Tipo->fetch_object()) {

                    $data[] = array(
                         "1"=>$reg->fecha,
                         "2"=>$reg->sucursal,
                         "3"=>$reg->empleado,
                         "4"=>$reg->proveedor,
                         "5"=>$reg->comprobante,
                         "6"=>$reg->serie,
                         "7"=>$reg->numero,
                         "8"=>$reg->impuesto,
                         "9"=>$reg->articulo,
                         "10"=>$reg->codigo,
                         "11"=>$reg->serie,
                         "12"=>$reg->stock_ingreso,
                         "13"=>$reg->stock_actual,
                         "14"=>$reg->stock_vendido,
                         "15"=>$reg->precio_compra,
                         "16"=>$reg->precio_ventapublico,
                         "17"=>$reg->precio_ventadistribuidor
                    );
               }

               $results = array(
                "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);

               break;

          case "listVentasEmpleado":

               $fecha_desde = $_REQUEST["fecha_desde"];
               $fecha_hasta = $_REQUEST["fecha_hasta"];
               $idsucursal = $_REQUEST["idsucursal"];
               $idempleado = $_REQUEST["idempleado"];
               $data= Array();
               //$query_Tipo = $objCategoria->ListarVentasEmpleado($idsucursal, $_SESSION["idempleado"], $fecha_desde, $fecha_hasta);
               $query_Tipo = $objCategoria->ListarVentasEmpleado($idsucursal, $idempleado, $fecha_desde, $fecha_hasta);
               while ($reg = $query_Tipo->fetch_object()) {
                    $data[] = array(
                         "1"=>$reg->fecha,
                         "2"=>$reg->sucursal,
                         "3"=>$reg->empleado,
                         "4"=>$reg->cliente,
                         "5"=>$reg->comprobante,
                         "6"=>$reg->numero,
					"7"=>$reg->tipo_pago,
                         "8"=>$reg->impuesto,
                         "9"=>$reg->subtotal,
                         "10"=>$reg->totalimpuesto,
                         "11"=>$reg->total
                   );
               }
               $results = array(
                "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);
               break;

          case "listVentasEmpleadoDet":

               $fecha_desde = $_REQUEST["fecha_desde"];
               $fecha_hasta = $_REQUEST["fecha_hasta"];
               $idsucursal = $_REQUEST["idsucursal"];
               $data= Array();
               $query_Tipo = $objCategoria->ListarVentasEmpleadoDet($idsucursal, $fecha_desde, $fecha_hasta);

               while ($reg = $query_Tipo->fetch_object()) {

                    $data[] = array(
                         "0"=>$reg->fecha,
                         "1"=>$reg->sucursal,
                         "2"=>$reg->empleado,
                         "3"=>$reg->cliente,
                         "4"=>$reg->comprobante,
                         "5"=>$reg->numero,
					"6"=>$reg->tipo_pago,
					"7"=>$reg->tipo_venta,
                         "8"=>$reg->articulo,
                         "9"=>$reg->Recibi,
                         "10"=>$reg->Cambio,
                         "11"=>$reg->cantidad,
                         "12"=>$reg->precio_venta,
                         "13"=>$reg->descuento,
                         "14"=>$reg->total
                    );
               }

               $results = array(
                "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);

               break;
               case "totalVentas":

                    $fecha_desde = $_REQUEST["fecha_desde"];
                    $fecha_hasta = $_REQUEST["fecha_hasta"];
                    $idsucursal = $_REQUEST["idsucursal"];
                    //$data= Array();
                    $data='';
                    $query_Tipo = $objCategoria->totalVentaBs($idsucursal, $fecha_desde, $fecha_hasta);
     
                    while ($reg = $query_Tipo->fetch_object()) {
     
                         // $data[] = array(
                         //      "0"=>$reg->total
                         // );
                         $data=$reg->total;
                    }

                    echo json_encode($data);
     
                    break;

	}
