<?php
$id=$_GET["id"];

require_once "../model/Pedido.php";
$objPedido = new Pedido();

$query_cli = $objPedido->GetComprobanteTipo($_GET["id"]);
$reg_cli = $query_cli->fetch_object();

If($reg_cli->tipo_comprobante=="Factura" || $reg_cli->tipo_comprobante=="FACTURA" || $reg_cli->tipo_comprobante=="FACTURAS" || $reg_cli->tipo_comprobante=="Facturas" )
{
  header('Location: exFactura.php?id='.$id);  
}

ElseIf($reg_cli->tipo_comprobante=="Ticket" || $reg_cli->tipo_comprobante=="TICKET" || $reg_cli->tipo_comprobante=="TICKETS" || $reg_cli->tipo_comprobante=="Tickets" )
{
  header('Location: exTicket.php?id='.$id);  
}

ElseIf($reg_cli->tipo_comprobante=="Boleta" || $reg_cli->tipo_comprobante=="BOLETA" || $reg_cli->tipo_comprobante=="BOLETAS" || $reg_cli->tipo_comprobante=="Boleta" )
{
  header('Location: exBoleta.php?id='.$id);  
}
Else{
  header('Location: exGuia.php?id='.$id);
}

?>