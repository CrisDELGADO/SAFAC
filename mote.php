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
		<h1>Mote<small>Sección Motes o Dispositivos</small></h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-plug"></i> Mote</a></li>
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
						
						<li class="pull-left header"><i class="fa fa-plug"></i> Mote</li>
					</ul>
					<div class="tab-content no-padding">
						<div class="chart tab-pane <?php if($_REQUEST['v']!=1)echo 'active'; ?> <?php if(!$crear) {echo 'hidden';} ?>" id="crear-chart" style="position: relative;">
							<form role="form" name="formNuevoMote">
								<div class="box-body">
									<div class="col-lg-4">
										<img src="img/arduino.png" width="100%" alt="User Image">
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label>Código:</label>
											<input type="text" class="form-control" name="codigo_moteo" placeholder="Código Autogenerado" disabled>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label>MAC:</label>
											<input type="text" class="form-control" name="mac_mote" placeholder="MAC">
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label>Empresa:</label>
											<select class="form-control select2 select2-hidden-accessible" name="empresa_id"  id="empresa_id" style="width: 100%;" tabindex="-1" aria-hidden="true" onchange="obtenerRUC();">

											</select>
										</div>
									</div>
									<div class="col-lg-4">
											<div class="form-group">
												<label>RUC (Empresa):</label>
												<input type="text" class="form-control" name="ruc_empresa" id="ruc_empresa" placeholder="RUC" disabled>
											</div>
										</div>
									<div class="col-lg-4 col-md-offset-4">
										<div class="form-group">
											<label>Estado:</label>
											<select class="form-control" name="estado_mote" style="cursor: pointer;">
												<option value="True">Habilitado</option>
												<option value="False">Deshabilitado</option>
											</select>
										</div>
									</div>
								</div><!-- /.box-body -->
							</form>
							<div class="box">
								<div class="box-header with-border">
									<h3 class="box-title">Sensor Actuador </h3>
									<div>
										<button class="btn btn-primary btn-xs" title="Agregar Sensor Actuador" onclick="addRow('tablaSensorActuador')">
											<i class="fa fa-plus-circle"> Añadir</i>
										</button>
									</div>
								</div><!-- /.box-header -->
								<div class="box-body">
									<table class="table table-bordered" id="tablaSensorActuador">
										<tr>
											<th style="width: 10px">Quitar</th>
											<th>Nombre</th>
											<th>Descripción</th>
											<th>Tipo</th>
											<th>Unidad Medida</th>
											<th>Estado</th>
											<th style="width: 20px">PIN</th>
										</tr>
									</table>
								</div><!-- /.box-body -->
							</div><!-- /.box -->
							<div style="text-align:center">
								<button class="btn btn-primary btn-lg" onclick="nuevoMote(); return false;"><i class="fa fa-save"></i> Guardar</button>
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


<div class="modal" id="datosMote">
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


<script type="text/javascript">
	
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

	var contadorFilas = 0;

	function addRow(tableID) {
		contadorFilas++;
		var table = document.getElementById(tableID);
		var rowCount = table.rows.length;
		var row = table.insertRow(rowCount);

		var cell1 = row.insertCell(0);
		var element1 = document.createElement("div");
		//element1.type = "checkbox";
		element1.innerHTML = "<button class='btn btn-primary btn-sm' onclick='deleteRow("+rowCount+");' title='Quitar'><i class='fa fa-minus-circle'></i></button>";
		cell1.appendChild(element1);

		var cell2 = row.insertCell(1);
		var element2 = document.createElement("div");
		element2.innerHTML = "<select class='form-control select2'  id='cod_sensor_actuador"+contadorFilas+"' style='width: 100%; cursor:pointer;' tabindex='-1' aria-hidden='true' onchange='datosSensorActuador("+contadorFilas+");'></select>";
		cell2.appendChild(element2);

		$.ajax({
			url: 'webservice/WService.php/sensor/',
			dataType:'json',
			type:'get',
			cache:'false',
			success: function(data){
				var datos = '';
				$(data.sensor).each(function(index, value){
					datos +="<option value='"+value.sen_id+"'>"+value.sen_nombre+"</option>";
				});
				document.getElementById("cod_sensor_actuador"+contadorFilas).innerHTML = datos; 
				document.getElementById("cod_sensor_actuador"+contadorFilas).selectedIndex=-1;
			}
		});



		var cell3 = row.insertCell(2);
		var element3 = document.createElement("div");
		element3.innerHTML = "<div id='descripcion"+contadorFilas+"'></div>";
		cell3.appendChild(element3);

		var cell4 = row.insertCell(3);
		var element4 = document.createElement("div");
		element4.innerHTML = "<div id='tipo"+contadorFilas+"'></div>";
		cell4.appendChild(element4);

		var cell5 = row.insertCell(4);
		var element5 = document.createElement("div");
		element5.innerHTML = "<div id='unidad"+contadorFilas+"'></div>";
		cell5.appendChild(element5);

		var cell6 = row.insertCell(5);
		var element6 = document.createElement("div");
		element6.innerHTML = "<input type='checkbox' id='estado_mote_sensor_actuador"+contadorFilas+"' checked data-toggle='toggle' data-size='small' onchange='cambiarEstadoMoteSensor()'>";
		cell6.appendChild(element6);

		var cell7 = row.insertCell(6);
		var element7 = document.createElement("div");
		element7.innerHTML = "<input id='pin"+contadorFilas+"' type='text' style='width:100%';>";
		cell7.appendChild(element7);


	}

	function deleteRow(rowPo) {
		var table = document.getElementById("tablaSensorActuador");
		table.deleteRow(rowPo);

		var rowCount = table.rows.length;
		for(var i=0; i<rowCount; i++) {
			var row = table.rows[i];
			var element1 = row.cells[0].childNodes[0];
			element1.innerHTML = "<button class='btn btn-primary btn-sm' onclick='deleteRow("+i+");' style='cursor: pointer;' title='Quitar'><i class='fa fa-minus-circle'></i></button>";
		}
	}

	function datosSensorActuador(rowCount){
		var codSensor = document.getElementById("cod_sensor_actuador"+rowCount).value;

		$.ajax({
			url: 'webservice/WService.php/sensor/'+codSensor+'',
			dataType:'json',
			type:'get',
			cache:'false',
			success: function(data){
				var datos = '';
				$(data).each(function(index, value){
					document.getElementById("descripcion"+rowCount).innerHTML = value.sen_descripcion;
					document.getElementById("tipo"+rowCount).innerHTML = value.tse_nombre;
					document.getElementById("unidad"+rowCount).innerHTML = value.sen_medida;
				});
			}
		});
	}




	function nuevoMote(){
		//DATOS MOTE
			
		
	}

	function mostrarDatosMote(codMote){
		
		

		$('#datosMote').modal('show');
	}

	function editarDatosMote(){

	}



</script>


<?php
include('pie.php');
?>