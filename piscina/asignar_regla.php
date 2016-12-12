<?php
	error_reporting(0);

	require('../webservice/WSConexion.php');

	//DATOS REGLA
	$piscina = $_POST['cod_piscina'];
	$regla = $_POST['cod_regla'];

	$db = getConnection();

	try{
		$db->beginTransaction(); 
	
		$sql = "INSERT INTO piscina_regla (pis_id, reg_id)
	     VALUES (".$piscina.",".$regla.")";

		$stmt = $db->prepare($sql);
		$stmt -> execute();


	    $db->commit(); 
		$db = null;
	    echo 'BIEN';
	}catch(Exception $e){
		$db->rollback(); 
		$codeError = $e->getCode();
		echo 'Error al asignar regla';
	}


?>