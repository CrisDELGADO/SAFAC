<?php
	require('../webservice/WSConexion.php');

	$codigoSensor = $_POST['codigoSensor'];
	$nombre_sensor_actuador = $_POST['nombre_sensor_actuador'];
	$descripcion_sensor_actuador = $_POST['descripcion_sensor_actuador'];
	$unidadmedida_sensor_actuador = $_POST['unidadmedida_sensor_actuador'];
	$accion_sensor_actuador = $_POST['accion_sensor_actuador'];
	$cod_tipo_sensor_actuador = $_POST['cod_tipo_sensor_actuador'];



	$db = getConnection();

	try{
		$sql = "UPDATE sensor_actuador SET sen_nombre='".$nombre_sensor_actuador."', sen_descripcion='".$descripcion_sensor_actuador."', 
		sen_medida='".$unidadmedida_sensor_actuador."', sen_accion='".$accion_sensor_actuador."', tse_id=".$cod_tipo_sensor_actuador." WHERE sen_id=".$codigoSensor." ";

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
			echo 'Datos repetidos';
		}else{
			echo 'Error al actualizar datos'.$e;
		}
	}

?>