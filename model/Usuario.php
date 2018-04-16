<?php
	require "Conexion.php";

	class usuario{
	
		
		public function __construct(){
		}

		public function Registrar($idsucursal, $idempleado, $tipo_usuario, $mnu_almacen, $mnu_compras, $mnu_ventas, $mnu_mantenimiento, $mnu_seguridad, $mnu_consulta_compras, $mnu_consultas_ventas, $mnu_admin){
			global $conexion;
			$sql = "INSERT INTO usuario(idsucursal, idempleado, tipo_usuario, fecha_registro, mnu_almacen, mnu_compras, mnu_ventas, mnu_mantenimiento, mnu_seguridad, mnu_consulta_compras, mnu_consulta_ventas, mnu_admin, estado)
						VALUES($idsucursal, $idempleado, '$tipo_usuario', curdate(), $mnu_almacen, $mnu_compras, $mnu_ventas, $mnu_mantenimiento, $mnu_seguridad, $mnu_consulta_compras, $mnu_consultas_ventas, $mnu_admin, 'A')";
			$query = $conexion->query($sql);
			return $query;
		}
		
		public function Modificar($idusuario, $idsucursal, $idempleado, $tipo_usuario, $mnu_almacen, $mnu_compras, $mnu_ventas, $mnu_mantenimiento, $mnu_seguridad, $mnu_consulta_compras, $mnu_consultas_ventas, $mnu_admin){
			global $conexion;
			$sql = "UPDATE usuario set idsucursal = $idsucursal, idempleado = $idempleado, tipo_usuario = '$tipo_usuario', mnu_almacen = $mnu_almacen, mnu_compras = $mnu_compras, mnu_ventas = $mnu_ventas, mnu_mantenimiento = $mnu_mantenimiento, mnu_seguridad = $mnu_seguridad, mnu_consulta_compras = $mnu_consulta_compras, mnu_consulta_ventas = $mnu_consultas_ventas, mnu_admin = $mnu_admin
						WHERE idusuario = $idusuario";
			$query = $conexion->query($sql);
			return $query;
		}
		
		public function Eliminar($idusuario){
			global $conexion;
			$sql = "DELETE from usuario WHERE idusuario = $idusuario";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Listar(){
			global $conexion;
			$sql = "select u.*, s.razon_social, concat(e.nombre, ' ', e.apellidos) as empleado
	from usuario u inner join sucursal s on u.idsucursal = s.idsucursal
	inner join empleado e on u.idempleado = e.idempleado
	where u.estado <> 'C'";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Ingresar_Sistema($user, $pass){
			global $conexion;
			$sql = "select u.*, s.razon_social, s.logo as logo, concat(e.nombre, ' ', e.apellidos) as empleado, e.*, e.estado as superadmin
	from usuario u inner join sucursal s on u.idsucursal = s.idsucursal
	inner join empleado e on u.idempleado = e.idempleado
	where e.login = '$user' and e.clave = '$pass' and u.estado <> 'C'";
			$query = $conexion->query($sql);
			return $query;
		}

	}
