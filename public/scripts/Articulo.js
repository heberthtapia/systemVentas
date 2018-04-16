$(document).on("ready", init);

function init(){
	
	var tabla = $('#tblArticulos').dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });

	ListadoArticulos();
	ComboCategoria();
	ComboUM();
	$("#VerForm").hide();
	$("#txtRutaImgArt").hide();
	$("form#frmArticulos").submit(SaveOrUpdate);
	
	$("#btnNuevo").click(VerForm);

	function SaveOrUpdate(e){
			e.preventDefault();

	        var formData = new FormData($("#frmArticulos")[0]);

	        $.ajax({

	                url: "./ajax/ArticuloAjax.php?op=SaveOrUpdate",

	                type: "POST",

	               data: formData,

	                contentType: false,

	                processData: false,

	                success: function(datos)

	                {

	                    swal("Mensaje del Sistema", datos, "success");
						  ListadoArticulos();
						  OcultarForm();
						  $('#frmArticulos').trigger("reset");
	                }

	            });
	};

	function ComboCategoria(){
			$.post("./ajax/ArticuloAjax.php?op=listCategoria", function(r){
	            $("#cboCategoria").html(r);
	        });
	}

	function ComboUM(){
			$.post("./ajax/ArticuloAjax.php?op=listUM", function(r){
	            $("#cboUnidadMedida").html(r);
	        });
	}

	function Limpiar(){
			$("#txtIdArticulo").val("");
		    $("#txtNombre").val("");
	}

	function VerForm(){
			$("#VerForm").show();
			$("#btnNuevo").hide();
			$("#VerListado").hide();
	}

	function OcultarForm(){
			$("#VerForm").hide();// Mostramos el formulario
			$("#btnNuevo").show();// ocultamos el boton nuevo
			$("#VerListado").show();
	}
}
function ListadoArticulos(){ 
	var tabla = $('#tblArticulos').dataTable(
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
	        		url: './ajax/ArticuloAjax.php?op=list',
					type : "get",
					dataType : "json",
					
					error: function(e){
				   		console.log(e.responseText);	
					}
	        	},
	        "bDestroy": true

    	}).DataTable();

    };
function eliminarArticulo(id){
	bootbox.confirm("¿Esta Seguro de eliminar la Articulo?", function(result){
		if(result){
			$.post("./ajax/ArticuloAjax.php?op=delete", {id : id}, function(e){
                
				swal("Mensaje del Sistema", e, "success");
				ListadoArticulos();

            });
		}
		
	})
}

function cargarDataArticulo(idarticulo, idcategoria, idunidad_medida, nombre, descripcion, imagen){
		$("#VerForm").show();
		$("#btnNuevo").hide();
		$("#VerListado").hide();

		$("#txtIdArticulo").val(idarticulo);
	    $("#cboCategoria").val(idcategoria);
	    $("#cboUnidadMedida").val(idunidad_medida);
	    $("#txtNombre").val(nombre);
	    $("#txtDescripcion").val(descripcion);
	   // $("#imagenArt").val(imagen);
	    $("#txtRutaImgArt").val(imagen);
	    $("#txtRutaImgArt").show();
	    //$("#txtRutaImgArt").prop("disabled", true);
}