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
		<h1>Sensor-Actuador<small>Sección Sensor-Actuador</small></h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Sensor-Actuador</a></li>
		</ol>
	</section>

	<?php
		include('sensor-actuador/tarea.php');
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
						
						<li class="pull-left header"><i class="fa fa-dashboard"></i> Sensor-Actuador</li>
					</ul>
					<div class="tab-content no-padding">
						<div class="chart tab-pane <?php if($_REQUEST['v']!=1)echo 'active'; ?> <?php if(!$crear) {echo 'hidden';} ?>" id="crear-chart" style="position: relative;">
							<form role="form" name="formNuevoSensor">
								<div class="box-body">
									<div class="col-lg-4">
										<img src="img/sensor.jpg" width="100%" alt="User Image">
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label>Código:</label>
											<input type="text" class="form-control" name="cod_sensor_actuador" placeholder="Generado Automáticamente" disabled>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label>Nombre:</label>
											<input type="text" class="form-control" name="nombre_sensor_actuador" placeholder="Nombre">
										</div>
									</div>
									<div class="col-lg-8">
										<div class="form-group">
											<label>Descripción:</label>
											<input type="textarea" class="form-control" name="descripcion_sensor_actuador" placeholder="Descripción">
											</div>
									</div>
									<div class="col-lg-2 col-md-offset-4">
										<div class="form-group">
											<label>Tipo:</label>
											<select class="form-control" name="tipo_sensor_actuador" style="cursor: pointer;">
												<option value="1">ACTUADOR</option>
												<option value="2">SENSOR</option>
											</select>
										</div>
									</div>
									<div class="col-lg-3">
										<div class="form-group">
											<label>Unidad de Medida:</label>
											<input type="text" class="form-control" name="unidadmedida_sensor_actuador" placeholder="Unidad de Medida">
										</div>
									</div>
									<div class="col-lg-3">
										<div class="form-group">
											<label>Acción:</label>
											<input type="text" class="form-control" name="accion_sensor_actuador" placeholder="Acción">
										</div>
									</div>
								</div><!-- /.box-body -->
							</form>
							<div style="text-align:center">
								<button class="btn btn-primary btn-lg" onclick="nuevoSensor(); return false;"><i class="fa fa-save"></i> Guardar</button>
								<br><br>
							</div>
						</div>
						<div class="chart tab-pane <?php if($_REQUEST['v']==1)echo 'active '; if(!$crear&&$ver)echo 'active' ?> " id="ver-chart" style="position: relative;">
							<div class="box-body table-responsive">
								<table id="example1" class="table table-bordered table-striped">
									<thead>
										<tr>
											<th># Código</th>
											<th>Nombre</th>
											<th>Descripción</th>
											<th>TIPO</th>
											<th>MEDIDA</th>
											<th>ACCIONES</th>
										</tr>
									</thead>
									<tbody>
										<?php
										include('conexion.php');
										try{
											pg_query($conn,"BEGIN WORK");
											$sql = "SELECT * FROM sensor_actuador, tipo_sensor_actuador where tipo_sensor_actuador.tse_id=sensor_actuador.tse_id";
											$resultado = pg_query($conn, $sql);

											while($reg=pg_fetch_assoc($resultado)){
												

										?>
												<tr>
													<td><?php echo $reg['sen_id'] ?></td>
													<td><?php echo $reg['sen_nombre'] ?></td>
													<td><?php echo $reg['sen_descripcion'] ?></td>
													<td><?php echo $reg['tse_nombre'] ?></td>
													<td><?php echo $reg['sen_medida'] ?></td>
													<td>
														<a onclick="mostrarDatosSensor(<?= $reg['sen_id']; ?>);$('#btnEditar').hide();"  title="Ver" style="cursor: pointer;">
															<button class="btn"><i class="fa fa-search"></i></button>
														</a>
														<?php 
															if($editar){
														?>
														<a onclick="mostrarDatosSensor(<?= $reg['sen_id']; ?>);$('#btnEditar').show();"  title="Editar" style="cursor: pointer;">
															<button class="btn"><i class="fa fa-edit"></i></button>
														</a>
														<?php
															}
															if($eliminar){
														?>
														<a onclick="mostrarEliminarMensaje(<?= $reg['sen_id']; ?>);"  title="Eliminar" style="cursor: pointer;">
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
										}catch(Exception $e){
											pg_query($conn,"ROLLBACK WORK");
											echo "Fallo: " . $e->getMessage();
										}
										?>
								</table>
							</div>
						</div>
									</tbody>
					</div>
				</div>
			</div>
		</div>
	</section><!-- /.content -->
</div><!-- /.content-wrapper -->


<div class="modal" id="datosSensor">
	<div class="modal-dialog" >
		<div class="modal-content" >
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title"><i class="fa fa-dashboard"></i> <i> Datos de Sensor-Actuador</i></h4>
			</div>
			<div class="modal-body">
				<form role="form" name="formDatosSensor">
					<div class="box-body">
						<div class="col-lg-6">
							<div class="form-group">
								<label>Código:</label>
								<input type="text" class="form-control" name="cod_sensor_actuador" placeholder="Generado Automáticamente" disabled>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>Nombre:</label>
								<input type="text" class="form-control" name="nombre_sensor_actuador" placeholder="Nombre">
							</div>
						</div>
						<div class="col-lg-12">
							<div class="form-group">
								<label>Descripción:</label>
								<input type="textarea" class="form-control" name="descripcion_sensor_actuador" placeholder="Descripción">
								</div>
						</div>
						<div class="col-lg-4">
							<div class="form-group">
								<label>Tipo:</label>
								<select class="form-control" name="tipo_sensor_actuador" style="cursor: pointer;">
									<option value="1">ACTUADOR</option>
									<option value="2">SENSOR</option>
								</select>
							</div>
						</div>
						<div class="col-lg-4">
							<div class="form-group">
								<label>Unidad de Medida:</label>
								<input type="text" class="form-control" name="unidadmedida_sensor_actuador" placeholder="Unidad de Medida">
							</div>
						</div>
						<div class="col-lg-4">
							<div class="form-group">
								<label>Acción:</label>
								<input type="text" class="form-control" name="accion_sensor_actuador" placeholder="Acción">
							</div>
						</div>
				
					</div><!-- /.box-body -->
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-remove text-alert"></i> Cancelar</button>
				<button type="button" class="btn btn-primary" id="btnEditar" onclick="editarDatosSensor(); return false;"><i class="fa fa-save"></i> Guardar Cambios</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal" id="mensajeEliminar">
	<div class="modal-dialog" >
		<div class="modal-content" >
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title"><i class="fa fa-dashboard"></i> <i> Eliminar Sensor-Actuador</i></h4>
			</div>
			<div class="modal-body">
				<input type="hidden" id="codSensor_eliminar"> 
				<h4>¿Está seguro que desea eliminar este sensor-actuador?</h4>
				<br><i>NOTA: Recuerde que solo se eliminará cuando no existan datos relacionados.</i>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-remove text-alert"></i> Cancelar</button>
				<button type="button" class="btn btn-primary" onclick="eliminarSensor();"><i class="fa fa-trash-o text-alert"></i> Eliminar</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<script type="text/javascript">
	
	function nuevoSensor(){
		//DATOS SENSOR
		var nombre = document.formNuevoSensor.nombre_sensor_actuador.value;
		var descripcion = document.formNuevoSensor.descripcion_sensor_actuador.value;
		var unidad = document.formNuevoSensor.unidadmedida_sensor_actuador.value;
		var accion = document.formNuevoSensor.accion_sensor_actuador.value;
		var codigoTipo = document.formNuevoSensor.tipo_sensor_actuador.value;


		if(nombre.trim()==''||descripcion.trim()==''){
			toastr.options = {"timeOut": "1000"};
			toastr.error('Campos Obligatorios (Nombre, Descripción)','Estado');
		}else{

			ajax = objetoAjax();
			ajax.open("POST", "sensor-actuador/ingresar_sensor-actuador.php", true);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4) {
					var mensajeRespuesta = ajax.responseText;

					if(mensajeRespuesta == 'BIEN'){
						toastr.options = {"timeOut": "1000"};
						toastr.success('Guardado con éxito','Estado');
						setTimeout(function(){
							window.location.href = 'sensor-actuador.php';
						},1000)

					}else{
						toastr.options = {"timeOut": "1000"};
						toastr.error(mensajeRespuesta,'Estado');
					}
				}
			}
			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax.send("nombre_sensor_actuador="+nombre+"&descripcion_sensor_actuador="+descripcion+"&unidadmedida_sensor_actuador="+unidad+"&accion_sensor_actuador="+accion+"&cod_tipo_sensor_actuador="+codigoTipo);

		}
	}

	function mostrarDatosSensor(codSensor){
		$.ajax({
			url: 'webservice/WService.php/sensor/'+codSensor+'',
			dataType:'json',
			type:'get',
			cache:'false',
			success: function(data){
				var datos = '';
				$(data).each(function(index, value){
					document.formDatosSensor.cod_sensor_actuador.value = value.sen_id; 
					document.formDatosSensor.nombre_sensor_actuador.value = value.sen_nombre; 
					document.formDatosSensor.descripcion_sensor_actuador.value = value.sen_descripcion; 
					document.formDatosSensor.unidadmedida_sensor_actuador.value = value.sen_medida; 
					document.formDatosSensor.accion_sensor_actuador.value = value.sen_accion; 
					document.formDatosSensor.tipo_sensor_actuador.value = value.tse_id; 
				});
			}
		});

		$('#datosSensor').modal('show');
	}


	function editarDatosSensor(){
		//DATOS SENSOR
		var codigoSensor = document.formDatosSensor.cod_sensor_actuador.value;
		var nombre = document.formDatosSensor.nombre_sensor_actuador.value;
		var descripcion = document.formDatosSensor.descripcion_sensor_actuador.value;
		var unidad = document.formDatosSensor.unidadmedida_sensor_actuador.value;
		var accion = document.formDatosSensor.accion_sensor_actuador.value;
		var codigoTipo = document.formDatosSensor.tipo_sensor_actuador.value;


		if(nombre.trim()==''||descripcion.trim()==''){
			toastr.options = {"timeOut": "1000"};
			toastr.error('Campos Obligatorios (Nombre, Descripción)','Estado');
		}else{

			ajax = objetoAjax();
			ajax.open("POST", "sensor-actuador/editar_sensor-actuador.php", true);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4) {
					var mensajeRespuesta = ajax.responseText;

					if(mensajeRespuesta == 'BIEN'){
						toastr.options = {"timeOut": "1000"};
						toastr.success('Cambios guardados con éxito','Estado');
						setTimeout(function(){
							window.location.href = 'sensor-actuador.php?v=1';
						},1000)

					}else{
						toastr.options = {"timeOut": "1000"};
						toastr.error(mensajeRespuesta,'Estado');
					}
				}
			}
			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax.send("codigoSensor="+codigoSensor+"&nombre_sensor_actuador="+nombre+"&descripcion_sensor_actuador="+descripcion+"&unidadmedida_sensor_actuador="+unidad+"&accion_sensor_actuador="+accion+"&cod_tipo_sensor_actuador="+codigoTipo);

		}
	}

	function mostrarEliminarMensaje(codSensor){
		document.getElementById('codSensor_eliminar').value = codSensor;

		$('#mensajeEliminar').modal('show');
	}

	function eliminarSensor(){
		var cod_sensor_actuador = document.getElementById('codSensor_eliminar').value;

		ajax = objetoAjax();
		ajax.open("POST", "sensor-actuador/eliminar_sensor-actuador.php", true);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				var mensajeRespuesta = ajax.responseText;

				if(mensajeRespuesta == 'BIEN'){
					toastr.options = {"timeOut": "1000"};
					toastr.success('Se ha eliminado con éxito!','Estado');
					setTimeout(function(){
						window.location.href = 'sensor-actuador.php?v=1';
					},1000)

				}else{
					toastr.options = {"timeOut": "1000"};
					toastr.error(mensajeRespuesta,'Estado');
				}
			}
		}
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.send("cod_sensor_actuador="+cod_sensor_actuador);
	}



</script>


<?php
include('pie.php');
?>