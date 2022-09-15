<?php
	require "Conexion.php";

	class Pedido{


				public function Registrar_Ticket($idUsuario, $idSucursal, $tipo_comprobante,$Fecha_emision_factura, $Total_venta,$detalle,
      $nombre_cliente,$Documento_cliente,$numero_TF,$recibi,$cambio,$idclientet,$tipo_pago,$tipo_venta,$descuento){
					global $conexion;
					$sw = true;
					$idVenta = "";
					try {
// Registramos los productos en la tabla venta
						$sql = "INSERT INTO venta(idCliente,idusuario,idSucursal,tipo_comprobante,CodigoAutorizacion,num_comprobante, fecha ,total,estado,Recibi,Cambio,estado_factura,tipo_pago,tipo_venta,descuento,tiempo_entrega,fecha_validez)
								VALUES('$idclientet','$idUsuario','$idSucursal','$tipo_comprobante',null,'$numero_TF', curdate(),'$Total_venta','A','$recibi','$cambio',null,'$tipo_pago','$tipo_venta','$descuento','-',curdate())";
						//var_dump($sql);
						$conexion->query($sql);
// recuperamos el idventa de la tabla venta
						 $get_id_venta ="select  idventa  from venta where num_comprobante = '$numero_TF' and  total = '$Total_venta' and Recibi= '$recibi' ";
						$consulta=  $conexion->query($get_id_venta);

						 while ($reg = $consulta->fetch_object()) {
							$idVenta=		 $reg->idventa;
							}
// ahora agregamos lo que falta en la tabla venta despues de recuperar el idVenta
						$conexion->autocommit(true);
						foreach($detalle as $indice => $valor){
							$sql_detalle = "INSERT INTO detalle_pedido(idventa, iddetalle_ingreso, cantidad, precio_venta,estado_pedido)
													VALUES($idVenta, ".$valor[0].", ".$valor[3].", ".$valor[2].",'A')";
							$conexion->query($sql_detalle);
						}
// actualizamos la tabla de idingreso de los productos
// [5] es igual  al sotk actual del producto  [3] cantidad  que estoy comprando entonces restamos y colocamos el nuevo stock
										foreach($detalle as $indice => $valor){
											$sql_detalle = "UPDATE detalle_ingreso set stock_actual = ".$valor[5]." - ".$valor[3]." where iddetalle_ingreso = ".$valor[0]."";
											$conexion->query($sql_detalle) ;
										}
// ahora por ultimo vamos a actualziar el valor  del ticket   +1 cada ves que compre
$entero = intval($numero_TF);

$cant_letra = strlen($entero);

$parte_izquierda = substr($numero_TF, 0, -$cant_letra);

$suma = $entero + 1;

$numero = $parte_izquierda."".$suma;
//Recuperamos el id  de ticket
$get_id_ticket ="select  idtipo_documento  from tipo_documento where nombre = '$tipo_comprobante'";
$consultax=  $conexion->query($get_id_ticket);

while ($regx = $consultax->fetch_object()) {
 $iddo =		 $regx->idtipo_documento;
 }

$sql_detalle_doc = "UPDATE detalle_documento_sucursal set ultimo_numero = '$numero' where idtipo_documento = '$iddo'";
//var_dump($sql);
$conexion->query($sql_detalle_doc)or $sw = false;







						if ($conexion != null) {
		                	$conexion->close();
		            	}

					} catch (Exception $e) {
						$conexion->rollback();
					}
					return $idVenta;
				}













public function registrarCliente_Para_Factura($tipo_persona,$nombre,$objTipo_Documento,$numDocumento){


global $conexion;
$Consulta ="INSERT INTO persona ( tipo_persona, nombre, tipo_documento,num_documento,direccion_departamento,direccion_provincia, direccion_distrito,direccion_calle, telefono, email, numero_cuenta, estado)
VALUES ('$tipo_persona', '$nombre', '$objTipo_Documento', '$numDocumento', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'A')
";

	$conexion->query($Consulta);

	$idclientex = "SELECT idpersona from persona where tipo_persona ='$tipo_persona' and nombre ='$nombre' and tipo_documento='$objTipo_Documento' and '$numDocumento' = '$numDocumento' " ;
$guardan= $conexion->query($idclientex);
	while ($reg = $guardan->fetch_object()) {
	 $idpersona=		 $reg->idpersona;
	 }

return $idpersona;

}










		public function Registrar_Factura($idUsuario, $idSucursal, $tipo_comprobante,$Fecha_emision_factura, $Total_venta,$detalle,
		$nombre_cliente,$Documento_cliente,$numero_TF,$recibi,$cambio,$Codigo_Control,$idcliente){
			global $conexion;
			$sw = true;
			$idVenta = "";
			try {
// Registramos los productos en la tabla venta
				$sql = "INSERT INTO venta(idCliente,idusuario,idSucursal,tipo_comprobante,CodigoAutorizacion,num_comprobante, fecha ,total,estado,Recibi,Cambio,estado_factura)
						VALUES('$idcliente','$idUsuario','$idSucursal','$tipo_comprobante','$Codigo_Control','$numero_TF', '$Fecha_emision_factura','$Total_venta','A','$recibi','$cambio','V')";
				//var_dump($sql);
				$conexion->query($sql);
// recuperamos el idventa de la tabla venta
				 $get_id_venta ="select  idventa  from venta where num_comprobante = '$numero_TF' and  total = '$Total_venta' and Recibi= '$recibi' ";
				$consulta=  $conexion->query($get_id_venta);

				 while ($reg = $consulta->fetch_object()) {
					$idVenta=		 $reg->idventa;
					}
// ahora agregamos lo que falta en la tabla venta despues de recuperar el idVenta
				$conexion->autocommit(true);
				foreach($detalle as $indice => $valor){
					$sql_detalle = "INSERT INTO detalle_pedido(idventa, iddetalle_ingreso, cantidad, precio_venta,estado_pedido)
											VALUES($idVenta, ".$valor[0].", ".$valor[3].", ".$valor[2].",'A')";
					$conexion->query($sql_detalle);
				}
// actualizamos la tabla de idingreso de los productos
// [5] es igual  al sotk actual del producto  [3] cantidad  que estoy comprando entonces restamos y colocamos el nuevo stock
								foreach($detalle as $indice => $valor){
									$sql_detalle = "UPDATE detalle_ingreso set stock_actual = ".$valor[5]." - ".$valor[3]." where iddetalle_ingreso = ".$valor[0]."";
									$conexion->query($sql_detalle) ;
								}
// ahora por ultimo vamos a actualziar el valor  del ticket   +1 cada ves que compre
$entero = intval($numero_TF);

$cant_letra = strlen($entero);

$parte_izquierda = substr($numero_TF, 0, -$cant_letra);

$suma = $entero + 1;

$numero = $parte_izquierda."".$suma;
//Recuperamos el id  de ticket
$get_id_ticket ="select  idtipo_documento  from tipo_documento where nombre = '$tipo_comprobante'";
$consultax=  $conexion->query($get_id_ticket);

while ($regx = $consultax->fetch_object()) {
$iddo =		 $regx->idtipo_documento;
}

$sql_detalle_doc = "UPDATE detalle_documento_sucursal set ultimo_numero = '$numero' where idtipo_documento = '$iddo'";
//var_dump($sql);
$conexion->query($sql_detalle_doc);
//or $sw = false;







				if ($conexion != null) {
									$conexion->close();
							}

			} catch (Exception $e) {
				$conexion->rollback();
			}
			return $idVenta;
		}












// LISTAMOS LA INFORMACION QUE SE VE CUANDO ENTRAMOS AL AREA VENTAS LO PRIMERO QUE SE VE
		public function Listar($idsucursal){
			global $conexion;
			$sql = "SELECT venta.idventa ,persona.nombre,fecha,total,tipo_comprobante  ,venta.estado estado_pedido from persona,venta
WHERE venta.idSucursal = $idsucursal
and venta.idCliente =persona.idpersona
and venta.tipo_comprobante <> 'PROFORMA'
order by venta.idventa desc limit 0,2999" ;
/*
			$sql = "select venta.idventa ,persona.nombre,fecha,total,tipo_comprobante  ,venta.estado
from venta left join persona on venta.idcliente = persona.idpersona
where venta.idsucursal = $idsucursal and venta.tipo_comprobante <> 'PROFORMA' order by venta.idventa desc limit 0,2999"
*/

			$query = $conexion->query($sql);
			return $query;
		}

		public function VerVenta($idpedido){
			global $conexion;
			$sql = "select * from venta where idpedido = $idpedido";
			$query = $conexion->query($sql);
			return $query;
		}

		public function TotalPedido($idpedido){
			global $conexion;
			$sql = "select sum((cantidad * precio_venta) - descuento) as Total
	from detalle_pedido where idpedido = $idpedido";
			$query = $conexion->query($sql);
			return $query;
		}

		public function CambiarEstado($idpedido, $detalle){
			global $conexion;
			$sw = true;
			try {

				$sql = "UPDATE detalle_pedido set estado_pedido = 'C'
						WHERE idventa = $idpedido";
				//var_dump($sql);
				$conexion->query($sql);

				$sql2 = "UPDATE venta set estado = 'C', estado_factura ='A'
						WHERE idventa = $idpedido";
				//var_dump($sql);
				$conexion->query($sql2);

				$conexion->autocommit(true);
				foreach($detalle as $indice => $valor){
					$sql_detalle = "UPDATE detalle_ingreso SET stock_actual = stock_actual + ".$valor[1]." WHERE iddetalle_ingreso = ".$valor[0]."";
					$conexion->query($sql_detalle) or $sw = false;
				}
				if ($conexion != null) {
                	$conexion->close();
            	}
			} catch (Exception $e) {
				$conexion->rollback();
			}
			return $sw;
		}

		public function ProformaaFactura($idpedido){
			global $conexion;
			$sw = true;
			try {

				$sql = "UPDATE venta set estado = 'F'
						WHERE idventa = $idpedido";
				//var_dump($sql);
				$conexion->query($sql);

				if ($conexion != null) {
                	$conexion->close();
            	}
			} catch (Exception $e) {
				$conexion->rollback();
			}
			return $sw;
		}

		public function EliminarPedido($idpedido){
			global $conexion;
			$sql = "DELETE FROM detalle_pedido
						WHERE idpedido = $idpedido";
			$query = $conexion->query($sql);

			$sql = "DELETE FROM pedido
						WHERE idpedido = $idpedido";
			$query = $conexion->query($sql);
			return $query;
		}

		public function GetPrimerCliente()
		{
			global $conexion;
			$sql = "select idpersona,nombre from persona where tipo_persona='Cliente' order by idpersona limit 0,1";
			$query = $conexion->query($sql);
			return $query;
		}


		public function TraerCantidad($idpedido){
			global $conexion;
			$sql = "select iddetalle_ingreso, cantidad from detalle_pedido where idventa = $idpedido";
			$query = $conexion->query($sql);
			return $query;
		}


		public function GetDetallePedido($idpedido){
			global $conexion;
			$sql = "select a.nombre as articulo, dg.codigo, dg.serie, dp.*
			from venta v inner join detalle_pedido dp on v.idventa = dp.idventa
			inner join detalle_ingreso dg on dp.iddetalle_ingreso = dg.iddetalle_ingreso
			inner join articulo a on dg.idarticulo = a.idarticulo
			where v.idventa  = $idpedido";
			$query = $conexion->query($sql);
			return $query;
		}

		public function GetDetalleCantStock($idpedido){
			global $conexion;
			$sql = "select di.iddetalle_ingreso, di.stock_actual, dp.cantidad
	from detalle_pedido dp inner join detalle_ingreso di on dp.iddetalle_ingreso = di.iddetalle_ingreso
	where dp.idpedido = $idpedido";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarTipoPedidoPedido($idsucursal){
			global $conexion;
			$sql = "select p.*, c.nombre as Cliente, c.email from pedido p inner join persona c
			on p.idcliente = c.idpersona where p.estado = 'A' and p.idsucursal = $idsucursal and p.tipo_pedido <> 'Venta'
			order by idpedido desc";
			$query = $conexion->query($sql);
			return $query;
		}
		public function TotalVenta($idpedido){
			global $conexion;
			$sql = "select * from venta where idventa = $idpedido";
			$query = $conexion->query($sql);
			return $query;
		}
		public function GetTotal($idpedido){
			global $conexion;
			$sql = "select sum((cantidad * precio_venta) - descuento) as total from detalle_pedido where idpedido = $idpedido";
			$query = $conexion->query($sql);
			return $query;
		}

		public function GetIdPedido(){
			global $conexion;
			$sql = "select max(idpedido) as idpedido from pedido";
			$query = $conexion->query($sql);
			return $query;
		}

		public function GetNextNumero($idsucursal){
			global $conexion;
			$sql = "select max(numero) + 1 as numero from pedido where idsucursal = $idsucursal";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarClientes(){
			global $conexion;
			$sql = "select * from persona where tipo_persona = 'Cliente'";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarDetalleIngresos($idsucursal){
			global $conexion;
		$sql = "SELECT DISTINCT di.iddetalle_ingreso, di.stock_actual, a.nombre AS Articulo, a.codigo_interno, a.minima, a.idarticulo, di.codigo, di.serie AS caducidad, di.precio_ventapublico, di.precio2, di.precio3, di.precio4, di.precio5, a.imagen, a.descripcion, a.numero, a.instruccion, a.vrestringida, di.serie, i.fecha, c.nombre AS presentacion, u.nombre AS unidad_medida, u.prefijo FROM ingreso i INNER JOIN detalle_ingreso di ON di.idingreso = i.idingreso INNER JOIN sucursal s ON s.idsucursal = i.idsucursal INNER JOIN articulo a ON di.idarticulo = a.idarticulo INNER JOIN categoria c ON a.idcategoria = c.idcategoria INNER JOIN unidad_medida u ON a.idunidad_medida = u.idunidad_medida
			where i.estado = 'A' and i.idsucursal = $idsucursal and di.stock_actual > 0 order by a.idarticulo desc";
		$query = $conexion->query($sql);
		return $query;
		}

		public function ListarProveedor(){
			global $conexion;
			$sql = "select * from persona where tipo_persona = 'Proveedor' and estado = 'A'";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarTipoDocumento($idsucursal){
			global $conexion;
			$sql = "select dds.*, td.nombre
	from detalle_documento_sucursal dds inner join tipo_documento td on dds.idtipo_documento = td.idtipo_documento
	where dds.idsucursal = $idsucursal and operacion = 'Comprobante'";
			$query = $conexion->query($sql);
			return $query;
		}

		public function GetTipoDocSerieNum($nombre){
			global $conexion;
			$sql = "select ultima_serie, ultimo_numero from tipo_documento where operacion = 'Comprobante' and nombre = '$nombre'";
			$query = $conexion->query($sql);
			return $query;
		}


		public function Get_id_ticket($Sucursal){
			global $conexion;
			$iduTicket = 0;

			//Recuperamos el ID DE TICKET

			// $get_id_ticket ="select idtipo_documento from tipo_documento where nombre = 'TICKET'";
			// $consultax=  $conexion->query($get_id_ticket);

			// while ($regx = $consultax->fetch_object()) {
			//  $iddo =$regx->idtipo_documento;
			//  }

			 // ahora con esto traemos el ultimo valor que tenia ticket
			//$sql = "SELECT ultimo_numero from detalle_documento_sucursal where idtipo_documento ='".$iddo."' and idsucursal = '".$_SESSION["idsucursal"]."'";
			$sql = "SELECT num_comprobante FROM venta WHERE idSucursal = ".$Sucursal." and tipo_comprobante ='TICKET' order by num_comprobante DESC limit 1";

			$query = $conexion->query($sql);

			while ($reg1 = $query->fetch_object()) {
			 $iduTicket = $reg1->num_comprobante;
			}
			return $iduTicket;
			//return $sql;
		}

		public function ListarProveedores(){
			global $conexion;
			$sql = "select * from persona where tipo_perssona = 'Proveedor'";
			$query = $conexion->query($sql);
			return $query;
		}

		public function GetClienteSucursalPedido($idpedido){
			global $conexion;

			$sql = "select p.*, emp.nombre nombreusuario, ped.fecha, s.razon_social, ped.num_comprobante, ped.tipo_comprobante, ped.tipo_pago, ped.fecha_validez, ped.tiempo_entrega, s.tipo_documento, s.num_documento as num_sucursal, s.direccion, s.telefono as telefono_suc, s.email as email_suc,
			s.representante, s.logo, ped.tipo_venta,p.tipo_documento as doc from persona p inner join venta ped on ped.idcliente = p.idpersona inner join sucursal s on ped.idsucursal = s.idsucursal inner join usuario usu on usu.idusuario = ped.idusuario inner join empleado emp on emp.idempleado = usu.idempleado
			where ped.idventa = $idpedido";

			$query = $conexion->query($sql);
			return $query;
		}
		public function GetEmpleadoPedido($idpedido){
			global $conexion;

			$sql = "select apellidos from empleado e
			inner join usuario u on u.idempleado = e.idempleado
			inner join venta v on u.idusuario = v.idusuario where v.idventa = $idpedido";

			$query = $conexion->query($sql);
			return $query;
		}
		public function GetVenta($idpedido){
			global $conexion;
			$sql = "select p.*, ped.fecha, s.razon_social, v.num_comprobante, v.serie_comprobante, s.tipo_documento, s.num_documento as num_sucursal, s.direccion, s.telefono as telefono_suc, s.email as email_suc, s.representante, s.logo, ped.tipo_pedido,v.impuesto,p.tipo_documento as doc
	from persona p inner join pedido ped on ped.idcliente = p.idpersona
	inner join sucursal s on ped.idsucursal = s.idsucursal
	inner join venta v on v.idpedido = ped.idpedido
	where ped.idpedido = $idpedido";
			$query = $conexion->query($sql);
			return $query;
		}



		public function GetComprobanteTipo($idVenta){
			//seleccionamos el comprobante de ventas
			global $conexion;
			$sql = "SELECT tipo_comprobante from venta WHERE idventa ='$idVenta'";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ImprimirDetallePedido($idVenta){
			global $conexion;
			$sql = "SELECT venta.total , venta.descuento, detalle_pedido.cantidad ,detalle_pedido.precio_venta,articulo.nombre, articulo.codigo_interno, (select nombre from unidad_medida where unidad_medida.idunidad_medida=articulo.idunidad_medida)as marca, (select cod_marca from unidad_medida where unidad_medida.idunidad_medida=articulo.idunidad_medida)as cod_marca FROM venta
			INNER join detalle_pedido on venta.idventa=detalle_pedido.idventa INNER JOIN detalle_ingreso on detalle_ingreso.iddetalle_ingreso=detalle_pedido.iddetalle_ingreso
			INNER join articulo on articulo.idarticulo =detalle_ingreso.idarticulo WHERE venta.idventa=$idVenta";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ImprimirDetalleProforma($idVenta){
			global $conexion;
			$sql = "SELECT venta.total , venta.descuento, detalle_pedido.cantidad ,detalle_pedido.precio_venta,articulo.nombre, articulo.codigo_interno, (select nombre from unidad_medida where unidad_medida.idunidad_medida=articulo.idunidad_medida)as marca, (select cod_marca from unidad_medida where unidad_medida.idunidad_medida=articulo.idunidad_medida)as cod_marca FROM venta
			INNER join detalle_pedido on venta.idventa=detalle_pedido.idventa INNER JOIN detalle_ingreso on detalle_ingreso.iddetalle_ingreso=detalle_pedido.iddetalle_ingreso
			INNER join articulo on articulo.idarticulo =detalle_ingreso.idarticulo WHERE venta.idventa=$idVenta";
			$query = $conexion->query($sql);
			return $query;
		}


public function Recuperar_informacion_tf($idVenta){
global $conexion;
$sql = "SELECT venta.num_comprobante as num_factura, venta.fecha, venta.total , venta.descuento,sucursal.llave_dosificacion, sucursal.razon_social,sucursal.direccion,sucursal.telefono,sucursal.num_documento as nit_sucursal ,sucursal.numero_autorizacion, sucursal.fecha_limite_emision,sucursal.leyenda_facturas, persona.nombre as nombre_cliente , persona.num_documento as nit_cliente , venta.CodigoAutorizacion as codigo_control from sucursal INNER JOIN venta on sucursal.idsucursal=venta.idSucursal INNER JOIN persona on persona.idpersona = venta.idCliente WHERE venta.idventa='$idVenta'";
$query = $conexion->query($sql);
return $query;


}


	public function Get_autorizacion_dosificacion($idSucursal){
		global $conexion;
		$sql = "	SELECT   numero_autorizacion,llave_dosificacion FROM
			sucursal WHERE sucursal.idsucursal='$idSucursal'";
		$query = $conexion->query($sql);
		return $query;
	}


		public function ListarProformas($idsucursal){

			global $conexion;

			$sql = "select venta.idventa,venta.idcliente,venta.descuento,venta.tiempo_entrega,venta.fecha_validez,venta.tipo_venta,venta.tipo_pago,persona.nombre,persona.tipo_documento,persona.num_documento,fecha,total,tipo_comprobante, venta.estado estado_pedido from venta left join persona on venta.idcliente = persona.idpersona where venta.idsucursal = $idsucursal and venta.tipo_comprobante = 'PROFORMA' and (venta.estado!='F' and venta.estado!='C') order by venta.idventa desc limit 0,2999";


			$query = $conexion->query($sql);

			return $query;

		}

		public function GetDetalleProformaPed($idventa) {
			global $conexion;
			$sql = "select a.nombre as articulo, dg.iddetalle_ingreso, dg.stock_actual, dg.codigo, dg.serie, dp.*
			from venta v inner join detalle_pedido dp on v.idventa = dp.idventa
			inner join detalle_ingreso dg on dp.iddetalle_ingreso = dg.iddetalle_ingreso
			inner join articulo a on dg.idarticulo = a.idarticulo
			where v.idventa  = $idventa";
			$query = $conexion->query($sql);
			return $query;
		}






	}
