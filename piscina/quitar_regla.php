<?php
	error_reporting(0);

	require('../webservice/WSConexion.php');

	//DATOS REGLA
	$piscina = $_POST['cod_piscina'];
	$regla = $_POST['cod_regla'];

	$db = getConnection();

	try{
		$db->beginTransaction(); 
	
		$sql = "DELETE FROM piscina_regla where pis_id=".$piscina." and reg_id=".$regla."";

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