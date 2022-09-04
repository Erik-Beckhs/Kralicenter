$(document).on("ready", init);

var objinit = new init();
var bandera = 1;

function init() {

    $("#VerFormAperturaCaja").show();

    getCajaAbierta();

    $("form#frmDocAperturaCaja").submit(GuardarDocAperturaCaja);


    function GuardarDocAperturaCaja(e){

        e.preventDefault();

        var data = [];
        if (bandera == 1) {
            data  = {
                idSucursal: $("#txtIdSucursal").val(),
                tipo_transaccion: $("#tipomovimiento").val(),
                motivo: $("#txtmotivo").val(),
                monto : $("#txtmontoapertura").val(),
                idusuario :$("#txtIdUsuario").val(),
            };
        } else {
            data = {
                idSucursal: $("#txtIdSucursal").val(),
                tipo_transaccion: $("#tipomovimiento").val(),
                motivo: $("#txtmotivo").val(),
                monto : $("#txtmontoapertura").val(),
                idusuario :$("#txtIdUsuario").val(),
            };
        }
        $.post("./ajax/apertura_cajaAjax.php?op=Save", data, function(r) {
          swal("Mensaje del Sistema", r, "success");
//          location.reload();
        });
    }

    function getCajaAbierta() {
      $.post("./ajax/apertura_cajaAjax.php?op=CajaAbierta", null, function(r) {// llamamos la url por post. function(r). r-> llamada del callback
        $("#txtmontoapertura").val(r);
        if (r>0) {
          $("#btnRegAct").text('Actualizar Monto de Apertura de Caja');
        } else {
          $("#btnRegAct").text('Registrar Monto de Apertura de Caja');
        }
       
      });
    }
}

