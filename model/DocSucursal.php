<?php
	require "Conexion.php";
	
	class DocSucursal{
	
		public function Registrar($detalle){
			global $conexion;
			$sw = true;
				
				foreach($detalle as $indice => $valor){
					$sql_detalle = "INSERT INTO detalle_documento_sucursal(idsucursal, idtipo_documento, ultima_serie, ultimo_numero)
											VALUES(".$valor[0].", ".$valor[1].", '".$valor[3]."', '".$valor[4]."')";
					$conexion->query($sql_detalle) or $sw = false;
				}
			return $sw;
		}

		public function Modificar($iddetalle_documento_sucursal, $idsucursal, $idtipo_documento, $serie, $numero){
			global $conexion;
			$sql = "UPDATE detalle_documento_sucursal set idsucursal = $idsucursal, idtipo_documento = $idtipo_documento,
						ultima_serie = '$serie', ultimo_numero = '$numero'
						where iddetalle_documento_sucursal = $iddetalle_documento_sucursal";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Eliminar($iddetalle_documento_sucursal){
			global $conexion;
			$sql = "DELETE FROM detalle_documento_sucursal
						where iddetalle_documento_sucursal = $iddetalle_documento_sucursal";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarTipoDocumento(){
			global $conexion;
			$sql = "select * from tipo_documento where operacion = 'Comprobante'";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarDetalleDocSuc($idsucursal){
			global $conexion;
			$sql = "select dds.*, td.nombre 
	from detalle_documento_sucursal dds inner join tipo_documento td on dds.idtipo_documento = td.idtipo_documento
	where dds.idsucursal = $idsucursal";
			$query = $conexion->query($sql);
			return $query;
		}

	}