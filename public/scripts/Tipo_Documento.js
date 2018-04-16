$(document).on("ready", init);// Inciamos el jquery

function init(){
	
    $('#tblTipo_Documento').dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });
    
	ListadoTipo_Documento();// Ni bien carga la pagina que cargue el metodo
	$("#VerForm").hide();// Ocultamos el formulario
	$("form#frmTipo_Documento").submit(SaveOrUpdate);// Evento submit de jquery que llamamos al metodo SaveOrUpdate para poder registrar o modificar datos
	
	$("#btnNuevo").click(VerForm);// evento click de jquery que llamamos al metodo VerForm


	function SaveOrUpdate(e){
		e.preventDefault();// para que no se recargue la pagina
        $.post("./ajax/Tipo_DocumentoAjax.php?op=SaveOrUpdate", $(this).serialize(), function(r){// llamamos la url por post. function(r). r-> llamada del callback
            swal("Mensaje del Sistema", r, "success");// mostramos el mensaje del callback
            Limpiar();// llamamos al metodod Limpiar
            ListadoTipo_Documento();
            OcultarForm();
        });
	};

	function Limpiar(){
		// Limpiamos las cajas de texto
		$("#txtIdTipo_Documento").val("");
	    $("#txtNombre").val("");
	    //$("#txtOperacion").val("");
	    $("#txtUltima_Serie").val("");
	    $("#txtUltimo_Numero").val("");
	}

	function VerForm(){
		$("#VerForm").show();// Mostramos el formulario
		$("#btnNuevo").hide();// ocultamos el boton nuevo
		$("#VerListado").hide();
	}

	function OcultarForm(){
        $("#VerForm").hide();
        $("#btnNuevo").show();
        $("#VerListado").show();
    }

}

function ListadoTipo_Documento(){ 
         var tabla = $('#tblTipo_Documento').dataTable();
        $.ajax({
            url: './ajax/Tipo_DocumentoAjax.php?op=list',
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
                                    s[i][4]
                                      ]);                                       
                        } // End For
                                        
            },
            error: function(e){
               console.log(e.responseText); 
            }
        });
    };

function eliminarTipo_Documento(id){// funcion que llamamos del archivo ajax/CategoriaAjax.php?op=delete linea 53
	bootbox.confirm("¿Esta Seguro de eliminar el tipo de documento?", function(result){ // confirmamos con una pregunta si queremos eliminar
		if(result){// si el result es true
			$.post("./ajax/Tipo_DocumentoAjax.php?op=delete", {id : id}, function(e){// llamamos la url de eliminar por post. y mandamos por parametro el id 
                swal("Mensaje del Sistema", e, "success");
				ListadoTipo_Documento();

            });
		}
		
	})
}

function cargarDataTipo_Documento(id, nombre,operacion){// funcion que llamamos del archivo ajax/CategoriaAjax.php linea 52
		$("#VerForm").show();// mostramos el formulario
		$("#btnNuevo").hide();// ocultamos el boton nuevo
		$("#VerListado").hide();

		$("#txtIdTipo_Documento").val(id);// recibimos la variable id a la caja de texto txtIdCategoria
	    $("#txtNombre").val(nombre);// recibimos la variable nombre a la caja de texto txtNombre
	    $("#txtOperacion").val(operacion);
}