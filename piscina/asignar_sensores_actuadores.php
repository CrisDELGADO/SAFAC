<?php
	error_reporting(0);
	require('../webservice/WSConexion.php');

	//DATOS
	$cod_piscina = $_POST['cod_piscina'];
	$sensores = $_POST['listaSensores'];
	$sensores = explode(',',$sensores);


	$db = getConnection();

	try{
		$db->beginTransaction(); 

		for($i=0;$i<count($sensores);$i++){
			$cod_mote_sensor_actuador = $sensores[$i];
			$sql = "INSERT INTO piscina_mote_sensor_actuador (pis_id, mse_id,pmo_estado) 
			VALUES (".$cod_piscina.",".$cod_mote_sensor_actuador.",FALSE)"; 

			$stmt = $db->prepare($sql);
			$stmt -> execute();
		}

		$db->commit(); 
		$db = null;
		echo 'BIEN';
	}catch(Exception $e){
		$db->rollback(); 
		$codeError = $e->getCode();
		if($codeError==23505){
			echo 'Datos repetidos';
		}else{
			echo 'Error al agregar sensores-actuadores';
		}
	}

?>