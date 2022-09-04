$(document).on("ready", init);






var objinit = new init();

var bandera = 1;

var detalleIngresos = new Array();

var detalleTraerCantidad = new Array();

elementos = new Array();

var email = "";

//AgregatStockCant(21);btnGenerarVenta

function init() {

    var total = 0.0;
    GetNextNumero();
    //GetTotal(19);

    $('#tblPedidos').dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5',

        ]

    });

    var tablaArtPed = $('#tblArticulosPed').dataTable({
            "iDisplayLength": 4,
            "aLengthMenu": [2, 4]
        });


    ListadoPedidos();
    GetImpuesto();
    GetPrimerCliente();
    GetPrimerIDTicket();

    $("#VerForm").hide();
    $("#VerFormVentaPed").hide();

   // $("#btnAgregar").click(AgregarDetallePedPedido)
   // $("#cboTipoComprobanteVta").change(VerNumSerie);
    $("#btnBuscarCliente").click(AbrirModalCliente);
    $("#btnBuscarDetIng").click(AbrirModalDetPed);
    $("#btnEnviarCorreo").click(EnviarCorreo);
    $("#btnNuevoVent").click(VerForm);

    $("#btnNuevoProforma").click(AbrirModalProforma);

    $("form#frmPedidos").submit(GuardarPedido);

    $("#btnGenerarVenta").click(GenerarVenta);
$("form#frmcreditos").submit(Savecredito);

    //$("#btnAgregarCliente").click(function(e){
		//e.preventDefault();

		//var opt = $("input[type=radio]:checked");
	//	$("#txtIdCliente").val(opt.val());
		//$("#txtCliente").val(opt.attr("data-nombre"));
        //email = opt.attr("data-email");

		//$("#modalListadoCliente").modal("hide");
	//});

	$("#btnAgregarArtPed").click(function(e){
		e.preventDefault();

		var opt = tablaArtPed.$("input[name='optDetIngBusqueda[]']:checked", {"page": "all"});

		opt.each(function() {
			AgregarDetallePed($(this).val(), $(this).attr("data-nombre"), $(this).attr("data-precio-venta"), "1", "0.0", $(this).attr("data-stock-actual"), $(this).attr("data-codigo"), $(this).attr("data-serie"));
		})

		$("#modalListadoArticulosPed").modal("hide");
	});

    $("#btnAgregarProforma").click(function(e){
        e.preventDefault();

        var opt = $("input[type=radio]:checked");
//        $("#txtIdCliente").val(opt.val());
        $("#txtNombre").val(opt.attr("data-nombre"));
        $("#tipo_pago").val(opt.attr("data-tipo_pago"));
        $("#tipo_venta").val(opt.attr("data-tipo_venta"));
        $("#descuento").val(opt.attr("data-descuento"));
        $("#txtNum_Documento").val(opt.attr("data-num_documento"));
//        $("#cboTipo_Documento").val(opt.attr("data-tipo_documento"));
        $("#txtClienteFarm").val(opt.attr("data-id_cliente"));

        $("#modalListadoProformas").modal("hide");
        VerForm();
        $("#txtIsProforma").val(opt.attr("id"));
//        bootbox.alert(opt.val());
        CargarDetalleProformaPed(opt.val())
    });

// OBETENEMOS EL ID DE TICKET
  function GetPrimerIDTicket() {
    var data = {

        txtIdSucursal:$("#txtIdSucursal").val(),

      };
       $.post("./ajax/PedidoAjax.php?op=GetPrimerIDTicket", data, function(r){// llamamos la url por post. function(r). r-> llamada del callback

            //$.toaster({ priority : 'success', title : 'Mensaje', message : r});

  $("#txtNumeroVent").val(r)

     });
  }


    function FormVenta(total, idpedido){
        $("#VerFormVentaPed").show();
        $("#btnNuevo").hide();
        $("#VerForm").hide();
        $("#VerListado").hide();
        $("#txtTotalVent").val(total);
        $("#txtIdPedido").val(idpedido);
        $("#lblTitlePed").html("Venta");
        //Ver();
    }

    //funcion donde llamamos al formulario de Proformas y ejecutamos
    function AbrirModalProforma(){
      $("#modalListadoProformas").modal("show");

      $.post("./ajax/PedidoAjax.php?op=listProformas", function(r) {
              $("#Proformas").html(r);
              $("#tblProformas").DataTable();
          });
    }

    function EnviarCorreo(){
        bootbox.prompt({
            title: "Ingrese el correo para enviar el detalle de la compra",
            value: email,
            callback: function(result) {
                if (result !== null) {
                    $.post("./ajax/VentaAjax.php?op=EnviarCorreo", {result:result, idPedido : $("#txtIdPedido").val()}, function(r){
                        bootbox.alert(r);
                    })
                }
            }
        });
    }




    function Savecredito(e){
        e.preventDefault();// para que no se recargue la pagina

        $.post("./ajax/CreditoAjax.php?op=SaveOrUpdateP", $(this).serialize(), function(r){// llamamos la url por post. function(r). r-> llamada del callback

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





        // con esta funcion agregamos los clientes del html pedidos venta no va direccionas a la carpeta clientesajax
        function Cliente_Add_Recoger(){

          //e.preventDefault();// para que no se recargue la pagina

          var data = {
               cboTipo_Persona : $("#cboTipo_Persona").val(),
              txtNombre : $("#txtNombre").val(),
              cboTipo_Documento:$("#cboTipo_Documento").val(),
              txtNum_Documento:$("#txtNum_Documento").val(),
              txtEstado:$("#txtEstado").val(),

            };
             $.post("./ajax/ClienteAjax.php?op=ClienteGuardarVentas", data, function(r){// llamamos la url por post. function(r). r-> llamada del callback

                  //$.toaster({ priority : 'success', title : 'Mensaje', message : r});

        $("#txtIdClienteA").val(r)

           });

        };
        //fin funcion cliente add


      // Con esta funcion vamos a  generar la venta de nuestros productos para la farmcia



  Cliente_Add_Recoger();


    function GenerarVenta(e){
      e.preventDefault();

      var idVenta = $("#txtIsProforma").val();

switch ($("#cboTipoComprobanteVta").val()) {
  case "TICKET":
if ($("#tipo_venta").val() == "contado") {

  if (elementos.length > 0) {
      var detalle =  JSON.parse(consultar());

      var data = {
      idUsuario : $("#txtIdUsuario").val(), // usuario logueado
      idSucursal : $("#txtIdSucursal").val(),// sucursal
      tipo_comprobante : $("#cboTipoComprobanteVta").val(),
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

      if (idVenta!="0") {
          $.post("./ajax/PedidoAjax.php?op=ProformaaFactura", {idPedido:idVenta}, function(r){
          });
      }

      $.post("./ajax/PedidoAjax.php?op=SaveTicket", data, function(r){

//        location.href ="../solventas/Pedido.php";
//    Limpiar();
            //  swal("Mensaje del Sistema", r, "success");

            location.href ="../kralicenter/Pedido.php";

                              //
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
      tipo_comprobante : $("#cboTipoComprobanteVta").val(),
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

      if (idVenta!="0") {
          $.post("./ajax/PedidoAjax.php?op=ProformaaFactura", {idPedido:idVenta}, function(r){
          });
      }

      $.post("./ajax/PedidoAjax.php?op=SaveTicket", data, function(r){
  //        location.href ="../solventas/Pedido.php";
  //    Limpiar();
            //  swal("Mensaje del Sistema", r, "success");

                        });
                     }//fin // si existe productos
                     else {
                       bootbox.alert("Ingrese Articulos");
                    }
    $("#modalcredito").modal("show");
    $("#modalListadoClienteP").modal("hide");
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
           tipo_comprobante : $("#cboTipoComprobanteVta").val(),
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



         $.post("./ajax/PedidoAjax.php?op=SaveFactura", data, function(r){
          //        swal("Mensaje del Sistema", r, "success");
                     //delete this.elementos;
//$("#modalListadoCliente").modal("hide");

//$("#VerForm").hide();

//$("#btnNuevoVent").show();
//$("#tblPedidos").show();
//$("#VerListado").show();

location.href ="../kralicenter/Pedido.php";

                  //
                  var es = String(r);
window.open('./Reportes/exVenta.php?id='+es, 'target', ' toolbar=0 , location=1 , status=0 , menubar=1 , scrollbars=0 , resizable=1 ,left=600pt,top=90pt, width=380px,height=880px');


         });
      }else {
        bootbox.alert(" Los Campos Nombre y Documento no deben estar vacios si desea FACTURAR");
     }








  break;



  default:

}// fin case




}// fimn funcion generar venta












    function Limpiar(){

     $("#txtCantidaPed[]").val("0");
     $("#txtNombre").val("");
     $("#txtNum_Documento").val("");
     $("#recibi").val("");
     $("#cambio").val("");
     $("#txtClienteFarm").val("");
        elementos.length = 0;
        $("#tblDetallePedido tbody").html("");
        GetNextNumero();
    }

    function GetTotal(idPedido) {
        $.getJSON("./ajax/PedidoAjax.php?op=GetTotal", {idPedido: idPedido}, function(r) {
                if (r) {
                    total = r.Total;
                    $("#txtTotalVent").val(total);

                    var igvPed=total * parseInt($("#txtImpuesto").val())/(100+parseInt($("#txtImpuesto").val()));
                    $("#txtIgvPedVer").val(Math.round(igvPed*100)/100);

                    var subTotalPed=total - (total * parseInt($("#txtImpuesto").val())/(100+parseInt($("#txtImpuesto").val())));
                    $("#txtSubTotalPedVer").val(Math.round(subTotalPed*100)/100);

                    $("#txtTotalPedVer").val(Math.round(total*100)/100);
                }
        });
    }

    function GetNextNumero() {
        $.getJSON("./ajax/PedidoAjax.php?op=GetNextNumero", function(r) {
                if (r) {
                    $("#txtNumeroPed").val(r.numero);
                }
        });
    }


    function GetImpuesto() {

        $.getJSON("./ajax/GlobalAjax.php?op=GetImpuesto", function(r) {
                $("#txtImpuestoPed").val(r.porcentaje_impuesto);
                $("#SubTotal").html(r.simbolo_moneda + " Sub Total:");
                $("#IGV").html(r.simbolo_moneda +" " + r.nombre_impuesto+ " "  +r.porcentaje_impuesto + "%:");
                $("#Total").html(r.simbolo_moneda + " Total:");


                $("#txtImpuesto").val(r.porcentaje_impuesto);
                $("#SubTotal_Ver").html(r.simbolo_moneda + " Sub Total:");
                $("#IGV_Ver").html(r.simbolo_moneda +" " + r.nombre_impuesto+ " "  +r.porcentaje_impuesto + "%:");
                $("#Total_Ver").html(r.simbolo_moneda + " Total:");

        })
    }

    function VerNumSerie(){
    	var nombre = $("#cboTipoComprobanteVta").val();

            $.getJSON("./ajax/PedidoAjax.php?op=GetTipoDocSerieNum", {nombre: nombre}, function(r) {
                if (r) {
                    $("#txtSerie").val(r.ultima_serie);
                    $("#txtNumeroPed").val(r.ultimo_numero);
                }
            });
    }

    function VerForm(){
        $("#txtIsProforma").val("0");
        $("#VerForm").show();
        $("#btnNuevoVent").hide();
        $("#cboTipoPedido").hide();
        $("#txtNumeroPed").hide();
        $("#inputTipoPed").hide();
        $("#inputNumero").hide();
        $('#btnRegPedido').hide();
        $("#VerListado").hide();
        $("#btnNuevoProforma").hide();
        ComboTipoDoc();

    }

    function OcultarForm(){
        $("#VerForm").hide();
        $("#btnNuevoVent").show();
        $("#VerListado").show();
    }

    function AbrirModalCliente(){
		$("#modalListadoClienteP").modal("show");

    var recibi=$("#recibi").val()
    var total=($("#txtTotalPed").val() )-(($("#descuento").val()*$("#txtTotalPed").val())/100)
    $("#cambio").val(recibi-total);

		$.post("./ajax/PedidoAjax.php?op=listClientesP", function(r){
            $("#ClienteP").html(r);
            $("#tblClientesP").DataTable();
        });
	}

  	// ======================================================conbinacion de teclados atajos==============================================

  	var eventoControlado = false;

  window.onload = function() { document.onkeypress = mostrarInformacionCaracter;

  document.onkeyup = mostrarInformacionTecla; }




  function mostrarInformacionCaracter(evObject) {

                  var msg = ''; var elCaracter = String.fromCharCode(evObject.which);

                  if (evObject.which!=0 && evObject.which!=13) {

                  msg = 'Tecla pulsada: ' + elCaracter;

                  control.innerHTML += msg + '-----------------------------<br/>'; }

                  else { msg = 'Pulsada tecla especial';

                  control.innerHTML += msg + '-----------------------------<br/>';}

                  eventoControlado=true;

  }



  function mostrarInformacionTecla(evObject) {

                  var msg = ''; var teclaPulsada = evObject.keyCode;



                  eventoControlado = false;
    if(teclaPulsada == 112){

AbrirModalDetPed();
      $("#modalListadoCliente").hide();
$("#VerForm").show();
  		//AbrirModalCliente();
          $("#btnNuevoVent").hide();
          $("#cboTipoPedido").hide();
          $("#txtNumeroPed").hide();
          $("#inputTipoPed").hide();
          $("#inputNumero").hide();
          $('#btnRegPedido').hide();
          $("#VerListado").hide();

    }


      if(teclaPulsada == 113){
$("#modalListadoArticulosPed").hide();
      AbrirModalCliente();


           $("#VerForm").show();
        		//AbrirModalCliente();
                $("#btnNuevoVent").hide();
                $("#cboTipoPedido").hide();
                $("#txtNumeroPed").hide();
                $("#inputTipoPed").hide();
                $("#inputNumero").hide();
                $('#btnRegPedido').hide();
                $("#VerListado").hide();




    }


        if(teclaPulsada == 107){

      alert('estas presionando f2');

    }

  }




	function AbrirModalDetPed(){
    $("#modalListadoArticulosPed").modal("show");
          var tabla = $('#tblArticulosPed').dataTable(
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
                          url: './ajax/PedidoAjax.php?op=listDetIng',
                          type : "get",
                          dataType : "json",

                          error: function(e){
                              console.log(e.responseText);
                          }
                      },
                  "bDestroy": true

              }).DataTable();
	}

    function AgregatStockCant(idPedido){

        $.ajax({
            url: './ajax/PedidoAjax.php?op=GetDetalleCantStock',
            dataType: 'json',
            data:{idPedido: idPedido},
            success: function(s){
                for(var i = 0; i < s.length; i++) {
                    AgregarDetalleCantStock(s[i][0],
                                    s[i][1],
                                    s[i][2]
                            );

                }
              //      Ver();
            },
            error: function(e){
               console.log(e.responseText);
            }
        });

    };

    function AgregarDetallePed(iddet_ing, nombre, precio_venta, cant, desc, stock_actual, codigo, serie) {
        var detalles = new Array(iddet_ing, nombre, precio_venta, cant, desc, stock_actual, codigo, serie);
        elementos.push(detalles);
        ConsultarDetallesPed();
    }

    function consultar() {
        return JSON.stringify(elementos);
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

};

function ListadoPedidos(){
            var tabla = $('#tblPedidos').dataTable(
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
                    url: './ajax/PedidoAjax.php?op=list',
                    type : "get",
                    dataType : "json",

                    error: function(e){
                        console.log(e.responseText);
                    }
                },
            "bDestroy": true

        }).DataTable();
    };
function eliminarDetallePed(ele){
        console.log(ele);
        objinit.eliminar(ele);
        ConsultarDetallesPed();
    }

function ConsultarDetallesPed() {
        $("table#tblDetallePedido tbody").html("");
        var data = JSON.parse(objinit.consultar());

        for (var pos in data) {

//----------------------------------------------------------DETALLE DE VENTA ---------------------------------------------------------------------------

            $("table#tblDetallePedido").append("<tr><td>" + data[pos][1] + " <input class='form-control' type='hidden' name='txtIdDetIng' id='txtIdDetIng[]' value='" + data[pos][0] + "' /></td><td> " + data[pos][6] + "</td><td> " + data[pos][7] + "</td><td>" + data[pos][5]+ "</td><td><input class='form-control' type='text' name='txtPrecioVentPed' id='txtPrecioVentPed[]' value='" + data[pos][2] + "' onchange='calcularTotalPed(" + pos + ")' /></td><td><input class='form-control' type='text' name='txtCantidaPed' id='txtCantidaPed[]'   value='" + data[pos][3] + "'onchange='calcularTotalPed1(" + pos + ")' /></td><td><input class='form-control' type='hidden' name='txtDescuentoPed' id='txtDescuentoPed[]'  value='" + data[pos][4] + "' onchange ='calcularTotalPed(" + pos + ")' /></td><td><button type='button' onclick='eliminarDetallePed(" + pos + ")' class='btn btn-danger'><i class='fa fa-remove' ></i> </button></td></tr>");
        }
        calcularIgvPed();
        calcularSubTotalPed();
        calcularTotalPed();
    }

    function calcularIgvPed(){
        var suma = 0;

        var data = JSON.parse(objinit.consultar());

        for (var pos in data) {
            suma += parseFloat(data[pos][3] *  (data[pos][2]));
        }
        var igvPed=suma * parseInt($("#txtImpuesto").val())/(100+parseInt($("#txtImpuesto").val()));
        $("#txtIgvPed").val(Math.round(igvPed*100)/100);
    }

    function calcularSubTotalPed(){
        var suma = 0;
        var data = JSON.parse(objinit.consultar());
        for (var pos in data) {
            suma += parseFloat(data[pos][3] * (data[pos][2]));
        }
        var subTotalPed=suma - (suma * parseInt($("#txtImpuesto").val())/(100+parseInt($("#txtImpuesto").val())));
        $("#txtSubTotalPed").val(Math.round(subTotalPed*100)/100);
    }

    function calcularTotalPed(posi){
        if(posi != null){
          ModificarPed(posi);
        }
        var suma = 0;
        var data = JSON.parse(objinit.consultar());
        for (var pos in data) {
            suma += parseFloat(data[pos][3] * (data[pos][2]));
        }
        calcularIgvPed();
        calcularSubTotalPed();
        $("#txtTotalPed").val(Math.round(suma*100)/100);

    }

    function calcularTotalPed1(posii){
        if(posii != null){
          ModificarPed(posii);
        }
        var suma = 0;
        var data = JSON.parse(objinit.consultar());
        for (var pos in data) {
            suma += parseFloat(data[pos][3] * (data[pos][2]));
        }
        calcularIgvPed();
        calcularSubTotalPed();
        $("#txtTotalPed").val(Math.round(suma*100)/100);

    }

    function cargarDataPedido(idPedido, tipo_pedido, numero, Cliente, total, correo){
        bandera = 2;
        $("#VerForm").show();
        $("#btnNuevoProforma").hide();
        $("#btnNuevoVent").hide();
        $("#VerListado").hide();
        $("#txtIdPedido").val(idPedido);
        $("#txtCliente").hide();
        $("#cboTipoPedido").hide();
        email = correo;
        var igvPed=total * parseInt($("#txtImpuesto").val())/(100+parseInt($("#txtImpuesto").val()));
        $("#txtIgvPed").val(Math.round(igvPed*100)/100);

        var subTotalPed=total - (total * parseInt($("#txtImpuesto").val())/(100+parseInt($("#txtImpuesto").val())));
        $("#txtSubTotalPed").val(Math.round(subTotalPed*100)/100);

        $("#txtTotalPed").val(Math.round(total*100)/100);

        if (tipo_pedido == "Venta") {
            $.getJSON("./ajax/PedidoAjax.php?op=GetVenta", {idPedido:idPedido}, function(r) {
                if (r) {

                    $("#VerFormVentaPed").show();
                    $("#VerDetallePedido").hide();

                    $("#VerTotalesDetPedido").hide();
                    $("#inputTotal").hide();
                    $("#txtTotalVent").hide();
                    $("#VerRegPedido").hide();
                    $("#txtClienteVent").val(Cliente);
                    $("#txtSerieVent").val(r.serie_comprobante);
                    $("#txtNumeroVent").val(r.num_comprobante);
                    $("#cboTipoVenta").val(r.tipo_venta);
                    $("#cboTipoComprobanteVta").html("<option>" + r.tipo_comprobante + "</option>");

                    var igvPed=r.total * parseInt($("#txtImpuesto").val())/(100+parseInt($("#txtImpuesto").val()));
                    $("#txtIgvPedVer").val(Math.round(igvPed*100)/100);

                    var subTotalPed=r.total - (r.total * parseInt($("#txtImpuesto").val())/(100+parseInt($("#txtImpuesto").val())));
                    $("#txtSubTotalPedVer").val(Math.round(subTotalPed*100)/100);

                    $("#txtTotalPedVer").val(Math.round(r.total*100)/100);
                    $("#txtVenta").html("Datos de la Venta");
                    $("#OcultaBR1").hide();
                    $("#OcultaBR2").hide();
                    $('button[type="submit"]').hide();
                    $('#btnGenerarVenta').hide();
                    $('#btnEnviarCorreo').show();
                }

            })
        };





        $("#txtNumeroPed").hide();

        $("#txtImpuestoPed").hide();
        $("#Porcentaje").hide();
        $("#btnBuscarCliente").hide();
        $("#btnBuscarDetIng").hide();

        $("#inputCliente").hide();
        $("#inputImpuesto").hide();
        $("#inputTipoPed").hide();
        $("#inputNumero").hide();

        CargarDetallePedido(idPedido);
        $("#cboTipoPedido").prop("disabled", true);
        $("#txtNumeroPed").prop("disabled", true);
        $("#txtCliente").prop("disabled", true);

        $('button[type="submit"]').hide();
        $('#btnGenerarVenta').hide();
        //$('button[type="submit"]').attr('disabled','disabled');
        $("#btnBuscarDetIng").prop("disabled", true);
        $("#btnBuscarCliente").prop("disabled", true);

        $("#cboFechaDesdeVent").hide();
        $("#cboFechaHastaVent").hide();
        $("#lblDesde").hide();
        $("#lblHasta").hide();
        $("#btnNuevoPedido").hide();
        $("#txtTotalVent").val(total);
    }

    function CargarDetalleProformaPed(idVenta) {
/*
        $('table#tblDetallePedidoVer th:nth-child(4)').hide();
        $('table#tblDetallePedidoVer th:nth-child(8)').hide();

        $('table#tblDetallePedido th:nth-child(4)').hide();
        $('table#tblDetallePedido th:nth-child(8)').hide();

        $.post("./ajax/PedidoAjax.php?op=GetDetalleProformaPed", {idVenta: idVenta}, function(r) {
                $("table#tblDetallePedidoVer tbody").html(r);
                $("table#tblDetallePedido tbody").html(r);
        })
*/

        $.post("./ajax/PedidoAjax.php?op=GetDetalleProformaPed", {idVenta: idVenta}, function(r) {
          datos = JSON.parse(r);
          $.each(datos, function(key, val) {
              console.log (val.articulo);
              AgregarPedCarritoProforma(val.iddetalle_ingreso,val.stock_actual,val.articulo,val.codigo,val.serie,val.precio_venta,val.cantidad);
//              temperaturas.push(val.temperatura);
//              fechas.push(val.fecha);
          })
        });
    }

    function CargarDetallePedido(idPedido) {
        //$('th:nth-child(2)').hide();
        //$('th:nth-child(3)').hide();
        $('table#tblDetallePedidoVer th:nth-child(4)').hide();
        $('table#tblDetallePedidoVer th:nth-child(8)').hide();

        $('table#tblDetallePedido th:nth-child(4)').hide();
        $('table#tblDetallePedido th:nth-child(8)').hide();

        $.post("./ajax/PedidoAjax.php?op=GetDetallePedido", {idPedido: idPedido}, function(r) {
                $("table#tblDetallePedidoVer tbody").html(r);
                $("table#tblDetallePedido tbody").html(r);
        })
    }

    function cancelarPedido(idPedido){
       // alert(idPedido);

            //alert(detalleTraerCantidad[0]);
        bootbox.confirm("¿Esta Seguro de Anular la Venta?", function(result){

            if(result){

                $.ajax({
                    url: './ajax/PedidoAjax.php?op=TraerCantidad',
                    dataType: 'json',
                    data:{idPedido: idPedido},
                    success: function(s){
                        for(var i = 0; i < s.length; i++) {
                            //alert(s[i][0] + " - " + s[i][1]);
                            TraerCantidad(s[i][0], s[i][1]);

                        }
                           var detalle =  JSON.parse(consultarCantidad());
                var data = {idPedido : idPedido, detalle: detalle};

                $.post("./ajax/PedidoAjax.php?op=CambiarEstado", data, function(e){

                    swal("Mensaje del Sistema", e, "success");
                   //alert(e);
                    ListadoPedidos();

                });

                    },

                    error: function(e){
                       console.log(e.responseText);
                    }
                });
                 //Ver();


                detalleTraerCantidad.length = 0;
            }

        })
    }

    function TraerCantidad(iddet_ing, cantidad) {
        var detalle = new Array(iddet_ing, cantidad);
        detalleTraerCantidad.push(detalle);
    }

    function eliminarPedido(idPedido){
        bootbox.confirm("¿Esta Seguro de eliminar el pedido?", function(result){
            if(result){
                $.post("./ajax/PedidoAjax.php?op=EliminarPedido", {idPedido : idPedido}, function(e){

                    swal("Mensaje del Sistema", e, "success");
                    ListadoPedidos();
                    ListadoVenta();
                });
            }

        })
    }


    function VerMsj(){
        bootbox.alert("No se puede generar la venta, este pedido esta cancelado");
    }

    function ModificarPed(pos){
        var idDetIng = document.getElementsByName("txtIdDetIng");
        var pvd = document.getElementsByName("txtPrecioVentPed");
        var cantPed = document.getElementsByName("txtCantidaPed");
        var descPed = document.getElementsByName("txtDescuentoPed");
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
                    calcularTotalPed();
                });
            }
        } else {
            bootbox.alert("<center>El Articulo " + elementos[pos][1] + " no tiene suficiente stock para tal cantidad</center>", function() {
                elementos[pos][3] = "1";
                cantPed[pos].value = "1";
                calcularIgvPed();
                calcularSubTotalPed();
                calcularTotalPed();
            });
        }

        elementos[pos][4] = descPed[pos].value;
        //alert(elementos[pos][3]);
        //alert(elementos[pos][0] + " - " + elementos[pos][2] + " - " + elementos[pos][3] + " - " + elementos[pos][4] + " - ");
        calcularIgvPed();
        calcularSubTotalPed();
        calcularTotalPed();
        ConsultarDetalles();
    }

    function FormVenta(total, idpedido, total, Cliente, correo){
        $("#VerFormVentaPed").show();
        $("#btnNuevo").hide();
        $("#btnEnviarCorreo").hide();
        $("#VerListado").hide();
        $("#txtTotalVent").val(total);
        $("#txtClienteVent").val(Cliente);
        $("#txtIdPedido").val(idpedido);
        email = correo;
        $("#lblTitlePed").html("Venta");
        ComboTipoDoc();
        CargarDetallePedido(idpedido);
        var igvPed=total * parseInt($("#txtImpuesto").val())/(100+parseInt($("#txtImpuesto").val()));
        $("#txtIgvPedVer").val(Math.round(igvPed*100)/100);

        var subTotalPed=total - (total * parseInt($("#txtImpuesto").val())/(100+parseInt($("#txtImpuesto").val())));
        $("#txtSubTotalPedVer").val(Math.round(subTotalPed*100)/100);

        $("#txtTotalPedVer").val(Math.round(total*100)/100);
        AgregatStockCant(idpedido);
    }


    function AgregatStockCant(idPedido){

        $.ajax({
            url: './ajax/PedidoAjax.php?op=GetDetalleCantStock',
            dataType: 'json',
            data:{idPedido: idPedido},
            success: function(s){
                for(var i = 0; i < s.length; i++) {
                    AgregarDetalleCantStock(s[i][0],
                                    s[i][1],
                                    s[i][2]
                            );

                }
              //      Ver();
            },
            error: function(e){
               console.log(e.responseText);
            }
        });

    };

    function Ver(){
        var data = JSON.parse(consultarCantidad());

                for (var pos in data) {
                    alert(data[pos][1]);
                }
    }


    function AgregarDetalleCantStock(iddet_ing, stock, cant) {
        var detalles = new Array(iddet_ing, stock, cant);
        detalleIngresos.push(detalles);
    }

    function consultarCantidad() {
        return JSON.stringify(detalleTraerCantidad);
    };

    this.consultarCantidad = function(){
        return JSON.stringify(detalleTraerCantidad);
    };

    this.consultarDet = function(){
        return JSON.stringify(detalleIngresos);
    };

    function ComboTipoDoc() {

        $.get("./ajax/PedidoAjax.php?op=listTipoDoc", function(r) {
                $("#cboTipoComprobanteVta").html(r);

        })
    }

    function AgregarPedCarrito(iddet_ing, stock_actual, art, cod, serie, precio_venta){

            if (stock_actual > 0) {
                var detalles = new Array(iddet_ing, art, precio_venta, "1", "0.0", stock_actual, cod, serie);
                elementos.push(detalles);
                ConsultarDetallesPed();
            } else {
                bootbox.alert("No se puede agregar al detalle. No tiene stock");
            }

    }
    function AgregarPedCarritoProforma(iddet_ing, stock_actual, art, cod, serie, precio_venta, cantidad){

            if (stock_actual > 0) {
                var detalles = new Array(iddet_ing, art, precio_venta, cantidad, "0.0", stock_actual, cod, serie);
                elementos.push(detalles);
                ConsultarDetallesPed();
            } else {
                bootbox.alert("No se puede agregar al detalle. No tiene stock");
            }

    }

    function GetPrimerCliente() {
        $.getJSON("./ajax/PedidoAjax.php?op=GetPrimerCliente", function(r) {
                if (r) {
                    $("#txtIdCliente").val(r.idpersona);
                    $("#txtCliente").val(r.nombre);
                }
        });
    }




    /*EN ESTA SECCION MODIFICAMOS EL AREA DE CLIENTES EN VENTAS PARA QUE CUANDO SELECCIONE  UNO ME APARESCA EN AUTOMATICO EN MIS CASILLAS*/
  function AgregarCliente(nombre,num_comprobante,idpersona){


              $("#txtNombre").val(nombre);
              $("#txtNum_Documento").val(num_comprobante);
              $("#txtClienteFarm").val(idpersona);


  }



  function GuardarPedido(){

swal("estoy en guardar pedido");
    //e.preventDefault();
        if ($("#txtIdClienteA").val() != "") {
            if (elementos.length > 0) {
              var detalle =  JSON.parse(consultar());
                var data = {
                    idUsuario : $("#txtIdUsuario").val(),
                    idCliente : $("#txtIdClienteA").val(),
                    idSucursal : $("#txtIdSucursal").val(),
                    tipo_pedido : $("#cboTipoPedido").val(),
                    numero : $("#txtNumeroPed").val(),
                    detalle : detalle
                };

swal("estamos dentro de este buvle ");

                $.post("./ajax/PedidoAjax.php?op=Save", data, function(r){
                           swal("Mensaje del Sistema", r, "success");
                           // delete this.elementos;

                            //$("#tblDetallePedido tbody").html("");
                            $("#txtIgvPed").val("");
                            $("#txtTotalPed").val("");
                            $("#txtSubTotalPed").val("");
                            OcultarForm();
                            $("#VerFormPed").hide();// Mostramos el formulario
                            $("#btnNuevoPedido").show();
                            Limpiar();
                            $("#txtCliente").val("");
                            ListadoVenta();
                            GetPrimerCliente();

                });
            } else {
                bootbox.alert("Debe agregar articulos al detalle");
            }
        } else {
            bootbox.alert("Debe elegir un cliente");
        }

  }
