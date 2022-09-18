<?php
	require "Conexion.php";

	class articulo{


		public function __construct(){
		}

		public function Registrar($idcategoria, $idunidad_medida, $nombre, $descripcion, $imagen,$instruccion,$numero,$vrestringida,$codigo3,$minima){
			global $conexion;
			$sql = "INSERT INTO articulo(idcategoria, idunidad_medida, nombre, descripcion, imagen, estado,instruccion,numero,vrestringida,codigo_interno,minima)
						VALUES($idcategoria, $idunidad_medida, '$nombre', '$descripcion', '$imagen', 'A','$instruccion','$numero','$vrestringida','$codigo3','$minima')";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Modificar($idarticulo, $idcategoria, $idunidad_medida, $nombre, $descripcion, $imagen,$instruccion,$numero,$vrestringida,$codigo3,$minima){
			global $conexion;
			$sql = "UPDATE articulo set idcategoria = $idcategoria, idunidad_medida = $idunidad_medida, nombre = '$nombre',
						descripcion = '$descripcion', imagen = '$imagen',instruccion ='$instruccion',numero ='$numero', vrestringida= '$vrestringida',codigo_interno ='$codigo3',minima ='$minima'
						WHERE idarticulo = $idarticulo";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Eliminar($idarticulo){
			global $conexion;
			$sql = "delete from articulo WHERE idarticulo = '$idarticulo'";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Listar(){
			global $conexion;
			$sql = "select a.*, c.nombre as categoria, um.nombre as unidadMedida, um.prefijo as procedencia
			from articulo a inner join categoria c on a.idcategoria = c.idcategoria inner join unidad_medida um on
			a.idunidad_medida = um.idunidad_medida where a.estado = 'A' order by idarticulo desc ";
			$query = $conexion->query($sql);
			return $query;
		}
		public function GetMargen(){
			global $conexion;
			$sql = "select margen from global";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarArticulosxSucursal($idsucursalorigen){
			global $conexion;
			$sql = "SELECT DISTINCT di.iddetalle_ingreso, di.idingreso, di.stock_actual, di.precio_compra, di.precio_ventadistribuidor, a.nombre AS Articulo, a.codigo_interno, a.minima, a.idarticulo, di.codigo, di.serie AS caducidad, di.precio_ventapublico, a.imagen, a.descripcion, a.numero, a.instruccion, a.vrestringida, di.serie, i.fecha, c.nombre AS presentacion, u.nombre AS unidad_medida, u.prefijo, di.precio2, di.precio3, di.precio4, di.precio5, di.precio5
			FROM ingreso i INNER JOIN detalle_ingreso di ON di.idingreso = i.idingreso INNER JOIN sucursal s ON s.idsucursal = i.idsucursal INNER JOIN articulo a ON di.idarticulo = a.idarticulo INNER JOIN categoria c ON a.idcategoria = c.idcategoria INNER JOIN unidad_medida u ON a.idunidad_medida = u.idunidad_medida
				where i.estado = 'A' and i.idsucursal = $idsucursalorigen and di.stock_actual > 0 order by a.idarticulo desc";
			$query = $conexion->query($sql);
			return $query;
		}


		public function Reporte(){
			global $conexion;
			$sql = "select a.*, c.nombre as categoria, um.nombre as unidadMedida, um.prefijo as procedencia
	from articulo a inner join categoria c on a.idcategoria = c.idcategoria
	inner join unidad_medida um on a.idunidad_medida = um.idunidad_medida where a.estado = 'A' order by a.nombre asc";
			$query = $conexion->query($sql);
			return $query;
		}

	}
