<?php

	require('../webservice/WSConexion.php');
	$cod_mote = $_POST['cod_mote'];

	$db = getConnection();

	try{
		$db->beginTransaction(); 
		$sql = "DELETE FROM mote_sensor_actuador WHERE mot_id=".$cod_mote;
		$stmt = $db->prepare($sql);
		$stmt -> execute();

		$sql = "DELETE FROM mote WHERE mot_id=".$cod_mote;
		$stmt = $db->prepare($sql);
		$stmt -> execute();

		$db->commit(); 
		$db = null;
		echo 'BIEN';
	}catch(Exception $e){
		$db->rollback(); 
		$codeError = $e->getCode();
		echo 'No se pudo eliminar';
	}

?>