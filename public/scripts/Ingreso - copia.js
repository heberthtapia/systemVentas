$(document).on("ready", init);

var objinit = new init();

var bandera = 1;

elementos = new Array();

elementosReg = new Array(); 

function init() {

    sessionStorage.idSucursal = $("#txtIdSucursal").val();
    sessionStorage.Sucursal = $("#txtSucursal").val();

    $('#tblIngresos').dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });
    
    var tabla = $('#tblArticulosIng').dataTable({
            "iDisplayLength": 4,
            "aLengthMenu": [2, 4]
        });

        

    ComboTipoDoc();
    ListadoIngresos();
    GetImpuesto();

    $("#VerForm").hide();

   // $("#btnAgregar").click(AgregarDetalleIngreso)
   // $("#cboTipoComprobanteIng").change(VerNumSerie);
    $("#btnBuscarProveedor").click(AbrirModalProveedor);
    $("#btnBuscarSucursal").click(AbrirModalSucursal);
    $("#btnBuscarSucursal2").click(AbrirModalSucursal);
    $("#btnBuscarArt").click(AbrirModalArticulo);

    $("#frmIngresos").submit(GuardarIngreso);

    $("#btnAgregarProveedor").click(function(e){
		e.preventDefault();

		var opt = $("input[type=radio]:checked");
		$("#txtIdProveedor").val(opt.val());
		$("#txtProveedor").val(opt.attr("data-nombre"));

		$("#modalListadoProveedor").modal("hide");
	});

    $("#btnAgregarSucursal").click(function(e){
        e.preventDefault();

        var opt = $("input[type=radio]:checked");
        $("#txtIdSucursal").val(opt.val());
        $("#txtSucursal").val(opt.attr("data-nombre"));

        $("#txtIdSucursal2").val(opt.val());
        $("#txtSucursal2").val(opt.attr("data-nombre"));

        $("#modalListadoSucursal").modal("hide");
    });

	$("#btnAgregarArt").click(function(e){
		e.preventDefault();

        var opt =  tabla.$("input[name='optArtBusqueda[]']:checked", {"page": "all"});

		opt.each(function() {
			AgregarDetalle($(this).val(), $(this).attr("data-nombre"), "", "", "", "1", "1", "1", "1", "1");
		})
		
		$("#modalListadoArticulos").modal("hide");
	});

    $("#txtStockIng").keypress(function total_suma(){
        var suma = 0;
        alert("s");
        $("#txtTotal").val("23");
    });



	function Agregar(){
        var idArt = document.getElementsByName("txtIdArticulo");
        var cod = document.getElementsByName("txtCodgo");
        var serie = document.getElementsByName("txtSeries");
        var desc = document.getElementsByName("txtDescripcion");
        var stock_ing = document.getElementsByName("txtStockIng");
        var prec_comp = document.getElementsByName("txtPrecioComp");
        var prec_ventaD = document.getElementsByName("txtPrecioVentD");
        var prec_ventaP = document.getElementsByName("txtPrecioVentaP");
        /*
		var idArt = document.frmIngresos.elements["txtIdArticulo[]"];
		var cod = document.frmIngresos.elements["txtCodgo[]"];
        var serie = document.frmIngresos.elements["txtSerie[]"];
        var desc = document.frmIngresos.elements["txtDescripcion[]"];
        var stock_ing = document.frmIngresos.elements["txtStockIng[]"];
        //var stock_act = document.frmIngresos.elements["txtStockAct[]"];
        var prec_comp = document.frmIngresos.elements["txtPrecioComp[]"];
        var prec_ventaD = document.frmIngresos.elements["txtPrecioVentD[]"];
        var prec_ventaP = document.frmIngresos.elements["txtPrecioVentaP[]"];
        */
        for (var i = 0; i < stock_ing.length; i++) {
            if (stock_ing[i].value !== "") {
                AgregarDetalleRegistrar(idArt[i].value, cod[i].value, serie[i].value, desc[i].value, stock_ing[i].value, 1,
                	 prec_comp[i].value, prec_ventaD[i].value, prec_ventaP[i].value);
            }
        }

	}
    
	function GuardarIngreso(e){
		e.preventDefault();

        if ($("#txtIdProveedor").val() != "") {
            if ($("#cboTipoComprobanteIng").val() != "") {
                if (elementos.length > 0) {
                    Agregar();
                    var detalle =  JSON.parse(consultarReg());

                    var data = { 
                        idUsuario : $("#txtIdUsuario").val(),
                        idSucursal : $("#txtIdSucursal").val(),
                        idproveedor : $("#txtIdProveedor").val(),
                        tipo_comprobante : $("#cboTipoComprobanteIng").val(),
                        serie_comprobante : $("#txtSerie").val(),
                        num_comprobante : $("#txtNumero").val(),
                        impuesto : $("#txtImpuesto").val(),
                        total : $("#txtTotal").val(),
                        detalle : detalle
                    };
                    
                    $.post("./ajax/IngresoAjax.php?op=Save", data, function(r){
                        swal("Mensaje del Sistema", r, "success");
                        //alert(r);
                        Limpiar();
                        OcultarForm();
                        ListadoIngresos();
                    });
                } else {
                    bootbox.alert("Debe agregar articulos al detalle");
                }
            } else {
                bootbox.alert("Debe seleccionar un tipo de comprobante");
            }
        } else {
            bootbox.alert("Debe elegir un Proveedor");
        }
	}

    function Limpiar(){
        $("#txtIdSucursal").val(sessionStorage.idSucursal);
        $("#txtSucursal").val(sessionStorage.Sucursal);
        $("#txtIdProveedor").val("");
        $("#txtProveedor").val("");
       // $("#cboTipoComprobanteIng").val("");
        $("#txtSerie").val("");
        $("#txtNumero").val("");
        $("#txtSubTotal").val("");
        $("#txtIgv").val("");
        $("#txtTotal").val("");
        elementosReg.length = 0;
        elementos.length = 0;
        $("#tblDetalleIngreso tbody").html("");
    }

    function ListadoIngresos(){ 
        var tabla = $('#tblIngresos').dataTable(
        {   "aProcessing": true,
            "aServerSide": true,
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            "aoColumns":[
                    {   "mDataProp": "0"},
                    {   "mDataProp": "1"},
                    {   "mDataProp": "2"},
                    {   "mDataProp": "3"},
                    {   "mDataProp": "4"},
                    {   "mDataProp": "5"},
                    {   "mDataProp": "6"},
                    {   "mDataProp": "7"},
                    {   "mDataProp": "8"},
                    {   "mDataProp": "9"}

            ],"ajax": 
                {
                    url: './ajax/IngresoAjax.php?op=list',
                    type : "get",
                    dataType : "json",
                    
                    error: function(e){
                        console.log(e.responseText);    
                    }
                },
            "bDestroy": true

        }).DataTable();


    };
    function ComboTipoDoc() {

        $.get("./ajax/IngresoAjax.php?op=listTipoDoc", function(r) {
                $("#cboTipoComprobanteIng").html(r);
            
        })
    }

    function GetImpuesto() {

        $.getJSON("./ajax/GlobalAjax.php?op=GetImpuesto", function(r) {
                $("#txtImpuesto").val(r.porcentaje_impuesto);
                $("#SubTotal").html(r.simbolo_moneda + " Sub Total:");
                $("#IGV").html(r.simbolo_moneda + " IGV %:");
                $("#Total").html(r.simbolo_moneda + " Total:");                
        })
    }

    function VerForm(){
        $("#VerForm").show();
        $("#btnNuevo").hide();
        $("#VerListado").hide();
    }

    function OcultarForm(){
        $("#VerForm").hide();
        $("#btnNuevo").show();
        $("#VerListado").show();
    }

    function AbrirModalProveedor(){
		$("#modalListadoProveedor").modal("show");

		$.post("./ajax/IngresoAjax.php?op=listProveedor", function(r){
            $("#Proveedor").html(r);
            $("#tblProveedores").DataTable();
        });
	}

    function AbrirModalSucursal(){
        $("#modalListadoSucursal").modal("show");

        $.post("./ajax/IngresoAjax.php?op=listSucursal", function(r){
            $("#Sucursales").html(r);
            $("#tblSucursales").DataTable();
        });
    }

	function AbrirModalArticulo(){
		
        $("#modalListadoArticulos").modal("show");
        var tabla = $('#tblArticulosIng').dataTable(
            {   "aProcessing": true,
                "aServerSide": true,
                "aoColumns":[
                        {   "mDataProp": "0"},
                        {   "mDataProp": "1"},
                        {   "mDataProp": "2"},
                        {   "mDataProp": "3"},
                        {   "mDataProp": "4"},
                        {   "mDataProp": "5"},
                        {   "mDataProp": "6"}

                ],"ajax": 
                    {
                        url: './ajax/ArticuloAjax.php?op=listArtElegir',
                        type : "get",
                        dataType : "json",
                        
                        error: function(e){
                            console.log(e.responseText);    
                        }
                    },
                "bDestroy": true

            }).DataTable();
	}

    /*

    function GuardarIngreso(e) {
        e.preventDefault();
        var detalle = JSON.parse(consultar());

        var data;

        if(bandera === 1){
            data = {
                idCategoria: $("#cboCategoria").val(),
                titulo: $("#txtTitulo").val(),
                descripcion: $("#txtDescripcion").val(),
                slide: $("#cboSlide").val(),
                imagen_principal: $("#txtRutaImagenPrinc").val(),
                detalle: detalle
            };
        } else {
            data = {
                idIngreso : $("#txtIdIngreso").val(),
                idCategoria: $("#cboCategoria").val(),
                titulo: $("#txtTitulo").val(),
                descripcion: $("#txtDescripcion").val(),
                slide: $("#cboSlide").val(),
                imagen_principal: $("#txtRutaImagenPrinc").val()
            };
        }
        
        $.post("./ajax/IngresoAjax.php?op=Save", data,  function(r) {
            alert(r);
            location.reload();
        });
        
    }

    */

    function AgregarDetalle(idart, nombre, codigo, serie, descripcion, stock_ing, stock_act, p_compra, p_ventaD, p_ventaP) {
        var detalles = new Array(idart, nombre, codigo, serie, descripcion, stock_ing, stock_act, p_compra, p_ventaD, p_ventaP);
        elementos.push(detalles);
        ConsultarDetalles();
    }

    function AgregarDetalleRegistrar(idart, codigo, serie, descripcion, stock_ing, stock_act, p_compra, p_ventaD, p_ventaP) {
        var detallesReg = new Array(idart, codigo, serie, descripcion, stock_ing, stock_act, p_compra, p_ventaD, p_ventaP);
        elementosReg.push(detallesReg);
    }

    

    function consultar() {
        return JSON.stringify(elementos);
    }

    function consultarReg() {
        return JSON.stringify(elementosReg);
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

    this.consultarReg = function(){
        /*
        for(i=0;i<this.elementos.length;i++){
            for(j=0;j<this.this.elementos[i].length;j++){
                console.log("Elemento: "+this.elementos[i][j]);
            }
        }
        */
        return JSON.stringify(elementosReg);
    };

};

function eliminarDetalle(ele){
        console.log(ele);
        objinit.eliminar(ele);
        ConsultarDetalles();
        calcularIgv();
        calcularSubTotal();
    }

    function AgregarFila(pos){
        //<td> <input class='form-control' type='hidden' name='txtIdArticulo' id='txtIdArticulo[]' value='1' /></td><td><input class='form-control' type='text' name='txtCodgo' id='txtCodgo[]' value='1' /></td><td><input class='form-control' type='text' name='txtSerie' id='txtSerie[]'  value='1' /></td><td><input class='form-control' type='text' name='txtDescripcion' id='txtDescripcion[]' value='1' /></td><td><input class='form-control' type='text' onkeypress='return justNumbers(event);' name='txtStockIng' id='txtStockIng[]'   value='1' onkeyup='calcularTotal(" + pos + ");' required /></td><td><input class='form-control' type='text' onkeypress='return onKeyDecimal(event,this);' name='txtPrecioComp' id='txtPrecioComp[]'  value='1' onkeyup='calcularTotal(" + pos + ");' required /></td><td><input class='form-control' type='text' onkeypress='return onKeyDecimal(event,this);' name='txtPrecioVentD' id='txtPrecioVentD[]'  value='1' required /></td><td><input class='form-control' type='text' onkeypress='return onKeyDecimal(event,this);' name='txtPrecioVentaP' id='txtPrecioVentaP[]' value='1' required /></td><td WIDTH='100'><button type='button' data-toggle='tooltip' title='Quitar Articulo del detalle' onclick='eliminarDetalle(" + pos + ")' class='btn btn-danger'><i class='fa fa-remove' ></i> </button> <button type='button' data-toggle='tooltip' title='Pulse aqui para agregar mas filas de este articulo' onclick='AgregarFila(" + pos + ")' class='btn btn-success'><i class='fa fa-plus' ></i> </button></td>
        $("#tblDetalleIngreso tbody tr:eq(" + pos + ")").clone().appendTo("#tblDetalleIngreso tbody");
       //$("#tblDetalleIngreso tbody tr:last").after("<tr><td> <input class='form-control' type='hidden' name='txtIdArticulo' id='txtIdArticulo[]' value='1' /></td><td><input class='form-control' type='text' name='txtCodgo' id='txtCodgo[]' value='1' /></td><td><input class='form-control' type='text' name='txtSerie' id='txtSerie[]'  value='1' /></td><td><input class='form-control' type='text' name='txtDescripcion' id='txtDescripcion[]' value='1' /></td><td><input class='form-control' type='text' onkeypress='return justNumbers(event);' name='txtStockIng' id='txtStockIng[]'   value='1' onkeyup='calcularTotal(" + pos + ");' required /></td><td><input class='form-control' type='text' onkeypress='return onKeyDecimal(event,this);' name='txtPrecioComp' id='txtPrecioComp[]'  value='1' onkeyup='calcularTotal(" + pos + ");' required /></td><td><input class='form-control' type='text' onkeypress='return onKeyDecimal(event,this);' name='txtPrecioVentD' id='txtPrecioVentD[]'  value='1' required /></td><td><input class='form-control' type='text' onkeypress='return onKeyDecimal(event,this);' name='txtPrecioVentaP' id='txtPrecioVentaP[]' value='1' required /></td><td WIDTH='100'><button type='button' data-toggle='tooltip' title='Quitar Articulo del detalle' onclick='eliminarDetalle(" + pos + ")' class='btn btn-danger'><i class='fa fa-remove' ></i> </button> <button type='button' data-toggle='tooltip' title='Pulse aqui para agregar mas filas de este articulo' onclick='AgregarFila(" + pos + ")' class='btn btn-success'><i class='fa fa-plus' ></i> </button></td></tr>");
    }

function ConsultarDetalles() {
        $("table#tblDetalleIngreso tbody").html("");
        var data = JSON.parse(objinit.consultar());
        
        for (var pos in data) {
            $("table#tblDetalleIngreso").append("<tr><td>" + data[pos][1] + " <input class='form-control' type='hidden' name='txtIdArticulo' id='txtIdArticulo[]' value='" + data[pos][0] + "' /></td><td><input class='form-control' type='text' onkeyup='Modificar(" + pos + ");' name='txtCodgo' id='txtCodgo[]' value='" + data[pos][2] + "' /></td><td><input class='form-control' type='text' name='txtSeries' onkeyup='Modificar(" + pos + ");' id='txtSeries[]'  value='" + data[pos][3] + "' /></td><td><input class='form-control' type='text' name='txtDescripcion' onkeyup='Modificar(" + pos + ");' id='txtDescripcion[]' value='" + data[pos][4] + "' /></td><td><input class='form-control' type='text' onkeypress='return justNumbers(event);' name='txtStockIng' id='txtStockIng[]'   value='" + data[pos][5] + "' onkeyup='calcularTotal(" + pos + ");' required /></td><td><input class='form-control' type='text' onkeypress='return onKeyDecimal(event,this);' name='txtPrecioComp' id='txtPrecioComp[]'  value='" + data[pos][6] + "' onkeyup='calcularTotal(" + pos + ");' required /></td><td><input class='form-control' type='text' onkeyup='Modificar(" + pos + ");' onkeypress='return onKeyDecimal(event,this);' name='txtPrecioVentD' id='txtPrecioVentD[]'  value='" + data[pos][7] + "' required /></td><td><input class='form-control' type='text' onkeypress='return onKeyDecimal(event,this);' onkeyup='Modificar(" + pos + ");' name='txtPrecioVentaP' id='txtPrecioVentaP[]' value='" + data[pos][8] + "' required /></td><td WIDTH='100'><button type='button' data-toggle='tooltip' title='Quitar Articulo del detalle' onclick='eliminarDetalle(" + pos + ")' class='btn btn-danger'><i class='fa fa-remove' ></i> </button> <button type='button' data-toggle='tooltip' title='Pulse aqui para agregar mas filas de este articulo' onclick='AgregarDetalle(" + data[pos][0] + ",\"" + data[pos][1] +"\",\"" + data[pos][2] + "\",\"" + "" + "\",\"" + data[pos][4] + "\",\"" + data[pos][5] + "\",\"" + data[pos][6] + "\",\"" + data[pos][7] + "\",\"" + data[pos][8] + "\",\"" + data[pos][9] + "\",\"" + pos + "\")' class='btn btn-success'><i class='fa fa-plus' ></i> </button></td></tr>");
        }
        calcularIgv();
        calcularSubTotal();
        calcularTotal();
    }

function ListadoIngresos(){ 
        var tabla = $('#tblIngresos').dataTable(
        {   "aProcessing": true,
            "aServerSide": true,
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            "aoColumns":[
                    {   "mDataProp": "0"},
                    {   "mDataProp": "1"},
                    {   "mDataProp": "2"},
                    {   "mDataProp": "3"},
                    {   "mDataProp": "4"},
                    {   "mDataProp": "5"},
                    {   "mDataProp": "6"},
                    {   "mDataProp": "7"},
                    {   "mDataProp": "8"},
                    {   "mDataProp": "9"},

            ],"ajax": 
                {
                    url: './ajax/IngresoAjax.php?op=list',
                    type : "get",
                    dataType : "json",
                    
                    error: function(e){
                        console.log(e.responseText);    
                    }
                },
            "bDestroy": true

        }).DataTable();
    };

    function cargarDataIngreso(id, serie, numero, impuesto, total, idIngreso, Proveedor, tipo_comprobante){
        bandera = 2;
        $("#VerForm").show();
        $("#btnNuevo").hide();
        $("#VerListado").hide();

        $("#btnRegistrarIng").hide();
        //$("#btnBuscarProveedor").hide();
        $("#btnVerArticulos").hide();

       // $("#VerMod").show();
        $("#txtProveedor").val(Proveedor);
        $("#txtImpuesto").val(impuesto);
        $("#cboTipoComprobanteIng").html("<option>" + tipo_comprobante + "</option>");
        $("#txtSerie").val(serie);
        $("#txtTotal").val(Math.round(total*100)/100);
        var totalIgv=total * impuesto/(100+parseInt(impuesto));
        $("#txtIgv").val(Math.round(totalIgv*100)/100);
        var subTotal=total - (total * impuesto/(100+parseInt(impuesto)));
        $("#txtSubTotal").val(Math.round(subTotal*100)/100);
        $("#txtNumero").val(numero);
        CargarDetalleIngreso(idIngreso);
        //$('button[type="submit"]').attr('disabled','disabled');
        //$("#btnBuscarArt").prop("disabled", true);
        $("#btnBuscarProveedor").prop("disabled", true);
        $("#btnBuscarSucursal").prop("disabled", true);

        $("#cboFechaDesde").hide();
        $("#cboFechaHasta").hide();
        $("#btnBuscarSucursal2").hide();
        $("#txtSucursal2").hide();
        $("#lblSucursal2").hide();
        $("#lblDesde").hide();
        $("#lblHasta").hide();
    }

    function cancelarIngreso(idIngreso){
        bootbox.confirm("¿Esta Seguro de Anular el ingreso?", function(result){
            if(result){
                $.post("./ajax/IngresoAjax.php?op=CambiarEstado", {idIngreso : idIngreso}, function(e){
                    
                    swal("Mensaje del Sistema", e, "success");
                    ListadoIngresos();
                    
                });
            }
            
        })
    }

    function CargarDetalleIngreso(idIngreso) {
        $('th:nth-child(9)').hide();
        $.post("./ajax/IngresoAjax.php?op=GetDetalleArticulo", {idIngreso: idIngreso}, function(r) {
                $("table#tblDetalleIngreso tbody").html(r);
            
        })
    }

    function calcularIgv(){
        var suma = 0;

        var data = JSON.parse(objinit.consultar());
        
        for (var pos in data) {
            suma += parseFloat(data[pos][5] * data[pos][6]);
        }
        var totalIgv=suma * parseInt($("#txtImpuesto").val()) / (100+parseInt($("#txtImpuesto").val()));
        $("#txtIgv").val(Math.round(totalIgv*100)/100);
    }

    function calcularSubTotal(){
        var suma = 0;
        var data = JSON.parse(objinit.consultar());
        for (var pos in data) {
            suma += parseFloat(data[pos][5] * data[pos][6]);
        }
        var subTotal=suma - (suma * parseInt($("#txtImpuesto").val()) / (100+parseInt($("#txtImpuesto").val())));
        $("#txtSubTotal").val(Math.round(subTotal*100)/100);
    }

    function calcularTotal(posi){
        
        var suma = 0;
        var data = JSON.parse(objinit.consultar());
        for (var pos in data) {
            suma += parseFloat(data[pos][5] * data[pos][6]);
        }
        calcularIgv();
        calcularSubTotal();
        $("#txtTotal").val(Math.round(suma*100)/100);
        if(posi != null){
          Modificar(posi);
            //alert(pos);
        }
    }

    function Modificar(pos){
        var idArt = document.getElementsByName("txtIdArticulo");
        var cod = document.getElementsByName("txtCodgo");
        var serie = document.getElementsByName("txtSeries");
        var desc = document.getElementsByName("txtDescripcion");
        var stock_ing = document.getElementsByName("txtStockIng");
        var prec_comp = document.getElementsByName("txtPrecioComp");
        var prec_ventaD = document.getElementsByName("txtPrecioVentD");
        var prec_ventaP = document.getElementsByName("txtPrecioVentaP");
        
        elementos[pos][2] = cod[pos].value;
        elementos[pos][3] = serie[pos].value;
        elementos[pos][4] = desc[pos].value;
        elementos[pos][5] = stock_ing[pos].value;
        elementos[pos][6] = prec_comp[pos].value;
        elementos[pos][7] = prec_ventaD[pos].value;
        elementos[pos][8] = prec_ventaP[pos].value;
       // alert(elementos[pos][3] + " " + serie[pos].value);
        calcularIgv();
        calcularSubTotal();
        calcularTotal();
        //ConsultarDetalles();
    }

    function justNumbers(e) {
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8 || keynum == 48))
            return true;
        if (keynum <= 47 || keynum >= 58) return false;
            return /\d/.test(String.fromCharCode(keynum));
    }

    function onKeyDecimal(e, field) {
        key = e.keyCode ? e.keyCode : e.which
        if (key == 8) return true
        if (key > 47 && key < 58) {
          if (field.value == "") return true
          regexp = /.[0-9]{5}$/
          return !(regexp.test(field.value))
        }
        if (key == 46) {
          if (field.value == "") return false
          regexp = /^[0-9]+$/
          return regexp.test(field.value)
        }
        return false
    }

    function AgregarDetalles(id1, id2, id3, id4, id5, id6, id7, id8, id9, id10){
        alert(id1 + " "+ id2 + " " + id3 + " " + id4 + " "+ id5 + " "+ id6 + " " + id7 + " " + id8 + " " + id9 + " " + id10);
    }

    function AgregarDetalle(idart, nombre, codigo, serie, descripcion, stock_ing, stock_act, p_compra, p_ventaD, p_ventaP, pos) {
        var cant = prompt("¿Cuantas series necesita?", "");
        for (var i = 1; i <= cant; i++) {
            var serie = prompt("Serie " + i + ":", "");
            if (serie != null) {
                var detalles = new Array(idart, nombre, elementos[pos][2], serie, elementos[pos][4], elementos[pos][5], 
                          elementos[pos][6], elementos[pos][7], elementos[pos][8], elementos[pos][9]);
                          elementos.push(detalles);
                        ConsultarDetalles();
            }
        }
        
    }

    function AgregarDetalleCarrito(idart, nombre, codigo, serie, descripcion, stock_ing, stock_act, p_compra, p_ventaD, p_ventaP) {
        var detalles = new Array(idart, nombre, codigo, serie, descripcion, stock_ing, stock_act, p_compra, p_ventaD, p_ventaP);
        elementos.push(detalles);
        ConsultarDetalles();
    }

    function Agregar(id, art){
        AgregarDetalleCarrito(id, art, "", "", "", "1", "1", "1", "1", "1");
    }