<?php

	session_start();

	require_once "../model/Articulo.php";

	$objArticulo = new Articulo();

	switch ($_GET["op"]) {

		case 'SaveOrUpdate':			

			$idcategoria = $_POST["cboCategoria"];
			$idunidad_medida = $_POST["cboUnidadMedida"];
			$nombre = $_POST["txtNombre"];
			$descripcion = $_POST["txtDescripcion"];
			$imagen = $_FILES["imagenArt"]["tmp_name"];
			$ruta = $_FILES["imagenArt"]["name"];

			if(move_uploaded_file($imagen, "../Files/Articulo/".$ruta)){

				if(empty($_POST["txtIdArticulo"])){
					
					if($objArticulo->Registrar($idcategoria, $idunidad_medida, $nombre, $descripcion, "Files/Articulo/".$ruta)){
						echo "Articulo Registrado";
					}else{
						echo "Articulo no ha podido ser registado.";
					}
				}else{
					
					$idarticulo = $_POST["txtIdArticulo"];
					if($objArticulo->Modificar($idarticulo, $idcategoria, $idunidad_medida, $nombre, $descripcion, "Files/Articulo/".$ruta)){
						echo "Informacion del Articulo ha sido actualizada";
					}else{
						echo "Informacion del Articulo no ha podido ser actualizada.";
					}
				}
			} else {
				$ruta_img = $_POST["txtRutaImgArt"];
				if(empty($_POST["txtIdArticulo"])){
					
					if($objArticulo->Registrar($idcategoria, $idunidad_medida, $nombre, $descripcion, $ruta_img)){
						echo "Articulo Registrado";
					}else{
						echo "Articulo no ha podido ser registado.";
					}
				}else{
					
					$idarticulo = $_POST["txtIdArticulo"];
					if($objArticulo->Modificar($idarticulo, $idcategoria, $idunidad_medida, $nombre, $descripcion, $ruta_img)){
						echo "Informacion del Articulo ha sido actualizada";
					}else{
						echo "Informacion del Articulo no ha podido ser actualizada.";
					}
				}
			}

			break;

		case "delete":			
			
			$id = $_POST["id"];
			$result = $objArticulo->Eliminar($id);
			if ($result) {
				echo "Eliminado Exitosamente";
			} else {
				echo "No fue Eliminado";
			}
			break;
		
		case "list":
			$query_Tipo = $objArticulo->Listar();
			$data = Array();
            $i = 1;
     		while ($reg = $query_Tipo->fetch_object()) {

     			$data[] = array("id"=>$i,
					"1"=>$reg->categoria,
					"2"=>$reg->unidadMedida,
					"3"=>$reg->nombre,
					"4"=>$reg->descripcion,
					"5"=>'<img width=100px height=100px src="./'.$reg->imagen.'" />',
					'<button class="btn btn-warning" data-toggle="tooltip" title="Editar" onclick="cargarDataArticulo('.$reg->idarticulo.',\''.$reg->idcategoria.'\',\''.$reg->idunidad_medida.'\',\''.$reg->nombre.'\',\''.$reg->descripcion.'\',\''.$reg->imagen.'\')"><i class="fa fa-pencil"></i> </button>&nbsp;'.
					'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarArticulo('.$reg->idarticulo.')"><i class="fa fa-trash"></i> </button>');
				$i++;
			}
			$results = array(
            "sEcho" => 1,
        	"iTotalRecords" => count($data),
        	"iTotalDisplayRecords" => count($data),
            "aaData"=>$data);
			echo json_encode($results);
            
			break;
		case "listArtElegir":
			$query_Tipo = $objArticulo->Listar();
			$data = Array();
            $i = 1;
     		while ($reg = $query_Tipo->fetch_object()) {

     			$data[] = array(
     				"0"=>'<button type="button" class="btn btn-warning" data-toggle="tooltip" title="Agregar al detalle" onclick="Agregar('.$reg->idarticulo.',\''.$reg->nombre.'\')" name="optArtBusqueda[]" data-nombre="'.$reg->nombre.'" id="'.$reg->idarticulo.'" value="'.$reg->idarticulo.'" ><i class="fa fa-check" ></i> </button>',
     				"1"=>$i,
					"2"=>$reg->categoria,
					"3"=>$reg->unidadMedida,
					"4"=>$reg->nombre,
					"5"=>$reg->descripcion,
					"6"=>'<img width=100px height=100px src="./'.$reg->imagen.'" />');
				$i++;
            }
            
            $results = array(
            "sEcho" => 1,
        	"iTotalRecords" => count($data),
        	"iTotalDisplayRecords" => count($data),
            "aaData"=>$data);
			echo json_encode($results);
            
			break;

		case "listCategoria":
	        require_once "../model/Categoria.php";

	        $objCategoria = new Categoria();

	        $query_Categoria = $objCategoria->Listar();

	        while ($reg = $query_Categoria->fetch_object()) {
	            echo '<option value=' . $reg->idcategoria . '>' . $reg->nombre . '</option>';
	        }

	        break;

	    case "listUM":

	    	require_once "../model/Categoria.php";

	        $objCategoria = new Categoria();

	        $query_Categoria = $objCategoria->ListarUM();

	        while ($reg = $query_Categoria->fetch_object()) {
	            echo '<option value=' . $reg->idunidad_medida . '>' . $reg->nombre . '</option>';
	        }

	        break;


	}
	