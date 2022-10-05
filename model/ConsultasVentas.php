<?php
	require "Conexion.php";

	class ConsultasVentas{


		public function __construct(){

		}


		public function listas_libro_ventas($idsucursal, $fecha_desde, $fecha_hasta){
			global $conexion;
			$sql = "SELECT  venta.num_comprobante as numero_factura ,sucursal.numero_autorizacion, venta.fecha as fecha_emision ,persona.nombre,persona.num_documento AS nit_cliente, venta.estado_factura as estado,venta.total ,venta.CodigoAutorizacion as codigo_control
FROM sucursal INNER JOIN venta on sucursal.idsucursal = venta.idSucursal
INNER JOIN persona on persona.idpersona = venta.idCliente
WHERE venta.fecha BETWEEN '$fecha_desde' AND '$fecha_hasta' AND venta.tipo_comprobante ='FACTURA' AND venta.idSucursal='$idsucursal'

				";
			$query = $conexion->query($sql);
			return $query;
		}
		public function ListarVentasFechas($idsucursal, $fecha_desde, $fecha_hasta){
			global $conexion;
			$sql = "select v.idventa, v.tipo_venta, v.fecha,s.razon_social as sucursal, concat(e.apellidos,' ',e.nombre) as empleado,
			pe.nombre as cliente,v.tipo_comprobante as comprobante, v.tipo_pago as tipo,v.num_comprobante as numero, v.impuesto,
			format((v.total-(v.impuesto*v.total/(100+v.impuesto))),2) as subtotal, format((v.impuesto*v.total/(100+v.impuesto)),2) as totalimpuesto,
			v.total from venta v inner join sucursal s on v.idsucursal=s.idsucursal
			inner join usuario u on v.idusuario=u.idusuario inner join empleado e on u.idempleado=e.idempleado
			inner join persona pe on v.idCliente=pe.idpersona
				where v.fecha>='$fecha_desde' and v.fecha<='$fecha_hasta' and s.idsucursal= $idsucursal and v.estado='A'
				order by v.fecha desc
				";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarVentasDetalladas($idsucursal, $fecha_desde, $fecha_hasta){
			global $conexion;
			$sql = "select v.fecha,s.razon_social as sucursal, concat(e.apellidos,' ',e.nombre) as empleado, pe.nombre as cliente,v.tipo_comprobante as comprobante, v.CodigoAutorizacion as serie,v.num_comprobante as numero, v.impuesto as impuesto, v.descuento, a.nombre as articulo,di.codigo as codigo,di.serie as serie_art, dp.cantidad,dp.precio_venta, (dp.cantidad*(dp.precio_venta)-(v.descuento))as total from detalle_pedido dp inner join detalle_ingreso di on dp.iddetalle_ingreso=di.iddetalle_ingreso inner join articulo a on di.idarticulo=a.idarticulo inner join venta v on v.idventa=dp.idventa inner join sucursal s on v.idSucursal=s.idsucursal inner join usuario u on v.idusuario=u.idusuario inner join empleado e on u.idempleado=e.idempleado inner join persona pe on v.idCliente=pe.idpersona
				where v.fecha>='$fecha_desde' and v.fecha<='$fecha_hasta'
				and s.idsucursal= $idsucursal and v.estado='A'
				order by v.fecha desc
				";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarVentasPendientes($idsucursal, $fecha_desde, $fecha_hasta){
			global $conexion;
			$sql = "select v.fecha,s.razon_social as sucursal,
				concat(e.apellidos,' ',e.nombre) as empleado,
				pe.nombre as cliente,v.tipo_comprobante as comprobante,
				v.serie_comprobante as serie,v.num_comprobante as numero,
				v.impuesto,
				format((v.total-(v.impuesto*v.total/(100+v.impuesto))),2) as subtotal,
				format((v.impuesto*v.total/(100+v.impuesto)),2) as totalimpuesto,
				v.total as totalpagar,(select sum(total_pago) from creditos where idventa=v.idventa)as totalpagado,
				(v.total-(select sum(total_pago) from creditos where idventa=v.idventa))as totaldeuda
				from venta v inner join pedido p on v.idpedido=p.idpedido
				inner join sucursal s on p.idsucursal=s.idsucursal
				inner join usuario u on p.idusuario=u.idusuario
				inner join empleado e on u.idempleado=e.idempleado
				inner join persona pe on p.idcliente=pe.idpersona
				where v.fecha>='$fecha_desde' and v.fecha<='$fecha_hasta'
				and (v.total-(select sum(total_pago) from creditos where idventa=v.idventa))> 0
				and s.idsucursal= $idsucursal and v.tipo_venta='Credito' and v.estado='A'
				order by v.fecha desc
				";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarVentasContado($idsucursal, $fecha_desde, $fecha_hasta){
			global $conexion;
			$sql = "select v.fecha,s.razon_social as sucursal, concat(e.apellidos,' ',e.nombre) as empleado, pe.nombre as cliente,v.tipo_comprobante as comprobante, v.tipo_pago,v.tipo_venta, v.num_comprobante as numero, v.impuesto, format((v.total-(v.impuesto*v.total/(100+v.impuesto))),2) as subtotal, format((v.impuesto*v.total/(100+v.impuesto)),2) as totalimpuesto, v.total from venta v inner join sucursal s on v.idsucursal=s.idsucursal inner join usuario u on v.idusuario=u.idusuario inner join empleado e on u.idempleado=e.idempleado inner join persona pe on v.idcliente=pe.idpersona
				where v.fecha>='$fecha_desde' and v.fecha<='$fecha_hasta' and s.idsucursal= $idsucursal and v.tipo_venta='Contado' and v.estado='A'
				order by v.fecha desc
				";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarVentascredito($idsucursal, $fecha_desde, $fecha_hasta){
			global $conexion;
			$sql = "select v.fecha,s.razon_social as sucursal, concat(e.apellidos,' ',e.nombre) as empleado, pe.nombre as cliente,v.tipo_comprobante as comprobante, v.tipo_venta, v.num_comprobante as numero, v.impuesto, format((v.total-(v.impuesto*v.total/(100+v.impuesto))),2) as subtotal, format((v.impuesto*v.total/(100+v.impuesto)),2) as totalimpuesto, v.total as totalpagar,(select sum(total_pago) from credito where idventa=v.idventa)as totalpagado, (v.total-(select sum(total_pago) from credito where idventa=v.idventa))as totaldeuda from venta v inner join sucursal s on v.idsucursal=s.idsucursal inner join usuario u on v.idusuario=u.idusuario inner join empleado e on u.idempleado=e.idempleado inner join persona pe on v.idcliente=pe.idpersona
				where v.fecha>='$fecha_desde' and v.fecha<='$fecha_hasta'
				and s.idsucursal= $idsucursal and v.tipo_venta='Credito' and v.estado='A'
				order by v.fecha desc
				";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarVentasCliente($idsucursal, $idcliente, $fecha_desde, $fecha_hasta){
			global $conexion;
			$sql = "select v.fecha,s.razon_social as sucursal, concat(e.apellidos,' ',e.nombre) as empleado, pe.nombre as cliente,v.tipo_comprobante as comprobante, v.num_comprobante as numero, v.tipo_pago,v.tipo_venta, v.impuesto, format((v.total-(v.impuesto*v.total/(100+v.impuesto))),2) as subtotal, format((v.impuesto*v.total/(100+v.impuesto)),2) as totalimpuesto, v.total from venta v inner join sucursal s on v.idsucursal=s.idsucursal inner join usuario u on v.idusuario=u.idusuario inner join empleado e on u.idempleado=e.idempleado inner join persona pe on v.idcliente=pe.idpersona
				where v.fecha>='$fecha_desde' and v.fecha<='$fecha_hasta'
				and pe.idpersona= $idcliente and s.idsucursal= $idsucursal and v.estado='A'
				order by v.fecha desc
				";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarVentasEmpleado($idsucursal, $idempleado, $fecha_desde, $fecha_hasta){
			global $conexion;
			$sql = "select v.fecha,s.razon_social as sucursal, concat(e.apellidos,' ',e.nombre) as empleado, pe.nombre as cliente,v.tipo_comprobante as comprobante, v.num_comprobante as numero, v.tipo_pago,v.tipo_venta, v.impuesto, format((v.total-(v.impuesto*v.total/(100+v.impuesto))),2) as subtotal, format((v.impuesto*v.total/(100+v.impuesto)),2) as totalimpuesto, v.total from venta v inner join sucursal s on v.idsucursal=s.idsucursal inner join usuario u on v.idusuario=u.idusuario inner join empleado e on u.idempleado=e.idempleado inner join persona pe on v.idcliente=pe.idpersona
				where v.fecha>='$fecha_desde' and v.fecha<='$fecha_hasta'
				and e.idempleado= $idempleado and s.idsucursal= $idsucursal and v.estado='A'
				order by v.fecha desc;
				";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarVentasEmpleadoDet($idsucursal, $fecha_desde, $fecha_hasta){
			global $conexion;
			$sql = "select v.fecha,s.razon_social as sucursal, concat(e.apellidos,' ',e.nombre) as empleado, pe.nombre as cliente,v.tipo_comprobante as comprobante, v.tipo_pago,v.tipo_venta,v.num_comprobante as numero, v.Recibi, a.nombre as articulo,v.Cambio , dp.cantidad,dp.precio_venta,v.descuento, (dp.cantidad*(dp.precio_venta)-(v.descuento))as total from detalle_pedido dp inner join detalle_ingreso di on dp.iddetalle_ingreso=di.iddetalle_ingreso inner join articulo a on di.idarticulo=a.idarticulo inner join venta v on v.idventa=dp.idventa inner join sucursal s on v.idSucursal=s.idsucursal inner join usuario u on v.idusuario=u.idusuario inner join empleado e on u.idempleado=e.idempleado inner join persona pe on v.idCliente=pe.idpersona
				where v.fecha>= '$fecha_desde' and v.fecha<= '$fecha_hasta'
				and s.idsucursal=$idsucursal and v.estado='A'

				order by v.fecha desc
				";
			$query = $conexion->query($sql);
			return $query;
		}

		public function totalVentaBs($idsucursal, $fecha_desde, $fecha_hasta){
			global $conexion;
			$sql = "select sum(dp.cantidad*(dp.precio_venta)-(v.descuento))as total from detalle_pedido dp inner join detalle_ingreso di on dp.iddetalle_ingreso=di.iddetalle_ingreso inner join articulo a on di.idarticulo=a.idarticulo inner join venta v on v.idventa=dp.idventa inner join sucursal s on v.idSucursal=s.idsucursal inner join usuario u on v.idusuario=u.idusuario inner join empleado e on u.idempleado=e.idempleado inner join persona pe on v.idCliente=pe.idpersona
				where v.fecha>= '$fecha_desde' and v.fecha<= '$fecha_hasta'
				and s.idsucursal=$idsucursal and v.estado='A'
				order by v.fecha desc
				";
			$query = $conexion->query($sql);
			return $query;
		}

	
	}
