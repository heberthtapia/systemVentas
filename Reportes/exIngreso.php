<?php
// (c) Xavier Nicolay
// Exemple de génération de devis/facture PDF

require('Ingreso.php');

session_start();

$lo = $_SESSION["logo"];

require_once "../model/Configuracion.php";

      $objConf = new Configuracion();

      $query_conf = $objConf->Listar();

      $regConf = $query_conf->fetch_object();

require_once "../model/Ingreso.php";

$obIngreso = new Ingreso();

$query_cli = $obIngreso->GetProveedorSucursalIngreso($_GET["id"]);

        $reg_cli = $query_cli->fetch_object();



$f = "";

      if ($_SESSION["superadmin"] == "S") {
        $f = $regConf->logo;
      } else {
        $f = $reg_cli->logo;
      }

      $archivo = $f; 
      $trozos = explode(".", $archivo); 
      $extension = end($trozos); 

$pdf = new PDF_Invoice( 'P', 'mm', 'A4' );
$pdf->AddPage();
$pdf->addSociete( $reg_cli->razon_social,
                  "$reg_cli->documento_sucursal: $reg_cli->num_sucursal\n" .
                  "Dirección:".utf8_decode( "$reg_cli->direccion")."\n".
                  "Teléfono:".utf8_decode(" $reg_cli->telefono_suc")."\n" .
                  "email : $reg_cli->email_suc ","../$f","$extension");
$pdf->fact_dev( "$reg_cli->tipo_comprobante ", "$reg_cli->serie_comprobante-$reg_cli->num_comprobante" );
$pdf->temporaire( "" );
$pdf->addDate( $reg_cli->fecha);
//$pdf->addClient("CL01");
//$pdf->addPageNumber("1");
$pdf->addClientAdresse("Razón Social: ".utf8_decode($reg_cli->nombre),"Domicilio: ".utf8_decode($reg_cli->direccion_calle)." - ".$reg_cli->direccion_departamento,$reg_cli->tipo_documento.": ".$reg_cli->num_documento,"Email: ".$reg_cli->email,"Telefono: ".$reg_cli->telefono);
//$pdf->addReglement("Soluciones Innovadoras Perú S.A.C.");
//$pdf->addEcheance("RUC","2147715777");
//$pdf->addNumTVA("Chongoyape, José Gálvez 1368");
//$pdf->addReference("Devis ... du ....");
$cols=array( "CODIGO"    => 23,
             "DESCRIPCION"  => 78,
             "CANTIDAD"     => 22,
             "P.COSTO"      => 25,
             "P.VENTA" => 20,
             "SUBTOTAL"          => 22 );
$pdf->addCols( $cols);
$cols=array( "CODIGO"    => "L",
             "DESCRIPCION"  => "L",
             "CANTIDAD"     => "C",
             "P.COSTO"      => "R",
             "P.VENTA" => "R",
             "SUBTOTAL"          => "C" );
$pdf->addLineFormat( $cols);
$pdf->addLineFormat($cols);

$y    = 89;

$query_ped = $obIngreso->GetDetalleArticulo($_GET["id"]);

        while ($reg = $query_ped->fetch_object()) {

          if ($reg->codigo != "") {
            $cod = $reg->codigo;
          } else {
            $cod = "-";
          }

            $line = array( "CODIGO"    => utf8_decode("$cod"),
                           "DESCRIPCION"  => utf8_decode("$reg->articulo Serie:$reg->serie"),
                           "CANTIDAD"     => "$reg->stock_ingreso",
                           "P.COSTO"      => "$reg->precio_compra",
                           "P.VENTA" => "$reg->precio_ventadistribuidor",
                           "SUBTOTAL"          => "$reg->sub_total");
            $size = $pdf->addLine( $y, $line );
            $y   += $size + 2;
        }

require_once "../ajax/Letras.php";

 $V=new EnLetras(); 
 $con_letra=strtoupper($V->ValorEnLetras($reg_cli->total,"BOLIVIANOS")); 

$pdf->addCadreTVAs("---".$con_letra);

require_once "../model/Configuracion.php";

$objConfiguracion = new Configuracion();


$query_global = $objConfiguracion->Listar();

$reg_igv = $query_global->fetch_object();

$pdf->addTVAs( $reg_cli->impuesto, $reg_cli->total,"$reg_igv->simbolo_moneda ");
$pdf->addCadreEurosFrancs("$reg_igv->nombre_impuesto"." $reg_cli->impuesto%");
$pdf->Output('Reporte de Ingreso','I');
?>
