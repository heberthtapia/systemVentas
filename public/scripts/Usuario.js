$(document).on("ready", init);

function init(){

	$('#tblUsuarios').dataTable({
	        dom: 'Bfrtip',
	        buttons: [
	            'copyHtml5',
	            'excelHtml5',
	            'csvHtml5',
	            'pdfHtml5'
	        ]
	    });

	ListadoUsuarios();
	ComboSucursal();
	$("#VerForm").hide();
	$("#txtRutaImgArt").hide();
	$("form#frmUsuarios").submit(SaveOrUpdate);

	$("#btnNuevo").click(VerForm);
	$("#btnBuscarTrabajador").click(AbrirModalEmpleado);

	$("#btnAgregarEmpleado").click(function(e){
		e.preventDefault();

		var opt = $("input[type=radio]:checked");
		$("#txtIdEmpleado").val(opt.val());
		$("#txtEmpleado").val(opt.attr("data-nombre") + " " + opt.attr("data-apellidos"));

		$("#modalListadoEmpleados").modal("hide");
	});

	function SaveOrUpdate(e){
		e.preventDefault();

		if ($("#txtIdEmpleado").val() != "") {
	        $.post("./ajax/UsuarioAjax.php?op=SaveOrUpdate", $(this).serialize(), function(r){
	            swal("Mensaje del Sistema", r, "success");
				ListadoUsuarios();
				Limpiar();
				OcultarForm();
	        });
	    } else {
	    	bootbox.alert("Debe elegir un trabajador");
	    }
	};

	function ComboSucursal(){
		$.post("./ajax/UsuarioAjax.php?op=listSucursal", function(r){
            $("#cboSucursal").html(r);
        });
	}

	function AbrirModalEmpleado(){
		$("#modalListadoEmpleados").modal("show");

		$.post("./ajax/UsuarioAjax.php?op=listEmpleado", function(r){
            $("#Trabajador").html(r);
            $('#tblTrabajadores').DataTable();
        });
	}

	function Limpiar(){
		$("#txtIdUsuario").val("");
	    $("#txtNombre").val("");
	    $("#txtIdEmpleado").val("");
	    $("#txtEmpleado").val("");
	    $("#chkMnuAlmacen").attr('checked', '');
	    $("#chkMnuCompras").attr('checked', '');
	    $("#chkMnuVentas").attr('checked', '');
	    $("#chkMnuMantenimiento").attr('checked', '');
	    $("#chkMnuSeguridad").attr('checked', '');
	    $("#chkConsultaCompras").attr('checked', '');
	    $("#chkConsultaVentas").attr('checked', '');
	    $("#chkMnuAdmin").attr('checked', '');
	    $("#chkMnuPerfil").attr('checked', '');
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
        Limpiar();
    }


}

function eliminarUsuario(id){
	bootbox.confirm("¿Esta Seguro de eliminar el Usuario?", function(result){
		if(result){
			$.post("./ajax/UsuarioAjax.php?op=delete", {id : id}, function(e){
                swal("Mensaje del Sistema", e, "success");
				ListadoUsuarios();
				Limpiar();

            });
		}

	})
}

function ListadoUsuarios(){
        var tabla = $('#tblUsuarios').dataTable(
		{   "aProcessing": true,
       		"aServerSide": true,
       		dom: 'Bfrtip',
	        buttons: [
	            //'copyHtml5',
	            'excelHtml5'
	            //'csvHtml5',
	            //'pdfHtml5'
	        ],
        	"aoColumns":[
        	     	{   "mDataProp": "0"},
                    {   "mDataProp": "1"},
                    {   "mDataProp": "2"},
                    {   "mDataProp": "3"},
                    {   "mDataProp": "4"},
                    {   "mDataProp": "5"}

        	],"ajax":
	        	{
	        		url: './ajax/UsuarioAjax.php?op=list',
					type : "get",
					dataType : "json",

					error: function(e){
				   		console.log(e.responseText);
					}
	        	},
	        "bDestroy": true

    	}).DataTable();

};

function cargarDataUsuario(idUsuario, idSucursal, idempleado, empleado, tipo_usuario, num_grupo, mnu_almacen, mnu_compras, mnu_ventas,
	 mnu_mantenimiento, mnu_seguridad, mnu_consulta_compras, mnu_consulta_ventas, mnu_admin, mnu_perfil){
		$("#VerForm").show();
		$("#btnNuevo").hide();
		$("#VerListado").hide();

		$("#txtIdUsuario").val(idUsuario);
	    $("#cboSucursal").val(idSucursal);
	    $("#txtIdEmpleado").val(idempleado);
	    $("#txtEmpleado").val(empleado);
	    $("#cboTipoUsuario").val(tipo_usuario);
	    $("#cboGrupo").val(num_grupo);

	    if (mnu_almacen == 1) {
	    	$("#chkMnuAlmacen").attr('checked', true);
	    } else {
	    	$("#chkMnuAlmacen").attr('checked', false);
	    }
	    if (mnu_compras == 1) {
	    	$("#chkMnuCompras").attr('checked', true);
	    } else {
	    	$("#chkMnuCompras").attr('checked', false);
	    }
	    if (mnu_ventas == 1) {
	    	$("#chkMnuVentas").attr('checked', true);
	    } else {
	    	$("#chkMnuVentas").attr('checked', false);
	    }
	    if (mnu_mantenimiento == 1) {
	    	$("#chkMnuMantenimiento").attr('checked', true);
	    } else {
	    	$("#chkMnuMantenimiento").attr('checked', false);
	    }
	    if (mnu_seguridad == 1) {
	    	$("#chkMnuSeguridad").attr('checked', true);
	    } else {
	    	$("#chkMnuSeguridad").attr('checked', false);
	    }
	    if (mnu_consulta_compras == 1) {
	    	$("#chkConsultaCompras").attr('checked', true);
	    } else {
	    	$("#chkConsultaCompras").attr('checked', false);
	    }
	    if (mnu_consulta_ventas == 1) {
	    	$("#chkConsultaVentas").attr('checked', true);
	    } else {
	    	$("#chkConsultaVentas").attr('checked', false);
	    }
	    if (mnu_admin == 1) {
	    	$("#chkMnuAdmin").attr('checked', true);
	    } else {
	    	$("#chkMnuAdmin").attr('checked', false);
	    }
	    if (mnu_perfil == 1) {
	    	$("#chkMnuPerfil").attr('checked', true);
	    } else {
	    	$("#chkMnuPerfil").attr('checked', false);
	    }
}

function verPerfil(id){
	$.post("./ajax/UsuarioAjax.php?op=verPerfil", {id : id}, function(e){
        /*swal("Mensaje del Sistema", e, "success");
		ListadoUsuarios();
		Limpiar();*/
		if (e == 1)
			$('.alert').show();
		else
			$('.alert').hide();
		//alert(e);
    });
}
