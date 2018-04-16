<?php

	session_start();

	if(isset($_SESSION["idusuario"])){

		include "view/header.html";

		include "view/footer.html";

	} else {
		header("Location:index.html");
	}
		