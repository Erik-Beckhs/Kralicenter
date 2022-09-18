<?php
	require "Conexion.php";

	class Traspaso {


		public function __construct(){
		}

		public function Registrar($idusuario, $idsucursalorigen, $idsucursaldestino, $fecha, $motivo, $detalle) {
			global $conexion;
			$sql = "INSERT INTO traspaso (idusuario, idsucursalorigen, idsucursaldestino, fecha, motivo, estado)
						VALUES($idusuario, $idsucursalorigen, $idsucursaldestino, '$fecha', '$motivo', 'P')";
			$query = $conexion->query($sql);
			$idtraspaso=$conexion->insert_id;

			$conexion->autocommit(true);
// actualizamos la tabla de idingreso de los productos
// [5] es igual  al sotk actual del producto  [3] cantidad  que estoy comprando entonces restamos y colocamos el nuevo stock
			foreach($detalle as $indice => $valor) {

				$idingresoanterior = $valor[8];
				$idingresonuevo = 0;
// Verificamos si ya se ha creado un idingreso para el artículo
				$get_idingresonuevo ="select idingresonuevo from detalle_traspaso where idtraspaso = $idtraspaso and idingresoanterior = $idingresoanterior";
				$consultaidingresonuevo =  $conexion->query($get_idingresonuevo);

				while ($regidingreso = $consultaidingresonuevo->fetch_object()) {
					$idingresonuevo = $regidingreso->idingresonuevo;
				}

				$sql_detalle = "UPDATE detalle_ingreso set stock_actual = ".$valor[5]." - ".$valor[3]." where iddetalle_ingreso = ".$valor[0]."";
				$conexion->query($sql_detalle) ;

				if ($idingresonuevo==0) {
// Cargar los datos del ingreso desde donde se traspasan los artículos
					$sql_cargar = "SELECT * FROM ingreso WHERE idingreso = $idingresoanterior";
					$ingreso_cargado = $conexion->query($sql_cargar);
		            while ($reg_cargado = $ingreso_cargado->fetch_object()) {
		            	$idproveedor = $reg_cargado->idproveedor;
		            	$serie_comprobante = $reg_cargado->serie_comprobante;
		            	$codigo_control = $reg_cargado->codigo_control;
		            	$tipo_ingreso = $reg_cargado->tipo_ingreso;
		            	$numero_autorizacion = $reg_cargado->numero_autorizacion;
		            	$impuesto = $reg_cargado->impuesto;
		            	$total = $reg_cargado->total;
		            }
// Agregar en ingreso la nueva sucursal de los artículos traspasados.
					$sql_insertar = "INSERT INTO ingreso (idusuario, idsucursal, idproveedor, tipo_comprobante, serie_comprobante, codigo_control, tipo_ingreso, numero_autorizacion,fecha, impuesto,
							total, estado)
							VALUES($idusuario, $idsucursaldestino, $idproveedor, 'TRASPASO', '$serie_comprobante','$codigo_control','$tipo_ingreso', '$numero_autorizacion','$fecha', $impuesto, $total, 'A')";
					$conexion->query($sql_insertar);
					$idingresonuevo=$conexion->insert_id;
				}

// Agregar en detalle_ingreso los artículos traspasados a la nueva sucursal.
				$sql_detalleingreso = "INSERT INTO detalle_ingreso (idingreso, idarticulo, codigo, serie, descripcion, stock_ingreso, stock_actual, precio_compra, precio_ventadistribuidor, precio_ventapublico, precio2, precio3, precio4, precio5)
				VALUES($idingresonuevo, ".$valor[9].", '".$valor[6]."', '".$valor[7]."', '".$valor[10]."', ".$valor[3].", ".$valor[3].", ".$valor[11].", ".$valor[12].", ".$valor[2].", ".$valor[13].", ".$valor[14].", ".$valor[15].", ".$valor[16].")";
				$conexion->query($sql_detalleingreso) or $sw = false;

// ahora agregamos lo que falta en la tabla detalle_traspaso despues de recuperar el idtraspaso
				$sql_detalle = "INSERT INTO detalle_traspaso (idtraspaso, iddetalle_ingreso, stock_actual, stock_traspaso, idingresoanterior, idingresonuevo)
										VALUES($idtraspaso, ".$valor[0].", ".$valor[5].", ".$valor[3].", ".$idingresoanterior.", ".$idingresonuevo.")";
				$conexion->query($sql_detalle);

			}
// ahora agregamos lo que falta en la tabla detalle_traspaso despues de recuperar el idtraspaso
/*			foreach($detalle as $indice => $valor){
				$sql_detalle = "INSERT INTO detalle_traspaso (idtraspaso, iddetalle_ingreso, stock_actual, stock_traspaso, idingresoanterior, idingresonuevo)
										VALUES($idtraspaso, ".$valor[0].", ".$valor[5].", ".$valor[3].", ".$idingresoanterior.", ".$idingresonuevo.")";
				$conexion->query($sql_detalle);
			}
*/
			return $query;
		}



		public function Modificar($idusuario,$idtraspaso,$idsucursalorigen,$idsucursaldestino){
//		public function Modificar($idusuario,$idtraspaso,$idsucursalorigen,$idsucursaldestino,$fecha,$motivo){
			global $conexion;
//			$sql = "UPDATE traspaso set idusuario = $idusuario, idsucursalorigen = $idsucursalorigen, idsucursaldestino = $idsucursaldestino, fecha = '$fecha', motivo = '$motivo'
			$sql = "UPDATE traspaso set idusuario = $idusuario, idsucursalorigen = $idsucursalorigen, idsucursaldestino = $idsucursaldestino 
						WHERE idtraspaso = $idtraspaso";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Eliminar($idtraspaso){
			global $conexion;

			$get_idrestaurar ="select * from detalle_traspaso where idtraspaso = $idtraspaso";
			$consultacantidadrestaurar =  $conexion->query($get_idrestaurar);

			while ($regidrestaurar = $consultacantidadrestaurar->fetch_object()) {
				$iddetalle_ingreso = $regidrestaurar->iddetalle_ingreso;
				$stock_traspaso = $regidrestaurar->stock_traspaso;
				$idingresonuevo = $regidrestaurar->idingresonuevo;

				$sql_actualizar = "UPDATE detalle_ingreso set stock_actual = stock_actual + " . $stock_traspaso . " where iddetalle_ingreso = ". $iddetalle_ingreso ."";
				$conexion->query($sql_actualizar) ;
			}
			
// Eliminar el Ingreso a la nueva Sucursal
			$sql = "DELETE FROM ingreso WHERE idingreso = $idingresonuevo";
			$query = $conexion->query($sql);
// Eliminar el Detalle del Ingreso
			$sql = "DELETE FROM detalle_ingreso WHERE idingreso = $idingresonuevo";
			$query = $conexion->query($sql);
// Eliminar el Traspaso
			$sql = "DELETE FROM traspaso WHERE idtraspaso = $idtraspaso";
			$query = $conexion->query($sql);
// Eliminar el Detalle del Traspaso
			$sql = "DELETE FROM detalle_traspaso WHERE idtraspaso = $idtraspaso";
			$query = $conexion->query($sql);

			return $query;
		}

		public function Listar(){
			global $conexion;
			$sql = "SELECT * FROM traspaso order by idtraspaso desc";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarDatosCompletos(){
			global $conexion;
			$sql = "SELECT t.* , e.nombre nombreusuario, s.razon_social sucursalorigen, s.num_documento numdocumentoso, s.direccion direccionso, s.telefono telefonoso, s.email emailso, s.logo logoso, s2.razon_social sucursaldestino, s2.num_documento numdocumentosd, s2.direccion direccionsd, s2.telefono telefonosd, s2.email emailsd, s2.logo logosd 
			          FROM traspaso t, usuario u, empleado e, sucursal s, sucursal s2 
			         WHERE t.idusuario = u.idusuario and u.idempleado = e.idempleado and t.idsucursalorigen = s.idsucursal and t.idsucursaldestino = s2.idsucursal
			         ORDER BY idtraspaso DESC";
			$query = $conexion->query($sql);
			return $query;
		}

		public function GetSucursalTraspaso($idtraspaso){
			global $conexion;
			$sql = "SELECT t.* , e.nombre nombreusuario, s.razon_social sucursalorigen, s.num_documento numdocumentoso, s.direccion direccionso, s.telefono telefonoso, s.email emailso, s.logo logoso, s2.razon_social sucursaldestino, s2.num_documento numdocumentosd, s2.direccion direccionsd, s2.telefono telefonosd, s2.email emailsd, s2.logo logosd 
			          FROM traspaso t, usuario u, empleado e, sucursal s, sucursal s2 
			         WHERE t.idusuario = u.idusuario and u.idempleado = e.idempleado and t.idsucursalorigen = s.idsucursal and t.idsucursaldestino = s2.idsucursal and t.idtraspaso = $idtraspaso 
			         ORDER BY idtraspaso DESC";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Reporte(){
			global $conexion;
			$sql = "SELECT * FROM traspaso order by idtraspaso asc";
			$query = $conexion->query($sql);
			return $query;
		}

		public function GetDetalleTraspaso($idtraspaso){
			global $conexion;
			$sql = "select a.nombre as articulo,a.idarticulo, dg.codigo, dg.serie, dt.*
			from traspaso t inner join detalle_traspaso dt on t.idtraspaso = dt.idtraspaso 
			inner join detalle_ingreso dg on dt.iddetalle_ingreso = dg.iddetalle_ingreso
			inner join articulo a on dg.idarticulo = a.idarticulo
			where t.idtraspaso  = $idtraspaso";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ImprimirDetalleTraspaso($idTraspaso){
			global $conexion;
			$sql = "SELECT traspaso.fecha, traspaso.motivo, detalle_traspaso.stock_traspaso ,articulo.nombre, articulo.codigo_interno, (select nombre from unidad_medida where unidad_medida.idunidad_medida=articulo.idunidad_medida) as marca FROM traspaso 
			INNER join detalle_traspaso on traspaso.idtraspaso=detalle_traspaso.idtraspaso INNER JOIN detalle_ingreso on detalle_ingreso.iddetalle_ingreso=detalle_traspaso.iddetalle_ingreso
			INNER join articulo on articulo.idarticulo =detalle_ingreso.idarticulo WHERE traspaso.idtraspaso=$idTraspaso";
			$query = $conexion->query($sql);
			return $query;
		}

	}
