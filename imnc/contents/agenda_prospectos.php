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
					<!--
					<button type="button" class="btn btn-primary" ng-click="limpiarEditor()" id="btnlimpiarEditor" >Limpiar Editor</button>				
			    -->
					<ul class="nav navbar-right panel_toolbox">
						<li>
							<a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
						</li>
			    </ul>
					<div class="clearfix"></div>
				</div>

				<div class="x_content">
					<div class = "row">
						<div class="col-md-6 col-sm-12 col-xs-12 profile_left">
						<form name="form4" id="form4" data-parsley-validate="" class="form-horizontal form-label-left">
							<div class="form-group" style="text-align: left;">
								<label>Prospecto<span class="required">*</span></label>
							  <select ng-model="cmbProspecto" class="select2_single form-control" id="cmbProspecto" 
							  ng-options="prospecto.id as prospecto.nombre for prospecto in Prospectos">
								<option value="" disabled=true>---Seleccione un prospecto---</option>
							  </select>
							</div>
							<div class="form-group" style="text-align: left;">
								<label>Producto<span class="required">*</span></label>
							  <select ng-model="cmbProducto" class=" form-control" id="cmbProducto" ng-change="cambioProspecto()" 
							  ng-options="producto.id as producto.nombre for producto in ProductosL">
								<option value="">---Todos---</option>
							  </select>
							</div>
							</form>
							<div class="col-12">
								<h2>Contactos</h2>

								<table class="table table-striped responsive-utilities">
								<thead>
									<tr class="headings">
										<th class="column-title"><i class="fa fa-map-marker user-profile-icon"></i> Nombre</th>
										<th class="column-title"><i class="fa fa-map-marker user-profile-icon"></i> Ubicaci&oacuten </th>							  
										<th class="column-title"><i class="fa fa-user user-profile-icon"></i> Correo</th>
										<th class="column-title"><i class="fa fa-phone user-profile-icon"></i> Tel&eacutefono </th>  
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
								
							</div>
						</div>

						<div class="col-md-6 col-sm-12 col-xs-12">
						<form name="form3" id="form3" data-parsley-validate="" class="form-horizontal form-label-left">
							<div class="form-group">
								<label>Estatus del prospecto<span class="required">*</span></label>
							  <select ng-model="cmbEstatus" id="cmbEstatus" required="required" 
								class="form-control col-md-7 col-xs-12" data-parsley-id="2324" ng-model="cmbEstatus" 
								ng-change = "cambioPorcentaje()" ng-options="estatus.id as estatus.nombre for estatus in Estatus">
									<option value="">---Seleccione una estatus---</option>
								</select>
							</div>

							<div class="form-group">
								<label>Porcentaje<span class="required">*</span></label>
							  <select ng-model="cmbPorcentaje" id="cmbPorcentaje" required="required" 
								class="form-control col-md-7 col-xs-12" data-parsley-id="2324" ng-model="cmbPorcentaje" 
								ng-change = "cambioPorcentaje()" ng-options="porcentaje.id as porcentaje.nombre for porcentaje in Porcentajes">
									<option value="">---Seleccione una porcentaje---</option>
								</select>
							</div>
							<!--
							<div class="form-group">
								<label>Usuario Principal<span class="required">*</span></label>
							  <select ng-model="usuariosP" disabled="true" class="form-control col-md-7 col-xs-12" data-parsley-id="2324"
								ng-options="usuario.id as usuario.descripcion for usuario in Usuarios">
								</select>
							</div>

							<div class="form-group">
								<label>Usuario secundario<span class="required">*</span></label>
							  <select ng-model="usuariosS" disabled="true" class="form-control col-md-7 col-xs-12" data-parsley-id="2324" 
								ng-options="usuario.id as usuario.descripcion for usuario in Usuarios">
								</select>
							</div>
							-->
							</form>
							
						</div>

						
						<!--
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
						-->
					</div>
					
					<div class = "row">
					<button type="button" class="btn btn-primary" style="float : right;"
							ng-click="mostrarModalEvento()"  
							ng-if="prospectoActual"
							>Agregar nueva tarea</button>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<h2>Historial de tareas del prospecto</h2>							
						</div>
					</div>
					<div class = "row">
						<div class="col-12">
							<table class="table table-striped responsive-utilities">
							<thead>
								<tr class="headings">
									<th class="column-title">Fecha</th>							  
									<th class="column-title">Asunto</th>
									<th class="column-title">Descripci&oacuten</th>  
									<th class="column-title">Estado</th> 
									<th class="column-title"></th>                         
								</tr>
							</thead>
								
							<tbody id="tbodyHistorial">
								<tr ng-repeat="x in Historial">
									<td>{{ x.fecha }}</td>
									<td>{{ x.asunto }}</td>
									<td>{{ x.descripcion }}</td>
									<td>{{ x.estado }}</td>
									<td>
										<button type="button" class="btn btn-primary" ng-click='editarTarea(x.fecha,x.hora_inicio,x.fecha_fin,x.hora_fin,x.tipo_asunto,x.id_producto,x.descripcion,x.id )' ng-if="x.estado=='PENDIENTE'" id="btnEditarTarea" ><i class="fa fa-edit"> </i>
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
																<label class="control-label col-md-4 col-sm-4 col-xs-12">Fecha de Inicio
																</label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																<input type="text" id="fecha_inicio" required="required" class="form-control col-sm-6 col-md-6 col-xs-12" ng-model="fecha_inicio" data-parsley-id="2324" >
																	<ul class="parsley-errors-list" id="parsley-id-2324"></ul>																	
																</div>
															</div>

															<div class="form-group">
																<label class="control-label col-md-4 col-sm-4 col-xs-12">Hora de inicio
																</label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																	<input type="texttext" required="required" ng-model="hora_inicio" class="form-control col-md-5 col-xs-10" id="hora_inicio" data-parsley-id="2324">
																	<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
																	
																</div>
															</div>

															<div class="form-group">
																<label class="control-label col-md-4 col-sm-4 col-xs-12">Fecha Fin
																</label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																<input type="text" id="fecha_fin" required="required" class="form-control col-sm-6 col-md-6 col-xs-12" ng-model="fecha_fin" data-parsley-id="2324" >
																</div>
															</div>

															<div class="form-group">
																<label class="control-label col-md-4 col-sm-4 col-xs-12">Hora Fin
																</label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																	<input type="text" required="required" class="form-control col-md-5 col-xs-10" id="hora_fin" ng-model="hora_fin" data-parsley-id="2324">
																</div>
															</div>

															<div class="form-group">
																<label class="control-label col-md-4 col-sm-4 col-xs-12">Tipo de Asunto
																</label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																	<select ng-model="cmbTipoAsunto" required="required" id="cmbTipoAsunto"
																	class="form-control col-md-6 col-xs-12" data-parsley-id="2324" ng-options="tipoasunto.id as tipoasunto.nombre for tipoasunto in TiposAsunto">
																		<option value="">---Seleccione un tipo de asunto---</option>
																	</select>
																</div>
																<br />
															</div>
															<div class="form-group">
																<label class="control-label col-md-4 col-sm-4 col-xs-12">Producto
																</label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																	<select ng-model="cmbProducto" required="required" id="cmbProducto"
																	class="form-control col-md-6 col-xs-12" data-parsley-id="2324" ng-options="producto.id as producto.nombre for producto in ProductosL">
																		<option value="">---Seleccione un producto---</option>
																	</select>
																</div>
																<br />
															</div>

															<div class="form-group">
																<label class="control-label col-md-4 col-sm-4 col-xs-12">Descripción de la tarea
																</label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																<textarea rows="10" cols="50" name="descripcion" id="descripcion" class="form-control col-md-7 col-xs-12" ng-model="descripcion" data-parsley-id="2324"></textarea>
																</div>
																<br />
															</div>

														</form>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
														<button type="button" class="btn btn-primary" ng-click="guardarTarea()" id="btnGuardarTarea" id_tarea = "" accion = "guardar">Guardar sin cerrar tarea</button>
														<button type="button" class="btn btn-primary" ng-click="cerrarTarea()" id="btnCerrarTarea" estado_tarea="Pendiente">
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