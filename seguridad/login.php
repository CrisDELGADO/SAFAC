<?php
  session_start();
  require('../conexion.php');

  $username = $_REQUEST['username'];
  $password = $_REQUEST['password'];

  $passwordE = base64_encode($password);

  try{
    pg_query($conn,"BEGIN WORK");
    $sql = "SELECT * FROM usuario, empresa, rol, cargo WHERE usuario.usu_cedula='$username' AND
     usuario.usu_password='$passwordE' AND usuario.emp_id=empresa.emp_id AND rol.rol_id=usuario.rol_id AND cargo.car_id=rol.car_id";
    $resultado = pg_query($conn, $sql);
    $sql = "SELECT * FROM usuario WHERE usu_cedula='$username' AND usu_password='$passwordE' AND rol_id=1";
    $resultado2 = pg_query($conn, $sql);
    pg_query($conn,"COMMIT WORK");
  }catch(Exception $e){
    pg_query($conn,"ROLLBACK WORK");
    echo "Fallo: " . $e->getMessage();
  }
  

  if(!$resultado){
    //ERROR DE BUSQUEDA
  }

  $mensaje = '';

  $filas = pg_num_rows($resultado);
  if($filas<=0){
    //NO EXISTE
    $filas2 = pg_num_rows($resultado2);
    if($filas2<=0){
      $mensaje = 'Usuario o Contraseña incorrectos';
    }else{
      while($reg2=pg_fetch_assoc($resultado2)){
        $mensaje = 'Bienvenido';
        $_SESSION['IDUSUARIO'] = $reg2['usu_id'];
        $_SESSION['NOMBRE'] = $reg2['usu_nombre'];
        $_SESSION['USERNAME'] = $reg2['usu_cedula'];
        $_SESSION['IDEMPRESA'] = 0;
        $_SESSION['IDROL'] = $reg2['rol_id'];
        $_SESSION['CARGO'] = 'SUPERADMINISTRADOR';
        $_SESSION['NOMBREEMPRESA'] = 'SAFAC';
      }
    }

  }else{
    //EXISTE
    while($reg=pg_fetch_assoc($resultado)){
      
      if($reg['usu_estado']=='t'){

        if($reg['emp_estado']=='t'){
          $mensaje = 'Bienvenido';

          $_SESSION['IDUSUARIO'] = $reg['usu_id'];
          $_SESSION['NOMBRE'] = $reg['usu_nombre'];
          $_SESSION['USERNAME'] = $reg['usu_cedula'];
          $_SESSION['IDEMPRESA'] = $reg['emp_id'];
          $_SESSION['IDROL'] = $reg['rol_id'];
          $_SESSION['CARGO'] = $reg['car_nombre'];
          $_SESSION['NOMBREEMPRESA'] = $reg['emp_nombre'];
        }else{
          $mensaje = 'Su empresa está deshabilitada';
        }
        
     
      }else{
        $mensaje = 'Usuario deshabilitado';
      }
      
    
      
    }
    
    

  }

  echo $mensaje;
?>