<span ng-controller="agenda_prospectos_controller">
	<link rel="stylesheet" type="text/css" href="css/jquery-ui-themes/jquery-ui.min.css">
	<link rel="stylesheet" type="text/css" href="css/jquery-ui-themes/timepicker.css">
	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script src="js/ngFileUpload/ng-file-upload-shim.min.js"></script>
	<script src="js/ngFileUpload/ng-file-upload.min.js"></script>
	<script type="text/javascript" src="js/datepicker/timepicker.js"></script>
<div class="right_col" role="main">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="page-title">
	            <div class="title_left">
	              <h3>Agenda de Prospectos</h3>
	            </div>
          	</div>
          <div class="clearfix"></div>
        <div class="x_panel">
	        <div class="x_title">
					<h2>Editor de Tareas <small></small></h2>
					<button type="button" class="btn btn-primary" ng-click="limpiarEditor()" id="btnlimpiarEditor" >Limpiar Editor</button>				
			            <ul class="nav navbar-right panel_toolbox">
			            <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
			            </li>
			            </ul>
					<div class="clearfix"></div>
				</div>

			<div class="x_content">
				<div class = "row">
					<div class="col-md-4 col-sm-4 col-xs-4 profile_left">
						<div class="form-group" style="text-align: left;">
							<label>Prospecto<span class="required">*</span></label>
							<div>
							  <select ng-model="form.cmbProspecto" class="select2_single form-control" id="cmbProspecto" 
							  ng-options="prospecto.id as prospecto.nombre for prospecto in Prospectos">
								<option value="" disabled=true>---Seleccione un prospecto---</option>
							  </select>
							</div>
						</div>

						<ul class="list-unstyled user_data">
							<li>
								<div class = "row">
									<div class = "col-md-6 col-sm-6 col-xs-12">
										<label class="control-label">Fecha Inicio<span class="required">*</span></label>
									</div>
									<div class = "col-md-6 col-sm-6 col-xs-12">
										<label class="control-label">Hora Inicio<span class="required">*</span></label>
									</div>
								</div>
								<div class = "row">
									<div class = "col-md-6 col-sm-6 col-xs-12">
										<input type="text" id="fecha_inicio" required="required" class="form-control col-sm-6 col-md-6 col-xs-12" ng-model="fecha_inicio" 
										data-parsley-id="2324" ></input>
									</div>
									<div class = "col-md-6 col-sm-6 col-xs-12">
										<input type="texttext" required="required" ng-model="hora_inicio" class="form-control col-md-5 col-xs-10" id="hora_inicio" data-parsley-id="2324"></input>
										<span id="horainicioerror" class="text-danger"></span>
									</div>
								</div>									
							</li>

							<li>
								<div class = "row">
									<div class = "col-md-6 col-sm-6 col-xs-12">
										<label class="control-label">Fecha Fin<span class="required">*</span></label>
									</div>
									<div class = "col-md-6 col-sm-6 col-xs-12">
										<label class="control-label">Hora Fin<span class="required">*</span></label>
									</div>
								</div>
								<div class = "row">
									<div class = "col-md-6 col-sm-6 col-xs-12">
										<input type="text" id="fecha_fin" required="required" class="form-control col-sm-6 col-md-6 col-xs-12" ng-model="fecha_fin" 
										data-parsley-id="2324" ></input>
										<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
									</div>
									<div class = "col-md-6 col-sm-6 col-xs-12">
										<input type="text" required="required" class="form-control col-md-5 col-xs-10" id="hora_fin" ng-model="hora_fin" data-parsley-id="2324"></input>
										<span id="horainicioerror" class="text-danger"></span>
									</div>
								</div>
							</li>

							<li>
								<div class = "row">
									<div class = "col-md-6 col-sm-6 col-xs-12">
										<label class="control-label">Tipo Asunto<span class="required">*</span></label>
									</div>
								</div>
								<div class = "row">
									<div class = "col-md-12 col-sm-12 col-xs-12">
										<select ng-model="cmbTipoAsunto" required="required" id="cmbTipoAsunto"
										class="form-control col-md-6 col-xs-12" data-parsley-id="2324" ng-options="tipoasunto.id as tipoasunto.nombre for tipoasunto in TiposAsunto">
											<option value="">---Seleccione un tipo de asunto---</option>
										</select>
										<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
										<span id="tipoasuntoerror" class="text-danger"></span>
									</div>
								</div>
							</li>
								
						</ul>
					</div>
					
					<div class="col-md-5 col-sm-5 col-xs-5 table-responsive">
						<h2>Contactos</h2>

						<table class="table table-striped responsive-utilities">
						<thead>
							<tr class="headings">
								<th class="column-title"><i class="fa fa-map-marker user-profile-icon"></i> Nombre</th>
								<th class="column-title"><i class="fa fa-map-marker user-profile-icon"></i> Ubicaci&oacuten</th>							  
								<th class="column-title"><i class="fa fa-user user-profile-icon"></i> Correo</th>
								<th class="column-title"><i class="fa fa-phone user-profile-icon"></i> Tel&eacutefono</th>  
								<th class="column-title"><i class="fa fa-tablet user-profile-icon"></i> Celular</th>  								
							</tr>
						</thead>
						<tbody id="tbodyContactos">
							<tr ng-repeat="x in Contactos">
								<td>{{ x.nombre_contacto }}</td>
								<td>{{ x.ubicacion }}</td>
								<td>{{ x.correo }}</td>
								<td>{{ x.telefono }}</td>
								<td>{{ x.celular }}</td>
							</tr>
						</tbody>

						</table>
						<!--
						<ul class="list-unstyled user_data">
							<li>
								<i class="fa fa-map-marker user-profile-icon"></i> Ubicaci&oacuten: {{estado}}
							</li>
							<li>
								<i class="fa fa-user user-profile-icon"></i> Cargo: {{cargo_contacto}}
							</li>

							<li>
								<i class="fa fa-phone user-profile-icon"></i> Tel&eacutefono: {{tel_fijo}}
							</li>

							<li class="m-top-xs">
								<i class="fa fa-tablet user-profile-icon"></i> Celular: {{tel_celular}}
							</li>
								
						</ul>
						-->
					</div>
					
					<div class="col-md-3 col-sm-3 col-xs-3">
						<h2>Estado del prospecto</h2>

						<ul class="list-unstyled user_data">
							<li>
								<div class = "row">
									<div class = "col-md-6 col-sm-6 col-xs-12">
										<label class="control-label col-md-4 col-sm-4 col-xs-12">Estatus</label>
									</div>
								</div>
								<div class = "row">
									<div class = "col-md-12 col-sm-6 col-xs-12">
										<select ng-model="cmbEstatus" id="cmbEstatus" required="required" 
										class="form-control col-md-7 col-xs-12" data-parsley-id="2324" ng-model="cmbEstatus" 
										ng-change = "cambioPorcentaje()" ng-options="estatus.id as estatus.nombre for estatus in Estatus">
											<option value="">---Seleccione una estatus---</option>
										</select>
										<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
										<span id="tipoasuntoerror" class="text-danger"></span>
									</div>
								</div>
							</li>
							<li>
								<div class = "row">
									<div class = "col-md-6 col-sm-6 col-xs-12">
										<label class="control-label col-md-4 col-sm-4 col-xs-12">Porcentaje</label>
									</div>
								</div>
								<div class = "row">
									<div class = "col-md-12 col-sm-6 col-xs-12">
										<select ng-model="cmbPorcentaje" id="cmbPorcentaje" required="required" 
										class="form-control col-md-7 col-xs-12" data-parsley-id="2324" ng-model="cmbPorcentaje" 
										ng-change = "cambioPorcentaje()" ng-options="porcentaje.id as porcentaje.nombre for porcentaje in Porcentajes">
											<option value="">---Seleccione una porcentaje---</option>
										</select>
										<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
										<span id="tipoasuntoerror" class="text-danger"></span>
									</div>
								</div>
							</li>
							<li>
								<div class = "row">
									<div class = "col-md-6 col-sm-6 col-xs-12">
										<label class="control-label col-md-4 col-sm-4 col-xs-12">Usuario Principal</label>
									</div>
								</div>
								<div class = "row">
									<div class = "col-md-12 col-sm-6 col-xs-12">
										<select ng-model="usuariosP" disabled="true" class="form-control col-md-7 col-xs-12" data-parsley-id="2324" ng-options="usuario.id as usuario.descripcion for usuario in Usuarios">
										</select>
									</div>
								</div>
							</li>
							<li>
								<div class = "row">
									<div class = "col-md-6 col-sm-6 col-xs-12">
										<label class="control-label col-md-4 col-sm-4 col-xs-12">Usuario Secundario</label>
									</div>
								</div>
								<div class = "row">
									<div class = "col-md-12 col-sm-6 col-xs-12">
										<select ng-model="usuariosS" disabled="true" class="form-control col-md-7 col-xs-12" data-parsley-id="2324" ng-options="usuario.id as usuario.descripcion for usuario in Usuarios">
										</select>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
				<div class = "row">
					<div class="col-md-6 col-sm-6 col-xs-12">
						<h2>Descripci&oacuten de la tarea</h2>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-12">
						<h2>Historial de tareas del prospecto</h2>
					</div>
				</div>
				<div class = "row">
					<div class="col-md-6 col-sm-6 col-xs-12">
						<textarea rows="20" cols="50" name="descripcion" id="descripcion" class="form-control col-md-7 col-xs-12" ng-model="descripcion" data-parsley-id="2324"></textarea>
						<button type="button" class="btn btn-primary" ng-click="guardarTarea()" ng-if="mostrar_guardar" id="btnGuardarTarea" id_tarea = "" accion = "guardar">Guardar sin cerrar tarea</button>
						<button type="button" class="btn btn-primary" ng-click="cerrarTarea()" ng-if="mostrar_cerrar" id="btnCerrarTarea" estado_tarea="Pendiente">
						Guardar y Cerrar Tarea</button>
						<p ng-if="mostrar_guardar == false">Tarea cerrada</p>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-12">
						<table class="table table-striped responsive-utilities">
						<thead>
							<tr class="headings">
								<th class="column-title">Fecha</th>							  
								<th class="column-title">Asunto</th>
								<th class="column-title">Descripci&oacuten</th>                          
							</tr>
						</thead>
						
						<tbody id="tbodyHistorial">
							<tr ng-repeat="x in Historial">
								<td>{{ x.fecha }}</td>
								<td>{{ x.asunto }}</td>
								<td>{{ x.descripcion }}</td>
								<td>{{ x.estado }}</td>
								<td>
									<button type="button" class="btn btn-primary" ng-click='editarTarea(x.fecha,x.hora_inicio,x.fecha_fin,x.hora_fin,x.tipo_asunto,x.descripcion )' ng-if="x.estado=='PENDIENTE'" id="btnEditarTarea" ><i class="fa fa-edit"> </i>
									Editar
									</button>
								</td>
							</tr>
						</tbody>

						</table>
					</div>
				</div>
			</div>
		</div>
			<div class="x_panel">
				<div class="x_content">
					<div id="calendario" class="cal1"></div>
										<!-- MODAL-->
										<div class="modal fade" id="modalEvento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
											<div class="modal-dialog" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
														<h4 class="modal-title" id="modalTitulo">Datos del Evento</h4>
													</div>
													<div class="modal-body">
														<form name="form2" id="form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" enctype="multipart/form-data">
															<div class="form-group">
																<label class="control-label col-md-4 col-sm-4 col-xs-12">Asunto
																</label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																	<input type="text" name="asunto" id="asunto" class="form-control col-md-7 col-xs-12" ng-model="form.asunto" readonly>
																	<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
																	
																</div>
															</div>

															<div class="form-group">
																<label class="control-label col-md-4 col-sm-4 col-xs-12">Folio
																</label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																	<input type="text" name="folio" id="folio" class="form-control col-md-7 col-xs-12" 
																	ng-model="form.folio" readonly>
																	<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
																	
																</div>
															</div>

															<div class="form-group">
																<label class="control-label col-md-4 col-sm-4 col-xs-12">Tipo Asunto
																</label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																	<select ng-model="form.tipo_asunto" required="required" 
																	class="form-control col-md-7 col-xs-12" disabled="true" ng-options="asu.id_tipo_asunto as asu.descripcion for asu in Asuntos">
																		<option value="">---Seleccione un Asunto---</option>
																	</select>
																</div>
															</div>

															<div class="form-group" ng-if="Usuario.permisos == 'admin'">
																<label class="control-label col-md-4 col-sm-4 col-xs-12">Asignar Usuario
																</label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																	<select ng-model="form.usuario_asignado" required="required" 
																	class="form-control col-md-7 col-xs-12" disabled="true" 
																	ng-options="user.id_usuarios as user.nombre for user in Lista_Usuarios">
																		<option value="">---Seleccione un Usuario---</option>
																	</select>
																</div>
															</div>

															<div class="form-group">
																<label class="control-label col-md-4 col-sm-4 col-xs-12">Factibilidad
																</label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																	<select ng-model="form.porcentaje" required="required" 
																	class="form-control col-md-7 col-xs-12" disabled="true"
																	ng-change = "des_porcentaje = form.porcentaje.descripcion"
																	ng-options="per as per.porcentaje for per in Porcentajes track by per.id_porcentaje">
																		<option value="">---Seleccione un porcentaje---</option>
																	</select>
																</div>
																<br />
															</div>
															<div class="form-group">
																<label class="control-label col-md-4 col-sm-4 col-xs-12">Descripcion de Factabilidad</label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																	<input type="text" class="form-control col-md-7 col-xs-12" ng-model="des_porcentaje" readonly>
																</div>
															</div>

															<div class="form-group">
																<label class="control-label col-md-4 col-sm-4 col-xs-12">Estado de Propuesta
																</label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																	<select ng-model="form.propuesta_estado" required="required" 
																	class="form-control col-md-7 col-xs-12" disabled="true" ng-options="est.id_estado as est.estado for est in PropuestasEstados">
																		<option value="">---Seleccione un estado---</option>
																	</select>
																</div>
															</div>

															<div id="fechas">
																<div class="form-group">
																<label class="control-label col-md-4 col-sm-4 col-xs-12">Fecha Inicio: </label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																	<input type="text" readonly ng-model="form.fecha_inicio" 
																	class="form-control col-md-5 col-xs-10">
																</div>
																</div>
																<div class="form-group">
																<label class="control-label col-md-4 col-sm-4 col-xs-12">Fecha Fin: </label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																	<input type="text" readonly ng-model="form.fecha_fin" 
																	class="form-control col-md-5 col-xs-10">
																</div>
																</div>
															</div>

															<div class="form-group">
																<label class="control-label col-md-4 col-sm-4 col-xs-12">Observaciones
																</label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																	<textarea rows="4" cols="50" name="observaciones" id="observaciones" class="form-control col-md-7 col-xs-12" ng-model="form.observaciones" readonly>
																	</textarea>
																	<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
																	
																</div>
															</div>

											                <div id="up_files" class="form-group">
											                	<label class="col-md-12 col-sm-12 col-xs-12">Archivos Subidos: </label>
											                	<div  ng-repeat="a in form.archivos">
											                    <div class="col-md-12 col-sm-12 col-xs-12">
											                    	<a href="{{file_url}}?entidad=1&codigo={{a.id_encriptado}}" target="_blank">  {{a.nombre_archivo}} </a>
											                    </div>
											                    </div>
											                </div>

														</form>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
													</div>
												</div>
											</div>
										</div>
									<!-- /////////////////////////FIN CALENDARIO ////////////////////////////-->
								</div>
							</div>
						</div>
					</div>
				</div>

</span>
<script type="text/javascript" src="js/notify.js"></script>