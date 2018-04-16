<?php

	session_start();

	require_once "../model/Venta.php";

	$objVenta = new Venta();

	switch ($_GET["op"]) {

		case 'SaveOrUpdate':			

			$idpedido = $_POST["idPedido"];
			$idusuario = $_POST["idUsuario"];
			$tipo_venta = $_POST["tipo_venta"];
			$iddetalle_doc_suc = $_POST["iddetalle_doc_suc"];
			$tipo_comprobante = $_POST["tipo_comprobante"];
			$serie_comprobante = $_POST["serie_vent"];
			$num_comprobante = $_POST["num_vent"];
			$impuesto = $_POST["impuesto"];
			$total = $_POST["total_vent"];
			$estado = "A";	

			$entero = intval($num_comprobante);

			$cant_letra = strlen($entero);

			$parte_izquierda = substr($num_comprobante, 0, -$cant_letra);
			
			$suma = $entero + 1;

			$numero = $parte_izquierda."".$suma;	

				if(empty($_POST["txtIdVenta"])){
					
					if($objVenta->Registrar($idpedido,$idusuario,$tipo_venta,$tipo_comprobante,$serie_comprobante,$num_comprobante,$impuesto,$total,$estado, $numero, $iddetalle_doc_suc, $_POST["detalle"])){
						
							echo "Venta Registrada correctamente.";
					}else{
							echo "Venta no ha podido ser registado.";
					}
						
				}else{
					
					$idVenta = $_POST["txtIdVenta"];
					if($objVenta->Modificar($idventa,$idpedido, $idusuario,$tipo_venta,$tipo_comprobante,$serie_comprobante,$num_comprobante,$impuesto,$total,$estado)){
						echo "La información del Venta ha sido actualizada.";
					}else{
						echo "La información del Venta no ha podido ser actualizada.";
					}
				}

			break;

		case "delete":			
			
			$id = $_POST["id"];// Llamamos a la variable id del js que mandamos por $.post (Categoria.js (Linea 62))
			$result = $objVenta->Eliminar($id);
			if ($result) {
				echo "Eliminado Exitosamente";
			} else {
				echo "No fue Eliminado";
			}
			break;
		
		case "list":
			$query_Tipo = $objVenta->Listar();
            $data = Array();
        	$i = 1;
     		while ($reg = $query_Tipo->fetch_object()) {
	             echo '<tr>
		                <td>'.$i.'</td>
		                <td>'.$reg->apellidos.'&nbsp;'.$reg->nombre.'</td>
		                <td>'.$reg->tipo_documento.'&nbsp;'.$reg->num_documento.'</td>
		                <td>'.$reg->email.'</td>
		                <td>'.$reg->telefono.'</td>
		                <td>'.$reg->login.'</td>
		                <td><img width=100px height=100px src="./'.$reg->foto.'" /></td>
		                <td><button class="btn btn-warning" onclick="cargarDataVenta('.$reg->idVenta.',\''.$reg->apellidos.'\',\''.$reg->nombre.'\',\''.$reg->tipo_documento.'\',\''.$reg->num_documento.'\',\''.$reg->direccion.'\',\''.$reg->telefono.'\',\''.$reg->email.'\',\''.$reg->fecha_nacimiento.'\',\''.$reg->foto.'\',\''.$reg->login.'\',\''.$reg->clave.'\',\''.$reg->estado.'\')"><i class="fa fa-pencil"></i> </button></td>
                        <td><button class="btn btn-danger" onclick="eliminarVenta('.$reg->idVenta.')"><i class="fa fa-trash"></i> </button></td>
	                   </tr>';
	             $i++; 
            }
            
			break;
		case "listTipoPedidoPedido":	
			require_once "../model/Pedido.php";
			$objPed = new Pedido();

			$query_Tipo = $objPed->ListarTipoPedidoPedido($_SESSION["idsucursal"]);
			$data = Array();
            $i = 1;
     		while ($reg = $query_Tipo->fetch_object()) {
     			$regTotal = $objPed->GetTotal($reg->idpedido);
     			$fetch = $regTotal->fetch_object();
     			$data[] = array(
     				"0"=>$i,
                    "1"=>$reg->Cliente,
                    "2"=>$reg->tipo_pedido,
                    "3"=>$reg->fecha,
                    "4"=>'<button class="btn btn-success" data-toggle="tooltip" title="Ver Detalle" onclick="cargarDataPedido('.$reg->idpedido.',\''.$reg->tipo_pedido.'\',\''.$reg->numero.'\',\''.$reg->Cliente.'\',\''.$fetch->total.'\')" ><i class="fa fa-eye"></i> </button>&nbsp'.
                    '<button class="btn btn-success" onclick="pasarIdPedido('.$reg->idpedido.',\''.$fetch->total.'\',\''.$reg->email.'\')"><i class="fa fa-shopping-cart"></i> </button>&nbsp'.
                    '<a href="./Reportes/exPedido.php?id='.$reg->idpedido.'" class="btn btn-primary" data-toggle="tooltip" title="Imprimir" target="blanck" ><i class="fa fa-file-text"></i> </a>&nbsp;'.
                    '<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar Pedido" onclick="eliminarPedido('.$reg->idpedido.')" ><i class="fa fa-trash"></i> </button>&nbsp'
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
			
		case "listTipo_DocumentoPersona":
		        require_once "../model/Tipo_Documento.php";

		        $objTipo_Documento = new Tipo_Documento();

		        $query_tipo_Documento = $objTipo_Documento->VerTipo_Documento_Persona();

		        while ($reg = $query_tipo_Documento->fetch_object()) {
		            echo '<option value=' . $reg->nombre . '>' . $reg->nombre . '</option>';
		        }

		    break;

		 case "GetTipoDocSerieNum":

            $nombre = $_REQUEST["nombre"];
            $idsucursal = $_REQUEST["idsucursal"];

            $query_Categoria = $objVenta->GetTipoDocSerieNum($nombre,$idsucursal);

            $reg = $query_Categoria->fetch_object();

            echo json_encode($reg);

            break;

        case "EnviarCorreo":
        	require_once "../PHPMailer/class.phpmailer.php";

			$server = $_SERVER["HTTP_HOST"];

			$idPedido = $_POST["idPedido"];
			$result = $_POST["result"];
			$sucursal = $_SESSION["sucursal"];
			$email = $_SESSION["email"];
			$mail = new PHPMailer;
			$mail->Host = "$server";
			$mail->From = "$email";
			$mail->FromName = "$sucursal - Administracion";
			$mail->Subject = "$sucursal - Detalle de compra";
			$mail->addAddress("$result", "Alex");
			$mail->MsgHTML("Puede ver el detalle de su compra haciendo click <a href='$server/Reportes/exVenta.php?id=".$idPedido."'> Aqui</a>");
  
			if ($mail->Send()) {
			 	echo "Enviado con éxito";
			} else {
				echo "Venta Registrada correctamente. No se pudo realizar el envio";
			}

			break;

	}
	