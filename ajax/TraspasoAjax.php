<?php

	session_start();

	require_once "../model/Traspaso.php";

	$objTraspaso = new Traspaso();

	switch ($_GET["op"]) {

		case 'SaveOrUpdate':

	      	$idUsuario = $_POST["idusuario"];
   			$idSucursalOrigen = $_POST["idsucursalorigen"];
   			$idSucursalDestino = $_POST["idsucursaldestino"];
   			$FechaTraspaso = $_POST["fechatraspaso"];
   			$Motivo = $_POST["motivo"];

			if ($objTraspaso->Registrar($idUsuario, $idSucursalOrigen, $idSucursalDestino, $FechaTraspaso, $Motivo, $_POST["detalle"])) {
				echo "Traspaso Registrado correctamente.";
			} else {
				echo "Traspaso no ha podido ser registrado.";
			}

			break;

		case "delete":

			$id = $_POST["id"];// Llamamos a la variable id del js que mandamos por $.post (Categoria.js (Linea 62))
			$result = $objTraspaso->Eliminar($id);
			if ($result) {
				echo "Eliminado Exitosamente";
			} else {
				echo "No fue Eliminado";
			}
			break;

		case "list":
			$query_Tipo = $objTraspaso->ListarDatosCompletos();
			$data= Array();
            $i = 1;
     		while ($reg = $query_Tipo->fetch_object()) {

     			$data[] = array("0"=>$i,
					"1"=>$reg->sucursalorigen,
					"2"=>$reg->sucursaldestino,
					"3"=>$reg->nombreusuario,
					"4"=>$reg->fecha,
					"5"=>$reg->motivo,
//					"6"=>$reg->estado,
					"6"=>'<button class="btn btn-success" data-toggle="tooltip" title="Ver Detalle" onclick="cargarDataTraspaso('.$reg->idtraspaso.',\''.$reg->idsucursalorigen.'\',\''.$reg->idsucursaldestino.'\',\''.$reg->fecha.'\',\''.$reg->motivo.'\')"><i class="fa fa-eye"></i> </button>&nbsp;'.
                          '<a href="./Reportes/exTraspaso.php?id='.$reg->idtraspaso.'" class="btn btn-primary" data-toggle="tooltip" title="Imprimir" target="blanck" ><i class="fa fa-file-text"></i> </a>&nbsp' .
						'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarTraspaso('.$reg->idtraspaso.')"><i class="fa fa-trash"></i> </button>');
				$i++;
			}
			$results = array(
            "sEcho" => 1,
        	"iTotalRecords" => count($data),
        	"iTotalDisplayRecords" => count($data),
            "aaData"=>$data);
			echo json_encode($results);

			break;
    case "listSucursalDestino":
        require_once "../model/Sucursal.php";

        $idsucursaldestino = $_POST["idsucursalorigen"];

        $objSucursal = new Sucursal();

        $query_prov = $objSucursal->ListarExcepto($idsucursaldestino);

        $i = 1;
				
            while ($reg = $query_prov->fetch_object()) {
                 echo '<tr>
                        <td><input type="radio" name="optSucursalBusqueda" data-nombre="'.$reg->razon_social.'" id="'.$reg->idsucursal.'" value="'.$reg->idsucursal.'" /></td>
                        <td>'.$i.'</td>
                        <td>'.$reg->razon_social.'</td>
                        <td>'.$reg->tipo_documento.' - '.$reg->num_documento.'</td>
                        <td>'.$reg->direccion.'</td>
                        <td>'.$reg->email.'</td>
                        <td> <img width=100px height=100px src='.$reg->logo.' /></td>
                       </tr>';
                 $i++;
            }

        break;

				case "listSucursalOrigen":
		        require_once "../model/Sucursal.php";



		        $objSucursal = new Sucursal();

		        $query_prov = $objSucursal->Listar();

		        $i = 1;
		            while ($reg = $query_prov->fetch_object()) {
		                 echo '<tr>
		                        <td><input type="radio" name="optSucursalBusqueda" data-nombre="'.$reg->razon_social.'" id="'.$reg->idsucursal.'" value="'.$reg->idsucursal.'" /></td>
		                        <td>'.$i.'</td>
		                        <td>'.$reg->razon_social.'</td>
		                        <td>'.$reg->tipo_documento.' - '.$reg->num_documento.'</td>
		                        <td>'.$reg->direccion.'</td>
		                        <td>'.$reg->email.'</td>
		                        <td> <img width=100px height=100px src='.$reg->logo.' /></td>
		                       </tr>';
		                 $i++;
		            }

		        break;

    case "listArticulosxSucursal":
        require_once "../model/Articulo.php";

        $idsucursalorigen = $_SESSION["idsucursal"];
        $objArticulo = new Articulo();

        $query_prov = $objArticulo->ListarArticulosxSucursal($idsucursalorigen);


		$data= Array();
		$i = 1;

		while ($reg = $query_prov->fetch_object()) {
			$stock_actual = $reg->stock_actual;
			$stock_minimo = $reg->minima;

			if ($stock_actual <= +$stock_minimo) {
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

				onclick="AgregarPedCarritoTraspaso('.$reg->iddetalle_ingreso.',\''.$reg->idingreso.'\',\''.$reg->idarticulo.'\',\''.$reg->stock_actual.'\',\''.$reg->Articulo.'\',\''.$reg->codigo.'\',\''.$reg->serie.'\',\''.$reg->precio_ventapublico.'\',\''.$reg->descripcion.'\',\''.$reg->precio_compra.'\',\''.$reg->precio_ventadistribuidor.'\');changeColor(this);" >
				<i class="fa fa-check" ></i> </button>',


				"1"=>$reg->Articulo,
				"2"=>$reg->idarticulo,
				"3"=>$reg->numero,
				"4"=>$reg->codigo_interno,
				"5"=>$reg->presentacion,
				"6"=>$reg->unidad_medida,
				"7"=>$reg->prefijo,
				"8"=>$reg->descripcion,
				"9"=>$reg->instruccion,
				"10"=>$reg->vrestringida,
				"11"=>"<span class='rojo'>$mensaje<br></span><br><span class='rojo'>$reg->stock_actual</span>",
				"12"=>$reg->precio_ventapublico);
			}

			if ($stock_actual > +$stock_minimo) {

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

					onclick="AgregarPedCarritoTraspaso('.$reg->iddetalle_ingreso.',\''.$reg->idingreso.'\',\''.$reg->idarticulo.'\',\''.$reg->stock_actual.'\',\''.$reg->Articulo.'\',\''.$reg->codigo.'\',\''.$reg->serie.'\',\''.$reg->precio_ventapublico.'\',\''.$reg->descripcion.'\',\''.$reg->precio_compra.'\',\''.$reg->precio_ventadistribuidor.'\');changeColor(this);" >
					<i class="fa fa-check" ></i> </button>',


				"1"=>$reg->Articulo,
				"2"=>$reg->idarticulo,
				"3"=>$reg->numero,
				"4"=>$reg->codigo_interno,
				"5"=>$reg->presentacion,
				"6"=>$reg->unidad_medida,
				"7"=>$reg->prefijo,
				"8"=>$reg->descripcion,
				"9"=>$reg->instruccion,
				"10"=>$reg->vrestringida,
				"11"=>"<span class='verde'>$reg->stock_actual</span>",

				"12"=>$reg->precio_ventapublico);
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

    case "GetDetalleTraspaso":
        require_once "../model/Traspaso.php";

        $objTraspaso = new Traspaso();

        $idTraspaso = $_POST["idTraspaso"];

        $query_prov = $objTraspaso->GetDetalleTraspaso($idTraspaso);
        $i = 1;
            while ($reg = $query_prov->fetch_object()) {
                 echo '<tr>
                        <td>'.$i.'</td>
                        <td>'.$reg->codigo.'</td>
                        <td>'.$reg->articulo.'</td>
                        <td>'.$reg->stock_actual.'</td>
                        <td>'.$reg->stock_traspaso.'</td>
                       </tr>';
                 $i++;
            }

        break;
	case "GetNombreSucursal":
        require_once "../model/Sucursal.php";

        $idsucursal = $_POST["idSucursal"];

        $objSucursal = new Sucursal();

        $query_prov = $objSucursal->ListarSucursal($idsucursal);

        $i = 1;
            while ($reg = $query_prov->fetch_object()) {
                 echo $reg->razon_social;
            }

        break;
	}
