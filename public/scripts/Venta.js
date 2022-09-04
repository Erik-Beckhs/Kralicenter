$(document).on("ready", init);// Inciamos el jquery


var objinitpf = new init();

var detalleTraerCantidad = new Array();

var email = "";

function init(){
var total = 0.0;
    //Ver();
	$('#tblVentaPedido').dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });

	ListadoVenta();// Ni bien carga la pagina que cargue el metodo
	ComboTipo_Documento();
    GetPrimerIDTicket();


    $("#VerFormProforma").hide();
	$("#VerFormPF").hide();// Ocultamos el formulario
    $("#btnGenerarProforma").click(SaveOrUpdate);// Evento submit de jquery que llamamos al metodo SaveOrUpdate para poder registrar o modificar datos
	$("#cboTipoComprobante").change(VerNumSerie);
	$("#btnNuevo").click(VerFormPF);// evento click de jquery que llamamos al metodo VerForm
    $("#btnNuevoPedido").click(VerFormPedido);
//    $("#btnGenerarVenta").click(GenerarVenta);

    $("form#frmcreditos").submit(Savecredito);
		// donde ejecuta la funcion interna "AbrirModalCliente linea 181" el boton con id "btnBuscarCliente" al hacer  click
    $("#btnBuscarCliente").click(AbrirModalCliente);
    $("#btnBuscarDetIng").click(AbrirModalDetPed);
    $("#btnAgregarCliente").click(function(e){
        e.preventDefault();

        var opt = $("input[type=radio]:checked");
    	$("#txtIdCliente").val(opt.val());
        $("#txtCliente").val(opt.attr("data-nombre"));
    	email = opt.attr("data-email");

        $("#modalListadoCliente").modal("hide");
        $("#btnBuscarDetIng").show();
    });

    function ComboTipo_Documento() {

        $.get("./ajax/VentaAjax.php?op=listTipoDoc", function(r) {
                $("#cboTipoComprobante").html(r);

        })
    }

		$("#btnAgregarArtPed").click(function(e){
			e.preventDefault();

			var opt = tablaArtPed.$("input[name='optDetIngBusqueda[]']:checked", {"page": "all"});

			opt.each(function() {
//                AgregarPedCarritoPF()

				AgregarDetalleProforma($(this).val(), $(this).attr("data-nombre"), $(this).attr("data-precio-venta"), "1", "0.0", $(this).attr("data-stock-actual"), $(this).attr("data-codigo"), $(this).attr("data-serie"));

			})

			$("#modalListadoArticulosProforma").modal("hide");
		});

		// OBETENEMOS EL ID DE TICKET
		  function GetPrimerIDTicket() {
		    var data = {

		        txtIdSucursal:$("#txtIdSucursal").val(),

		      };
		       $.post("./ajax/VentaAjax.php?op=GetPrimerIDTicket", data, function(r){// llamamos la url por post. function(r). r-> llamada del callback

		            //$.toaster({ priority : 'success', title : 'Mensaje', message : r});

        		  $("#txtNumeroVent").val(r)

		       });
		  }

	function SaveOrUpdate(e){

		e.preventDefault();// para que no se recargue la pagina

            var detalle =  JSON.parse(consultar());
            var data = {
                idUsuario : $("#txtIdUsuario").val(),
				idSucursal : $("#txtIdSucursal").val(),
				idCliente: $("#txtIdCliente").val(),
                tipo_Pedido : $("#cboTipoPedido").val(),
                tipo_pago : $("#tipo_pago").val(),
                descuento : $("#descuento").val(),
                tiempo_entrega : $("#txtTiempoEntrega").val(),
                fecha_validez : $("#cboFechaValidez").val(),
                impuesto : $("#txtImpuesto").val(),
                total_vent : $("#txtTotalProformaVer").val(),
				Numero_TF : $("#txtNumeroVent").val(),
				tipo_comprobante : $("#cboTipoComprobante").val(),
                detalle : detalle
            };

            $.post("./ajax/VentaAjax.php?op=SaveOrUpdate", data, function(r){// llamamos la url por post. function(r). r-> llamada del callback
                swal("Mensaje del Sistema", r, "success");
				location.href ="../importadora/Venta.php";

	                              //
	                              var es = String(r);
	            window.open('./Reportes/exVenta.php?id='+es, 'target', ' toolbar=0 , location=1 , status=0 , menubar=1 , scrollbars=0');

	});
}
    function Savecredito(e){
        e.preventDefault();// para que no se recargue la pagina
        $.post("./ajax/CreditoAjax.php?op=SaveOrUpdate", $(this).serialize(), function(r){// llamamos la url por post. function(r). r-> llamada del callback

                swal("Mensaje del Sistema", r, "success");
                $("#modalcredito").modal("hide");
                OcultarForm();
                ListadoVenta();
                ListadoPedidos();
        });
    }

    function GetIdVenta() {

        $.get("./ajax/CreditoAjax.php?op=GetIdVenta", function(r) {
                $("#txtIdVentaCred").val(r);

        })
    }

	function ComboTipoDocumentoS_N() {

        $.get("./ajax/VentaAjax.php?op=listTipo_DocumentoPersona", function(r) {
                $("#cboTipoDocumentoSN").html(r);

        })
    }

		function GetTotal(idPedido) {
        $.getJSON("./ajax/VentaAjax.php?op=GetTotal", {idPedido: idPedido}, function(r) {
                if (r) {
                    total = r.Total;
                    $("#txtTotalProforma").val(total);

                    var igvPed=total * parseInt($("#txtImpuesto").val())/(100+parseInt($("#txtImpuesto").val()));
                    $("#txtIgvProformaVer").val(Math.round(igvPed*100)/100);

                    var subTotalPed=total - (total * parseInt($("#txtImpuesto").val())/(100+parseInt($("#txtImpuesto").val())));
                    $("#txtSubTotalProformaVer").val(Math.round(subTotalPed*100)/100);

                    $("#txtTotalProformaVer").val(Math.round(total*100)/100);
                }
        });
    }

    function VerNumSerie(){
    	var nombre = $("#cboTipoComprobante").val();
        var idsucursal = $("#txtIdSucursal").val();

            $.getJSON("./ajax/VentaAjax.php?op=GetTipoDocSerieNum", {nombre: nombre,idsucursal: idsucursal}, function(r) {
                if (r) {
                    $("#txtIdTipoDoc").val(r.iddetalle_documento_sucursal);
                    $("#txtSerieVent").val(r.ultima_serie);
                    $("#txtNumeroVent").val(r.ultimo_numero);
                } else {
                    $("#txtIdTipoDoc").val("");
                	$("#txtSerieVent").val("");
                    $("#txtNumeroVent").val("");
                }
            });

    }

    function VerFormPedido(){
        $("#VerFormProforma").show();// Mostramos el formulario
        $("#btnNuevoPedido").hide();// ocultamos el boton nuevo
//        $("#btnGenerarVenta").hide();
        $("#VerListadoPF").hide();// ocultamos el listado
        $("#btnReporte").hide();
        $("#btnBuscarDetIng").hide();
        $("#btnGenerarProforma").hide();
    }

	function VerFormPF(){
		$("#VerFormPF").show();// Mostramos el formulario
		$("#btnNuevo").hide();// ocultamos el boton nuevo
		$("#VerListadoPF").hide();// ocultamos el listado
		$("#btnReporte").hide();
	}
	//funcion donde llamamos al formulario de clientes y ejecutamos
	function AbrirModalCliente(){
		$("#modalListadoCliente").modal("show");

		$.post("./ajax/VentaAjax.php?op=listClientesV", function(r){//cambiar a uno propio
						$("#Cliente").html(r);//linea 327 venta.html donde esta el id=cliente
						$("#tblClientees").DataTable();
				});
	}

	function AbrirModalDetPed(){
		$("#modalListadoArticulosProforma").modal("show");
					var tabla = $('#tblArticulosPF').dataTable(
							{   "aProcessing": true,
									"aServerSide": true,
									"iDisplayLength": 4,
									//"aLengthMenu": [0, 4],
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
											//------------------------



									],"ajax":
											{
													url: './ajax/VentaAjax.php?op=listDetIng',
													type : "get",
													dataType : "json",

													error: function(e){
															console.log(e.responseText);
													}
											},
									"bDestroy": true

							}).DataTable();
	}

	function OcultarForm(){
		$("#VerFormPF").hide();// Mostramos el formulario
		$("#VerListadoPF").show();// ocultamos el listado
		$("#btnReporte").show();
        $("#btnNuevo").show();
        $("#VerFormVentaPed").hide();
        $("#btnNuevoVent").show();
       // $("#lblTitlePed").html("Pedidos");
	}


     function LimpiarPedido(){
        $("#txtIdCliente").val("");
        $("#txtCliente").val("");

        $("#cboTipoPedido").val("Pedido");
        $("#txtNumeroVent").val("");
        elementos.length = 0;
        $("#tblDetalleProforma tbody").html("");
        $("#txtSerieVent").val("");
        $("#txtNumeroVent").val("");
        GetNextNumero();
    }

		function GetTotal(idPedido) {
        $.getJSON("./ajax/VentaAjax.php?op=GetTotal", {idPedido: idPedido}, function(r) {
                if (r) {
                    total = r.Total;
                    $("#txtTotalProforma").val(total);

                    var igvPed=total * parseInt($("#txtImpuesto").val())/(100+parseInt($("#txtImpuesto").val()));
                    $("#txtIgvProformaVer").val(Math.round(igvPed*100)/100);

                    var subTotalPed=total - (total * parseInt($("#txtImpuesto").val())/(100+parseInt($("#txtImpuesto").val())));
                    $("#txtSubTotalProformaVer").val(Math.round(subTotalPed*100)/100);

                    $("#txtTotalProformaVer").val(Math.round(total*100)/100);
                }
        });
    }

    function GetNextNumero() {
        $.getJSON("./ajax/VentaAjax.php?op=GetNextNumero", function(r) {
                if (r) {
                    $("#txtNumeroVent").val(r.numero);
                }
        });
    }


}

function ListadoVenta(){
        var tabla = $('#tblVentaPedido').dataTable(
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
                    {   "mDataProp": "6"}

            ],"ajax":
                {
                    url: './ajax/VentaAjax.php?op=list',
                    type : "get",
                    dataType : "json",

                    error: function(e){
                        console.log(e.responseText);
                    }
                },
            "bDestroy": true

        }).DataTable();
    };

	function ConsultarDetallesProforma() {
        $("table#tblDetalleProforma tbody").html("");
        var data = JSON.parse(objinit.consultar());

        for (var pos in data) {

//----------------------------------------------------------DETALLE DE VENTA ---------------------------------------------------------------------------

            $("table#tblDetalleProforma").append("<tr><td>" + data[pos][1] + " <input class='form-control' type='hidden' name='txtIdDetIng' id='txtIdDetIng[]' value='" + data[pos][0] + "' /></td><td> " + data[pos][6] + "</td><td> " + data[pos][7] + "</td><td>" + data[pos][5]+ "</td><td><input class='form-control' type='text' name='txtPrecioVentPed' readonly id='txtPrecioVentPed[]' value='" + data[pos][2] + "' onchange='calcularTotalPF(" + pos + ")' /></td><td><input class='form-control' type='text' name='txtCantidaPed' id='txtCantidaPed[]'   value='" + data[pos][3] + "' onchange='calcularTotalPF(" + pos + ")' /></td><td><input class='form-control' type='hidden' name='txtDescuentoPed' id='txtDescuentoPed[]'  value='" + data[pos][4] + "' onchange='calcularTotalPF(" + pos + ")' /></td><td><button type='button' onclick='eliminarDetallePed(" + pos + ")' class='btn btn-danger'><i class='fa fa-remove' ></i> </button></td></tr>");
        }
        calcularIgvPed();
        calcularSubTotalPed();
        calcularTotalPF();
    }



    function calcularIgvPed(){
        var suma = 0;

        var data = JSON.parse(objinit.consultar());

        for (var pos in data) {
            suma += parseFloat(data[pos][3] *  (data[pos][2] - data[pos][4]));
        }
        var igvPed=suma * parseInt($("#txtImpuesto").val())/(100+parseInt($("#txtImpuesto").val()));
        $("#txtIgvProforma").val(Math.round(igvPed*100)/100);
    }

    function calcularSubTotalPed(){
        var suma = 0;
        var data = JSON.parse(objinit.consultar());
        for (var pos in data) {
            suma += parseFloat(data[pos][3] * (data[pos][2] - data[pos][4]));
        }
        var subTotalPed=suma - (suma * parseInt($("#txtImpuesto").val())/(100+parseInt($("#txtImpuesto").val())));
        $("#txtSubTotalProforma").val(Math.round(subTotalPed*100)/100);
    }


    function calcularTotalPF(posi){
        if(posi != null){
          ModificarProforma(posi);
        }
        var suma = 0;
        var data = JSON.parse(objinit.consultar());
        for (var pos in data) {
            suma += parseFloat(data[pos][3] * (data[pos][2] - data[pos][4]));
        }
        calcularIgvPed();
        calcularSubTotalPed();

        $("#txtTotalProformaVer").val(Math.round(suma*100)/100);

    }

    function ModificarProforma(pos){
        var idDetIng = document.getElementsByName("txtIdDetIng");
        var pvd = document.getElementsByName("txtPrecioVentPed");
        var cantPed = document.getElementsByName("txtCantidaPed");
       // alert(pos);
       //elementos[pos][2] = $("input[name=txtPrecioVentPed]:eq(" + pos + ")").val();

        elementos[pos][0] = idDetIng[pos].value;
        elementos[pos][2] = pvd[pos].value;
        if (parseInt(cantPed[pos].value) <= elementos[pos][5]) {
            elementos[pos][3] = cantPed[pos].value;
            if (parseInt(cantPed[pos].value) <= 0) {
                bootbox.alert("<center>El Articulo " + elementos[pos][1] + " no puede estar vacio, menor o igual que 0</center>", function() {
                    elementos[pos][3] = "1";
                    cantPed[pos].value = "1";
                    calcularIgvPed();
                    calcularSubTotalPed();
                    calcularTotalPF();
                });
            }
        } else {
            bootbox.alert("<center>El Articulo " + elementos[pos][1] + " no tiene suficiente stock para tal cantidad</center>", function() {
                elementos[pos][3] = "1";
                cantPed[pos].value = "1";
                calcularIgvPed();
                calcularSubTotalPed();
                calcularTotalPF();
            });
        }

//        elementos[pos][4] = descPed[pos].value;
        //alert(elementos[pos][3]);
        //alert(elementos[pos][0] + " - " + elementos[pos][2] + " - " + elementos[pos][3] + " - " + elementos[pos][4] + " - ");
        ConsultarDetallesProforma();
    }





    function consultar() {
        return JSON.stringify(elementos);
    }

				function eliminarDetallePed(ele){
				        console.log(ele);
				        objinit.eliminar(ele);
				        ConsultarDetallesProforma();
				    }

    function eliminarVenta(id){// funcion que llamamos del archivo ajax/CategoriaAjax.php?op=delete linea 53
    	bootbox.confirm("¿Esta Seguro de eliminar el Venta seleccionado?", function(result){ // confirmamos con una pregunta si queremos eliminar
    		if(result){// si el result es true
    			$.post("./ajax/VentaAjax.php?op=delete", {id : id}, function(e){// llamamos la url de eliminar por post. y mandamos por parametro el id


    				swal("Mensaje del Sistema", e, "success");

    				location.reload();
                });
    		}

    	})
    }
    function AgregarPedCarritoPF(iddet_ing, stock_actual, art, cod, serie, precio_venta){

		if (stock_actual > 0) {
			var detalles = new Array(iddet_ing, art, precio_venta, "1", "0.0", stock_actual, cod, serie);
			elementos.push(detalles);
			ConsultarDetallesProforma();
            $("#btnGenerarProforma").show();
		} else {
			bootbox.alert("No se puede agregar al detalle. No tiene stock");
		}
    }

    function pasarIdPedido(idPedido, total, correo){// funcion que llamamos del archivo ajax/CategoriaAjax.php linea 52
    		$("#VerFormPF").show();// mostramos el formulario
    		$("#VerListadoPF").hide();// ocultamos el listado
            $("#btnNuevoPedido").hide();
            $("#VerTotalesDetProforma").hide();

    		$("#txtIdPedido").val(idPedido);
    		$("#txtTotalProforma").val(total);
            email = correo;
            AgregatStockCant(idPedido);
            CargarDetallePedido(idPedido);
            var igvPed=total * parseInt($("#txtImpuesto").val())/(100+parseInt($("#txtImpuesto").val()));
            $("#txtIgvProforma").val(Math.round(igvPed*100)/100);

            var subTotalPed=total - (total * parseInt($("#txtImpuesto").val())/(100+parseInt($("#txtImpuesto").val())));
            $("#txtSubTotalProforma").val(Math.round(subTotalPed*100)/100);

            $("#txtTotalProforma").val(Math.round(total*100)/100);
     	}

    function AgregarDetalleProforma(iddet_ing, nombre, precio_venta, cant, desc, stock_actual, codigo, serie) {
        var detalles = new Array(iddet_ing, nombre, precio_venta, cant, desc, stock_actual, codigo, serie);
        elementos.push(detalles);
        ConsultarDetallesProforma();
    }

    function cargarDataPedido(idVenta, tipo_pedido, numero, Cliente, total, correo) {
        bandera = 2;
        $("#VerFormProforma").show();
        $("#btnNuevoPedido").hide();
        $("#VerListadoPF").hide();
        $("#txtIdVenta").val(idVenta);
        $("#btnBuscarCliente").hide();

//        $("#cboTipoPedido").hide();
/*
        email = correo;
        var igvPed=total * parseInt($("#txtImpuesto").val())/(100+parseInt($("#txtImpuesto").val()));
        $("#txtIgvPed").val(Math.round(igvPed*100)/100);

        var subTotalPed=total - (total * parseInt($("#txtImpuesto").val())/(100+parseInt($("#txtImpuesto").val())));
        $("#txtSubTotalPed").val(Math.round(subTotalPed*100)/100);

        $("#txtTotalPed").val(Math.round(total*100)/100);
*/
        if (tipo_pedido == "PROFORMA") {
            $.getJSON("./ajax/VentaAjax.php?op=GetVenta", {idVenta:idVenta}, function(r) {
                if (r) {
                       console.log(r);

                    $("#VerFormVentaPed").show();
//                    $("#VerDetalleProforma").hide();

                    $("#VerTotalesDetPedido").hide();
                    $("#inputTotal").hide();
                    $("#txtTotalVent").hide();
                    $("#VerRegPedido").hide();
                    $("#txtCliente").val(Cliente);
                    $("#txtSerieVent").val(r.serie_comprobante);
                    $("#txtNumeroVent").val(r.num_comprobante);
                    $("#cboTipoVenta").val(r.tipo_venta);
                    $("#cboTipoComprobante").html("<option>" + r.tipo_comprobante + "</option>");

                    $("#txtTiempoEntrega").val(r.tiempo_entrega);

                    $("#descuento").val(r.descuento);

                    $("#txtImpuesto").val(r.impuesto);
//                    var igvPed=r.total * parseInt($("#txtImpuesto").val())/(100+parseInt($("#txtImpuesto").val()));
                    var igvPed=r.total * parseInt(r.impuesto)/(100+parseInt(r.impuesto));
                    $("#txtIgvProforma").val(Math.round(igvPed*100)/100);

                    var subTotalPed=r.total - (r.total * parseInt(r.impuesto)/(100+parseInt(r.impuesto)));
                    $("#txtSubTotalProforma").val(Math.round(subTotalPed*100)/100);

                    $("#txtTotalProformaVer").val(Math.round(r.total*100)/100);

                    $("#txtVenta").html("Datos de la Venta");
                    $("#OcultaBR1").hide();
                    $("#OcultaBR2").hide();
                    $('button[type="submit"]').hide();
//                    $('#btnGenerarVenta').hide();
                    $('#btnEnviarCorreo').show();

                    $("#cboTipoPedido").prop('disabled', true);
                    $("#tipo_pago").prop('disabled', true);
                    $("#txtTiempoEntrega").prop('readOnly', true);
                    $("#descuento").prop('readOnly', true);
                    $("#cboFechaValidez").prop('readOnly', true);

                } else {
                    console.log('no existe');

                }

            })
        };

        $("#btnBuscarDetIng").hide();

        CargarDetalleProforma(idVenta);

        $("#btnGenerarProforma").hide();
    };

    function CargarDetalleProforma(idVenta) {
        //$('th:nth-child(2)').hide();
        //$('th:nth-child(3)').hide();
        $('table#tblDetalleProforma th:nth-child(4)').hide();
        $('table#tblDetalleProforma th:nth-child(8)').hide();

        $('table#tblDetalleProforma th:nth-child(4)').hide();
        $('table#tblDetalleProforma th:nth-child(8)').hide();

        $.post("./ajax/VentaAjax.php?op=GetDetalleProforma", {idVenta: idVenta}, function(r) {
                $("table#tblDetalleProforma tbody").html(r);
//                $("table#tblDetalleProforma tbody").html(r);
        })
    }

    function cancelarPedido(idVenta){
       // alert(idPedido);

            //alert(detalleTraerCantidad[0]);
        bootbox.confirm("¿Esta Seguro de Anular la Proforma?", function(result){

            if(result){

                $.post("./ajax/VentaAjax.php?op=CambiarEstado", {idVenta: idVenta}, function(e){

                    swal("Mensaje del Sistema", e, "success");
                   //alert(e);
                    ListadoVenta();

                });

            }

        })

    }

    function TraerCantidad(iddet_ing, cantidad) {
        var detalle = new Array(iddet_ing, cantidad);
        detalleTraerCantidad.push(detalle);
    }

    function consultarCantidad() {
        return JSON.stringify(detalleTraerCantidad);
    };

    function eliminarProforma(idVenta){
        bootbox.confirm("¿Esta Seguro de eliminar la Proforma?", function(result){
            if(result){
                $.post("./ajax/VentaAjax.php?op=EliminarProforma", {idVenta : idVenta}, function(e){

                    swal("Mensaje del Sistema", e, "success");
//                    ListadoPedidos();
                    ListadoVenta();
                });
            }

        })
    }

/*
    function GenerarVenta(e) {
      e.preventDefault();

      swal("Mensaje del Sistema", "Venta", "success");

      var detalle =  JSON.parse(consultar());
	  if (elementos.length > 0) {

	        var data = {
		        idUsuario : $("#txtIdUsuario").val(), // usuario logueado
		        idSucursal : $("#txtIdSucursal").val(),// sucursal
		        tipo_comprobante : $("#cboTipoComprobante").val(),
		        totalVenta : $("#txtTotalPed").val(),
		        nombre_cliente : $("#txtNombre").val(),
		        Documento_cliente : $("#txtNum_Documento").val(),
		        Numero_TF : $("#txtNumeroVent").val(),
		        recibi : $("#recibi").val(),
		        cambio : $("#cambio").val(),
		        tipo_pago: $("#tipo_pago").val(),

		        tipo_venta: $("#tipo_venta").val(),
		        descuento: $("#descuento").val(),
		        idClientet: $("#txtClienteFarm").val(),
		        detalle : detalle//son los productos

	      };
	      $.post("./ajax/PedidoAjax.php?op=SaveTicket", data, function(r){
	//        location.href ="../solventas/Pedido.php";
	//    Limpiar();
	            //  swal("Mensaje del Sistema", r, "success");

	            location.href ="../importadora/Pedido.php";

	                              //
	                              var es = String(r);
	            window.open('./Reportes/exVenta.php?id='+es, 'target', ' toolbar=0 , location=1 , status=0 , menubar=1 , scrollbars=0');


	                        });
	                     }//fin // si existe productos
	   else {
	     bootbox.alert("Ingrese Articulos");
	  }

    }
*/
