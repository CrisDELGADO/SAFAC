<?php
	error_reporting(0);

	require('../webservice/WSConexion.php');

	//DATOS EMPRESA
	$nombre_empresa = $_POST['nombre'];
	$ruc_empresa = $_POST['ruc'];
	$direccion_empresa = $_POST['direccion'];
	$telefono_empresa = $_POST['telefono'];
	$estado_empresa = $_POST['estado'];
	$ubicacion_empresa =  $_POST['ubicacion'];
	$parroquia_id =  $_POST['parroquia_id'];

	$db = getConnection();

	try{
		$sql = "INSERT INTO empresa (emp_nombre, emp_ruc, emp_direccion, emp_telefono, emp_estado, emp_ubicacion, par_id)
	     VALUES ('".$nombre_empresa."','".$ruc_empresa."','".$direccion_empresa."','".$telefono_empresa."'
	     	,".$estado_empresa.",'".$ubicacion_empresa."',".$parroquia_id.")";

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
	        echo 'Verifique RUC (Ya existe)';
	    }else{
	        echo 'Error al crear nueva empresa';
	    }
	}


?>