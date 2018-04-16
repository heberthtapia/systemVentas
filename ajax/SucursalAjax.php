<?php

	session_start();

	require_once "../model/Sucursal.php";

	$objSucursal = new Sucursal();

	switch ($_GET["op"]) {

		case 'SaveOrUpdate':			

			$razon_social = $_POST["txtRazon_Social"];
			$tipo_documento = $_POST["cboTipo_Documento"];
			$num_documento = $_POST["txtNum_Documento"];
			$direccion = $_POST["txtDireccion"];
			$telefono = $_POST["txtTelefono"];
			$email = $_POST["txtEmail"];
			$representante = $_POST["txtRepresentante"];
			$imagen = $_FILES["imagenSuc"]["tmp_name"];
			$ruta = $_FILES["imagenSuc"]["name"];
			$estado = $_POST["txtEstado"];
			

			if(move_uploaded_file($imagen, "../Files/Sucursal/".$ruta)){

				if(empty($_POST["txtIdSucursal"])){
					
					if($objSucursal->Registrar($razon_social,$tipo_documento,$num_documento,$direccion,$telefono,$email,$representante,"Files/Sucursal/".$ruta,$estado)){
						echo "Sucursal Registrada correctamente.";
					}else{
						echo "Sucursal no ha podido ser registada.";
					}
				}else{
					
					$idsucursal = $_POST["txtIdSucursal"];
					if($objSucursal->Modificar($idsucursal, $razon_social,$tipo_documento,$num_documento,$direccion,$telefono,$email,$representante,"Files/Sucursal/".$ruta,$estado)){
						echo "La información de la Sucursal ha sido actualizada.";
					}else{
						echo "La información de la Sucursal no ha podido ser actualizada.";
					}
				}
			} else {
				$ruta_img = $_POST["txtRutaImgSuc"];
				if(empty($_POST["txtIdSucursal"])){
					
					if($objSucursal->Registrar($razon_social,$tipo_documento,$num_documento,$direccion,$telefono,$email,$representante, $ruta_img,$estado)){
						echo "Sucursal Registrada correctamente.";
					}else{
						echo "Sucursal no ha podido ser registada.";
					}
				}else{
					
					$idsucursal = $_POST["txtIdSucursal"];
					if($objSucursal->Modificar($idsucursal,$razon_social,$tipo_documento,$num_documento,$direccion,$telefono,$email,$representante, $ruta_img,$estado)){
						echo "La información de la Sucursal ha sido actualizada.";
					}else{
						echo "La información de la Sucursal no ha podido ser actualizada.";
					}
				}
			}

			break;

		case "delete":			
			
			$id = $_POST["id"];// Llamamos a la variable id del js que mandamos por $.post (Categoria.js (Linea 62))
			$result = $objSucursal->Eliminar($id);
			if ($result) {
				echo "Eliminado Exitosamente";
			} else {
				echo "No fue Eliminado";
			}
			break;
		
		case "list":
			$query_Tipo = $objSucursal->Listar();

            $i = 1;
     		while ($reg = $query_Tipo->fetch_object()) {

     			$data[] = array($i,
					$reg->razon_social,
					$reg->tipo_documento.'&nbsp;'.$reg->num_documento,
					$reg->direccion,
					$reg->email,
					'<img width=100px height=100px src="./'.$reg->logo.'" />',
					'<button class="btn btn-warning" data-toggle="tooltip" title="Editar" onclick="cargarDataSucursal('.$reg->idsucursal.',\''.$reg->razon_social.'\',\''.$reg->tipo_documento.'\',\''.$reg->num_documento.'\',\''.$reg->direccion.'\',\''.$reg->telefono.'\',\''.$reg->email.'\',\''.$reg->representante.'\',\''.$reg->logo.'\',\''.$reg->estado.'\')"><i class="fa fa-pencil"></i> </button>&nbsp;'.
					'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarSucursal('.$reg->idsucursal.')"><i class="fa fa-trash"></i> </button>');
				$i++;
			}
			echo json_encode($data);
            
			break;
			
		case "listSucursalEmp":

			require_once "../model/Configuracion.php";

			$objConf = new Configuracion();

			$query_conf = $objConf->Listar();

			$query_Tipo = $objSucursal->ListarSucursalesEmp($_SESSION["idempleado"]);

            $i = 1;
            $estadoAdmin = "";
            $idempleado = "";
            $idusuario = "";
            $idsucursal = "";
            $empleado = "";
            $tipo_documento = "";
            $direccion = "";
            $telefono = "";
            $foto = "";
            $email = "";
            $login = "";
            $mnu_almacen = 1;
	            $mnu_compras = 1;
	            $mnu_ventas = 1;
	            $mnu_mantenimiento = 1;
	            $mnu_seguridad = 1;
	            $mnu_consulta_compras = 1;
	            $mnu_consulta_ventas = 1;
	            $mnu_admin = 1;

	        $regConf = $query_conf->fetch_object();

     		while ($reg = $query_Tipo->fetch_object()) {
	             echo '<tr>
	             		<td><button type="button" onclick="Acceder('.$reg->idusuario.',\''.$reg->idsucursal.'\',\''.$reg->idempleado.'\',\''.$reg->empleado.'\',\''.$reg->tipo_documento.'\',\''.$reg->tipo_usuario.'\',\''.$reg->num_documento.'\',\''.$reg->direccion.'\',\''.$reg->telefono.'\',\''.$reg->foto.'\',\''.$reg->logo.'\',\''.$reg->email.'\',\''.$reg->login.'\',\''.$reg->razon_social.'\',\''.$reg->mnu_almacen.'\',\''.$reg->mnu_compras.'\',\''.$reg->mnu_ventas.'\',\''.$reg->mnu_mantenimiento.'\',\''.$reg->mnu_seguridad.'\',\''.$reg->mnu_consulta_compras.'\',\''.$reg->mnu_consulta_ventas.'\',\''.$reg->mnu_admin.'\')" class="btn btn-info pull-left">Acceder</button></td>
		                <td>'.$reg->razon_social.'</td>
		                <td><img class="img-thumbnail" width="100px" height="100px" src="./'.$reg->logo.'" /></td>
	                   </tr>';
	             $i++; 
	             $estadoAdmin = $reg->superadmin;
	             $idempleado = $reg->idempleado;
	             $idusuario = $reg->idusuario;
	             $idsucursal = $reg->idsucursal;
	             $empleado = $reg->empleado;
	             $tipo_documento = $reg->tipo_documento;
	             $direccion = $reg->direccion;
	             $telefono = $reg->telefono;
	             $foto = $reg->foto;
	             $email = $reg->email;
	             $login = $reg->login;
            }

            if ($estadoAdmin == "S") {
            	echo '<tr>
            		<td><button type="button" onclick="AccederSuperAdmin('.$idempleado.',\''.$idusuario.'\',\''.$idsucursal.'\',\''.$estadoAdmin.'\',\''.$empleado.'\',\''.$tipo_documento.'\',\''.$direccion.'\',\''.$telefono.'\',\''.$foto.'\',\''.$email.'\',\''.$login.'\',\''.$mnu_almacen.'\',\''.$mnu_compras.'\',\''.$mnu_ventas.'\',\''.$mnu_mantenimiento.'\',\''.$mnu_seguridad.'\',\''.$mnu_consulta_compras.'\',\''.$mnu_consulta_ventas.'\',\''.$mnu_admin.'\',\''.$regConf->logo.'\')" class="btn btn-success pull-left">Acceder</button></td>
            		<td>Acceso Administrador</td>
            		<td></td>
            	</tr>';
            }
            
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
	