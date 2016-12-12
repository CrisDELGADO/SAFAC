<?php

	require('../webservice/WSConexion.php');
	$cod_sensor_actuador = $_POST['cod_sensor_actuador'];

	$db = getConnection();

	try{
		$db->beginTransaction(); 
		$sql = "DELETE FROM sensor_actuador WHERE sen_id=".$cod_sensor_actuador;
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