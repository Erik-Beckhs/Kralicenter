$(document).on("ready", init);

  var total_ingresos = 0  ;
  var total_salidas = 0; //

function init() {




  ListadoIngresos();
  get_total_ingresos();
  ListadoSalidas();
  get_total_salida();
 ListadoIngresos2();
//      Listado_Salidas();




}


 function ListadoIngresos(){


  var tabla = $('#tbl_ingreso_caja').dataTable(
            {   "aProcessing": true,
            "aServerSide": true,
            dom: 'Bfrtip',
                buttons: [

                ],
            "aoColumns":[
                    {   "mDataProp": "0"},
                    {   "mDataProp": "1"},
                    {   "mDataProp": "2"},
                    {   "mDataProp": "3"},
                    {   "mDataProp": "4"}
            ],"ajax":
                {
                    url: './ajax/generar_arqueoAjax.php?op=get_listado_ingreso',
                    type : "get",
                    dataType : "json",

                    error: function(e){
                        console.log(e.responseText);
                    swal(e);                    }
                },
            "bDestroy": true

        }).DataTable();
};


 function ListadoIngresos2(){


  var tabla = $('#tb2_ingreso_caja').dataTable(
            {   "aProcessing": true,
            "aServerSide": true,
            dom: 'Bfrtip',
                buttons: [

                ],
            "aoColumns":[
                    {   "mDataProp": "0"},
                    {   "mDataProp": "1"},
                    {   "mDataProp": "2"},
                    {   "mDataProp": "3"},
                    {   "mDataProp": "4"}
            ],"ajax":
                {
                    url: './ajax/generar_arqueoAjax.php?op=get_listado_ingreso2',
                    type : "get",
                    dataType : "json",

                    error: function(e){
                        console.log(e.responseText);
                    swal(e);                    }
                },
            "bDestroy": true

        }).DataTable();
};













    function get_total_ingresos() {

var total_ingreso_mc = 0;
var total_venta_ingreso = 0;
        $.getJSON("./ajax/generar_arqueoAjax.php?op=get_total_ingresos", function(r) {
                  total_ingreso_mc =  r.total_ingreso;

 $.getJSON("./ajax/generar_arqueoAjax.php?op=get_total_ventas_ingresos", function(r) {
           total_venta_ingreso = r.total_venta;

var total1 = parseFloat(total_ingreso_mc);
var total2 = parseFloat(total_venta_ingreso);


              if (Number.isNaN(total1)) {
total1 = 0;

              }
              else {
    total1 = parseFloat(total_ingreso_mc) ;
              }

              if (Number.isNaN(total2)) {
total2 = 0;

              }
              else {
    total2 = parseFloat(total_venta_ingreso) ;
              }

total_ingresos = total1+total2;


$("#txt_total_ingreso").val(total_ingresos);

 })

        })





    }








    function ListadoSalidas(){


     var tabla = $('#tb2_salida_caja').dataTable(
               {   "aProcessing": true,
               "aServerSide": true,
               dom: 'Bfrtip',
                   buttons: [

                   ],
               "aoColumns":[
                       {   "mDataProp": "0"},
                       {   "mDataProp": "1"},
                       {   "mDataProp": "2"},
                       {   "mDataProp": "3"},
                       {   "mDataProp": "4"}
               ],"ajax":
                   {
                       url: './ajax/generar_arqueoAjax.php?op=get_listado_salida',
                       type : "get",
                       dataType : "json",

                       error: function(e){
                           console.log(e.responseText);
                       swal(e);                    }
                   },
               "bDestroy": true

           }).DataTable();
   };


   function get_total_salida() {



       $.getJSON("./ajax/generar_arqueoAjax.php?op=get_total_salidas", function(r) {
                  var total_salidas ;

              if (Number.isNaN(parseFloat(r.total_salida))) {
total_salidas = 0;

              }
              else {
    total_salidas = parseFloat(r.total_salida) ;
              }


$("#txt_total_salida").val(total_salidas);


       })
   }



   function total_caja() {
var total_ingresos= 0;
var total_salidas = 0;


      total_ingresos = $("#txt_total_ingreso").val();
     total_salidas  = $("#txt_total_salida").val();
$("#txt_total_caja").val(total_ingresos - total_salidas);

}
