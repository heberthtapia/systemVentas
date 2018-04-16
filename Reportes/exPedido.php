<?php
// (c) Xavier Nicolay
// Exemple de génération de devis/facture PDF

require('Pedido.php');

require_once "../model/Pedido.php";

$objPedido = new Pedido();

$query_cli = $objPedido->GetClienteSucursalPedido($_GET["id"]);

        $reg_cli = $query_cli->fetch_object();
$archivo = $reg_cli->logo; 
$trozos = explode(".", $archivo); 
$extension = end($trozos); 

$pdf = new PDF_Invoice( 'P', 'mm', 'A4' );
$pdf->AddPage();
$pdf->addSociete( $reg_cli->razon_social,
                  "$reg_cli->num_sucursal\n" .
                  "Dirección: $reg_cli->direccion\n".
                  "Teléfono: $reg_cli->telefono_suc\n" .
                  "email : $reg_cli->email_suc ","../$reg_cli->logo","$extension");
$pdf->fact_dev( "$reg_cli->tipo_pedido ", "$reg_cli->numero" );
$pdf->temporaire( "" );
$pdf->addDate( $reg_cli->fecha);
//$pdf->addClient("CL01");
//$pdf->addPageNumber("1");

$pdf->addClientAdresse($reg_cli->nombre,"Domicilio: ".$reg_cli->direccion_calle." - ".$reg_cli->direccion_departamento,$reg_cli->doc.": ".$reg_cli->num_documento,"Email: ".$reg_cli->email,"Telefono: ".$reg_cli->telefono);
//$pdf->addReglement("Soluciones Innovadoras Perú S.A.C.");
//$pdf->addEcheance("RUC","2147715777");
//$pdf->addNumTVA("Chongoyape, José Gálvez 1368");
//$pdf->addReference("Devis ... du ....");
$cols=array( "CODIGO"    => 23,
             "DESCRIPCION"  => 78,
             "CANTIDAD"     => 22,
             "P.U."      => 25,
             "DSCTO" => 20,
             "SUBTOTAL"          => 22 );
$pdf->addCols( $cols);
$cols=array( "CODIGO"    => "L",
             "DESCRIPCION"  => "L",
             "CANTIDAD"     => "C",
             "P.U."      => "R",
             "DSCTO" => "R",
             "SUBTOTAL"          => "C" );
$pdf->addLineFormat( $cols);
$pdf->addLineFormat($cols);

$y    = 89;

$query_ped = $objPedido->ImprimirDetallePedido($_GET["id"]);

        while ($reg = $query_ped->fetch_object()) {

            $line = array( "CODIGO"    => "'$reg->codigo'",
                           "DESCRIPCION"  => "$reg->articulo Serie: $reg->serie",
                           "CANTIDAD"     => "$reg->cantidad",
                           "P.U."      => "$reg->precio_venta",
                           "DSCTO" => "$reg->descuento",
                           "SUBTOTAL"          => "$reg->sub_total");
            $size = $pdf->addLine( $y, $line );
            $y   += $size + 2;
        }

$query_total = $objPedido->TotalPedido($_GET["id"]);

$reg_total = $query_total->fetch_object();

require_once "../ajax/Letras.php";

 $V=new EnLetras(); 
 $con_letra=strtoupper($V->ValorEnLetras($reg_total->Total,"BOLIVIANOS")); 
//$pdf->addCadreTVAs("---TRES MILLONES CUATROCIENTOS CINCUENTA Y UN MIL DOSCIENTOS CUARENTA PESOS 00/100 M.N.");
$pdf->addCadreTVAs("---".$con_letra);


require_once "../model/Configuracion.php";

$objConfiguracion = new Configuracion();


$query_global = $objConfiguracion->Listar();

$reg_igv = $query_global->fetch_object();

$pdf->addTVAs( $reg_igv->porcentaje_impuesto, $reg_total->Total,"$reg_igv->simbolo_moneda ");
$pdf->addCadreEurosFrancs("$reg_igv->nombre_impuesto"." $reg_igv->porcentaje_impuesto%");
$pdf->Output('Reporte de Pedido','I');
?>
