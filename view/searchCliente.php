<?php
	require "../model/Conexion.php";

	sleep( 2 );
	// no term passed - just exit early with no response
	if (empty($_GET['term'])) exit ;
	$q = strtolower($_GET["term"]);
	// remove slashes if they were magically added
	if (get_magic_quotes_gpc()) $q = stripslashes($q);

	$items = array();
	$tipoDoc = array();
	$name = array();
	$c = 0;

	$term = $_REQUEST['term'];

	$sql  = "SELECT * ";
	$sql .= "FROM persona AS p ";
	$sql .= "WHERE p.nombre like '%$term%' AND p.tipo_persona = 'Cliente' ";

	$strQuery = $conexion->query($sql);

	while( $row = $strQuery->fetch_object()){
		$items[$row->nombre.' - '.$row->tipo_documento.': '.$row->num_documento] = $row->idpersona;
		$tipoDoc[$c]         = $row->tipo_documento;
		$name[$c]          = $row->nombre;
		$c++;
	}

	$result = array();
	$c=0;
	foreach ($items as $key=>$value) {

		$doc = $tipoDoc[$c];
		$emp = $name[$c];

		if (strpos(strtolower($key), $q) !== false) {
			array_push($result, array("id"=>$value, "label"=>$key, "value" => strip_tags($emp)));
		}
		$c++;
		if (count($result) > 11)
			break;
	}

	// json_encode is available in PHP 5.2 and above, or you can install a PECL module in earlier versions
	echo json_encode($result);
?>
