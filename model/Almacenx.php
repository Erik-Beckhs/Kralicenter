<?php
	require "Conexion.php";

	class articulo{
	
		
		public function __construct(){
		}

		public function Registrar($idcategoria, $idunidad_medida, $nombre, $descripcion, $imagen,$instruccion,$numero,$vrestringida){
			global $conexion;
			$sql = "INSERT INTO articulo(idcategoria, idunidad_medida, nombre, descripcion, imagen, estado,instruccion,numero,vrestringida)
						VALUES($idcategoria, $idunidad_medida, '$nombre', '$descripcion', '$imagen', 'A','$instruccion','$numero','$vrestringida')";
			$query = $conexion->query($sql);
			return $query;
		}
		
		public function Modificar($idarticulo, $idcategoria, $idunidad_medida, $nombre, $descripcion, $imagen,$instruccion,$numero,$vrestringida){
			global $conexion;
			$sql = "UPDATE articulo set idcategoria = $idcategoria, idunidad_medida = $idunidad_medida, nombre = '$nombre',
						descripcion = '$descripcion', imagen = '$imagen',instruccion ='$instruccion',numero ='$numero' , vrestringida= '$vrestringida'
						WHERE idarticulo = $idarticulo";
			$query = $conexion->query($sql);
			return $query;
		}
		
		public function Eliminar($idarticulo){
			global $conexion;
			$sql = "delete from articulo WHERE idarticulo = $idarticulo";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Listar(){
			global $conexion;
			$sql = "
select a.nombre as articulo,a.imagen as imagen,a.descripcion as dolencia, a.instruccion as instruccion, a.numero as numero,a.vrestringida as vrestringida, di.*, (di.stock_ingreso * di.precio_compra) as sub_total
	from detalle_ingreso di
	inner join articulo a on di.idarticulo = a.idarticulo where di.idingreso = idingreso";
			$query = $conexion->query($sql);
			return $query;
		}


		public function Reporte(){
			global $conexion;
			$sql = "select a.*, c.nombre as categoria, um.nombre as unidadMedida 
	from articulo a inner join categoria c on a.idcategoria = c.idcategoria
	inner join unidad_medida um on a.idunidad_medida = um.idunidad_medida where a.estado = 'A' order by a.nombre asc";
			$query = $conexion->query($sql);
			return $query;
		}

	}
