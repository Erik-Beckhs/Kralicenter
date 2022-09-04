<?php

	session_start();

	require_once "../model/Credito.php";

	$objcredito = new Credito();

	switch ($_GET["op"]) {
//----------------------------------------En caso de registrar creditos desde el módulo de creditoS-----------------------------------
		case 'SaveOrUpdate':

			$idventa = $_POST["txtIdVenta"]; // Llamamos al input txtNombre
			$fecha_pago = date("d/m/Y");
			$total_pago = $_POST["txtTotalPago"];

				if(empty($_POST["txtIdcredito"])){

					if($objcredito->Registrar($idventa,$fecha_pago, $total_pago)){
						echo "credito registrado";
					}else{
						echo "credito no ha podido ser registado.";
					}
				}else{

					$idcredito = $_POST["txtIdcredito"];
					if($objcredito->Modificar($idcredito, $idventa,$fecha_pago, $total_pago)){
						echo "credito ha sido actualizada";
					}else{
						echo "credito no ha podido ser actualizada.";
					}
				}

			break;
			//----------------------------------------En caso de registrar creditos desde el módulo de VENTAS-----------------------------------
			case 'SaveOrUpdateP':

				$idventa = $_POST["txtIdVentaCred"]; // Llamamos al input txtNombre
				$fecha_pago = date("d/m/Y");
				$total_pago = $_POST["txtTotalPago"];

					if(empty($_POST["txtIdcredito"])){

						if($objcredito->Registrar($idventa,$fecha_pago, $total_pago)){
							echo "credito registrado";
						}else{
							echo "credito no ha podido ser registado.";
						}
					}else{

						$idcredito = $_POST["txtIdcredito"];
						if($objcredito->Modificar($idcredito, $idventa,$fecha_pago, $total_pago)){
							echo "credito ha sido actualizada";
						}else{
							echo "credito no ha podido ser actualizada.";
						}
					}

				break;

				//----------------------------------------En caso de registrar creditos desde el módulo de Cuentas por Pagar-----------------------------------
				case 'SaveOrUpdateD':

					$idingreso = $_POST["txtIdIngreso"]; // Llamamos al input txtNombre
					$fecha_pago = date("d/m/Y");
					$total_pago = $_POST["txtTotalPagoC"];

						if(empty($_POST["txtIdcredito"])){

							if($objcredito->RegistrarC($idingreso,$fecha_pago, $total_pago)){
								echo "credito registrado";
							}else{
								echo "credito no ha podido ser registado.";
							}
						}else{

							$idcredito = $_POST["txtIdcredito"];
							if($objcredito->Modificar($idcredito, $idventa,$fecha_pago, $total_pago)){
								echo "credito ha sido actualizada";
							}else{
								echo "credito no ha podido ser actualizada.";
							}
						}

					break;


		case "GetIdVenta":
			$query_get = $objcredito->GetIdVenta();
			$reg = $query_get->fetch_object();
			echo $reg->id;
			break;


		case "delete":

			$id = $_POST["id"];// Llamamos a la variable id del js que mandamos por $.post (Categoria.js (Linea 62))
			$result = $objcredito->Eliminar($id);
			if ($result) {
				echo "Configuración credito eliminada Exitosamente";
			} else {
				echo "La configuración credito no fue Eliminada";
			}
			break;

		case "list":
			$query_Tipo = $objcredito->Listar($_SESSION["idsucursal"]);
			$data= Array();
            $i = 1;
     		while ($reg = $query_Tipo->fetch_object()) {
     			$query_total = $objcredito->GetMontoTotalcredito($reg->idventa);

                $reg_total = $query_total->fetch_object();

	             $data[] = array("0"=>$i,
                    "1"=>$reg->tipo_venta,
                    "2"=>$reg->tipo_comprobante,

                    "3"=>$reg->num_comprobante,
                    "4"=>$reg->fecha,

                    "5"=>$reg->total,
                    "6"=>$reg_total->total_pago,
                    "7"=>$reg->total-$reg_total->total_pago,
                    "8"=>($reg->total-$reg_total->total_pago>0)?'<button class="btn btn-success" data-toggle="tooltip" title="Agregar credito" onclick="Agregarcredito('.$reg->idventa.',\''.$reg->total.'\')"><i class="fa fa-usd"></i> </button>&nbsp;'.
										'<a href="./Reportes/exCobro.php?id='.$reg->idventa.'" class="btn btn-primary" data-toggle="tooltip" title="Imprimir" target="blanck" ><i class="fa fa-file-text"></i> </a>&nbsp;':
										'<button class="btn btn-warning" data-toggle="tooltip" title="Total Pagado, Puede ver el detalle de credito haciendo click aqui" onclick="Agregarcredito('.$reg->idventa.',\''.$reg->total.'\')"><i class="fa fa-eye"></i> </button>&nbsp'.
										'<a href="./Reportes/exCobro.php?id='.$reg->idventa.'" class="btn btn-primary" data-toggle="tooltip" title="Imprimir" target="blanck" ><i class="fa fa-file-text"></i> </a>&nbsp;');

                $i++;
            }
			$results = array(
            "sEcho" => 1,
        	"iTotalRecords" => count($data),
        	"iTotalDisplayRecords" => count($data),
            "aaData"=>$data);
			echo json_encode($results);

			break;

		case "listDeudas":
			$query_Tipo = $objcredito->ListarDeuda($_SESSION["idsucursal"]);
			$data= Array();
            $i = 1;
     		while ($reg = $query_Tipo->fetch_object()) {
     			$query_total = $objcredito->GetMontoTotalcreditoMayorCeroC($reg->idingreso);

                $reg_total = $query_total->fetch_object();

	             $data[] = array("0"=>$i,
                    "1"=>$reg->tipo_ingreso,
                    "2"=>$reg->tipo_comprobante,

                    "3"=>$reg->serie_comprobante,
                    "4"=>$reg->fecha,

                    "5"=>$reg->total,
                    "6"=>$reg_total->total_pago,
                    "7"=>$reg->total-$reg_total->total_pago,
										"8"=>($reg->total-$reg_total->total_pago>0)?'<button class="btn btn-success" data-toggle="tooltip" title="Agregar Pago" onclick="AgregarPago('.$reg->idingreso.',\''.$reg->total.'\')"><i class="fa fa-usd"></i> </button>&nbsp;'.
										'<a href="./Reportes/exPago.php?id='.$reg->idingreso.'" class="btn btn-primary" data-toggle="tooltip" title="Imprimir" target="blanck" ><i class="fa fa-file-text"></i> </a>&nbsp;':
										'<button class="btn btn-warning" data-toggle="tooltip" title="Total Pagado, Puede ver el detalle de Pagos haciendo click aqui" onclick="AgregarPago('.$reg->idingreso.',\''.$reg->total.'\')"><i class="fa fa-eye"></i> </button>&nbsp'.
										'<a href="./Reportes/exPago.php?id='.$reg->idingreso.'" class="btn btn-primary" data-toggle="tooltip" title="Imprimir" target="blanck" ><i class="fa fa-file-text"></i> </a>&nbsp;');

                $i++;
            }
			$results = array(
            "sEcho" => 1,
        	"iTotalRecords" => count($data),
        	"iTotalDisplayRecords" => count($data),
            "aaData"=>$data);
			echo json_encode($results);

			break;

		case "VerDetcredito":

			$idVenta = $_POST["idVenta"];

			$query_Tipo = $objcredito->VerDetallecredito($idVenta);

            $i = 1;
     		while ($reg = $query_Tipo->fetch_object()) {
	             echo '
	             	<tr>
	             		<td>'.$reg->fecha_pago.'</td>
	             		<td>'.$reg->total_pago.'</td>
	             ';
                $i++;
            }

			break;

			case "VerDetcreditoCompra":

				$idIngreso = $_POST["idIngreso"];

				$query_Tipo = $objcredito->VerDetallecreditoC($idIngreso);

							$i = 1;
					while ($reg = $query_Tipo->fetch_object()) {
								 echo '
									<tr>
										<td>'.$reg->fecha_pago.'</td>
										<td>'.$reg->total_pago.'</td>
								 ';
									$i++;
							}

				break;

		case "MontoTotalPagados":

			$idVenta = $_REQUEST["idVenta"];

			$query_Tipo = $objcredito->MontoTotalPagados($idVenta);

            $reg = $query_Tipo->fetch_object();

     		echo json_encode($reg);

			break;

			case "MontoTotalPagadosCompra":

				$idIngreso = $_REQUEST["idIngreso"];

				$query_Tipo = $objcredito->MontoTotalPagadosC($idIngreso);

	            $reg = $query_Tipo->fetch_object();

	     		echo json_encode($reg);

				break;

		case "GetImpuesto":
			$query_Tipo = $objcredito->Listar();

            $reg = $query_Tipo->fetch_object();

     		echo json_encode($reg);

			break;

	}
