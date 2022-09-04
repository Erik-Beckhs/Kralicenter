<?php
	require "Conexion.php";
	require "get_date.php";

	class DocSucursal{


		public function Registrar($detalle,$id_sucursal,$motivo,$monto,$id_usuario,$tipo_transaccion){
			global $conexion;
			$sw = true;
			//0 = idsucursal
			//1 = tipo Documento
			//3= monto
			//4 = razon

				foreach($detalle as $indice => $valor){

					$sql_detalle = "INSERT INTO movimiento_caja(idingresocaja,motivo,monto, idusuario,idsucursal,fecha,tipo_operacion)
											VALUES(null,'$valor[3]','$valor[4]' , '$id_usuario', '$id_sucursal',now(),'$tipo_transaccion')";

					$conexion->query($sql_detalle) or $sw = false;
				}


		return $sw;
		}






		public function Modificar($iddetalle_documento_sucursal, $idsucursal, $motivo, $monto,$usuario, $transaccion){
			global $conexion;
			$sql = "UPDATE movimiento_caja set motivo = '$motivo',
						monto = $monto, fecha = now() , tipo_operacion ='$transaccion'
						where idingresocaja = $iddetalle_documento_sucursal AND idsucursal = $idsucursal  AND  idusuario = $usuario " ;
			$query = $conexion->query($sql);
			return $query;
		}

		public function Eliminar($iddetalle_documento_sucursal){
			global $conexion;
			$sql = "DELETE FROM movimiento_caja
						where idingresocaja = $iddetalle_documento_sucursal";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarTipoDocumento(){
			global $conexion;
			$sql = "select * from tipo_documento where operacion = 'Operacion'";
			$query = $conexion->query($sql);
			return $query;
		}
		public function Listar_tipo_movimiento($nombre_operacion){
			global $conexion;
			$sql = "select idtipo_documento from tipo_documento where nombre = '$nombre_operacion'";
			$query = $conexion->query($sql);
			return $query;
		}




		public function ListarDetalleDocSuc($idsucursal,$idusuario){
/*$obj = new get_date();
$fecha_servidor = $obj->get_fecha();
$x= (string)$fecha_servidor;
*/
$fecha = date("Y-m-d");
$fecha2 = date("Y-m-d");

			global $conexion;
			$sql = "select  idingresocaja,motivo,monto,idusuario,idsucursal,fecha,tipo_operacion from
			movimiento_caja where fecha BETWEEN '$fecha' and  '$fecha2' and  idsucursal= $idsucursal and idusuario = $idusuario";
			$query = $conexion->query($sql);
			return $query;
		}

	}
