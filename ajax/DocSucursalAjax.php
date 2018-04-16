<?php
session_start();

switch ($_GET["op"]) {

    case 'Save':

    require_once "../model/DocSucursal.php";

        $obj= new DocSucursal();

        if(empty($_POST["idDocSucursal"])){
            $hosp = $obj->Registrar($_POST["detalle"]);
                if ($hosp) {
                    echo "Detalle Registrado";
                } else {
                    echo "No se ha podido registrar el Detalle";
                }
        } else {
            
            if($obj->Modificar($_POST["idDocSucursal"], $_POST["idSucursal"], $_POST["idTipoDoc"], $_POST["serie"], $_POST["numero"])){
                echo "Modificado Exitosamente";
            } else {
                echo "No se ha podido modificado";
            }
            
        }
                
        break;


    case "list":
        require_once "../model/DocSucursal.php";

        $objDocSucursal = new DocSucursal();

        $query_DocSucursal = $objDocSucursal->ListarDetalleDocSuc($_SESSION["idsucursal"]);

        $i = 1;
            while ($reg = $query_DocSucursal->fetch_object()) {
                $data[] = array($i,
                    $reg->nombre,
                    $reg->ultima_serie,
                    $reg->ultimo_numero,
                    '<button class="btn btn-warning" data-toggle="tooltip" title="Editar" onclick="cargarDataDocSucursal('.$reg->iddetalle_documento_sucursal.',\''.$reg->idtipo_documento.'\',\''.$reg->ultima_serie.'\',\''.$reg->ultimo_numero.'\')"><i class="fa fa-pencil"></i> </button>&nbsp;'.
                    '<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarDocSucursal('.$reg->iddetalle_documento_sucursal.')"><i class="fa fa-trash"></i> </button>'
                );
                $i++;
            }
            echo json_encode($data);

        break;

    case "listProveedor":
        require_once "../model/DocSucursal.php";

        $objDocSucursal = new DocSucursal();

        $query_prov = $objDocSucursal->ListarProveedor();

        $i = 1;
            while ($reg = $query_prov->fetch_object()) {
                 echo '<tr>
                        <td>'.$i.'</td>
                        <td>'.$reg->nombre.'</td>
                        <td>'.$reg->tipo_documento.'</td>
                        <td>'.$reg->num_documento.'</td>
                        <td>'.$reg->email.'</td>
                        <td>'.$reg->numero_cuenta.'</td>
                        <td><input type="radio" name="optProveedorBusqueda" data-nombre="'.$reg->nombre.'" id="'.$reg->idpersona.'" value="'.$reg->idpersona.'" /></td>
                       </tr>';
                 $i++; 
            }

        break;

    case "delete" :
        require_once "../model/DocSucursal.php";

        $obj= new DocSucursal();

        $idDocSucursal = $_POST["idDocSucursal"];

        $hosp = $obj->Eliminar($idDocSucursal);
                if ($hosp) {
                    echo "Eliminado Exitosamente";
                } else {
                    echo "No se ha podido eliminar";
                }
        break;

     case "listTipoDoc":

            require_once "../model/DocSucursal.php";

            $objDocSucursal = new DocSucursal();

            $query_Categoria = $objDocSucursal->ListarTipoDocumento();

            echo "<option>--Seleccione Comprobante--</option>";
            while ($reg = $query_Categoria->fetch_object()) {
                echo '<option value=' . $reg->idtipo_documento . '>' . $reg->nombre . '</option>';
            }

            break;

    case "GetTipoDocSerieNum":

            require_once "../model/DocSucursal.php";

            $objDocSucursal = new DocSucursal();

            $nombre = $_REQUEST["nombre"];

            $query_Categoria = $objDocSucursal->GetTipoDocSerieNum($nombre);

            $reg = $query_Categoria->fetch_object();

            echo json_encode($reg);

            break;

}
	