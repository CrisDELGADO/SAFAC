<?php
	include('cabecera.php');
	if($_SESSION['IDROL']==''){
		header('Location: index.php');
	}
?>


<!-- =============================================== -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>Usuario<small>Sección Usuarios</small></h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-users"></i> Usuario</a></li>
		</ol>
	</section>

	<?php
		include('usuario/tarea.php');
		if(!$crear && !$ver){
			header('Location: index.php');
		}
	?>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="nav-tabs-custom box box-primary">
					<ul class="nav nav-tabs pull-right">
						<?php
							if($_REQUEST['v']==1){
								if($ver)
								{
									echo '<li class="active"><a href="#ver-chart" data-toggle="tab"> Listar </a></li>';
								}
								if($crear)
								{
									echo '<li><a href="#crear-chart" data-toggle="tab"> Nuevo </a></li>';
								}
							}else{
								if($ver)
								{
									if($crear)echo '<li><a href="#ver-chart" data-toggle="tab"> Listar </a></li>';
									else echo '<li class="active"><a href="#ver-chart" data-toggle="tab"> Listar </a></li>';
								}
								if($crear)
								{
									echo '<li class="active"><a href="#crear-chart" data-toggle="tab"> Nuevo </a></li>';
								}
							}
						?>
						
						<li class="pull-left header"><i class="fa fa-users"></i> Usuario</li>
					</ul>
					<div class="tab-content no-padding">
						<div class="chart tab-pane <?php if($_REQUEST['v']!=1)echo 'active'; ?> <?php if(!$crear) {echo 'hidden';} ?>" id="crear-chart" style="position: relative;">
							<form role="form" name="formNuevoUsuario">
								<div class="box-body">
									<div class="col-lg-2">
										<img src="img/administrador.png" width="100%" alt="User Image">
									</div>
									<div class="col-lg-5">
										<div class="form-group">
											<label>Código:</label>
											<input type="text" class="form-control" name="codigo_usuario" placeholder="Código Autogenerado" disabled>
										</div>
									</div>
									<div class="col-lg-5">
										<div class="form-group">
											<label>Cédula:</label>
											<input type="text" class="form-control" name="cedula_usuario" placeholder="Cédula (Username)">
										</div>
									</div>
									<div class="col-lg-5">
										<div class="form-group">
											<label>Nombre:</label>
											<input type="text" class="form-control" name="nombre_usuario" placeholder="Nombre">
										</div>
									</div>
									<div class="col-lg-5">
										<div class="form-group">
											<label>Apellido:</label>
											<input type="text" class="form-control" name="apellido_usuario" placeholder="Apellido">
										</div>
									</div>
									<div class="col-lg-7">
										<div class="form-group">
											<label>Domicilio:</label>
											<input type="text" class="form-control" name="domicilio_usuario" placeholder="Dirección de Domicilio">
										</div>
									</div>
									<div class="col-lg-3">
										<div class="form-group">
											<label>Teléfono:</label>
											<input type="text" class="form-control" name="telefono_usuario" placeholder="Teléfono">
										</div>
									</div>
									<div class="col-lg-2">
										<div class="form-group">
											<label>Sexo:</label>
											<select class="form-control" name="sexo_usuario" style="cursor: pointer;">
												<option value="M">Masculino</option>
												<option value="F">Femenino</option>
											</select>
										</div>
									</div>
									<div class="col-lg-2">
										<div class="form-group">
											<label>Estado:</label>
											<select class="form-control" name="estado_usuario" style="cursor: pointer;">
												<option value="True">Habilitado</option>
												<option value="False">Deshabilitado</option>
											</select>
										</div>
									</div>
									<div class="col-lg-5">
										<div class="form-group">
											<label>E-mail:</label>
											<input type="text" class="form-control" name="email_usuario" placeholder="E-mail">
										</div>
									</div>
									<div class="col-lg-5">
										<div class="form-group">
											<label>Contraseña:</label>
											<input type="password" class="form-control" name="password_usuario" placeholder="Contraseña">
											<div class="checkbox">
												<label>
												<input type="checkbox" name="checkMostrar" onchange="mostrarContra();"> Mostrar Contraseña
												</label>
											</div>
										</div>
									</div>
								</div><!-- /.box-body -->
								<div class="<?php if($_SESSION['IDEMPRESA']!=0) {echo 'hidden';} ?>" >
									<div class="col-lg-12">
										<hr>
										<h5><span><li class="fa fa-building"></li> Empresa</span></h5>
									</div>
									<div class="col-lg-12">
										<br>
										<div class="col-lg-6">
											<div class="form-group">
												<select class="form-control select2 select2-hidden-accessible" name="empresa_id"  id="empresa_id"
												style="width: 100%;" tabindex="-1" aria-hidden="true" onchange="obtenerRUC();">

												</select>
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
												<input type="text" class="form-control" id="ruc_empresa" placeholder="RUC" disabled>
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-12">
									<hr>
									<h5><span><li class="fa fa-user"></li> Rol - Tareas</span></h5>
								</div>
								<div class="col-lg-12">
									<br>
									<div class="col-lg-6">
										<div class="form-group">
											<select class="form-control" name="rol_usuario" id="rol_usuario" style="cursor: pointer;">
												
											</select>
										</div>
									</div>
									<div class="col-lg-3">
										<div class="input-group">
											<span class="input-group-addon">
												<input type="radio" name="gTarea" id="checkTareaD" style="cursor: pointer;" checked onclick="$('#listaTareas').hide();">
											</span>
											<input type="text" class="form-control" disabled value="Tareas por defecto">
										</div>
									</div>
									<div class="col-lg-3">
										<div class="input-group">
											<span class="input-group-addon">
												<input type="radio" name="gTarea" id="checkTareaA" style="cursor: pointer;" onclick="$('#listaTareas').show();">
											</span>
											<input type="text" class="form-control" disabled value="Asignar tareas">
										</div>
									</div>
								</div>
								
								<div class="col-lg-12">
									<table class="table table-hover" id="listaTareas">
									<tr>
										<th style="width:10px;">[-]</th>
										<th style="width:230px;">GESTIÓN</th>
										<th>TAREA</th>
									</tr>
									<tbody id="contenidoTareas">

									</tbody>
									</table>
								</div><!-- /.box-body -->
							</form>
							<div style="text-align:center">
								<button class="btn btn-primary btn-lg" onclick="nuevoUsuario(); return false;"><i class="fa fa-save"></i> Guardar</button>
								<br><br>
							</div>
						</div>
						<div class="chart tab-pane <?php if($_REQUEST['v']==1)echo 'active '; if(!$crear&&$ver)echo 'active' ?> " id="ver-chart" style="position: relative;">
							<div class="box-body table-responsive">
								<table id="example1" class="table table-bordered table-striped">
									<thead>
										<tr>
											<th># Código</th>
											<th>Cédula</th>
											<th>Apellidos</th>
											<th>Nombres</th>
											<th>Estado</th>
											<th>Acciones</th>
										</tr>
									</thead>
									<tbody>
										<?php
											require('conexion.php');
											try{
												pg_query($conn,"BEGIN WORK");
												if($_SESSION['IDEMPRESA']==0) $sql = "SELECT * FROM usuario where rol_id!=1 order by usu_id asc";
												else $sql = "SELECT * FROM usuario where emp_id=".$_SESSION['IDEMPRESA']." order by usu_id asc";
												$resultado = pg_query($conn, $sql);

												while($reg=pg_fetch_assoc($resultado)){
										?>
										<tr>
											<td>
												<?= $reg['usu_id'] ?>
											</td>
											<td>
												<?= $reg['usu_cedula'] ?>
											</td>
											<td>
												<?= $reg['usu_apellido'] ?>
											</td>
											<td>
												<?= $reg['usu_nombre'] ?>
											</td>
											<td>
												<input type="checkbox" <?php if(!$editar)echo 'disabled' ?> id="estado_usuario<?= $reg['usu_id'] ?>" <?php if($reg['usu_estado']=='t')echo 'checked';?> data-toggle="toggle" data-size="small" onchange="cambiarEstadoUsuario(<?= $reg['usu_id'] ?>)">
											</td>
											<td>
												<a onclick="mostrarDatosUsuario(<?= $reg['usu_id']; ?>);$('#btnEditar').hide();"  title="Ver" style="cursor: pointer;">
													<button class="btn"><i class="fa fa-search"></i></button>
												</a>
												<?php 
													if($editar){
												?>
												<a onclick="mostrarDatosUsuario(<?= $reg['usu_id']; ?>);$('#btnEditar').show();"  title="Editar" style="cursor: pointer;">
													<button class="btn"><i class="fa fa-edit"></i></button>
												</a>
												<?php
													}
													if($eliminar){
												?>
												<a onclick="mostrarEliminarMensaje(<?= $reg['usu_id']; ?>);"  title="Eliminar" style="cursor: pointer;">
													<button class="btn"><i class="fa fa-remove"></i></button>
												</a>
												<?php	
													}
												?>	
											</td>
										</tr>
										<?php
												}
												pg_query($conn,"COMMIT WORK");
											}
											catch(Exception $e)
											{
												pg_query($conn,"ROLLBACK WORK");
												echo "Fallo: " . $e->getMessage();
											}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section><!-- /.content -->
</div><!-- /.content-wrapper -->


<div class="modal" id="datosUsuario">
	<div class="modal-dialog" >
		<div class="modal-content" >
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title"><i class="fa fa-user"></i> <i> Datos de Usuario</i></h4>
			</div>
			<div class="modal-body">
				<form role="form" name="formDatosUsuario">
					<div class="box-body">
						<div class="col-lg-6">
							<div class="form-group">
								<label>Código:</label>
								<input type="text" class="form-control" name="codigo_usuario" placeholder="Código Autogenerado" disabled>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>Cédula:</label>
								<input type="text" class="form-control" name="cedula_usuario" placeholder="Cédula (Username)">
							</div>
						</div>
						<div class="col-lg-12">
							<div class="form-group">
								<label>Nombre:</label>
								<input type="text" class="form-control" name="nombre_usuario" placeholder="Nombre">
							</div>
						</div>
						<div class="col-lg-12">
							<div class="form-group">
								<label>Apellido:</label>
								<input type="text" class="form-control" name="apellido_usuario" placeholder="Apellido">
							</div>
						</div>
						<div class="col-lg-8">
							<div class="form-group">
								<label>Domicilio:</label>
								<input type="text" class="form-control" name="domicilio_usuario" placeholder="Dirección de Domicilio">
							</div>
						</div>
						<div class="col-lg-4">
							<div class="form-group">
								<label>Teléfono:</label>
								<input type="text" class="form-control" name="telefono_usuario" placeholder="Teléfono">
							</div>
						</div>
						<div class="col-lg-4">
							<div class="form-group">
								<label>Sexo:</label>
								<select class="form-control" name="sexo_usuario" style="cursor: pointer;">
									<option value="M">Masculino</option>
									<option value="F">Femenino</option>
								</select>
							</div>
						</div>
						<div class="col-lg-8">
							<div class="form-group">
								<label>E-mail:</label>
								<input type="text" class="form-control" name="email_usuario" placeholder="E-mail">
							</div>
						</div>
						<div class="col-lg-4">
							<div class="form-group">
								<label>Estado:</label>
								<select class="form-control" name="estado_usuario" style="cursor: pointer;">
									<option value="true">Habilitado</option>
									<option value="false">Deshabilitado</option>
								</select>
							</div>
						</div>
					
						
						<div class="<?php if($_SESSION['IDEMPRESA']!=0) {echo 'hidden';} ?>" >
							<div class="col-lg-12">
								<hr>
								<h5><span><li class="fa fa-building"></li> Empresa</span></h5>
							</div>
							<div class="col-lg-12">
								<br>
								<div class="col-lg-6">
									<div class="form-group">
										<select class="form-control select2 select2-hidden-accessible" name="empresa_id"  id="empresa_id2"
										style="width: 100%;" tabindex="-1" aria-hidden="true" onchange="obtenerRUC2();">

										</select>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group">
										<input type="text" class="form-control" name="ruc_empresa" id="ruc_empresa2" placeholder="RUC" disabled>
									</div>
								</div>
							</div>
						</div>
						
						<div class="col-lg-12">
							<hr>
							<h5><span><li class="fa fa-user"></li> Rol - Tareas</span></h5>
						</div>
						<div class="col-lg-12">
							<br>
							<div class="col-lg-6">
								<div class="form-group">
									<input type="hidden" id="verRol_id" name="verRol_id">
									<select class="form-control" name="rol_usuario" id="rol_usuario2" style="cursor: pointer;">
										
									</select>
								</div>
							</div>
							<div class="col-lg-6">
							</div>
							<div class="col-lg-6">
								<div class="input-group">
									<span class="input-group-addon">
										<input type="radio" name="gTarea2" id="checkTareaD2" style="cursor: pointer;" checked onclick="$('#listaTareas2').hide();">
									</span>
									<input type="text" class="form-control" disabled value="Tareas por defecto">
								</div>
							</div>
							<div class="col-lg-6">
								<div class="input-group">
									<span class="input-group-addon">
										<input type="radio" name="gTarea2" id="checkTareaA2" style="cursor: pointer;" onclick="$('#listaTareas2').show();">
									</span>
									<input type="text" class="form-control" disabled value="Asignar tareas">
								</div>
							</div>
						</div>
						
						<div class="col-lg-12">
							<table class="table table-hover" id="listaTareas2">
							<tr>
								<th style="width:10px;">[-]</th>
								<th style="width:230px;">GESTIÓN</th>
								<th>TAREA</th>
							</tr>
							<tbody id="contenidoTareas2">

							</tbody>
							</table>
						</div>
					</div><!-- /.box-body -->
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-remove text-alert"></i> Cancelar</button>
				<button type="button" class="btn btn-primary" id="btnEditar" onclick="editarDatosUsuario(); return false;"><i class="fa fa-save"></i> Guardar Cambios</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal" id="mensajeEliminar">
	<div class="modal-dialog" >
		<div class="modal-content" >
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title"><i class="fa fa-user"></i> <i> Eliminar Usuario</i></h4>
			</div>
			<div class="modal-body">
				<input type="hidden" id="codUsuario_eliminar"> 
				<h4>¿Está seguro que desea eliminar este usuario?</h4>
				<br><i>NOTA: Recuerde que solo se eliminará cuando no existan datos relacionados.</i>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-remove text-alert"></i> Cancelar</button>
				<button type="button" class="btn btn-primary" onclick="eliminarUsuario();"><i class="fa fa-trash-o text-alert"></i> Eliminar</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script type="text/javascript">

	//Ocultar lista de tareas
	$("#listaTareas").hide();
	$("#listaTareas2").hide();
	
	//Llenado de Empresas en un elemento Select
	$.ajax({
		url: 'webservice/WService.php/empresa/',
		dataType:'json',
		type:'get',
		async: false,
		crossDomain: true,
		cache: false,
		success: function(data){
			var datos = '';
			$(data.empresa).each(function(index, value){
				datos +="<option value='"+value.emp_id+"'>"+value.emp_nombre+"</option>";
			});
			document.getElementById("empresa_id").innerHTML = datos; 
			document.getElementById("empresa_id").selectedIndex=-1;
			document.getElementById("empresa_id2").innerHTML = datos; 
			document.getElementById("empresa_id2").selectedIndex=-1;
		}
	});

	//Llenado de Cargo-Rol
	$.ajax({
		url: 'webservice/WService.php/cargo/',
		dataType:'json',
		type:'get',
		async: false,
		crossDomain: true,
		cache: false,
		success: function(data){
			var datos = '';
			$(data.cargo).each(function(index, value){
				datos +="<option value='"+value.car_id+"'>"+value.car_nombre+"</option>";
			});
			document.getElementById("rol_usuario").innerHTML = datos; 
			document.getElementById("rol_usuario").selectedIndex=0;
			document.getElementById("rol_usuario2").innerHTML = datos; 
			document.getElementById("rol_usuario2").selectedIndex=0;
		}
	});

	//Llenado de Tareas
	$.ajax({
		url: 'webservice/WService.php/tarea/',
		dataType:'json',
		type:'get',
		async: false,
		crossDomain: true,
		cache: false,
		success: function(data){
			var datos = '';
			$(data.tarea).each(function(index, value){
				datos +="<tr><td><input type='checkbox' name='checkT"+value.tar_id+"' id='"+value.tar_code+"_"+value.ges_id+"' onclick='tareasSelect("+'"'+value.tar_code+'"'+","+value.ges_id+");'></td><td><li class='"+value.ges_img+"'></li> "+value.ges_nombre+"</td><td>"+value.tar_nombre+"</td></tr>";
			});
			document.getElementById("contenidoTareas").innerHTML = datos; 
		}
	});

	//Llenado de Tareas2
	$.ajax({
		url: 'webservice/WService.php/tarea/',
		dataType:'json',
		type:'get',
		async: false,
		crossDomain: true,
		cache: false,
		success: function(data){
			var datos = '';
			$(data.tarea).each(function(index, value){
				datos +="<tr><td><input type='checkbox' name='checkT2_"+value.tar_id+"' id='2"+value.tar_code+"_"+value.ges_id+"' onclick='tareasSelect2("+'"'+value.tar_code+'"'+","+value.ges_id+");'></td><td><li class='"+value.ges_img+"'></li> "+value.ges_nombre+"</td><td>"+value.tar_nombre+"</td></tr>";
			});
			document.getElementById("contenidoTareas2").innerHTML = datos;
		}
	});

	function obtenerRUC(){
		var id = document.getElementById("empresa_id").value;
		$.ajax({
			url: 'webservice/WService.php/empresa/'+id,
			dataType:'json',
			type:'get',
			async: false,
			crossDomain: true,
			cache: false,
			success: function(data){
				var datos = '';
				$(data).each(function(index, value){
					datos +=value.emp_ruc;
				});
				document.getElementById("ruc_empresa").value = datos; 
			}
		});
	}

	function obtenerRUC2(){
		var id = document.getElementById("empresa_id2").value;
		$.ajax({
			url: 'webservice/WService.php/empresa/'+id,
			dataType:'json',
			type:'get',
			async: false,
			crossDomain: true,
			cache: false,
			success: function(data){
				var datos = '';
				$(data).each(function(index, value){
					datos +=value.emp_ruc;
				});
				document.getElementById("ruc_empresa2").value = datos; 
			}
		});
	}

	function mostrarContra(){
		if(document.formNuevoUsuario.checkMostrar.checked){
			document.formNuevoUsuario.password_usuario.type = "text";
		}else{
			document.formNuevoUsuario.password_usuario.type = "password";
		}	
	}

	function tareasSelect(tarCode, gesID){
		if(tarCode=='U'||tarCode=='D'||tarCode=='P'){
			if(document.getElementById(''+tarCode+'_'+gesID).checked){
				$('#S_'+gesID).prop('checked', true);
			}
		}
		if(tarCode=='S'){
			if(!document.getElementById(''+tarCode+'_'+gesID).checked){
				try{
					$('#U_'+gesID).prop('checked', false);
					$('#D_'+gesID).prop('checked', false);
					$('#P_'+gesID).prop('checked', false);	
				}catch(e){
					console.log(e);
				}
				
			}
		}
	}

	function tareasSelect2(tarCode, gesID){
		if(tarCode=='U'||tarCode=='D'||tarCode=='P'){
			if(document.getElementById('2'+tarCode+'_'+gesID).checked){
				$('#2S_'+gesID).prop('checked', true);
			}
		}
		if(tarCode=='S'){
			if(!document.getElementById('2'+tarCode+'_'+gesID).checked){
				try{
					$('#2U_'+gesID).prop('checked', false);
					$('#2D_'+gesID).prop('checked', false);
					$('#2P_'+gesID).prop('checked', false);	
				}catch(e){
					console.log(e);
				}
				
			}
		}
	}

	function nuevoUsuario(){
		//DATOS USUARIO
		var cedulaUsuario = document.formNuevoUsuario.cedula_usuario.value;
		var nombreUsuario = document.formNuevoUsuario.nombre_usuario.value;
		var apellidoUsuario = document.formNuevoUsuario.apellido_usuario.value;
		var sexoUsuario = document.formNuevoUsuario.sexo_usuario.value;
		var emailUsuario = document.formNuevoUsuario.email_usuario.value;
		var domicilioUsuario = document.formNuevoUsuario.domicilio_usuario.value;
		var telefonoUsuario = document.formNuevoUsuario.telefono_usuario.value;
		var passwordUsuario = document.formNuevoUsuario.password_usuario.value;
		var estadoUsuario = document.formNuevoUsuario.estado_usuario.value;

		var empresaID = document.formNuevoUsuario.empresa_id.value;
		var empresaSESSION = <?= $_SESSION['IDEMPRESA'] ?>;
		var cargoID = document.formNuevoUsuario.rol_usuario.value;
		var rolID = 0;

		

		if(document.getElementById('checkTareaD').checked) rolID = cargoID;

		if(cedulaUsuario.trim()==''||nombreUsuario.trim()==''||apellidoUsuario.trim()==''||emailUsuario.trim()==''
		||domicilioUsuario.trim()==''||telefonoUsuario.trim()==''||passwordUsuario.trim()==''){
			toastr.options = {"timeOut": "1000"};
			toastr.error('Llene todos los campos','Estado');
		}
		else{

			if(empresaID==''&&empresaSESSION==0){
				toastr.options = {"timeOut": "1000"};
				toastr.error('Escoga una Empresa para el nuevo Usuario','Estado');
			}else{
				if(empresaSESSION!=0) empresaID = empresaSESSION;
				var listaTareas = [];
				var rowCount = 25;

				for(var i=1; i <= rowCount; i++){
					try{
						$("input[name=checkT"+i+"]").each(function(){
							if(this.checked)
							{
								listaTareas.push(i);
							}
						});
					}catch(error){
						
					}
					
				}

				if(document.getElementById('checkTareaA').checked && listaTareas.length==0){
					toastr.options = {"timeOut": "1000"};
					toastr.error('Asigne tareas al nuevo Usuario','Estado');
				}else{
					
					ajax = objetoAjax();
					ajax.open("POST", "usuario/ingresar_usuario.php", true);
					ajax.onreadystatechange=function() {
						if (ajax.readyState==4) {
							var mensajeRespuesta = ajax.responseText;

							if(mensajeRespuesta == 'BIEN'){
								toastr.options = {"timeOut": "1000"};
								toastr.success('Guardado con éxito','Estado');
								setTimeout(function(){
									window.location.href = 'usuario.php';
								},1000)

							}else{
								toastr.options = {"timeOut": "1000"};
								toastr.error(mensajeRespuesta,'Estado');
							}
						}
					}
					ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
					ajax.send("cedulaUsuario="+cedulaUsuario+"&nombreUsuario="+nombreUsuario+"&apellidoUsuario="+apellidoUsuario+
					"&emailUsuario="+emailUsuario+"&sexoUsuario="+sexoUsuario+"&domicilioUsuario="+domicilioUsuario+
					"&passwordUsuario="+passwordUsuario+"&estadoUsuario="+estadoUsuario+"&empresaID="+empresaID+
					"&rolID="+rolID+"&cargoID="+cargoID+"&telefonoUsuario="+telefonoUsuario+"&listaTareas="+listaTareas);
					
				}
			}
			
		}
	}

	function mostrarDatosUsuario(codUsuario){
		$("#listaTareas2").hide();
		$.ajax({
			url: 'webservice/WService.php/usuario/'+codUsuario+'',
			dataType:'json',
			type:'get',
			cache:'false',
			async: false,
      		crossDomain: true,
			success: function(data){
				var datos = '';
				$(data).each(function(index, value){
					document.formDatosUsuario.codigo_usuario.value = value.usu_id; 
					document.formDatosUsuario.cedula_usuario.value = value.usu_cedula; 
					document.formDatosUsuario.nombre_usuario.value = value.usu_nombre; 
					document.formDatosUsuario.apellido_usuario.value = value.usu_apellido; 
					document.formDatosUsuario.sexo_usuario.value = value.usu_sexo; 
					document.formDatosUsuario.domicilio_usuario.value = value.usu_domicilio; 
					document.formDatosUsuario.email_usuario.value = value.usu_email; 
					document.formDatosUsuario.telefono_usuario.value = value.usu_telefono;
					document.formDatosUsuario.estado_usuario.value = value.usu_estado;
					document.formDatosUsuario.rol_usuario.value = value.car_id;
					document.formDatosUsuario.verRol_id.value = value.rol_id;
					document.formDatosUsuario.empresa_id.value = value.emp_id;
					document.getElementById('select2-empresa_id2-container').innerHTML = value.emp_nombre;
					document.formDatosUsuario.ruc_empresa.value = value.emp_ruc;
										
					if(value.rol_id>3){
						document.getElementById('checkTareaA2').checked = true;
						$("#listaTareas2").show();
					}else{
						document.getElementById('checkTareaD2').checked = true;
					}
					
					
				});

			}
		});

		var rowCount = 25;
		for(var i=1; i <= rowCount; i++){
			try{
				$("input[name=checkT2_"+i+"]").each(function(){
					if(this.checked)
					{
						this.checked = false;
					}
				});
			}catch(error){
				
			}
		}

	
		if(document.getElementById('checkTareaA2').checked){
			$.ajax({
				url: 'webservice/WService.php/rol/'+document.formDatosUsuario.verRol_id.value+'',
				dataType:'json',
				type:'get',
				cache:'false',
				async: false,
	      		crossDomain: true,
				success: function(data){
					var datos = '';
					$(data).each(function(index, value){
						$("input[name=checkT2_"+value.tar_id+"]").each(function(){
							if(!this.checked)
							{
								this.checked = true;
							}
						});
					});

				}
			});
		}

		

		$('#datosUsuario').modal('show');
	}

	function editarDatosUsuario(){
		//DATOS USUARIO
		var codUsuario = document.formDatosUsuario.codigo_usuario.value;
		var cedulaUsuario = document.formDatosUsuario.cedula_usuario.value;
		var nombreUsuario = document.formDatosUsuario.nombre_usuario.value;
		var apellidoUsuario = document.formDatosUsuario.apellido_usuario.value;
		var sexoUsuario = document.formDatosUsuario.sexo_usuario.value;
		var emailUsuario = document.formDatosUsuario.email_usuario.value;
		var domicilioUsuario = document.formDatosUsuario.domicilio_usuario.value;
		var telefonoUsuario = document.formDatosUsuario.telefono_usuario.value;
		var estadoUsuario = document.formDatosUsuario.estado_usuario.value;

		var empresaID = document.formDatosUsuario.empresa_id.value;
		var empresaSESSION = <?= $_SESSION['IDEMPRESA'] ?>;
		var cargoID = document.formDatosUsuario.rol_usuario.value;
		var rolID = 0;
		var rolID_last = document.formDatosUsuario.verRol_id.value;

		if(document.getElementById('checkTareaD2').checked) rolID = cargoID;

		if(cedulaUsuario.trim()==''||nombreUsuario.trim()==''||apellidoUsuario.trim()==''||emailUsuario.trim()==''
		||domicilioUsuario.trim()==''||telefonoUsuario.trim()==''){
			toastr.options = {"timeOut": "1000"};
			toastr.error('Llene todos los campos','Estado');
		}
		else{

			if(empresaID==''&&empresaSESSION==0){
				toastr.options = {"timeOut": "1000"};
				toastr.error('Escoga una Empresa para el Usuario','Estado');
			}else{
				if(empresaSESSION!=0) empresaID = empresaSESSION;
				var listaTareas = [];
				var rowCount = 25;

				for(var i=1; i <= rowCount; i++){
					try{
						$("input[name=checkT2_"+i+"]").each(function(){
							if(this.checked)
							{
								listaTareas.push(i);
							}
						});
					}catch(error){
						
					}
					
				}

				if(document.getElementById('checkTareaA2').checked && listaTareas.length==0){
					toastr.options = {"timeOut": "1000"};
					toastr.error('Asigne tareas al Usuario','Estado');
				}else{
					
					ajax = objetoAjax();
					ajax.open("POST", "usuario/editar_usuario.php", true);
					ajax.onreadystatechange=function() {
						if (ajax.readyState==4) {
							var mensajeRespuesta = ajax.responseText;
							if(mensajeRespuesta == 'BIEN'){
								toastr.options = {"timeOut": "1000"};
								toastr.success('Cambios guardados con éxito','Estado');
								setTimeout(function(){
									window.location.href = 'usuario.php?v=1';
								},1000)

							}else{
								toastr.options = {"timeOut": "1000"};
								toastr.error(mensajeRespuesta,'Estado');
							}
						}
					}
					ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
					ajax.send("cedulaUsuario="+cedulaUsuario+"&nombreUsuario="+nombreUsuario+"&apellidoUsuario="+apellidoUsuario+
					"&emailUsuario="+emailUsuario+"&sexoUsuario="+sexoUsuario+"&domicilioUsuario="+domicilioUsuario+
					"&codigoUsuario="+codUsuario+"&estadoUsuario="+estadoUsuario+"&empresaID="+empresaID+
					"&rolID="+rolID+"&rolID_last="+rolID_last+"&cargoID="+cargoID+"&telefonoUsuario="+telefonoUsuario+
					"&listaTareas="+listaTareas);
				}
			}
			
		}
	}

	function cambiarEstadoUsuario(codUsuario){
		var estado = false;
		if(document.getElementById('estado_usuario'+codUsuario+'').checked){
			estado = true;
		}

		ajax = objetoAjax();
		ajax.open("POST", "usuario/cambiarEstado.php", true);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				var mensajeRespuesta = ajax.responseText;

				if(mensajeRespuesta == 'BIEN'){
					toastr.options = {"timeOut": "1000"};
					toastr.success('Estado cambiado con éxito','Estado');
				}else{
					toastr.options = {"timeOut": "1000"};
					toastr.error(mensajeRespuesta,'Estado');
				}
			}
		}
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.send("&codUsuario="+codUsuario+"&estadoUsuario="+estado);

	}

	function mostrarEliminarMensaje(codUsuario){
		document.getElementById('codUsuario_eliminar').value = codUsuario;

		$('#mensajeEliminar').modal('show');
	}

	function eliminarUsuario(){
		var cod_usuario = document.getElementById('codUsuario_eliminar').value;

		ajax = objetoAjax();
		ajax.open("POST", "usuario/eliminar_usuario.php", true);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				var mensajeRespuesta = ajax.responseText;

				if(mensajeRespuesta == 'BIEN'){
					toastr.options = {"timeOut": "1000"};
					toastr.success('Se ha eliminado con éxito!','Estado');
					setTimeout(function(){
						window.location.href = 'usuario.php?v=1';
					},1000)

				}else{
					toastr.options = {"timeOut": "1000"};
					toastr.error(mensajeRespuesta,'Estado');
				}
			}
		}
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.send("cod_usuario="+cod_usuario);
	}

</script>


<?php
include('pie.php');
?>