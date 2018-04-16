$(document).on("ready", init);

var objinit = new init();

var bandera = 1;

function init() {

    elementos = new Array(); 

    $('#tblDocSucursal').dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });    

    ComboTipoDoc();
    ListadoDocSuc();

    $("#VerForm").hide();

    $("#btnAgregarSN").click(AgregarDetalleDocumentoSucursal);
    $("#btnNuevoDocSuc").click(VerForm);
   // $("#cboTipoDocumento").change(VerNumSerie);

    $("form#frmDocSuc").submit(GuardarDocSucursal);

    function AgregarDetalleDocumentoSucursal(){
        var idtipo_doc = $("#cboTipoDocumento").val(),
            tipo_doc = $("#cboTipoDocumento option:selected").html(),
            idsucursal = $("#txtIdSucursal").val(),
            serie = $("#txtSerie").val(),
            numero = $("#txtNumero").val();

        if(idtipo_doc != "" && idsucursal != "" && serie !="" && numero !=""){
            AgregarDetalleDocSuc(idsucursal, idtipo_doc, tipo_doc, serie, numero);
        }
        ConsultarDetallesDocSuc();
        $("#txtSerie").val("");
        $("#txtNumero").val("");
    }


    function GuardarDocSucursal(e){
        e.preventDefault();

        var detalle =  JSON.parse(consultar());

        var data = [];

        if (bandera == 1) {
            data  = { 
                idDocSucursal : $("#txtIdDocSucursal").val(),
                detalle : detalle
            };
        } else {
            data = {
                idDocSucursal : $("#txtIdDocSucursal").val(),
                idSucursal: $("#txtIdSucursal").val(),
                idTipoDoc: $("#cboTipoDocumento").val(),
                serie: $("#txtSerie").val(),
                numero : $("#txtNumero").val()
            };
        }
        
        $.post("./ajax/DocSucursalAjax.php?op=Save", data, function(r){
            swal("Mensaje del Sistema", r, "success");
            ListadoDocSuc();
            OcultarForm();
        });

    }

    function ListadoDocSuc(){ 
        var tabla = $('#tblDocSucursal').dataTable();
        $.ajax({
            url: './ajax/DocSucursalAjax.php?op=list',
            dataType: 'json',
            success: function(s){
            //console.log(s);
                    tabla.fnClearTable();
                        for(var i = 0; i < s.length; i++) {
                         tabla.fnAddData([
                                    s[i][0],
                                    s[i][1],
                                    s[i][2],
                                    s[i][3],
                                    s[i][4],
                                    s[i][5],
                                    s[i][6],
                                    s[i][7]
                                      ]);                                       
                        } // End For
                                        
            },
            error: function(e){
               console.log(e.responseText); 
            }
        });
    };

    function ComboTipoDoc() {

        $.get("./ajax/DocSucursalAjax.php?op=listTipoDoc", function(r) {
                $("#cboTipoDocumento").html(r);
            
        })
    }

    function VerForm(){
        bandera = 1;
        $("#VerForm").show();
        $("#btnNuevoDocSuc").hide();
        $("#VerListado").hide();
        $("#txtIdDocSucursal").val("");
        $("#cboTipoDocumento").val("");
        $("#txtSerie").val("");
        $("#txtNumero").val("");
        $("#btnAgregarSN").show();
        $("#tblDetalleDoc").show();
    }

    function OcultarForm(){
        $("#VerForm").hide();
        $("#btnNuevoDocSuc").show();
        $("#VerListado").show();
    }


    function AgregarDetalleDocSuc(idsucursal, idtipo_doc, tipo_doc, serie, numero) {
        var detalles = new Array(idsucursal, idtipo_doc, tipo_doc, serie, numero);
        elementos.push(detalles);
        ConsultarDetallesDocSuc();
    }    

    function consultar() {
        return JSON.stringify(elementos);
    }

    this.eliminar = function(pos){
        //var pos = elementos[].indexOf( 'c' );
        console.log(pos);
        
        pos > -1 && elementos.splice(parseInt(pos),1);
        console.log(elementos);
        
        //this.elementos.splice(pos, 1);
        //console.log(this.elementos);
    };

    this.consultar = function(){
        /*
        for(i=0;i<this.elementos.length;i++){
            for(j=0;j<this.this.elementos[i].length;j++){
                console.log("Elemento: "+this.elementos[i][j]);
            }
        }
        */
        return JSON.stringify(elementos);
    };

};

function eliminarDetalleDocSuc(ele){
        console.log(ele);
        objinit.eliminar(ele);
        ConsultarDetallesDocSuc();
    }

    function ListadoDocSuc(){ 
        var tabla = $('#tblDocSucursal').dataTable();
        $.ajax({
            url: './ajax/DocSucursalAjax.php?op=list',
            dataType: 'json',
            success: function(s){
            //console.log(s);
                    tabla.fnClearTable();
                        for(var i = 0; i < s.length; i++) {
                         tabla.fnAddData([
                                    s[i][0],
                                    s[i][1],
                                    s[i][2],
                                    s[i][3],
                                    s[i][4],
                                    s[i][5],
                                    s[i][6],
                                    s[i][7]
                                      ]);                                       
                        } // End For
                                        
            },
            error: function(e){
               console.log(e.responseText); 
            }
        });
    };

function ConsultarDetallesDocSuc() {
        $("table#tblDetalleDoc tbody").html("");
        var data = JSON.parse(objinit.consultar());
        
        for (var pos in data) {
            $("table#tblDetalleDoc").append("<tr><td>"+data[pos][2]+"</td><td>"+data[pos][3]+"</td><td>"+data[pos][4]+"</td><td><button type='button' onclick='eliminarDetalleDocSuc(" + pos + ")' class='btn btn-danger'><i class='fa fa-remove' ></i> </button></td></tr>");
        }
        //calcularIgvPed();
        //calcularSubTotalPed();
        //calcularTotalPed();
    }

    function cargarDataDocSucursal(iddetalle_documento_sucursal, idtipo_documento, ultima_serie, ultimo_numero){
        bandera = 2;
        $("#VerForm").show();
        $("#btnNuevoDocSuc").hide();
        $("#VerListado").hide();
       // $("#VerMod").show();
        $("#txtIdDocSucursal").val(iddetalle_documento_sucursal);
        $("#cboTipoDocumento").val(idtipo_documento);
        $("#txtSerie").val(ultima_serie);
        $("#txtNumero").val(ultimo_numero);
        $("#btnAgregarSN").hide();
        $("#tblDetalleDoc").hide();
    }

    function eliminarDocSucursal(idDocSucursal){
        bootbox.confirm("¿Esta Seguro de Eliminar el registro?", function(result){
            if(result){
                $.post("./ajax/DocSucursalAjax.php?op=delete", {idDocSucursal : idDocSucursal}, function(e){
                    
                    swal("Mensaje del Sistema", e, "success");
                    ListadoDocSuc();
                    
                });
            }
            
        })
    }

/*
    function Modificar(pos){
        var idDetIng = document.frmDocSucursals.elements["txtIdDetIng[]"];
        var pvd = document.frmDocSucursals.elements["txtPrecioVentPed[]"];
        var cantPed = document.frmDocSucursals.elements["txtCantidaPed[]"];
        var descPed = document.frmDocSucursals.elements["txtDescuentoPed[]"];
       // alert(pos);
       
        elementos[pos][0] = idDetIng[pos].value;
        elementos[pos][2] = pvd[pos].value;
        elementos[pos][3] = cantPed[pos].value;
        elementos[pos][4] = descPed[pos].value;
        ConsultarDetalles();
    }

    */