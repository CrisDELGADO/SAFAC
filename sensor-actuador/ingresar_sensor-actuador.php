<?php
	error_reporting(0);
	require('../webservice/WSConexion.php');

	//DATOS SENSOR
	$nombre_sensor_actuador = $_POST['nombre_sensor_actuador'];
	$descripcion_sensor_actuador = $_POST['descripcion_sensor_actuador'];
	$unidadmedida_sensor_actuador = $_POST['unidadmedida_sensor_actuador'];
	$accion_sensor_actuador = $_POST['accion_sensor_actuador'];
	$cod_tipo_sensor_actuador = $_POST['cod_tipo_sensor_actuador'];


	$db = getConnection();

	try{
		$sql = "INSERT INTO sensor_actuador (sen_nombre, sen_descripcion, sen_medida, sen_accion, tse_id)
		VALUES ('".$nombre_sensor_actuador."','".$descripcion_sensor_actuador."','".$unidadmedida_sensor_actuador."','".$accion_sensor_actuador."',".$cod_tipo_sensor_actuador.")";

		$db->beginTransaction(); 
		$stmt = $db->prepare($sql);
		$stmt -> execute();

		$db->commit(); 
		$db = null;
		echo 'BIEN';
	}catch(Exception $e){
		$db->rollback(); 
		$codeError = $e->getCode();
		if($codeError==23505){
			echo 'ERROR';
		}else{
			echo 'Error al crear nuevo sensor-actuador';
			//echo $e->getMessage();
		}
	}


?>