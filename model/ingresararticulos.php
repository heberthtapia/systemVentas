<?php
	require "Conexion.php";

			$cont=0;

			for ($i=0;$i<300;$i++)
			{
				$cont=$cont+1;
				global $conexion;
				$sql = "INSERT INTO credito (idventa, fecha_pago, total_pago) 
				VALUES ('1', '2016-06-08', '100')";
				$query = $conexion->query($sql);
				//return $query;	
			}
			echo'Se agregaron 300 ventas Credito';

?>