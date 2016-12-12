<?php
	error_reporting(0);

	require('../webservice/WSConexion.php');

	//DATOS EMPRESA
	$nombre_piscina = $_POST['nombre_piscina'];
	$area_piscina = $_POST['area_piscina'];
	$volumen_piscina = $_POST['volumen_piscina'];
	$codigo_piscina = $_POST['cod_piscina'];

	$db = getConnection();

	try{
		$sql = "UPDATE piscina SET pis_nombre='".$nombre_piscina."', pis_area='".$area_piscina."', 
		pis_volumen='".$volumen_piscina."' WHERE pis_id = ".$codigo_piscina." ";

		$db->beginTransaction(); 
		$stmt = $db->prepare($sql);
		$stmt -> execute();

	    $db->commit(); 
		$db = null;
	    echo 'BIEN';
	}catch(Exception $e){
		$db->rollback(); 
		$codeError = $e->getCode();
		echo 'Error al actualizar datos de pisicna';
	}


?>