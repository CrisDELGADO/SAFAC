<?php

	require('../webservice/WSConexion.php');

	$cod_usuario = $_POST['cod_usuario'];

	$db = getConnection();

	try{
		$db->beginTransaction(); 

		$sql = "SELECT rol_id FROM usuario WHERE usu_id=".$cod_usuario;
		$stmt = $db->prepare($sql);
		$stmt -> execute();
		$rol = $stmt->fetchObject()->rol_id;



		$sql = "DELETE FROM usuario WHERE usu_id=".$cod_usuario;
		$stmt = $db->prepare($sql);
		$stmt -> execute();

		if($rol>3){
			$sql = "DELETE FROM permiso WHERE rol_id=".$rol."";
			$stmt = $db->prepare($sql);
			$stmt -> execute();

			$sql = "DELETE FROM rol WHERE rol_id=".$rol."";
			$stmt = $db->prepare($sql);
			$stmt -> execute();

		}



		$db->commit(); 
		$db = null;
		echo 'BIEN';
	}catch(Exception $e){
		$db->rollback(); 
		$codeError = $e->getCode();
		echo 'No se pudo eliminar';
	}

?>