$(document).on("ready", init);// Inciamos el jquery

var objC = new init();

var montoPendiente;

function init(){

	$("#tblcredito").dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });

    $("#tblDeuda").dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });

	Listadocreditos();
	ListadoDeudas();

	$("form#frmcreditosV").submit(SaveOrUpdate);
	$("form#frmcreditosDeudas").submit(SaveOrUpdateD);

	function SaveOrUpdate(e){
		e.preventDefault();
		//alert(montoPendiente + " " + )
		if (parseFloat($("#txtTotalPago").val()) > "0.0") {
			if (montoPendiente >= parseFloat($("#txtTotalPago").val())) {
				$.post("./ajax/CreditoAjax.php?op=SaveOrUpdate", $(this).serialize(), function(r){// llamamos la url por post. function(r). r-> llamada del callback

		            Limpiar();
		            Listadocreditos();
		            //$.toaster({ priority : 'success', title : 'Mensaje', message : r});
								swal("Mensaje del Sistema", r, "success");
		            $("#VerListado").show();
					$("#VerForm").hide();

		        });
			} else {
				bootbox.alert("El monto a pagar no puede ser mayor al monto pendiente");
				$("#txtTotalPago").val("1.0");
			}
        } else {
			bootbox.alert("el monto a pagar no puede ser vacio, menor o igual que 0");
			$("#txtTotalPago").val("1.0");
		}
	};

	function SaveOrUpdateD(e){
		e.preventDefault();
		//alert(montoPendiente + " " + )
		if (parseFloat($("#txtTotalPagoC").val()) > "0.0") {
			if (montoPendiente >= parseFloat($("#txtTotalPagoC").val())) {
				$.post("./ajax/CreditoAjax.php?op=SaveOrUpdateD", $(this).serialize(), function(r){// llamamos la url por post. function(r). r-> llamada del callback

								Limpiar();
								ListadoDeudas();
								//$.toaster({ priority : 'success', title : 'Mensaje', message : r});
								swal("Mensaje del Sistema", r, "success");
								$("#VerListado").show();
					$("#VerForm").hide();

						});
			} else {
				bootbox.alert("El monto a pagar no puede ser mayor al monto pendiente");
				$("#txtTotalPagoC").val("1.0");
			}
				} else {
			bootbox.alert("el monto a pagar no puede ser vacio, menor o igual que 0");
			$("#txtTotalPagoC").val("1.0");
		}
	};


	function Limpiar(){
		$("#txtIdcredito").val("");
		$("#txtIdcreditoC").val("");
		$("#txtIdVenta").val("");
		$("#txtIdIngreso").val("");
		$("#txtTotalPago").val("");
		$("#txtTotalPagoC").val("");
		$("#txtMontoPendiente").val("");
	}

	function Listadocreditos(){


	var tabla = $('#tblcredito').dataTable(
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



        	],"ajax":
	        	{
	        		url: './ajax/CreditoAjax.php?op=list',
					type : "get",
					dataType : "json",

					error: function(e){
				   		console.log(e.responseText);
					}
	        	},
	        "bDestroy": true

    	}).DataTable();


    };

    function ListadoDeudas(){
	  var tabla = $('#tblDeuda').dataTable(
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


        	],"ajax":
	        	{
	        		url: './ajax/CreditoAjax.php?op=listDeudas',
					type : "get",
					dataType : "json",

					error: function(e){
				   		console.log(e.responseText);
					}
	        	},
	        "bDestroy": true

    	}).DataTable();
    };

    function GetDetallecredito(idVenta) {

    }

}

function Agregarcredito(idVenta, total){
	$("#VerListado").hide();
	$("#VerForm").show();
	$("#txtIdVenta").val(idVenta);
	$("#txtMontoTotal").val(total);

	$.post("./ajax/CreditoAjax.php?op=VerDetcredito", {idVenta: idVenta}, function(r) {
                $("table#tblVerDetalle tbody").html(r);

        });

	$.getJSON("./ajax/CreditoAjax.php?op=MontoTotalPagados", {idVenta: idVenta}, function(r) {
                if (r) {
                	$("#txtMontoPendiente").val(r.MontoTotalPagados);
                	montoPendiente = r.MontoTotalPagados;
                }

    })

}

function AgregarPago(idIngreso, total){
	$("#VerListado").hide();
	$("#VerForm").show();
	$("#txtIdIngreso").val(idIngreso);
	$("#txtMontoTotal").val(total);

	$.post("./ajax/CreditoAjax.php?op=VerDetcreditoCompra", {idIngreso: idIngreso}, function(r) {
                $("table#tblVerDetalle tbody").html(r);

        });

	$.getJSON("./ajax/CreditoAjax.php?op=MontoTotalPagadosCompra", {idIngreso: idIngreso}, function(r) {
                if (r) {
                	$("#txtMontoPendiente").val(r.MontoTotalPagados);
                	montoPendiente = r.MontoTotalPagados;
                }

    })

}
