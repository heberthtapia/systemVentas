<?php
	require "Conexion.php";

	class Unidad_Medida{
	
		
		public function __construct(){
		}

		public function Registrar($nombre,$prefijo){
			global $conexion;
			$sql = "INSERT INTO unidad_medida(nombre,prefijo, estado)
						VALUES('$nombre','$prefijo', 'A')";
			$query = $conexion->query($sql);
			return $query;
		}
		
		public function Modificar($idunidad_medida, $nombre,$prefijo){
			global $conexion;
			$sql = "UPDATE unidad_medida set nombre = '$nombre',prefijo='$prefijo'
						WHERE idunidad_medida = $idunidad_medida";
			$query = $conexion->query($sql);
			return $query;
		}
		
		public function Eliminar($idunidad_medida){
			global $conexion;
			$sql = "DELETE FROM unidad_medida WHERE idunidad_medida = $idunidad_medida";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Listar(){
			global $conexion;
			$sql = "SELECT * FROM unidad_medida order by idunidad_medida desc";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Reporte(){
			global $conexion;
			$sql = "SELECT * FROM unidad_medida order by nombre asc";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarUM(){
			global $conexion;
			$sql = "SELECT * FROM unidad_medida";
			$query = $conexion->query($sql);
			return $query;
		}

		

	}
