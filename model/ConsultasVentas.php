<?php
	require "Conexion.php";

	class ConsultasVentas{


		public function __construct(){

		}

		public function ListarVentasFechas($idsucursal, $fecha_desde, $fecha_hasta){
			global $conexion;
			$sql = "select p.idpedido, p.tipo_pedido, v.fecha,s.razon_social as sucursal,
				concat(e.apellidos,' ',e.nombre) as empleado,
				pe.nombre as cliente,v.tipo_comprobante as comprobante,
				v.serie_comprobante as serie,v.num_comprobante as numero,
				v.impuesto,
				format((v.total-(v.impuesto*v.total/(100+v.impuesto))),2) as subtotal,
				format((v.impuesto*v.total/(100+v.impuesto)),2) as totalimpuesto,
				v.total
				from venta v inner join pedido p on v.idpedido=p.idpedido
				inner join sucursal s on p.idsucursal=s.idsucursal
				inner join usuario u on p.idusuario=u.idusuario
				inner join empleado e on u.idempleado=e.idempleado
				inner join persona pe on p.idcliente=pe.idpersona
				where v.fecha>='$fecha_desde' and v.fecha<='$fecha_hasta' and s.idsucursal= $idsucursal and v.estado='A'
				order by v.fecha desc
				";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarVentasDetalladas($idsucursal, $fecha_desde, $fecha_hasta){
			global $conexion;
			$sql = "select v.fecha,s.razon_social as sucursal,
				concat(e.apellidos,' ',e.nombre) as empleado,
				pe.nombre as cliente,v.tipo_comprobante as comprobante,
				v.serie_comprobante as serie,v.num_comprobante as numero,
				v.impuesto,
				a.nombre as articulo,di.codigo as codigo,di.serie as serie_art,
				dp.cantidad,dp.precio_venta,dp.descuento,
				((dp.cantidad*dp.precio_venta)-dp.descuento)as total
				from detalle_pedido dp inner join detalle_ingreso di on dp.iddetalle_ingreso=di.iddetalle_ingreso
				inner join articulo a on di.idarticulo=a.idarticulo
				inner join pedido p on dp.idpedido=p.idpedido
				inner join venta v on v.idpedido=p.idpedido
				inner join sucursal s on p.idsucursal=s.idsucursal
				inner join usuario u on p.idusuario=u.idusuario
				inner join empleado e on u.idempleado=e.idempleado
				inner join persona pe on p.idcliente=pe.idpersona
				where v.fecha>='$fecha_desde' and v.fecha<='$fecha_hasta'
				and s.idsucursal= $idsucursal and v.estado='A'
				order by v.fecha desc
				";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarVentasDetalladasArticulo($idsucursal, $categoria, $fecha_desde, $fecha_hasta){
			global $conexion;

			$sql = "SELECT a.idarticulo, a.nombre AS nombre, c.nombre AS categoria FROM articulo AS a, categoria AS c ";
			$sql.= "WHERE a.idcategoria = c.idcategoria ";
			if ($categoria != 0) {
				$sql.= "AND c.idcategoria = $categoria";
			}

			$query = $conexion->query($sql);

			/*$sql = "select ,' ',e.nombre) as empleado, a.nombre as articulo, di.codigo as codigo,di.serie as serie_art,
				dp.cantidad,dp.precio_venta,dp.descuento,
				(dp.cantidad*(dp.precio_venta-dp.descuento))as total
				from detalle_pedido dp inner join detalle_ingreso di on dp.iddetalle_ingreso=di.iddetalle_ingreso
				inner join articulo a on di.idarticulo=a.idarticulo
				inner join pedido p on dp.idpedido=p.idpedido
				inner join venta v on v.idpedido=p.idpedido
				inner join sucursal s on p.idsucursal=s.idsucursal
				inner join usuario u on p.idusuario=u.idusuario
				inner join empleado e on u.idempleado=e.idempleado
				inner join persona pe on p.idcliente=pe.idpersona
				where v.fecha>='$fecha_desde' and v.fecha<='$fecha_hasta'
				and s.idsucursal= $idsucursal and v.estado='A'
				order by v.fecha desc
				";*/
			//$query = $conexion->query($sql);
			return $query;
		}

		public function ListarVentasPendientes($idsucursal, $fecha_desde, $fecha_hasta){
			global $conexion;
			$sql = "select v.fecha,s.razon_social as sucursal,
				concat(e.apellidos,' ',e.nombre) as empleado,
				pe.nombre as cliente,v.tipo_comprobante as comprobante,
				v.serie_comprobante as serie,v.num_comprobante as numero,
				v.impuesto,
				format((v.total-(v.impuesto*v.total/(100+v.impuesto))),2) as subtotal,
				format((v.impuesto*v.total/(100+v.impuesto)),2) as totalimpuesto,
				v.total as totalpagar,(select sum(total_pago) from credito where idventa=v.idventa)as totalpagado,
				(v.total-(select sum(total_pago) from credito where idventa=v.idventa))as totaldeuda
				from venta v inner join pedido p on v.idpedido=p.idpedido
				inner join sucursal s on p.idsucursal=s.idsucursal
				inner join usuario u on p.idusuario=u.idusuario
				inner join empleado e on u.idempleado=e.idempleado
				inner join persona pe on p.idcliente=pe.idpersona
				where v.fecha>='$fecha_desde' and v.fecha<='$fecha_hasta'
				and (v.total-(select sum(total_pago) from credito where idventa=v.idventa))> 0
				and s.idsucursal= $idsucursal and v.tipo_venta='Credito' and v.estado='A'
				order by v.fecha desc
				";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarVentasContado($idsucursal, $fecha_desde, $fecha_hasta){
			global $conexion;
			$sql = "select v.fecha,s.razon_social as sucursal,
				concat(e.apellidos,' ',e.nombre) as empleado,
				pe.nombre as cliente,v.tipo_comprobante as comprobante,
				v.serie_comprobante as serie,v.num_comprobante as numero,
				v.impuesto,
				format((v.total-(v.impuesto*v.total/(100+v.impuesto))),2) as subtotal,
				format((v.impuesto*v.total/(100+v.impuesto)),2) as totalimpuesto,
				v.total
				from venta v inner join pedido p on v.idpedido=p.idpedido
				inner join sucursal s on p.idsucursal=s.idsucursal
				inner join usuario u on p.idusuario=u.idusuario
				inner join empleado e on u.idempleado=e.idempleado
				inner join persona pe on p.idcliente=pe.idpersona
				where v.fecha>='$fecha_desde' and v.fecha<='$fecha_hasta' and s.idsucursal= $idsucursal and v.tipo_venta='Contado' and v.estado='A'
				order by v.fecha desc
				";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarVentasCredito($idsucursal, $fecha_desde, $fecha_hasta){
			global $conexion;
			$sql = "select v.fecha,s.razon_social as sucursal,
				concat(e.apellidos,' ',e.nombre) as empleado,
				pe.nombre as cliente,v.tipo_comprobante as comprobante,
				v.serie_comprobante as serie,v.num_comprobante as numero,
				v.impuesto,
				format((v.total-(v.impuesto*v.total/(100+v.impuesto))),2) as subtotal,
				format((v.impuesto*v.total/(100+v.impuesto)),2) as totalimpuesto,
				v.total as totalpagar,(select sum(total_pago) from credito where idventa=v.idventa)as totalpagado,
				(v.total-(select sum(total_pago) from credito where idventa=v.idventa))as totaldeuda
				from venta v inner join pedido p on v.idpedido=p.idpedido
				inner join sucursal s on p.idsucursal=s.idsucursal
				inner join usuario u on p.idusuario=u.idusuario
				inner join empleado e on u.idempleado=e.idempleado
				inner join persona pe on p.idcliente=pe.idpersona
				where v.fecha>='$fecha_desde' and v.fecha<='$fecha_hasta'
				and s.idsucursal= $idsucursal and v.tipo_venta='Credito' and v.estado='A'
				order by v.fecha desc
				";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarVentasCliente($idsucursal, $idcliente, $fecha_desde, $fecha_hasta){
			global $conexion;
			$sql = "select v.fecha,s.razon_social as sucursal,
				concat(e.apellidos,' ',e.nombre) as empleado,
				pe.nombre as cliente,v.tipo_comprobante as comprobante,
				v.serie_comprobante as serie,v.num_comprobante as numero,
				v.impuesto,
				format((v.total-(v.impuesto*v.total/(100+v.impuesto))),2) as subtotal,
				format((v.impuesto*v.total/(100+v.impuesto)),2) as totalimpuesto,
				v.total
				from venta v inner join pedido p on v.idpedido=p.idpedido
				inner join sucursal s on p.idsucursal=s.idsucursal
				inner join usuario u on p.idusuario=u.idusuario
				inner join empleado e on u.idempleado=e.idempleado
				inner join persona pe on p.idcliente=pe.idpersona
				where v.fecha>='$fecha_desde' and v.fecha<='$fecha_hasta'
				and pe.idpersona= $idcliente and s.idsucursal= $idsucursal and v.estado='A'
				order by v.fecha desc
				";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarVentasEmpleado($idsucursal, $idempleado, $tipoempleado, $fecha_desde, $fecha_hasta){
			global $conexion;
			$sql = "select v.fecha,s.razon_social as sucursal,
				concat(e.apellidos,' ',e.nombre) as empleado,
				pe.nombre as cliente,v.tipo_comprobante as comprobante,
				v.serie_comprobante as serie,v.num_comprobante as numero,
				v.impuesto,
				format((v.total-(v.impuesto*v.total/(100+v.impuesto))),2) as subtotal,
				format((v.impuesto*v.total/(100+v.impuesto)),2) as totalimpuesto,
				v.total
				from venta v inner join pedido p on v.idpedido=p.idpedido
				inner join sucursal s on p.idsucursal=s.idsucursal
				inner join usuario u on p.idusuario=u.idusuario
				inner join empleado e on u.idempleado=e.idempleado
				inner join persona pe on p.idcliente=pe.idpersona
				where v.fecha>='$fecha_desde' and v.fecha<='$fecha_hasta' ";
			if($tipoempleado != 'Administrador'){
				$sql.="and e.idempleado= $idempleado ";
			}
			$sql.="and s.idsucursal= $idsucursal and v.estado='A'
				order by v.fecha desc;
				";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarVentasEmpleadoDet($idsucursal, $idempleado, $tipoempleado, $fecha_desde, $fecha_hasta){
			global $conexion;
			$sql = "select v.fecha,s.razon_social as sucursal,
				concat(e.apellidos,' ',e.nombre) as empleado,
				pe.nombre as cliente,v.tipo_comprobante as comprobante,
				v.serie_comprobante as serie,v.num_comprobante as numero,
				v.impuesto, c.nombre as categoria,
				a.nombre as articulo,di.codigo as codigo,di.serie as serie_art,
				dp.cantidad,dp.precio_venta,dp.descuento,
				(dp.cantidad*(dp.precio_venta-dp.descuento))as total
				from detalle_pedido dp inner join detalle_ingreso di on dp.iddetalle_ingreso=di.iddetalle_ingreso
				inner join articulo a on di.idarticulo=a.idarticulo
				inner join pedido p on dp.idpedido=p.idpedido
				inner join venta v on v.idpedido=p.idpedido
				inner join sucursal s on p.idsucursal=s.idsucursal
				inner join usuario u on p.idusuario=u.idusuario
				inner join empleado e on u.idempleado=e.idempleado
				inner join persona pe on p.idcliente=pe.idpersona
				inner join categoria c on a.idcategoria=c.idcategoria
				where v.fecha>='$fecha_desde' and v.fecha<='$fecha_hasta'
				and s.idsucursal= $idsucursal and v.estado='A' ";
				if($tipoempleado != 'Administrador'){
					$sql.= "and e.idempleado= $idempleado ";
				}
			$sql.= "order by c.nombre asc ";
			$query = $conexion->query($sql);
			return $query;
		}

	}
