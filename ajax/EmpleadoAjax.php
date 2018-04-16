<?php

	session_start();

	require_once "../model/Empleado.php";

	$objEmpleado = new Empleado();

	switch ($_GET["op"]) {

		case 'SaveOrUpdate':			

			$apellidos = $_POST["txtApellidos"];
			$nombre = $_POST["txtNombre"];
			$tipo_documento = $_POST["cboTipo_Documento"];
			$num_documento = $_POST["txtNum_Documento"];
			$direccion = $_POST["txtDireccion"];
			$telefono = $_POST["txtTelefono"];
			$email = $_POST["txtEmail"];
			$fecha_nacimiento = $_POST["txtFecha_Nacimiento"];
			$imagen = $_FILES["imagenEmp"]["tmp_name"];
			$ruta = $_FILES["imagenEmp"]["name"];
			$login = $_POST["txtLogin"];
			$clave = md5($_POST["txtClave"]);
			$estado = $_POST["txtEstado"];
			

			if(move_uploaded_file($imagen, "../Files/Empleado/".$ruta)){

				if(empty($_POST["txtIdEmpleado"])){
					
					if($objEmpleado->Registrar($apellidos,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$fecha_nacimiento,"Files/Empleado/".$ruta, $login, $clave,$estado)){
						echo "Empleado Registrado correctamente.";
					}else{
						echo "Empleado no ha podido ser registado.";
					}
				}else{
					
					if ($_POST["txtClave"] == "") {
						$idempleado = $_POST["txtIdEmpleado"];
						if($objEmpleado->Modificar($idempleado, $apellidos,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$fecha_nacimiento,"Files/Empleado/".$ruta, $login, $_POST["txtClaveOtro"], $estado)){
							echo "La información del empleado ha sido actualizada.";
						}else{
							echo "La información del empleado no ha podido ser actualizada.";
						}
					} else {
						$idempleado = $_POST["txtIdEmpleado"];
						if($objEmpleado->Modificar($idempleado, $apellidos,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$fecha_nacimiento,"Files/Empleado/".$ruta, $login, $clave, $estado)){
							echo "La información del empleado ha sido actualizada.";
						}else{
							echo "La información del empleado no ha podido ser actualizada.";
						}
					}

					
				}
			} else {
				$ruta_img = $_POST["txtRutaImgEmp"];
				if(empty($_POST["txtIdEmpleado"])){
					
					if($objEmpleado->Registrar($apellidos,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$fecha_nacimiento, $ruta_img, $login, $clave, $estado)){
						echo "Empleado Registrado correctamente.";
					}else{
						echo "Empleado no ha podido ser registado.";
					}
				}else{
					
					$idempleado = $_POST["txtIdEmpleado"];
					
					if ($_POST["txtClave"] == "") {
						$idempleado = $_POST["txtIdEmpleado"];
						if($objEmpleado->Modificar($idempleado, $apellidos,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$fecha_nacimiento,$ruta_img, $login, $_POST["txtClaveOtro"], $estado)){
							echo "La información del empleado ha sido actualizada.";
						}else{
							echo "La información del empleado no ha podido ser actualizada.";
						}
					} else {
						$idempleado = $_POST["txtIdEmpleado"];
						if($objEmpleado->Modificar($idempleado, $apellidos,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$fecha_nacimiento, $ruta_img, $login, $clave, $estado)){
							echo "La información del empleado ha sido actualizada.";
						}else{
							echo "La información del empleado no ha podido ser actualizada.";
						}
					}
				}
			}

			break;

		case "delete":			
			
			$id = $_POST["id"];// Llamamos a la variable id del js que mandamos por $.post (Categoria.js (Linea 62))
			$result = $objEmpleado->Eliminar($id);
			if ($result) {
				echo "Eliminado Exitosamente";
			} else {
				echo "No fue Eliminado";
			}
			break;
		
		case "list":
			$query_Tipo = $objEmpleado->Listar();
			$data= Array();
            $i = 1;
     		while ($reg = $query_Tipo->fetch_object()) {

     			$data[] = array("0"=>$i,
					"1"=>$reg->apellidos.'&nbsp;'.$reg->nombre,
					"2"=>$reg->tipo_documento,
					"3"=>$reg->num_documento,
					"4"=>$reg->email,
					"5"=>$reg->telefono,
					"6"=>$reg->login,
					"7"=>'<img width=100px height=100px src="./'.$reg->foto.'" />',
					"8"=>'<button class="btn btn-warning" data-toggle="tooltip" title="Editar" onclick="cargarDataEmpleado('.$reg->idempleado.',\''.$reg->apellidos.'\',\''.$reg->nombre.'\',\''.$reg->tipo_documento.'\',\''.$reg->num_documento.'\',\''.$reg->direccion.'\',\''.$reg->telefono.'\',\''.$reg->email.'\',\''.$reg->fecha_nacimiento.'\',\''.$reg->foto.'\',\''.$reg->login.'\',\''.$reg->clave.'\',\''.$reg->estado.'\')"><i class="fa fa-pencil"></i> </button>&nbsp;'.
					'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarEmpleado('.$reg->idempleado.')"><i class="fa fa-trash"></i> </button>');
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
		