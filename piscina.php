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
		<h1>Piscina<small>Sección Piscinas</small></h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-object-ungroup"></i> Piscina</a></li>
		</ol>
	</section>

	<?php
		include('piscina/tarea.php');
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
								if($regla){
									echo '<li><a href="#regla-chart" data-toggle="tab"> Asignar Reglas </a></li>';
								}
								if($sensor){
									echo '<li><a href="#sensor-chart" data-toggle="tab"> Asignar Sensor-Actuador </a></li>';
								}
								if($ver)
								{
									echo '<li class="active"><a href="#ver-chart" data-toggle="tab"> Listar </a></li>';
								}
								if($crear)
								{
									echo '<li><a href="#crear-chart" data-toggle="tab"> Nueva </a></li>';
								}
							}else{

								if($_REQUEST['s']==1){
									if($regla){
										echo '<li><a href="#regla-chart" data-toggle="tab"> Asignar Reglas </a></li>';
									}
									if($sensor){
										echo '<li class="active"><a href="#sensor-chart" data-toggle="tab"> Asignar Sensor-Actuador </a></li>';
									}
									if($ver)
									{
										echo '<li><a href="#ver-chart" data-toggle="tab"> Listar </a></li>';
									}
									if($crear)
									{
										echo '<li><a href="#crear-chart" data-toggle="tab"> Nueva </a></li>';
									}
								}else{
									if($_REQUEST['r']==1){
										if($regla){
											echo '<li class="active"><a href="#regla-chart" data-toggle="tab"> Asignar Reglas </a></li>';
										}
										if($sensor){
											echo '<li><a href="#sensor-chart" data-toggle="tab"> Asignar Sensor-Actuador </a></li>';
										}
										if($ver)
										{
											echo '<li><a href="#ver-chart" data-toggle="tab"> Listar </a></li>';
										}
										if($crear)
										{
											echo '<li><a href="#crear-chart" data-toggle="tab"> Nueva </a></li>';
										}
									}else{
										if($regla){
											echo '<li><a href="#regla-chart" data-toggle="tab"> Asignar Reglas </a></li>';
										}
										if($sensor){
											echo '<li><a href="#sensor-chart" data-toggle="tab"> Asignar Sensor-Actuador </a></li>';
										}
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
								}

								
							}
						?>
						
						<li class="pull-left header"><i class="fa fa-object-ungroup"></i> Piscina</li>
					</ul>
					<div class="tab-content no-padding">
						<div class="chart tab-pane <?php if($_REQUEST['v']!=1&&$_REQUEST['s']!=1&&$_REQUEST['r']!=1)echo 'active'; ?> <?php if(!$crear) {echo 'hidden';} ?>" id="crear-chart" style="position: relative;">
							<form role="form" name="formNuevaPiscina">
								<div class="box-body">
									<div class="col-lg-4 ">
										<br>
										<img src="img/agua.jpg" class="img-circle" width="100%" alt="User Image">
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label>Código:</label>
											<input type="text" class="form-control" name="cod_piscina" placeholder="Generado Automáticamente" disabled>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label>Nombre:</label>
											<input type="text" class="form-control" name="nombre_piscina" placeholder="Nombre">
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label>Área (m2):</label>
											<input type="text" class="form-control" name="area_piscina" placeholder="Área">
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label>Volumen (m3):</label>
											<input type="text" class="form-control" name="volumen_piscina" placeholder="Volumen">
										</div>
									</div>
								</div><!-- /.box-body -->
							</form>
							<div style="text-align:center">
								<button class="btn btn-primary btn-lg" onclick="nuevaPiscina(); return false;"><i class="fa fa-save"></i> Guardar</button>
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
											<th>Área</th>
											<th>Volumen</th>
											<th>Acciones</th>
										</tr>
									</thead>
									<tbody>
										<?php
											require('conexion.php');
											try{
												pg_query($conn,"BEGIN WORK");
												$sql = "SELECT * FROM piscina where emp_id=".$_SESSION['IDEMPRESA']."";
												$resultado = pg_query($conn, $sql);

												while($reg=pg_fetch_assoc($resultado)){
										?>
										<tr>
											<td>
												<?= $reg['pis_id'] ?>
											</td>
											<td>
												<?= $reg['pis_nombre'] ?>
											</td>
											<td>
												<?= $reg['pis_area'] ?>
											</td>
											<td>
												<?= $reg['pis_volumen'] ?>
											</td>
											<td>
												<a onclick="mostrarDatosPiscina(<?= $reg['pis_id']; ?>);$('#btnEditar').hide();"  title="Ver" style="cursor: pointer;">
													<button class="btn"><i class="fa fa-search"></i></button>
												</a>
												<?php 
													if($editar){
												?>
												<a onclick="mostrarDatosPiscina(<?= $reg['pis_id']; ?>);$('#btnEditar').show();"  title="Editar" style="cursor: pointer;">
													<button class="btn"><i class="fa fa-edit"></i></button>
												</a>
												<?php
													}
													if($eliminar){
												?>
												<a onclick="mostrarEliminarMensaje(<?= $reg['pis_id']; ?>);"  title="Eliminar" style="cursor: pointer;">
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
						<div class="chart tab-pane <?php if($_REQUEST['s']==1)echo 'active'; ?>" id="sensor-chart" style="position: relative;">
							<div class="col-md-3">
								<table id="example2" class="table table-bordered table-hover">
									<thead>
										<tr>
											<th style="width:10px"><i class="fa fa-object-ungroup"></i></th>
											<th>Piscina</th>
										</tr>
									</thead>
									<tbody>
										<?php
											require('webservice/WSConexion.php');
											$db = getConnection();

											try{
											$db->beginTransaction(); 

											$sql = "SELECT * FROM piscina WHERE emp_id=:cod_empresa order by pis_nombre"; 

											$stmt = $db->prepare($sql);
											$stmt->bindParam("cod_empresa", $_SESSION['IDEMPRESA']);
											$stmt -> execute();

											while($piscina = $stmt->fetchObject()){

										?>
										<tr>
										<td><input type="radio" name="r1" class="" onClick="verDetallePiscina(<?php echo $_SESSION['IDEMPRESA']; ?>,<?php echo $piscina->pis_id; ?>);"></td>
										<td><?php echo $piscina->pis_nombre; ?></td>
										</tr>
										<?php
											}

											$db->commit(); 
											$db = null;

											}catch(Exception $e){
												$db->rollback(); 
											}
										?>
									</tbody>
								</table>
							</div>
							<div class="col-md-9" id="detallePiscina" style="display:none">
								<div class="box">
									<div class="box-header">
										<h3 class="box-title">Detalle Piscina</h3>
									</div><!-- /.box-header -->
									<div class="box-body">
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label>Código:</label>
													<input type="text" class="form-control" id="cod_piscina" placeholder="Código" disabled>
												</div>
												<div class="form-group">
													<label>Nombre:</label>
													<input type="text" class="form-control" id="nombre_piscina" placeholder="Nombre" disabled>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label>Área:</label>
													<input type="text" class="form-control" id="area_piscina" placeholder="Área" disabled>
												</div>
													<div class="form-group">
													<label>Volumen:</label>
													<input type="text" class="form-control" id="volumen_piscina" placeholder="Volumen" disabled>
												</div>
											</div>
											<div class="col-md-4">
												<br>
												<img src="img/rectangulo.jpg" class="img-border" width="100%" alt="User Image">
											</div>
											<div class="col-md-12">
												<hr>
												<div style="text-align:center">
													<button class="btn btn-primary btn-sm" onclick="quitarSensorActuador(<?php echo $_SESSION['IDEMPRESA']; ?>); return false;" title="Quitar"><i class="fa fa-angle-double-right"></i></button>
													<button class="btn btn-primary btn-sm" onclick="agregarSensorActuador(<?php echo $_SESSION['IDEMPRESA']; ?>);" title="Agregar"><i class="fa fa-angle-double-left"></i></button>
												</div>
												<br>
											</div>
											<div class="col-md-6">
												<label>Sensores - Actuadores Asignados</label>
												<table id="sensoresAsigandos" class="table table-bordered">
													<thead>
														<tr>
															<th style="width:10px"><i class="fa fa-dashboard"></i></th>
															<th>Nombre</th>
															<th>MAC</th>
															<th>PIN</th>
														</tr>
													</thead>
													<tbody id="cuerpoTablaSensoresAsignados">
													</tbody>
												</table>
											</div>
											<div class="col-md-6">
												<label>Sensores - Actuadores Disponibles</label>
												<table id="sensoresDisponibles" class="table table-bordered">
													<thead>
														<tr>
															<th style="width:10px"><i class="fa fa-dashboard"></i></th>
															<th>Nombre</th>
															<th>MAC</th>
															<th>PIN</th>
														</tr>
													</thead>
													<tbody id="cuerpoTablaSensoresDisponibles">
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="chart tab-pane <?php if($_REQUEST['r']==1)echo 'active'; ?>" id="regla-chart" style="position: relative;">
							<div class="box-body">
								<div class="col-md-4">
									<div class="form-group">
										<label>Piscina:</label>
										<select class="form-control select2 select2-hidden-accessible" name="piscina_regla_id"  id="piscina_regla_id"
								style="width: 100%;" tabindex="-1" aria-hidden="true" onchange="verReglas(); return false;">
										</select>
									</div>
								</div>
								<div class="col-md-offset-5 col-md-3">
									<div class="form-group" align="right">
										<button class="btn btn-primary" onclick="verFormRegla();"><span class="fa fa-file-o"></span> +Nueva Regla</button>
									</div>
								</div>
								<div class="col-md-12">
									<label>Reglas</label>
									<table id="reglasTabla" class="table table-bordered">
										<thead>
											<tr>
												<th style="width:10px"><i class="fa fa-file"></i></th>
												<th>SENSOR</th>
												<th>OPERADOR</th>
												<th>VALOR</th>
												<th>ACTUADOR</th>
												<th>ALERTA</th>
											</tr>
										</thead>
										<tbody id="cuerpoTablaReglas">

										</tbody>
									</table>
								</div>
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section><!-- /.content -->
</div><!-- /.content-wrapper -->


<div class="modal" id="datosPiscina">
	<div class="modal-dialog" >
		<div class="modal-content" >
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title"><i class="fa fa-object-ungroup"></i> <i> Datos de Piscina</i></h4>
			</div>
			<div class="modal-body">
				<form role="form" name="formDatosPiscina">
					<div class="box-body">
						<div class="col-lg-6">
							<div class="form-group">
								<label>Código:</label>
								<input type="text" class="form-control" name="cod_piscina" placeholder="Generado Automáticamente" disabled>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>Nombre:</label>
								<input type="text" class="form-control" name="nombre_piscina" placeholder="Nombre">
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>Área (m2):</label>
								<input type="text" class="form-control" name="area_piscina" placeholder="Área">
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>Volumen (m3):</label>
								<input type="text" class="form-control" name="volumen_piscina" placeholder="Volumen">
							</div>
						</div>
					</div><!-- /.box-body -->
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-remove text-alert"></i> Cancelar</button>
				<button type="button" class="btn btn-primary" id="btnEditar" onclick="editarDatosPiscina(); return false;"><i class="fa fa-save"></i> Guardar Cambios</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal" id="mensajeEliminar">
	<div class="modal-dialog" >
		<div class="modal-content" >
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title"><i class="fa fa-object-ungroup"></i> <i> Eliminar Piscina</i></h4>
			</div>
			<div class="modal-body">
				<input type="hidden" id="codPiscina_eliminar"> 
				<h4>¿Está seguro que desea eliminar esta piscina?</h4>
				<br><i>NOTA: Recuerde que solo se eliminará cuando no existan datos relacionados.</i>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-remove text-alert"></i> Cancelar</button>
				<button type="button" class="btn btn-primary" onclick="eliminarPiscina();"><i class="fa fa-trash-o text-alert"></i> Eliminar</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal" id="formularioRegla">
	<div class="modal-dialog" >
		<div class="modal-content" >
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title"><i class="fa fa-file"></i> <i> Crear Regla</i></h4>
			</div>
			<div class="modal-body">
				<form role="form" name="formNuevaRegla">
					<div class="box-body">
						<div class="col-lg-6">
							<div class="form-group">
								<label>Sensor:</label>
								<select class="form-control select2 select2-hidden-accessible" name="cod_sensor" id="reg_cod_sensor"
								style="width: 100%;" tabindex="-1" aria-hidden="true">
								</select>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>Actuador:</label>
								<select class="form-control select2 select2-hidden-accessible" name="cod_actuador"  id="reg_cod_actuador"
								style="width: 100%;" tabindex="-1" aria-hidden="true">
								</select>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>Operador:</label>
								<select class="form-control select2 select2-hidden-accessible" name="cod_condicion"  id="reg_cod_condicion"
								style="width: 100%;" tabindex="-1" aria-hidden="true">
								</select>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>Valor:</label>
								<input type="text" class="form-control" name="valor_regla" placeholder="Valor">
							</div>
						</div>
						<div class="col-lg-12">
							<div class="form-group">
								<label>Mensaje:</label>
							</div>
						</div>
						<div class="col-md-6">
							<div class="input-group">
								<span class="input-group-addon">
									<input type="radio" name="gMensaje" style="cursor: pointer;" checked 
									onclick="$('#panelNuevoMensaje').show();$('#panelSeleccioneMensaje').hide();">
								</span>
								<input type="text" class="form-control"  disabled value="Nuevo Mensaje">
							</div>
						</div>
						<div class="col-md-6">
							<div class="input-group">
								<span class="input-group-addon">
									<input type="radio" name="gMensaje" id="r_mensaje" style="cursor: pointer;" checked
									onclick="$('#panelNuevoMensaje').hide();$('#panelSeleccioneMensaje').show();">
								</span>
								<input type="text" class="form-control" disabled value="Escoger Mensaje">
							</div>
						</div>
						<div class="col-lg-12" id="panelSeleccioneMensaje">
							<div class="form-group">
								<hr>
								<label>Seleccione mensaje:</label>
								<select class="form-control select2 select2-hidden-accessible" name="cod_alerta"  id="reg_cod_alerta"
								style="width: 100%;" tabindex="-1" aria-hidden="true">
								</select>
							</div>
						</div>
						<div class="col-lg-12" hidden id="panelNuevoMensaje">
							<div class="form-group">
								<hr>
								<label>Nuevo mensaje:</label>
								<input type="text" class="form-control" placeHolder="Nuevo Mensaje" name="mensaje_regla">
							</div>
						</div>
					</div><!-- /.box-body -->
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-remove text-alert"></i> Cancelar</button>
				<button type="button" class="btn btn-primary" onclick="nuevaRegla(); return false;"><i class="fa fa-save"></i> Guardar</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script type="text/javascript">

	//Llenado de Piscinas
	$.ajax({
		url: 'webservice/WService.php/piscina/',
		dataType:'json',
		type:'get',
		async: false,
		crossDomain: true,
		cache: false,
		success: function(data){
			var datos = '';
			$(data).each(function(index, value){
				datos +="<option value='"+value.pis_id+"'>"+value.pis_nombre+"</option>";
			});
			document.getElementById("piscina_regla_id").innerHTML = datos; 
			document.getElementById("piscina_regla_id").selectedIndex=-1;
		}
	});

	function nuevaPiscina(){
		//DATOS SENSOR
		var nombre = document.formNuevaPiscina.nombre_piscina.value;
		var area = document.formNuevaPiscina.area_piscina.value;
		var volumen = document.formNuevaPiscina.volumen_piscina.value;

		if(nombre.trim()==''||area.trim()==''||volumen.trim()==''){
			toastr.options = {"timeOut": "1000"};
			toastr.error('Llene todos los campos','Estado');
		}else{

			ajax = objetoAjax();
			ajax.open("POST", "piscina/ingresar_piscina.php", true);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4) {
					var mensajeRespuesta = ajax.responseText;

					if(mensajeRespuesta == 'BIEN'){
						toastr.options = {"timeOut": "1000"};
						toastr.success('Guardado con éxito','Estado');
						setTimeout(function(){
							window.location.href = 'piscina.php';
						},1000)

					}else{
						toastr.options = {"timeOut": "1000"};
						toastr.error(mensajeRespuesta,'Estado');
					}
				}
			}
			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax.send("nombre_piscina="+nombre+"&area_piscina="+area+"&volumen_piscina="+volumen);

		}
	}

	function mostrarDatosPiscina(codPiscina){
		$.ajax({
			url: 'webservice/WService.php/piscina/'+codPiscina+'',
			dataType:'json',
			type:'get',
			cache:'false',
			success: function(data){
				var datos = '';
				$(data).each(function(index, value){
					document.formDatosPiscina.cod_piscina.value = value.pis_id; 
					document.formDatosPiscina.nombre_piscina.value = value.pis_nombre; 
					document.formDatosPiscina.area_piscina.value = value.pis_area; 
					document.formDatosPiscina.volumen_piscina.value = value.pis_volumen; 
				});
			}
		});
		$('#datosPiscina').modal('show');
	}

	function editarDatosPiscina(){
		//DATOS SENSOR
		var codPiscina = document.formDatosPiscina.cod_piscina.value;
		var nombre = document.formDatosPiscina.nombre_piscina.value;
		var area = document.formDatosPiscina.area_piscina.value;
		var volumen = document.formDatosPiscina.volumen_piscina.value;

		if(nombre.trim()==''||area.trim()==''||volumen.trim()==''){
			toastr.options = {"timeOut": "1000"};
			toastr.error('Llene todos los campos','Estado');
		}else{

			ajax = objetoAjax();
			ajax.open("POST", "piscina/editar_piscina.php", true);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4) {
					var mensajeRespuesta = ajax.responseText;

					if(mensajeRespuesta == 'BIEN'){
						toastr.options = {"timeOut": "1000"};
						toastr.success('Cambios guardados con éxito','Estado');
						setTimeout(function(){
							window.location.href = 'piscina.php?v=1';
						},1000)

					}else{
						toastr.options = {"timeOut": "1000"};
						toastr.error(mensajeRespuesta,'Estado');
					}
				}
			}
			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax.send("cod_piscina="+codPiscina+"&nombre_piscina="+nombre+"&area_piscina="+area+"&volumen_piscina="+volumen);

		}
	}

	function mostrarEliminarMensaje(codPiscina){
		document.getElementById('codPiscina_eliminar').value = codPiscina;

		$('#mensajeEliminar').modal('show');
	}

	function eliminarPiscina(){
		var cod_piscina = document.getElementById('codPiscina_eliminar').value;

		ajax = objetoAjax();
		ajax.open("POST", "piscina/eliminar_piscina.php", true);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				var mensajeRespuesta = ajax.responseText;

				if(mensajeRespuesta == 'BIEN'){
					toastr.options = {"timeOut": "1000"};
					toastr.success('Se ha eliminado con éxito!','Estado');
					setTimeout(function(){
						window.location.href = 'piscina.php?v=1';
					},1000)

				}else{
					toastr.options = {"timeOut": "1000"};
					toastr.error(mensajeRespuesta,'Estado');
				}
			}
		}
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.send("cod_piscina="+cod_piscina);
	}

	function verDetallePiscina(codEmpresa, codPiscina){
		document.getElementById('detallePiscina').style.display="block";

		$.ajax({
		    url: 'webservice/WService.php/piscina/'+codPiscina+'',
		    dataType:'json',
		    type:'get',
		    cache:'false',
		    success: function(data){
		      
		      $(data).each(function(index, value){
		      	document.getElementById('cod_piscina').value = value.pis_id;
		      	document.getElementById('nombre_piscina').value = value.pis_nombre;
		      	document.getElementById('area_piscina').value = value.pis_area;
		      	document.getElementById('volumen_piscina').value = value.pis_volumen;
		      });
		      
			}
		 });

		$.ajax({
			url: 'webservice/WService.php/sensoractuador/piscina/'+codPiscina+'',
			dataType:'json',
			type:'get',
			cache:'false',
			success: function(data){
				var datos = '';
				$(data.sensorespiscina).each(function(index, value){
					datos += '<tr><td><input type="hidden" id="codAsignado'+index+'" value="'+value.mse_id+'"><input type="checkbox" name="c1" id="checkAsignado'+index+'"></td><td>'+value.sen_nombre+'</td><td>'+value.mot_mac+'</td><td>'+value.mse_pin+'</td></tr>';
			  	
				});

				document.getElementById('cuerpoTablaSensoresAsignados').innerHTML = datos; 	
			  
			}
		});


		$.ajax({
			url: 'webservice/WService.php/motesensor/empresa/'+codEmpresa+'',
			dataType:'json',
			type:'get',
			cache:'false',
			success: function(data){
				var datos = '';
				$(data.motesensor).each(function(index, value){
			  		datos += '<tr><td><input type="hidden" id="codDisponible'+index+'" value="'+value.mse_id+'"><input type="checkbox" name="c2" id="checkDisponible'+index+'"></td><td>'+value.sen_nombre+'</td><td>'+value.mot_mac+'</td><td>'+value.mse_pin+'</td></tr>';
			  	
				});

				document.getElementById('cuerpoTablaSensoresDisponibles').innerHTML = datos;
			}
		});
	}

	function agregarSensorActuador(codEmpresa){
		var codPiscina = document.getElementById('cod_piscina').value;

		var table = document.getElementById('sensoresDisponibles');
		var rowCount = table.rows.length;
		console.log(rowCount);

		var listaSensores = [];

		for (var i = 0; i < rowCount-1; i++) {
			if(document.getElementById('checkDisponible'+i).checked){
				listaSensores.push(document.getElementById('codDisponible'+i).value);
			}
		}

		if(listaSensores.length<=0){
			toastr.options = {"timeOut": "1000"};
			toastr.error('No hay sensores-actuadores para agregar','Estado');
		}else{
			ajax = objetoAjax();
		    ajax.open("POST", "piscina/asignar_sensores_actuadores.php", true);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4) {
					var mensajeRespuesta = ajax.responseText;
					if(mensajeRespuesta == 'BIEN'){
						toastr.options = {"timeOut": "1000"};
						toastr.success('Guardado con éxito','Estado');
		          
						$.ajax({
							url: 'webservice/WService.php/sensoractuador/piscina/'+codPiscina+'',
							dataType:'json',
							type:'get',
							cache:'false',
							success: function(data){
								var datos = '';
								$(data.sensorespiscina).each(function(index, value){
									datos += '<tr><td><input type="hidden" id="codAsignado'+index+'" value="'+value.mse_id+'"><input type="checkbox" name="c1" id="checkAsignado'+index+'"></td><td>'+value.sen_nombre+'</td><td>'+value.mot_mac+'</td><td>'+value.mse_pin+'</td></tr>';
								});
								document.getElementById('cuerpoTablaSensoresAsignados').innerHTML = datos;  
							}
						});

						$.ajax({
							url: 'webservice/WService.php/motesensor/empresa/'+codEmpresa+'',
							dataType:'json',
							type:'get',
							cache:'false',
							success: function(data){
								var datos = '';
								$(data.motesensor).each(function(index, value){
									datos += '<tr><td><input type="hidden" id="codDisponible'+index+'" value="'+value.mse_id+'"><input type="checkbox" name="c2" id="checkDisponible'+index+'"></td><td>'+value.sen_nombre+'</td><td>'+value.mot_mac+'</td><td>'+value.mse_pin+'</td></tr>';

								});

								document.getElementById('cuerpoTablaSensoresDisponibles').innerHTML = datos;
							}
						});
		          
					}else{
						toastr.options = {"timeOut": "1000"};
						toastr.error(mensajeRespuesta,'Estado');
					}
				}
			}
			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax.send("&cod_piscina="+codPiscina+"&listaSensores="+listaSensores);
		}
	}

	function quitarSensorActuador(codEmpresa){
		var codPiscina = document.getElementById('cod_piscina').value;
		var table = document.getElementById('sensoresAsigandos');
		var rowCount = table.rows.length;
		console.log(rowCount);
		
		var listaSensores = [];

		for (var i = 0; i < rowCount-1; i++) {
			if(document.getElementById('checkAsignado'+i).checked){
				listaSensores.push(document.getElementById('codAsignado'+i).value);
			}
		}

		if(listaSensores.length<=0){
			toastr.options = {"timeOut": "1000"};
			toastr.error('No hay sensores-actuadores para quitar','Estado');
		}else{
			ajax = objetoAjax();
			ajax.open("POST", "piscina/quitar_sensores_actuadores.php", true);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4) {
					var mensajeRespuesta = ajax.responseText;

					if(mensajeRespuesta == 'BIEN'){
						toastr.options = {"timeOut": "1000"};
						toastr.success('Guardado con éxito','Estado');

						$.ajax({
							url: 'webservice/WService.php/sensoractuador/piscina/'+codPiscina+'',
							dataType:'json',
							type:'get',
							cache:'false',
							success: function(data){
								var datos = '';
								$(data.sensorespiscina).each(function(index, value){
									datos += '<tr><td><input type="hidden" id="codAsignado'+index+'" value="'+value.mse_id+'"><input type="checkbox" name="c1" id="checkAsignado'+index+'"></td><td>'+value.sen_nombre+'</td><td>'+value.mot_mac+'</td><td>'+value.mse_pin+'</td></tr>';
							  	
								});
								document.getElementById('cuerpoTablaSensoresAsignados').innerHTML = datos;
							}
						});
	              
						$.ajax({
							url: 'webservice/WService.php/motesensor/empresa/'+codEmpresa+'',
							dataType:'json',
							type:'get',
							cache:'false',
							success: function(data){
								var datos = '';
								$(data.motesensor).each(function(index, value){
									datos += '<tr><td><input type="hidden" id="codDisponible'+index+'" value="'+value.mse_id+'"><input type="checkbox" name="c2" id="checkDisponible'+index+'"></td><td>'+value.sen_nombre+'</td><td>'+value.mot_mac+'</td><td>'+value.mse_pin+'</td></tr>';
							  	
								});

							document.getElementById('cuerpoTablaSensoresDisponibles').innerHTML = datos;
							
							}
						});
					}else{
						toastr.options = {"timeOut": "1000"};
						toastr.error(mensajeRespuesta,'Estado');
					}
				}
			}
			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax.send("listaSensores="+listaSensores);
		}
	}

	function verFormRegla(){
		document.getElementById('select2-reg_cod_sensor-container').innerHTML = '';
		document.getElementById('select2-reg_cod_actuador-container').innerHTML = ''; 
		document.getElementById('select2-reg_cod_condicion-container').innerHTML = ''; 
		document.getElementById('select2-reg_cod_alerta-container').innerHTML = ''; 
		document.formNuevaRegla.valor_regla.value = '';
		document.formNuevaRegla.mensaje_regla.value = '';

		$.ajax({
			url: 'webservice/WService.php/sensor/',
			dataType:'json',
			type:'get',
			async: false,
			crossDomain: true,
			cache: false,
			success: function(data){
				var datos = '';
				var datos2 = '';
				$(data.sensor).each(function(index, value){
					if(value.tse_id==1)
						datos2 +="<option value='"+value.sen_id+"'>"+value.sen_nombre+"</option>";
					if(value.tse_id==2)
						datos +="<option value='"+value.sen_id+"'>"+value.sen_nombre+"</option>";
				});
				document.formNuevaRegla.cod_sensor.innerHTML = datos; 
				document.formNuevaRegla.cod_sensor.selectedIndex=-1;
				document.formNuevaRegla.cod_actuador.innerHTML = datos2; 
				document.formNuevaRegla.cod_actuador.selectedIndex=-1;
			}
		});

		$.ajax({
			url: 'webservice/WService.php/condicion/',
			dataType:'json',
			type:'get',
			async: false,
			crossDomain: true,
			cache: false,
			success: function(data){
				var datos = '';
				$(data.condicion).each(function(index, value){
					datos +="<option value='"+value.con_id+"'>"+value.con_nombre+" ' "+value.con_operador+" ' "+"</option>";
				});
				document.formNuevaRegla.cod_condicion.innerHTML = datos; 
				document.formNuevaRegla.cod_condicion.selectedIndex=-1;
			}
		});

		$.ajax({
			url: 'webservice/WService.php/alerta/',
			dataType:'json',
			type:'get',
			async: false,
			crossDomain: true,
			cache: false,
			success: function(data){
				var datos = '';
				$(data.alerta).each(function(index, value){
					datos +="<option value='"+value.ale_id+"'>"+value.ale_mensaje+"</option>";
				});
				document.formNuevaRegla.cod_alerta.innerHTML = datos; 
				document.formNuevaRegla.cod_alerta.selectedIndex=-1;
			}
		});


		
		$('#formularioRegla').modal('show');
	}

	function nuevaRegla(){
		var sensor = document.formNuevaRegla.cod_sensor.value;
		var actuador = document.formNuevaRegla.cod_actuador.value;
		var condicion = document.formNuevaRegla.cod_condicion.value;
		var valor = document.formNuevaRegla.valor_regla.value;
		var mensaje = document.formNuevaRegla.mensaje_regla.value;
		var alerta = document.formNuevaRegla.cod_alerta.value;

		if(sensor<1||actuador<1||condicion<1||valor.trim()==''){
			toastr.options = {"timeOut": "1000"};
			toastr.error('Llene todos los campos','Estado');
		}else{
			if(document.getElementById('r_mensaje').checked&&alerta<1){
				toastr.options = {"timeOut": "1000"};
				toastr.error('Escoga mensaje','Estado');
			}else{
				if(!document.getElementById('r_mensaje').checked&&mensaje.trim()==''){
					toastr.options = {"timeOut": "1000"};
					toastr.error('Coloque un nuevo mensaje','Estado');
				}else{
					//GUARDO

					var men = true;
					if(document.getElementById('r_mensaje').checked) men = false;

					ajax = objetoAjax();
					ajax.open("POST", "piscina/ingresar_regla.php", true);
					ajax.onreadystatechange=function() {
						if (ajax.readyState==4) {
							var mensajeRespuesta = ajax.responseText;

							if(mensajeRespuesta == 'BIEN'){
								toastr.options = {"timeOut": "1000"};
								toastr.success('Guardado con éxito!','Estado');
								setTimeout(function(){
									window.location.href = 'piscina.php?r=1';
								},1000)

							}else{
								toastr.options = {"timeOut": "1000"};
								toastr.error(mensajeRespuesta,'Estado');
							}
						}
					}
					ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
					ajax.send("sensor="+sensor+"&actuador="+actuador+"&condicion="+condicion+
						"&valor="+valor+"&alerta="+alerta+"&mensaje="+mensaje+"&men="+men);
					
				}
			}
		}
	}

	function verReglas(){
		var cod_piscina = document.getElementById('piscina_regla_id').value;
		$.ajax({
			url: 'webservice/WService.php/regla/',
			dataType:'json',
			type:'get',
			async: false,
			crossDomain: true,
			cache: false,
			success: function(data){
				var datos = '';
				
				$(data.regla).each(function(index, value){
					datos += "<tr><td><input type='checkbox' id='checkPis_regla_"+value.reg_id+"' onchange='asignar_regla("+value.reg_id+"); return false;'></td><td>"+value.sensor+
					"</td><td><a style='color:black;' title='"+value.con_nombre+"'>"+value.con_operador+"</a></td><td>"+value.reg_valor+"</td><td>"+value.actuador+"</td><td>"+
					value.ale_mensaje+"</td>";
				});
				document.getElementById('cuerpoTablaReglas').innerHTML = datos;
			}
		});

		$.ajax({
			url: 'webservice/WService.php/regla/piscina/'+cod_piscina,
			dataType:'json',
			type:'get',
			async: false,
			crossDomain: true,
			cache: false,
			success: function(data){
				var datos = '';
				
				$(data.regla).each(function(index, value){
					document.getElementById('checkPis_regla_'+value.reg_id).checked = 1;
				});
				
			}
		});
		
	}

	function asignar_regla(cod_regla){
		var cod_piscina = document.getElementById('piscina_regla_id').value;

		if(document.getElementById('checkPis_regla_'+cod_regla).checked){
			//ASIGNAR
			ajax = objetoAjax();
			ajax.open("POST", "piscina/asignar_regla.php", true);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4) {
					var mensajeRespuesta = ajax.responseText;

					if(mensajeRespuesta == 'BIEN'){
						toastr.options = {"timeOut": "1000"};
						toastr.success('Regla asignada!','Estado');
					}else{
						toastr.options = {"timeOut": "1000"};
						toastr.error(mensajeRespuesta,'Estado');
					}
				}
			}
			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax.send("cod_piscina="+cod_piscina+"&cod_regla="+cod_regla);
		}else{
			//QUITAR
			ajax = objetoAjax();
			ajax.open("POST", "piscina/quitar_regla.php", true);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4) {
					var mensajeRespuesta = ajax.responseText;

					if(mensajeRespuesta == 'BIEN'){
						toastr.options = {"timeOut": "1000"};
						toastr.warning('Regla quitada!','Estado');
					}else{
						toastr.options = {"timeOut": "1000"};
						toastr.error(mensajeRespuesta,'Estado');
					}
				}
			}
			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax.send("cod_piscina="+cod_piscina+"&cod_regla="+cod_regla);
		}
	}

</script>


<?php
include('pie.php');
?>