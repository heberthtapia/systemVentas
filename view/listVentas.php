<?php
require "../model/Conexion.php";

$grupo = $_REQUEST["grup"];

$sql = "SELECT e.apellidos, e.nombre
        FROM empleado AS e, usuario AS u
        WHERE e.estado = 'A' AND e.idempleado = u.idempleado AND u.tipo_usuario = 'Vendedor' ";
if($grupo != 0)
    $sql.= "AND u.num_grupo = $grupo ";
$sql.= "ORDER BY e.idempleado ";

$Query = $conexion->query($sql);

$Query1 = $conexion->query($sql);

?>

<table id="tblVentasDetalladas" class="table table-striped table-bordered table-condensed table-hover" cellpadding="0" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>ITEM</th>
        <?php
            while ($reg = $Query1->fetch_object()) {
        ?>
            <th><?=$reg->apellidos.' '.$reg->nombre;?></th>
        <?php
            }
        ?>
            <th>TOTAL</th>
        </tr>
    </thead>

    <tfoot>
        <tr>
            <th>ITEM</th>
        <?php
            while ($row = $Query->fetch_object()) {
        ?>
            <th><?=$row->apellidos.' '.$row->nombre;?></th>
        <?php
            }
        ?>
            <th>TOTAL</th>
        </tr>
    </tfoot>
</table>
