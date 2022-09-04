<?php

session_start();

switch ($_GET["op"]) {

    case 'Save':

    require_once "../model/Ingreso.php";

    $obj= new Ingreso();

        $idUsuario = $_POST["idUsuario"];
        $idSucursal = $_POST["idSucursal"];
        $idproveedor = $_POST["idproveedor"];
        $tipo_comprobante = trim($_POST["tipo_comprobante"]);
        $numero_factura = $_POST["numero_factura"];
        $codigo_control = $_POST["codigo_control"];
        $tipo_compra =$_POST["tipo_ingreso"];
        $numero_autorizacion = $_POST["numero_autorizacion"];
        $impuesto = $_POST["impuesto"];
        $total = $_POST["total"];

        if(empty($_POST["idIngreso"])){
            $hosp = $obj->Registrar($idUsuario, $idSucursal, $idproveedor, $tipo_comprobante, $numero_factura, $codigo_control, $tipo_compra,$numero_autorizacion,$impuesto, $total, $_POST["detalle"]);
                if ($hosp) {
                    echo "Ingreso Registrado";
                } else {
                    echo "No se ha podido registrar el Ingreso";
                }
        } else {
            /*
            if($obj->Modificar($_POST["idIngreso"], $idCategoria, $titulo, $descripcion, $slide, $imagen_principal)){
                echo "Ingreso Modificada";
            } else {
                echo "No se ha podido modificar la Ingreso";
            }
            */
        }

        break;

    case "CambiarEstado" :
        require_once "../model/Ingreso.php";

        $obj= new Ingreso();

        $idIngreso = $_POST["idIngreso"];

        $hosp = $obj->CambiarEstado($idIngreso);
                if ($hosp) {
                    echo "Ingreso Anulado";
                } else {
                    echo "No se ha podido Anular el Ingreso";
                }
        break;


    case "list":
        require_once "../model/Ingreso.php";

        $objIngreso = new Ingreso();

        $query_Ingreso = $objIngreso->Listar($_SESSION["idsucursal"]);

        $data = Array();
        $i = 1;
            while ($reg = $query_Ingreso->fetch_object()) {
                $data[] = array(
                    "0"=>$i,
                    "1"=>$reg->proveedor,
                    "2"=>$reg->tipo_comprobante,
                    "3"=>$reg->serie_comprobante,
                    //"3"=>$reg->num_comprobante,
                    "4"=>$reg->fecha,
                    "5"=>$reg->impuesto,
                    "6"=>$reg->total,
                    //$reg->estado,

                    "7"=>($reg->estado=="A")?'<span class="badge bg-green">ACEPTADO</span>':'<span class="badge bg-red">CANCELADO</span>',
                    "8"=>($reg->estado=="A")?'<button class="btn btn-success" data-toggle="tooltip" title="Ver Detalle" onclick="cargarDataIngreso('.$reg->idingreso.',\''.$reg->serie_comprobante.'\',\''.$reg->num_comprobante.'\',\''.$reg->impuesto.'\',\''.$reg->total.'\',\''.$reg->idingreso.'\',\''.$reg->proveedor.'\',\''.$reg->tipo_comprobante.'\')" ><i class="fa fa-eye"></i> </button>&nbsp'.
                    '<button class="btn btn-danger" data-toggle="tooltip" title="Anular Ingreso" onclick="cancelarIngreso('.$reg->idingreso.')" ><i class="fa fa-times-circle"></i> </button>&nbsp'.
                    '<a href="./Reportes/exIngreso.php?id='.$reg->idingreso.'" class="btn btn-primary" data-toggle="tooltip" title="Imprimir" target="blanck" ><i class="fa fa-file-text"></i> </a>':'<button class="btn btn-success" data-toggle="tooltip" title="Ver Detalle" onclick="cargarDataIngreso('.$reg->idingreso.',\''.$reg->serie_comprobante.'\',\''.$reg->num_comprobante.'\',\''.$reg->impuesto.'\',\''.$reg->total.'\',\''.$reg->idingreso.'\',\''.$reg->proveedor.'\',\''.$reg->tipo_comprobante.'\')" ><i class="fa fa-eye"></i> </button>&nbsp'.
                    '<a href="./Reportes/exIngreso.php?id='.$reg->idingreso.'" class="btn btn-primary" data-toggle="tooltip" title="Imprimir" target="blanck" ><i class="fa fa-file-text"></i> </a>');
                $i++;
            }
            $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData"=>$data);
            echo json_encode($results);

            break;


        break;


        case "GetIdIngreso":
        require_once "../model/Ingreso.php";

        $objIngreso = new Ingreso();
          $query_get = $objIngreso->GetIdIngreso();
          $reg = $query_get->fetch_object();
          echo $reg->id;
          break;


    case "GetDetalleArticulo":
        require_once "../model/Ingreso.php";

        $objIngreso = new Ingreso();

        $idIngreso = $_POST["idIngreso"];

        $query_prov = $objIngreso->GetDetalleArticulo($idIngreso);
       $margen=30;
        $i = 1;
            while ($reg = $query_prov->fetch_object()) {
                 echo '<tr>
                        <td>'.$reg->articulo.'</td>
                       <td>'.$reg->serie.'</td>
                      <td>'.''.'</td>
                         <td>'.$reg->stock_ingreso.'</td>
                        <td>'.$reg->precio_compra.'</td>
                        
                        <td>'.$reg->precio_ventapublico.'</td>
                        <td>'.$reg->precio2.'</td>
                        <td>'.$reg->precio3.'</td>
                        <td>'.$reg->precio4.'</td>
                        <td>'.$reg->precio5.'</td>
                       
                       </tr>';
                       //<td><input type="radio" name="optProveedorBusqueda" data-nombre="'.$reg->nombre.'" id="'.$reg->idpersona.'" value="'.$reg->idpersona.'" /></td>
                 $i++;
            }

        break;

    case "listProveedor":
        require_once "../model/Ingreso.php";

        $objIngreso = new Ingreso();

        $query_prov = $objIngreso->ListarProveedor();

        $i = 1;
            while ($reg = $query_prov->fetch_object()) {
                 echo '<tr>
                        <td><input type="radio" name="optProveedorBusqueda" data-nombre="'.$reg->nombre.'" id="'.$reg->idpersona.'" value="'.$reg->idpersona.'" /></td>
                        <td>'.$i.'</td>
                        <td>'.$reg->nombre.'</td>
                        <td>'.$reg->tipo_documento.'</td>
                        <td>'.$reg->num_documento.'</td>
                        <td>'.$reg->email.'</td>
                        <td>'.$reg->numero_cuenta.'</td>
                       </tr>';
                 $i++;
            }

        break;

        case 'SaveC':
        
        require_once "../model/Ingreso.php";

        $objIngreso = new Ingreso();

          $idingreso = $_POST["txtIdIngresoCred"]; // Llamamos al input txtNombre
          $fecha_pagoC = date("d/m/Y");
          $total_pago = $_POST["txtTotalPagoC"];

            if(empty($_POST["txtIdcreditoC"])){

              if($objIngreso->RegistrarC($idingreso,$fecha_pagoC, $total_pago)){
                echo "credito registrado";
              }else{
                echo "credito no ha podido ser registado.";
              }
            }else{

              $idcredito = $_POST["txtIdcreditoC"];
              if($objIngreso->ModificarC($idcredito, $idingreso,$fecha_pago, $total_pago)){
                echo "credito ha sido actualizada";
              }else{
                echo "credito no ha podido ser actualizada.";
              }
            }

          break;

    case "listSucursalC":
        require_once "../model/Sucursal.php";

        $objSucursal = new Sucursal();

        $query_prov = $objSucursal->Listar();

        $i = 1;
            while ($reg = $query_prov->fetch_object()) {
                 echo '<tr>
                        <td><input type="radio" name="optSucursalBusqueda" data-nombre="'.$reg->razon_social.'" id="'.$reg->idsucursal.'" value="'.$reg->idsucursal.'" /></td>
                        <td>'.$i.'</td>
                        <td>'.$reg->razon_social.'</td>
                        <td>'.$reg->tipo_documento.' - '.$reg->num_documento.'</td>
                        <td>'.$reg->direccion.'</td>
                        <td>'.$reg->email.'</td>
                        <td> <img width=100px height=100px src='.$reg->logo.' /></td>
                       </tr>';
                 $i++;
            }

        break;
        case "listSucursal":
            require_once "../model/Sucursal.php";

            $objSucursal = new Sucursal();

            $query_prov = $objSucursal->Listar();

            $i = 1;
                while ($reg = $query_prov->fetch_object()) {
                     echo '<tr>
                            <td><input type="radio" name="optSucursalBusqueda" data-nombre="'.$reg->razon_social.'" id="'.$reg->idsucursal.'" value="'.$reg->idsucursal.'" /></td>
                            <td>'.$i.'</td>
                            <td>'.$reg->razon_social.'</td>
                            <td>'.$reg->tipo_documento.' - '.$reg->num_documento.'</td>
                            <td>'.$reg->direccion.'</td>
                            <td>'.$reg->email.'</td>
                            <td> <img width=100px height=100px src='.$reg->logo.' /></td>
                           </tr>';
                     $i++;
                }

            break;
     case "listTipoDoc":

            require_once "../model/Ingreso.php";

            $objIngreso = new Ingreso();

            $query_Categoria = $objIngreso->ListarTipoDocumento();

            //echo '<option value="">--Seleccione Comprobante--</option>';
            while ($reg = $query_Categoria->fetch_object()) {
                echo '<option value=' . $reg->nombre . '>' . $reg->nombre . '</option>';
            }

            break;
            case "GetIdIngreso":
            require_once "../model/Ingreso.php";

            $objIngreso = new Ingreso();
              $query_get = $objIngreso->GetIdCompra();
              $reg = $query_get->fetch_object();
              echo $reg->id;
              break;

    case "GetTipoDocSerieNum":

            require_once "../model/Ingreso.php";

            $objIngreso = new Ingreso();

            $nombre = $_REQUEST["nombre"];

            $query_Categoria = $objIngreso->GetTipoDocSerieNum($nombre);

            $reg = $query_Categoria->fetch_object();

            echo json_encode($reg);

            break;

}
