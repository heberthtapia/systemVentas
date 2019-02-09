<?PHP
session_start();

$lo = $_SESSION["logo"];

require_once "../model/Configuracion.php";

      $objConf = new Configuracion();

      $query_conf = $objConf->Listar();

      $regConf = $query_conf->fetch_object();

require_once "../model/Pedido.php";

$objPedido = new Pedido();

$query_cli = $objPedido->GetVenta($_GET["id"]);

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

$query_total = $objPedido->TotalPedido($_GET["id"]);

$reg_total = $query_total->fetch_object();

require_once "../ajax/Letras.php";

$V=new EnLetras();
$con_letra=strtoupper($V->ValorEnLetras($reg_total->Total,""));

?>

<style type="text/css">
<!--
.div { background: #CCDDCC; color: #002200; text-align: center; width: 70mm; height: 20mm; margin: 2mm; }
.div1 {
    border: solid 1px #abb2b9;
    border-radius: 2mm;
    -moz-border-radius: 2mm;
    width: 80%;
    margin-left: 5mm;
    margin-bottom: 5px;
}
.div2 {
    border: solid 1px #abb2b9;
    border-radius: 2mm 2mm 0mm 0mm;
    -moz-border-radius: 2mm 2mm 0mm 0mm;
    width: 80%;
    margin: 10px 0 0 50px;
}
.div3 {
    border-top: none;
    border-right: : solid 1px #abb2b9;
    border-bottom: : solid 1px #abb2b9;
    border-left: solid 1px #abb2b9;
    border-radius: 0mm 0mm 2mm 2mm;
    -moz-border-radius: 0mm 0mm 2mm 2mm;
    width: 80%;
    margin: 0 0 0 50px;
}
.div4 {
    border: solid 1px #000;
    background-color: #0BD2C2;
    border-radius: 2mm;
    -moz-border-radius: 2mm;
    width: 50%;
    padding: 4px;
    font-weight: bold;
}

.div5 {
    border: solid 1px #abb2b9;
    border-radius: 2mm 2mm 0mm 0mm;
    -moz-border-radius: 2mm 2mm 0mm 0mm;
    width: 60%;
}
.div6 {
    border-top: none;
    border-right: : solid 1px #abb2b9;
    border-bottom: : solid 1px #abb2b9;
    border-left: solid 1px #abb2b9;
    border-radius: 0mm 0mm 2mm 2mm;
    -moz-border-radius: 0mm 0mm 2mm 2mm;
    width: 60%;
}
.hyphenation {
    width: 120px;
     word-break: break-all;
     /* Non standard for webkit */
     word-break: break-word;

    -webkit-hyphens: auto;
       -moz-hyphens: auto;
        -ms-hyphens: auto;
            hyphens: auto;
}
.hyphenation1 {
    width: 220px;
     word-break: break-all;
     /* Non standard for webkit */
     word-break: break-word;

    -webkit-hyphens: auto;
       -moz-hyphens: auto;
        -ms-hyphens: auto;
            hyphens: auto;
}
.hyphenation2 {
    width: 210px;
     word-break: break-all;
     /* Non standard for webkit */
     word-break: break-word;

    -webkit-hyphens: auto;
       -moz-hyphens: auto;
        -ms-hyphens: auto;
            hyphens: auto;
}
-->
</style>

<page orientation="paysage" backtop="7mm" backbottom="5mm" backleft="3mm" backright="3mm">
    <page_header>
        <table class="page_header" style="width: 100%;">
            <tr>
                <td style="width: 15%"><?=$reg_cli->razon_social;?></td>
                <td style="width: 20%; text-align: center;"><strong><?=$reg_cli->serie_comprobante.'-'.$reg_cli->num_comprobante;?></strong></td>
                <td style="width: 15%; text-align:right; padding-right: 10px;"><strong><?php echo $reg_cli->fecha.' '.$reg_cli->hora; ?></strong></td>

                <td style="width: 17%; padding-left: 10px;"><?=$reg_cli->razon_social;?></td>
                <td style="width: 18%; text-align: center;"><strong><?=$reg_cli->serie_comprobante.'-'.$reg_cli->num_comprobante;?></strong></td>
                <td style="width: 15%; text-align:right;"><strong><?php echo $reg_cli->fecha.' '.$reg_cli->hora; ?></strong></td>
            </tr>
        </table>
    </page_header>
    <page_footer>
        <table class="page_footer" style="width: 100%;">
            <tr>
                <td style="width: 25%">Dirección: <?=$reg_cli->direccion;?><br>Telefono: <?=$reg_cli->telefono_suc?></td>
                <td style=" text-align:right; width: 25%; padding-right: 10px;">Email: <?=$reg_cli->email_suc?><br>Pag. [[page_cu]]/[[page_nb]]</td>

                <td style="width: 25%; padding-left: 10px;">Dirección: <?=$reg_cli->direccion;?><br>Telefono: <?=$reg_cli->telefono_suc?></td>
                <td style=" text-align:right; width: 25%">Email: <?=$reg_cli->email_suc?><br>Pag. [[page_cu]]/[[page_nb]]</td>
            </tr>
        </table>
        <link rel="stylesheet" type="text/css" href="../public/css/pdf.css"/>
    </page_footer>

<table style="width: 100%; border-collapse: collapse;" align="center">
    <tbody>
    <tr>
        <td valign="top" style="width: 50%; border-right: 1px solid dashed;">
            <h4 align="center" style="font-size:24px;">NOTA DE VENTA</h4>
            <table cellspacing="0" style="width: 100%; text-align: center; font-size: 14px; margin-bottom: 10px;">
                <tr>
                    <td style="width: 30%;">
                        <img height="65" src="../<?=$reg_cli->logo;?>" alt="Logo"><br>
                        <span style="font-size: 9px; font-weight: bold; margin: 3px 0 0 0;"><?=$reg_cli->razon_social;?></span>
                    </td>
                    <td style="width: 60%;">
                        <div class="div4">
                            Boleta: <?=$reg_cli->serie_comprobante.'-'.$reg_cli->num_comprobante;?>
                        </div>
                    </td>
                </tr>
            </table>

            <table id="datos" style="border-collapse: collapse;" >
                <tbody>
                    <tr>
                        <td style="width: 15%; text-align:left; font-weight: bold;"><strong>Cliente Sr(a).:</strong></td>
                        <td style="width: 45%; text-align:left; text-transform:capitalize">
                            <div class="hyphenation1">
                            <?=$reg_cli->nombre;?>
                            </div>
                        </td>

                        <td style =" text-align:left;"><strong>Fecha:</strong></td>
                        <td style ="text-align:left;"><?=$reg_cli->fecha.' '.$reg_cli->hora;?></td>
                    </tr>
                    <tr>
                        <td style =" text-align:left; font-weight: bold;"><strong>Direcci&oacute;n:</strong></td>
                        <td style ="text-align:left; text-transform:capitalize">
                            <div class="hyphenation1">
                            <?=utf8_decode($reg_cli->direccion_calle)." - ".utf8_decode($reg_cli->direccion_departamento);?>
                            </div>
                        </td>

                        <td style =" text-align:left; font-weight: bold;" valign="top"><strong>Vendedor:</strong></td>
                        <td style ="text-align:left; text-transform:capitalize;">
                            <div class="hyphenation">
                            <?=$reg_cli->us.' '.$reg_cli->usAp;?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style =" text-align:left; font-weight: bold;"><strong>N°:</strong></td>
                        <td style ="text-align:left; text-transform:capitalize"></td>
                    </tr>
                    <tr>
                        <td style =" text-align:left; font-weight: bold;"><strong><?=$reg_cli->doc;?>:</strong></td>
                        <td style ="text-align:left; text-transform:capitalize"><?=$reg_cli->num_documento;?></td>

                    </tr>
                    <tr>
                        <td style =" text-align:left; font-weight: bold;"><strong>Email:</strong></td>
                        <td style ="text-align:left;"><?=$reg_cli->email;?></td>
                    </tr>
                    <tr>
                        <td style =" text-align:left; font-weight: bold;"><strong>Celular:</strong></td>
                        <td style ="text-align:left;"><?=$reg_cli->telefono;?></td>
                    </tr>
                </tbody>
            </table>

            <h5>DETALLE</h5>

            <table class="report" style="width: 100%; border-radius: 5px; border-top: 1px solid #abb2b9; border-right: 1px solid #abb2b9; border-bottom: 1px solid #abb2b9; border-left: 1px solid #abb2b9;" align="center">
                <thead>
                    <tr>
                        <th>CODIGO</th>
                        <th align="center">DESCRIPCION</th>
                        <th>CANT.</th>
                        <th>P. UNIT.</th>
                        <th align="center">DESC.</th>
                        <th class="last">SUBTOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <?PHP
                    $conti = 0;
                    $query_ped = $objPedido->ImprimirDetallePedido($_GET["id"]);

                    $row_cnt = $query_ped->num_rows;

                    $query_ped_izq = $objPedido->ImprimirDetallePedidoLimit($_GET["id"],0,11);

                    while( ($regi = $query_ped_izq->fetch_object()) && ($conti < 11)){
                      $conti++;
                    ?>
                    <tr <?php if($conti%2 == 0) echo("class='even'"); ?> >
                        <td align="center" class="pri"><?=$regi->codigo;?></td>
                        <td align="left">
                            <div class="hyphenation2">
                            <?=utf8_decode("$regi->articulo Serie:$regi->serie");?>
                            </div>
                        </td>
                        <td align="center"><?=$regi->cantidad;?></td>
                        <td align="center"><?=$regi->precio_venta;?></td>
                        <td align="center"><?=$regi->descuento;?></td>
                        <td align="right" class="last"><?=$regi->sub_total;?></td>
                    </tr>
                    <?PHP
                        $total = $reg_total->Total;
                    }
                    for ($k=$conti; $k<11; $k++) {
                        $conti++;
                    ?>
                    <tr>
                        <td class="pri">&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="last"></td>
                    </tr>
                    <?php
                    }
                    ?>

                </tbody>
            </table>
            <?php if ($row_cnt <= 11): ?>
                <table class="total" align="center" style=" width: 100%; border-radius: 5px; border-top: 1px solid #abb2b9; border-right: 1px solid #abb2b9; border-bottom: 1px solid #abb2b9; border-left: 1px solid #abb2b9;" >
                    <tbody>
                        <tr>
                            <td style="width: 351px">Importe Total:</td>
                            <td class="last" style="width: 60px">Bs.- <?=$total;?></td>
                        </tr>
                        <tr>
                            <td colspan="6" class="last top">Son: <?=$con_letra;?></td>

                        </tr>
                    </tbody>
                </table>
            <?php endif ?>
        </td>
        <td valign="top" style="width: 50%; padding-left: 15px;">
            <h4 align="center" style="font-size:24px;">NOTA DE VENTA</h4>
            <table cellspacing="0" style="width: 100%; text-align: center; font-size: 14px; margin-bottom: 10px;">
                <tr>
                    <td style="width: 30%;">
                        <img height="65" src="../<?=$reg_cli->logo;?>" alt="Logo"><br>
                        <span style="font-size: 9px; font-weight: bold; margin: 3px 0 0 0;"><?=$reg_cli->razon_social;?></span>
                    </td>
                    <td style="width: 60%;">
                        <div class="div4">
                            Boleta: <?=$reg_cli->serie_comprobante.'-'.$reg_cli->num_comprobante;?>
                        </div>
                    </td>
                </tr>
            </table>

            <table id="datos" style="border-collapse: collapse;" >
                <tbody>
                    <tr>
                        <td style="width: 15%; text-align:left; font-weight: bold;"><strong>Cliente Sr(a).:</strong></td>
                        <td style="width: 45%; text-align:left; text-transform:capitalize">
                            <div class="hyphenation1">
                            <?=$reg_cli->nombre;?>
                            </div>
                        </td>

                        <td style =" text-align:left;"><strong>Fecha:</strong></td>
                        <td style ="text-align:left;"><?=$reg_cli->fecha.' '.$reg_cli->hora;?></td>
                    </tr>
                    <tr>
                        <td style =" text-align:left; font-weight: bold;"><strong>Direcci&oacute;n:</strong></td>
                        <td style ="text-align:left; text-transform:capitalize">
                            <div class="hyphenation1">
                            <?=utf8_decode($reg_cli->direccion_calle)." - ".utf8_decode($reg_cli->direccion_departamento);?>
                            </div>
                        </td>

                        <td style =" text-align:left; font-weight: bold;" valign="top"><strong>Vendedor:</strong></td>
                        <td style ="text-align:left; text-transform:capitalize;">
                            <div class="hyphenation">
                            <?=$reg_cli->us.' '.$reg_cli->usAp;?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style =" text-align:left; font-weight: bold;"><strong>N°:</strong></td>
                        <td style ="text-align:left; text-transform:capitalize"></td>
                    </tr>
                    <tr>
                        <td style =" text-align:left; font-weight: bold;"><strong><?=$reg_cli->doc;?>:</strong></td>
                        <td style ="text-align:left; text-transform:capitalize"><?=$reg_cli->num_documento;?></td>

                    </tr>
                    <tr>
                        <td style =" text-align:left; font-weight: bold;"><strong>Email:</strong></td>
                        <td style ="text-align:left;"><?=$reg_cli->email;?></td>
                    </tr>
                    <tr>
                        <td style =" text-align:left; font-weight: bold;"><strong>Celular:</strong></td>
                        <td style ="text-align:left;"><?=$reg_cli->telefono;?></td>
                    </tr>
                </tbody>
            </table>

            <h5>DETALLE</h5>
            <table class="report" style="width: 100%; border-radius: 5px; border-top: 1px solid #abb2b9; border-right: 1px solid #abb2b9; border-bottom: 1px solid #abb2b9; border-left: 1px solid #abb2b9;" align="center">
                <thead>
                    <tr>
                        <th>CODIGO</th>
                        <th align="center">DESCRIPCION</th>
                        <th>CANT.</th>
                        <th>P. UNIT.</th>
                        <th align="center">DESC.</th>
                        <th class="last">SUBTOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <?PHP
                    $contd = 0;
                    $query_ped = $objPedido->ImprimirDetallePedido($_GET["id"]);

                    $row_cnt = $query_ped->num_rows;

                    $query_ped_der = $objPedido->ImprimirDetallePedidoLimit($_GET["id"],0,11);

                    while( ($regd = $query_ped_der->fetch_object()) && ($contd < 11)){
                      $contd++;
                    ?>
                    <tr <?php if($contd%2 == 0) echo("class='even'"); ?> >
                        <td align="center" class="pri"><?=$regd->codigo;?></td>
                        <td align="left">
                            <div class="hyphenation2">
                            <?=utf8_decode("$regd->articulo Serie:$regd->serie");?>
                            </div>
                        </td>
                        <td align="center"><?=$regd->cantidad;?></td>
                        <td align="center"><?=$regd->precio_venta;?></td>
                        <td align="center"><?=$regd->descuento;?></td>
                        <td align="right" class="last"><?=$regd->sub_total;?></td>
                    </tr>
                    <?PHP
                        $total = $reg_total->Total;
                    }
                    for ($k=$contd; $k<11; $k++) {
                        $contd++;
                    ?>
                    <tr>
                        <td class="pri">&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="last"></td>
                    </tr>
                    <?php
                    }
                    ?>

                </tbody>
            </table>
            <?php if ($row_cnt <= 11): ?>
                <table class="total" align="center" style=" width: 100%; border-radius: 5px; border-top: 1px solid #abb2b9; border-right: 1px solid #abb2b9; border-bottom: 1px solid #abb2b9; border-left: 1px solid #abb2b9;" >
                    <tbody>
                        <tr>
                            <td style="width: 351px">Importe Total:</td>
                            <td class="last" style="width: 60px">Bs.- <?=$total;?></td>
                        </tr>
                        <tr>
                            <td colspan="6" class="last top">Son: <?=$con_letra;?></td>

                        </tr>
                    </tbody>
                </table>
            <?php endif ?>

        </td>
    </tr>
    </tbody>
</table>

</page>

<!-- SEGUNDA PAGINA -->
<?php if ($row_cnt > 11): ?>
<page orientation="paysage" backtop="10mm" backbottom="10mm" backleft="3mm" backright="3mm">
    <page_header>
        <table class="page_header" style="width: 100%;">
            <tr>
                <td style="width: 15%"><?=$reg_cli->razon_social;?></td>
                <td style="width: 20%; text-align: center;"><strong>P<?=$reg_cli->serie_comprobante.'-'.$reg_cli->num_comprobante;?></strong></td>
                <td style="width: 15%; text-align:right; padding-right: 10px;"><strong><?php echo $reg_cli->fecha; ?></strong></td>

                <td style="width: 17%; padding-left: 10px;"><?=$reg_cli->razon_social;?></td>
                <td style="width: 18%; text-align: center;"><strong><?=$reg_cli->serie_comprobante.'-'.$reg_cli->num_comprobante;?></strong></td>
                <td style="width: 15%; text-align:right;"><strong><?php echo $reg_cli->fecha; ?></strong></td>
            </tr>
        </table>
    </page_header>
    <page_footer>
        <table class="page_footer" style="width: 100%;">
            <tr>
                <td style="width: 25%">Dirección: <?=$reg_cli->direccion;?><br>Telefono: <?=$reg_cli->telefono_suc?></td>
                <td style=" text-align:right; width: 25%; padding-right: 10px;">Email: <?=$reg_cli->email_suc?><br>Pag. [[page_cu]]/[[page_nb]]</td>

                <td style="width: 25%; padding-left: 10px;">Dirección: <?=$reg_cli->direccion;?><br>Telefono: <?=$reg_cli->telefono_suc?></td>
                <td style=" text-align:right; width: 25%">Email: <?=$reg_cli->email_suc?><br>Pag. [[page_cu]]/[[page_nb]]</td>
            </tr>
        </table>
        <link rel="stylesheet" type="text/css" href="../public/css/pdf.css"/>
    </page_footer>

<table style="width: 100%; border-collapse: collapse;" align="center">
    <tbody>
    <tr>
        <td valign="top" style="width: 50%; border-right: 1px solid dashed;">

            <table class="report" style="width: 100%; border-radius: 5px; border-top: 1px solid #abb2b9; border-right: 1px solid #abb2b9; border-bottom: 1px solid #abb2b9; border-left: 1px solid #abb2b9;" align="center">
                <thead>
                    <tr>
                        <th>CODIGO</th>
                        <th align="center">DESCRIPCION</th>
                        <th>CANT.</th>
                        <th>P. UNIT.</th>
                        <th align="center">DESC.</th>
                        <th class="last">SUBTOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <?PHP
                    //$conti = 0;
                    //$query_ped = $objPedido->ImprimirDetallePedido($_GET["id"]);

                    //echo $row_cnt = $query_ped->num_rows;

                    $query_ped_izq = $objPedido->ImprimirDetallePedidoLimit($_GET["id"],11,30);

                    while( ($regi = $query_ped_izq->fetch_object()) && ($conti < 30)){
                      $conti++;
                    ?>
                    <tr <?php if($conti%2 == 0) echo("class='even'"); ?> >
                        <td align="center" class="pri"><?=$regi->codigo;?></td>
                        <td align="left">
                            <div class="hyphenation2">
                            <?=utf8_decode("$regi->articulo Serie:$regi->serie");?>
                            </div>
                        </td>
                        <td align="center"><?=$regi->cantidad;?></td>
                        <td align="center"><?=$regi->precio_venta;?></td>
                        <td align="center"><?=$regi->descuento;?></td>
                        <td align="right" class="last"><?=$regi->sub_total;?></td>
                    </tr>
                    <?PHP
                        $total = $reg_total->Total;
                    }
                    for ($k=$conti; $k<22; $k++) {
                        $conti++;
                    ?>
                    <tr>
                        <td class="pri">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="last"></td>
                    </tr>
                    <?php
                    }
                    ?>

                </tbody>
            </table>
            <?php if ($row_cnt <= 30): ?>
            <table class="total" align="center" style=" width: 100%; border-radius: 5px; border-top: 1px solid #abb2b9; border-right: 1px solid #abb2b9; border-bottom: 1px solid #abb2b9; border-left: 1px solid #abb2b9;" >
                <tbody>
                    <tr>
                        <td style="width: 351px">Importe Total:</td>
                        <td class="last" style="width: 60px">Bs.- <?=$total;?></td>
                    </tr>
                    <tr>
                        <td colspan="6" class="last top">Son: <?=$con_letra;?></td>

                    </tr>
                </tbody>
            </table>
            <?php endif ?>

        </td>
        <td valign="top" style="width: 50%; padding-left: 15px;">

            <table class="report" style="width: 100%; border-radius: 5px; border-top: 1px solid #abb2b9; border-right: 1px solid #abb2b9; border-bottom: 1px solid #abb2b9; border-left: 1px solid #abb2b9;" align="center">
                <thead>
                    <tr>
                        <th>CODIGO</th>
                        <th align="center">DESCRIPCION</th>
                        <th>CANT.</th>
                        <th>P. UNIT.</th>
                        <th align="center">DESC.</th>
                        <th class="last">SUBTOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <?PHP
                    //$conti = 0;
                    //$query_ped = $objPedido->ImprimirDetallePedido($_GET["id"]);

                    //echo $row_cnt = $query_ped->num_rows;

                    $query_ped_der = $objPedido->ImprimirDetallePedidoLimit($_GET["id"],11,30);

                    while( ($regd = $query_ped_der->fetch_object()) && ($contd < 30)){
                      $contd++;
                    ?>
                    <tr <?php if($conti%2 == 0) echo("class='even'"); ?> >
                        <td align="center" class="pri"><?=$regd->codigo;?></td>
                        <td align="left">
                            <div class="hyphenation2">
                            <?=utf8_decode("$regd->articulo Serie:$regd->serie");?>
                            </div>
                        </td>
                        <td align="center"><?=$regd->cantidad;?></td>
                        <td align="center"><?=$regd->precio_venta;?></td>
                        <td align="center"><?=$regd->descuento;?></td>
                        <td align="right" class="last"><?=$regd->sub_total;?></td>
                    </tr>
                    <?PHP
                        $total = $reg_total->Total;
                    }
                    for ($k=$contd; $k<22; $k++) {
                        $contd++;
                    ?>
                    <tr>
                        <td class="pri">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="last"></td>
                    </tr>
                    <?php
                    }
                    ?>

                </tbody>
            </table>

            <?php if ($row_cnt <= 30): ?>
            <table class="total" align="center" style=" width: 100%; border-radius: 5px; border-top: 1px solid #abb2b9; border-right: 1px solid #abb2b9; border-bottom: 1px solid #abb2b9; border-left: 1px solid #abb2b9;" >
                <tbody>
                    <tr>
                        <td style="width: 351px">Importe Total:</td>
                        <td class="last" style="width: 60px">Bs.- <?=$total;?></td>
                    </tr>
                    <tr>
                        <td colspan="6" class="last top">Son: <?=$con_letra;?></td>

                    </tr>
                </tbody>
            </table>
            <?php endif ?>

        </td>
    </tr>
    </tbody>
</table>

</page>
<?php endif ?>

<!-- TERCERA PAGINA -->
<?php if ($row_cnt > 30): ?>
<page orientation="paysage" backtop="10mm" backbottom="10mm" backleft="3mm" backright="3mm">
    <page_header>
        <table class="page_header" style="width: 100%;">
            <tr>
                <td style="width: 15%"><?=$reg_cli->razon_social;?></td>
                <td style="width: 20%; text-align: center;"><strong>P<?=$reg_cli->serie_comprobante.'-'.$reg_cli->num_comprobante;?></strong></td>
                <td style="width: 15%; text-align:right; padding-right: 10px;"><strong><?php echo $reg_cli->fecha; ?></strong></td>

                <td style="width: 17%; padding-left: 10px;"><?=$reg_cli->razon_social;?></td>
                <td style="width: 18%; text-align: center;"><strong><?=$reg_cli->serie_comprobante.'-'.$reg_cli->num_comprobante;?></strong></td>
                <td style="width: 15%; text-align:right;"><strong><?php echo $reg_cli->fecha; ?></strong></td>
            </tr>
        </table>
    </page_header>
    <page_footer>
        <table class="page_footer" style="width: 100%;">
            <tr>
                <td style="width: 25%">Dirección: <?=$reg_cli->direccion;?><br>Telefono: <?=$reg_cli->telefono_suc?></td>
                <td style=" text-align:right; width: 25%; padding-right: 10px;">Email: <?=$reg_cli->email_suc?><br>Pag. [[page_cu]]/[[page_nb]]</td>

                <td style="width: 25%; padding-left: 10px;">Dirección: <?=$reg_cli->direccion;?><br>Telefono: <?=$reg_cli->telefono_suc?></td>
                <td style=" text-align:right; width: 25%">Email: <?=$reg_cli->email_suc?><br>Pag. [[page_cu]]/[[page_nb]]</td>
            </tr>
        </table>
        <link rel="stylesheet" type="text/css" href="../public/css/pdf.css"/>
    </page_footer>

<table style="width: 100%; border-collapse: collapse;" align="center">
    <tbody>
    <tr>
        <td valign="top" style="width: 50%; border-right: 1px solid dashed;">

            <table class="report" style="width: 100%; border-radius: 5px; border-top: 1px solid #abb2b9; border-right: 1px solid #abb2b9; border-bottom: 1px solid #abb2b9; border-left: 1px solid #abb2b9;" align="center">
                <thead>
                    <tr>
                        <th>CODIGO</th>
                        <th align="center">DESCRIPCION</th>
                        <th>CANT.</th>
                        <th>P. UNIT.</th>
                        <th align="center">DESC.</th>
                        <th class="last">SUBTOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <?PHP
                    //$conti = 0;
                    //$query_ped = $objPedido->ImprimirDetallePedido($_GET["id"]);

                    //echo $row_cnt = $query_ped->num_rows;

                    $query_ped_izq = $objPedido->ImprimirDetallePedidoLimit($_GET["id"],30,40);

                    while( ($regi = $query_ped_izq->fetch_object()) && ($conti < 40)){
                      $conti++;
                    ?>
                    <tr <?php if($conti%2 == 0) echo("class='even'"); ?> >
                        <td align="center" class="pri"><?=$regi->codigo;?></td>
                        <td align="left">
                            <div class="hyphenation2">
                            <?=utf8_decode("$regi->articulo Serie:$regi->serie");?>
                            </div>
                        </td>
                        <td align="center"><?=$regi->cantidad;?></td>
                        <td align="center"><?=$regi->precio_venta;?></td>
                        <td align="center"><?=$regi->descuento;?></td>
                        <td align="right" class="last"><?=$regi->sub_total;?></td>
                    </tr>
                    <?PHP
                        $total = $reg_total->Total;
                    }
                    for ($k=$conti; $k<22; $k++) {
                        $conti++;
                    ?>
                    <tr>
                        <td class="pri">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="last"></td>
                    </tr>
                    <?php
                    }
                    ?>

                </tbody>
            </table>

            <table class="total" align="center" style=" width: 100%; border-radius: 5px; border-top: 1px solid #abb2b9; border-right: 1px solid #abb2b9; border-bottom: 1px solid #abb2b9; border-left: 1px solid #abb2b9;" >
                <tbody>
                    <tr>
                        <td style="width: 351px">Importe Total:</td>
                        <td class="last" style="width: 60px">Bs.- <?=$total;?></td>
                    </tr>
                    <tr>
                        <td colspan="6" class="last top">Son: <?=$con_letra;?></td>

                    </tr>
                </tbody>
            </table>

        </td>
        <td valign="top" style="width: 50%; padding-left: 15px;">

            <table class="report" style="width: 100%; border-radius: 5px; border-top: 1px solid #abb2b9; border-right: 1px solid #abb2b9; border-bottom: 1px solid #abb2b9; border-left: 1px solid #abb2b9;" align="center">
                <thead>
                    <tr>
                        <th>CODIGO</th>
                        <th align="center">DESCRIPCION</th>
                        <th>CANT.</th>
                        <th>P. UNIT.</th>
                        <th align="center">DESC.</th>
                        <th class="last">SUBTOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <?PHP
                    //$conti = 0;
                    //$query_ped = $objPedido->ImprimirDetallePedido($_GET["id"]);

                    //echo $row_cnt = $query_ped->num_rows;

                    $query_ped_der = $objPedido->ImprimirDetallePedidoLimit($_GET["id"],30,40);

                    while( ($regd = $query_ped_der->fetch_object()) && ($contd < 40)){
                      $contd++;
                    ?>
                    <tr <?php if($conti%2 == 0) echo("class='even'"); ?> >
                        <td align="center" class="pri"><?=$regd->codigo;?></td>
                        <td align="left">
                            <div class="hyphenation2">
                            <?=utf8_decode("$regd->articulo Serie:$regd->serie");?>
                            </div>
                        </td>
                        <td align="center"><?=$regd->cantidad;?></td>
                        <td align="center"><?=$regd->precio_venta;?></td>
                        <td align="center"><?=$regd->descuento;?></td>
                        <td align="right" class="last"><?=$regd->sub_total;?></td>
                    </tr>
                    <?PHP
                        $total = $reg_total->Total;
                    }
                    for ($k=$contd; $k<22; $k++) {
                        $contd++;
                    ?>
                    <tr>
                        <td class="pri">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="last"></td>
                    </tr>
                    <?php
                    }
                    ?>

                </tbody>
            </table>

            <table class="total" align="center" style=" width: 100%; border-radius: 5px; border-top: 1px solid #abb2b9; border-right: 1px solid #abb2b9; border-bottom: 1px solid #abb2b9; border-left: 1px solid #abb2b9;" >
                <tbody>
                    <tr>
                        <td style="width: 351px">Importe Total:</td>
                        <td class="last" style="width: 60px">Bs.- <?=$total;?></td>
                    </tr>
                    <tr>
                        <td colspan="6" class="last top">Son: <?=$con_letra;?></td>

                    </tr>
                </tbody>
            </table>


        </td>
    </tr>
    </tbody>
</table>

</page>
<?php endif ?>
