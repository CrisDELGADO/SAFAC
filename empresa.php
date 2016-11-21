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
		<h1>Empresa<small>Sección Empresa</small></h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-building"></i> Empresa</a></li>
		</ol>
	</section>

	<?php
		include('empresa/tarea.php');
		if(!$crear && !$ver){
			header('Location: index.php');
		}
	?>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<!-- left column -->
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
									echo '<li><a href="#crear-chart" data-toggle="tab"> Nueva </a></li>';
								}
							}else{
								if($ver)
								{
									if($crear)echo '<li><a href="#ver-chart" data-toggle="tab"> Listar </a></li>';
									else echo '<li class="active"><a href="#ver-chart" data-toggle="tab"> Listar </a></li>';
								}
								if($crear)
								{
									echo '<li class="active"><a href="#crear-chart" data-toggle="tab"> Nueva </a></li>';
								}
							}
						?>
						<li class="pull-left header"><i class="fa fa-building"></i> Empresa</li>
					</ul>
					<div class="tab-content no-padding">
						<!-- Morris chart - Sales -->
						<div class="chart tab-pane <?php if($_REQUEST['v']!=1)echo 'active'; ?>" id="crear-chart" style="position: relative;">
							<form role="form" name="formNuevaEmpresa">
								<div class="box-body">
									<div class="col-lg-4 ">
										<br>
										<img src="img/empresa.jpg" class="img-circle" alt="User Image">
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label>Código:</label>
											<input type="text" class="form-control" name="codigo_empresa" placeholder="Generado Automáticamente" disabled>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label>RUC:</label>
											<input type="text" class="form-control" name="ruc_empresa" placeholder="RUC">
										</div>
									</div>
									<div class="col-lg-8">
										<div class="form-group">
											<label>Nombre:</label>
											<input type="text" class="form-control" name="nombre_empresa" placeholder="Nombre">
										</div>
									</div>
									<div class="col-lg-4 ">
										<div class="form-group">
											<label>Teléfono:</label>
											<input type="text" class="form-control" name="telefono_empresa" placeholder="Teléfono">
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label>Estado:</label>
											<select class="form-control" name="estado_empresa" style="cursor:pointer;">
												<option value="True">Habilitado</option>
												<option value="False">Deshabilitado</option>
											</select>
										</div>
									</div>
									<div class="col-lg-4 ">
										<div class="form-group">
											<label>País:</label>
											<select class="form-control select2 select2-hidden-accessible" name="pais_id"  id="pais_id"
									style="width: 100%;" tabindex="-1" aria-hidden="true" onchange="llenarProvincias();">
											</select>
										</div>
									</div>
									<div class="col-lg-8">
										<div class="form-group">
											<label>Dirección:</label>
											<input type="text" class="form-control" name="direccion_empresa" placeholder="Dirección">
										</div>
									</div>
									<div class="col-lg-4 ">
										<div class="form-group">
											<label>Provincia:</label>
											<select class="form-control select2 select2-hidden-accessible" name="provincia_id"  id="provincia_id"
											style="width: 100%;" tabindex="-1" aria-hidden="true" onchange="llenarCantones();">
											</select>
										</div>
									</div>
									<div class="col-lg-4 ">
										<div class="form-group">
											<label>Cantón:</label>
											<select class="form-control select2 select2-hidden-accessible" name="canton_id" 
											id="canton_id" style="width: 100%;" tabindex="-1" aria-hidden="true"
											onchange="llenarParroquias();">
											</select>
										</div>
									</div>
									<div class="col-lg-4 ">
										<div class="form-group">
											<label>Parroquia:</label>
											<select class="form-control select2 select2-hidden-accessible" name="parroquia_id"  
											id="parroquia_id" style="width: 100%;" tabindex="-1" aria-hidden="true" >
											</select>
										</div>
									</div>
									<div class="col-lg-12 ">
										<div class="form-group">
											<label>Ubicación: <a href="https://www.google.com.ec/maps/@-3.2568589,-79.9956923,13z" target="_blank">(Google Maps)</a></label>
											<input type="text" class="form-control" name="ubicacion_empresa" id="ubicacion_empresa" placeholder="Ubicación obtenida por Google Maps">
										</div>
									</div>
									<div class="col-lg-12" id="mapa" align="center">
									</div>
								</div><!-- /.box-body -->
							</form>

							<div style="text-align:center">
								<button class="btn btn-primary btn-lg" onclick="nuevaEmpresa(); return false;"><i class="fa fa-save"></i> Guardar</button>
							</div>
							<br>
						</div>
						<div class="chart tab-pane <?php if($_REQUEST['v']==1)echo 'active'; ?>" id="ver-chart" style="position: relative;">
							<div class="box-body table-responsive">
								<table id="example1" class="table table-bordered table-striped">
									<thead>
										<tr>
											<th># Código</th>
											<th>Empresa</th>
											<th>RUC</th>
											<th>Estado</th>
											<th>Acciones</th>
										</tr>
									</thead>
									<tbody>
										<?php
											require('conexion.php');
											try{
												pg_query($conn,"BEGIN WORK");
												$sql = "SELECT * FROM empresa order by emp_id asc";
												$resultado = pg_query($conn, $sql);

												while($reg=pg_fetch_assoc($resultado)){
										?>
										<tr>
											<td>
												<?= $reg['emp_id'] ?>
											</td>
											<td>
												<?= $reg['emp_nombre'] ?>
											</td>
											<td>
												<?= $reg['emp_ruc'] ?>
											</td>
											<td>
												<input type="checkbox" <?php if(!$editar)echo 'disabled' ?> id="estado_empresa<?= $reg['emp_id'] ?>" <?php if($reg['emp_estado']=='t')echo 'checked';?> data-toggle="toggle" data-size="small" onchange="cambiarEstadoEmpresa(<?= $reg['emp_id'] ?>)">
											</td>
											<td>
												<a onclick="mostrarDatosEmpresa(<?= $reg['emp_id']; ?>);$('#btnEditar').hide();"  title="Ver" style="cursor: pointer;">
													<button class="btn"><i class="fa fa-search"></i></button>
												</a>
												<?php 
													if($editar){
												?>
												<a onclick="mostrarDatosEmpresa(<?= $reg['emp_id']; ?>);$('#btnEditar').show();"  title="Editar" style="cursor: pointer;">
													<button class="btn"><i class="fa fa-edit"></i></button>
												</a>
												<?php
													}
													if($eliminar){
												?>
												<a onclick="mostrarEliminarMensaje(<?= $reg['emp_id']; ?>);"  title="Eliminar" style="cursor: pointer;">
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
				</div><!-- /.nav-tabs-custom -->
			</div>
		</div>
	</section><!-- /.content -->
</div><!-- /.content-wrapper -->

<!--MODAL-->
<div class="modal" id="datosEmpresa">
	<div class="modal-dialog" >
		<div class="modal-content" >
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title"><i class="fa fa-building"></i> <i> Datos de Empresa</i></h4>
			</div>
			<div class="modal-body">
				<form role="form" name="formDatosEmpresa">
					<div class="box-body">
						<div class="col-lg-6">
							<div class="form-group">
								<label>Código:</label>
								<input type="text" class="form-control" name="cod_empresa" id="cod_empresa" placeholder="Generado Automáticamente" disabled>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>RUC:</label>
								<input type="text" class="form-control" name="ruc_empresa" id="ruc_empresa" placeholder="RUC">
							</div>
						</div>
						<div class="col-lg-12">
							<div class="form-group">
								<label>Nombre:</label>
								<input type="text" class="form-control" name="nombre_empresa" id="nombre_empresa" placeholder="Nombre de Empresa">
							</div>
						</div>
						<div class="col-lg-12">
							<div class="form-group">
								<label>Dirección:</label>
								<input type="text" class="form-control" name="direccion_empresa" id="direccion_empresa"  placeholder="Dirección de Empresa">
							</div>
						</div>
						<div class="col-lg-6 ">
							<div class="form-group">
								<label>País:</label>
								<select class="form-control select2 select2-hidden-accessible" name="pais_id2"  id="pais_id2"
								style="width: 100%;" tabindex="-1" aria-hidden="true" onchange="llenarProvincias2();">
								</select>
							</div>
						</div>
						<div class="col-lg-6 ">
							<div class="form-group">
								<label>Provincia:</label>
								<select class="form-control select2 select2-hidden-accessible" name="provincia_id2"  
								id="provincia_id2" style="width: 100%;" tabindex="-1" aria-hidden="true" 
								onchange="llenarCantones2();">
								</select>
							</div>
						</div>
						<div class="col-lg-6 ">
							<div class="form-group">
								<label>Cantón:</label>
								<select class="form-control select2 select2-hidden-accessible" name="canton_id2"  
								id="canton_id2" style="width: 100%;" tabindex="-1" aria-hidden="true" 
								onchange="llenarParroquias2();">
								</select>
							</div>
						</div>
						<div class="col-lg-6 ">
							<div class="form-group">
								<label>Parroquia:</label>
								<select class="form-control select2 select2-hidden-accessible" name="parroquia_id2"  
								id="parroquia_id2" style="width: 100%;" tabindex="-1" aria-hidden="true" >
								</select>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>Teléfono:</label>
								<input type="text" class="form-control" name="telefono_empresa" id="telefono_empresa" placeholder="Teléfono de Empresa">
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>Estado:</label>
								<select class="form-control" name="estado_empresa" id="estado_empresa">
									<option value="true">Habilitado</option>
									<option value="false">Deshabilitado</option>
								</select>
							</div>
						</div>
						<div class="col-lg-12 ">
							<div class="form-group">
								<label>Ubicación: <a href="https://www.google.com.ec/maps/@-3.2568589,-79.9956923,13z" target="_blank">(Google Maps)</a></label>
								<input type="text" class="form-control" name="ubicacion_empresa2" id="ubicacion_empresa2" placeholder="Ubicación obtenida por Google Maps">
							</div>
						</div>
						<div class="col-lg-12">
							<div id="mostrarMapa" align="center">
							</div>
						</div>
					</div><!-- /.box-body -->
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-remove text-alert"></i> Cancelar</button>
				<button type="button" class="btn btn-primary" id="btnEditar" onclick="editarDatosEmpresa(); return false;"><i class="fa fa-save"></i> Guardar Cambios</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal" id="mensajeEliminar">
	<div class="modal-dialog" >
		<div class="modal-content" >
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title"><i class="fa fa-building"></i> <i> Eliminar Empresa</i></h4>
			</div>
			<div class="modal-body">
				<input type="hidden" id="codEmpresa_eliminar"> 
				<h4>¿Está seguro que desea eliminar esta empresa?</h4>
				<br><i>NOTA: Recuerde que solo se eliminará cuando no existan datos relacionados.</i>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-remove text-alert"></i> Cancelar</button>
				<button type="button" class="btn btn-primary" onclick="eliminarEmpresa();"><i class="fa fa-trash-o text-alert"></i> Eliminar</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script type="text/javascript">
	$("#btnEditar").hide();

	$( "#ubicacion_empresa" ).keyup(function() {
		document.getElementById("mapa").innerHTML = document.getElementById('ubicacion_empresa').value; 
	});

	//Llenado de Paises
	$.ajax({
		url: 'webservice/WService.php/pais/',
		dataType:'json',
		type:'get',
		async: false,
		crossDomain: true,
		cache: false,
		success: function(data){
			var datos = '';
			$(data.pais).each(function(index, value){
				datos +="<option value='"+value.pai_id+"'>"+value.pai_nombre+"</option>";
			});
			document.getElementById("pais_id").innerHTML = datos; 
			document.getElementById("pais_id").selectedIndex=-1;
			document.getElementById("pais_id2").innerHTML = datos; 
			document.getElementById("pais_id2").selectedIndex=-1;
		}
	});

	function llenarProvincias(){
		var id = document.getElementById("pais_id").value;
		$.ajax({
			url: 'webservice/WService.php/provincia/'+id,
			dataType:'json',
			type:'get',
			async: false,
			crossDomain: true,
			cache: false,
			success: function(data){
				var datos = '';
				$(data.provincia).each(function(index, value){
					datos +="<option value='"+value.pro_id+"'>"+value.pro_nombre+"</option>";
				});
				document.getElementById("provincia_id").innerHTML = datos; 
				document.getElementById("provincia_id").selectedIndex=-1;
				document.getElementById('select2-provincia_id-container').innerHTML = '';
				document.getElementById('select2-canton_id-container').innerHTML = '';
				document.getElementById('select2-parroquia_id-container').innerHTML = '';
			}
		});
	}

	function llenarProvincias2(){
		var id = document.getElementById("pais_id2").value;
		$.ajax({
			url: 'webservice/WService.php/provincia/'+id,
			dataType:'json',
			type:'get',
			async: false,
			crossDomain: true,
			cache: false,
			success: function(data){
				var datos = '';
				$(data.provincia).each(function(index, value){
					datos +="<option value='"+value.pro_id+"'>"+value.pro_nombre+"</option>";
				});
				document.getElementById("provincia_id2").innerHTML = datos; 
				document.getElementById("provincia_id2").selectedIndex=-1;
				document.getElementById('select2-provincia_id2-container').innerHTML = '';
				document.getElementById('select2-canton_id2-container').innerHTML = '';
				document.getElementById('select2-parroquia_id2-container').innerHTML = '';
			}
		});
	}

	function llenarCantones(){
		var id = document.getElementById("provincia_id").value;
		$.ajax({
			url: 'webservice/WService.php/canton/'+id,
			dataType:'json',
			type:'get',
			async: false,
			crossDomain: true,
			cache: false,
			success: function(data){
				var datos = '';
				$(data.canton).each(function(index, value){
					datos +="<option value='"+value.can_id+"'>"+value.can_nombre+"</option>";
				});
				document.getElementById("canton_id").innerHTML = datos; 
				document.getElementById("canton_id").selectedIndex=-1;
				document.getElementById('select2-canton_id-container').innerHTML = '';
				document.getElementById('select2-parroquia_id-container').innerHTML = '';
			}
		});
	}

	function llenarCantones2(){
		var id = document.getElementById("provincia_id2").value;
		$.ajax({
			url: 'webservice/WService.php/canton/'+id,
			dataType:'json',
			type:'get',
			async: false,
			crossDomain: true,
			cache: false,
			success: function(data){
				var datos = '';
				$(data.canton).each(function(index, value){
					datos +="<option value='"+value.can_id+"'>"+value.can_nombre+"</option>";
				});
				document.getElementById("canton_id2").innerHTML = datos; 
				document.getElementById("canton_id2").selectedIndex=-1;
				document.getElementById('select2-canton_id2-container').innerHTML = '';
				document.getElementById('select2-parroquia_id2-container').innerHTML = '';
			}
		});
	}

	function llenarParroquias(){
		var id = document.getElementById("canton_id").value;
		$.ajax({
			url: 'webservice/WService.php/parroquia/'+id,
			dataType:'json',
			type:'get',
			async: false,
			crossDomain: true,
			cache: false,
			success: function(data){
				var datos = '';
				$(data.parroquia).each(function(index, value){
					datos +="<option value='"+value.par_id+"'>"+value.par_nombre+"</option>";
				});
				document.getElementById("parroquia_id").innerHTML = datos; 
				document.getElementById("parroquia_id").selectedIndex=-1;
				document.getElementById('select2-parroquia_id-container').innerHTML = '';
			}
		});
	}

	function llenarParroquias2(){
		var id = document.getElementById("canton_id2").value;
		$.ajax({
			url: 'webservice/WService.php/parroquia/'+id,
			dataType:'json',
			type:'get',
			async: false,
			crossDomain: true,
			cache: false,
			success: function(data){
				var datos = '';
				$(data.parroquia).each(function(index, value){
					datos +="<option value='"+value.par_id+"'>"+value.par_nombre+"</option>";
				});
				document.getElementById("parroquia_id2").innerHTML = datos; 
				document.getElementById("parroquia_id2").selectedIndex=-1;
				document.getElementById('select2-parroquia_id2-container').innerHTML = '';
			}
		});
	}

	function nuevaEmpresa(){
		//DATOS EMPRESA
		var nombreEmpresa = document.formNuevaEmpresa.nombre_empresa.value;
		var rucEmpresa = document.formNuevaEmpresa.ruc_empresa.value;
		var direccionEmpresa = document.formNuevaEmpresa.direccion_empresa.value;
		var telefonoEmpresa = document.formNuevaEmpresa.telefono_empresa.value;
		var estadoEmpresa = document.formNuevaEmpresa.estado_empresa.value;
		var parroquia = document.formNuevaEmpresa.parroquia_id.value;
		var ubicacion = document.formNuevaEmpresa.ubicacion_empresa.value;



		if(nombreEmpresa.trim()==''||rucEmpresa.trim()==''||direccionEmpresa.trim()==''||telefonoEmpresa.trim()==''
		||ubicacion.trim()==''||parroquia==''){
			toastr.options = {"timeOut": "1000"};
			toastr.error('Llene todos los campos','Estado');


		}else{
			ajax = objetoAjax();
			ajax.open("POST", "empresa/ingresar_empresa.php", true);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4) {
					var mensajeRespuesta = ajax.responseText;

					if(mensajeRespuesta == 'BIEN'){
						toastr.options = {"timeOut": "1000"};
						toastr.success('Guardado con éxito','Estado');
						setTimeout(function(){
							window.location.href = 'empresa.php';
						},1000)

					}else{
						toastr.options = {"timeOut": "1000"};
						toastr.error(mensajeRespuesta,'Estado');
					}
				}
			}
			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax.send("nombre="+nombreEmpresa+"&ruc="+rucEmpresa+"&direccion="+direccionEmpresa+
			"&telefono="+telefonoEmpresa+"&estado="+estadoEmpresa+"&parroquia_id="+parroquia+
			"&ubicacion="+ubicacion);
		}
	}

	function mostrarDatosEmpresa(codEmpresa){
		$.ajax({
			url: 'webservice/WService.php/empresa/'+codEmpresa+'',
			dataType:'json',
			type:'get',
			cache:'false',
			async: false,
      		crossDomain: true,
			success: function(data){
				var datos = '';
				$(data).each(function(index, value){
					document.formDatosEmpresa.cod_empresa.value = value.emp_id; 
					document.formDatosEmpresa.nombre_empresa.value = value.emp_nombre; 
					document.formDatosEmpresa.ruc_empresa.value = value.emp_ruc; 
					document.formDatosEmpresa.direccion_empresa.value = value.emp_direccion; 
					document.formDatosEmpresa.telefono_empresa.value = value.emp_telefono; 
					document.formDatosEmpresa.estado_empresa.value = value.emp_estado; 
					document.formDatosEmpresa.ubicacion_empresa2.value = value.emp_ubicacion; 
					document.getElementById('mostrarMapa').innerHTML = value.emp_ubicacion;
					document.getElementById('pais_id2').value = value.pai_id;
					document.getElementById('select2-pais_id2-container').innerHTML = value.pai_nombre;
					llenarProvincias2();
					document.getElementById('provincia_id2').value = value.pro_id;
					document.getElementById('select2-provincia_id2-container').innerHTML = value.pro_nombre;
					llenarCantones2();
					document.getElementById('canton_id2').value = value.can_id;
					document.getElementById('select2-canton_id2-container').innerHTML = value.can_nombre;
					llenarParroquias2();
					document.getElementById('parroquia_id2').value = value.par_id;
					document.getElementById('select2-parroquia_id2-container').innerHTML = value.par_nombre;
				});

			}
		});

		

		$('#datosEmpresa').modal('show');
	}

	$( "#ubicacion_empresa2" ).keyup(function() {
		document.getElementById("mostrarMapa").innerHTML = document.getElementById('ubicacion_empresa2').value; 
	});

	function editarDatosEmpresa(){
		var codEmpresa = document.formDatosEmpresa.cod_empresa.value;
		var nombreEmpresa = document.formDatosEmpresa.nombre_empresa.value;
		var rucEmpresa = document.formDatosEmpresa.ruc_empresa.value;
		var direccionEmpresa = document.formDatosEmpresa.direccion_empresa.value;
		var telefonoEmpresa = document.formDatosEmpresa.telefono_empresa.value;
		var estadoEmpresa = document.formDatosEmpresa.estado_empresa.value;
		var parroquia = document.formDatosEmpresa.parroquia_id2.value;
		var ubicacion = document.formDatosEmpresa.ubicacion_empresa2.value;

		if(nombreEmpresa.trim()==''||rucEmpresa.trim()==''||direccionEmpresa.trim()==''||telefonoEmpresa.trim()==''
		||ubicacion.trim()==''||parroquia==''){
			toastr.options = {"timeOut": "1000"};
			toastr.error('Llene todos los campos','Estado');
		}else{
			ajax = objetoAjax();
			ajax.open("POST", "empresa/editar_empresa.php", true);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4) {
					var mensajeRespuesta = ajax.responseText;

					if(mensajeRespuesta == 'BIEN'){
						toastr.options = {"timeOut": "1000"};
						toastr.success('Cambios guardados con éxito','Estado');
						setTimeout(function(){
							window.location.href = 'empresa.php?v=1';
						},1000)

					}else{
						toastr.options = {"timeOut": "1000"};
						toastr.error(mensajeRespuesta,'Estado');
					}
				}
			}
			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax.send("codigo="+codEmpresa+"&nombre="+nombreEmpresa+"&ruc="+rucEmpresa+"&direccion="+direccionEmpresa+
			"&telefono="+telefonoEmpresa+"&estado="+estadoEmpresa+"&parroquia_id="+parroquia+
			"&ubicacion="+ubicacion);
		}
	}

	function cambiarEstadoEmpresa(codEmpresa){
		var estado = false;
		if(document.getElementById('estado_empresa'+codEmpresa+'').checked){
			estado = true;
		}

		ajax = objetoAjax();
		ajax.open("POST", "empresa/cambiarEstado.php", true);
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
		ajax.send("&cod_empresa="+codEmpresa+"&estado_empresa="+estado);

	}

	function mostrarEliminarMensaje(codEmpresa){
		document.getElementById('codEmpresa_eliminar').value = codEmpresa;

		$('#mensajeEliminar').modal('show');
	}

	function eliminarEmpresa(){
		var cod_empresa = document.getElementById('codEmpresa_eliminar').value;

		ajax = objetoAjax();
		ajax.open("POST", "empresa/eliminar_empresa.php", true);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				var mensajeRespuesta = ajax.responseText;

				if(mensajeRespuesta == 'BIEN'){
					toastr.options = {"timeOut": "1000"};
					toastr.success('Se ha eliminado con éxito!','Estado');
					setTimeout(function(){
						window.location.href = 'empresa.php?v=1';
					},1000)

				}else{
					toastr.options = {"timeOut": "1000"};
					toastr.error(mensajeRespuesta,'Estado');
				}
			}
		}
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.send("cod_empresa="+cod_empresa);
	}

</script>

<?php
	include('pie.php')
?>