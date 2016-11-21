<?php
	//include('configuracion.php');
	$cadena = " host='localhost' port='5432' dbname='safac_v2.0' user='postgres' password='admin' ";
	try {
		$conn = pg_connect($cadena) or die ("Error de Conexion". pg_last_error());
		
              
	}catch(PDOException $e){
		echo ':( Error al conectar con la base de datos '.$e;
	}

?>