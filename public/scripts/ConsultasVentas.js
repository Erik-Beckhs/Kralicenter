$(document).on("ready", init);// Inciamos el jquery

var objC = new init();

function init(){
	sessionStorage.idSucursal = $("#txtIdSucursal").val();
	sessionStorage.Sucursal = $("#txtSucursal").val();
	$("#tblVentaFechas").dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });
	$("#tblVentasDetalladas").dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });
	$("#tblVentasPendientes").dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });
	$("#tblVentasContado").dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });
	$("#tblVentascredito").dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });
	$("#tblVentasCliente").dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });
	$("#tblVentaEmp").dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });		///TABLA DE LIBROO DE VENTAS
						$("#tb1libroventas").dataTable({
									dom: 'Bfrtip',
									buttons: [
											'copyHtml5',
											'excelHtml5',
											'csvHtml5',
											'pdfHtml5'
									]
							});



	$("#tblVentaEmpDet").dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });
		$("#btnAgregarCliente").click(function(e){
		e.preventDefault();

		var opt = $("input[type=radio]:checked");
			$("#txtIdCliente").val(opt.val());
		$("#txtCliente").val(opt.attr("data-nombre"));
				email = opt.attr("data-email");

		$("#modalListadoCliente").modal("hide");
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

		$("#txtfechalibroventas").change(Libroventas);
			$("#txt2flv").change(Libroventas);
	$("#cboFechaDesdeVent").change(ListadoVentasFechas);
	$("#cboFechaHastaVent").change(ListadoVentasFechas);

	$("#cboFechaDesdeDetVent").change(ListadoVentasDetalladas);
	$("#cboFechaHastaDetVent").change(ListadoVentasDetalladas);

	$("#cboFechaDesdeVentPend").change(ListadoVentasPendientes);
	$("#cboFechaHastaVentPend").change(ListadoVentasPendientes);

	$("#cboFechaDesdeVentCont").change(ListadoVentasContados);
	$("#cboFechaHastaVentCont").change(ListadoVentasContados);

	$("#cboFechaDesdeVentCred").change(ListadoVentascredito);
	$("#cboFechaHastaVentCred").change(ListadoVentascredito);

	//$("#txtIdCliente").change(ListadoVentasCliente);
	$("#cboFechaDesdeCli").change(ListadoVentasCliente);
	$("#cboFechaHastaCli").change(ListadoVentasCliente);

	$("#cboFechaDesdeVentEmp").change(ListadoVentasEmpleado);
	$("#cboFechaHastaVentEmp").change(ListadoVentasEmpleado);

	$("#cboFechaDesdeVentEmpDet").change(ListadoVentasEmpleadoDet);
	$("#cboFechaHastaVentEmpDet").change(ListadoVentasEmpleadoDet);
}
//funcion para el libor de Ventas


			//EVENTO CUANDO SE CAMBIO LA FECHA EN LIBRO DE VENTAS

function Libroventas(){

	if($("#txtfechalibroventas").val() != "" && $("#txt2flv").val() != ""){

		var fecha_desde = $("#txtfechalibroventas").val();
		 fecha_hasta = $("#txt2flv").val();
		  idsucursal = $("#txtIdSucursal").val();

		var tabla = $('#tb1libroventas').dataTable(
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
											{   "mDataProp": "10"},
											{   "mDataProp": "11"},
											{   "mDataProp": "12"},
											{   "mDataProp": "13"},
											{   "mDataProp": "14"},
											{   "mDataProp": "15"},
											{   "mDataProp": "16"}

						],"ajax":
							{
								url: './ajax/ConsultasVentasAjax.php?op=libro_ventas',
						type : "get",
						dataType : "json",
						data:{fecha_desde: fecha_desde, fecha_hasta: fecha_hasta, idsucursal: idsucursal},

						error: function(e){
								console.log(e.responseText);
						}
							},
						"bDestroy": true

				}).DataTable();
	}
}
























	function ListadoVentasFechas(){

		if($("#cboFechaDesdeVent").val() != "" && $("#cboFechaHastaVent").val() != ""){
			var fecha_desde = $("#cboFechaDesdeVent").val(), fecha_hasta = $("#cboFechaHastaVent").val(), idsucursal = $("#txtIdSucursal").val();
			var tabla = $('#tblVentaFechas').dataTable(
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
			        		url: './ajax/ConsultasVentasAjax.php?op=listVentasFechas',
							type : "get",
							dataType : "json",
							data:{fecha_desde: fecha_desde, fecha_hasta: fecha_hasta, idsucursal: idsucursal},

							error: function(e){
						   		console.log(e.responseText);
							}
			        	},
			        "bDestroy": true

		    	}).DataTable();

		}
	}

	function ListadoVentasDetalladas(){

		if($("#cboFechaDesdeDetVent").val() != "" && $("#cboFechaHastaDetVent").val() != ""){
			var fecha_desde = $("#cboFechaDesdeDetVent").val(), fecha_hasta = $("#cboFechaHastaDetVent").val(), idsucursal = $("#txtIdSucursal").val();
			var tabla = $('#tblVentasDetalladas').dataTable(
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
		                    {   "mDataProp": "10"},
		                    {   "mDataProp": "11"},
		                     {   "mDataProp": "12"},




		        	],"ajax":
			        	{
			        		url: './ajax/ConsultasVentasAjax.php?op=listVentasDetalladas',
							type : "get",
							dataType : "json",
							data:{fecha_desde: fecha_desde, fecha_hasta: fecha_hasta, idsucursal: idsucursal},
							error: function(e){
						   		console.log(e.responseText);
							}
			        	},
			        "bDestroy": true

		    	}).DataTable();

		}
	}

	function ListadoVentasPendientes(){

		if($("#cboFechaDesdeVentPend").val() != "" && $("#cboFechaHastaVentPend").val() != ""){
			var fecha_desde = $("#cboFechaDesdeVentPend").val(), fecha_hasta = $("#cboFechaHastaVentPend").val(), idsucursal = $("#txtIdSucursal").val();
			var tabla = $('#tblVentasPendientes').dataTable(
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
		                    {   "mDataProp": "10"},
		                    {   "mDataProp": "11"},
		                    {   "mDataProp": "12"}

		        	],"ajax":
			        	{
			        		url: './ajax/ConsultasVentasAjax.php?op=listVentasPendientes',
							type : "get",
							dataType : "json",
							data:{fecha_desde: fecha_desde, fecha_hasta: fecha_hasta, idsucursal: idsucursal},
							error: function(e){
						   		console.log(e.responseText);
							}
			        	},
			        "bDestroy": true

		    	}).DataTable();

		}
	}

	function ListadoVentasContados(){

		if($("#cboFechaDesdeVentCont").val() != "" && $("#cboFechaHastaVentCont").val() != ""){
			var fecha_desde = $("#cboFechaDesdeVentCont").val(), fecha_hasta = $("#cboFechaHastaVentCont").val(), idsucursal = $("#txtIdSucursal").val();
			var tabla = $('#tblVentasContado').dataTable(
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
		                    {   "mDataProp": "10"},
												{   "mDataProp": "11"}
		        	],"ajax":
			        	{
			        		url: './ajax/ConsultasVentasAjax.php?op=listVentasContado',
							type : "get",
							dataType : "json",
							data:{fecha_desde: fecha_desde, fecha_hasta: fecha_hasta, idsucursal: idsucursal},
							error: function(e){
						   		console.log(e.responseText);
							}
			        	},
			        "bDestroy": true

		    	}).DataTable();


		}
	}

	function ListadoVentascredito(){

		if($("#cboFechaDesdeVentCred").val() != "" && $("#cboFechaHastaVentCred").val() != ""){
			var fecha_desde = $("#cboFechaDesdeVentCred").val(), fecha_hasta = $("#cboFechaHastaVentCred").val(), idsucursal = $("#txtIdSucursal").val();
			var tabla = $('#tblVentascredito').dataTable(
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
		                    {   "mDataProp": "10"},
		                    {   "mDataProp": "11"},
		                    {   "mDataProp": "12"}

		        	],"ajax":
			        	{
			        		url: './ajax/ConsultasVentasAjax.php?op=listVentascredito',
							type : "get",
							dataType : "json",
							data:{fecha_desde: fecha_desde, fecha_hasta: fecha_hasta, idsucursal: idsucursal},
							error: function(e){
						   		console.log(e.responseText);
							}
			        	},
			        "bDestroy": true

		    	}).DataTable();
		}
	}

	function ListadoVentasCliente(){

		if($("#cboFechaDesdeCli").val() != "" && $("#cboFechaHastaCli").val() != "" && $("#txtIdCliente").val() != ""){
			var idCliente = $("#txtIdCliente").val(), fecha_desde = $("#cboFechaDesdeCli").val(), fecha_hasta = $("#cboFechaHastaCli").val(), idsucursal = $("#txtIdSucursal").val();
			var tabla = $('#tblVentasCliente').dataTable(
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
		                    {   "mDataProp": "10"},
												{   "mDataProp": "11"}

		        	],"ajax":
			        	{
			        		url: './ajax/ConsultasVentasAjax.php?op=listVentasCliente',
							type : "get",
							dataType : "json",
							data:{idCliente: idCliente, fecha_desde: fecha_desde, fecha_hasta: fecha_hasta, idsucursal: idsucursal},
							error: function(e){
						   		console.log(e.responseText);
							}
			        	},
			        "bDestroy": true

		    	}).DataTable();
		}
	}
	function ListadoComprasDetProveedor(){

		if($("#cboFechaDesdeDetProv").val() != "" && $("#cboFechaHastaDetProv").val() != "" && $("#txtIdProveedor").val() != ""){
			var idProveedor = $("#txtIdProveedor").val(), fecha_desde = $("#cboFechaDesdeDetProv").val(), fecha_hasta = $("#cboFechaHastaDetProv").val(), idsucursal = $("#txtIdSucursal").val();
			var tabla = $('#tblComprasDetProveedor').dataTable(
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
		                    {   "mDataProp": "10"},
		                    {   "mDataProp": "11"},
		                    {   "mDataProp": "12"},


		        	],"ajax":
			        	{
			        		url: './ajax/ConsultasComprasAjax.php?op=listComprasDetProveedor',
							type : "get",
							dataType : "json",
							data:{idProveedor: idProveedor, fecha_desde: fecha_desde, fecha_hasta: fecha_hasta, idsucursal: idsucursal},
							error: function(e){
						   		console.log(e.responseText);
							}
			        	},
			        "bDestroy": true

		    	}).DataTable();
		}
	}

	function ListadoVentasEmpleado(){
		if($("#cboFechaDesdeVentEmp").val() != "" && $("#cboFechaHastaVentEmp").val() != ""){
			var fecha_desde = $("#cboFechaDesdeVentEmp").val(), fecha_hasta = $("#cboFechaHastaVentEmp").val(), idsucursal = $("#txtIdSucursal").val();
			var idempleado = $('#txtIdEmpleado').val();
			var tabla = $('#tblVentaEmp').dataTable(
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
							{   "mDataProp": "11"}

		        	],"ajax":
			        	{
			        		url: './ajax/ConsultasVentasAjax.php?op=listVentasEmpleado',
							type : "get",
							dataType : "json",
							data:{fecha_desde, fecha_hasta, idsucursal, idempleado},
							// success:function(data){
							// 	console.log(data);
							// },
							error: function(e){
						   		console.log(e.responseText);
							}
			        	},
			        "bDestroy": true

		    	}).DataTable();
		}
	}

	function ListadoVentasEmpleadoDet(){

		if($("#cboFechaDesdeVentEmpDet").val() != "" && $("#cboFechaHastaVentEmpDet").val() != ""){
			var fecha_desde = $("#cboFechaDesdeVentEmpDet").val(), fecha_hasta = $("#cboFechaHastaVentEmpDet").val(), idsucursal = $("#txtIdSucursal").val();
			var tabla = $('#tblVentaEmpDet').dataTable(
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
		                    {   "mDataProp": "10"},
		                    {   "mDataProp": "11"},
		                    {   "mDataProp": "12"},
		                    {   "mDataProp": "13"},
												{   "mDataProp": "14"}
		        	],"ajax":
			        	{
			        		url: './ajax/ConsultasVentasAjax.php?op=listVentasEmpleadoDet',
							type : "get",
							dataType : "json",
							data:{fecha_desde: fecha_desde, fecha_hasta: fecha_hasta, idsucursal: idsucursal},
							error: function(e){
						   		console.log(e.responseText);
							}
			        	},
			        "bDestroy": true

		    	}).DataTable();

		}
	}
