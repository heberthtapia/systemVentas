<?php

	session_start();

	require_once "../model/Tipo_Documento.php";

	$objTipo_Documento = new Tipo_Documento();

	switch ($_GET["op"]) {

		case 'SaveOrUpdate':			

			$nombre = $_POST["txtNombre"]; // Llamamos al input txtNombre
			$operacion = $_POST["txtOperacion"];

			if(empty($_POST["txtIdTipo_Documento"])){
				
				if($objTipo_Documento->Registrar($nombre,$operacion)){
					echo "Tipo de Documento registrado correctamente";
				}else{
					echo "Tipo documento no ha podido ser registrado.";
				}
			}else{
				
				$idtipo_documento = $_POST["txtIdTipo_Documento"];
				if($objTipo_Documento->Modificar($idtipo_documento,$nombre,$operacion)){
					echo "La informacion del tipo de documento ha sido actualizada";
				}else{
					echo "La informacion del tipo de documento no ha podido ser actualizada.";
				}
			}
			break;

		case "delete":			
			
			$id = $_POST["id"];// Llamamos a la variable id del js que mandamos por $.post (Categoria.js (Linea 62))
			$result = $objTipo_Documento->Eliminar($id);
			if ($result) {
				echo "Eliminado Exitosamente";
			} else {
				echo "No fue Eliminado";
			}
			break;
		
		case "list":
			$query_Tipo = $objTipo_Documento->Listar();

            $i = 1;
     		while ($reg = $query_Tipo->fetch_object()) {
     			$data[] = array($i,
                    $reg->nombre,
                    $reg->operacion,
                    '<button class="btn btn-warning" data-toggle="tooltip" title="Editar" onclick="cargarDataTipo_Documento('.$reg->idtipo_documento.',\''.$reg->nombre.'\',\''.$reg->operacion.'\')"><i class="fa fa-pencil"></i> </button>&nbsp;'.
                    '<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarTipo_Documento('.$reg->idtipo_documento.')"><i class="fa fa-trash"></i> </button>');
                $i++;
            }
            echo json_encode($data);

			break;

	}
	