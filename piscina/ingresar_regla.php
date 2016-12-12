<?php
	session_start();
	error_reporting(0);

	require('../webservice/WSConexion.php');

	//DATOS REGLA
	$sensor = $_POST['sensor'];
	$actuador = $_POST['actuador'];
	$condicion = $_POST['condicion'];
	$valor = $_POST['valor'];
	$alerta = $_POST['alerta'];
	$mensaje = $_POST['mensaje'];
	$men = $_POST['men'];

	$db = getConnection();

	try{
		$db->beginTransaction(); 
		if($men){
			//Creo nuevo mensaje o alerta
			$sql = "INSERT INTO alerta (ale_mensaje)
		     VALUES ('".$mensaje."')";

			$stmt = $db->prepare($sql);
			$stmt -> execute();

			$sql = "SELECT MAX (ale_id) as ale_id from alerta";
			$stmt = $db->prepare($sql);
			$stmt -> execute();
			$MAX_alerta = $stmt->fetchObject();
			$alerta = $MAX_alerta->ale_id;

		}
		$sql = "INSERT INTO regla (sen_id, act_id, con_id, reg_valor, ale_id)
	     VALUES (".$sensor.",".$actuador.",".$condicion.",'".$valor."',".$alerta.")";

		$stmt = $db->prepare($sql);
		$stmt -> execute();


	    $db->commit(); 
		$db = null;
	    echo 'BIEN';
	}catch(Exception $e){
		$db->rollback(); 
		$codeError = $e->getCode();
		echo 'Error al crear nueva regla';
	}


?>