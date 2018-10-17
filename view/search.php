<?php
	require "../model/Conexion.php";

	sleep( 2 );
	// no term passed - just exit early with no response
	if (empty($_GET['term'])) exit ;
	$q = strtolower($_GET["term"]);
	// remove slashes if they were magically added
	if (get_magic_quotes_gpc()) $q = stripslashes($q);

	$items = array();

	$term = $_REQUEST['term'];

	$sql  = "SELECT * ";
	$sql .= "FROM empleado AS e, usuario AS u ";
	$sql .= "WHERE e.idempleado = u.idempleado ";
	$sql .= "AND CONCAT(' ',e.nombre,e.apellidos) like '%$term%' AND e.idempleado = u.idempleado ";
	$sql .= "AND u.tipo_usuario = 'Vendedor' ";

	$strQuery = $conexion->query($sql);

		while( $row = $strQuery->fetch_object()){
			$items[$row->nombre.' '.$row->apellidos] = $row->idempleado;
		}


	$result = array();
	foreach ($items as $key=>$value) {

		if (strpos(strtolower($key), $q) !== false) {
			array_push($result, array("id"=>$value, "label"=>$key, "value" => strip_tags($key)));
		}
		if (count($result) > 11)
			break;
	}

	// json_encode is available in PHP 5.2 and above, or you can install a PECL module in earlier versions
	echo json_encode($result);
?>
