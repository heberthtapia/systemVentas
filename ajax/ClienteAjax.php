<?php

	session_start();

	require_once "../model/Persona.php";

	$objCliente = new Persona();

	switch ($_GET["op"]) {

		case 'SaveOrUpdate':

			$tipo_persona           = $_POST["cboTipo_Persona"];
			$nombre                 = $_POST["txtNombre"];
			$tipo_documento         = $_POST["cboTipo_Documento"];
			$num_documento          = $_POST["txtNum_Documento"];
			$direccion_departamento = isset($_POST["txtDireccion_Departamento"])?$_POST["txtDireccion_Departamento"]:"";
			$direccion_provincia    = isset($_POST["txtDireccion_Provincia"])?$_POST["txtDireccion_Provincia"]:"";
			$direccion_calle        = isset($_POST["txtDireccion_Calle"])?$_POST["txtDireccion_Calle"]:"";
			$direccion_nom_calle    = isset($_POST["txtDireccion_Nom_Calle"])?$_POST["txtDireccion_Nom_Calle"]:"";
			$direccion_num          = isset($_POST["txtDireccion_Num"])?$_POST["txtDireccion_Num"]:"";
			$direccion_zona         = isset($_POST["txtDireccion_Zona"])?$_POST["txtDireccion_Zona"]:"";
			$direccion_nom_zona     = isset($_POST["txtDireccion_Nom_Zona"])?$_POST["txtDireccion_Nom_Zona"]:"";
			$cx                     = isset($_POST["cx"])?$_POST["cx"]:"";
			$cy                     = isset($_POST["cy"])?$_POST["cy"]:"";
			$imagen 				= $_FILES["imagenCli"]["tmp_name"];
			$ruta 					= $_FILES["imagenCli"]["name"];
			$telefono               = isset($_POST["txtTelefono"])?$_POST["txtTelefono"]:"";
			$email                  = isset($_POST["txtEmail"])?$_POST["txtEmail"]:"";
			$numero_cuenta          = isset($_POST["txtNumero_Cuenta"])?$_POST["txtNumero_Cuenta"]:"";
			$estado                 = $_POST["txtEstado"];

			if(move_uploaded_file($imagen, "../Files/Persona/".$ruta)){

				if(empty($_POST["txtIdPersona"])){

					if($objCliente->Registrar($tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion_departamento,$direccion_provincia,'',$direccion_calle,$direccion_nom_calle,$direccion_num,$direccion_zona,$direccion_nom_zona,$cx,$cy,$telefono,$email,$numero_cuenta,"Files/Persona/".$ruta,$estado)){
						echo "Cliente registrado correctamente";
					}else{
						echo "El Cliente no ha podido ser registrado.";
					}
				}else{

					$idpersona = $_POST["txtIdPersona"];
					if($objCliente->Modificar($idpersona,$tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion_departamento,$direccion_provincia,'',$direccion_calle,$direccion_nom_calle,$direccion_num,$direccion_zona,$direccion_nom_zona,$cx,$cy,$telefono,$email,$numero_cuenta,"Files/Persona/".$ruta,$estado)){
						echo "La informacion del Cliente ha sido actualizada";
					}else{
						echo "La informacion del Cliente no ha podido ser actualizada.";
					}
				}

			}else{
				$ruta_img = $_POST["txtRutaImgCli"];
				if(empty($_POST["txtIdPersona"])){

					if($objCliente->Registrar($tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion_departamento,$direccion_provincia,'',$direccion_calle,$direccion_nom_calle,$direccion_num,$direccion_zona,$direccion_nom_zona,$cx,$cy,$telefono,$email,$numero_cuenta,$ruta_img,$estado)){
						echo "Cliente registrado correctamente";
					}else{
						echo "El Cliente no ha podido ser registrado.";
					}
				}else{

					$idpersona = $_POST["txtIdPersona"];
					if($objCliente->Modificar($idpersona,$tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion_departamento,$direccion_provincia,'',$direccion_calle,$direccion_nom_calle,$direccion_num,$direccion_zona,$direccion_nom_zona,$cx,$cy,$telefono,$email,$numero_cuenta,$ruta_img,$estado)){
						echo "La informacion del Cliente ha sido actualizada";
					}else{
						echo "La informacion del Cliente no ha podido ser actualizada.";
					}
				}
			}
			break;

		case "delete":

			$id = $_POST["id"];// Llamamos a la variable id del js que mandamos por $.post (Categoria.js (Linea 62))
			$result = $objCliente->Eliminar($id);
			if ($result) {
				echo "Eliminado Exitosamente";
			} else {
				echo "No fue Eliminado";
			}
			break;

		case "list":
			$query_Tipo = $objCliente->ListarCliente();
			$data = Array();
            $i = 1;
     		while ($reg = $query_Tipo->fetch_object()) {

     			$data[] = array(
     				"id"=>$i,
					"1"=>$reg->nombre,
					"2"=>$reg->tipo_documento.'&nbsp;'.$reg->num_documento,
					"3"=>$reg->email,
					"4"=>$reg->telefono,
					"5"=>$reg->direccion_departamento,
					"6"=>'<button class="btn btn-warning" data-toggle="tooltip" title="Editar" onclick="cargarDataCliente('.$reg->idpersona.',\''.$reg->tipo_persona.'\',\''.$reg->nombre.'\',\''.$reg->tipo_documento.'\',\''.$reg->num_documento.'\',\''.$reg->direccion_departamento.'\',\''.$reg->direccion_provincia.'\',\''.$reg->direccion_distrito.'\',\''.$reg->direccion_calle.'\',\''.$reg->direccion_nom_calle.'\',\''.$reg->direccion_num.'\',\''.$reg->direccion_zona.'\',\''.$reg->direccion_nom_zona.'\',\''.$reg->coorX.'\',\''.$reg->coorY.'\',\''.$reg->foto.'\',\''.$reg->telefono.'\',\''.$reg->email.'\',\''.$reg->numero_cuenta.'\',\''.$reg->estado.'\')"><i class="fa fa-pencil"></i> </button>&nbsp;'.
					'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarCliente('.$reg->idpersona.')"><i class="fa fa-trash"></i> </button>');
				$i++;
			}
			$results = array(
            "sEcho" => 1,
        	"iTotalRecords" => count($data),
        	"iTotalDisplayRecords" => count($data),
            "aaData"=>$data);
			echo json_encode($results);

			break;
		case "listTipo_DocumentoPersona":
		        require_once "../model/Tipo_Documento.php";

		        $objTipo_Documento = new Tipo_Documento();

		        $query_tipo_Documento = $objTipo_Documento->VerTipo_Documento_Persona();

		        while ($reg = $query_tipo_Documento->fetch_object()) {
		            echo '<option value=' . $reg->nombre . '>' . $reg->nombre . '</option>';
		        }

		    break;

	}

