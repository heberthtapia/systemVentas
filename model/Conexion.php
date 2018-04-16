<?php

	date_default_timezone_set("America/La_Paz");

	$conexion = new mysqli("localhost", "root", "mysql", "solventas");

	if (mysqli_connect_errno()) {
	    printf("Connect failed: %s\n", mysqli_connect_error());
	    exit();
	}

?>
