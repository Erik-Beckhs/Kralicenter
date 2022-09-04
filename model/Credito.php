<?php
	require "Conexion.php";

	class Credito{


		public function __construct(){
		}

		public function Registrar($idventa,$fecha_pago, $total_pago){
			global $conexion;
			$sql = "INSERT INTO creditos(idventa, idingreso,fecha_pago, total_pago, tipo_credito)
						VALUES($idventa, 0, curdate(), $total_pago, 'Venta')";
			$query = $conexion->query($sql);
			return $query;
		}

		public function RegistrarC($idingreso,$fecha_pago, $total_pago){
			global $conexion;
			$sql = "INSERT INTO creditos(idingreso,idventa,fecha_pago, tipo_credito, total_pago)
						VALUES($idingreso,0,curdate(),'Compra', $total_pago)";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Modificar($idcredito, $idventa,$fecha_pago, $total_pago){
			global $conexion;
			$sql = "UPDATE creditos set idventa = '$idventa',fecha_pago='$fecha_pago', total_pago = $total_pago
						WHERE idcredito = $idcredito";
			$query = $conexion->query($sql);
			return $query;
		}


		public function Eliminar($idcredito){
			global $conexion;
			$sql = "DELETE FROM creditos WHERE idcredito = $idcredito";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Listar($idsucursal){
			global $conexion;
			$sql = "select * from venta v where v.tipo_venta = 'Credito' and v.idsucursal='$idsucursal' order by v.idventa desc ";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarC($idsucursal){
			global $conexion;
			$sql = "select * from ingreso i where i.tipo_ingreso = 'credito' and i.idsucursal='$idsucursal' order by i.idingreso desc ";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarDeuda($idsucursal){
			global $conexion;
			$sql = "select i.* from ingreso i
			where i.tipo_ingreso = 'Credito'
			and i.total>ifnull((select sum(c.total_pago) from creditos c where c.idingreso = i.idingreso),0)
			and i.idsucursal='$idsucursal'
			order by i.idingreso desc";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarDeudaC($idsucursal){
			global $conexion;
			$sql = "select i.* from ingreso i
			where i.tipo_ingreso = 'Credito'
			and i.total>ifnull((select sum(c.total_pago) from creditos c where c.idingreso = i.idingreso),0)
			and i.idsucursal='$idsucursal'
			order by i.idingreso desc";
			$query = $conexion->query($sql);
			return $query;
		}

		public function GetMontoTotalcredito($idventa){
			global $conexion;
			$sql = "select sum(c.total_pago) as total_pago from creditos c where c.idventa = $idventa";
			$query = $conexion->query($sql);
			return $query;
		}

		public function GetMontoTotalcreditoC($idingreso){
			global $conexion;
			$sql = "select sum(c.total_pago) as total_pago from creditos c where c.idingreso = $idingreso";
			$query = $conexion->query($sql);
			return $query;
		}

		public function GetMontoTotalcreditoMayorCero($idventa){
			global $conexion;
			$sql = "select sum(c.total_pago) as total_pago from creditos c where c.idventa = $idventa and c.total_pago >= 0";
			$query = $conexion->query($sql);
			return $query;
		}

		public function GetMontoTotalcreditoMayorCeroC($idingreso){
			global $conexion;
			$sql = "select sum(c.total_pago) as total_pago from creditos c where c.idingreso = $idingreso and c.total_pago >= 0";
			$query = $conexion->query($sql);
			return $query;
		}

		public function VerDetallecredito($idventa){
			global $conexion;
			$sql = "select fecha_pago, total_pago
	from creditos where idventa = $idventa";
			$query = $conexion->query($sql);
			return $query;
		}

		public function VerDetallecreditoC($idingreso){
			global $conexion;
			$sql = "select fecha_pago, total_pago
	from creditos where idingreso = $idingreso";
			$query = $conexion->query($sql);
			return $query;
		}

		public function MontoTotalPagados($idventa){
			global $conexion;
			$sql = "select v.total - sum(c.total_pago) as MontoTotalPagados
	from creditos c inner join venta v on c.idventa = v.idventa where c.idventa = $idventa";
			$query = $conexion->query($sql);
			return $query;
		}

		public function MontoTotalPagadosC($idingreso){
			global $conexion;
			$sql = "select i.total - sum(c.total_pago) as MontoTotalPagados
		from creditos c inner join ingreso i on c.idingreso = i.idingreso where c.idingreso = $idingreso";
			$query = $conexion->query($sql);
			return $query;
		}

			public function GetUltimoPago($idpedido){
				global $conexion;
				$sql = "	select c.total_pago
		  from creditos c
		  inner join venta ped on ped.idventa = c.idventa
		  WHERE c.idventa= $idpedido
		 order by c.idcredito desc
		 limit 1";
		 $query = $conexion->query($sql);
		 return $query;
	 }

	 public function GetUltimoPagoC($idingreso){
	 	global $conexion;
	 	$sql = "	select c.total_pago
	 from creditos c
	 inner join ingreso i on i.idingreso = c.idingreso
	 WHERE c.idingreso= $idingreso
	 order by c.idcredito desc
	 limit 1";
	 $query = $conexion->query($sql);
	 return $query;
	 }

		public function GetIdVenta(){
			global $conexion;
			$sql = "select max(idventa) as id from venta";
			$query = $conexion->query($sql);
			return $query;
		}



		public function GetClienteSucursalPedidoCobro($idpedido){
			global $conexion;
			$sql = "select p.*, ped.fecha, s.razon_social, ped.num_comprobante, ped.tipo_comprobante,s.tipo_documento, s.num_documento as num_sucursal, s.direccion, s.telefono as telefono_suc, s.email as email_suc,
			s.representante, s.logo, ped.tipo_venta,p.tipo_documento as doc from persona p inner join venta ped on ped.idcliente = p.idpersona inner join sucursal s on ped.idsucursal = s.idsucursal
			where ped.idventa = $idpedido";
			$query = $conexion->query($sql);
			return $query;
		}

		public function GetProveedorSucursalCompraPago($idingreso){
			global $conexion;
			$sql = "select p.*, i.fecha, s.razon_social, i.serie_comprobante, i.tipo_comprobante,s.tipo_documento, s.num_documento as num_sucursal, s.direccion, s.telefono as telefono_suc, s.email as email_suc,
			s.representante, s.logo, i.tipo_ingreso,p.tipo_documento as doc from persona p inner join ingreso i on i.idproveedor = p.idpersona inner join sucursal s on i.idsucursal = s.idsucursal
			where i.idingreso = $idingreso";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ImprimirDetalleCobro($idVenta){
			global $conexion;
			$sql = "SELECT creditos.total_pago, creditos.fecha_pago , venta.total as totalVenta, venta.tipo_comprobante, venta.num_comprobante, venta.tipo_venta, venta.descuento FROM creditos
			INNER join venta on venta.idventa=creditos.idventa
            WHERE creditos.idventa=$idVenta";
			$query = $conexion->query($sql);
			return $query;
		}
		public function ImprimirDetalleCobroC($idingreso){
			global $conexion;
			$sql = "SELECT creditos.total_pago, creditos.fecha_pago , ingreso.total as totalIngreso, ingreso.tipo_comprobante, ingreso.serie_comprobante, ingreso.tipo_ingreso FROM creditos
			INNER join ingreso on ingreso.idingreso=creditos.idingreso
            WHERE creditos.idingreso=$idingreso";
			$query = $conexion->query($sql);
			return $query;
		}
		public function TotalVenta($idpedido){
			global $conexion;
			$sql = "select * from venta where idventa = $idpedido";
			$query = $conexion->query($sql);
			return $query;
		}

		public function TotalIngreso($idingreso){
			global $conexion;
			$sql = "select * from ingreso where idingreso = $idingreso";
			$query = $conexion->query($sql);
			return $query;
		}


	  public function GetUsuario($idpedido){
		global $conexion;
		$sql = "select * from venta where idventa = $idpedido";
		$query = $conexion->query($sql);
		return $query;
	}

	public function GetUsuarioC($idingreso){
	global $conexion;
	$sql = "select * from ingreso where idingreso = $idingreso";
	$query = $conexion->query($sql);
	return $query;
	}

	}
