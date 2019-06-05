<?php
	session_start();
	require "Conexion.php";
	date_default_timezone_set("America/La_Paz" );


	class Empleado{

		public function __construct(){
		}

		public function Registrar($apellidos,$nombre,$tipo_documento,$num_documento,$direccion,$coorX,$coorY,$telefono,$email,$fecha_nacimiento,$foto, $login, $clave,$estado){
			global $conexion;

			$hoy = date("Y-m-d H:i:s");
			$idEmp = $_SESSION['idempleado'];

			$sql = "INSERT INTO empleado(apellidos,nombre,tipo_documento,num_documento,direccion,coorX,coorY,telefono,email,fecha_nacimiento,foto, login, clave,estado,id_update)
						VALUES('$apellidos','$nombre','$tipo_documento','$num_documento','$direccion','$coorX','$coorY','$telefono','$email','$fecha_nacimiento','$foto', '$login', '$clave','$estado','$idEmp - $hoy')";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Modificar($idempleado,$apellidos,$nombre, $tipo_documento,$num_documento,$direccion,$coorX,$coorY,$telefono,$email,$fecha_nacimiento,$foto, $login, $clave,$estado){
			global $conexion;

			$hoy = date("Y-m-d H:i:s");
			$idEmp = $_SESSION['idempleado'];

			$sql = "UPDATE empleado set apellidos = '$apellidos',nombre = '$nombre',tipo_documento='$tipo_documento',num_documento='$num_documento', direccion = '$direccion', coorX = '$coorX', coorY = '$coorY', telefono	='$telefono',email='$email',fecha_nacimiento='$fecha_nacimiento',foto='$foto',
					login = '$login', clave = '$clave' ,estado='$estado', id_update = '$idEmp - $hoy'
						WHERE idempleado = $idempleado";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Eliminar($idempleado){
			global $conexion;
			$sql = "SET FOREIGN_KEY_CHECKS=0";
			$query = $conexion->query($sql);
			if ($query) {
				$sql = "DELETE FROM usuario WHERE idempleado = $idempleado";
				$query = $conexion->query($sql);
				if ($query) {
					$sql = "DELETE FROM empleado WHERE idempleado = $idempleado";
					$query = $conexion->query($sql);

					$sqlQuery = "SET FOREIGN_KEY_CHECKS=1";
					$querySql = $conexion->query($sqlQuery);

					return $query;
				}
			}
		}

		public function Listar(){
			global $conexion;
			$sql = "SELECT * FROM empleado order by idempleado desc";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Reporte(){
			global $conexion;
			$sql = "SELECT * FROM empleado order by apellidos asc";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarEmp(){
			global $conexion;
			$sql = "SELECT * FROM empleado AS e, usuario AS u WHERE e.idempleado = u.idempleado AND u.tipo_usuario = 'Vendedor' order by e.idempleado desc";
			$query = $conexion->query($sql);
			return $query;
		}

		public function updatePerfil($id){
			global $conexion;
			$sql = "UPDATE usuario SET mnu_perfil = 0 WHERE idusuario = $id ";
			$query = $conexion->query($sql);
			return $query;
		}

	}
