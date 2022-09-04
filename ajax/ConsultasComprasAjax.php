<?php

	session_start();

	require_once "../model/ConsultasCompras.php";

	$objCategoria = new ConsultasCompras();

	switch ($_GET["op"]) {


		// libro de libro_ventas

		case "libro_ventas":


		               $fecha_desde = $_REQUEST["fecha_desde"];
		               $fecha_hasta = $_REQUEST["fecha_hasta"];
		               $idsucursal = $_REQUEST["idsucursal"];
		               $data = Array();
		               $query_Tipo = $objCategoria->libro_ventas($idsucursal, $fecha_desde, $fecha_hasta);
									 $contador = 1;

		               while ($reg = $query_Tipo->fetch_object()) {


$numero_dui= 0;
$total= $reg->total;
$importe_no_sujeto_cf = 0;
$subtotal = $total-$importe_no_sujeto_cf;
$descuento= 0;
$importe_base_cf = $subtotal-$descuento;
$credito_fiscal = $importe_base_cf * 13/100;
$tipo_compra= 1;

		                    $data[] = array(
		                         "0"=>"1",
		                         "1"=>$contador,
		                         "2"=>$reg->fecha,
		                         "3"=>$reg->nit_provedor,
		                         "4"=>$reg->razon_social,
		                         "5"=>$reg->numero_factura,
		                         "6"=>$numero_dui,
		                         "7"=>$reg->numero_autorizacion,
		                         "8"=>$total,
		                         "9"=>$importe_no_sujeto_cf,
		                         "10"=>$subtotal,
														   "11"=>$descuento,
															   "12"=>$importe_base_cf,
																   "13"=>$credito_fiscal,
																	   "14"=>$reg->codigo_control,
																		 "15"=>$tipo_compra

		                    );

												$contador = $contador +1;
		               }

		               $results = array(
		               "sEcho" => 1,
		               "iTotalRecords" => count($data),
		               "iTotalDisplayRecords" => count($data),
		               "aaData"=>$data);
		               echo json_encode($results);

		               break;


		case "listKardexValorizado":
               if ( !isset($_REQUEST['idsucursal'])) $_REQUEST['idsucursal'] = 1;
               $idsucursal = $_REQUEST["idsucursal"];

			$query_Tipo = $objCategoria->ListarKardexValorizado($idsucursal);
               $data = Array();
			while ($reg = $query_Tipo->fetch_object()) {
				$data[] = array(
                    "0"=>$reg->sucursal,
                    "1"=>$reg->articulo,
                    "2"=>$reg->categoria,
                    "3"=>$reg->unidad,
                    "4"=>$reg->totalingreso,
                    "5"=>$reg->valorizadoingreso,
                    "6"=>$reg->totalstock,
                    "7"=>$reg->valorizadostock,
                    "8"=>$reg->totalventa,
                    "9"=>$reg->valorizadoventa,
                    "10"=>$reg->utilidadvalorizada
                    );
			}
               $results = array(
               "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);

			break;

		case "listStockArticulos":
           if ( !isset($_REQUEST['idsucursal'])) $_REQUEST['idsucursal'] = 1;
          $idsucursal = $_REQUEST["idsucursal"];
          $data =Array();

			$query_Tipo = $objCategoria->ListarStockArticulos($idsucursal);

			while ($reg = $query_Tipo->fetch_object()) {

				$data[] = array(
                    "0"=>$reg->sucursal,
                    "1"=>$reg->articulo,
                    "2"=>$reg->categoria,
                    "3"=>$reg->codigo,
                    "4"=>$reg->serie,
                    "5"=>"<p style='background-color:#49a6b180;'>$reg->totalingreso</p>",
                    "6"=>$reg->valorizadoingreso,
                    "7"=>"<p style='background-color:#49a6b180;'>$reg->totalstock</p>",
                    "8"=>$reg->valorizadostock,
                    "9"=>$reg->totalventa,
                    "10"=>$reg->valorizadoventa,
                    "11"=>$reg->utilidadvalorizada
                    );
			}
               $results = array(
               "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);

			break;

          case "listComprasFechas":

               $fecha_desde = $_REQUEST["fecha_desde"];
               $fecha_hasta = $_REQUEST["fecha_hasta"];
               $idsucursal = $_REQUEST["idsucursal"];
               $data = Array();
               $query_Tipo = $objCategoria->ListarComprasFechas($idsucursal, $fecha_desde, $fecha_hasta);

               while ($reg = $query_Tipo->fetch_object()) {

                    $data[] = array(
                         "0"=>$reg->fecha,
                         "1"=>$reg->sucursal,
                         "2"=>$reg->empleado,
                         "3"=>$reg->proveedor,
                         "4"=>$reg->comprobante,

                         "5"=>$reg->impuesto,
                         "6"=>$reg->subtotal,
                         "7"=>$reg->totalimpuesto,
                         "8"=>$reg->total,
                         "9"=>'<button class="btn btn-success" data-toggle="tooltip" title="Ver Detalle" onclick="cargarDataIngreso('.$reg->idingreso.',\''.$reg->serie.'\',\''.$reg->numero.'\',\''.$reg->impuesto.'\',\''.$reg->total.'\',\''.$reg->idingreso.'\',\''.$reg->proveedor.'\',\''.$reg->comprobante.'\')" ><i class="fa fa-eye"></i> </button>&nbsp'.
                    '<a href="./Reportes/exIngreso.php?id='.$reg->idingreso.'" class="btn btn-primary" data-toggle="tooltip" title="Imprimir" target="blanck" ><i class="fa fa-file-text"></i> </a>'
                    );
               }

               $results = array(
               "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);

               break;

          case "listComprasDetalladas":

               $fecha_desde = $_REQUEST["fecha_desde"];
               $fecha_hasta = $_REQUEST["fecha_hasta"];
               //if ( !isset($_REQUEST['idsucursal'])) $_REQUEST['idsucursal'] = 1;
               $idsucursal = $_REQUEST["idsucursal"];
               $data = Array();
               $query_Tipo = $objCategoria->ListarComprasDetalladas($idsucursal, $fecha_desde, $fecha_hasta);

               while ($reg = $query_Tipo->fetch_object()) {

                    $data[] = array(
                         "0"=>$reg->fecha,
                         "1"=>$reg->sucursal,
                         "2"=>$reg->empleado,
                         "3"=>$reg->proveedor,
                         "4"=>$reg->comprobante,

                         "5"=>$reg->impuesto,
                         "6"=>$reg->articulo,

                         "7"=>$reg->stock_ingreso,
                         "8"=>$reg->stock_actual,
                         "9"=>$reg->stock_vendido,
                         "10"=>$reg->precio_compra,
                         "11"=>$reg->precio_ventapublico

                    );
               }

               $results = array(
               "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);

               break;

          case "listComprasProveedor":

               $idProveedor = $_REQUEST["idProveedor"];
               $fecha_desde = $_REQUEST["fecha_desde"];
               $fecha_hasta = $_REQUEST["fecha_hasta"];
               // if ( !isset($_REQUEST['idsucursal'])) $_REQUEST['idsucursal'] = 1;
               $idsucursal = $_REQUEST["idsucursal"];
               $data = Array();

               $query_Tipo = $objCategoria->ListarComprasProveedor($idsucursal, $idProveedor, $fecha_desde, $fecha_hasta);

               while ($reg = $query_Tipo->fetch_object()) {

                    $data[] = array(
                         "0"=>$reg->fecha,
                         "1"=>$reg->sucursal,
                         "2"=>$reg->empleado,
                         "3"=>$reg->proveedor,
                         "4"=>$reg->comprobante,

                         "5"=>$reg->impuesto,
                         "6"=>$reg->subtotal,
                         "7"=>$reg->totalimpuesto,
                         "8"=>$reg->total
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
               // if ( !isset($_REQUEST['idsucursal'])) $_REQUEST['idsucursal'] = 1;
               $idsucursal = $_REQUEST["idsucursal"];
               $data = Array();
               $query_Tipo = $objCategoria->ListarComprasDetProveedor($idsucursal, $idProveedor, $fecha_desde, $fecha_hasta);

               while ($reg = $query_Tipo->fetch_object()) {

                    $data[] = array(
                         "0"=>$reg->fecha,
                         "1"=>$reg->sucursal,
                         "2"=>$reg->empleado,
                         "3"=>$reg->proveedor,
                         "4"=>$reg->comprobante,

                         "5"=>$reg->impuesto,
                         "6"=>$reg->articulo,

                         "7"=>$reg->stock_ingreso,
                         "8"=>$reg->stock_actual,
                         "9"=>$reg->stock_vendido,
                         "10"=>$reg->precio_compra,
                         "11"=>$reg->precio_ventapublico,
						   "12"=>' '
                    );
               }

               $results = array(
               "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);

               break;


	}
