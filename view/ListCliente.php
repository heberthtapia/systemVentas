<script>
    $(document).ready(function(){
        $("#status, #txtZona").change(function(){
            status = $("#status").val();
            zona = $("#txtZona").val();
            fecha = $("#cboFecha").val();

            listaCliente(status,zona,fecha);
        });
    })
</script>
<div class="panel panel-default">
            <div class="panel-heading">
                <div class="box-header with-border">
                  <h3 class="box-title" >Geolocalizacion de Clientes</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
            </div>
                <!-- /.box-header -->
                <div class="box-body">
                  	<div class="row">
	                  	<div class="col-md-12">
		                    <!--Contenido-->

	<div class="col-sm-14">

        <form role="form">
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                            <div class="form-group has-success">
                                <label>Zona:</label>
                                <input id="txtZona" type="text" maxlength="100" name="txtZona" class="form-control" placeholder="Zona" autofocus="" />
                            </div>
                        </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 left">
                    <div class="form-group has-success">
                        <label>Estado:</label>
                        <select id="status" name="status" class="form-control" required="" >
                            <option value="">Todos</option>
                            <option value="V">Vendidas</option>
                            <option value="N">No Vendidas</option>
                            <option value="C">Cerradas</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 left">
                    <div class="form-group has-success">
                        <label for="inputMarca">Fecha :</label>
                        <input id="txtIdSucursal" type="hidden" value="<?php echo $_SESSION["idsucursal"] ?>" maxlength="50" class="form-control" name="txtIdSucursal" required="" placeholder="" autofocus="" />
                        <input id="cboFecha" type="date" maxlength="50" value="<?php echo date("Y-m-d"); ?>"  class="form-control" name="cboFecha" required="" />
                    </div>
                </div>
            </div>
        </form>
        <br>
            <div id="mapa" class="table-responsive">
                <!-- Listado de articulos por vendedor -->
            </div>
    </div>

		                    <!--Fin-Contenido-->
                  		</div>
                  	</div><!-- /.row -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->

<div id="modalListadoSucursal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Listado de Sucursales</h4>
      </div>
      <div class="modal-body">
      <div class="table-responsive">
        <table id="tblSucursales" class="table table-striped table-bordered table-condensed table-hover" cellpadding="0" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Seleccione</th>
                            <th>#</th>
                            <th>Razón Social</th>
                            <th>Documento</th>
                            <th>Dirección</th>
                            <th>E-Mail</th>
                            <th>Logo</th>
                        </tr>
                    </thead>

                    <tfoot>
                        <tr>
                            <th>Seleccione</th>
                            <th>#</th>
                            <th>Razón Social</th>
                            <th>Documento</th>
                            <th>Dirección</th>
                            <th>E-Mail</th>
                            <th>Logo</th>
                        </tr>
                    </tfoot>

                    <tbody id="Sucursales">

                    </tbody>
                </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-remove"></i> Cerrar</button>
        <button type="button" id="btnAgregarSucursal" class="btn btn-primary"><i class="fa fa-plus"></i> Agregar</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

    $('#liConsultaVentas').addClass("treeview active");
    $('#liStatusCliente').addClass("active");

    jQuery(document).ready(function($) {
        status = $("#status").val();
        zona = $("#txtZona").val();
        fecha = $("#cboFecha").val();

        initMap();
        listaCliente(status,zona,fecha);
    });
</script>
<style>
#mapa {

    height: 500px;
    border: 1px #ccc solid;
}
</style>

<script type="text/javascript">
    //VARIABLES GENERALES
    //DECLARAS FUERA DEL READY DE JQUERY
    var map;
    var markers       = [];
    var marcadores_bd = [];
    var mapa          = null; //VARIABLE GENERAL PARA EL MAPA

    function initMap(){
        /* GOOGLE MAPS */
        var formulario = $('#frmCliente');
        //COODENADAS INICIALES -16.5207007,-68.1615534
        //VARIABLE PARA EL PUNTO INICIAL
        var punto = new google.maps.LatLng(-16.499299167397574, -68.1646728515625);
        //VARIABLE PARA CONFIGURACION INICIAL
        var config = {
            zoom:10,
            center:punto,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        mapa = new google.maps.Map( $("#mapa")[0], config );
    }

    //FUNCIONES PARA EL GOOGLE MAPS
    function deleteMarkers(lista){
        for(i in lista){
            lista[i].setMap(null);
        }
    }

    function listaCliente(status,zona,fecha){
        //ANTES DE LISTAR MARCADORES
        //SE DEBEN QUITAR LOS ANTERIORES DEL MAPA
        deleteMarkers(markers);
        //deleteMarkers(marcadores_bd);
        var img;
        //var formulario_edicion = $("#formUpdate");
        $.ajax({
            type:"POST",
            url:"view/ListPuntos.php",
            data: {
                status: status,
                zona: zona,
                fecha: fecha,
            },
            dataType:"JSON",
            //data:"&id="+id_empleado,
            success: function(data){
                var c = 0;
                // Add multiple markers to map
                var infoWindow = new google.maps.InfoWindow();
                var contentString = new Array();

                $.each(data, function(i, item){
                    $.each(item, function(j, val){
                        contentString[c] = '<div>'
                         +'<h3>Cliente: '+item.nombre[c]+'</h3>'
                         +'<p>Dirección: <strong>'+item.avenida[c]+'</strong> '+item.nomAve[c]+' </p>'
                         +'<p><strong># </strong>: '+item.num[c]+' </p>'
                         +'<p><strong>'+item.zona[c]+': </strong> '+item.nomZona[c]+' </p>'
                         +'</div>';

                        var infowindow = new google.maps.InfoWindow({
                            content: contentString[c],
                            maxWidth: 300
                        });

                        switch (item.status[c]) {
                            case 'V':
                                img = 'green.png';
                                break;
                            case 'C':
                                img = 'yellow.png';
                                break;
                            default:
                                img = 'red.png';
                        }

                        //alert(item.status[c]+'---'+img);

                        //OBTENER LAS COORDENADAS DEL PUNTO
                        var posi = new google.maps.LatLng(item.cx[c], item.cy[c]);
                        //CARGAR LAS PROPIEDADES AL MARCADOR
                        var marca = new google.maps.Marker({
                            //idMarcador:item.IdPunto,
                            position:posi,
                            icon: 'img/'+img,
                            //zoom:15,
                            //titulo: item.Titulo,
                            cx:item.cx[c],//esas coordenadas vienen de la BD
                            cy:item.cy[c],//esas coordenadas vienen de la BD
                            draggable: false
                        });
                        // Add info window to marker
                        google.maps.event.addListener(marca, 'click', (function(marca, c) {
                            return function() {
                                infoWindow.setContent(contentString[c]);
                                infoWindow.open(mapa, marca);
                            }
                        })(marca, c));
                        //AGREGAR EL MARCADOR A LA VARIABLE MARCADORES_BD
                        // marcadores_bd.push(marca);
                        //UBICAR EL MARCADOR EN EL MAPA
                        markers.push(marca);
                        marca.setMap(mapa);
                        c++;
                    });
                });
            },
            beforeSend: function(){
            },
            complete: function(){
            }
        });
    }
</script>
<script  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA7FN4j43pO5hJesGiaTGDqShcxqzcZLZ8&force=lite"></script>
