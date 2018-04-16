<?php
	require "Conexion.php";

	class Sucursal{
	
		
		public function __construct(){
		}

		public function Registrar($razon_social,$tipo_documento,$num_documento,$direccion,$telefono,$email,$representante,$logo,$estado){
			global $conexion;
			$sql = "INSERT INTO sucursal(razon_social,tipo_documento,num_documento,direccion,telefono,email,representante,logo,estado)
						VALUES('$razon_social','$tipo_documento','$num_documento','$direccion','$telefono','$email','$representante','$logo','$estado')";
			$query = $conexion->query($sql);
			return $query;
		}
		
		public function Modificar($idsucursal,$razon_social, $tipo_documento,$num_documento,$direccion,$telefono,$email,$representante,$logo,$estado){
			global $conexion;
			$sql = "UPDATE sucursal set razon_social = '$razon_social',direccion='$direccion',tipo_documento='$tipo_documento',num_documento='$num_documento',telefono	='$telefono',email='$email',representante='$representante',logo='$logo',estado='$estado'
						WHERE idsucursal = $idsucursal";
			$query = $conexion->query($sql);
			return $query;
		}
		
		public function Eliminar($idsucursal){
			global $conexion;
			$sql = "DELETE FROM sucursal WHERE idsucursal = $idsucursal";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Listar(){
			global $conexion;
			$sql = "SELECT * FROM sucursal order by idsucursal desc";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Reporte(){
			global $conexion;
			$sql = "SELECT * FROM sucursal order by razon_social asc";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarSucursalesEmp($id){
			global $conexion;
			$sql = "select u.*, s.razon_social, s.logo as logo, concat(e.nombre, ' ', e.apellidos) as empleado, e.*, e.estado as superadmin
	from usuario u inner join empleado e on u.idempleado = e.idempleado
	inner join sucursal s on u.idsucursal = s.idsucursal
	where u.idempleado = $id and u.estado='A'";
			$query = $conexion->query($sql);
			return $query;
		}

	}
