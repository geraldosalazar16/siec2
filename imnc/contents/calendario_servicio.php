<span ng-controller="calendario_servicios_controller">
	<link rel="stylesheet" type="text/css" href="css/jquery-ui-themes/jquery-ui.min.css">
	<link rel="stylesheet" type="text/css" href="css/jquery-ui-themes/timepicker.css">
	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script src="js/ngFileUpload/ng-file-upload-shim.min.js"></script>
	<script src="js/ngFileUpload/ng-file-upload.min.js"></script>
	<script type="text/javascript" src="js/datepicker/timepicker.js"></script>
<div class="right_col" role="main">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_title">
					<h2>Planificador de actividades</small></h2>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">

					<div class="col-md-3 col-sm-3 col-xs-12 profile_left">
						<h3>{{nombre_cliente}}</h3>

						<ul class="list-unstyled user_data">
							
							<li>
								<i class="fa fa-briefcase user-profile-icon"></i> {{tipo_servicio}}
							</li>

							
						</ul>
						<br/>
					</div>
					<div class="col-md-9 col-sm-9 col-xs-12">
                    	<!-- ///////////////////////// CALENDARIO //////////////////////////////-->
									<script type="text/javascript" src="controllers/cita_calendario_servicio.js"></script>
									<div ng-controller="cita_calendario_servicio_controller">
										<div id="calendario" class="cal1"></div>


										<!-- MODAL CREATE-->
										<div class="modal fade" id="modalCreateEvento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
											<div class="modal-dialog" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
														<h4 class="modal-title" id="modalTitulo">Nueva tarea</h4>
													</div>
													<div class="modal-body">
														<form name="form2" id="form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" enctype="multipart/form-data">
															
															<div class="form-group">
																<label class="control-label col-md-4 col-sm-4 col-xs-12">Tarea</label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																<select ng-model="form.cmbTarea" ng-disabled="editar_tipo_tarea === false" required="required" 
																	class="form-control col-md-7 col-xs-12" data-parsley-id="2324" ng-options="tarea.id_tarea as tarea.nombre_tarea for tarea in Tareas">
																		<option value="">---Seleccione una Tarea---</option>
																	</select>
																	
																	<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
																	<span id="tareaerror" class="text-danger"></span>
																</div>
															</div>
															
															<div class="form-group" id="FI">
																<label class="control-label col-md-4 col-sm-4 col-xs-12">Fecha Inicio
																<span class="required">*</span></label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																	<input type="text" required="required" ng-model="form.fecha_inicio" class="form-control col-md-5 col-xs-10" id="fecha_inicio" data-parsley-id="2324">
																	<span id="fechainicioerror" class="text-danger"></span>
																</div>
															</div>

															<div class="form-group" id="HI">
																<label class="control-label col-md-4 col-sm-4 col-xs-12">Hora Inicio
																<span class="required">*</span></label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																	<input type="texttext" required="required" ng-model="form.hora_inicio" class="form-control col-md-5 col-xs-10" id="hora_inicio" data-parsley-id="2324">
																	<span id="horainicioerror" class="text-danger"></span>
																</div>
															</div>
															
															<div class="form-group" id="FF">
																<label class="control-label col-md-4 col-sm-4 col-xs-12">Fecha Fin
																<span class="required">*</span></label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																	<input type="text" required="required" ng-model="form.fecha_fin" class="form-control col-md-5 col-xs-10" id="fecha_fin" data-parsley-id="2324">
																	<span id="fechafinerror" class="text-danger"></span>
																</div>
															</div>

															<div class="form-group" id="HF">
																<label class="control-label col-md-4 col-sm-4 col-xs-12">Hora Fin
																<span class="required">*</span></label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																	<input type="text" required="required" ng-model="form.hora_fin" class="form-control col-md-5 col-xs-10" id="hora_fin" data-parsley-id="2324">
																	<span id="horafinerror" class="text-danger"></span>
																</div>
															</div>
												
															<div class="form-group">
																<label class="control-label col-md-4 col-sm-4 col-xs-12" ng-if="editar_descripcion_tarea">{{desc_modificacion_tarea}}
																</label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																	<textarea rows="4" cols="50" name="observaciones" id="observaciones" class="form-control col-md-7 col-xs-12" ng-if="editar_descripcion_tarea" ng-model="form.observaciones" data-parsley-id="2324">
																	</textarea>
																	<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
																	<span id="observacioneserror" class="text-danger"></span>
																</div>
															</div>
															<!--
															<div class="form-group">
											                	<label class="control-label col-md-4 col-sm-4 col-xs-12">Archivo: </label>
											                    <div class="col-md-6 col-sm-6 col-xs-12">
											                      <div class="btn btn-primary" ngf-select="getFileName(form.FILE)" ng-model="form.FILE"
											                        ngf-accept="'.pdf'" >Agregar</div>
											                      <span id="archivo-error" class="text-danger" ></span>
											                    </div>
											                </div>

											                <div class="form-group" ng-repeat="C in form.CITA_ARCHIVOS">
											                    <div class="col-md-12 col-sm-12 col-xs-12">
											                    	<span> {{C.name}} </span>
											                    	<button type="button" class="close" ng-click="delFileName(C.name)">
											                    	<span aria-hidden="true">&times;</span></button>
											                    </div>
											                </div>

											                <div id="up_files" class="form-group" ng-if="accion=='editar'">
											                	<label class="col-md-12 col-sm-12 col-xs-12">Archivos Subidos: </label>
											                	<div  ng-repeat="a in form.archivos">
											                    <div class="col-md-12 col-sm-12 col-xs-12">
											                    	<a href="{{file_url}}?entidad=1&codigo={{a.id_encriptado}}" target="_blank">  {{a.nombre_archivo}} </a>
											                    </div>
											                    </div>
											                </div>
															-->
														</form>
													</div>
													<div class="modal-footer">
												
														<a type="button" class="btn btn-primary" id="hist-button"
														href="./?pagina=prospecto_cita_historial&id_cita={{form.id_calendario}}">Historial</a>
														<button type="button" class="btn btn-default" ng-click="cerrar()">Cerrar</button>
														<button type="button" class="btn btn-primary" ng-click="guardar()" id="btnGuardar">Guardar</button>
													</div>
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
</div>
<!-- Modal insertar/actualizar de contacto-->
<div class="modal fade" id="modalInsertarActualizarContacto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="modalTituloContactoProspecto">Insertar/actualizar</h4>
			</div>
			<div class="modal-body">
				<form name="form2" id="form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Nombre:<span class="required">*</span>
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control col-md-7 col-xs-12" ng-model="nombre_contacto">
							<span id="nombreerror" class="text-danger"></span>
						</div>
					</div>

					 <div class="form-group">
		              <label class="control-label col-md-4 col-sm-4 col-xs-12">Domicilio del Contacto<span class="required">*</span>
		              </label>
		              <div class="col-md-6 col-sm-6 col-xs-12">
		                <select class="form-control col-md-7 col-xs-12" id="domicilioContacto" ng-model="domicilioContacto"
		                	ng-options="dom.ID as dom.NOMBRE for dom in listaDomicilios">
							<option value="">---Seleccione un domicilio---</option>
		                </select>
		              </div>
		            </div>

					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Correo:<span class="required">*</span>
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="correo">
							<span id="correoerror" class="text-danger"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Telefono:<span class="required">*</span>
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="tel" required="required" class="form-control col-md-7 col-xs-12" ng-model="telefono">
							<span id="telefonoerror" class="text-danger"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Celular:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="tel" required="required" class="form-control col-md-7 col-xs-12" ng-model="celular">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Puesto:<span class="required">*</span>
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="puesto">
							<span id="puestoerror" class="text-danger"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Datos Adicionales
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<textarea rows="4" cols="50" name="datos_adicionales" id="datos_adicionales" class="form-control col-md-7 col-xs-12" 
							ng-model="datos_adicionales"> </textarea>
						</div>
					</div>
				<div class="form-group">
					<label class="control-label col-md-4 col-sm-4 col-xs-12">ACTIVO:<span class="required">*</span>
              		</label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                           <input type="checkbox" class="form-control col-md-7 col-xs-12 selector noshadow" ng-model="contactohabilitado" data-parsley-id="2324" >
                    </div>
                    </div>

				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary" ng-click="guardarContacto()" id="btnGuardarContacto">Guardar</button>
			</div>
		</div>
	</div>
</div>
<!-- Modal insertar/actualizar de Domicilio-->
<div class="modal fade" id="modalInsertarActualizarDomicilio" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="modalTitulo2">Insertar/Actualizar</h4>
			</div>
			<div class="modal-body">
				<form name="form2" id="form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Nombre:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control col-md-7 col-xs-12" ng-model="nombre_domicilio">
							<span id="nombreerror" class="text-danger"></span>
						</div>
					</div>


            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">País<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
              	<select class="select2_single form-control col-md-7 col-xs-12" id="autocompletePais">

                </select>
                <!--<input  type="text" class="form-control col-md-7 col-xs-12" ng-model="pais" id="autocompletePais"></input>-->
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Código Postal<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
              	<input type="text" id="txtCP" required="required" ng-model="codigo_postal" class="form-control col-md-7 col-xs-12">
                <select class="select2_single form-control col-md-7 col-xs-12" id="autocompleteCP" style="display: none;">
                </select>
              </div>
            </div>

            <div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Calle:<span class="required">*</span>
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="calle">
						    <span id="calleerror" class="text-danger"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Numero Exterior:<span class="required">*</span>
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="numero_exterior">
						    <span id="numero_exteriorerror" class="text-danger"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Numero interior:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="numero_interior">
						</div>
					</div>


	 		<div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Colonia<span class="required">*</span>
              	<div style="float: right;" id="campoChkCol"><input type="checkbox" ng-model="chkColonia" ng-change="colonia_checkbox()" id="chkColonia">
               		<span style="font-size: 11px;">No encuentra colonia</span>
               	</div>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12" id="campoSelectColonia">
              	<input type="text" id="txtColonia" required="required" ng-model="colonia" class="form-control col-md-7 col-xs-12">
                <select class="select2_single form-control col-md-7 col-xs-12" id="autocompleteColonia" style="display: none;">

                </select>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12" id="auxColonia" hidden>
              	<input type="text" required="required" class="form-control col-md-7 col-xs-12" readonly>
              </div>
            </div>
			
			<div class="form-group" id="campoNuevaColonia" hidden>
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Nueva Colonia<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
              	<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="colonia">
              </div>
            </div>	

			<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Estado:<span class="required">*</span>
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="estado" id="txtEstado">
						    <span id="estadoerror" class="text-danger"></span>
						</div>
			</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Municipio:<span class="required">*</span>
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="municipio" id="txtMunicipio">
						    <span id="municipioerror" class="text-danger"></span>
						</div>
					</div>
				
					
					 <div class="form-group">
                         <label class="control-label col-md-4 col-sm-4 col-xs-12">Domicilio para Facturación Fiscal:<span class="required">*</span>
              </label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                           <input type="checkbox" class="form-control col-md-7 col-xs-12 selector noshadow" ng-model="fiscalhabilitado" data-parsley-id="2324" >
                    </div>
                    </div>
                    <div class="form-group">
                         <label class="control-label col-md-4 col-sm-4 col-xs-12">¿Oficina central?<span class="required">*</span>
              </label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                           <input type="checkbox" class="form-control col-md-7 col-xs-12 selector noshadow" ng-model="domiciliohabilitado" data-parsley-id="2324" >
                    </div>
                    </div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" ng-click="cerrarDomicilio()">Cerrar</button>
				<button type="button" class="btn btn-primary" ng-click="guardarDomicilio()" id="btnGuardarDomicilio">Guardar</button>
			</div>
		</div>
	</div>
</div>
<!-- Modal Detalles Contacto -->
<div class="modal fade" id="modalDetallesContacto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="modalTitulo">Detalles</h4>
			</div>
			<div class="modal-body">
				<form name="form2" id="form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Nombre:
              			</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="nombre_contacto" data-parsley-id="2324" readonly="false">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Nombre del Domicilio:
              			</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="nombre_domicilioContacto" readonly="false">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Correo:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="correo" data-parsley-id="2324" readonly="false">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Telefono:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="telefono" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Celular:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="celular" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Puesto:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="puesto" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Datos Adicionales
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<textarea rows="4" cols="50" name="datos_adicionales" id="datos_adicionales" class="form-control col-md-7 col-xs-12" 
							ng-model="datos_adicionales" readonly> </textarea>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Id Usuario Registro:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="usuarioReg" required="required" class="form-control col-md-7 col-xs-12" ng-model="id_usuario_creacion_contacto" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Id Usuario Modifico:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="id_usuario_modificacion_contacto" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Fecha Registro:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="fecha_creacion_contacto" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Fecha Modifico:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="fechaMod" required="required" class="form-control col-md-7 col-xs-12" ng-model="fecha_modificacion_contacto" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
					<label class="control-label col-md-4 col-sm-4 col-xs-12">ACTIVO:<span class="required">*</span>
              </label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                           <input type="checkbox" class="form-control col-md-7 col-xs-12 selector noshadow" ng-model="contactohabilitado" disabled="disabled" data-parsley-id="2324" >
                    </div>
                    </div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default">Cerrar</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal Detalles Domicilio-->
<div class="modal fade" id="modalDetallesDomicilio" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="modalTitulo">Detalles</h4>
			</div>
			<div class="modal-body">
				<form name="form2" id="form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Nombre:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="nombre_domicilio" data-parsley-id="2324" readonly="false">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Pais
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="pais" data-parsley-id="2324" readonly="false">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Estado:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="estado" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Municipio:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="municipio" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Colonia:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="colonia" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Codigo postal:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="codigo_postal" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Colonia:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="colonia" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Numero interior:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="numero_interior" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Numero exterior:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="numero_exterior" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					 
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Id Usuario Registro:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="usuarioMod" required="required" class="form-control col-md-7 col-xs-12" ng-model="id_usuario_creacion_domicilio" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Id Usuario modificacion:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="id_usuario_modificacion_domicilio" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Fecha Registro:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="fecha_creacion_domicilio" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Fecha modificacion:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="fechaMod" required="required" class="form-control col-md-7 col-xs-12" ng-model="fecha_modificacion_domicilio" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
                         <label class="control-label col-md-4 col-sm-4 col-xs-12">Fiscal:<span class="required">*</span>
              </label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                           <input type="checkbox" class="form-control col-md-7 col-xs-12 selector noshadow" ng-model="fiscalhabilitado" disabled="disabled" data-parsley-id="2324" ><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
                    </div>
                    </div>
                    <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12">¿Oficina central?:<span class="required">*</span>
              </label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                           <input type="checkbox" class="form-control col-md-7 col-xs-12 selector noshadow" ng-model="domiciliohabilitado" disabled="disabled" data-parsley-id="2324" >
                    </div>
                    </div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default">Cerrar</button>
			</div>
		</div>
	</div>
</div>
<!-- Modal Detalles Cliente-->
<div class="modal fade" id="modalInsertarActualizarCliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="modalTituloCliente">Insertar/Actualizar</h4>
			</div>
			<div class="modal-body">
				<form id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Nombre <span class="required">*</span>
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="txtNombre" required="required" class="form-control col-md-7 col-xs-12">
							<input type="hidden" id="id_prospecto" value="">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtRfc">RFC  <span class="required">*</span>
							<div style="float: right;"><input type="checkbox" id="chkRfc"> <span style="font-size: 11px;">No tiene RFC</span></div>
              			</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="txtRfc" data-inputmask="'mask': '<?php echo $str_mascara_rfc; ?> '" placeholder="PELJ900412XXX" name="txtRfc" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Es facturatario <span class="required">*</span>
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<select class="form-control" id="cmbEsFacturario">
								<option value="" selected disabled>-- elige una opción --</option>
								<option value="S">si</option>
								<option value="N">no</option>
							</select>
							<!-- <input type="text" id="txtEs_Fac" placeholder="S" name="txtEs_Fac" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324"><ul class="parsley-errors-list" id="parsley-id-2324"></ul> -->
						</div>
					</div>
					
					
					
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Cliente facturario<span class="required">*</span>
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="txtClienteFacturario" required="required" class="form-control" disabled>
						</div>
					</div>
					
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">RFC del facturario<span class="required">*</span>
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="txtRFCFac"  data-inputmask="'mask': '<?php echo $str_mascara_rfc; ?>'" required="required"  class="form-control" disabled>
							</div>
					</div>
					
					
					
					
					
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12" for="cmbTPersona">Tipo de persona  <span class="required">*</span>
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<select class="form-control" id="cmbTPersona">
								<option value="elige" selected disabled>-elige una opción-</option>
							</select>
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12" for="cmbTEntidad">Tipo de entidad  <span class="required">*</span>
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<select class="form-control" id="cmbTEntidad">
								<option value="elige" selected disabled>-elige una opción-</option>
							</select>
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary" id="btnGuardarCliente">Guardar</button>
			</div>
		</div>
	</div>
</div>
<!-- Modal Detalles-->
<div class="modal fade" id="modalDetallesProspecto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="modalTituloProspecto">Detalles</h4>
			</div>
			<div class="modal-body">
				<form name="form2" id="form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Nombre:<span class="required">*</span>
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="rfc" required="required" class="form-control col-md-7 col-xs-12" ng-model="nombre" data-parsley-id="2324" readonly="false">
						</div>
					</div>

				<div class="form-group">
	              <label class="control-label col-md-4 col-sm-4 col-xs-12">Origen
	              </label>
	              <div class="col-md-6 col-sm-6 col-xs-12">
	                <input type="text" id="origen-det" required="required" class="form-control col-md-7 col-xs-12" ng-model="nombre_origen" data-parsley-id="2324" readonly="false" ><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
	              </div>
	            </div>
	            
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">RFC
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="rfc" required="required" class="form-control col-md-7 col-xs-12" ng-model="rfc" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Giro:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="giro" required="required" class="form-control col-md-7 col-xs-12" ng-model="giro" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Comentario:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="comentario" required="required" class="form-control col-md-7 col-xs-12" ng-model="comentario" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Id Usuario Registro:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="usarioReg" required="required" class="form-control col-md-7 col-xs-12" ng-model="id_usuario_creacion" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Id Usuario modificacion:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="usuarioMod" required="required" class="form-control col-md-7 col-xs-12" ng-model="id_usuario_modificacion" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Fecha Registro:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="fechaReg" required="required" class="form-control col-md-7 col-xs-12" ng-model="fecha_creacion" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Fecha modificacion:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="fechaMod" required="required" class="form-control col-md-7 col-xs-12" ng-model="fecha_modificacion" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Habilitado<span class="required">*</span>
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="checkbox" class="form-control col-md-7 col-xs-12 selector noshadow" disabled="disabled" ng-model="cbhabilitado" data-parsley-id="2324">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default">Cerrar</button>
			</div>
		</div>
	</div>
</div>
<!-- Modal insertar/actualizarProspecto-->
<div class="modal fade" id="modalInsertarActualizarProspecto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="modalTituloProspecto2">Insertar/Actualizar</h4>
			</div>
			<div class="modal-body">
				<form name="form2" id="form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Nombre:<span class="required">*</span>
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control col-md-7 col-xs-12" ng-model="nombre_prospecto">
							<span id="nombreProspectoerror" class="text-danger"></span>
						</div>
					</div>
			<div class="form-group">
				<label class="control-label col-md-4 col-sm-4 col-xs-12">Origen
				<span class="required">*</span></label>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<select ng-model="origen" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324" ng-options="ori.id_origen as ori.origen for ori in Origenes">
						<option value="">---Seleccione un origen---</option>
					</select>
					<span id="origenerror" class="text-danger"></span>
				</div>
			</div>
			<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">RFC</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="rfc" required="required" class="form-control col-md-7 col-xs-12" ng-model="rfc">
						</div>
			</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Giro:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="giro" required="required" class="form-control col-md-7 col-xs-12" ng-model="giro">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Comentario:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="comentario" required="required" class="form-control col-md-7 col-xs-12" ng-model="comentario">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Habilitado<span class="required">*</span>
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="checkbox" class="form-control col-md-7 col-xs-12 selector noshadow" ng-model="cbhabilitado" data-parsley-id="2324">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default">Cerrar</button>
				<button type="button" class="btn btn-primary" ng-click="guardarProspecto()" id="btnGuardarProspecto">Guardar</button>
				
			</div>
		</div>
	</div>
</div>


</span>
<script type="text/javascript" src="js/notify.js"></script>
<script type="text/javascript">
	$( document ).ready( function () {
		$( 'a[data-toggle="tab"]' ).on( 'shown.bs.tab', function ( e ) {
			$( '#calendario' ).fullCalendar( 'render' );
		} );
		$( '#myTab a:first' ).tab( 'show' );
	} )
</script>