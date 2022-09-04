$(document).on("ready", init);

function init(){





}


function limpiar(){

	 $("#txt_dosificacion").val("");
 $("#txt_autorizacion").val("");
 $("#txt_factura").val("");
 $("#txt_nit").val("");
	 $("#txt_fecha").val("");
 $("#txt_total").val("");

}

			function verificar_codigo_control(){


if ($("#txt_dosificacion").val()!="" && $("#txt_autorizacion").val()!="" && $("#txt_factura").val()!=""
&& $("#txt_nit").val()!="" && $("#txt_fecha").val()!="" && $("#txt_total").val()!="" ) {
				var data = [];


						data = {
									txt_dosificacion : $("#txt_dosificacion").val(),
									txt_autorizacion: $("#txt_autorizacion").val(),
									txt_factura: $("#txt_factura").val(),
									txt_nit: $("#txt_nit").val(),
									txt_fecha : $("#txt_fecha").val(),
									txt_total : $("#txt_total").val()
							};


					$.post("./ajax/Codigo_Control_verificacionAjax.php?op=verificar", data, function(r){
							swal("El Codigo de control es : ", r, "success");






limpiar()
					});


				}
					else {

						swal("Todos Los Campos Son Requeridos");
					}





			}
