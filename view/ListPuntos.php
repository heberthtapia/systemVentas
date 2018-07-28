<?php
require "../model/Conexion.php";

$status     = $_POST['status'];
$zona       = $_POST["zona"];
$fecha      = $_POST["fecha"];
$idEmpleado = $_POST["idempleado"];

$sql = "SELECT *
        FROM persona
        WHERE tipo_persona = 'Cliente'
        AND coorX != '' ";

if ($zona != '') {
    $sql.= "AND direccion_nom_zona like '%".$zona."%' ";
}

$Query = $conexion->query($sql);

$data = new stdClass();

$c=0;

$sqlEmp = "SELECT u.idusuario ";
$sqlEmp.= "FROM empleado AS e, usuario AS u ";
$sqlEmp.= "WHERE e.idempleado = u.idempleado ";
$sqlEmp.= "AND u.idempleado = '".$idEmpleado."' ";

$queryEmp = $conexion->query($sqlEmp);
$rowReg = $queryEmp->fetch_object();

while ($reg = $Query->fetch_object()) {

    $id = $reg->idpersona;

    if ($status != 'L') {

        $sqlQuery = "SELECT * ";
        $sqlQuery.= "FROM status_cliente ";
        $sqlQuery.= "WHERE idpersona = '".$id."' ";
        $sqlQuery.= "AND fecha = '".$fecha."' ";
        $sqlQuery.= "AND status = '".$status."' ";
        $sqlQuery.= "ORDER BY (idstatus_cliente) DESC ";

        $query = $conexion->query($sqlQuery);
        $row = $query->fetch_object();

        if ( $idEmpleado == 0 ) {
            if (!empty($row->status)) {
                $data->cx[$c]      = $reg->coorX;
                $data->cy[$c]      = $reg->coorY;
                $data->nombre[$c]  = $reg->nombre;
                $data->avenida[$c] = $reg->direccion_calle;
                $data->nomAve[$c]  = $reg->direccion_nom_calle;
                $data->num[$c]     = $reg->direccion_num;
                $data->zona[$c]    = $reg->direccion_zona;
                $data->nomZona[$c] = $reg->direccion_nom_zona;
                $data->foto[$c]    = $reg->foto;

                $data->status[$c]  = $row->status;
            }

        }elseif( $row->idusuario == $rowReg->idusuario ){
                $data->cx[$c]      = $reg->coorX;
                $data->cy[$c]      = $reg->coorY;
                $data->nombre[$c]  = $reg->nombre;
                $data->avenida[$c] = $reg->direccion_calle;
                $data->nomAve[$c]  = $reg->direccion_nom_calle;
                $data->num[$c]     = $reg->direccion_num;
                $data->zona[$c]    = $reg->direccion_zona;
                $data->nomZona[$c] = $reg->direccion_nom_zona;
                $data->foto[$c]    = $reg->foto;

                $data->status[$c]  = $row->status;
        }
        $c++;
    }else{

        $sqlQuery = "SELECT * ";
        $sqlQuery.= "FROM status_cliente ";
        $sqlQuery.= "WHERE idpersona = '".$id."' ";
        $sqlQuery.= "AND fecha = '".$fecha."' ";
        //$sqlQuery.= "AND status = '".$status."' ";
        $sqlQuery.= "ORDER BY (idstatus_cliente) DESC ";

        $query = $conexion->query($sqlQuery);
        $row = $query->fetch_object();

        /*echo '===>'.$row->idusuario.'-------'. $rowReg->idusuario;
        echo '<br>';*/

        if ( $idEmpleado == 0 ) {

            $data->cx[$c]      = $reg->coorX;
            $data->cy[$c]      = $reg->coorY;
            $data->nombre[$c]  = $reg->nombre;
            $data->avenida[$c] = $reg->direccion_calle;
            $data->nomAve[$c]  = $reg->direccion_nom_calle;
            $data->num[$c]     = $reg->direccion_num;
            $data->zona[$c]    = $reg->direccion_zona;
            $data->nomZona[$c] = $reg->direccion_nom_zona;
            $data->foto[$c]    = $reg->foto;

            $data->status[$c]  = $reg->status;
            $c++;

        }elseif( $row->idusuario == $rowReg->idusuario ){

            $data->cx[$c]      = $reg->coorX;
            $data->cy[$c]      = $reg->coorY;
            $data->nombre[$c]  = $reg->nombre;
            $data->avenida[$c] = $reg->direccion_calle;
            $data->nomAve[$c]  = $reg->direccion_nom_calle;
            $data->num[$c]     = $reg->direccion_num;
            $data->zona[$c]    = $reg->direccion_zona;
            $data->nomZona[$c] = $reg->direccion_nom_zona;
            $data->foto[$c]    = $reg->foto;

            $data->status[$c]  = $reg->status;
            $c++;
        }

    }
}
//echo $c;
//print_r($data);
if($data){
    echo json_encode($data);
}else{
    echo 0;
    }

?>
