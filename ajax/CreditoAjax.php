<?php

	session_start();

	require_once "../model/Credito.php";

	$objCredito = new Credito();

	switch ($_GET["op"]) {

		case 'SaveOrUpdate':			

			$idventa = $_POST["txtIdVenta"]; // Llamamos al input txtNombre
			$fecha_pago = "--";
			$total_pago = $_POST["txtTotalPago"];

				if(empty($_POST["txtIdCredito"])){
					
					if($objCredito->Registrar($idventa,$fecha_pago, $total_pago)){
						echo "Credito registrado";
					}else{
						echo "Credito no ha podido ser registado.";
					}
				}else{
					
					$idCredito = $_POST["txtIdCredito"];
					if($objCredito->Modificar($idCredito, $idventa,$fecha_pago, $total_pago)){
						echo "Credito ha sido actualizada";
					}else{
						echo "Credito no ha podido ser actualizada.";
					}
				}
			
			break;

		case "GetIdVenta":
			$query_get = $objCredito->GetIdVenta();
			$reg = $query_get->fetch_object();
			echo $reg->id;
			break;

		case "delete":			
			
			$id = $_POST["id"];// Llamamos a la variable id del js que mandamos por $.post (Categoria.js (Linea 62))
			$result = $objCredito->Eliminar($id);
			if ($result) {
				echo "Configuración Credito eliminada Exitosamente";
			} else {
				echo "La configuración Credito no fue Eliminada";
			}
			break;
		
		case "list":
			$query_Tipo = $objCredito->Listar($_SESSION["idsucursal"]);
			$data= Array();
            $i = 1;
     		while ($reg = $query_Tipo->fetch_object()) {
     			$query_total = $objCredito->GetMontoTotalCredito($reg->idventa);

                $reg_total = $query_total->fetch_object();

	             $data[] = array("0"=>$i,
                    "1"=>$reg->tipo_venta,
                    "2"=>$reg->tipo_comprobante,
                    "3"=>$reg->serie_comprobante,
                    "4"=>$reg->num_comprobante,
                    "5"=>$reg->fecha,
                    "6"=>$reg->impuesto,
                    "7"=>$reg->total,
                    "8"=>$reg_total->total_pago,
                    "9"=>$reg->total-$reg_total->total_pago,
                    "10"=>($reg->total-$reg_total->total_pago>0)?'<button class="btn btn-success" data-toggle="tooltip" title="Agregar Credito" onclick="AgregarCredito('.$reg->idventa.',\''.$reg->total.'\')"><i class="fa fa-usd"></i> </button>':'<button class="btn btn-warning" data-toggle="tooltip" title="Total Pagado, Puede ver el detalle de credito haciendo click aqui" onclick="AgregarCredito('.$reg->idventa.',\''.$reg->total.'\')"><i class="fa fa-eye"></i> </button>'
                                        
                    );
                $i++;
            }
			$results = array(
            "sEcho" => 1,
        	"iTotalRecords" => count($data),
        	"iTotalDisplayRecords" => count($data),
            "aaData"=>$data);
			echo json_encode($results);
            
			break;

		case "listDeudas":
			$query_Tipo = $objCredito->ListarDeuda($_SESSION["idsucursal"]);
			$data= Array();
            $i = 1;
     		while ($reg = $query_Tipo->fetch_object()) {
     			$query_total = $objCredito->GetMontoTotalCreditoMayorCero($reg->idventa);

                $reg_total = $query_total->fetch_object();

	             $data[] = array("0"=>$i,
                    "1"=>$reg->tipo_venta,
                    "2"=>$reg->tipo_comprobante,
                    "3"=>$reg->serie_comprobante,
                    "4"=>$reg->num_comprobante,
                    "5"=>$reg->fecha,
                    "6"=>$reg->impuesto,
                    "7"=>$reg->total,
                    "8"=>$reg_total->total_pago,
                    "9"=>$reg->total-$reg_total->total_pago,
                    "10"=>($reg->total-$reg_total->total_pago>0)?'<button class="btn btn-success" data-toggle="tooltip" title="Agregar Credito" onclick="AgregarCredito('.$reg->idventa.',\''.$reg->total.'\')"><i class="fa fa-usd"></i> </button>':'<button class="btn btn-warning" data-toggle="tooltip" title="Total Pagado, Puede ver el detalle de credito haciendo click aqui" onclick="AgregarCredito('.$reg->idventa.',\''.$reg->total.'\')"><i class="fa fa-eye"></i> </button>'
                                        
                    );
                $i++;
            }
			$results = array(
            "sEcho" => 1,
        	"iTotalRecords" => count($data),
        	"iTotalDisplayRecords" => count($data),
            "aaData"=>$data);
			echo json_encode($results);
            
			break;

		case "VerDetCredito":

			$idVenta = $_POST["idVenta"];

			$query_Tipo = $objCredito->VerDetalleCredito($idVenta);

            $i = 1;
     		while ($reg = $query_Tipo->fetch_object()) {
	             echo '
	             	<tr>
	             		<td>'.$reg->fecha_pago.'</td>
	             		<td>'.$reg->total_pago.'</td>
	             ';
                $i++;
            }
            
			break;

		case "MontoTotalPagados":

			$idVenta = $_REQUEST["idVenta"];

			$query_Tipo = $objCredito->MontoTotalPagados($idVenta);

            $reg = $query_Tipo->fetch_object();
     		
     		echo json_encode($reg);
            
			break;

		case "GetImpuesto":
			$query_Tipo = $objCredito->Listar();

            $reg = $query_Tipo->fetch_object();
     		
     		echo json_encode($reg);
            
			break;

	}
	