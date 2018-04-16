<?php

	session_start();

	require_once "../model/Categoria.php";

	$objCategoria = new Categoria();

	switch ($_GET["op"]) {

		case 'SaveOrUpdate':			

			$nombre = $_POST["txtNombre"]; // Llamamos al input txtNombre

			if(empty($_POST["txtIdCategoria"])){
				
				if($objCategoria->Registrar($nombre)){
					echo "Categoria Registrada";
				}else{
					echo "Categoria no ha podido ser registado.";
				}
			}else{
				
				$idCategoria = $_POST["txtIdCategoria"];
				if($objCategoria->Modificar($idCategoria, $nombre)){
					echo "Categoria actualizada";
				}else{
					echo "Informacion de la Categoria no ha podido ser actualizada.";
				}
			}
			break;

		case "delete":			
			
			$id = $_POST["id"];// Llamamos a la variable id del js que mandamos por $.post (Categoria.js (Linea 62))
			$result = $objCategoria->Eliminar($id);
			if ($result) {
				echo "Eliminado Exitosamente";
			} else {
				echo "No fue Eliminado";
			}
			break;
		
		case "list":

			$query_Tipo = $objCategoria->Listar();
			$data = Array();
			$i = 1;
			while ($reg = $query_Tipo->fetch_object()) {
				$data[] = array(
					"id"=>$i,
					"1"=>$reg->nombre,
					"2"=>'<button class="btn btn-warning" data-toggle="tooltip" title="Editar" onclick="cargarDataCategoria('.$reg->idcategoria.',\''.$reg->nombre.'\')"><i class="fa fa-pencil"></i> </button>&nbsp;'.
					'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarCategoria('.$reg->idcategoria.')"><i class="fa fa-trash"></i> </button>');
				$i++;
			}
			$results = array(
            "sEcho" => 1,
        	"iTotalRecords" => count($data),
        	"iTotalDisplayRecords" => count($data),
            "aaData"=>$data);
			echo json_encode($results);
            
			break;

	}
	