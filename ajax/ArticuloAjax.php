<?php

	session_start();

	require_once "../model/Articulo.php";

	$objArticulo = new articulo();

	switch ($_GET["op"]) {

		case 'SaveOrUpdate':

			$idcategoria = $_POST["cboCategoria"];
			$idunidad_medida = $_POST["cboUnidadMedida"];
			$nombre = $_POST["txtNombre"];
			$descripcion = $_POST["txtDescripcion"];
			$imagen = $_FILES["imagenArt"]["tmp_name"];
			$ruta = $_FILES["imagenArt"]["name"];
			$instruccion = $_POST["instruccion"];
			$numero = $_POST["numero"];
			$vrestringida = $_POST["vrestringida"];
     	$codigo3 = $_POST["txtcodigo3"];
			$minima = $_POST["minima"];

			if(move_uploaded_file($imagen, "../Files/Articulo/".$ruta)){

				if(empty($_POST["txtIdArticulo"])){

					if($objArticulo->Registrar($idcategoria, $idunidad_medida, $nombre, $descripcion, "Files/Articulo/".$ruta,$instruccion,$numero,$vrestringida,$codigo3,$minima)){
						echo "Articulo Registrado";
					}else{
						echo "Articulo no ha podido ser registado.";
					}
				}else{

					$idarticulo = $_POST["txtIdArticulo"];
					if($objArticulo->Modificar($idarticulo, $idcategoria, $idunidad_medida, $nombre, $descripcion, "Files/Articulo/".$ruta,$instruccion,$numero,$vrestringida,$codigo3,$minima)){
						echo "Informacion del Articulo ha sido actualizada";
					}else{
						echo "Informacion del Articulo no ha podido ser actualizada.";
					}
				}
			} else {
				$ruta_img = $_POST["txtRutaImgArt"];
				if(empty($_POST["txtIdArticulo"])){

					if($objArticulo->Registrar($idcategoria, $idunidad_medida, $nombre, $descripcion, $ruta_img,$instruccion,$numero,$vrestringida,$codigo3,$minima)){
						echo "Articulo Registrado";
					}else{
						echo "Articulo no ha podido ser registado.";
					}
				}else{

					$idarticulo = $_POST["txtIdArticulo"];
					if($objArticulo->Modificar($idarticulo, $idcategoria, $idunidad_medida, $nombre, $descripcion, $ruta_img,$instruccion,$numero,$vrestringida,$codigo3,$minima)){
						echo "Informacion del Articulo ha sido actualizada";
					}else{
						echo "Informacion del Articulo no ha podido ser actualizada.";
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





		case "list":
			$query_Tipo = $objArticulo->Listar();
			$data = Array();
            $i = 1;
     		while ($reg = $query_Tipo->fetch_object()) {
	$numero = $reg->numero;
$unidad = $reg->unidadMedida;
$minima = $reg->minima;

$codigo_interno=$reg->codigo_interno;

     			$data[] = array("id"=>$i,
				"1"=>$reg->nombre,
				"2"=>$reg->idarticulo,
				"3"=>$numero,
				"4"=>$codigo_interno,
				"5"=>$reg->categoria,
				"6"=>$unidad,
				"7"=>$reg->descripcion,
				"8"=>$reg->instruccion,
				"9"=>$reg->vrestringida,
          		"10"=>$minima,
				"11"=>'<button class="btn btn-warning" data-toggle="tooltip" title="Editar" onclick="cargarDataArticuloProd('.$reg->idarticulo.',\''.$reg->idcategoria.'\',\''.$reg->idunidad_medida.'\',\''.$reg->nombre.'\',\''.$reg->descripcion.'\',\''.$reg->instruccion.'\',\''.$numero.'\',\''.$reg->vrestringida.'\',\''.$reg->codigo_interno.'\',\''.$minima.'\');"><i class="fa fa-pencil"></i> </button>&nbsp;'.
				'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarArticulo('.$reg->idarticulo.');"><i class="fa fa-trash"></i> </button>');
				$i++;
			}
			$results = array(
            "sEcho" => 1,
        	"iTotalRecords" => count($data),
        	"iTotalDisplayRecords" => count($data),
            "aaData"=>$data);
			echo json_encode($results);

			break;

			case "listArtElegirIng":
	//******************JALAMOS EL MARGEN DE UTILIDAD ********************
			require_once "../model/Configuracion.php";
			$objGlobal = new Configuracion();
		$result = $objGlobal->listar();
		$regi = $result->fetch_object();
		$margen=$regi->margen;
//********************FIN MARGEN UTILIDAD***********************************
				$query_Tipo = $objArticulo->Listar();
				$data = Array();
	            $i = 1;
	     		while ($reg = $query_Tipo->fetch_object()) {
	$numero = $reg->numero;
	$unidad = $reg->unidadMedida;



	$minima = $reg->minima;

	$procedencia = $reg->procedencia;
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
							<button type="button" class="btn btn-warning" data-toggle="tooltip" title="Agregar al detalle" onclick="Agregar('.$reg->idarticulo.',\''.$reg->nombre.'\',\''.$margen.'\',\''.$reg->codigo_interno.'\');changeColor(this); "  name="optArtBusqueda[]" data-nombre="'.$reg->nombre.'" id="'.$reg->idarticulo.'" value="'.$reg->idarticulo.'" ><i class="fa fa-check" ></i> </button>',
	     				"1"=>$i,
							"2"=>$reg->nombre,
						"3"=>$reg->idarticulo,
						"4"=>$numero,
						"5"=>$reg->codigo_interno,
						"6"=>$reg->instruccion,
						"7"=>$reg->categoria,
						"8"=>$unidad,

						"9"=>$reg->procedencia,
						"10"=> $reg->vrestringida);


						/*"11"=>'<link rel="stylesheet" type="text/css" href="public/shadowbox/shadowbox.css">
						<script type="text/javascript" src="public/shadowbox/shadowbox.js"></script>
						<script type="text/javascript">
						Shadowbox.init();
						</script>
						<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=100px height=100px src="./'.$reg->imagen.'" /></a>');*/
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
$minima = $reg->minima;

$procedencia = $reg->procedencia;
     			$data[] = array(
     				"0"=>'<button type="button" class="btn btn-warning" data-toggle="tooltip" title="Agregar al detalle" onclick="Agregar('.$reg->idarticulo.',\''.$reg->nombre.'\')" name="optArtBusqueda[]" data-nombre="'.$reg->nombre.'" id="'.$reg->idarticulo.'" value="'.$reg->idarticulo.'" ><i class="fa fa-check" ></i> </button>',
     				"1"=>$i,
						"2"=>$reg->nombre,
					"3"=>$reg->idarticulo,
					"4"=>$numero,
					"5"=>$reg->codigo_interno,
					"6"=>$reg->categoria,
					"7"=>$unidad,

					"8"=>$reg->descripcion,
					"9"=> $reg->vrestringida,
					"10"=>$minima,

					"11"=>'<img width=100px height=100px src="./'.$reg->imagen.'" />');
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
