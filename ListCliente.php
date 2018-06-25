<?php

	session_start();

	if(isset($_SESSION["idusuario"]) && $_SESSION["mnu_consulta_ventas"] == 1){

		if ($_SESSION["superadmin"] != "S") {
			include "view/header.html";
			include "view/ListCliente.php";
		} else {
			include "view/headeradmin.html";
			include "view/ListCliente.php";
		}

		include "view/footer.html";
	} else {
		header("Location:index.html");
	}

?>
