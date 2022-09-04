<?php
	require "Conexion.php";

	class articuloAlmacen{


		public function __construct(){
		}

		public function Registrar($idcategoria, $idunidad_medida, $nombre, $descripcion, $imagen,$instruccion,$numero,$vrestringida){
			global $conexion;
			$sql = "INSERT INTO articulo(idcategoria, idunidad_medida, nombre, descripcion, imagen, estado,instruccion,numero,vrestringida)
						VALUES($idcategoria, $idunidad_medida, '$nombre', '$descripcion', '$imagen', 'A','$instruccion','$numero','$vrestringida')";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Modificar($stock_actual,$stock_ingreso,$venta1,$venta2,$venta3,$venta4,$venta5,$compra ,$iddetalle_ingreso){
			global $conexion;
			$sql = "UPDATE detalle_ingreso set stock_actual = '$stock_actual', stock_ingreso = '$stock_ingreso', precio_ventapublico = $venta1, precio2 = $venta2, precio3 = $venta3, precio4 = $venta4, precio5=$venta5, precio_compra = $compra

						WHERE iddetalle_ingreso = $iddetalle_ingreso ";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Eliminar($idarticulo){
			global $conexion;
			$sql = "delete from detalle_ingreso WHERE idarticulo = $idarticulo";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Listar(){
			global $conexion;
			$sql = "SELECT a.nombre AS articulo, s.razon_social, s.idsucursal, a.codigo_interno AS codigo_interno, a.minima, a.idarticulo AS idarticulo, a.imagen AS imagen, a.descripcion AS dolencia, a.instruccion AS instruccion, a.numero AS numero, a.vrestringida AS vrestringida, di.*, SUM(di.stock_ingreso) as stock, ( di.stock_ingreso * di.precio_compra ) AS sub_total, un.nombre AS marca, un.prefijo AS procedencia FROM detalle_ingreso di INNER JOIN articulo a ON di.idarticulo = a.idarticulo INNER JOIN unidad_medida un ON a.idunidad_medida = un.idunidad_medida INNER JOIN ingreso i ON di.idingreso = i.idingreso INNER JOIN sucursal s ON i.idsucursal = s.idsucursal GROUP BY di.codigo ORDER BY a.nombre ASC;";
			$query = $conexion->query($sql);
			return $query;
		}
		/*                   ************************Listar sin sucursal**********************
		public function Listar(){
			global $conexion;
			$sql = "SELECT a.nombre AS articulo, a.codigo_interno AS codigo_interno, a.minima,a.idarticulo AS idarticulo, a.imagen AS imagen, a.descripcion AS dolencia, a.instruccion AS instruccion, a.numero AS numero, a.vrestringida AS vrestringida, di.*, ( di.stock_ingreso * di.precio_compra ) AS sub_total, un.nombre as marca, un.prefijo as procedencia FROM detalle_ingreso di INNER JOIN articulo a ON di.idarticulo = a.idarticulo INNER JOIN unidad_medida un ON a.idunidad_medida = un.idunidad_medida WHERE di.idingreso = idingreso";
			$query = $conexion->query($sql);
			return $query;
		}
*/

		public function Reporte(){
			global $conexion;
			$sql = "select a.*, c.nombre as categoria, um.nombre as unidadMedida
	from articulo a inner join categoria c on a.idcategoria = c.idcategoria
	inner join unidad_medida um on a.idunidad_medida = um.idunidad_medida where a.estado = 'A' order by a.nombre asc";
			$query = $conexion->query($sql);
			return $query;
		}

	}
