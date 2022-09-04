<?php

	session_start();

	require_once "../model/Almacen.php";

	$objArticulo = new articuloAlmacen();

	switch ($_GET["op"]) {

		case 'modificar':

			$stock_actual= $_POST["txtstock_actual"];
			$stock_ingreso= $_POST["txtstock_actual"];
			$venta1= $_POST["txtventa1"];
			$venta2= $_POST["txtventa2"];
			$venta3= $_POST["txtventa3"];
			$venta4= $_POST["txtventa4"];
			$venta5= $_POST["txtventa5"];
			$compra= $_POST["txtcompra"];



			if(true){

				if(empty($_POST["txtIdDetalleIngreso"])){

					if($objArticulo->Registrar($idcategoria, $idunidad_medida, $nombre, $descripcion, "Files/Articulo/".$ruta,$instruccion,$numero,$vrestringida)){
						echo "Articulo Registrado";
					}else{
						echo "no.";
					}
				}else{

					$idarticulo = $_POST["txtIdDetalleIngreso"];
					if($objArticulo->Modificar($stock_actual,$stock_ingreso,$venta1,$venta2,$venta3,$venta4,$venta5, $compra, $_POST["txtIdDetalleIngreso"])){
						echo "Informacion del Articulo ha sido actualizada";
					}else{
						echo "Informacion del Articulo No ha sido actualizada.";
					}
				}
			} else {
				$ruta_img = $_POST["txtRutaImgArt"];
				if(empty($_POST["txtIdDetalleIngreso"])){

					if($objArticulo->Registrar($idcategoria, $idunidad_medida, $nombre, $descripcion, $ruta_img,$instruccion,$numero,$vrestringida)){
						echo "Articulo Registrado";
					}else{
						echo "no.";
					}
				}else{

					$idarticulo = $_POST["txtIdDetalleIngreso"];
					if($objArticulo->Modificar($stock_actual,$stock_ingreso,$venta1,$venta2,$venta3,$venta4,$venta5, $compra, $_POST["txtIdDetalleIngreso"])){
						echo "Informacion del Articulo ha sido actualizada";
					}else{
						echo "no.";
					}
				}
			}

			break;

		case "delete":

			$id = $_POST["id"];
			$result = $objArticulo->Eliminar($id);
			if ($result) {
				echo "Eliminado Exitosamente";
			} else {
				echo "No fue Eliminado";
			}
			break;





		case "list4":
			$query_Tipo = $objArticulo->Listar();
			$data = Array();
            $i = 1;
     		while ($reg = $query_Tipo->fetch_object()) {

					$stock_actual = $reg->stock_actual;
					$stock_minimo = $reg->minima;
$idsucursal=$reg->idsucursal;
  if ($stock_actual <= +$stock_minimo) {
		if ($reg->idsucursal==1) {
  $mensaje= "Cantidad Baja! :";
     			$data[] = array("id"=>$i,
				"1"=>$reg->articulo,
				"2"=>"<span class='azulClaro'>$reg->razon_social</span>",
				"3"=>$reg->idarticulo,
				"4"=>$reg->numero,
				"5"=>$reg->codigo_interno,

				"6"=>$reg->marca,

				"7"=>"<span class='rojo'>$mensaje<br></span><br><span class='rojo'>$reg->stock</span>",

				"8"=>$reg->dolencia,
				"9"=>$reg->instruccion,

					"10"=>$reg->vrestringida,
					"11"=>$reg->precio_compra,

//						"12"=>'
	//					<link rel="stylesheet" type="text/css" href="public/shadowbox/shadowbox.css">
//						<script type="text/javascript" src="public/shadowbox/shadowbox.js"></script>
//						<script type="text/javascript">
//						Shadowbox.init();
//						</script>
//						<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=100px height=100px src="./'.$reg->imagen.'" /></a>',
						"12"=>'<button class="btn btn-warning" onclick="cargarDataArticulo2 ('.$reg->iddetalle_ingreso.',\''.$reg->stock_actual.'\',\''.$reg->precio_compra.'\',\''.$reg->precio_ventapublico.'\',\''.$reg->precio2.'\',\''.$reg->precio3.'\',\''.$reg->precio4.'\',\''.$reg->precio5.'\');" data-toggle="tooltip" title="Editar"><i class="fa fa-pencil"></i> </button>&nbsp;'.
					'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarArticulo2('.$reg->idarticulo.')"><i class="fa fa-trash"></i> </button>');
  }
	else {
if ($reg->idsucursal==2) {
		$mensaje= "Cantidad Baja! :";
	     			$data[] = array("id"=>$i,
					"1"=>$reg->articulo,
					"2"=>"<span class='naranjaClaro'>$reg->razon_social</span>",
					"3"=>$reg->idarticulo,
					"4"=>$reg->numero,
					"5"=>$reg->codigo_interno,

					"6"=>$reg->marca,

					"7"=>"<span class='rojo'>$mensaje<br></span><br><span class='rojo'>$reg->stock_actual</span>",

					"8"=>$reg->dolencia,
					"9"=>$reg->instruccion,

						"10"=>$reg->vrestringida,
						"11"=>$reg->precio_compra,
						//	"12"=>'
						//	<link rel="stylesheet" type="text/css" href="public/shadowbox/shadowbox.css">
						//	<script type="text/javascript" src="public/shadowbox/shadowbox.js"></script>
						//	<script type="text/javascript">
						//	Shadowbox.init();
						//	</script>
						//	<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=100px height=100px src="./'.$reg->imagen.'" /></a>',
							"12"=>'<button class="btn btn-warning" onclick="cargarDataArticulo2('.$reg->iddetalle_ingreso.',\''.$reg->stock_actual.'\',\''.$reg->precio_compra.'\',\''.$reg->precio_ventapublico.'\',\''.$reg->precio2.'\',\''.$reg->precio3.'\',\''.$reg->precio4.'\',\''.$reg->precio5.'\');" data-toggle="tooltip" title="Editar"><i class="fa fa-pencil"></i> </button>&nbsp;'.
						'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarArticulo2('.$reg->idarticulo.')"><i class="fa fa-trash"></i> </button>');
}
else
{
	if ($reg->idsucursal==3) {
			$mensaje= "Cantidad Baja! :";
		     			$data[] = array("id"=>$i,
						"1"=>$reg->articulo,
						"2"=>"<span class='lilaClaro'>$reg->razon_social</span>",
						"3"=>$reg->idarticulo,
						"4"=>$reg->numero,
						"5"=>$reg->codigo_interno,

						"6"=>$reg->marca,

						"7"=>"<span class='rojo'>$mensaje<br></span><br><span class='rojo'>$reg->stock_actual</span>",

						"8"=>$reg->dolencia,
						"9"=>$reg->instruccion,

							"10"=>$reg->vrestringida,
							"11"=>$reg->precio_compra,
							//	"12"=>'
							//	<link rel="stylesheet" type="text/css" href="public/shadowbox/shadowbox.css">
							//	<script type="text/javascript" src="public/shadowbox/shadowbox.js"></script>
							//	<script type="text/javascript">
							//	Shadowbox.init();
							//	</script>
							//	<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=100px height=100px src="./'.$reg->imagen.'" /></a>',
								"12"=>'<button class="btn btn-warning" onclick="cargarDataArticulo2('.$reg->iddetalle_ingreso.',\''.$reg->stock_actual.'\',\''.$reg->precio_compra.'\',\''.$reg->precio_ventapublico.'\',\''.$reg->precio2.'\',\''.$reg->precio3.'\',\''.$reg->precio4.'\',\''.$reg->precio5.'\');" data-toggle="tooltip" title="Editar"><i class="fa fa-pencil"></i> </button>&nbsp;'.
							'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarArticulo2('.$reg->idarticulo.')"><i class="fa fa-trash"></i> </button>');
	}
	else {
		if ($reg->idsucursal==4) {
				$mensaje= "Cantidad Baja! :";
			     			$data[] = array("id"=>$i,
							"1"=>$reg->articulo,
							"2"=>"<span class='verdeClaro'>$reg->razon_social</span>",
							"3"=>$reg->idarticulo,
							"4"=>$reg->numero,
							"5"=>$reg->codigo_interno,

							"6"=>$reg->marca,

							"7"=>"<span class='rojo'>$mensaje<br></span><br><span class='rojo'>$reg->stock_actual</span>",

							"8"=>$reg->dolencia,
							"9"=>$reg->instruccion,

								"10"=>$reg->vrestringida,
								"11"=>$reg->precio_compra,
								//	"12"=>'
								//	<link rel="stylesheet" type="text/css" href="public/shadowbox/shadowbox.css">
								//	<script type="text/javascript" src="public/shadowbox/shadowbox.js"></script>
								//	<script type="text/javascript">
								//	Shadowbox.init();
								//	</script>
								//	<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=100px height=100px src="./'.$reg->imagen.'" /></a>',
									"12"=>	'<button class="btn btn-warning" onclick="cargarDataArticulo2('.$reg->iddetalle_ingreso.',\''.$reg->stock_actual.'\',\''.$reg->precio_compra.'\',\''.$reg->precio_ventapublico.'\',\''.$reg->precio2.'\',\''.$reg->precio3.'\',\''.$reg->precio4.'\',\''.$reg->precio5.'\');" data-toggle="tooltip" title="Editar"><i class="fa fa-pencil"></i> </button>&nbsp;'.
								'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarArticulo2('.$reg->idarticulo.')"><i class="fa fa-trash"></i> </button>');
		}
		else {
			if ($reg->idsucursal==5) {
					$mensaje= "Cantidad Baja! :";
				     			$data[] = array("id"=>$i,
								"1"=>$reg->articulo,
								"2"=>"<span class='amarilloClaro'>$reg->razon_social</span>",
								"3"=>$reg->idarticulo,
								"4"=>$reg->numero,
								"5"=>$reg->codigo_interno,

								"6"=>$reg->marca,

								"7"=>"<span class='rojo'>$mensaje<br></span><br><span class='rojo'>$reg->stock_actual</span>",

								"8"=>$reg->dolencia,
								"9"=>$reg->instruccion,

									"10"=>$reg->vrestringida,
									"11"=>$reg->precio_compra,
									//	"12"=>'
									//	<link rel="stylesheet" type="text/css" href="public/shadowbox/shadowbox.css">
									//	<script type="text/javascript" src="public/shadowbox/shadowbox.js"></script>
									//	<script type="text/javascript">
									//	Shadowbox.init();
									//	</script>
									//	<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=100px height=100px src="./'.$reg->imagen.'" /></a>',
										"12"=>	'<button class="btn btn-warning" onclick="cargarDataArticulo2('.$reg->iddetalle_ingreso.',\''.$reg->stock_actual.'\',\''.$reg->precio_compra.'\',\''.$reg->precio_ventapublico.'\',\''.$reg->precio2.'\',\''.$reg->precio3.'\',\''.$reg->precio4.'\',\''.$reg->precio5.'\');" data-toggle="tooltip" title="Editar"><i class="fa fa-pencil"></i> </button>&nbsp;'.
									'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarArticulo2('.$reg->idarticulo.')"><i class="fa fa-trash"></i> </button>');
			}
			else {
				if ($reg->idsucursal==6) {
						$mensaje= "Cantidad Baja! :";
					     			$data[] = array("id"=>$i,
									"1"=>$reg->articulo,
									"2"=>"<span class='rojoClaro'>$reg->razon_social</span>",
									"3"=>$reg->idarticulo,
									"4"=>$reg->numero,
									"5"=>$reg->codigo_interno,

									"6"=>$reg->marca,

									"7"=>"<span class='rojo'>$mensaje<br></span><br><span class='rojo'>$reg->stock_actual</span>",

									"8"=>$reg->dolencia,
									"9"=>$reg->instruccion,

										"10"=>$reg->vrestringida,
										"11"=>$reg->precio_compra,
										//	"12"=>'
										//	<link rel="stylesheet" type="text/css" href="public/shadowbox/shadowbox.css">
										//	<script type="text/javascript" src="public/shadowbox/shadowbox.js"></script>
										//	<script type="text/javascript">
										//	Shadowbox.init();
										//	</script>
										//	<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=100px height=100px src="./'.$reg->imagen.'" /></a>',
											"12"=>	'<button class="btn btn-warning" onclick="cargarDataArticulo2('.$reg->iddetalle_ingreso.',\''.$reg->stock_actual.'\',\''.$reg->precio_compra.'\',\''.$reg->precio_ventapublico.'\',\''.$reg->precio2.'\',\''.$reg->precio3.'\',\''.$reg->precio4.'\',\''.$reg->precio5.'\');" data-toggle="tooltip" title="Editar"><i class="fa fa-pencil"></i> </button>&nbsp;'.
										'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarArticulo2('.$reg->idarticulo.')"><i class="fa fa-trash"></i> </button>');
				}
			}
		}
	}
}
}
}

	if ($stock_actual > +$stock_minimo) {
		if ($reg->idsucursal==1) {
  $mensaje= "Cantidad Baja! :";
     			$data[] = array("id"=>$i,
				"1"=>$reg->articulo,
				"2"=>"<span class='azulClaro'>$reg->razon_social</span>",
				"3"=>$reg->idarticulo,
						"4"=>$reg->numero,
						"5"=>$reg->codigo_interno,

						"6"=>$reg->marca,

						"7"=>"<span >$reg->stock_actual</span>",

						"8"=>$reg->dolencia,
						"9"=>$reg->instruccion,

						"10"=>$reg->vrestringida,
						"11"=>$reg->precio_compra,
						//	"12"=>'
						//	<link rel="stylesheet" type="text/css" href="public/shadowbox/shadowbox.css">
						//	<script type="text/javascript" src="public/shadowbox/shadowbox.js"></script>
						//	<script type="text/javascript">
						//	Shadowbox.init();
						//	</script>
						//	<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=100px height=100px src="./'.$reg->imagen.'" /></a>',
							"12"=>	'<button class="btn btn-warning" onclick="cargarDataArticulo2('.$reg->iddetalle_ingreso.',\''.$reg->stock_actual.'\',\''.$reg->precio_compra.'\',\''.$reg->precio_ventapublico.'\',\''.$reg->precio2.'\',\''.$reg->precio3.'\',\''.$reg->precio4.'\',\''.$reg->precio5.'\');" data-toggle="tooltip" title="Editar"><i class="fa fa-pencil"></i> </button>&nbsp;'.
							'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarArticulo2('.$reg->idarticulo.')"><i class="fa fa-trash"></i> </button>');

  }
	else {
if ($reg->idsucursal==2) {
		$mensaje= "Cantidad Baja! :";
	     			$data[] = array("id"=>$i,
					"1"=>$reg->articulo,
					"2"=>"<span class='naranjaClaro'>$reg->razon_social</span>",
					"3"=>$reg->idarticulo,
							"4"=>$reg->numero,
							"5"=>$reg->codigo_interno,

							"6"=>$reg->marca,

							"7"=>"<span >$reg->stock_actual</span>",

							"8"=>$reg->dolencia,
							"9"=>$reg->instruccion,

							"10"=>$reg->vrestringida,
							"11"=>$reg->precio_compra,
							//	"12"=>'
							//	<link rel="stylesheet" type="text/css" href="public/shadowbox/shadowbox.css">
							//	<script type="text/javascript" src="public/shadowbox/shadowbox.js"></script>
							//	<script type="text/javascript">
							//	Shadowbox.init();
							//	</script>
							//	<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=100px height=100px src="./'.$reg->imagen.'" /></a>',
								"12"=>	'<button class="btn btn-warning" onclick="cargarDataArticulo2('.$reg->iddetalle_ingreso.',\''.$reg->stock_actual.'\',\''.$reg->precio_compra.'\',\''.$reg->precio_ventapublico.'\',\''.$reg->precio2.'\',\''.$reg->precio3.'\',\''.$reg->precio4.'\',\''.$reg->precio5.'\');" data-toggle="tooltip" title="Editar"><i class="fa fa-pencil"></i> </button>&nbsp;'.
								'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarArticulo2('.$reg->idarticulo.')"><i class="fa fa-trash"></i> </button>');
}
else
{
	if ($reg->idsucursal==3) {
			$mensaje= "Cantidad Baja! :";
		     			$data[] = array("id"=>$i,
						"1"=>$reg->articulo,
						"2"=>"<span class='lilaClaro'>$reg->razon_social</span>",
						"3"=>$reg->idarticulo,
								"4"=>$reg->numero,
								"5"=>$reg->codigo_interno,

								"6"=>$reg->marca,

								"7"=>"<span>$reg->stock_actual</span>",

								"8"=>$reg->dolencia,
								"9"=>$reg->instruccion,

								"10"=>$reg->vrestringida,
								"11"=>$reg->precio_compra,
								//	"12"=>'
								//	<link rel="stylesheet" type="text/css" href="public/shadowbox/shadowbox.css">
								//	<script type="text/javascript" src="public/shadowbox/shadowbox.js"></script>
								//	<script type="text/javascript">
								//	Shadowbox.init();
								//	</script>
								//	<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=100px height=100px src="./'.$reg->imagen.'" /></a>',
									"12"=>	'<button class="btn btn-warning" onclick="cargarDataArticulo2('.$reg->iddetalle_ingreso.',\''.$reg->stock_actual.'\',\''.$reg->precio_compra.'\',\''.$reg->precio_ventapublico.'\',\''.$reg->precio2.'\',\''.$reg->precio3.'\',\''.$reg->precio4.'\',\''.$reg->precio5.'\');" data-toggle="tooltip" title="Editar"><i class="fa fa-pencil"></i> </button>&nbsp;'.
									'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarArticulo2('.$reg->idarticulo.')"><i class="fa fa-trash"></i> </button>');
	}
	else {
		if ($reg->idsucursal==4) {
				$mensaje= "Cantidad Baja! :";
			     			$data[] = array("id"=>$i,
							"1"=>$reg->articulo,
							"2"=>"<span class='verdeClaro'>$reg->razon_social</span>",
							"3"=>$reg->idarticulo,
									"4"=>$reg->numero,
									"5"=>$reg->codigo_interno,

									"6"=>$reg->marca,

									"7"=>"<span >$reg->stock_actual</span>",

									"8"=>$reg->dolencia,
									"9"=>$reg->instruccion,

									"10"=>$reg->vrestringida,
									"11"=>$reg->precio_compra,
									//	"12"=>'
									//	<link rel="stylesheet" type="text/css" href="public/shadowbox/shadowbox.css">
									//	<script type="text/javascript" src="public/shadowbox/shadowbox.js"></script>
									//	<script type="text/javascript">
									//	Shadowbox.init();
									//	</script>
									//	<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=100px height=100px src="./'.$reg->imagen.'" /></a>',
										"12"=>	'<button class="btn btn-warning" onclick="cargarDataArticulo2('.$reg->iddetalle_ingreso.',\''.$reg->stock_actual.'\',\''.$reg->precio_compra.'\',\''.$reg->precio_ventapublico.'\',\''.$reg->precio2.'\',\''.$reg->precio3.'\',\''.$reg->precio4.'\',\''.$reg->precio5.'\');" data-toggle="tooltip" title="Editar"><i class="fa fa-pencil"></i> </button>&nbsp;'.
										'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarArticulo2('.$reg->idarticulo.')"><i class="fa fa-trash"></i> </button>');
		}
		else {
			if ($reg->idsucursal==5) {
					$mensaje= "Cantidad Baja! :";
				     			$data[] = array("id"=>$i,
								"1"=>$reg->articulo,
								"2"=>"<span class='amarilloClaro'>$reg->razon_social</span>",
								"3"=>$reg->idarticulo,
										"4"=>$reg->numero,
										"5"=>$reg->codigo_interno,

										"6"=>$reg->marca,

										"7"=>"<span >$reg->stock_actual</span>",

										"8"=>$reg->dolencia,
										"9"=>$reg->instruccion,

										"10"=>$reg->vrestringida,
										"11"=>$reg->precio_compra,
										//	"12"=>'
										//	<link rel="stylesheet" type="text/css" href="public/shadowbox/shadowbox.css">
										//	<script type="text/javascript" src="public/shadowbox/shadowbox.js"></script>
										//	<script type="text/javascript">
										//	Shadowbox.init();
										//	</script>
										//	<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=100px height=100px src="./'.$reg->imagen.'" /></a>',
											"12"=>	'<button class="btn btn-warning" onclick="cargarDataArticulo2('.$reg->iddetalle_ingreso.',\''.$reg->stock_actual.'\',\''.$reg->precio_compra.'\',\''.$reg->precio_ventapublico.'\',\''.$reg->precio2.'\',\''.$reg->precio3.'\',\''.$reg->precio4.'\',\''.$reg->precio5.'\');" data-toggle="tooltip" title="Editar"><i class="fa fa-pencil"></i> </button>&nbsp;'.
											'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarArticulo2('.$reg->idarticulo.')"><i class="fa fa-trash"></i> </button>');
			}
			else {
				if ($reg->idsucursal==6) {
						$mensaje= "Cantidad Baja! :";
					     			$data[] = array("id"=>$i,
									"1"=>$reg->articulo,
									"2"=>"<span class='rojoClaro'>$reg->razon_social</span>",
									"3"=>$reg->idarticulo,
											"4"=>$reg->numero,
											"5"=>$reg->codigo_interno,

											"6"=>$reg->marca,

											"7"=>"<span >$reg->stock_actual</span>",

											"8"=>$reg->dolencia,
											"9"=>$reg->instruccion,

											"10"=>$reg->vrestringida,
											"11"=>$reg->precio_compra,
											//	"12"=>'
											//	<link rel="stylesheet" type="text/css" href="public/shadowbox/shadowbox.css">
											//	<script type="text/javascript" src="public/shadowbox/shadowbox.js"></script>
											//	<script type="text/javascript">
											//	Shadowbox.init();
											//	</script>
											//	<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=100px height=100px src="./'.$reg->imagen.'" /></a>',
												"12"=>	'<button class="btn btn-warning" onclick="cargarDataArticulo2('.$reg->iddetalle_ingreso.',\''.$reg->stock_actual.'\',\''.$reg->precio_compra.'\',\''.$reg->precio_ventapublico.'\',\''.$reg->precio2.'\',\''.$reg->precio3.'\',\''.$reg->precio4.'\',\''.$reg->precio5.'\');" data-toggle="tooltip" title="Editar"><i class="fa fa-pencil"></i> </button>&nbsp;'.
												'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarArticulo2('.$reg->idarticulo.')"><i class="fa fa-trash"></i> </button>');
				}
			}
		}
	}
}
}
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



		case "listArtElegir":
			$query_Tipo = $objArticulo->Listar();
			$data = Array();
            $i = 1;
     		while ($reg = $query_Tipo->fetch_object()) {
$numero = $reg->numero;
$unidad = $reg->unidadMedida;
$ress= "$numero". " ". $unidad  ;

     			$data[] = array(
     				"0"=>'<button type="button" class="btn btn-warning" data-toggle="tooltip" title="Agregar al detalle" onclick="Agregar('.$reg->idarticulo.',\''.$reg->nombre.'\')" name="optArtBusqueda[]" data-nombre="'.$reg->nombre.'" id="'.$reg->idarticulo.'" value="'.$reg->idarticulo.'" ><i class="fa fa-check" ></i> </button>',
     				"1"=>$i,
					"2"=>$reg->nombre,
					"3"=>$reg->categoria,
					"4"=>$ress,

					"5"=>$reg->descripcion,
					"6"=> $reg->vrestringida,
					"7"=>'<img width=100px height=100px src="./'.$reg->imagen.'" />');
				$i++;
            }

            $results = array(
            "sEcho" => 1,
        	"iTotalRecords" => count($data),
        	"iTotalDisplayRecords" => count($data),
            "aaData"=>$data);
			echo json_encode($results);

			break;

		case "listCategoria":
	        require_once "../model/Categoria.php";

	        $objCategoria = new Categoria();

	        $query_Categoria = $objCategoria->Listar();

	        while ($reg = $query_Categoria->fetch_object()) {
	            echo '<option value=' . $reg->idcategoria . '>' . $reg->nombre . '</option>';
	        }

	        break;

	    case "listUM":

	    	require_once "../model/Categoria.php";

	        $objCategoria = new Categoria();

	        $query_Categoria = $objCategoria->ListarUM();

	        while ($reg = $query_Categoria->fetch_object()) {
	            echo '<option value=' . $reg->idunidad_medida . '>' . $reg->nombre . '</option>';
	        }

	        break;


	}
