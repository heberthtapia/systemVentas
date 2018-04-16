<?php

	session_start();

	if(isset($_SESSION["idusuario"]) && $_SESSION["mnu_consulta_compras"] == 1){
		
		if ($_SESSION["superadmin"] != "S") {
			include "view/header.html";
			include "view/ComprasDetProveedor.html";
		} else {
			include "view/headeradmin.html";
			include "view/ComprasDetProveedor.html";
		}

		include "view/footer.html";
	} else {
		header("Location:index.html");
	}
		

