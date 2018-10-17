<?PHP
	require "../model/Conexion.php";

	$data  = $_POST['res'];

	$data  = json_decode($data);

	$sqlQuery = "SELECT COUNT(idarticulo) AS cont FROM objetivo WHERE idarticulo = '".$data->idArticulo."' ";
	$q = $conexion->query($sqlQuery);

	$reg = $q->fetch_object();

	if( ($reg->cont) > 0 ){
		$sql = "UPDATE objetivo set objetivo = $data->cantObjetivo WHERE idarticulo = $data->idArticulo";
		$query = $conexion->query($sql);
	}else{
		$sql = "INSERT INTO objetivo (idarticulo, objetivo) ";
		$sql.= "VALUES ('".trim($data->idArticulo)."', '".trim($data->cantObjetivo)."')";
		$query = $conexion->query($sql);
	}

	if($query)
		echo json_encode($data);
	else
		echo 0;
?>
