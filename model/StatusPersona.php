<?php
	session_start();
	require "Conexion.php";

	class Persona{


		public function __construct(){
		}

		public function Registrar($tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion_departamento,$direccion_provincia,$direccion_distrito,$direccion_calle,$direccion_nom_calle,$direccion_num,$direccion_zona,$direccion_nom_zona,$cx,$cy,$telefono,$email,$numero_cuenta,$estado){
			global $conexion;
			//$sql = "INSERT INTO persona(tipo_persona,nombre,tipo_documento,num_documento,direccion_departamento,direccion_provincia,direccion_distrito,direccion_calle,direccion_nom_calle,direccion_num,direccion_zona,direccion_nom_zona,coorX,coorY,telefono,email,numero_cuenta,estado)
			//VALUES('$tipo_persona','$nombre','$tipo_documento','$num_documento','$direccion_departamento','$direccion_provincia','$direccion_distrito','$direccion_calle','$direccion_nom_calle','$direccion_num','$direccion_zona','$direccion_nom_zona','$cx','$cy','$telefono','$email','$numero_cuenta','$estado')";
			//$query = $conexion->query($sql);
			//return $query;
		}

		public function Modificar($idpersona,$status){
			global $conexion;

			$date = date( 'Y-m-d' ); /*curdate()*/
			$hour = date( 'H:i:s' );
			$idUsuario = $_SESSION["idusuario"];

			$sql = "INSERT INTO status_cliente(idpersona, idusuario, status, fecha) VALUES('$idpersona', '$idUsuario', '$status','$date')";
			$query = $conexion->query($sql);
			return $query;

			//$sql = "UPDATE persona set status = '$status'
					//WHERE idpersona = $idpersona";
			//$query = $conexion->query($sql);
			//return $query;
		}

		public function Eliminar($idpersona){
			global $conexion;
			$sql = "DELETE FROM persona WHERE idpersona = $idpersona";
			$query = $conexion->query($sql);
			return $query;
		}
		public function Listar(){
			global $conexion;
			$sql = "SELECT * FROM persona order by idpersona desc";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarProveedor(){
			global $conexion;
			$sql = "SELECT * FROM persona where tipo_persona='Proveedor' order by idpersona desc";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ReporteProveedor(){
			global $conexion;
			$sql = "SELECT * FROM persona where tipo_persona='Proveedor' order by nombre asc";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ReporteCliente(){
			global $conexion;
			$sql = "SELECT * FROM persona where tipo_persona='Cliente' order by nombre asc";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarCliente(){
			global $conexion;
			$sql = "SELECT * FROM persona where tipo_persona='Cliente' order by idpersona desc limit 0,1950";
			$query = $conexion->query($sql);
			return $query;
		}



	}
