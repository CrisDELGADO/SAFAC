<?php

    require('../webservice/WSConexion.php');

    $cod_usuario = $_POST['cod_usuario'];
    $password_usuarioA = base64_encode($_POST['password_usuarioA']);
    $password_usuario = base64_encode($_POST['password_usuario']);

    $db = getConnection();
    $sql = "SELECT * FROM usuario WHERE usu_id=".$cod_usuario." ";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $usuario = $stmt->fetchObject();

    $db = null;

    if($usuario->usu_password==$password_usuarioA){
        $sql = "UPDATE usuario SET usu_password=:password_usuario WHERE usu_id=:cod_usuario";
        try {
            $db = getConnection();
            $db->beginTransaction(); 
            $stmt = $db->prepare($sql);
            $stmt->bindParam("password_usuario", $password_usuario);
            $stmt->bindParam("cod_usuario", $cod_usuario);
            $stmt->execute();

            $db->commit(); 
            $db = null;

            echo 'BIEN';
        } catch(PDOException $e) {
            $db->rollback();
            echo 'Error al cambiar contraseña';
        }
    }else{
        echo "Contraseña actual incorrecta";
    }




?>