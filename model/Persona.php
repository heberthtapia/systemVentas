<?php
	ini_set('max_execution_time', 300);
	require "Conexion.php";

	class Persona{


		public function __construct(){
		}

		public function Registrar($tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion_departamento,$direccion_provincia,$direccion_distrito,$direccion_calle,$direccion_nom_calle,$direccion_num,$direccion_zona,$direccion_nom_zona,$cx,$cy,$telefono,$email,$numero_cuenta,$foto,$estado){
			global $conexion;
			$sql = "INSERT INTO persona(tipo_persona,nombre,tipo_documento,num_documento,direccion_departamento,direccion_provincia,direccion_distrito,direccion_calle,direccion_nom_calle,direccion_num,direccion_zona,direccion_nom_zona,coorX,coorY,telefono,email,numero_cuenta,foto,estado)
			VALUES('$tipo_persona','$nombre','$tipo_documento','$num_documento','$direccion_departamento','$direccion_provincia','$direccion_distrito','$direccion_calle','$direccion_nom_calle','$direccion_num','$direccion_zona','$direccion_nom_zona','$cx','$cy','$telefono','$email','$numero_cuenta','$foto','$estado')";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Modificar($idpersona,$tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion_departamento,$direccion_provincia,$direccion_distrito,$direccion_calle,$direccion_nom_calle,$direccion_num,$direccion_zona,$direccion_nom_zona,$cx,$cy,$telefono,$email,$numero_cuenta,$foto,$estado){
			global $conexion;
			$sql = "UPDATE persona set tipo_persona = '$tipo_persona',nombre = '$nombre',tipo_documento='$tipo_documento',num_documento='$num_documento', direccion_departamento = '$direccion_departamento',direccion_provincia='$direccion_provincia',direccion_distrito='$direccion_distrito',direccion_calle='$direccion_calle', direccion_nom_calle='$direccion_nom_calle', direccion_num='$direccion_num', direccion_zona='$direccion_zona', direccion_nom_zona='$direccion_nom_zona', coorX='$cx', coorY='$cy' ,telefono='$telefono',email='$email',numero_cuenta='$numero_cuenta',foto='$foto',estado='$estado'
						WHERE idpersona = $idpersona";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Eliminar($idpersona){
			global $conexion;
			$sql = "SET FOREIGN_KEY_CHECKS=0";
			$query = $conexion->query($sql);
			if ($query) {
				$sql = "DELETE FROM pedido WHERE idcliente = $idpersona";
				$query = $conexion->query($sql);
				if ($query) {
					$sql = "DELETE FROM persona WHERE idpersona = $idpersona";
					$query = $conexion->query($sql);

					$sqlQuery = "SET FOREIGN_KEY_CHECKS=1";
					$querySql = $conexion->query($sqlQuery);

					return $query;
				}
			}
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
			$sql = "SELECT * FROM persona where tipo_persona='Cliente' order by idpersona desc ";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListaCliente($id){
			global $conexion;
			$sql = "SELECT * FROM persona where tipo_persona='Cliente' and idpersona = $id order by idpersona desc ";
			$query = $conexion->query($sql);
			return $query;
		}


	}
