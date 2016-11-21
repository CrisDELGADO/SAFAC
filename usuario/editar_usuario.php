<?php
	error_reporting(0);

	require('../webservice/WSConexion.php');

	//DATOS USUARIO
	$codigoUsuario = $_POST['codigoUsuario'];
	$cedulaUsuario = $_POST['cedulaUsuario'];
	$nombreUsuario = $_POST['nombreUsuario'];
	$apellidoUsuario = $_POST['apellidoUsuario'];
	$sexoUsuario = $_POST['sexoUsuario'];
	$emailUsuario = $_POST['emailUsuario'];
	$domicilioUsuario = $_POST['domicilioUsuario'];
	$telefonoUsuario = $_POST['telefonoUsuario'];
	$estadoUsuario = $_POST['estadoUsuario'];;
	$rolID = $_POST['rolID'];
	$rolID_last = $_POST['rolID_last'];
	$cargoID = $_POST['cargoID'];
	$empresaID = $_POST['empresaID'];
	$listaTareas = $_POST['listaTareas'];
	$listaTareas = explode(',',$listaTareas);


	$db = getConnection();

	try{

		$db->beginTransaction(); 

		if($rolID_last>3){

			$sql = "DELETE FROM permiso WHERE rol_id=".$rolID_last."";
			$stmt = $db->prepare($sql);
			$stmt -> execute();


		}

		
		if($rolID==0){
			if($rolID_last>3){

				$rolID = $rolID_last;
				
				//Asigno Permisos
				for($i=0;$i<count($listaTareas);$i++){
					$sql = "INSERT INTO permiso (rol_id, tar_id) VALUES (".$rolID.",".$listaTareas[$i].")"; 
					$stmt = $db->prepare($sql);
					$stmt -> execute();
				}

			}else{
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
			
		}

		$sql = "UPDATE usuario SET usu_cedula = '".$cedulaUsuario."', usu_nombre = '".$nombreUsuario."', usu_apellido = '".$apellidoUsuario."', usu_sexo = '".$sexoUsuario."', usu_email = '".$emailUsuario."', usu_domicilio = '".$domicilioUsuario."', usu_telefono = '".$telefonoUsuario."', usu_estado = ".$estadoUsuario.", emp_id = ".$empresaID.", rol_id = ".$rolID." WHERE usu_id = ".$codigoUsuario." ";
		
		$stmt = $db->prepare($sql);
		$stmt -> execute();

	
		if($rolID_last>3 && $rolID !=0){
			$sql = "DELETE FROM rol WHERE rol_id=".$rolID_last."";
			$stmt = $db->prepare($sql);
			$stmt -> execute();
		}

		


	    $db->commit(); 
		$db = null;
	    echo 'BIEN';
	}catch(Exception $e){
		$db->rollback(); 
		$codeError = $e->getCode();
		if($codeError==23505){
	        echo 'Verifique número de cédula (Ya existe)';
	    }else{
	        echo 'Error al editar usuario';
	    }
	}
	
	
?>