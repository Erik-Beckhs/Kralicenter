$(document).on("ready", init);// Inciamos el jquery

elementos = new Array();
var objinit = new init();

function init(){

    $('#tblTraspaso').dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });

	ListadoTraspaso();// Ni bien carga la pagina que cargue el metodo

    $("#btnBuscarSucursalDestino").click(AbrirModalSucursalDestino);
    $("#btnBuscarSucursalOrigen").click(AbrirModalSucursalOrigen);
    $("#btnBuscarArticuloTraspaso").click(AbrirModalBuscarArticuloTraspaso);

    $("#btnFinalizarTraspaso").click(AbrirModalFinalTraspaso);

	$("#VerFormTraspaso").hide();// Ocultamos el formulario
	$("form#frmTraspaso").submit(SaveOrUpdate);// Evento submit de jquery que llamamos al metodo SaveOrUpdate para poder registrar o modificar datos

    $("#btnRegistrarTraspaso").click(RegistrarTraspaso);

	$("#btnNuevo").click(VerForm);// evento click de jquery que llamamos al metodo VerForm


    $("#btnAgregarSucursalDestino").click(function(e) {
        e.preventDefault();
        var opt = $("input[name=optSucursalBusqueda]:checked");

        $("#txtIdSucursalDestino").val(opt.val());
        $("#txtSucursalDestino").val(opt.attr("data-nombre"));

        $("#modalListadoSucursalDestino").modal("hide");
        $("#btnBuscarArticuloTraspaso").show();

    });


    $("#btnAgregarSucursalOrigen").click(function(e) {
        e.preventDefault();
        var opt = $("input[name=optSucursalBusqueda]:checked");

        $("#txtIdSucursalOrigen").val(opt.val());
        $("#txtSucursalOrigen").val(opt.attr("data-nombre"));

        $("#modalListadoSucursalOrigen").modal("hide");
        $("#btnBuscarArticuloTraspaso").show();

    });



	function SaveOrUpdate(e){
		e.preventDefault();

        var formData = new FormData($("#frmTraspaso")[0]);

        $.ajax({

                url: "./ajax/TraspasoAjax.php?op=SaveOrUpdate",

                type: "POST",

               data: formData,

                contentType: false,

                processData: false,

                success: function(datos)

                {

                    swal("Mensaje del Sistema", datos, "success");
                    Limpiar();
					ListadoTraspaso();
					OcultarForm();
                }

            });
	};

	function Limpiar(){
		// Limpiamos las cajas de texto
//		$("#txtIdSucursalOrigen").val("");
        $("#btnBuscarArticuloTraspaso").hide();
        $("#btnFinalizarTraspaso").hide();
        $("#txtIdSucursalDestino").val("");
        $("#txtSucursalDestino").val("");
        elementos.length = 0;
        $("#tblDetalleArticulo tbody").html("");
	}

	function VerForm(){
        Limpiar();
		$("#VerFormTraspaso").show();// Mostramos el formulario
		$("#btnNuevo").hide();// ocultamos el boton nuevo
		$("#VerListadoTraspaso").hide();
	}


	function OcultarForm(){
		$("#VerFormTraspaso").hide();// Mostramos el formulario
		$("#btnNuevo").show();// ocultamos el boton nuevo
		$("#VerListadoTraspaso").show();
	}

    this.consultar = function(){
        return JSON.stringify(elementos);
    };

    this.eliminar = function(pos){
        pos > -1 && elementos.splice(parseInt(pos),1);
        if (elementos.length==0) {
            $("#btnFinalizarTraspaso").hide();
        }
    };
}

function ListadoTraspaso(){
    var tabla = $('#tblTraspaso').dataTable(
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
//                    {   "mDataProp": "7"}

            ],"ajax":
                {
                    url: './ajax/TraspasoAjax.php?op=list',
                    type : "get",
                    dataType : "json",

                    error: function(e){
                        console.log(e.responseText);
                    }
                },
            "bDestroy": true

        }).DataTable();
    };



function eliminarTraspaso(id){// funcion que llamamos del archivo ajax/CategoriaAjax.php?op=delete linea 53
	bootbox.confirm("¿Esta Seguro de eliminar el Traspaso?", function(result){ // confirmamos con una pregunta si queremos eliminar
		if(result){// si el result es true
			$.post("./ajax/TraspasoAjax.php?op=delete", {id : id}, function(e){// llamamos la url de eliminar por post. y mandamos por parametro el id
                swal("Mensaje del Sistema", e, "success");
				ListadoTraspaso();

            });
		}

	})
}

function agregarCantidadTraspaso(){// funcion que llamamos del archivo ajax/CategoriaAjax.php?op=delete linea 53
//    bootbox.confirm("¿Esta Seguro de agregar cantidad el Artículo " + nombre + " stock " + stock + " a traspasar " + stock_traspaso + "?", function(result){ // confirmamos con una pregunta si queremos eliminar
    bootbox.confirm("¿Esta Seguro de agregar cantidad el Artículo?", function(result){ // confirmamos con una pregunta si queremos eliminar
        if(result){// si el result es true
            $.post("./ajax/TraspasoAjax.php?op=delete", {id : id}, function(e){// llamamos la url de eliminar por post. y mandamos por parametro el id
                swal("Mensaje del Sistema", e, "success");
                ListadoTraspaso();

            });
        }

    })
}

function AgregarPedCarritoTraspaso(iddet_ing, idingreso, idarticulo, stock_actual, art, cod, serie, precio_venta, descripcion, precio_compra, precio_distribuidor) {

    if (stock_actual > 0) {
            var detalles = new Array(iddet_ing, art, precio_venta, "1", "0.0", stock_actual, cod, serie, idingreso, idarticulo, descripcion, precio_compra, precio_distribuidor);
            elementos.push(detalles);
            ConsultarDetallesTraspaso();
            $("#btnFinalizarTraspaso").show();
    } else {
            bootbox.alert("No se puede agregar al detalle. No tiene stock");
    }

}

        function ConsultarDetallesTraspaso() {
                $("table#tblDetalleArticulo tbody").html("");
                var data = JSON.parse(objinit.consultar());

                $i = 1;

                for (var pos in data) {
                    $("table#tblDetalleArticulo").append(
                        "<tr><td>" + $i + "</td>" +
                        "<td>" + data[pos][6] + "</td>" +
                        "<td>" + data[pos][1] + " <input class='form-control' type='hidden' name='txtIdDetTraspaso' id='txtIdDetTraspaso[]' value='" +
                        data[pos][0] + "' /></td><td>" +
                        data[pos][2] + "</td><td><input class='form-control' type='text' name='txtCantidadActual' readonly id='txtCantidadActual[]' value='" + data[pos][5] + "' /></td>" +
                        "<td><input class='form-control' type='text' name='txtCantidadTraspasar' id='txtCantidadTraspasar[]' value='" + data[pos][3] + "' onchange='calcularTotalTraspaso(" + pos + ")' /></td>" +
                        "<td><button type='button' onclick='eliminarDetalleTraspaso(" + pos + ")' class='btn btn-danger'><i class='fa fa-remove' ></i> </button></td></tr>");
                    $i ++;
                }
            }

    function calcularTotalTraspaso(posi){
        if(posi != null){
          ModificarTraspaso(posi);
        }
    }

    function ModificarTraspaso(pos){
        var idDetTraspaso = document.getElementsByName("txtIdDetTraspaso");
        var cantTraspasar = document.getElementsByName("txtCantidadTraspasar");
       // alert(pos);
       //elementos[pos][2] = $("input[name=txtPrecioVentPed]:eq(" + pos + ")").val();

        elementos[pos][0] = idDetTraspaso[pos].value;
        if (parseInt(cantTraspasar[pos].value) <= elementos[pos][5]) {
            elementos[pos][3] = cantTraspasar[pos].value;
            if (parseInt(cantTraspasar[pos].value) <= 0) {
                bootbox.alert("<center>El Articulo " + elementos[pos][1] + " no puede estar vacio, menor o igual que 0</center>", function() {
                    elementos[pos][3] = "1";
                    cantTraspasar[pos].value = "1";
                });
            }
        } else {
            bootbox.alert("<center>El Articulo " + elementos[pos][1] + " no tiene suficiente stock para tal cantidad</center>", function() {
                elementos[pos][3] = "1";
                cantTraspasar[pos].value = "1";
            });
        }

        //alert(elementos[pos][3]);
        //alert(elementos[pos][0] + " - " + elementos[pos][2] + " - " + elementos[pos][3] + " - " + elementos[pos][4] + " - ");
        ConsultarDetallesTraspaso();
    }


function cargarDataTraspaso(idtraspaso, idsucursalorigen, idsucursaldestino, fecha, motivo){// funcion que llamamos del archivo ajax/CategoriaAjax.php linea 52
		$("#VerFormTraspaso").show();// mostramos el formulario
		$("#btnNuevo").hide();// ocultamos el boton nuevo
		$("#VerListadoTraspaso").hide();// ocultamos el listado
        $("#btnBuscarArticuloTraspaso").hide();// ocultamos el boton nuevo
        $("#btnFinalizarTraspaso").hide();// ocultamos el boton nuevo


        $("#txtIdSucursalOrigen").val(idsucursalorigen);// recibimos la variable id a la caja de texto txtIdCategoria
	    $("#txtIdSucursalDestino").val(idsucursaldestino);

        $.post("./ajax/TraspasoAjax.php?op=GetNombreSucursal", {idSucursal: idsucursaldestino}, function(r) {
            console.log(r);
            $("#txtSucursalDestino").val(r);
        })

        $("#txtFechaTraspaso").val(fecha);
        $("#txtMotivo").val(motivo);

        CargarDetallesTraspaso(idtraspaso);

//        $("#btnBuscarSucursalOrigen").hide();
        $("#btnBuscarSucursalDestino").hide();

        $("#modalVerFinalizarTraspaso").modal("show");
 	}


    function CargarDetallesTraspaso(idTraspaso) {
        $('table#tblDetalleArticulo th:nth-child(7)').hide();
        $('table#tblDetalleArticulo th:nth-child(4)').hide();
//        $('table#tblDetalleArticulo th:nth-child(3)').hide();
//        $('table#tblDetalleArticulo th:nth-child(2)').hide();
        console.log(idTraspaso);
        $.post("./ajax/TraspasoAjax.php?op=GetDetalleTraspaso", {idTraspaso: idTraspaso}, function(r) {
                $("table#tblDetalleArticulo tbody").html(r);
                $("table#tblDetalleArticulo tbody").html(r);
        })
    }



    function AbrirModalSucursalDestino(){

        idsucursalorigen = $("#txtIdSucursalOrigen").val();

        $("#modalListadoSucursalDestino").modal("show");

        $.post("./ajax/TraspasoAjax.php?op=listSucursalDestino", {idsucursalorigen : idsucursalorigen}, function(r){
            $("#SucursalesDestino").html(r);
            $("#tblSucursalesDestino").DataTable();
        });
    }

    function AbrirModalSucursalOrigen(){

        idsucursalorigen = $("#txtIdSucursalOrigen").val();

        $("#modalListadoSucursalOrigen").modal("show");

        $.post("./ajax/TraspasoAjax.php?op=listSucursalOrigen", {idsucursalorigen : idsucursalorigen}, function(r){
            $("#SucursalesOrigen").html(r);
            $("#tblSucursalesOrigen").DataTable();
        });
    }

    function AbrirModalBuscarArticuloTraspaso(){

        $("#modalListadoArticuloTraspaso").modal("show");

        var tabla = $('#tblArticulosTraspaso').dataTable(
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
                        {   "mDataProp": "11"},
                        {   "mDataProp": "12"}

                    ],"ajax":
                        {
                            url: './ajax/TraspasoAjax.php?op=listArticulosxSucursal',
                            type : "get",
                            dataType : "json",

                            error: function(e){
                                    console.log(e.responseText);
                            }
                        },
                    "bDestroy": true

                }).DataTable();
    }

    function eliminarDetalleTraspaso(ele){
        console.log(ele);
        objinit.eliminar(ele);
        ConsultarDetallesTraspaso();
    }

    function AbrirModalFinalTraspaso(){
        if ($("#txtFechaTraspaso").val() == "") {
            $("#txtFechaTraspaso").val(GetTodayDate());
        }

        $("#modalVerFinalizarTraspaso").modal("show");
    }

    function RegistrarTraspaso(e) {

//        e.preventDefault();

        if (elementos.length > 0 & ( $("#txtMotivo").val() == "") ) {
            $("#txtMotivo").select();
            return;
        }
        if (elementos.length > 0 & ( $("#txtFechaTraspaso").val() == "") ) {
            $("#txtFechaTraspaso").select();
            return;
        }
        if (elementos.length > 0 ) {
            var detalle =  JSON.parse(objinit.consultar());

            var data = {
                idusuario : $("#txtIdUsuario").val(),
                idsucursalorigen : $("#txtIdSucursalOrigen").val(),
                idsucursaldestino : $("#txtIdSucursalDestino").val(),
                fechatraspaso : $("#txtFechaTraspaso").val(),
                motivo : $("#txtMotivo").val(),
                detalle : detalle//son los productos
            };
          console.log (data);

            $.post("./ajax/TraspasoAjax.php?op=SaveOrUpdate", data, function(r) {
//              location.href ="../solventas/Pedido.php";
//              var es = String(r);
//              window.open('./Reportes/exVenta.php?id='+es, 'target', ' toolbar=0 , location=1 , status=0 , menubar=1 , scrollbars=0 , resizable=1 ,left=600pt,top=90pt, width=380px,height=880px');
                swal("Mensaje del Sistema", r, "success");
//                bootbox.alert(r);

                $("#modalVerFinalizarTraspaso").modal("hide");

                $("#VerFormTraspaso").hide();// Mostramos el formulario

                $("#btnNuevo").show();// ocultamos el boton nuevo

                $("#VerListadoTraspaso").show();

                ListadoTraspaso();

             });
          } else {
        console.log("Hola 6");
            bootbox.alert(" Debe indicar los Artículos a Traspasar ...");
         }


    }// fin funcion generar venta

    function GetTodayDate() {
       var tdate = new Date();
       var dd = tdate.getDate(); //yields day
       if (dd<10) {
          dd = '0' + dd;
       }
       var MM = tdate.getMonth()+1; //yields month
       if (MM<10) {
          MM = '0' + MM;
       }
       var yyyy = tdate.getFullYear(); //yields year

       var currentDate= yyyy + "-" + MM + "-" + dd;
       console.log (currentDate);
//     var currentDate=  dd + "-" + MM + "-" + yyyy;

       return currentDate;
    }



    function GenerarTraspaso(e) {
      e.preventDefault();

        switch ($("#cboTipoComprobante").val()) {
            case "TICKET":
                if ($("#tipo_venta").val() == "contado") {
                    if (elementos.length > 0) {
                        var detalle =  JSON.parse(consultar());
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
                        $.post("./ajax/PedidoAjax.php?op=SaveTicket", data, function(r) {
                            location.href ="../importadora/Pedido.php";
                            var es = String(r);
                            window.open('./Reportes/exVenta.php?id='+es, 'target', ' toolbar=0 , location=1 , status=0 , menubar=1 , scrollbars=0');
                        });
                    }//fin // si existe productos
                    else {
                        bootbox.alert("Ingrese Articulos");
                    }
                }
                else {
                    if (elementos.length > 0) {
                        var detalle =  JSON.parse(consultar());
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
                        });
                    }//fin // si existe productos
                    else {
                       bootbox.alert("Ingrese Articulos");
                    }
                    $("#modalCredito").modal("show");

                    GetIdVenta();
                }
                break;
            case "FACTURA":
//  recuperamos el valor de idcliente
// vamos a crear un campo que va guardar el  el id  y eso es lo que vamos usar e imprimir

// Fin Guardar Venta

                if (elementos.length > 0 & $("#txtNombre").val() != "" & $("#txtNum_Documento").val() != ""  ) {
                    var detalle =  JSON.parse(consultar());
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
                        idCliente: $("#txtClienteFarm").val(),
                        tipo_documento_de_cliente :$("#cboTipo_Documento").val(),
                        detalle : detalle//son los productos
                    };

                    $.post("./ajax/PedidoAjax.php?op=SaveFactura", data, function(r) {
                        location.href ="../importadora/Pedido.php";
                        var es = String(r);
                        window.open('./Reportes/exVenta.php?id='+es, 'target', ' toolbar=0 , location=1 , status=0 , menubar=1 , scrollbars=0 , resizable=1 ,left=600pt,top=90pt, width=380px,height=880px');
                    });
                } else {
                    bootbox.alert(" Los Campos Nombre y Documento no deben estar vacios si desea FACTURAR");
                }

                break;
            default:
        }// fin switch
    }// fin funcion generar traspaso
