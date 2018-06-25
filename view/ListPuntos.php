<?php
require "../model/Conexion.php";

$status = $_POST['status'];
$zona   = $_POST["zona"];
$fecha  = $_POST["fecha"];

$sql = "SELECT *
        FROM persona
        WHERE  tipo_persona = 'Cliente'
        AND coorX != '' ";

if ($zona != '') {
    $sql.= "AND direccion_nom_zona like '%".$zona."%' ";
}

$Query = $conexion->query($sql);

$data = new stdClass();
$data->coordenada = new stdClass();
$c=0;
while ($reg = $Query->fetch_object()) {
    if ($reg->coorX != '') {

        $id = $reg->idpersona;

        $data->coordenada->cx[$c]      = $reg->coorX;
        $data->coordenada->cy[$c]      = $reg->coorY;
        $data->coordenada->nombre[$c]  = $reg->nombre;
        $data->coordenada->avenida[$c] = $reg->direccion_calle;
        $data->coordenada->nomAve[$c]  = $reg->direccion_nom_calle;
        $data->coordenada->num[$c]     = $reg->direccion_num;
        $data->coordenada->zona[$c]    = $reg->direccion_zona;
        $data->coordenada->nomZona[$c] = $reg->direccion_nom_zona;

        $sqlQuery = "SELECT * FROM status_cliente WHERE idpersona = '".$id."' AND fecha = '".$fecha."' ORDER BY (idstatus_cliente) ASC ";
        $query = $conexion->query($sqlQuery);
        $row = $query->fetch_object();

        if ($row->status == ''){
            $data->coordenada->status[$c]  = $reg->status;
        }else{
            $data->coordenada->status[$c]  = $row->status;
        }

        $c++;
    }
}
//print_r($data);
if($data){
    echo json_encode($data);
}else{
    echo 0;
    }

?>
