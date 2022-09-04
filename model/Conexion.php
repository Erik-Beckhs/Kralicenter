<?php

 $conexion = new mysqli("localhost", "root", "", "bdklaricenter");
	if (mysqli_connect_errno()) {
	    printf("Connect failed: %s\n", mysqli_connect_error());
	    exit();
	}
