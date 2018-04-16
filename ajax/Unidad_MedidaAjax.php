<?php

	session_start();

	require_once "../model/Unidad_Medida.php";

	$objUnidad_Medida = new Unidad_Medida();

	switch ($_GET["op"]) {

		case 'SaveOrUpdate':			

			$nombre = $_POST["txtNombre"]; // Llamamos al input txtNombre
			$prefijo = $_POST["txtPrefijo"];
			
			if(empty($_POST["txtIdUnidad_Medida"])){
				
				if($objUnidad_Medida->Registrar($nombre,$prefijo)){
					echo "Unidad de Medida Registrada";
				}else{
					echo "Unidad de Medida no ha podido ser registado.";
				}
			}else{
				
				$idunidad_medida = $_POST["txtIdUnidad_Medida"];
				if($objUnidad_Medida->Modificar($idunidad_medida, $nombre,$prefijo)){
					echo "Unidad de Medida actualizada";
				}else{
					echo "Informacion de la Unidad de Medida no ha podido ser actualizada.";
				}
			}
			break;

		case "delete":			
			
			$id = $_POST["id"];// Llamamos a la variable id del js que mandamos por $.post (Categoria.js (Linea 62))
			$result = $objUnidad_Medida->Eliminar($id);
			if ($result) {
				echo "Eliminado Exitosamente";
			} else {
				echo "No fue Eliminado";
			}
			break;
			
		
		case "list":
			$query_Tipo = $objUnidad_Medida->Listar();
			$data = Array();
            $i = 1;
     		while ($reg = $query_Tipo->fetch_object()) {
     			$data[] = array(
     				"0"=>$i,
                    "1"=>$reg->nombre,
                    "2"=>$reg->prefijo,
                    "3"=>'<button class="btn btn-warning" data-toggle="tooltip" title="Editar" onclick="cargarDataUnidad_Medida('.$reg->idunidad_medida.',\''.$reg->nombre.'\',\''.$reg->prefijo.'\')"><i class="fa fa-pencil"></i> </button>&nbsp;'.
                    '<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" id="btnEliminarUM" onclick="eliminarUnidad_Medida('.$reg->idunidad_medida.')"><i class="fa fa-trash"></i> </button>');
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
	