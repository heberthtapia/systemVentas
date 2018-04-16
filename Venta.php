<?php

	session_start();

	if(isset($_SESSION["idusuario"]) && $_SESSION["mnu_ventas"] == 1){
		include "view/header.html";

		include "view/Venta.html";

		include "view/footer.html";
	} else {
		header("Location:index.html");
	}
		

