<?php

session_start();

switch ($_GET["op"]) {

    case 'CajaAbierta':
        require_once "../model/ingresos_arqueo.php";
        $obj= new DocSucursal();

        $CajaAbierta = $obj->MontoCajaAbierta($_SESSION['idsucursal'],$_SESSION['idusuario']);
        echo json_encode((int)$CajaAbierta);
        break;
    case 'Save':
        require_once "../model/ingresos_arqueo.php";
        $tipo_transaccion = $_POST["tipo_transaccion"];

        $obj= new DocSucursal();
        $CajaAbierta = $obj->MontoCajaAbierta($_POST["idSucursal"],$_POST["idusuario"]);
        if ($CajaAbierta==0) {
            $hosp = $obj->RegistrarApertura($_POST["idSucursal"],$_POST["motivo"],$_POST["monto"],$_POST["idusuario"],$tipo_transaccion);
                if ($hosp) {
                    echo "Apertura de Caja Registrada";
                } else {
                    echo "No se ha podido registrar la Apertura de Caja";
                }
        } else {
            if($obj->ModificarApertura($_POST["idSucursal"], $_POST["idusuario"], $_POST["monto"])
          ){
                echo "Monto de Apertura de Caja Modificado Exitosamente";
            } else {
                echo "No se logro modificar el monto de Apertura de Caja";
            }
        }
        break;

    case "delete" :

        require_once "../model/ingresos_arqueo.php";
        $obj= new DocSucursal();
        $idDocSucursal = $_POST["id"];
        $hosp = $obj->Eliminar($idDocSucursal);
        if ($hosp) {
            echo "Eliminado Exitosamente";
        } else {
            echo "No se ha podido eliminar";
        }
        break;

}

?> 
