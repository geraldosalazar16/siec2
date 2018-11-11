<!--
<link rel="stylesheet" type="text/css" href="css/jquery-ui-themes/jquery-ui.min.css">

<link rel="stylesheet" type="text/css" href="css/jquery-ui-themes/timepicker.css">

<script type="text/javascript" src="js/jquery-ui.js"></script>

<script src="js/ngFileUpload/ng-file-upload-shim.min.js"></script>

<script src="js/ngFileUpload/ng-file-upload.min.js"></script>

<script type="text/javascript" src="js/datepicker/timepicker.js"></script>
-->
<script type="text/javascript" src="js/notify.js"></script>
<span ng-controller="perfilprospecto_controller">	
	<div class="right_col" role="main">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h2>Actividad del Prospecto</small></h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						<div class="col-md-3 col-sm-3 col-xs-12 profile_left">
							<h3>{{nombre_prospecto}}</h3>

							<ul class="list-unstyled user_data">
								<li>
									<i class="fa fa-map-marker user-profile-icon"></i> {{rfc}}
								</li>

								<li>
									<i class="fa fa-briefcase user-profile-icon"></i> {{giro}}
								</li>

								<li class="m-top-xs">
									<i class="fa fa-external-link user-profile-icon"></i> {{comentario}}
								</li>
								<li>
									<button type="button" class="btn btn-primary btn-xs btn-imnc btnEditar" id="btnNuevoCliente" ng-click="asignarValor()" 
									ng-if='modulo_permisos["registrar"] == 1 && id_cliente==0' style="float: right;"><i class="fa fa-edit"> </i> Agregar cliente
									</button>
									<span ng-if="id_cliente!=0" style="float: right;"> El prospecto ya está registrado como cliente </span>
								</li>
								<li>
									<p align="right">
										<button type="button" ng-click="detallesProspecto()" class="btn btn-primary btn-xs btn-info">Detalles </button>
									</p>
								</li>
							</ul>
							<br/>
						</div>
						<div class="col-md-9 col-sm-9 col-xs-12">
							<div class="profile_title">
								<div class="col-md-6">
									<h2>Actividad del Propecto </h2>
								</div>
							</div>
							<div class="" role="tabpanel" data-example-id="togglable-tabs">
								<ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
									<li role="presentation" class="active">
										<a href="#tab_contacto" id="tab_contacto-tab"  role="tab" data-toggle="tab" aria-expanded="true" ng-click="listaDomiciliosForContacto()">
										Contacto</a>
									</li>
									
									<li role="presentation" class="">
										<a href="#tab_domicilio" id="home-tab"  role="tab"  data-toggle="tab" aria-expanded="false">
										Domicilio</a>
									</li>		

									<li role="presentation" class="">
									<a href="#tab_areas" id="tab_areas-tab"  role="tab" data-toggle="tab" aria-expanded="true" >
										Servicio, Tipo de servicio y norma</a>
									</li>				
								</ul>
								<div id="myTabContent" class="tab-content">
									<!-- Tab contacto -->
									<div role="tabpanel" class="tab-pane fade active in" id="tab_contacto" aria-labelledby="home-tab">
										<p>
											<button type="button" ng-click="agregarContacto()" class="btn btn-primary btn-xs btn-imnc"
												ng-if='modulo_permisos["registrar"] == 1' style="float: right;"> 
												<i class="fa fa-plus"> </i> Agregar 
											</button>
										</p>
										<div id="contactos-list" ng-repeat="x in contactoprospecto">
											<div class="message_wrapper">
												<h3 class="heading">{{x.NOMBRE}}</h3>
												<blockquote class="message">
													<div class="col-lg-7">
														<p><strong>Correo :</strong> {{x.CORREO}}</p>
													</div>
													<div class="col-lg-5">
														<p><strong>Puesto :</strong>{{x.PUESTO}}</p>
													</div>
													<div class="col-lg-7">
														<p><strong>Teléfono :</strong>{{x.TELEFONO}}</p>
													</div>
													<div class="col-lg-5">
														<p><strong>Celular:</strong>{{x.CELULAR}}</p>
													</div>
													<div class="col-lg-7">
														<p><strong>Domicilio:</strong>{{x.NOMBRE_DOMICILIO}}</p>
													</div>
													<div class="col-lg-5">
														<p ng-if="x.ACTIVO == 0"><strong>Activo:</strong>No</p>
														<p ng-if="x.ACTIVO == 1"><strong>Activo:</strong>S&iacute;</p>	
													</div>

													<div class="col-lg-12">
														<button type="button" ng-click="editarContacto(x.ID)" class="btn btn-primary btn-xs btn-imnc btnEditar"
														ng-if='modulo_permisos["editar"] == 1' style="float: right;"><i class="fa fa-edit"> </i> Editar
															</button>
														<button type="button" ng-click="detallesContacto(x.ID)" class="btn btn-primary btn-xs btn-info">Detalles </button>
													</div>
													<!--
													<div class="col-lg-12">
														<button type="button" ng-click="editarContacto(x.ID)" class="btn btn-primary btn-xs btn-imnc btnEditar"
														ng-if='modulo_permisos["editar"] == 1 && id_cliente==0' style="float: right;"><i class="fa fa-edit"> </i> Editar
															</button>
														<button type="button" ng-click="detallesContacto(x.ID)" class="btn btn-primary btn-xs btn-info">Detalles </button>
													</div>
													-->
												</blockquote>
											</div>
										</div>
									</div>
									<!-- Fin Tab Contacto-->
									<!-- Tab domicilio-->
									<div role="tabpanel" class="tab-pane fade" id="tab_domicilio" aria-labelledby="profile-tab">
										<p>
											<button type="button" ng-click="agregarDomicilio()" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc"
											ng-if='modulo_permisos["registrar"] == 1' style="float: right;"> 
											<i class="fa fa-plus"> </i> Agregar 
											</button>
											<!--
											<button type="button" ng-click="agregarDomicilio()" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc"
											ng-if='modulo_permisos["registrar"] == 1 && id_cliente==0' style="float: right;"> 
											<i class="fa fa-plus"> </i> Agregar 
											</button>
											-->
										</p>
										<div id="domicilio-list" ng-repeat="y in domicilioprospecto">
											<div class="message_wrapper">

												<h3 class="heading">{{y.NOMBRE}}</h3>
												<blockquote class="message">
													<div class="col-lg-6">
														<p><strong>País :</strong>{{y.PAIS}}</p>
													</div>
													<div class="col-lg-6">
														<p><strong>Estado:</strong>{{y.ESTADO}}</p>
													</div>
													<div class="col-lg-6">
														<p><strong>Código Postal:</strong>{{y.CODIGO_POSTAL}}</p>
													</div>
													<div class="col-lg-6">
														<p><strong>Calle :</strong>{{y.CALLE}}</p>
													</div>
													<div class="col-lg-6">
														<p><strong>Colonia:</strong>{{y.COLONIA}}</p>
													</div>
													<div class="col-lg-6">
														<p><strong>Número Exterior:</strong>{{y.NUMERO_EXTERIOR}}</p>
													</div>
													<div class="col-lg-6">
														<p><strong>Número Interior:</strong>{{y.NUMERO_INTERIOR}}</p>
													</div>
													<div class="col-lg-6">
														<p><strong>Municipio:</strong>{{y.MUNICIPIO}}</p>
													</div>
													<div class="col-lg-6">
														<p ng-if="y.FISCAL == 0"><strong>Fiscal:</strong>No</p>
														<p ng-if="y.FISCAL == 1"><strong>Fiscal:</strong>S&iacute;</p>
													</div>
													<div class="col-lg-6">
														<p ng-if="y.CENTRAL == 0"><strong>Central:</strong>No</p>
														<p ng-if="y.CENTRAL == 1"><strong>Central:</strong>S&iacute;</p>	
													</div>
													<div class="col-lg-12">
														<button type="button" ng-click="editarDomicilio(y.ID)" class="btn btn-primary btn-xs btn-imnc btnEditar"
														ng-if='modulo_permisos["editar"] == 1' style="float: right;">
															<i class="fa fa-edit"> </i> Editar
														</button>
														<!--
														<button type="button" ng-click="editarDomicilio(y.ID)" class="btn btn-primary btn-xs btn-imnc btnEditar"
														ng-if='modulo_permisos["editar"] == 1 && id_cliente==0' style="float: right;">
															<i class="fa fa-edit"> </i> Editar
														</button>
														-->
														<button type="button" ng-click="detallesDomicilio(y.ID)" class="btn btn-primary btn-xs btn-info">Detalles </button>
													</div>
												</blockquote>
											</div>
										</div>
									</div>
									<!-- Fin Tab Domicilio-->
									<!-- Tab Areas-->
									<div role="tabpanel" class="tab-pane fade" id="tab_areas" aria-labelledby="profile-tab">
										<div id="areas">
											<div class="row">
												<span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>
												<div class="x_panel">
													<div class="row">
														<div class="x_title">
															<div class="row">
																<div class="col-md-6">
																	<h3>Informaci&oacuten sobre el servicio</h3>
																</div>
																<div class="col-md-6">
																	<button type="button" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc float-rigth" style="float: right;" ng-click="mostrar_modal_insertar_editar_producto('insertar')"><i class="fa fa-plus"> </i> Agregar producto</button>
																</div>
															</div>
														</div>
													</div>
													<div class = "row">
														<table class="table table-striped">
																<thead class="thead-dark">
																	<tr>
																		<th>Servicio</th>
																		<th>Tipo de servicio</th>
																		<th>Normas</th>
																		<th></th>
																		<th></th>
																		<th></th>
																	</tr>
																</thead>
																<tbody>
																	<tr ng-repeat-start="producto in ProductosProspecto">
																		<td>{{producto.nombre_servicio}}</td>
																		<td>{{producto.nombre_tipo_servicio}}</td>
																		<td>
																			<div class="row" ng-repeat="norma in producto.normas">
																				<span>{{$index+1}}- {{norma.ID_NORMA}}</span>
																			</div>
																		</td>
																		<td>
																			<button type="button" class="btn btn-primary btn-xs btn-imnc btnEditar" 
																			ng-click="mostrar_modal_insertar_editar_producto('editar',producto)" style=	"float: right;">
																				<i class="fa fa-edit"> </i> Editar
																			</button>
																			<button type="button" class="btn btn-primary btn-xs btn-imnc btnEditar" 
																			ng-if="producto.tiene_cotizacion == 0"
																			ng-click="mostrar_modal_crear_cotizacion(producto)" style=	"float: right;">
																				<i class="fa fa-usd"> </i> Crear cotización
																			</button>
																			<button type="button" class="btn btn-primary btn-xs btn-imnc btnEditar" 
																			ng-if="producto.tiene_cotizacion == 1 && producto.id_cotizacion != 0 && producto.id_cotizacion"
																			ng-click="ver_cotizacion(producto)" style=	"float: right;">
																				<i class="fa fa-usd"> </i> Ver cotización
																			</button>
																		</td>
																		<td>																																		
																			<button type="button" class="btn btn-primary btn-xs btn-imnc btnEliminar" 
																			ng-if="producto.sectores_mostrandose == false"
																			ng-click="verSectores(producto)" style=	"float: right;">
																				<i class="fa fa-eye"> </i> Ver sectores
																			</button>
																			<button type="button" class="btn btn-primary btn-xs btn-imnc btnEliminar" 
																			ng-if="producto.sectores_mostrandose == true" 
																			ng-click="ocultarSectores(producto)" style=	"float: right;">
																				<i class="fa fa-eye"> </i> Ocultar sectores
																			</button>

																			<button type="button" class="btn btn-primary btn-xs btn-imnc btnEliminar" 
																			ng-if="producto.integracion_mostrandose == false && producto.id_tipo_servicio == 20"
																			ng-click="verIntegracion(producto)" style=	"float: right;">
																				<i class="fa fa-eye"> </i> Ver Integración
																			</button>
																			<button type="button" class="btn btn-primary btn-xs btn-imnc btnEliminar" +
																			ng-if="producto.integracion_mostrandose == true && producto.id_tipo_servicio == 20" 
																			ng-click="ocultarIntegracion(producto)" 
																			style=	"float: right;">
																				<i class="fa fa-eye"> </i> Ocultar Integración
																			</button>
																		</td>
																		<td>																																		
																			<button type="button" class="btn btn-primary btn-xs btn-imnc btnEliminar" 
																			ng-if="producto.tiene_cotizacion == 0"
																			ng-click="eliminarProducto(producto.id)" style=	"float: right;">
																				<i class="fa fa-trash"> </i> Eliminar
																			</button>
																		</td>
																	</tr>
																	<tr class="collapse out" id="collapse_sectores_{{producto.id_tipo_servicio}}">
																		<td colspan="13">
																			<h4>Sectores del servicio</h4>
																			<button type="button" ng-click="mostrar_modal_agregar_editar_sector('insertar',producto)" class="btn btn-primary btn-xs btn-imnc" style="float: right;"> 
																				<i class="fa fa-plus"> </i> Agregar sector 
																			</button>
																			<table class="table table-striped responsive-utilities jambo_table bulk_action" 
																			ng-if="producto.sectores.length > 0">																	
																			<thead>
																				<tr class="headings">
																					<th class="column-title">Nombre del sector</th>
																					<th class="column-title"></th>
																					<th class="column-title"></th>
																				</tr>
																			</thead>
																			<tbody>
																			<tr ng-repeat="sector in producto.sectores" class="ng-scope  even pointer">
																					<td> {{sector.ID_SECTOR}}-{{sector.NOMBRE}}</td>
																					<td>
																						<p ng-if='modulo_permisos["registrar"] == 1'>
																							<button type="button"  ng-click="mostrar_modal_agregar_editar_sector('editar',producto,sector)" class="btn btn-primary btn-xs btn-imnc" style="float: right;"> 
																								<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar sector 
																							</button>
																						</p>
																					</td>
																					<td>
																						<p ng-if='modulo_permisos["editar"] == 1'>
																							<button type="button"  ng-click="mostrar_modal_eliminar_sector(producto,sector)" class="btn btn-primary btn-xs btn-imnc" style="float: right;"> 
																								<i class="fa fa-trash" aria-hidden="true"></i> Eliminar sector 
																							</button>
																						</p>
																					</td>
																				</tr>
																			</tbody>
																			</table>
																		</td>																	
																	</tr>
																	<tr ng-repeat-end class="collapse out" id="collapse_integracion_{{producto.id_tipo_servicio}}">
																		<td colspan="13">
																			<h4>Integración del servicio: {{producto.nivel_integracion}}%</h4>
																			
																			<table class="table table-striped responsive-utilities jambo_table bulk_action" 
																			ng-if="producto.integracion.length > 0">																	
																			<thead>
																				<tr class="headings">
																					<th class="column-title">Pregunta</th>
																					<th class="column-title">Respuesta</th>
																					<th class="column-title">Porcentaje</th>
																					<th class="column-title"></th>
																				</tr>
																			</thead>
																			<tbody>
																			<tr ng-repeat="integracion in producto.integracion" class="ng-scope  even pointer">
																					<td> {{integracion.PREGUNTA}}</td>
																					<td> {{integracion.RESPUESTA}}</td>
																					<td> {{integracion.VALOR}}%</td>
																					<td>
																						<p ng-if='modulo_permisos["registrar"] == 1'>
																							<button type="button"  
																							ng-click="mostrar_modal_editar_integracion(producto,integracion)" 
																							class="btn btn-primary btn-xs btn-imnc" style="float: right;"> 
																								<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar 
																							</button>
																						</p>
																					</td>
																				</tr>
																			</tbody>
																			</table>
																		</td>																	
																	</tr>
																</tbody>
															</table>
														<div class = "table-responsive">
															
														</div>
													</div>												
												</div>
											</div>
										</div>
									</div>
									<!--Fin Tab Area-->
									<!--
									<div role="tabpanel" class="tab-pane fade" id="tab_calendario" aria-labelledby="profile-tab">
										<script type="text/javascript" src="controllers/cita_calendario.js"></script>
										<div ng-controller="cita_calendario_controller">
											<div id="calendario" class="cal1"></div>

											<div class="modal fade" id="modalCreateEvento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
												<div class="modal-dialog" role="document">
													<div class="modal-content">
														<div class="modal-header">
															<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
															<h4 class="modal-title" id="modalTitulo">Nuevo Evento</h4>
														</div>
														<div class="modal-body">
															<form name="form2" id="form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" enctype="multipart/form-data">
																<div class="form-group">
																	<label class="control-label col-md-4 col-sm-4 col-xs-12">Asunto
																	<span class="required">*</span></label>
																	<div class="col-md-6 col-sm-6 col-xs-12">
																		<input type="text" name="asunto" id="asunto" class="form-control col-md-7 col-xs-12" ng-model="form.asunto" data-parsley-id="2324">
																		<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
																		<span id="asuntoerror" class="text-danger"></span>
																	</div>
																</div>

																<div class="form-group">
																	<label class="control-label col-md-4 col-sm-4 col-xs-12">Folio</label>
																	<div class="col-md-6 col-sm-6 col-xs-12">
																	<select ng-model="form.id_cotizacion" required="required" 
																		class="form-control col-md-7 col-xs-12" data-parsley-id="2324" ng-options="fol.id_cotizacion as fol.folio for fol in Folios">
																			<option value="">---Seleccione un Asunto---</option>
																		</select>
																		
																		<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
																		<span id="folioerror" class="text-danger"></span>
																	</div>
																</div>

																<div class="form-group">
																	<label class="control-label col-md-4 col-sm-4 col-xs-12">Tipo Asunto
																	<span class="required">*</span></label>
																	<div class="col-md-6 col-sm-6 col-xs-12">
																		<select ng-model="form.tipo_asunto" required="required" 
																		class="form-control col-md-7 col-xs-12" data-parsley-id="2324" ng-options="asu.id_tipo_asunto as asu.descripcion for asu in Asuntos">
																			<option value="">---Seleccione un Asunto---</option>
																		</select>
																		<span id="tipoasuntoerror" class="text-danger"></span>
																	</div>
																</div>

																<div class="form-group" ng-if="Usuario.permisos == 'admin'">
																	<label class="control-label col-md-4 col-sm-4 col-xs-12">Asignar Usuario
																	<span class="required">*</span></label>
																	<div class="col-md-6 col-sm-6 col-xs-12">
																		<select ng-model="form.usuario_asignado" required="required" 
																		class="form-control col-md-7 col-xs-12" data-parsley-id="2324" 
																		ng-options="user.id_usuarios as user.nombre for user in Lista_Usuarios">
																			<option value="">---Seleccione un Usuario---</option>
																		</select>
																		<span id="usuarioasignadoerror" class="text-danger"></span>
																	</div>
																</div>

																<div class="form-group">
																	<label class="control-label col-md-4 col-sm-4 col-xs-12">Factibilidad
																	<span class="required">*</span></label>
																	<div class="col-md-6 col-sm-6 col-xs-12">
																		<select ng-model="form.porcentaje" required="required" 
																		class="form-control col-md-7 col-xs-12" 
																		ng-change = "des_porcentaje = form.porcentaje.descripcion"
																		ng-options="per as per.porcentaje for per in Porcentajes track by per.id_porcentaje">
																			<option value="">---Seleccione un porcentaje---</option>
																		</select>
																		<span id="porcentajeerror" class="text-danger"></span>
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
																	<span class="required">*</span></label>
																	<div class="col-md-6 col-sm-6 col-xs-12">
																		<select ng-model="form.propuesta_estado" required="required" 
																		class="form-control col-md-7 col-xs-12" data-parsley-id="2324" ng-options="est.id_estado as est.estado for est in PropuestasEstados">
																			<option value="">---Seleccione un estado---</option>
																		</select>
																		<span id="propuestaestadoerror" class="text-danger"></span>
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

																<div class="form-group" id="HF">
																	<label class="control-label col-md-4 col-sm-4 col-xs-12">Hora Fin
																	<span class="required">*</span></label>
																	<div class="col-md-6 col-sm-6 col-xs-12">
																		<input type="text" required="required" ng-model="form.hora_fin" class="form-control col-md-5 col-xs-10" id="hora_fin" data-parsley-id="2324">
																		<span id="horafinerror" class="text-danger"></span>
																	</div>
																</div>


																<div class="form-group" hidden>
																	<label class="control-label col-md-4 col-sm-4 col-xs-12">Recordatorio
																	<span class="required">*</span></label>
																	<div class="col-md-6 col-sm-6 col-xs-12">
																		<input type="number" name="recordatorio" id="recordatorio" class="form-control col-md-7 col-xs-12" ng-model="form.recordatorio" data-parsley-id="2324">
																		<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
																		<span id="recordatorioerror" class="text-danger"></span>
																	</div>
																</div>

																<div class="form-group">
																	<label class="control-label col-md-4 col-sm-4 col-xs-12">Observaciones
																	</label>
																	<div class="col-md-6 col-sm-6 col-xs-12">
																		<textarea rows="4" cols="50" name="observaciones" id="observaciones" class="form-control col-md-7 col-xs-12" ng-model="form.observaciones" data-parsley-id="2324">
																		</textarea>
																		<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
																		<span id="observacioneserror" class="text-danger"></span>
																	</div>
																</div>

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
	
									</div>
									-->
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<?php 
	include "perfilprospecto/modal_confirmacion.php";
	include "perfilprospecto/modal_detalles_cliente.php";
	include "perfilprospecto/modal_detalles_contacto.php";
	include "perfilprospecto/modal_detalles_domicilio.php";
	include "perfilprospecto/modal_detalles_prospecto.php";
	include "perfilprospecto/modal_editar_integracion.php";
	include "perfilprospecto/modal_eliminar_sector.php";
	include "perfilprospecto/modal_generar_pdf.php";
	include "perfilprospecto/modal_insertar_actualizar_contacto.php";
	include "perfilprospecto/modal_insertar_actualizar_domicilio.php";
	include "perfilprospecto/modal_insertar_actualizar_producto.php";
	include "perfilprospecto/modal_insertar_actualizar_prospecto.php";
	include "perfilprospecto/modal_insertar_actualizar_sector.php";
	include "perfilprospecto/modal_insertar_cotizacion.php";
	?>
</span>

