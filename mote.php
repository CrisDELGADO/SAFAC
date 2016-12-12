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
		include('mote/tarea.php');
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
											<input type="text" class="form-control" name="codigo_mote" placeholder="Código Autogenerado" disabled>
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
												<option value="true">Habilitado</option>
												<option value="false">Deshabilitado</option>
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
											<th>MAC</th>
											<th>EMPRESA</th>
											<th>SENSOR-ACTUADOR</th>
											<th>ESTADO</th>
											<th>ACCIONES</th>
										</tr>
									</thead>
									<tbody>
										<?php
										include('conexion.php');
										try{
											pg_query($conn,"BEGIN WORK");
											$sql = "SELECT * FROM mote, empresa WHERE mote.emp_id=empresa.emp_id";
											$resultado = pg_query($conn, $sql);

											while($reg=pg_fetch_assoc($resultado)){
												$sql = "SELECT * FROM mote, mote_sensor_actuador, sensor_actuador WHERE mote.mot_id='".$reg['mot_id']."' AND mote.mot_id=mote_sensor_actuador.mot_id AND mote_sensor_actuador.sen_id=sensor_actuador.sen_id";
												$resultado2 = pg_query($conn, $sql);
												$nombreSensores = '';
												while($reg2=pg_fetch_assoc($resultado2)){
													$nombreSensores .= $reg2['sen_nombre'].', ';
												}

										?>
												<tr>
													<td><?php echo $reg['mot_id'] ?></td>
													<td><?php echo $reg['mot_mac'] ?></td>
													<td><?php echo $reg['emp_nombre'] ?></td>
													<td><?php echo $nombreSensores ?></td>
													<td>
														<input type="checkbox" id="estado_mote<?php echo $reg['mot_id'] ?>" 
														<?php if($reg['mot_estado']=='t')echo 'checked';?> data-toggle="toggle" 
														data-size="small" onchange="cambiarEstadoMote(<?php echo $reg['mot_id'] ?>)">
													</td>
													<td>
														<a onclick="mostrarDatosMote(<?= $reg['mot_id']; ?>);$('#btnEditar').hide();"  title="Ver" style="cursor: pointer;">
															<button class="btn"><i class="fa fa-search"></i></button>
														</a>
														<?php 
															if($editar){
														?>
														<a onclick="mostrarDatosMote(<?= $reg['mot_id']; ?>);$('#btnEditar').show();"  title="Editar" style="cursor: pointer;">
															<button class="btn"><i class="fa fa-edit"></i></button>
														</a>
														<?php
															}
															if($eliminar){
														?>
														<a onclick="mostrarEliminarMensaje(<?= $reg['mot_id']; ?>);"  title="Eliminar" style="cursor: pointer;">
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
				<h4 class="modal-title"><i class="fa fa-plug"></i> <i> Datos de Mote</i></h4>
			</div>
			<div class="modal-body">
				<form role="form" name="formDatosMote">
					<div class="box-body">
						<div class="col-lg-6">
							<div class="form-group">
								<label>Código:</label>
								<input type="text" class="form-control" name="codigo_mote" placeholder="Código Autogenerado" disabled>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>MAC:</label>
								<input type="text" class="form-control" name="mac_mote" placeholder="MAC">
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>Empresa:</label>
								<select class="form-control select2 select2-hidden-accessible" name="empresa_id"  id="empresa_id2" style="width: 100%;" tabindex="-1" aria-hidden="true" onchange="obtenerRUC2();">

								</select>
							</div>
						</div>
						<div class="col-lg-6">
								<div class="form-group">
									<label>RUC (Empresa):</label>
									<input type="text" class="form-control" name="ruc_empresa" id="ruc_empresa2" placeholder="RUC" disabled>
								</div>
							</div>
						<div class="col-lg-6 col-md-offset-6">
							<div class="form-group">
								<label>Estado:</label>
								<select class="form-control" name="estado_mote" style="cursor: pointer;">
									<option value="true">Habilitado</option>
									<option value="false">Deshabilitado</option>
								</select>
							</div>
						</div>
						<h3 class="box-title">Sensor Actuador </h3>
						<div>
							<button class="btn btn-primary btn-xs" title="Agregar Sensor Actuador" onclick="addRow2('tablaSensorActuador2'); return false;">
								<i class="fa fa-plus-circle"> Añadir</i>
							</button>
							<table class="table table-bordered" id="tablaSensorActuador2">
								<tr>
									<th style="width: 10px">Quitar</th>
									<th>Nombre</th>
									<th>Tipo</th>
									<th>Estado</th>
									<th style="width: 20px">PIN</th>
								</tr>
								<tbody id="camposTablaSensor">
									
								</tbody>

							</table>
						</div>

				
					</div><!-- /.box-body -->
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-remove text-alert"></i> Cancelar</button>
				<button type="button" class="btn btn-primary" id="btnEditar" onclick="editarDatosMote(); return false;"><i class="fa fa-save"></i> Guardar Cambios</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal" id="mensajeEliminar">
	<div class="modal-dialog" >
		<div class="modal-content" >
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title"><i class="fa fa-plug"></i> <i> Eliminar Mote</i></h4>
			</div>
			<div class="modal-body">
				<input type="hidden" id="codMote_eliminar"> 
				<h4>¿Está seguro que desea eliminar este mote?</h4>
				<br><i>NOTA: Recuerde que solo se eliminará cuando no existan datos relacionados.</i>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-remove text-alert"></i> Cancelar</button>
				<button type="button" class="btn btn-primary" onclick="eliminarMote();"><i class="fa fa-trash-o text-alert"></i> Eliminar</button>
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
		var codigo = document.formNuevoMote.codigo_mote.value;
		var mac = document.formNuevoMote.mac_mote.value;
		var empresa = document.formNuevoMote.empresa_id.value;
		var estado = document.formNuevoMote.estado_mote.value;


		if(mac.trim()==''||empresa==0){
			toastr.options = {"timeOut": "1000"};
			toastr.error('Ingrese MAC y Empresa','Estado');
		}else{
			var sensores = [];
			var pines = [];
			var estadosMoteSensores = [];
			var exito = false;
			for(var i=1; i<=contadorFilas; i++){
				try{
					var codSensor = document.getElementById('cod_sensor_actuador'+i).value;
					var pinSensor = document.getElementById('pin'+i).value;
					var estadoMoteSensor = false;
					if(document.getElementById('estado_mote_sensor_actuador'+i).checked)estadoMoteSensor=true;

					if(codSensor==0||pinSensor.trim()==''){
						exito = false;
						break;
					}else{
						exito = true;
						sensores.push(codSensor);
						pines.push(pinSensor);
						estadosMoteSensores.push(estadoMoteSensor);
					}

				}catch(error){

				}
			}
			if(!exito){
				toastr.options = {"timeOut": "1000"};
				toastr.error('Agregar Sensor-Actuador con su PIN','Estado');
			}else{
			//GUARDAR

			var objetoMote = new Object();
			objetoMote.mot_id = codigo;
			objetoMote.mot_mac = mac;
			objetoMote.emp_id = empresa;
			objetoMote.mot_estado = estado;
			objetoMote.sensores = sensores;
			objetoMote.pines = pines;
			objetoMote.estados = estadosMoteSensores;


			var jsonMote = JSON.stringify(objetoMote);


			ajax = objetoAjax();
			ajax.open("POST", "webservice/WService.php/mote/", true);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4) {
					var mensajeRespuesta = ajax.responseText;

					if(mensajeRespuesta == 'BIEN'){
						toastr.options = {"timeOut": "1000"};
						toastr.success('Guardado con éxito','Estado');
						setTimeout(function(){
							window.location.href = 'mote.php';
						},1000)

					}else{
						toastr.options = {"timeOut": "1000"};
						toastr.error(mensajeRespuesta,'Estado');
					}
				}
			}
			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax.send(jsonMote);


			}
		}
		
	}

	var contadorFilas2 = 0;

	function mostrarDatosMote(codMote){
		contadorFilas2 = 0;

		var table = document.getElementById('tablaSensorActuador2');
		var rowCount = table.rows.length;
		for(var i=1; i<rowCount; i++) {
			table.deleteRow(1);
		}


		$.ajax({
			url: 'webservice/WService.php/mote/'+codMote+'',
			dataType:'json',
			type:'get',
			cache:'false',
			success: function(data){
				var datos = '';
				$(data).each(function(index, value){
					document.formDatosMote.codigo_mote.value = value.mot_id; 
					document.formDatosMote.mac_mote.value = value.mot_mac; 
					document.formDatosMote.empresa_id.value = value.emp_id; 
					document.getElementById('select2-empresa_id2-container').innerHTML = value.emp_nombre;
					document.formDatosMote.ruc_empresa.value = value.emp_ruc; 
					document.formDatosMote.estado_mote.value = value.mot_estado;
				});

			}
		});

		$.ajax({
			url: 'webservice/WService.php/motesensor/'+codMote+'',
			dataType:'json',
			type:'get',
			cache:'false',
			success: function(data){
				var datos = '';
				document.getElementById("camposTablaSensor").innerHTML = datos; 

				$(data.motesensor).each(function(index, value){
					var checkedEstado = '';
					if(value.mse_estado==true){
						var checkedEstado = 'checked';
					}
					datos = "<tr><td><div><button class='btn btn-primary btn-sm' onclick='deleteRow2("+(index+1)+");' title='Quitar'><i class='fa fa-minus-circle'></i></button></div></td><td><div><input type='hidden' id='idOcultoMoteSensor"+(index+1)+"' value='"+value.mse_id+"'><select class='form-control select2'  id='cod_sensor_actuador2_"+(index+1)+"' style='width: 100%; cursor:pointer;' tabindex='-1' aria-hidden='true' onchange='datosSensorActuador2("+(index+1)+");'></select></div></td><td><div><div id='tipo2_"+(index+1)+"'>"+value.tse_nombre+"</div></div></td><td><div><input type='checkbox' id='estado_mote_sensor_actuador2_"+(index+1)+"' data-toggle='toggle' data-size='small' "+checkedEstado+"></div></td></td><td><div><input type='text' value='"+value.mse_pin+"' id='pin2_"+(index+1)+"' style='width:100%;'></div></td></tr>";
					document.getElementById("camposTablaSensor").innerHTML += datos; 
					contadorFilas2++;

					$.ajax({
						url: 'webservice/WService.php/sensor/',
						dataType:'json',
						type:'get',
						cache:'false',
						success: function(data2){
							var datos2 = '';
							$(data2.sensor).each(function(index2, value2){
								datos2 +="<option value='"+value2.sen_id+"'>"+value2.sen_nombre+"</option>";
							});
							document.getElementById("cod_sensor_actuador2_"+(index+1)).innerHTML = datos2; 
							document.getElementById("cod_sensor_actuador2_"+(index+1)).value=value.sen_id;
						}
					});

				});

			}

		});

		$('#datosMote').modal('show');
	}

	function datosSensorActuador2(rowCount){
		var codSensor = document.getElementById("cod_sensor_actuador2_"+rowCount).value;

		$.ajax({
			url: 'webservice/WService.php/sensor/'+codSensor+'',
			dataType:'json',
			type:'get',
			cache:'false',
			success: function(data){
				var datos = '';
				$(data).each(function(index, value){
					document.getElementById("tipo2_"+rowCount).innerHTML = value.tse_nombre;
				});
			}
		});
	}

	function addRow2(tableID) {
		contadorFilas2++;
		var table = document.getElementById(tableID);
		var rowCount = table.rows.length;
		var row = table.insertRow(rowCount);

		var cell1 = row.insertCell(0);
		var element1 = document.createElement("div");
		//element1.type = "checkbox";
		element1.innerHTML = "<button class='btn btn-primary btn-sm' onclick='deleteRow2("+rowCount+");' title='Quitar'><i class='fa fa-minus-circle'></i></button>";
		cell1.appendChild(element1);

		var cell2 = row.insertCell(1);
		var element2 = document.createElement("div");
		element2.innerHTML = "<select class='form-control select2'  id='cod_sensor_actuador2_"+contadorFilas2+"' style='width: 100%; cursor:pointer;' tabindex='-1' aria-hidden='true' onchange='datosSensorActuador2("+contadorFilas2+");'></select>";
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
				document.getElementById("cod_sensor_actuador2_"+contadorFilas2).innerHTML = datos; 
				document.getElementById("cod_sensor_actuador2_"+contadorFilas2).selectedIndex=-1;
			}
		});


		var cell3 = row.insertCell(2);
		var element3 = document.createElement("div");
		element3.innerHTML = "<div id='tipo2_"+contadorFilas2+"'></div>";
		cell3.appendChild(element3);

		var cell4 = row.insertCell(3);
		var element4 = document.createElement("div");
		element4.innerHTML = "<input type='checkbox' id='estado_mote_sensor_actuador2_"+contadorFilas2+"'>";
		cell4.appendChild(element4);

		var cell5 = row.insertCell(4);
		var element5 = document.createElement("div");
		element5.innerHTML = "<input id='pin2_"+contadorFilas2+"' type='text' style='width:100%';>";
		cell5.appendChild(element5);


	}

	function deleteRow2(rowPo) {
		var table = document.getElementById("tablaSensorActuador2");
		table.deleteRow(rowPo);

		var rowCount = table.rows.length;
		for(var i=0; i<rowCount; i++) {
			var row = table.rows[i];
			var element1 = row.cells[0].childNodes[0];
			element1.innerHTML = "<button class='btn btn-primary btn-sm' onclick='deleteRow2("+i+");' style='cursor: pointer;' title='Quitar'><i class='fa fa-minus-circle'></i></button>";
		}
	}


	function editarDatosMote(){
		//DATOS MOTE
		var codigo = document.formDatosMote.codigo_mote.value;
		var mac = document.formDatosMote.mac_mote.value;
		var empresa = document.formDatosMote.empresa_id.value;
		var estado = document.formDatosMote.estado_mote.value;


		if(mac.trim()==''||empresa==0){
			toastr.options = {"timeOut": "1000"};
			toastr.error('Ingrese MAC y Empresa','Estado');
		}else{
			var sensores = [];
			var pines = [];
			var estadosMoteSensores = [];
			var codigoMoteSensores = [];
			var exito = false;
			for(var i=1; i<=contadorFilas2; i++){
				try{
					var codMoteSensor = document.getElementById('idOcultoMoteSensor'+i).value;
					if(codSensor!=0)codigoMoteSensores.push(codMoteSensor);
				}catch(error2){

				}
				try{
					var codSensor = document.getElementById('cod_sensor_actuador2_'+i).value;
					var pinSensor = document.getElementById('pin2_'+i).value;
					var estadoMoteSensor = 'false';
					if(document.getElementById('estado_mote_sensor_actuador2_'+i).checked)estadoMoteSensor=true;

					if(codSensor==0||pinSensor.trim()==''){
						exito = false;
						break;
					}else{
						exito = true;
						sensores.push(codSensor);
						pines.push(pinSensor);
						estadosMoteSensores.push(estadoMoteSensor);
					}

				}catch(error){

				}
			}
			if(!exito){
				toastr.options = {"timeOut": "1000"};
				toastr.error('Agregar Sensor-Actuador con su PIN','Estado');
			}else{
			//GUARDAR

			var objetoMote = new Object();
			objetoMote.mot_id = codigo;
			objetoMote.mot_mac = mac;
			objetoMote.emp_id = empresa;
			objetoMote.mot_estado = estado;
			objetoMote.sensores = sensores;
			objetoMote.pines = pines;
			objetoMote.estados = estadosMoteSensores;
			objetoMote.codigos = codigoMoteSensores;


			var jsonMote = JSON.stringify(objetoMote);


			ajax = objetoAjax();
			ajax.open("PUT", "webservice/WService.php/mote/"+codigo, true);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4) {
					var mensajeRespuesta = ajax.responseText;

					if(mensajeRespuesta == 'BIEN'){
						toastr.options = {"timeOut": "1000"};
						toastr.success('Cambios guardados con éxito','Estado');
						setTimeout(function(){
							window.location.href = 'mote.php?v=1';
						},1000)

					}else{
						toastr.options = {"timeOut": "1000"};
						toastr.error(mensajeRespuesta,'Estado');
					}
				}
			}
			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax.send(jsonMote);


			}
		}
		
	}

	function cambiarEstadoMote(codMote){
		var estado = false;
		if(document.getElementById('estado_mote'+codMote+'').checked){
			estado = true;
		}

		ajax = objetoAjax();
		ajax.open("POST", "mote/cambiarEstado.php", true);
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
		ajax.send("&codMote="+codMote+"&estadoMote="+estado);

	}

	function mostrarEliminarMensaje(codMote){
		document.getElementById('codMote_eliminar').value = codMote;

		$('#mensajeEliminar').modal('show');
	}

	function eliminarMote(){
		var cod_mote = document.getElementById('codMote_eliminar').value;

		ajax = objetoAjax();
		ajax.open("POST", "mote/eliminar_mote.php", true);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				var mensajeRespuesta = ajax.responseText;

				if(mensajeRespuesta == 'BIEN'){
					toastr.options = {"timeOut": "1000"};
					toastr.success('Se ha eliminado con éxito!','Estado');
					setTimeout(function(){
						window.location.href = 'mote.php?v=1';
					},1000)

				}else{
					toastr.options = {"timeOut": "1000"};
					toastr.error(mensajeRespuesta,'Estado');
				}
			}
		}
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.send("cod_mote="+cod_mote);
	}



</script>


<?php
include('pie.php');
?>