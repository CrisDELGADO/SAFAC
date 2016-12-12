<?php
	session_start();
	error_reporting(0);

	require('../webservice/WSConexion.php');

	//DATOS EMPRESA
	$nombre_piscina = $_POST['nombre_piscina'];
	$area_piscina = $_POST['area_piscina'];
	$volumen_piscina = $_POST['volumen_piscina'];
	$empresa_id = $_SESSION['IDEMPRESA'];

	$db = getConnection();

	try{
		$sql = "INSERT INTO piscina (pis_nombre, pis_area, pis_volumen, emp_id)
	     VALUES ('".$nombre_piscina."','".$area_piscina."','".$volumen_piscina."',".$empresa_id.")";

		$db->beginTransaction(); 
		$stmt = $db->prepare($sql);
		$stmt -> execute();


	    $db->commit(); 
		$db = null;
	    echo 'BIEN';
	}catch(Exception $e){
		$db->rollback(); 
		$codeError = $e->getCode();
		echo 'Error al crear nueva pisicna';
	}


?>