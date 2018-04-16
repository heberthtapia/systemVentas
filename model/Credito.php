<?php
	require "Conexion.php";

	class Credito{
	
		
		public function __construct(){
		}

		public function Registrar($idventa,$fecha_pago, $total_pago){
			global $conexion;
			$sql = "INSERT INTO credito(idventa,fecha_pago, total_pago)
						VALUES($idventa, curdate(), $total_pago)";
			$query = $conexion->query($sql);
			return $query;
		}
		
		public function Modificar($idCredito, $idventa,$fecha_pago, $total_pago){
			global $conexion;
			$sql = "UPDATE credito set idventa = '$idventa',fecha_pago='$fecha_pago', total_pago = $total_pago
						WHERE idCredito = $idCredito";
			$query = $conexion->query($sql);
			return $query;
		}
		
		public function Eliminar($idCredito){
			global $conexion;
			$sql = "DELETE FROM credito WHERE idcredito = $idCredito";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Listar($idsucursal){
			global $conexion;
			$sql = "select * from venta v inner join pedido p 
			on v.idpedido=p.idpedido where v.tipo_venta = 'Credito' and p.idsucursal='$idsucursal' order by v.idventa desc";
			$query = $conexion->query($sql);
			return $query;
		}
		public function ListarDeuda($idsucursal){
			global $conexion;
			$sql = "select v.* from venta v inner join pedido p on v.idpedido=p.idpedido
			where tipo_venta = 'Credito'
			and v.total>ifnull((select sum(c.total_pago) from credito c where c.idventa = v.idventa),0)
			and p.idsucursal='$idsucursal'
			order by v.idventa desc";
			$query = $conexion->query($sql);
			return $query;
		}


		public function GetMontoTotalCredito($idventa){
			global $conexion;
			$sql = "select sum(c.total_pago) as total_pago from credito c where c.idventa = $idventa";
			$query = $conexion->query($sql);
			return $query;
		}

		public function GetMontoTotalCreditoMayorCero($idventa){
			global $conexion;
			$sql = "select sum(c.total_pago) as total_pago from credito c where c.idventa = $idventa and c.total_pago >= 0";
			$query = $conexion->query($sql);
			return $query;
		}

		public function VerDetalleCredito($idventa){
			global $conexion;
			$sql = "select fecha_pago, total_pago 
	from credito where idventa = $idventa";
			$query = $conexion->query($sql);
			return $query;
		}
		
		public function MontoTotalPagados($idventa){
			global $conexion;
			$sql = "select v.total - sum(c.total_pago) as MontoTotalPagados
	from credito c inner join venta v on c.idventa = v.idventa where c.idventa = $idventa";
			$query = $conexion->query($sql);
			return $query;
		}

		public function GetIdVenta(){
			global $conexion;
			$sql = "select max(idventa) as id from venta";
			$query = $conexion->query($sql);
			return $query;
		}

	}
