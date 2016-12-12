<?php
	$codigo_mote = $_POST['codMote'];
	$estado_mote = $_POST['estadoMote'];

	error_reporting(0);
	require('../webservice/WSConexion.php');

	$db = getConnection();

	try{
		$sql = "UPDATE mote SET mot_estado=".$estado_mote." WHERE mot_id = ".$codigo_mote." ";

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