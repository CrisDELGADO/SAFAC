<?php

    session_start();

    require('../webservice/WSConexion.php');

    $cod_usuario = $_POST['cod_usuario'];
    $nombre_usuario = $_POST['nombre_usuario'];
    $apellido_usuario = $_POST['apellido_usuario'];
    $direccion_usuario = $_POST['domicilio_usuario'];
    $telefono_usuario = $_POST['telefono_usuario'];
    $sexo_usuario = $_POST['sexo_usuario'];
    $cedula_usuario = $_POST['cedula_usuario'];
    $email_usuario = $_POST['email_usuario'];


    $sql = "UPDATE usuario SET usu_nombre=:nombre_usuario, usu_apellido=:apellido_usuario, usu_sexo=:sexo_usuario,
    usu_cedula=:cedula_usuario, usu_email=:email_usuario, usu_domicilio=:direccion_usuario, usu_telefono=:telefono_usuario
    WHERE usu_id=:cod_usuario";
    try {
        $db = getConnection();
        $db->beginTransaction(); 
        $stmt = $db->prepare($sql);
        $stmt->bindParam("nombre_usuario", $nombre_usuario);
        $stmt->bindParam("apellido_usuario", $apellido_usuario);
        $stmt->bindParam("direccion_usuario", $direccion_usuario);
        $stmt->bindParam("telefono_usuario", $telefono_usuario);
        $stmt->bindParam("sexo_usuario", $sexo_usuario);
        $stmt->bindParam("cedula_usuario", $cedula_usuario);
        $stmt->bindParam("email_usuario", $email_usuario);
        $stmt->bindParam("cod_usuario", $cod_usuario);
        $stmt->execute();

        $db->commit(); 
        $db = null;

        $_SESSION['NOMBRE'] = $nombre_usuario;
        $_SESSION['USERNAME'] = $email_usuario;

        echo 'BIEN';
    } catch(PDOException $e) {
        $db->rollback();
        $codeError = $e->getCode();
        if($codeError==23505){
            echo 'Verifique número de cédula (Ya existe)';
        }else{
            echo 'Error al actualizar datos';
        }
    }

?>