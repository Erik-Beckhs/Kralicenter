$(document).on("ready", init);

function init(){

	var tabla = $('#1tblArticulos').dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });


	ListadoArticulos2();
	ComboCategoria();
	ComboUM();
	$("#VerForm").hide();
	$("#txtRutaImgArt").hide();

	$("form#afrmArticulos").submit(modificar);

	$("#btnNuevo").click(VerForm);


	function modificar(e){
			e.preventDefault();

	        var formData = new FormData($("#afrmArticulos")[0]);

	        $.ajax({

	                url: "./ajax/AlmacenAjax.php?op=modificar",

	                type: "POST",

	               data: formData,

	                contentType: false,

	                processData: false,

	                success: function(datos)

	                {

	                    swal("Mensaje del Sistema", datos, "success");
						  ListadoArticulos2();
						  OcultarForm();
						  $('#afrmArticulos').trigger("reset");
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
			$("#txtIdDetalleIngreso").val("");
		    $("#txtventa5").val("");
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

};


	function ListadoArticulos2(){
	var tabla = $('#1tblArticulos').dataTable(
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
                    {   "mDataProp": "6"},
					 {   "mDataProp": "7"},
					  {   "mDataProp": "8"},
						{   "mDataProp": "9"},
						{   "mDataProp": "10"},
						{   "mDataProp": "11"},
						{   "mDataProp": "12"}
						//{   "mDataProp": "13"},
						//{   "mDataProp": "14"},



        	],"ajax":
	        	{
	        		url: './ajax/AlmacenAjax.php?op=list4',
					type : "get",
					dataType : "json",

					error: function(e){
				   		console.log(e.responseText);
					}
	        	},
	        "bDestroy": true

    	}).DataTable();

    };
	function cargarDataArticulo2(idDetalle,stock,compra,venta1,venta2,venta3,venta4,venta5){
		$("#VerForm").show();
		$("#btnNuevo").hide();
		$("#VerListado").hide();
		$("#txtIdDetalleIngreso").val(idDetalle);
		$("#txtstock_actual").val(stock);
		$("#txtventa1").val(venta1);
		$("#txtventa2").val(venta2);
		$("#txtventa3").val(venta3);
		$("#txtventa4").val(venta4);
		$("#txtventa5").val(venta5);
		$("#txtcompra").val(compra);
};

function eliminarArticulo2(id){
	bootbox.confirm("���Esta Seguro de eliminar la Articulo?", function(result){
		if(result){
			$.post("./ajax/AlmacenAjax.php?op=delete", {id : id}, function(e){

				swal("Mensaje del Sistema", e, "success");
				ListadoArticulos2();

            });
		}

	})
};





