<?php
	require "Conexion.php";
	
	class Ingreso{
	
		public function Registrar($idusuario, $idsucursal, $idproveedor, $tipo_comprobante, $serie_comprobante, $num_comprobante, $impuesto, $total, $detalle){
			global $conexion;
			$sw = true;
			try {
				
			
				$sql = "INSERT INTO ingreso(idusuario, idsucursal, idproveedor, tipo_comprobante, serie_comprobante, num_comprobante, fecha, impuesto,
						total, estado) 
						VALUES($idusuario, $idsucursal, $idproveedor, '$tipo_comprobante', '$serie_comprobante', '$num_comprobante', curdate(), $impuesto, $total, 'A')";
				//var_dump($sql);
				$conexion->query($sql);	
				$idingreso=$conexion->insert_id;		

				$conexion->autocommit(true);
				foreach($detalle as $indice => $valor){
					$sql_detalle = "INSERT INTO detalle_ingreso(idingreso, idarticulo, codigo, serie, descripcion, stock_ingreso, stock_actual, precio_compra, precio_ventadistribuidor, precio_ventapublico)
											VALUES($idingreso, ".$valor[0].", '".$valor[1]."', '".$valor[2]."', '".$valor[3]."', ".$valor[4].", ".$valor[4].", ".$valor[6].", ".$valor[7].", ".$valor[8].")";
					$conexion->query($sql_detalle) or $sw = false;
				}
				if ($conexion != null) {
                	$conexion->close();
            	}
			} catch (Exception $e) {
				$conexion->rollback();
			}
			return $sw;
		}

		public function Listar($idsucursal){
			global $conexion;
			$sql = "select i.*, p.nombre as proveedor from ingreso i inner join persona p on i.idproveedor = p.idpersona 
			where i.idsucursal = $idsucursal order by idingreso desc limit 0,2999";
			$query = $conexion->query($sql);
			return $query;
		}

		public function CambiarEstado($idingreso){
			global $conexion;
			$sql = "UPDATE ingreso set estado = 'C'
						WHERE idingreso = $idingreso";
			$query = $conexion->query($sql);
			return $query;
		}

		public function GetDetalleArticulo($idingreso){
			global $conexion;
			$sql = "select a.nombre as articulo, di.*, (di.stock_ingreso * di.precio_compra) as sub_total
	from detalle_ingreso di
	inner join articulo a on di.idarticulo = a.idarticulo where di.idingreso = $idingreso";
			$query = $conexion->query($sql);
			return $query;
		}

		public function GetProveedorSucursalIngreso($idingreso){
			global $conexion;
			$sql = "select p.*, ped.fecha,ped.tipo_comprobante, ped.num_comprobante, ped.serie_comprobante, s.razon_social, s.tipo_documento as documento_sucursal, s.num_documento as num_sucursal, s.direccion, s.telefono as telefono_suc, 
	s.email as email_suc, s.representante, s.logo, sum(di.stock_ingreso * di.precio_compra) as total,ped.impuesto
	from persona p inner join ingreso ped on ped.idproveedor = p.idpersona 
	inner join detalle_ingreso di on ped.idingreso = di.idingreso
	inner join sucursal s on ped.idsucursal = s.idsucursal
	where ped.idingreso = $idingreso";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarProveedor(){
			global $conexion;
			$sql = "select * from persona where tipo_persona = 'Proveedor' and estado = 'A'";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarTipoDocumento(){
			global $conexion;
			$sql = "select * from tipo_documento where operacion = 'Comprobante'";
			$query = $conexion->query($sql);
			return $query;
		}

		public function GetTipoDocSerieNum($nombre){
			global $conexion;
			$sql = "select ultima_serie, ultimo_numero from tipo_documento where operacion = 'Comprobante' and nombre = '$nombre'";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarProveedores(){
			global $conexion;
			$sql = "select * from persona where tipo_perssona = 'Proveedor'";
			$query = $conexion->query($sql);
			return $query;
		}

	}	