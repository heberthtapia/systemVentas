<?php ob_start();
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
<html>
<link rel="stylesheet" type="text/css" href="../public/css/pdf.css"/>
<body>

<div id="divIzq" style="float: left; width: 50%" >

        	<h4 align="center" style="font-size:24px;">NOTA DE VENTA</h4>

            <table cellspacing="0" style="width: 100%; text-align: center; font-size: 14px">
                <tr>
                    <td style="width: 30%;">
                        <img style="width: 50%;" src="../<?=$reg_cli->logo;?>" alt="Logo"><br>
                        <span style="font-size: 11px; font-weight: bold;"><?=$reg_cli->razon_social;?></span>
                    </td>
                    <td style="width: 60%;">
                        <div class="div4">
                            Boleta: <?=$reg_cli->serie_comprobante.'-'.$reg_cli->num_comprobante;?>
                        </div>
                    </td>
                </tr>
            </table>
            <br>

            <table id="datos" style=" width:100%; border-collapse: collapse;">
                <tbody>
                    <tr>
                        <td style="width: 15%; text-align:left; font-weight: bold;"><strong>Cliente Sr(a).:</strong></td>
                        <td style="width: 50%; text-align:left; text-transform:capitalize"><?=$reg_cli->nombre;?></td>

                        <td style =" text-align:left;"><strong>Fecha:</strong></td>
                        <td style ="text-align:left;"><?=$reg_cli->fecha;?></td>
                    </tr>
                    <tr>
                        <td style =" text-align:left; font-weight: bold;"><strong>Direcci&oacute;n:</strong></td>
                        <td style ="text-align:left; text-transform:capitalize"><?=utf8_decode($reg_cli->direccion_calle)." - ".utf8_decode($reg_cli->direccion_departamento);?></td>

                        <td style =" text-align:left; font-weight: bold;" valign="top"><strong>Vendedor:</strong></td>
                        <td style ="text-align:left; text-transform:capitalize;">
                            <div class="hyphenation">
                            <?=$reg_cli->us.'<br> '.$reg_cli->usAp;?>
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
                    $cont = 0;
                    $query_ped = $objPedido->ImprimirDetallePedido($_GET["id"]);

                    while( $reg = $query_ped->fetch_object() ){
                      $cont = $cont + 1;
                    ?>
                    <tr <?php if($cont%2 == 0) echo("class='even'"); ?> >
                        <td align="center" class="pri"><?=$reg->codigo;?></td>
                        <td align="left"><?=utf8_decode("$reg->articulo Serie:$reg->serie");?></td>
                        <td align="center"><?=$reg->cantidad;?></td>
                        <td align="center"><?=$reg->precio_venta;?></td>
                        <td align="center"><?=$reg->descuento;?></td>
                        <td align="right" class="last"><?=$reg->sub_total;?></td>
                    </tr>
                    <?PHP
                        $total = $reg_total->Total;
                    }
                        $h = 300 - ($cont*50);
                        if($h < 0){
                            $h = $h*(-1);
                            $h = $h + 200;
                        };
                        if ($h == 0) {
                            $h = 100;
                        }
                    ?>

                    <tr>
                        <td class="pri">1</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="last"></td>
                    </tr>
                    <tr>
                        <td class="pri">1</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="last"></td>
                    </tr>
                    <tr>
                        <td class="pri">1</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="last"></td>
                    </tr>
                    <?php
                    for ($k=0; $k<12; $k++) {
                    ?>
                    <tr>
                        <td class="pri">1</td>
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

            <table class="total" align="center" style=" width: 100%; border-radius: 5px; border-top: 1px solid #abb2b9; border-right: 1px solid #abb2b9; border-bottom: 1px solid #abb2b9; border-left: 1px solid #abb2b9;" >
                <tbody>
                    <tr>
                        <td style="width: 310px">Importe Total:</td>
                        <td class="last" style="width: 60px">Bs.- <?=$total;?></td>
                    </tr>
                    <tr>
                        <td colspan="6" class="last top">Son: <?=$con_letra;?></td>

                    </tr>
                </tbody>
            </table>

</div>

<div id="divDer" style="float: right; width: 50%" >

        	<h4 align="center" style="font-size:24px;">NOTA DE VENTA</h4>

            <table cellspacing="0" style="width: 100%; text-align: center; font-size: 14px">
                <tr>
                    <td style="width: 30%;">
                        <img style="width: 50%;" src="../<?=$reg_cli->logo;?>" alt="Logo"><br>
                        <span style="font-size: 11px; font-weight: bold;"><?=$reg_cli->razon_social;?></span>
                    </td>
                    <td style="width: 60%;">
                        <div class="div4">
                            Boleta: <?=$reg_cli->serie_comprobante.'-'.$reg_cli->num_comprobante;?>
                        </div>
                    </td>
                </tr>
            </table>
            <br>

            <table id="datos" style=" width:100%; border-collapse: collapse;">
                <tbody>
                    <tr>
                        <td style="width: 15%; text-align:left; font-weight: bold;"><strong>Cliente Sr(a).:</strong></td>
                        <td style="width: 50%; text-align:left; text-transform:capitalize"><?=$reg_cli->nombre;?></td>

                        <td style =" text-align:left;"><strong>Fecha:</strong></td>
                        <td style ="text-align:left;"><?=$reg_cli->fecha;?></td>
                    </tr>
                    <tr>
                        <td style =" text-align:left; font-weight: bold;"><strong>Direcci&oacute;n:</strong></td>
                        <td style ="text-align:left; text-transform:capitalize"><?=utf8_decode($reg_cli->direccion_calle)." - ".utf8_decode($reg_cli->direccion_departamento);?></td>

                        <td style =" text-align:left; font-weight: bold;" valign="top"><strong>Vendedor:</strong></td>
                        <td style ="text-align:left; text-transform:capitalize;">
                            <div class="hyphenation">
                            <?=$reg_cli->us.'<br> '.$reg_cli->usAp;?>
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
                    $cont = 0;
                    $query_ped = $objPedido->ImprimirDetallePedido($_GET["id"]);

                    while( $reg = $query_ped->fetch_object() ){
                      $cont = $cont + 1;
                    ?>
                    <tr <?php if($cont%2 == 0) echo("class='even'"); ?> >
                        <td align="center" class="pri"><?=$reg->codigo;?></td>
                        <td align="left"><?=utf8_decode("$reg->articulo Serie:$reg->serie");?></td>
                        <td align="center"><?=$reg->cantidad;?></td>
                        <td align="center"><?=$reg->precio_venta;?></td>
                        <td align="center"><?=$reg->descuento;?></td>
                        <td align="right" class="last"><?=$reg->sub_total;?></td>
                    </tr>
                    <?PHP
                        $total = $reg_total->Total;
                    }
                        $h = 300 - ($cont*50);
                        if($h < 0){
                            $h = $h*(-1);
                            $h = $h + 200;
                        };
                        if ($h == 0) {
                            $h = 100;
                        }
                    ?>

                    <tr>
                        <td class="pri">1</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="last"></td>
                    </tr>
                    <tr>
                        <td class="pri">1</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="last"></td>
                    </tr>
                    <tr>
                        <td class="pri">1</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="last"></td>
                    </tr>
                    <?php
                    for ($k=0; $k<12; $k++) {
                    ?>
                    <tr>
                        <td class="pri">1</td>
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

            <table class="total" align="center" style=" width: 100%; border-radius: 5px; border-top: 1px solid #abb2b9; border-right: 1px solid #abb2b9; border-bottom: 1px solid #abb2b9; border-left: 1px solid #abb2b9;" >
                <tbody>
                    <tr>
                        <td style="width: 310px">Importe Total:</td>
                        <td class="last" style="width: 60px">Bs.- <?=$total;?></td>
                    </tr>
                    <tr>
                        <td colspan="6" class="last top">Son: <?=$con_letra;?></td>

                    </tr>
                </tbody>
            </table>

</div>

<table>
	<caption>table title and/or explanatory text</caption>

	<tbody>
		<tr>
			<td>
				<div id="divDer" style="float: right; width: 50%" >

        	<h4 align="center" style="font-size:24px;">NOTA DE VENTA</h4>

            <table cellspacing="0" style="width: 100%; text-align: center; font-size: 14px">
                <tr>
                    <td style="width: 30%;">
                        <img style="width: 50%;" src="../<?=$reg_cli->logo;?>" alt="Logo"><br>
                        <span style="font-size: 11px; font-weight: bold;"><?=$reg_cli->razon_social;?></span>
                    </td>
                    <td style="width: 60%;">
                        <div class="div4">
                            Boleta: <?=$reg_cli->serie_comprobante.'-'.$reg_cli->num_comprobante;?>
                        </div>
                    </td>
                </tr>
            </table>
            <br>

            <table id="datos" style=" width:100%; border-collapse: collapse;">
                <tbody>
                    <tr>
                        <td style="width: 15%; text-align:left; font-weight: bold;"><strong>Cliente Sr(a).:</strong></td>
                        <td style="width: 50%; text-align:left; text-transform:capitalize"><?=$reg_cli->nombre;?></td>

                        <td style =" text-align:left;"><strong>Fecha:</strong></td>
                        <td style ="text-align:left;"><?=$reg_cli->fecha;?></td>
                    </tr>
                    <tr>
                        <td style =" text-align:left; font-weight: bold;"><strong>Direcci&oacute;n:</strong></td>
                        <td style ="text-align:left; text-transform:capitalize"><?=utf8_decode($reg_cli->direccion_calle)." - ".utf8_decode($reg_cli->direccion_departamento);?></td>

                        <td style =" text-align:left; font-weight: bold;" valign="top"><strong>Vendedor:</strong></td>
                        <td style ="text-align:left; text-transform:capitalize;">
                            <div class="hyphenation">
                            <?=$reg_cli->us.'<br> '.$reg_cli->usAp;?>
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
                    $cont = 0;
                    $query_ped = $objPedido->ImprimirDetallePedido($_GET["id"]);

                    while( $reg = $query_ped->fetch_object() ){
                      $cont = $cont + 1;
                    ?>
                    <tr <?php if($cont%2 == 0) echo("class='even'"); ?> >
                        <td align="center" class="pri"><?=$reg->codigo;?></td>
                        <td align="left"><?=utf8_decode("$reg->articulo Serie:$reg->serie");?></td>
                        <td align="center"><?=$reg->cantidad;?></td>
                        <td align="center"><?=$reg->precio_venta;?></td>
                        <td align="center"><?=$reg->descuento;?></td>
                        <td align="right" class="last"><?=$reg->sub_total;?></td>
                    </tr>
                    <?PHP
                        $total = $reg_total->Total;
                    }
                        $h = 300 - ($cont*50);
                        if($h < 0){
                            $h = $h*(-1);
                            $h = $h + 200;
                        };
                        if ($h == 0) {
                            $h = 100;
                        }
                    ?>

                    <tr>
                        <td class="pri">1</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="last"></td>
                    </tr>
                    <tr>
                        <td class="pri">1</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="last"></td>
                    </tr>
                    <tr>
                        <td class="pri">1</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="last"></td>
                    </tr>
                    <?php
                    for ($k=0; $k<12; $k++) {
                    ?>
                    <tr>
                        <td class="pri">1</td>
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

            <table class="total" align="center" style=" width: 100%; border-radius: 5px; border-top: 1px solid #abb2b9; border-right: 1px solid #abb2b9; border-bottom: 1px solid #abb2b9; border-left: 1px solid #abb2b9;" >
                <tbody>
                    <tr>
                        <td style="width: 310px">Importe Total:</td>
                        <td class="last" style="width: 60px">Bs.- <?=$total;?></td>
                    </tr>
                    <tr>
                        <td colspan="6" class="last top">Son: <?=$con_letra;?></td>

                    </tr>
                </tbody>
            </table>

</div>
			</td>
			<td>
				<div id="divDer" style="float: right; width: 50%" >

        	<h4 align="center" style="font-size:24px;">NOTA DE VENTA</h4>

            <table cellspacing="0" style="width: 100%; text-align: center; font-size: 14px">
                <tr>
                    <td style="width: 30%;">
                        <img style="width: 50%;" src="../<?=$reg_cli->logo;?>" alt="Logo"><br>
                        <span style="font-size: 11px; font-weight: bold;"><?=$reg_cli->razon_social;?></span>
                    </td>
                    <td style="width: 60%;">
                        <div class="div4">
                            Boleta: <?=$reg_cli->serie_comprobante.'-'.$reg_cli->num_comprobante;?>
                        </div>
                    </td>
                </tr>
            </table>
            <br>

            <table id="datos" style=" width:100%; border-collapse: collapse;">
                <tbody>
                    <tr>
                        <td style="width: 15%; text-align:left; font-weight: bold;"><strong>Cliente Sr(a).:</strong></td>
                        <td style="width: 50%; text-align:left; text-transform:capitalize"><?=$reg_cli->nombre;?></td>

                        <td style =" text-align:left;"><strong>Fecha:</strong></td>
                        <td style ="text-align:left;"><?=$reg_cli->fecha;?></td>
                    </tr>
                    <tr>
                        <td style =" text-align:left; font-weight: bold;"><strong>Direcci&oacute;n:</strong></td>
                        <td style ="text-align:left; text-transform:capitalize"><?=utf8_decode($reg_cli->direccion_calle)." - ".utf8_decode($reg_cli->direccion_departamento);?></td>

                        <td style =" text-align:left; font-weight: bold;" valign="top"><strong>Vendedor:</strong></td>
                        <td style ="text-align:left; text-transform:capitalize;">
                            <div class="hyphenation">
                            <?=$reg_cli->us.'<br> '.$reg_cli->usAp;?>
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
                    $cont = 0;
                    $query_ped = $objPedido->ImprimirDetallePedido($_GET["id"]);

                    while( $reg = $query_ped->fetch_object() ){
                      $cont = $cont + 1;
                    ?>
                    <tr <?php if($cont%2 == 0) echo("class='even'"); ?> >
                        <td align="center" class="pri"><?=$reg->codigo;?></td>
                        <td align="left"><?=utf8_decode("$reg->articulo Serie:$reg->serie");?></td>
                        <td align="center"><?=$reg->cantidad;?></td>
                        <td align="center"><?=$reg->precio_venta;?></td>
                        <td align="center"><?=$reg->descuento;?></td>
                        <td align="right" class="last"><?=$reg->sub_total;?></td>
                    </tr>
                    <?PHP
                        $total = $reg_total->Total;
                    }
                        $h = 300 - ($cont*50);
                        if($h < 0){
                            $h = $h*(-1);
                            $h = $h + 200;
                        };
                        if ($h == 0) {
                            $h = 100;
                        }
                    ?>

                    <tr>
                        <td class="pri">1</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="last"></td>
                    </tr>
                    <tr>
                        <td class="pri">1</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="last"></td>
                    </tr>
                    <tr>
                        <td class="pri">1</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="last"></td>
                    </tr>
                    <?php
                    for ($k=0; $k<12; $k++) {
                    ?>
                    <tr>
                        <td class="pri">1</td>
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

            <table class="total" align="center" style=" width: 100%; border-radius: 5px; border-top: 1px solid #abb2b9; border-right: 1px solid #abb2b9; border-bottom: 1px solid #abb2b9; border-left: 1px solid #abb2b9;" >
                <tbody>
                    <tr>
                        <td style="width: 310px">Importe Total:</td>
                        <td class="last" style="width: 60px">Bs.- <?=$total;?></td>
                    </tr>
                    <tr>
                        <td colspan="6" class="last top">Son: <?=$con_letra;?></td>

                    </tr>
                </tbody>
            </table>

</div>
			</td>
		</tr>
	</tbody>
</table>

</body>
</html>

<?php
//$html = '<h1>Hola mundo!</h1>';

require "../vendor/autoload.php";

use Dompdf\Dompdf;

//generate some PDFs!
$dompdf = new DOMPDF();  //if you use namespaces you may use new \DOMPDF()
$dompdf->loadHtml(ob_get_clean());
$dompdf->set_paper("letter", "landscape");
$dompdf->render();
$dompdf->stream("sample.pdf", array("Attachment"=>0));

?>
