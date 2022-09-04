<?php
session_start();


$idusuario = $_SESSION["idusuario"];
$idsucursal = $_SESSION["idsucursal"];
//$string = $_GET["op"];
//$arra = explode("/",$string);
//$idsucursal = $arra[1];
//$idusuario =  $arra[2];
switch ($_GET["op"]) {

  case 'get_listado_ingreso':
  require_once "../model/generar_arqueodb.php";
  $data = Array();
    $i = 1;

  $objeto_Arqueo = new generar_arqueo();
  $objeto_Arqueo2 = new generar_arqueo();

  $query_Ingreso = $objeto_Arqueo->get_info_tb_mc_ingresos($idusuario,$idsucursal);




      while ($reg = $query_Ingreso->fetch_object()) {


          $data[] = array(
              "0"=>$i,
              "1"=>"",
              "2"=>$reg->motivo,
              "3"=>"",
              "4"=>$reg->monto);
    $i++;



      }

      $results = array(
      "sEcho" => 1,
      "iTotalRecords" => count($data),
      "iTotalDisplayRecords" => count($data),
      "aaData"=>$data);
      echo json_encode($results);









    break;

  case 'get_listado_ingreso2':
  require_once "../model/generar_arqueodb.php";
  $data = Array();
    $i = 1;

  $objeto_Arqueo = new generar_arqueo();
  $objeto_Arqueo2 = new generar_arqueo();




  $query  = $objeto_Arqueo2->get_info_tb_ventas_ingresos($idusuario,$idsucursal);


      while ($reg2 = $query->fetch_object()) {


          $data[] = array(
              "0"=>$i,
              "1"=>$reg2->cantidad,
              "2"=>$reg2->nombre,
              "3"=>$reg2->precio_venta,
              "4"=>$reg2->total);

  $i++;


      }

      $results = array(
      "sEcho" => 1,
      "iTotalRecords" => count($data),
      "iTotalDisplayRecords" => count($data),
      "aaData"=>$data);
      echo json_encode($results);









    break;




    case "get_total_ingresos":
      require_once "../model/generar_arqueodb.php";
        $objeto = new generar_arqueo();
      $query_Tipo = $objeto->get_total_ingresos($idusuario,$idsucursal);

            $reg = $query_Tipo->fetch_object();

        echo json_encode($reg);

      break;




      case "get_total_ventas_ingresos":
        require_once "../model/generar_arqueodb.php";
          $objeto = new generar_arqueo();
        $query_Tipo = $objeto->get_total_ventas_ingresos($idusuario,$idsucursal);

              $reg = $query_Tipo->fetch_object();

          echo json_encode($reg);

        break;


        case 'get_listado_salida':
        require_once "../model/generar_arqueodb.php";
        $data = Array();
          $i = 1;

        $objeto_Arqueo = new generar_arqueo();
        $objeto_Arqueo2 = new generar_arqueo();

        $query_Ingreso = $objeto_Arqueo->get_info_tb_mc_salidas($idusuario,$idsucursal);


    //    $query  = $objeto_Arqueo2->get_info_tb_ventas_ingresos($idusuario,$idsucursal);


            while ($reg = $query_Ingreso->fetch_object()) {
        //      $reg2 = $query->fetch_object();

                $data[] = array(
                    "0"=>$i,
                    "1"=>"",
                    "2"=>$reg->motivo,
                    "3"=>"",
                    "4"=>$reg->monto);
          $i++;


            }

            $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData"=>$data);
            echo json_encode($results);




break;



case "get_total_salidas":
  require_once "../model/generar_arqueodb.php";
    $objeto = new generar_arqueo();
  $query_Tipo = $objeto->get_total_salida_ma($idusuario,$idsucursal);

        $reg = $query_Tipo->fetch_object();

    echo json_encode($reg);

  break;









  default:
    // code...
    break;
}
