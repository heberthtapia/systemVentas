$(document).on("ready", init);// Inciamos el jquery

function init(){

	/*$('#tblCliente').dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });*/

	listaCliente();
	//ListadoCliente();// Ni bien carga la pagina que cargue el metodo
	ComboTipo_Documento();
	$("#VerForm").hide();// Ocultamos el formulario
	$("form#frmCliente").submit(SaveOrUpdate);// Evento submit de jquery que llamamos al metodo SaveOrUpdate para poder registrar o modificar datos

	$("#btnNuevo").click(VerForm);// evento click de jquery que llamamos al metodo VerForm


	function SaveOrUpdate(e){
		/*e.preventDefault();// para que no se recargue la pagina
        $.post("./ajax/ClienteAjax.php?op=SaveOrUpdate", $(this).serialize(), function(r){// llamamos la url por post. function(r). r-> llamada del callback

            Limpiar();
            ListadoCliente();
            //$.toaster({ priority : 'success', title : 'Mensaje', message : r});
            swal("Mensaje del Sistema", r, "success");
            OcultarForm();
        });*/

        e.preventDefault();// para que no se recargue la pagina

        var formData = new FormData($("#frmCliente")[0]);
        $.ajax({
                url: "./ajax/ClienteAjax.php?op=SaveOrUpdate",
                type: "POST",
               	data: formData,
                contentType: false,
                processData: false,
                success: function(datos){
                    swal("Mensaje del Sistema", datos, "success");
                    ListadoCliente();
                    OcultarForm();
                    Limpiar();
                }
            });
	};

	function Limpiar(){
		// Limpiamos las cajas de texto
		$("#txtIdPersona").val("");
	    $("#txtNombre").val("");
	    $("#txtNum_Documento").val("");
	    $("#txtDireccion_Departamento").val("");
	    $("#txtDireccion_Provincia").val("");
	    $("#txtDireccion_Distrito").val("");
	    $("#txtDireccion_Calle").val("");
	    $("#txtTelefono").val("");
	    $("#txtEmail").val("");
	    $("#txtNumero_cuenta").val("");
	    $("#frmCliente")[0].reset();
	}

	function ComboTipo_Documento() {

        $.get("./ajax/ClienteAjax.php?op=listTipo_DocumentoPersona", function(r) {
                $("#cboTipo_Documento").html(r);

        })
    }

	function VerForm(){
		$("#VerForm").show();// Mostramos el formulario
		$("#btnNuevo").hide();// ocultamos el boton nuevo
		$("#VerListado").hide();
		initMap();
	}

	function OcultarForm(){
		$("#VerForm").hide();// Mostramos el formulario
		$("#btnNuevo").show();// ocultamos el boton nuevo
		$("#VerListado").show();
	}


}

function listaCliente(){
	var r=0;
	var table = $('#tblCliente').DataTable( {
		"processing": true,
		"serverSide": true,
		"order": [[ 0, 'asc' ]],
		dom: 'Bfrtip',
		buttons: [
	            'copyHtml5',
	            'excelHtml5',
	            'csvHtml5',
	            'pdfHtml5'
	        ],
		"ajax": "./ajax/server_processing.php"
	} );

}

function ListadoCliente(){
		var tabla = $('#tblCliente').dataTable(
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
        	     	{   "mDataProp": "id"},
                    {   "mDataProp": "1"},
                    {   "mDataProp": "2"},
                    {   "mDataProp": "3"},
                    {   "mDataProp": "4"},
                    {   "mDataProp": "5"},
                    {   "mDataProp": "6"}

        	],"ajax":
	        	{
	        		url: './ajax/ClienteAjax.php?op=list',
					type : "get",
					dataType : "json"
	        	},
	        "bDestroy": true

    	}).DataTable();
    };

function eliminarCliente(id){// funcion que llamamos del archivo ajax/CategoriaAjax.php?op=delete linea 53
	bootbox.confirm("¿Esta Seguro de eliminar el cliente seleccionado?", function(result){ // confirmamos con una pregunta si queremos eliminar
		if(result){// si el result es true
			$.post("./ajax/ClienteAjax.php?op=delete", {id : id}, function(e){// llamamos la url de eliminar por post. y mandamos por parametro el id

				ListadoCliente();
				swal("Mensaje del Sistema", e, "success");

            });
		}

	})
}

function cargarDataCliente(id, tipo_persona,nombre,tipo_documento,num_documento,direccion_departamento,direccion_provincia,direccion_distrito,direccion_calle,direccion_nom_calle,direccion_num,direccion_zona,direccion_nom_zona,cx,cy,foto,telefono,email,numero_cuenta,estado){// funcion que llamamos del archivo ajax/CategoriaAjax.php linea 52
		$("#VerForm").show();// mostramos el formulario
		$("#btnNuevo").hide();// ocultamos el boton nuevo
		$("#VerListado").hide();

		$("#txtIdPersona").val(id);// recibimos la variable id a la caja de texto
		$("#cboTipoPersona").val(tipo_persona);
	    $("#txtNombre").val(nombre);// recibimos la variable nombre a la caja de texto txtNombre
	    $("#cboTipo_Documento").val(tipo_documento);
 		$("#txtNum_Documento").val(num_documento);
	    $("#txtDireccion_Departamento").val(direccion_departamento);
	    $("#txtDireccion_Provincia").val(direccion_provincia);
	    $("#txtDireccion_Distrito").val(direccion_distrito);
	    $("#txtDireccion_Calle").val(direccion_calle);
	    $("#txtDireccion_Nom_Calle").val(direccion_nom_calle);
	    $("#txtDireccion_Num").val(direccion_num);
	    $("#txtDireccion_Zona").val(direccion_zona);
	    $("#txtDireccion_Nom_Zona").val(direccion_nom_zona);
	    $("#cx").val(cx);
	    $("#cy").val(cy);
	    $("#txtRutaImgCli").val(foto);
	    $("#txtRutaImgCli").show();
	    $("#txtTelefono").val(telefono);
 		$("#txtEmail").val(email);
 		$("#txtNumero_Cuenta").val(numero_cuenta);
 		$("#cboEstado").val(estado);

 		initMap();
 		listaMap(cx,cy);
 	}

