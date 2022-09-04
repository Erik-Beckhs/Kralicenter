
function generar_numero(){
  var text = "";
    var possible = "0123456789";

    for (var i = 0; i < 12; i++){
      text += possible.charAt(Math.floor(Math.random() * possible.length));
  }

 document.getElementById("txt-codigo-barras").value = text;
 JsBarcode("#barcode", text);
}


function verificarcodigo() {



}

function imprimir() {


}
