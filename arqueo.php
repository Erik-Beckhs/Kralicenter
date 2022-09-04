<?php


session_start();

if(isset($_SESSION["idusuario"]) && $_SESSION["mnu_caja"] == 1){

  if ($_SESSION["superadmin"] != "S") {
    include "view/header.html";
    include "view/generar_reporte arqueo_vista.html";
  } else {
    include "view/headeradmin.html";
    include "view/generar_reporte arqueo_vista.html";
  }

  include "view/footer.html";
} else {
  header("Location:index.html");
}
