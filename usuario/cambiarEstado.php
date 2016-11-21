<?php
	$codigo_usuario = $_POST['codUsuario'];
	$estado_usuario = $_POST['estadoUsuario'];

	error_reporting(0);
	require('../webservice/WSConexion.php');

	$db = getConnection();

	try{
		$sql = "UPDATE usuario SET usu_estado=".$estado_usuario." WHERE usu_id = ".$codigo_usuario." ";

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