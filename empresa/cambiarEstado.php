<?php
	$codigo_empresa = $_POST['cod_empresa'];
	$estado_empresa = $_POST['estado_empresa'];

	error_reporting(0);
	require('../webservice/WSConexion.php');

	$db = getConnection();

	try{
		$sql = "UPDATE empresa SET emp_estado=".$estado_empresa." WHERE emp_id = ".$codigo_empresa." ";

		$db->beginTransaction(); 
		$stmt = $db->prepare($sql);
		$stmt -> execute();

		$db->commit(); 
		$db = null;
		echo 'BIEN';
	}catch(Exception $e){
		$db->rollback(); 
		$codeError = $e->getCode();
		echo "Falló al cambiar estado";
	}

?>