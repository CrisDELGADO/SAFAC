<?php
error_reporting(E_ERROR);
session_start();

include('../conexion.php');

if(isset($_SESSION['IDUSUARIO'])!=''){
  header('Location: ../index.php');
}

?>


<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SAFAC | Acceso</title>
    <link rel="shortcut icon" href="../img/safac.ico"> 
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="../plugins/iCheck/square/blue.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="hold-transition login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href="#"><b>Sistema</b>SAFAC</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Inicia Sesión</p>
        <form action="" method="post" name="frmLogin">
          <div id="mensajeFrmLogin"></div>
          <div class="form-group has-feedback">
            <input type="text" class="form-control" name="username" placeholder="Username">
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" name="password" placeholder="Password">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-6">
              <div class="checkbox icheck">
                <label>
                  <input type="checkbox"> Recordarme
                </label>
              </div>
            </div><!-- /.col -->
            <div class="col-xs-6">
              <span onclick="Logeo(); return false;" ><button type="submit" class="btn btn-primary btn-block btn-flat">Acceder</button></span>
              
            </div><!-- /.col -->
          </div>
        </form>

       

        <a href="#">¿Has olvidado tu contraseña?</a><br>
        

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <!-- jQuery 2.1.4 -->
    <script src="../plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="../plugins/iCheck/icheck.min.js"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>

    <!--AJAX-->
    <script type="text/javascript">
      function objetoAjax(){
        var xmlhttp=false;
        try {
          xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
          try {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
          } catch (E) {
            xmlhttp = false;
          }
        }
        if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
          xmlhttp = new XMLHttpRequest();
        }
        return xmlhttp;
      }

    </script>


    <script type="text/javascript">

      function Logeo(){
        username = document.frmLogin.username.value;
        password = document.frmLogin.password.value;

        if(username == '' || password == ''){
          mensajeRespuesta = 'Ambos campos son obligatorios';
          var htmlAlerta = '<div class="alert alert-danger alert-dismissable">' +
                               '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                                '<i class="icon fa fa-ban"></i> ' + mensajeRespuesta +
                                '</div>';
           document.getElementById("mensajeFrmLogin").innerHTML = htmlAlerta;
        }else{
           ajax = objetoAjax();

          ajax.open("POST", "login.php", true);

          ajax.onreadystatechange=function() {
              if (ajax.readyState==4) {
                var mensajeRespuesta = ajax.responseText;

                if(mensajeRespuesta == 'Bienvenido'){
                  window.location.reload(true);
                }else{
                  var htmlAlerta = '<div class="alert alert-danger alert-dismissable">' +
                                 '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                                  '<i class="icon fa fa-ban"></i> ' + mensajeRespuesta +
                                  '</div>';
                  document.getElementById("mensajeFrmLogin").innerHTML = htmlAlerta;
                }
         
                
                
              }
            }
          ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
          ajax.send("username="+username+"&password="+password);
        }

       
      }
    </script>

  </body>
</html>
