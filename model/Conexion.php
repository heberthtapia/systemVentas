<?php

	date_default_timezone_set("America/La_Paz");

	$conexion = new mysqli("localhost", "root", "mysql", "solventas");
	//$conexion = new mysqli("localhost", "dymmasivos", "}n#OFI[LS#~e", "dymmasivos");
	//$conexion = new mysqli("localhost", "demofarma", "-yWSSZx?0Myz", "demofarma");

	if (mysqli_connect_errno()) {
	    printf("Connect failed: %s\n", mysqli_connect_error());
	    exit();
	}

?>
