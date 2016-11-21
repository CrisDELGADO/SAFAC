<?php

	$ges_id = 2;
	$rol_id = $_SESSION['IDROL'];

	try{
		pg_query($conn,"BEGIN WORK");
		$sql = "SELECT * FROM permiso, tarea WHERE permiso.rol_id=$rol_id AND tarea.tar_id=permiso.tar_id AND 
		tarea.ges_id=$ges_id";
		$tareas = pg_query($conn, $sql);
		pg_query($conn,"COMMIT WORK");
	}catch(Exception $e){
		pg_query($conn,"ROLLBACK WORK");
	}

	$crear = false;
	$ver = false;
	$editar = false;
	$eliminar = false;

	while($reg=pg_fetch_assoc($tareas)){
		if($reg['tar_code']=='I')
			$crear = true;
		if($reg['tar_code']=='S')
			$ver = true;
		if($reg['tar_code']=='U')
			$editar = true;
		if($reg['tar_code']=='D')
			$eliminar = true;
	}

?>