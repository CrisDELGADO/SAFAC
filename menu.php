<?php

 $rol_id = $_SESSION['IDROL'];


try{
	pg_query($conn,"BEGIN WORK");
	//$sql = "SELECT * FROM permiso, tarea, gestion WHERE permiso.rol_id=1 AND tarea.tar_id=permiso.tar_id AND gestion.ges_id=tarea.ges_id";
	//$tareas = pg_query($conn, $sql);
	$sql = "SELECT distinct gestion.ges_id, gestion.ges_nombre, gestion.ges_img from gestion, tarea, permiso WHERE 
	permiso.rol_id=$rol_id AND tarea.tar_id=permiso.tar_id AND gestion.ges_id=tarea.ges_id order by gestion.ges_id";
	$modulos = pg_query($conn, $sql);
	pg_query($conn,"COMMIT WORK");
}catch(Exception $e){
	pg_query($conn,"ROLLBACK WORK");
}



?>