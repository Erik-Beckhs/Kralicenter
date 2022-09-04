<?php

	session_start();

	if(isset($_SESSION["idusuario"]) && $_SESSION["mnu_caja"] == 1){

		if ($_SESSION["superadmin"] != "S") {

			include "view/header.html";

			include "view/apertura_caja.html";

		} else {

			include "view/headeradmin.html";

			include "view/apertura_caja.html";

		}

		include "view/footer.html";

	} else {

		header("Location:index.html");

	}

