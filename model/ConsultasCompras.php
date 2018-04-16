<?php
	require "Conexion.php";

	class ConsultasCompras{
	
		
		public function __construct(){

		}

		public function ListarKardexValorizado($idsucursal){
			global $conexion;
			$sql = "select s.razon_social as sucursal,a.nombre as articulo,
				c.nombre as categoria,
				u.nombre as unidad,
				sum(di.stock_ingreso) as totalingreso,
				sum(di.stock_ingreso*di.precio_compra) as valorizadoingreso,
				sum(di.stock_actual) as totalstock,
				sum(di.stock_actual*di.precio_compra) as valorizadostock,
				sum(di.stock_ingreso-di.stock_actual) as totalventa,
				sum((di.stock_ingreso-di.stock_actual)*di.precio_ventapublico) as valorizadoventa,
				sum((di.precio_ventapublico-di.precio_compra)*di.stock_ingreso) as utilidadvalorizada
				from articulo a inner join detalle_ingreso di
				on di.idarticulo=a.idarticulo
				inner join ingreso i on di.idingreso=i.idingreso
				inner join sucursal s on i.idsucursal=s.idsucursal
				inner join categoria c on a.idcategoria=c.idcategoria
				inner join unidad_medida u on a.idunidad_medida=u.idunidad_medida
				where s.idsucursal='$idsucursal'  and i.estado='A'
				group by a.nombre,c.nombre,u.nombre
				order by a.nombre asc
				";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarStockArticulos($idsucursal){
			global $conexion;
			$sql = "select s.razon_social as sucursal,a.nombre as articulo,
				c.nombre as categoria,di.codigo,di.serie,
				u.nombre as unidad,
				sum(di.stock_ingreso) as totalingreso,
				sum(di.stock_ingreso*di.precio_compra) as valorizadoingreso,
				sum(di.stock_actual) as totalstock,
				sum(di.stock_actual*di.precio_compra) as valorizadostock,
				sum(di.stock_ingreso-di.stock_actual) as totalventa,
				sum((di.stock_ingreso-di.stock_actual)*di.precio_ventapublico) as valorizadoventa,
				sum((di.precio_ventapublico-di.precio_compra)*di.stock_ingreso) as utilidadvalorizada
				from articulo a inner join detalle_ingreso di on di.idarticulo=a.idarticulo
				inner join ingreso i on di.idingreso=i.idingreso
				inner join sucursal s on i.idsucursal=s.idsucursal
				inner join categoria c on a.idcategoria=c.idcategoria
				inner join unidad_medida u on a.idunidad_medida=u.idunidad_medida
				where di.stock_actual>'0' and s.idsucursal=$idsucursal and i.estado='A'
				group by a.nombre,c.nombre,u.nombre,di.serie,di.codigo
				order by a.nombre asc
				";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarComprasFechas($idsucursal, $fecha_desde, $fecha_hasta){
			global $conexion;
			$sql = "select i.idingreso, i.fecha,s.razon_social as sucursal,
				concat(e.apellidos,' ',e.nombre) as empleado,
				p.nombre as proveedor,i.tipo_comprobante as comprobante,
				i.serie_comprobante as serie,i.num_comprobante as numero,
				i.impuesto,
				format((i.total-(i.impuesto*i.total/(100+i.impuesto))),2) as subtotal,
				format((i.impuesto*i.total/(100+i.impuesto)),2) as totalimpuesto,
				i.total
				from ingreso i inner join sucursal s on i.idsucursal=s.idsucursal
				inner join usuario u on i.idusuario=u.idusuario
				inner join empleado e on u.idempleado=e.idempleado
				inner join persona p on i.idproveedor=p.idpersona
				where i.fecha>='$fecha_desde' and i.fecha<='$fecha_hasta'
				and s.idsucursal= $idsucursal  and i.estado='A'
				order by i.fecha desc
				";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarComprasDetalladas($idsucursal, $fecha_desde, $fecha_hasta){
			global $conexion;
			$sql = "select i.fecha,s.razon_social as sucursal,
				concat(e.apellidos,' ',e.nombre) as empleado,
				p.nombre as proveedor,i.tipo_comprobante as comprobante,
				i.serie_comprobante as serie,i.num_comprobante as numero,
				i.impuesto,
				a.nombre as articulo,di.codigo,di.serie as serie_art,di.stock_ingreso,
				di.stock_actual,
				(di.stock_ingreso-di.stock_actual)as stock_vendido,
				di.precio_compra,di.precio_ventapublico,
				di.precio_ventadistribuidor
				from detalle_ingreso di inner join articulo a
				on di.idarticulo=a.idarticulo
				inner join ingreso i on di.idingreso=i.idingreso
				inner join sucursal s on i.idsucursal=s.idsucursal
				inner join usuario u on i.idusuario=u.idusuario
				inner join empleado e on u.idempleado=e.idempleado
				inner join persona p on i.idproveedor=p.idpersona
				where i.fecha>='$fecha_desde' and i.fecha<='$fecha_hasta'
				and s.idsucursal= $idsucursal and i.estado='A'
				order by i.fecha desc
				";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarComprasProveedor($idsucursal, $idproveedor, $fecha_desde, $fecha_hasta){
			global $conexion;
			$sql = "select i.fecha,s.razon_social as sucursal,
				concat(e.apellidos,' ',e.nombre) as empleado,
				p.nombre as proveedor,i.tipo_comprobante as comprobante,
				i.serie_comprobante as serie,i.num_comprobante as numero,
				i.impuesto,
				format((i.total-(i.impuesto*i.total/(100+i.impuesto))),2) as subtotal,
				format((i.impuesto*i.total/(100+i.impuesto)),2) as totalimpuesto,
				i.total
				from ingreso i inner join sucursal s on i.idsucursal=s.idsucursal
				inner join usuario u on i.idusuario=u.idusuario
				inner join empleado e on u.idempleado=e.idempleado
				inner join persona p on i.idproveedor=p.idpersona
				where i.fecha>='$fecha_desde' and i.fecha<='$fecha_hasta' and i.estado='A'
				and p.idpersona= $idproveedor and s.idsucursal=$idsucursal
				order by p.nombre asc
				";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarComprasDetProveedor($idsucursal, $idproveedor, $fecha_desde, $fecha_hasta){
			global $conexion;
			$sql = "select i.fecha,s.razon_social as sucursal,
					concat(e.apellidos,' ',e.nombre) as empleado,
					p.nombre as proveedor,i.tipo_comprobante as comprobante,
					i.serie_comprobante as serie,i.num_comprobante as numero,
					i.impuesto,
					a.nombre as articulo,di.codigo,di.serie,di.stock_ingreso,
					di.stock_actual,
					(di.stock_ingreso-di.stock_actual)as stock_vendido,
					di.precio_compra,di.precio_ventapublico,
					di.precio_ventadistribuidor
					from detalle_ingreso di inner join articulo a
					on di.idarticulo=a.idarticulo
					inner join ingreso i on di.idingreso=i.idingreso
					inner join sucursal s on i.idsucursal=s.idsucursal
					inner join usuario u on i.idusuario=u.idusuario
					inner join empleado e on u.idempleado=e.idempleado
					inner join persona p on i.idproveedor=p.idpersona
					where i.fecha>='$fecha_desde' and i.fecha<='$fecha_hasta'
				and p.idpersona=$idproveedor and s.idsucursal= $idsucursal and i.estado='A'
					order by p.nombre asc
				";
			$query = $conexion->query($sql);
			return $query;
		}


	}
