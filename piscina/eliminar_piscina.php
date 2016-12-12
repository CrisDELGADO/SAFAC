<?php

	require('../webservice/WSConexion.php');

	$cod_piscina = $_POST['cod_piscina'];

	$db = getConnection();

	try{
		$db->beginTransaction(); 

		$sql = "DELETE FROM piscina WHERE pis_id=".$cod_piscina;
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