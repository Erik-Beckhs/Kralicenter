$(document).on("ready", init);

function init(){

	if ($("#txtMnuAlmacen").val() == "0") {

		$("#almacen").hide();
	}
	if ($("#txtMnuCompras").val() == "0") {

		$("#liCompras").hide();
	};
	if ($("#txtMnuVentas").val() == "0") {

		$("#liVentas").hide();
	};
	if ($("#txtMnuMantenimiento").val() == "0") {

		$("#liMantenimiento").hide();
	};
	if ($("#txtMnuCaja").val() == "0") {

		$("#caja").hide();
	};
	if ($("#txtMnuConsultaCompras").val() == "0") {

		$("#liConsultaCompras").hide();
	};
	if ($("#txtMnuConsultaVentas").val() == "0") {

		$("#liConsultaVentas").hide();
	};
	if ($("#txtMnuProd").val() == "0") {

		$("#liAlmacen").hide();
	};

	if ($("#txtMnuSeguridad").val() == "0") {

		$("#liSeguridad").hide();
	};
}
