<?php

	session_start();

	require_once "../model/ConsultasVentas.php";

	$objCategoria = new ConsultasVentas();

	switch ($_GET["op"]) {

          case "listVentasFechas":

               $fecha_desde = $_REQUEST["fecha_desde"];
               $fecha_hasta = $_REQUEST["fecha_hasta"];
               $idsucursal = $_REQUEST["idsucursal"];
               $data = Array();
               $query_Tipo = $objCategoria->ListarVentasFechas($idsucursal, $fecha_desde, $fecha_hasta);

               while ($reg = $query_Tipo->fetch_object()) {

                    $data[] = array(
                         "0"=>$reg->fecha,
                         "1"=>$reg->sucursal,
                         "2"=>$reg->empleado,
                         "3"=>$reg->cliente,
                         "4"=>$reg->comprobante,
                         "5"=>$reg->serie,
                         "6"=>$reg->numero,
                         "7"=>$reg->impuesto,
                         "8"=>$reg->subtotal,
                         "9"=>$reg->totalimpuesto,
                         "10"=>$reg->total,
                         "11"=>'<button class="btn btn-success" data-toggle="tooltip" title="Ver Detalle" onclick="cargarDataPedido('.$reg->idpedido.',\''.$reg->tipo_pedido.'\',\''.$reg->numero.'\',\''.$reg->cliente.'\',\''.$reg->total.'\')" ><i class="fa fa-eye"></i> </button>&nbsp'.
                    '<a href="./Reportes/exVenta.php?id='.$reg->idpedido.'" class="btn btn-primary" data-toggle="tooltip" title="Imprimir" target="blanck" ><i class="fa fa-file-text"></i> </a>&nbsp;'
                    );
               }
               $results = array(
               "sEcho" => 1,
                "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);

               break;

          case "listVentasDetalladas":

               $fecha_desde = $_REQUEST["fecha_desde"];
               $fecha_hasta = $_REQUEST["fecha_hasta"];
               $idsucursal = $_REQUEST["idsucursal"];
               $data = Array();
               $query_Tipo = $objCategoria->ListarVentasDetalladas($idsucursal, $fecha_desde, $fecha_hasta);

               while ($reg = $query_Tipo->fetch_object()) {

                    $data[] = array(
                         "0"=>$reg->fecha,
                         "1"=>$reg->sucursal,
                         "2"=>$reg->empleado,
                         "3"=>$reg->cliente,
                         "4"=>$reg->comprobante,
                         "5"=>$reg->serie,
                         "6"=>$reg->numero,
                         "7"=>$reg->impuesto,
                         "8"=>$reg->articulo,
                         "9"=>$reg->codigo,
                         "10"=>$reg->serie_art,
                         "11"=>$reg->cantidad,
                         "12"=>$reg->precio_venta,
                         "13"=>$reg->descuento,
                         "14"=>$reg->total
                    );
               }

               $results = array(
                "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);

               break;

          case "listVentasDetalladasArticulo":

               $fecha_desde = $_REQUEST["fecha_desde"];
               $idsucursal  = $_REQUEST["idsucursal"];
               $categoria   = $_REQUEST["categoria"];
               $grupo       = $_REQUEST["grupo"];
               $data        = Array();
               $query_Tipo  = $objCategoria->ListarVentasDetalladasArticulo($idsucursal, $categoria, $fecha_desde);
               $j           = 0;
               $totalEmp = Array();
               $totalPre = Array();

               while ($reg = $query_Tipo->fetch_object()) {

                    $total = 0;
                    $i     = 0;
                    $emp   = Array();

                    $sql   = "SELECT e.idempleado, e.apellidos, e.nombre ";
                    $sql   .= "FROM empleado AS e, usuario AS u ";
                    $sql   .= "WHERE e.idempleado = u.idempleado AND u.tipo_usuario = 'Vendedor' ";
                    if($grupo != 0){
                         $sql   .= "AND u.num_grupo = '".$grupo."' ";
                    }
                    $sql   .= "ORDER BY e.idempleado";

                    $Query = $conexion->query($sql);

                    $num   = mysqli_num_rows($Query);

                    while ($row = $Query->fetch_object()) {

                         $sqlQuery = "SELECT SUM(dp.cantidad) AS cantidad, di.precio_ventadistribuidor AS precio ";
                         $sqlQuery .= "FROM empleado AS e, usuario AS u, venta AS v, pedido AS p, detalle_pedido AS dp, detalle_ingreso AS di, articulo AS a ";
                         $sqlQuery .= "WHERE e.idempleado = u.idempleado ";
                         $sqlQuery .= "AND e.idempleado = $row->idempleado ";
                         //$sqlQuery .= "AND v.idusuario = u.idusuario ";
                         $sqlQuery .= "AND v.idpedido = p.idpedido ";
                         $sqlQuery .= "AND p.idusuario = u.idusuario ";
                         $sqlQuery .= "AND p.idpedido = dp.idpedido ";
                         $sqlQuery .= "AND dp.iddetalle_ingreso = di.iddetalle_ingreso ";
                         $sqlQuery .= "AND di.idarticulo = a.idarticulo ";
                         $sqlQuery .= "AND v.estado = 'A' ";
                         $sqlQuery .= "AND p.estado = 'A' ";
                         $sqlQuery .= "AND a.idarticulo = $reg->idarticulo ";
                         if( $categoria != 0 ){
                              $sqlQuery .= "AND a.idcategoria = $categoria ";
                         }
                         $sqlQuery .= "AND v.fecha = '".$fecha_desde."' ";
                         if($grupo != 0){
                             $sqlQuery .= "AND u.num_grupo = '".$grupo."' ";
                         }
                         //$sqlQuery .= "AND v.fecha >= '2018-04-01' AND v.fecha <= '2018-04-30' ";
                         //echo $sqlQuery;

                         $con = $conexion->query($sqlQuery);
                         $fila = $con->fetch_object();

                         if($fila->cantidad == NULL){
                              $fila->cantidad = 0;
                         }
                         $total = $total + $fila->cantidad;
                         $emp[] = array(
                              $fila->cantidad,
                         );
                         $i++;
                         $totalEmp[$i] = $totalEmp[$i] + $fila->cantidad;
                         $totalPre[$i] = $totalPre[$i] + ( $fila->cantidad * $fila->precio );
                         //echo '--->'.$fila->precio;
                    }

                    $c = 0;
                    $data[$j][$c] = $reg->nombre;

                    for($i=1; $i <= $num; $i++){
                         $data[$j][$i] = $emp[$c];
                          $c++;
                    }

                    $data[$j][$c+1] = $total;
                    $montoTotal = $montoTotal + $total;
                    $j++;
                    $k = $j;
               }

               $data[$k][0] = '<strong>z. TOTAL VENTAS</strong>';

               for($i=1; $i <= $num; $i++){
                    $data[$k][$i] = '<strong>'.$totalEmp[$i].'</strong>';
               }
               $data[$k][$num+1] = '<strong>'.$montoTotal.'</strong>';

               /*****************************/

               $data[$k+1][0] = '<strong>z. TOTAL Bs.-</strong>';

               for($i=1; $i <= $num; $i++){
                    $data[$k+1][$i] = '<strong>'.$totalPre[$i].'</strong>';
               }
               $data[$k+1][$num+1] = '';

               //print_r($totalPre);

               $results = array(
                    "sEcho"                => 1,
                    "iTotalRecords"        => count($data),
                    "iTotalDisplayRecords" => count($data),
                    "aaData"               =>$data
               );
               echo json_encode($results);

               break;

          case "listVentasPendientes":

               $fecha_desde = $_REQUEST["fecha_desde"];
               $fecha_hasta = $_REQUEST["fecha_hasta"];
               $idsucursal = $_REQUEST["idsucursal"];
               $data = Array();
               $query_Tipo = $objCategoria->ListarVentasPendientes($idsucursal, $fecha_desde, $fecha_hasta);

               while ($reg = $query_Tipo->fetch_object()) {

                    $data[] = array(
                         "0"=>$reg->fecha,
                         "1"=>$reg->sucursal,
                         "2"=>$reg->empleado,
                         "3"=>$reg->cliente,
                         "4"=>$reg->comprobante,
                         "5"=>$reg->serie,
                         "6"=>$reg->numero,
                         "7"=>$reg->impuesto,
                         "8"=>$reg->subtotal,
                         "9"=>$reg->totalimpuesto,
                         "10"=>$reg->totalpagar,
                         "11"=>$reg->totalpagado,
                         "12"=>$reg->totaldeuda
                    );
               }

               $results = array(
                "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);

               break;

          case "listVentasContado":

               $fecha_desde = $_REQUEST["fecha_desde"];
               $fecha_hasta = $_REQUEST["fecha_hasta"];
               $idsucursal = $_REQUEST["idsucursal"];
               $data = Array();
               $query_Tipo = $objCategoria->ListarVentasContado($idsucursal, $fecha_desde, $fecha_hasta);

               while ($reg = $query_Tipo->fetch_object()) {

                    $data[] = array(
                         "0"=>$reg->fecha,
                         "1"=>$reg->sucursal,
                         "2"=>$reg->empleado,
                         "3"=>$reg->cliente,
                         "4"=>$reg->comprobante,
                         "5"=>$reg->serie,
                         "6"=>$reg->numero,
                         "7"=>$reg->impuesto,
                         "8"=>$reg->subtotal,
                         "9"=>$reg->totalimpuesto,
                         "10"=>$reg->total
                    );
               }
               $results = array(
                "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);

               break;

          case "listVentasCredito":

               $fecha_desde = $_REQUEST["fecha_desde"];
               $fecha_hasta = $_REQUEST["fecha_hasta"];
               $idsucursal = $_REQUEST["idsucursal"];
               $data = Array();
               $query_Tipo = $objCategoria->ListarVentasCredito($idsucursal, $fecha_desde, $fecha_hasta);

               while ($reg = $query_Tipo->fetch_object()) {

                    $data[] = array(
                         "0"=>$reg->fecha,
                         "1"=>$reg->sucursal,
                         "2"=>$reg->empleado,
                         "3"=>$reg->cliente,
                         "4"=>$reg->comprobante,
                         "5"=>$reg->serie,
                         "6"=>$reg->numero,
                         "7"=>$reg->impuesto,
                         "8"=>$reg->subtotal,
                         "9"=>$reg->totalimpuesto,
                         "10"=>$reg->totalpagar,
                         "11"=>$reg->totalpagado,
                         "12"=>$reg->totaldeuda
                    );
               }

                $results = array(
                "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);

               break;

          case "listVentasCliente":

               $idCliente = $_REQUEST["idCliente"];
               $fecha_desde = $_REQUEST["fecha_desde"];
               $fecha_hasta = $_REQUEST["fecha_hasta"];
               $idsucursal = $_REQUEST["idsucursal"];
               $data= Array();
               $query_Tipo = $objCategoria->ListarVentasCliente($idsucursal, $idCliente, $fecha_desde, $fecha_hasta);

               while ($reg = $query_Tipo->fetch_object()) {

                    $data[] = array(
                         "0"=>$reg->fecha,
                         "1"=>$reg->sucursal,
                         "2"=>$reg->empleado,
                         "3"=>$reg->cliente,
                         "4"=>$reg->comprobante,
                         "5"=>$reg->serie,
                         "6"=>$reg->numero,
                         "7"=>$reg->impuesto,
                         "8"=>$reg->subtotal,
                         "9"=>$reg->totalimpuesto,
                         "10"=>$reg->total
                    );
               }

               $results = array(
                "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);

               break;

          case "listComprasDetProveedor":

               $idProveedor = $_REQUEST["idProveedor"];
               $fecha_desde = $_REQUEST["fecha_desde"];
               $fecha_hasta = $_REQUEST["fecha_hasta"];
               $idsucursal = $_REQUEST["idsucursal"];
               $data= Array();
               $query_Tipo = $objCategoria->ListarComprasDetProveedor($idsucursal, $idProveedor, $fecha_desde, $fecha_hasta);

               while ($reg = $query_Tipo->fetch_object()) {

                    $data[] = array(
                         "1"=>$reg->fecha,
                         "2"=>$reg->sucursal,
                         "3"=>$reg->empleado,
                         "4"=>$reg->proveedor,
                         "5"=>$reg->comprobante,
                         "6"=>$reg->serie,
                         "7"=>$reg->numero,
                         "8"=>$reg->impuesto,
                         "9"=>$reg->articulo,
                         "10"=>$reg->codigo,
                         "11"=>$reg->serie,
                         "12"=>$reg->stock_ingreso,
                         "13"=>$reg->stock_actual,
                         "14"=>$reg->stock_vendido,
                         "15"=>$reg->precio_compra,
                         "16"=>$reg->precio_ventapublico,
                         "17"=>$reg->precio_ventadistribuidor
                    );
               }

               $results = array(
                "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);

               break;

          case "listVentasEmpleado":

               $fecha_desde = $_REQUEST["fecha_desde"];
               $fecha_hasta = $_REQUEST["fecha_hasta"];
               $idsucursal = $_REQUEST["idsucursal"];
               $data= Array();
               $query_Tipo = $objCategoria->ListarVentasEmpleado($idsucursal, $_SESSION["idempleado"], $fecha_desde, $fecha_hasta);

               while ($reg = $query_Tipo->fetch_object()) {

                    $data[] = array(
                         "1"=>$reg->fecha,
                         "2"=>$reg->sucursal,
                         "3"=>$reg->empleado,
                         "4"=>$reg->cliente,
                         "5"=>$reg->comprobante,
                         "6"=>$reg->serie,
                         "7"=>$reg->numero,
                         "8"=>$reg->impuesto,
                         "9"=>$reg->subtotal,
                         "10"=>$reg->totalimpuesto,
                         "11"=>$reg->total
                   );
               }
               $results = array(
                "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);

               break;

          case "listVentasEmpleadoDet":

               $fecha_desde = $_REQUEST["fecha_desde"];
               $fecha_hasta = $_REQUEST["fecha_hasta"];
               $idsucursal = $_REQUEST["idsucursal"];
               $data= Array();
               $query_Tipo = $objCategoria->ListarVentasEmpleadoDet($idsucursal, $_SESSION["idempleado"], $fecha_desde, $fecha_hasta);

               while ($reg = $query_Tipo->fetch_object()) {

                    $data[] = array(
                         "1"=>$reg->fecha,
                         "2"=>$reg->sucursal,
                         "3"=>$reg->empleado,
                         "4"=>$reg->cliente,
                         "5"=>$reg->comprobante,
                         "6"=>$reg->serie,
                         "7"=>$reg->numero,
                         "8"=>$reg->impuesto,
                         "9"=>$reg->articulo,
                         "10"=>$reg->codigo,
                         "11"=>$reg->serie_art,
                         "12"=>$reg->cantidad,
                         "13"=>$reg->precio_venta,
                         "14"=>$reg->descuento,
                         "15"=>$reg->total
                    );
               }

               $results = array(
                "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);

               break;

	}
