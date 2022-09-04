<?php
require_once "conexion.php";


/**
 *
 */
class generar_arqueo
{


  function get_info_tb_mc_ingresos ($idusuario,$idsucursal){
    		global $conexion;
  $consulta = "
    SELECT  m.motivo , m.monto  from movimiento_caja m  where m.idusuario=$idusuario and  m.idsucursal= $idsucursal and  m.fecha BETWEEN  curdate() and
    curdate() and m.tipo_operacion = 'INGRESO'";

    $query = $conexion->query($consulta);
    return $query;

  }


  function get_total_ingresos ($idusuario,$idsucursal){
    		global $conexion;
  $consulta = "
  SELECT  SUM(m.monto)  AS total_ingreso from movimiento_caja m  where m.idusuario=$idusuario and  m.idsucursal= $idsucursal and  m.fecha BETWEEN curdate() and
  curdate() and m.tipo_operacion = 'INGRESO'";

    $query = $conexion->query($consulta);
    return $query;

  }

  function get_info_tb_ventas_ingresos ($idusuario,$idsucursal){
    global $conexion;
    $sql = "SELECT venta.total , detalle_pedido.cantidad ,detalle_pedido.precio_venta,articulo.nombre
FROM
venta INNER join  detalle_pedido on venta.idventa=detalle_pedido.idventa
INNER JOIN detalle_ingreso on detalle_ingreso.iddetalle_ingreso=detalle_pedido.iddetalle_ingreso
INNER join articulo on articulo.idarticulo =detalle_ingreso.idarticulo
WHERE venta.idusuario=$idusuario and venta.idSucursal and  venta.fecha BETWEEN curdate() and curdate()
and  venta.estado = 'A'  ";
    $query = $conexion->query($sql);
    return $query;
  }


  function get_total_ventas_ingresos ($idusuario,$idsucursal){
    global $conexion;
    $sql = "SELECT sum(venta.total) as total_venta FROM
venta where
venta.idusuario = $idusuario AND venta.idSucursal = $idsucursal and  venta.fecha BETWEEN  curdate() and curdate() and venta.estado = 'A' "  ;
    $query = $conexion->query($sql);
    return $query;
  }

  function get_info_tb_mc_salidas ($idusuario,$idsucursal){
    		global $conexion;
  $consulta = "
    SELECT  m.motivo , m.monto  from movimiento_caja m  where m.idusuario=$idusuario and  m.idsucursal= $idsucursal and  m.fecha BETWEEN curdate() and
    curdate() and m.tipo_operacion = 'SALIDA'";

    $query = $conexion->query($consulta);
    return $query;

  }



    function get_total_salida_ma ($idusuario,$idsucursal){
      global $conexion;
      $sql = "  SELECT  SUM(m.monto)  AS total_salida from movimiento_caja m  where m.idusuario=$idusuario and  m.idsucursal= $idsucursal and  m.fecha BETWEEN curdate() and
        curdate() and m.tipo_operacion = 'SALIDA'";
      $query = $conexion->query($sql);
      return $query;
    }



}
