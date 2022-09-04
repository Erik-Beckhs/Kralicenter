<?php

require "Conexion.php";


/**
 *
 */
class get_date
{
//funcion para devolver fecha actual del servidor
public function get_fecha(){
//retorno la fecha actual del servidor
	global $conexion;
	$sql = "select curdate()";
	$query = $conexion->query($sql);

$valor =$query->fetch_object() ;
	return $valor;

}

public function get_fecha_hora(){
//retorno la fecha actual del servidor
	global $conexion;
	$sql = "select now()";
	$query = $conexion->query($sql);

$valor =$query->fetch_object() ;
	return $valor;

}







}







 ?>
