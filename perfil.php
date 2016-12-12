<?php
include('cabecera.php');

?>
<!-- =============================================== -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>Perfil<small>Perfil Personal</small></h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-user"></i> Perfil</a></li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<!-- left column -->
			<div class="col-md-6">
				<!-- general form elements -->
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Mis Datos</h3>
					</div><!-- /.box-header -->
					<!-- form start -->
					<form role="form" name="formMisDatos">
						<div class="box-body">
							<div class="col-lg-12">
								<div class="form-group">
									<label>Cédula:</label>
									<input type="text" class="form-control" name="cedula_usuario" placeholder="Cédula">
								</div>
							</div>
							<div class="col-lg-12">
								<div class="form-group">
									<label>Nombre:</label>
									<input type="text" class="form-control" name="nombre_usuario" placeholder="Nombres">
								</div>
							</div>
							<div class="col-lg-12">
								<div class="form-group">
									<label>Apellido:</label>
									<input type="text" class="form-control" name="apellido_usuario" placeholder="Apellidos">
								</div>
							</div>
							<div class="col-lg-12">
								<div class="form-group">
									<label>Domicilio:</label>
									<input type="text" class="form-control" name="domicilio_usuario" placeholder="Domicilio">
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label>Teléfono:</label>
									<input type="text" class="form-control" name="telefono_usuario" placeholder="Teléfono">
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label>Sexo:</label>
									<select class="form-control" name="sexo_usuario">
										<option value="M">Masculino</option>
										<option value="F">Femenino</option>
									</select>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="form-group">
									<label>E-mail:</label>
									<input type="text" class="form-control" name="email_usuario" placeholder="E-mail">
								</div>
							</div>
						</div><!-- /.box-body -->
					</form>
					<div style="text-align:center">
						<button class="btn btn-primary btn-lg" onclick="editarDatosPersonales(); return false;"><i class="fa fa-save"></i> Guardar Cambios</button>
					</div>
					<br>
				</div>
			</div>
			<div class="col-md-6">
				<!-- general form elements -->
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Cambiar Contraseña</h3>
					</div><!-- /.box-header -->
					<!-- form start -->
					<form role="form" name="formCambiarContrasena">
						<div class="box-body">
							<div class="col-lg-12">
								<div class="form-group">
									<label>Contraseña Actual:</label>
									<input type="password" class="form-control" name="password_usuarioA" placeholder="Contraseña Actual">
								</div>
							</div>
							<div class="col-lg-12">
								<div class="form-group">
									<label>Nueva Contraseña:</label>
									<input type="password" class="form-control" name="password_usuario" placeholder="Contraseña Nueva">
								</div>
							</div>
							<div class="col-lg-12">
								<div class="form-group">
									<label>Repetir Contraseña:</label>
									<input type="password" class="form-control" name="password_usuario2" placeholder="Repetir Contraseña">
								</div>
							</div>
						</div><!-- /.box-body -->
					</form>
					<div style="text-align:center">
						<button class="btn btn-primary btn-lg" onclick="cambiarContrasena(); return false;"><i class="fa fa-save"></i> Cambiar Contraseña</button>
					</div>
					<br>
				</div>
			</div>
		</div>
	</section><!-- /.content -->
</div><!-- /.content-wrapper -->




<script type="text/javascript">

	//Llenado de Datos Personales
	$.ajax({
		url: 'webservice/WService.php/usuario/<?= $_SESSION["IDUSUARIO"] ?>',
		dataType:'json',
		type:'get',
		async: false,
		crossDomain: true,
		cache: false,
		success: function(data){
			var datos = '';
			$(data).each(function(index, value){
				document.formMisDatos.cedula_usuario.value = value.usu_cedula;
				document.formMisDatos.nombre_usuario.value = value.usu_nombre;
				document.formMisDatos.apellido_usuario.value = value.usu_apellido;
				document.formMisDatos.email_usuario.value = value.usu_email;
				document.formMisDatos.telefono_usuario.value = value.usu_telefono;
				document.formMisDatos.domicilio_usuario.value = value.usu_domicilio;
				document.formMisDatos.sexo_usuario.value = value.usu_sexo;
			});

		}
	});

	function editarDatosPersonales(){
		var cod_usuario = <?= $_SESSION['IDUSUARIO'] ?>;
		var nombre_usuario = document.formMisDatos.nombre_usuario.value;
		var apellido_usuario = document.formMisDatos.apellido_usuario.value;
		var sexo_usuario = document.formMisDatos.sexo_usuario.value;
		var cedula_usuario = document.formMisDatos.cedula_usuario.value;
		var email_usuario = document.formMisDatos.email_usuario.value;
		var domicilio_usuario = document.formMisDatos.domicilio_usuario.value;
		var telefono_usuario = document.formMisDatos.telefono_usuario.value;

		if(nombre_usuario.trim()==''||apellido_usuario.trim()==''||domicilio_usuario.trim()==''||telefono_usuario.trim()==''
		||email_usuario.trim()==''||cedula_usuario.trim()==''){
			toastr.options = {"timeOut": "1000"};
			toastr.error('Llene todos los campos','Estado');
		}else{
			ajax = objetoAjax();
			ajax.open("POST", "usuario/actualizar_datospersonales.php", true);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4) {
					mensajeRespuesta = ajax.responseText;

					if(mensajeRespuesta == 'BIEN'){
						toastr.options = {"timeOut": "1000"};
						toastr.success('Cambios guardados con éxito','Estado');
						setTimeout(function(){
							window.location.href = 'perfil.php';
						},1000)

					}else{
						toastr.options = {"timeOut": "1000"};
						toastr.error(mensajeRespuesta,'Estado');
					}
				}
			}
			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax.send("cod_usuario="+cod_usuario+"&nombre_usuario="+nombre_usuario+"&apellido_usuario="+apellido_usuario+
			"&domicilio_usuario="+domicilio_usuario+"&telefono_usuario="+telefono_usuario+"&sexo_usuario="+sexo_usuario+
			"&cedula_usuario="+cedula_usuario+"&email_usuario="+email_usuario);

		}

	}


	function cambiarContrasena(){
		var cod_usuario = <?= $_SESSION['IDUSUARIO'] ?>;
		var password_usuarioA = document.formCambiarContrasena.password_usuarioA.value;
		var password_usuario = document.formCambiarContrasena.password_usuario.value;
		var password_usuario2 = document.formCambiarContrasena.password_usuario2.value;


		if(password_usuarioA.trim()==''||password_usuario.trim()==''||password_usuario2.trim()==''){
			toastr.options = {"timeOut": "1000"};
			toastr.error('Llene todos los campos','Estado');
		}else{
			if(password_usuario!=password_usuario2){
				toastr.options = {"timeOut": "1000"};
			toastr.error('Contraseñas no coinciden','Estado');
			}else{
				ajax = objetoAjax();
				ajax.open("POST", "usuario/cambiar_contrasena.php", true);
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4) {
						var mensajeRespuesta = ajax.responseText;

						if(mensajeRespuesta == 'BIEN'){
							toastr.options = {"timeOut": "1000"};
							toastr.success('Contraseña cambiada con éxito','Estado');
							setTimeout(function(){
								window.location.reload(true);
							},1000)

						}else{
							toastr.options = {"timeOut": "1000"};
							toastr.error(mensajeRespuesta,'Estado');
						}
					}
				}
				ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
				ajax.send("&cod_usuario="+cod_usuario+"&password_usuarioA="+password_usuarioA+"&password_usuario="+password_usuario);
			}
		}
	}


</script>


<?php
	include('pie.php');
?>