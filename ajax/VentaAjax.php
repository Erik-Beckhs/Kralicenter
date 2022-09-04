<?php

	session_start();

	require_once "../model/Venta.php";

	$objVenta = new Venta();

	switch ($_GET["op"]) {

		case 'SaveOrUpdate':

				$idsucursal = $_POST["idSucursal"];
				$idusuario = $_POST["idUsuario"];
				$idcliente = $_POST["idCliente"];
				$tipo_pedido = $_POST["tipo_Pedido"];
				$tipo_pago = $_POST["tipo_pago"];
				$descuento = $_POST["descuento"];
				$tiempo_entrega = $_POST["tiempo_entrega"];
				$fecha_validez = $_POST["fecha_validez"];
				$impuesto = $_POST["impuesto"];
				$total_vent = $_POST["total_vent"];
				$numero_TF =isset($_POST["Numero_TF"])?$_POST["Numero_TF"]:0;
				$tipo_comprobante =$_POST["tipo_comprobante"];

					if(empty($_POST["txtIdVenta"])){

						if($objVenta->Registrar($idsucursal,$idusuario,$idcliente,$tipo_pedido,$tipo_pago,$descuento,$tiempo_entrega,$fecha_validez,$impuesto, $total_vent,$tipo_comprobante,$numero_TF, $_POST["detalle"])){

								echo "Proforma Registrada correctamente.";
						}else{
								echo "Proforma no ha podido ser registado.";
						}

					}else{

						$idVenta = $_POST["txtIdVenta"];
						if($objVenta->Modificar($idventa,$idpedido, $idusuario,$tipo_venta,$tipo_comprobante,$serie_comprobante,$num_comprobante,$impuesto,$total,$estado)){
							echo "La información de la Proforma ha sido actualizada.";
						}else{
							echo "La información de la Proforma no ha podido ser actualizada.";
						}
					}

				break;








			case "delete":

				$id = $_POST["id"];// Llamamos a la variable id del js que mandamos por $.post (Categoria.js (Linea 62))
				$result = $objVenta->Eliminar($id);
				if ($result) {
					echo "Eliminado Exitosamente";
				} else {
					echo "No fue Eliminado";
				}
				break;

			case "list":
				 require_once "../model/Venta.php";
				 $data= Array();
				 $objPedido = new Venta();
				 if ( !isset($_SESSION['idsucursal']))
				 {
						 $_SESSION['idsucursal'] = 1;
				 }
				 $query_Pedido = $objPedido->Listar($_SESSION["idsucursal"]);
				$numero = 5;
				$email ="";
				 $i = 1;
						 while ($reg = $query_Pedido->fetch_object()) {


								 $data[] = array("0"=>$i,
										 "1"=>$reg->nombre,
										 "2"=>($reg->tipo_comprobante =="TICKET")?'<span class="badge bg-blue">TICKET</span>':(($reg->tipo_comprobante=="FACTURA")?'<span class="badge bg-aqua">FACTURA</span>':'<span class="badge bg-green">PROFORMA</span>'),
										 "3"=>$reg->fecha,
										 "4"=>$reg->total,
										 "5"=>($reg->estado_pedido=="A")?'<span class="badge bg-blue">ACEPTADO</span>':(($reg->estado_pedido=="F")?'<span class="badge bg-green">FACTURADO</span>':'<span class="badge bg-red">ANULADO</span>'),
						         "6"=>($reg->estado_pedido=="A")?'<button class="btn btn-success" data-toggle="tooltip" title="Ver Detalle Proforma" onclick="cargarDataPedido('.$reg->idventa.',\''.$reg->tipo_comprobante.'\',\''.$numero.'\',\''.$reg->nombre.'\',\''.$reg->total.'\',\''.$email.'\')" ><i class="fa fa-eye"></i> </button>&nbsp'.
													 '<button class="btn btn-warning" data-toggle="tooltip" title="Anular" onclick="cancelarPedido('.$reg->idventa.')" ><i class="fa fa-times-circle"></i> </button>&nbsp'.
//													 '<button class="btn btn-warning" data-toggle="tooltip" title="Anular" onclick="cancelarPedido('.$reg->idventa.')" ><i class="fa fa-times-circle"></i> </button>&nbsp'.
													 '<a href="./Reportes/exProforma.php?id='.$reg->idventa.'" class="btn btn-primary" data-toggle="tooltip" title="Imprimir" target="blanck"><i class="fa fa-file-text"></i> </a>':
													 '<button class="btn btn-success" data-toggle="tooltip" title="Ver Detalle" onclick="cargarDataPedido('.$reg->idventa.',\''.$reg->tipo_comprobante.'\',\''.$numero.'\',\''.$reg->nombre.'\',\''.$reg->total.'\')" ><i class="fa fa-eye"></i> </button>&nbsp'.
													 '<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarProforma('.$reg->idventa.')" ><i class="fa fa-trash"></i> </button>&nbsp');


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

	            require_once "../model/Venta.php";

	            $objVenta = new Venta();

	            $idventa = $_REQUEST["idVenta"];

	            $query = $objVenta->VerVenta($idventa);

	            $reg = $query->fetch_object();

	            echo json_encode($reg);

	            break;

		    case "GetDetalleProforma":
		        require_once "../model/Venta.php";

		        $objVenta = new Venta();

		        $idVenta= $_POST["idVenta"];

		        $query_prov = $objVenta->GetDetalleProforma($idVenta);
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


			case "listDetIng":
			require_once "../model/Venta.php";
				$objVenta = new Venta();
				$query_cli = $objVenta->ListarDetalleIngresos($_SESSION["idsucursal"]);


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
										<button type="button" class="btn btn-warning" name="optDetIngBusqueda[]" data-codigo="'.$reg->codigo.'"
										data-serie="'.$reg->serie.'" data-nombre="'.$reg->Articulo.'" data-precio-venta="'.$reg->precio_ventapublico.'"
										data-stock-actual="'.$reg->stock_actual.'" id="'.$reg->iddetalle_ingreso.'" value="'.$reg->iddetalle_ingreso.'"
										data-toggle="tooltip" title="Agregar al carrito"

										onclick="AgregarPedCarritoPF('.$reg->iddetalle_ingreso.',\''.$reg->stock_actual.'\',\''.$reg->Articulo.'\',\''.$reg->codigo.'\',\''.$reg->serie.'\',\''.$reg->precio_ventapublico.'\');changeColor(this);" >
										<i class="fa fa-check" ></i> </button>',


										"1"=>$reg->Articulo,
										"2"=>$reg->idarticulo,
										"3"=>$reg->numero,
										"4"=>$reg->codigo_interno,
										"5"=>$reg->presentacion,
									"6"=>$reg->unidad_medida,
										"7"=>$reg->prefijo,

									"8"=>$reg->instruccion,
									"9"=>$reg->vrestringida,
								"10"=>"<span class='rojo'>$mensaje<br></span><br><span class='rojo'>$reg->stock_actual</span>",

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

											<button type="button" class="btn btn-warning" name="optDetIngBusqueda[]" data-codigo="'.$reg->codigo.'"
											data-serie="'.$reg->serie.'" data-nombre="'.$reg->Articulo.'" data-precio-venta="'.$reg->precio_ventapublico.'"
											data-stock-actual="'.$reg->stock_actual.'" id="'.$reg->iddetalle_ingreso.'" value="'.$reg->iddetalle_ingreso.'"
											data-toggle="tooltip" title="Agregar al carrito"

											onclick="AgregarPedCarritoPF('.$reg->iddetalle_ingreso.',\''.$reg->stock_actual.'\',\''.$reg->Articulo.'\',\''.$reg->codigo.'\',\''.$reg->serie.'\',\''.$reg->precio_ventapublico.'\');changeColor(this);" >
											<i class="fa fa-check" ></i> </button>',


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

		case "listTipo_DocumentoPersona":
		        require_once "../model/Tipo_Documento.php";

		        $objTipo_Documento = new Tipo_Documento();

		        $query_tipo_Documento = $objTipo_Documento->VerTipo_Documento_Persona();

		        while ($reg = $query_tipo_Documento->fetch_object()) {
		            echo '<option value=' . $reg->nombre . '>' . $reg->nombre . '</option>';
		        }

		    break;

				case "listTipoDoc":

							 require_once "../model/Venta.php";

							 $objPedido = new Venta();

							 $query_Categoria = $objPedido->ListarTipoDocumento($_SESSION["idsucursal"]);


							 while ($reg = $query_Categoria->fetch_object()) {
									 echo '<option value=' . $reg->nombre . '>' . $reg->nombre . '</option>';
							 }

							 break;

		 case "GetTipoDocSerieNum":

            $nombre = $_REQUEST["nombre"];
            $idsucursal = $_REQUEST["idsucursal"];

            $query_Categoria = $objVenta->GetTipoDocSerieNum($nombre,$idsucursal);

            $reg = $query_Categoria->fetch_object();

            echo json_encode($reg);

            break;

						case "GetPrimerIDTicket":

										require_once "../model/Venta.php";

										$objPedido = new Venta();

										$txtSucursal = $_POST["txtIdSucursal"];

										$query_idTicket = $objPedido->Get_id_ticket($txtSucursal);


										echo json_encode((int)$query_idTicket);

										break;

						case "listClientesV":
								require_once "../model/Persona.php";
//instanciamos a la calase persona()
								$objCliente = new Persona();
//llamar a la funcion listar clientes de persona()
								$query_cli = $objCliente->ListarCliente();

								$i = 1;
										while ($reg = $query_cli->fetch_object()) {

												 echo '<tr>

												 	<td><input type="radio" name="optClienteBusqueda[]" data-nombre="'.$reg->nombre.'" id="'.$reg->idpersona.'"value="'.$reg->idpersona.'" /></td>

																<td>'.$i.'</td>
																<td>'.$reg->tipo_persona.'</td>
																<td>'.$reg->nombre.'</td>
																<td>'.$reg->num_documento.'</td>
																<td>'.$reg->email.'</td>
															 </tr>';
												 $i++;
										}

								break;


								case "GetTotal":

						            require_once "../model/Venta.php";

						            $objPedido = new Venta();

						            $query_total = $objPedido->TotalProforma($_REQUEST["idPedido"]);

						            $reg_total = $query_total->fetch_object();

						            echo json_encode($reg_total);

						            break;

		    case "TraerCantidad" :
		        require_once "../model/Venta.php";

		        $objVenta = new Venta();

		        $query_Venta = $objVenta->TraerCantidad($_REQUEST["idPedido"]);

		            while ($reg = $query_Venta->fetch_object()) {
		                $data[] = array($reg->iddetalle_ingreso,
		                    $reg->cantidad
		                    );
		            }
		        echo json_encode($data);

		        break;

		    case "CambiarEstado" :
		        require_once "../model/Venta.php";

		        $obj= new Venta();

		        $idVenta = $_POST["idVenta"];
		/*
		        foreach($_POST["detalle"] as $indice => $valor){
		            echo $valor[0]. " - ";
		        }
		        */

		        $hosp = $obj->CambiarEstado($idVenta);
		                if ($hosp) {
		                    echo "Proforma Anulada";
		                } else {
		                    echo "No se ha podido Anular la Proforma";
		                }
		        break;
		    case "EliminarProforma" :
		        require_once "../model/Venta.php";

		        $obj= new Venta();

		        $idVenta = $_POST["idVenta"];

		        $hosp = $obj->EliminarProforma($idVenta);
		                if ($hosp) {
		                    echo "Proforma Eliminada ...";
		                } else {
		                    echo "No se ha podido eliminar la Proforma ...";
		                }
		        break;
	}
