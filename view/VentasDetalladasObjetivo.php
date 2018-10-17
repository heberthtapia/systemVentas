<script>
function despliega(p, div, id) {
    $.ajax({
        url: 'view/'+p,
        type: 'post',
        cache: false,
        data: 'id=' + id,
        beforeSend: function(data) {
            $("#" + div).html('<div id="load" align="center" class="alert alert-success" role="alert"><p>Cargando contenido. Por favor, espere ...</p></div>');
        },
        success: function(data) {
            $("#" + div).fadeOut(1000, function() {
                $("#" + div).html(data).fadeIn(2000);
            });
            //$("#"+div).html(data);
        }
    });
}

$(document).ready(function(){
    //despliega('listVentasObj.php', 'VerListado', '0');

    $("#nameEmp").change(function(){
        id = $("#idEmp").val();
        despliega('listVentasObj.php', 'VerListado', id);
    });
})
</script>

<div class="panel panel-default">
  <div class="panel-heading">
      <div class="box-header with-border">
        <h3 class="box-title" >Ventas Detalladas por Articulo</h3>
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
                <?php
                            if ($_SESSION["superadmin"] == "S") {
                        ?>
                        <div class="col-lg-4 left">
                            <label for="inputMarca">Sucursal:</label>
                            <div class="form-group has-success">

                                <div class="input-group">

                                    <input id="txtSucursal" type="text" value="<?php echo $_SESSION["sucursal"] ?>" class="form-control" name="txtSucursal" required="" placeholder="Seleccione una Sucursal" autofocus="" disabled/>
                                    <span class="input-group-btn" >
                                          <button type="button" class="btn btn-success" id="btnBuscarSucursal" ><i class='fa fa-search'></i>
                                                        Buscar
                                          </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 left">
                    <label>Empleado:</label>
                    <div class="form-group  has-success">
                        <input type="text" id="nameEmp" name="nameEmp" value="" class="form-control" placeholder="Nombre Empleado">
                        <input type="hidden" id="idEmp" name="idEmp" value="" placeholder="">
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 left">
                    <div class="form-group has-success">
                        <label>Categoria:</label>
                        <select id="cboCategoriaObj" name="cboCategoria" class="form-control" required="" >

                        </select>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 left">
                    <div class="form-group has-success">
                        <label for="inputMarca">Desde :</label>
                        <input id="txtIdSucursal" type="hidden" value="<?php echo $_SESSION["idsucursal"] ?>" maxlength="50" class="form-control" name="txtIdSucursal" required="" placeholder="" autofocus="" />
                        <input id="cboFechaHoyDetVentObj" type="date" value=""  maxlength="50" class="form-control" name="cboFechaHoyDetVentObj" required="" />
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 left">
                    <div class="form-group has-success">
                        <label for="inputMarca">Hasta :</label>
                        <input id="cboFechaHDetVentObj" type="date" value=""  maxlength="50" class="form-control" name="cboFechaHDetVentObj" required="" />
                    </div>
                </div>

            </div>
        </form>
        <br>
            <div id="VerListado" class="table-responsive">
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
    $('#liVentasDetalladasObjetivo').addClass("active");

    //$("#txtNombre").numeric();


  $( function() {
    function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }

    $( "#nameEmp" )
      // don't navigate away from the field on tab when selecting an item
      .on( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).autocomplete( "instance" ).menu.active ) {
          event.preventDefault();
        }
      })
      .autocomplete({
        source: function( request, response ) {
          $.getJSON( "view/search.php", {
            term: extractLast( request.term )
          }, response );
        },
        search: function() {
          // custom minLength
          var term = extractLast( this.value );
          if ( term.length < 2 ) {
            return false;
          }
        },
        focus: function() {
          // prevent value inserted on focus
          return false;
        },
        select: function( event, ui ) {
          var terms = split( this.value );
          // remove the current input
          terms.pop();
          // add the selected item
          terms.push( ui.item.value );
          // add placeholder to get the comma-and-space at the end
          terms.push( "" );
          this.value = terms.join( "" );
          // agrego el id al input
          $('#idEmp').val(ui.item.id);
          return false;
        }
      });
  } );
  </script>

  <style>
   .ui-autocomplete-loading {
        background: white url('img/ui-anim_basic_16x16.gif') right center no-repeat;
    }
  </style>
