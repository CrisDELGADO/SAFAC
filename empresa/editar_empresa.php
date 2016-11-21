<?php
	error_reporting(0);
	require('../webservice/WSConexion.php');

	//DATOS EMPRESA
	$codigo_empresa = $_POST['codigo'];
	$nombre_empresa = $_POST['nombre'];
	$ruc_empresa = $_POST['ruc'];
	$direccion_empresa = $_POST['direccion'];
	$telefono_empresa = $_POST['telefono'];
	$estado_empresa = $_POST['estado'];
	$ubicacion_empresa =  $_POST['ubicacion'];
	$parroquia_id =  $_POST['parroquia_id'];

	$db = getConnection();

	try{
		$sql = "UPDATE empresa SET emp_nombre='".$nombre_empresa."', emp_ruc='".$ruc_empresa."', emp_direccion='".$direccion_empresa."', emp_telefono='".$telefono_empresa."', emp_estado=".$estado_empresa.", emp_ubicacion='".$ubicacion_empresa."', par_id=".$parroquia_id." WHERE emp_id = ".$codigo_empresa." ";

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
	        echo 'No se pudo realizar los cambios';
	    }
	}

?>