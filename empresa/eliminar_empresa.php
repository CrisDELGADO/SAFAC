<?php

	require('../webservice/WSConexion.php');

	$cod_empresa = $_POST['cod_empresa'];

	$db = getConnection();

	try{
		$db->beginTransaction(); 

		$sql = "DELETE FROM empresa WHERE emp_id=".$cod_empresa;
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