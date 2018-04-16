<?php

session_start();

switch ($_GET["op"]) {

    case 'Save':

    require_once "../model/Pedido.php";

    $obj= new Pedido();

        $idCliente = $_POST["idCliente"];
        $idUsuario = $_POST["idUsuario"];
        $idSucursal = $_POST["idSucursal"];
        $tipo_pedido = trim($_POST["tipo_pedido"]);
        $numero = $_POST["numero"];

        if ($numero=="")
        {
            $numero="1";
        }
        else
        {
            $numero = $_POST["numero"];
        }

        if(empty($_POST["idPedido"])){
            $hosp = $obj->Registrar($idCliente, $idUsuario, $idSucursal, $tipo_pedido, $numero, $_POST["detalle"]);
                if ($hosp) {
                    echo "Pedido Registrado";
                } else {
                    echo "No se ha podido registrar el Pedido";
                }
        } else {
            /*
            if($obj->Modificar($_POST["idPedido"], $idCategoria, $titulo, $descripcion, $slide, $imagen_principal)){
                echo "Pedido Modificada";
            } else {
                echo "No se ha podido modificar la Pedido";
            }
            */
        }
                
        break;


       case "list":
        require_once "../model/Pedido.php";
        $data= Array();
        $objPedido = new Pedido();
        if ( !isset($_SESSION['idsucursal']))
        {
            $_SESSION['idsucursal'] = 1;
        }
        $query_Pedido = $objPedido->Listar($_SESSION["idsucursal"]);

        $i = 1;
            while ($reg = $query_Pedido->fetch_object()) {
                $query_total = $objPedido->TotalPedido($reg->idpedido);

                $reg_total = $query_total->fetch_object();

                $data[] = array("0"=>$i,
                    "1"=>$reg->Cliente,
                    "2"=>($reg->tipo_pedido=="Pedido")?'<span class="badge bg-blue">Pedido</span>':(($reg->tipo_pedido=="Venta")?'<span class="badge bg-aqua">Venta</span>':'<span class="badge bg-green">Proforma</span>'),
                    "3"=>$reg->fecha,
                    "4"=>$reg_total->Total,
                    "5"=>($reg->estado=="A")?'<span class="badge bg-green">ACEPTADO</span>':'<span class="badge bg-red">CANCELADO</span>',
                    "6"=>($reg->estado=="A")?'<button class="btn btn-success" data-toggle="tooltip" title="Ver Detalle" onclick="cargarDataPedido('.$reg->idpedido.',\''.$reg->tipo_pedido.'\',\''.$reg->numero.'\',\''.$reg->Cliente.'\',\''.$reg_total->Total.'\',\''.$reg->email.'\')" ><i class="fa fa-eye"></i> </button>&nbsp'.
                    '<button class="btn btn-warning" data-toggle="tooltip" title="Anular Venta" onclick="cancelarPedido('.$reg->idpedido.')" ><i class="fa fa-times-circle"></i> </button>&nbsp'.
                    '<a href="./Reportes/exVenta.php?id='.$reg->idpedido.'" class="btn btn-primary" data-toggle="tooltip" title="Imprimir" target="blanck" ><i class="fa fa-file-text"></i> </a>':
                    '<button class="btn btn-success" data-toggle="tooltip" title="Ver Detalle" onclick="cargarDataPedido('.$reg->idpedido.',\''.$reg->tipo_pedido.'\',\''.$reg->numero.'\',\''.$reg->Cliente.'\',\''.$reg_total->Total.'\')" ><i class="fa fa-eye"></i> </button>&nbsp'.
                    '<a href="./Reportes/exPedido.php?id='.$reg->idpedido.'" class="btn btn-primary" data-toggle="tooltip" title="Imprimir" target="blanck" ><i class="fa fa-file-text"></i> </a>&nbsp;');
                $i++;
            }
            $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData"=>$data);
            echo json_encode($results);

        break;

    case "GetVenta":

            require_once "../model/Pedido.php";

            $objPedido = new Pedido();

            $idpedido = $_REQUEST["idPedido"];

            $query = $objPedido->VerVenta($idpedido);

            $reg = $query->fetch_object();

            echo json_encode($reg);

            break;

    case "GetDetalleCantStock":
        require_once "../model/Pedido.php";

        $objPedido = new Pedido();

        $query_Pedido = $objPedido->GetDetalleCantStock($_REQUEST["idPedido"]);

            while ($reg = $query_Pedido->fetch_object()) {
                $data[] = array($reg->iddetalle_ingreso,
                    $reg->stock_actual,
                    $reg->cantidad
                    );
            }
        echo json_encode($data);

        break;

    case "GetNextNumero":
        require_once "../model/Pedido.php";

        $objPedido = new Pedido();

        $query_Pedido = $objPedido->GetNextNumero($_SESSION["idsucursal"]);

        $reg = $query_Pedido->fetch_object();

        echo json_encode($reg);

        break;

    case "GetPrimerCliente":
        require_once "../model/Pedido.php";

        $objPedido = new Pedido();

        $query_Pedido = $objPedido->GetPrimerCliente();

        $reg = $query_Pedido->fetch_object();

        echo json_encode($reg);

        break;
    case "GetDetallePedido":
        require_once "../model/Pedido.php";

        $objPedido = new Pedido();

        $idPedido = $_POST["idPedido"];

        $query_prov = $objPedido->GetDetallePedido($idPedido);

        $i = 1;
            while ($reg = $query_prov->fetch_object()) {
                 echo '<tr>
                        <td>'.$reg->articulo.'</td>
                        <td>'.$reg->codigo.'</td>
                        <td>'.$reg->serie.'</td>
                        <td>'.$reg->precio_venta.'</td>
                        <td>'.$reg->cantidad.'</td>
                        <td>'.$reg->descuento.'</td>
                       </tr>';
                 $i++; 
            }

        break;

    case "TraerCantidad" :
        require_once "../model/Pedido.php";

        $objPedido = new Pedido();

        $query_Pedido = $objPedido->TraerCantidad($_REQUEST["idPedido"]);

            while ($reg = $query_Pedido->fetch_object()) {
                $data[] = array($reg->iddetalle_ingreso,
                    $reg->cantidad
                    );
            }
        echo json_encode($data);

        break;

    case "CambiarEstado" :
        require_once "../model/Pedido.php";

        $obj= new Pedido();

        $idPedido = $_POST["idPedido"];
/*
        foreach($_POST["detalle"] as $indice => $valor){
            echo $valor[0]. " - ";
        }
        */
        
        $hosp = $obj->CambiarEstado($idPedido, $_POST["detalle"]);
                if ($hosp) {
                    echo "Venta Anulada";
                } else {
                    echo "No se ha podido Anular la Venta";
                }
        break;


    case "EliminarPedido" :
        require_once "../model/Pedido.php";

        $obj= new Pedido();

        $idPedido = $_POST["idPedido"];

        $hosp = $obj->EliminarPedido($idPedido);
                if ($hosp) {
                    echo "Pedido Eliminado";
                } else {
                    echo "No se ha podido eliminar el Pedido";
                }
        break;

    case "listClientes":
        require_once "../model/Pedido.php";

        $objPedido = new Pedido();

        $query_cli = $objPedido->ListarClientes();

        $i = 1;
            while ($reg = $query_cli->fetch_object()) {
                 echo '<tr>
                        <td><input type="radio" name="optClienteBusqueda" data-nombre="'.$reg->nombre.'" data-email="'.$reg->email.'" id="'.$reg->idpersona.'" value="'.$reg->idpersona.'" /></td>
                        <td>'.$i.'</td>
                        <td>'.$reg->tipo_persona.'</td>
                        <td>'.$reg->nombre.'</td>
                        <td>'.$reg->num_documento.'</td>
                        <td>'.$reg->email.'</td>
                       </tr>';
                 $i++; 
            }

        break;

    case "listDetIng":
        require_once "../model/Pedido.php";
        $objPedido = new Pedido();
        $query_cli = $objPedido->ListarDetalleIngresos($_SESSION["idsucursal"]);

        $data= Array();
        $i = 1;
            while ($reg = $query_cli->fetch_object()) {
                $data[] = array(

                    "0"=>'<button type="button" class="btn btn-warning" name="optDetIngBusqueda[]" data-codigo="'.$reg->codigo.'" 
                    data-serie="'.$reg->serie.'" data-nombre="'.$reg->Articulo.'" data-precio-venta="'.$reg->precio_ventapublico.'" 
                    data-stock-actual="'.$reg->stock_actual.'" id="'.$reg->iddetalle_ingreso.'" value="'.$reg->iddetalle_ingreso.'"
                    data-toggle="tooltip" title="Agregar al carrito"
                    onclick="AgregarPedCarrito('.$reg->iddetalle_ingreso.',\''.$reg->stock_actual.'\',\''.$reg->Articulo.'\',\''.$reg->codigo.'\',\''.$reg->serie.'\',\''.$reg->precio_ventapublico.'\')" >
                    <i class="fa fa-check" ></i> </button>',
                    "1"=>$reg->Articulo,
                    "2"=>$reg->codigo,
                    "3"=>$reg->serie,
                    "4"=>$reg->stock_actual,
                    "5"=>$reg->precio_ventapublico,
                    "6"=>'<img width=100px height=100px src="./'.$reg->imagen.'" />',
                    "7"=>$reg->fecha
                    );
                $i++;
            }

            $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData"=>$data);
            echo json_encode($results);
            
            break;

     case "listTipoDoc":

            require_once "../model/Pedido.php";

            $objPedido = new Pedido();

            $query_Categoria = $objPedido->ListarTipoDocumento($_SESSION["idsucursal"]);

            echo "<option>--Seleccione Comprobante--</option>";
            while ($reg = $query_Categoria->fetch_object()) {
                echo '<option value=' . $reg->nombre . '>' . $reg->nombre . '</option>';
            }

            break;

    case "GetTipoDocSerieNum":

            require_once "../model/Pedido.php";

            $objPedido = new Pedido();

            $nombre = $_REQUEST["nombre"];

            $query_Categoria = $objPedido->GetTipoDocSerieNum($nombre);

            $reg = $query_Categoria->fetch_object();

            echo json_encode($reg);

            break;

    case "GetIdPedido":

            require_once "../model/Pedido.php";

            $objPedido = new Pedido();

            $query_Categoria = $objPedido->GetIdPedido();

            $reg = $query_Categoria->fetch_object();

            echo json_encode($reg);

            break;

    case "GetTotal":

            require_once "../model/Pedido.php";

            $objPedido = new Pedido();

            $query_total = $objPedido->TotalPedido($_REQUEST["idPedido"]);

            $reg_total = $query_total->fetch_object();

            echo json_encode($reg_total);

            break;

}
	