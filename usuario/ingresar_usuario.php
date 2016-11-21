<?php
	error_reporting(0);

	require('../webservice/WSConexion.php');

	//DATOS USUARIO
	$cedulaUsuario = $_POST['cedulaUsuario'];
	$nombreUsuario = $_POST['nombreUsuario'];
	$apellidoUsuario = $_POST['apellidoUsuario'];
	$sexoUsuario = $_POST['sexoUsuario'];
	$emailUsuario = $_POST['emailUsuario'];
	$passwordUsuario = base64_encode($_POST['passwordUsuario']);
	$domicilioUsuario = $_POST['domicilioUsuario'];
	$telefonoUsuario = $_POST['telefonoUsuario'];
	$estadoUsuario = $_POST['estadoUsuario'];;
	$rolID = $_POST['rolID'];
	$cargoID = $_POST['cargoID'];
	$empresaID = $_POST['empresaID'];
	$listaTareas = $_POST['listaTareas'];
	$listaTareas = explode(',',$listaTareas);
	
	$db = getConnection();

	try{

		$db->beginTransaction(); 

		if($rolID==0){
			//Creo un ROL
			$sql = "INSERT INTO rol (car_id) VALUES (".$cargoID.")";
			$stmt = $db->prepare($sql);
			$stmt -> execute();
			
			$sql = "SELECT MAX (rol_id) as rol_id from rol";
			$stmt = $db->prepare($sql);
			$stmt -> execute();
			$MAX_rol = $stmt->fetchObject();
			$rolID = $MAX_rol->rol_id;

			//Asigno Permisos
			for($i=0;$i<count($listaTareas);$i++){
				$sql = "INSERT INTO permiso (rol_id, tar_id) VALUES (".$rolID.",".$listaTareas[$i].")"; 
				$stmt = $db->prepare($sql);
				$stmt -> execute();
			}
			
		}

		$sql = "INSERT INTO usuario (usu_cedula, usu_nombre, usu_apellido, usu_sexo, usu_email, usu_domicilio, usu_telefono, usu_password, usu_estado, emp_id, rol_id) VALUES ('".$cedulaUsuario."','".$nombreUsuario."','".$apellidoUsuario."','".$sexoUsuario."','".$emailUsuario."','".$domicilioUsuario."','".$telefonoUsuario."','".$passwordUsuario."',".$estadoUsuario.",".$empresaID.",".$rolID.")";
		
		$stmt = $db->prepare($sql);
		$stmt -> execute();


	    $db->commit(); 
		$db = null;
	    echo 'BIEN';
	}catch(Exception $e){
		$db->rollback(); 
		$codeError = $e->getCode();
		if($codeError==23505){
	        echo 'Verifique número de cédula (Ya existe)';
	    }else{
	        echo 'Error al crear nuevo usuario';
	    }
	}
	

?>