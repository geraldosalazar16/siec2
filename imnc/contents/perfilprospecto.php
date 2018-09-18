<span ng-controller="perfilprospecto_controller">
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
					<h2>Actividad del Prospecto</small></h2>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">

					<div class="col-md-3 col-sm-3 col-xs-12 profile_left">
						<h3>{{nombre_prospecto}}</h3>

						<ul class="list-unstyled user_data">
							<li><i class="fa fa-map-marker user-profile-icon"></i> {{rfc}}
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
							<!--
								<button type="button" ng-click="editarProspecto()" class="btn btn-primary btn-xs btn-imnc btnEditar" style="float: right;">
			                     <i class="fa fa-edit"> </i> Editar
			                    </button>
								<!--
								<button type="button" ng-click="editarProspecto()" class="btn btn-primary btn-xs btn-imnc btnEditar" 
								ng-if='modulo_permisos["editar"] == 1 && id_cliente==0' style="float: right;">
			                     <i class="fa fa-edit"> </i> Editar
			                    </button>
								-->
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
                          			Productos</a>
								</li>
								
								<li role="presentation" class="">
								<a href="#tab_cotizacion" id="tab_cotizacion-tab"  role="tab" data-toggle="tab" aria-expanded="true" >
                          			Cotizaciones</a>
								</li>
								
								
								<!--
								<li role="presentation" class=""><a href="#tab_calendario" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">
                          Calendario</a>
								-->

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
								<!-- Tab Cotizacion-->
								<div role="tabpanel" class="tab-pane fade" id="tab_cotizacion" aria-labelledby="profile-tab">
									<div class="row">
										<div  ng-if='modulo_permisos["registrar"] == 1'>
											<button type="button" id="btnNuevaCotizacion" ng-click="agregarCotizacion()" class="btn btn-primary btn-xs btn-imnc">
												<i class="fa fa-plus"> </i> Agregar Cotizaci&oacuten
											</button>
										</div>
									</div>
									<div class = "row" style="margin-top: 15px;">
										<div class="col-sm-6 col-md-6">	
											<label class="control-label col-md-12 col-sm-12 col-xs-12">Productos</label>
											<select class="form-control col-md-7 col-xs-12" ng-model="productosCotizacion" ng-options="producto as producto.nombre for producto in ProductosProspecto" ng-change="cambioProducto()"> 
												<option value="" ng-selected="true" >Mostrar todas las cotizaciones</option>
											</select>
											<span id="productoerror" class="text-danger"></span>
										</div>
										<div class="col-sm-6 col-md-6">	
											<label class="control-label col-md-12 col-sm-12 col-xs-12">Cotizaciones</label>
											<select class="form-control col-md-7 col-xs-12" ng-model="cotizaciones" ng-options="cot as cot.NOMBRE for cot in Cotizaciones" ng-change="cambioCotizacion()"> 
												<option value="" ng-selected="true" disabled>Seleccione una opción</option>
											</select>
										</div>
									</div>
									<div id="cotizacion" ng-hide="mostrar_cotizacion == false">
									    	<!--/////////////////////////////////////////////////////-->
										<div class="row" style="padding-top: 10px">
											<div class="col-sm-6">
												<label class="control-label col-md-12 col-sm-12 col-xs-12">Referencia </label>
												<input type="text" id="referencia" required="required" class="form-control col-sm-6 col-md-6 col-xs-12" value={{referencia}} 
														data-parsley-id="2324" readonly="readonly">
												</input>
											</div>	
											<div class="col-sm-6">	
												<label class="control-label col-md-12 col-sm-12 col-xs-12">Periodicidad de la Cotizaci&oacuten</label>
												<select class="form-control col-md-7 col-xs-12" ng-model="periodicidad_cotizacion" ng-change="cambioPeriodicidad()"> 
													<option value="0">Semestral</option>
													<option value="1" ng-selected="true">Anual</option>
												</select>
											</div>										
										</div>
										<!--/////////////////////////////////////////////////////-->
										<div class="row" style="margin-top: 10px">
											<div class="dropdown" ng-if='area_cotizacion == 1' style="margin-bottom: 15px; margin-left: 15px; margin-top: 15px;">
												<button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
												<i class="fa fa-cloud-download" aria-hidden="true"></i> Exportar cotizaci&oacuten
												<span class="caret"></span>
												</button>
												<ul class="dropdown-menu">
												    <li><a ng-click="GenerarPDF()"> PDF</a></li>
												</ul>
											</div>
											<div class="x_panel">
												<div class="row" style="padding-top: 10px">
													<div class="col-sm-6">	
														<label class="control-label col-md-12 col-sm-12 col-xs-12">Nombre Cotizaci&oacuten </label>
														<input type="text" id="nombreCotizacion" required="required" class="form-control col-sm-6 col-md-6 col-xs-12" ng-model="nombreCotizacion" data-parsley-id="2324" >
														</input>
														<span id="nombrecotizacionerror" class="text-danger"></span>
													</div>
													<div class="col-sm-6 col-md-6">	
														<label class="control-label col-md-12 col-sm-12 col-xs-12">Tipo de Cotizaci&oacuten</label>
														<select class="form-control col-md-7 col-xs-12" ng-model="tiposCotizacion" > 
															<option value="" ng-selected="true" disabled>Seleccione una opción</option>
															<option value="0">General</option>
															<option value="1">Por producto</option>
														</select>
														<span id="productoerror" class="text-danger"></span>
													</div>
												</div>
											</div>
											<div class="x_panel">
												<div class="row">
													<div class="x_title">
														<div class="col-md-6">
															<h3>Fechas Tentativas</h3>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-sm-3">
														<label class="control-label col-md-12 col-sm-12 col-xs-12">Fecha E1 </label>
														<input type="text" id="fecha_e1" required="required" class="form-control col-sm-6 col-md-6 col-xs-12" ng-model="fecha_e1" 
														data-parsley-id="2324" ></input>
													</div>	
													<div class="col-sm-3">												
														<label class="control-label col-md-12 col-sm-12 col-xs-12">Fecha E2 </label>
														<input type="text" id="fecha_e2" required="required" class="form-control col-sm-6 col-md-6 col-xs-12" ng-model="fecha_e2" 
														data-parsley-id="2324" ></input>
													</div>												
													<div class="col-sm-3">	
														<label class="control-label col-md-12 col-sm-12 col-xs-12">Fecha V1 </label>
														<input type="text" id="fecha_v1" required="required" class="form-control col-sm-6 col-md-6 col-xs-12" ng-model="fecha_v1" 
														data-parsley-id="2324" ></input>
													</div>
													<div class="col-sm-3">												
														<label class="control-label col-md-12 col-sm-12 col-xs-12">Fecha V2 </label>														
														<input type="text" id="fecha_v2" required="required" class="form-control col-sm-6 col-md-6 col-xs-12" ng-model="fecha_v2" 
														data-parsley-id="2324" ></input>
													</div>
												</div>
												<div class="row" style="padding-top: 10px" ng-if="mostrar_semestrales == true">
													<div class="col-sm-3">	
														<label class="control-label col-md-12 col-sm-12 col-xs-12">Fecha V3 </label>
														<input type="text" id="fecha_v3" required="required" class="form-control col-sm-6 col-md-6 col-xs-12" ng-model="fecha_v3" 
														data-parsley-id="2324" ></input>
													</div>
													<div class="col-sm-3">	
														<label class="control-label col-md-12 col-sm-12 col-xs-12">Fecha V4 </label>
														<input type="text" id="fecha_v4" required="required" class="form-control col-sm-6 col-md-6 col-xs-12" ng-model="fecha_v4" 
														data-parsley-id="2324" ></input>
													</div>
													<div class="col-sm-3">	
														<label class="control-label col-md-12 col-sm-12 col-xs-12">Fecha V5 </label>
														<input type="text" id="fecha_v5" required="required" class="form-control col-sm-6 col-md-6 col-xs-12" ng-model="fecha_v5" 
														data-parsley-id="2324" ></input>
													</div>
												</div>
											</div>
											<div class="x_panel">
												<div class="row">
													<div class="x_title">
														<div class="col-md-6">
															<h3>Montos</h3>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-sm-3">
														<label class="control-label col-md-12 col-sm-12 col-xs-12">Monto E1 </label>
														<input type="number" step="0.01" ng-model="monto_e1" class="form-control col-md-5 col-xs-10">
													</div>	
													<div class="col-sm-3">												
														<label class="control-label col-md-12 col-sm-12 col-xs-12">Monto E2 </label>
														<input type="number" step="0.01" class="form-control col-md-5 col-xs-10" ng-model="monto_e2">
													</div>												
													<div class="col-sm-3">	
														<label class="control-label col-md-12 col-sm-12 col-xs-12">Monto V1 </label>
														<input type="number" step="0.01" ng-model="monto_v1" class="form-control col-md-5 col-xs-10">
													</div>
													<div class="col-sm-3">												
														<label class="control-label col-md-12 col-sm-12 col-xs-12">Monto V2 </label>														
														<input type="number" step="0.01" ng-model="monto_v2" class="form-control col-md-5 col-xs-10">
													</div>
												</div>
												<div class="row" style="padding-top: 10px" ng-if="mostrar_semestrales == true">
													<div class="col-sm-3">	
														<label class="control-label col-md-12 col-sm-12 col-xs-12">Monto V3 </label>
														<input type="number" step="0.01" ng-model="monto_v3" class="form-control col-md-5 col-xs-10">
													</div>
													<div class="col-sm-3">	
														<label class="control-label col-md-12 col-sm-12 col-xs-12">Monto V4 </label>
														<input type="number" step="0.01" ng-model="monto_v4" class="form-control col-md-5 col-xs-10">
													</div>
													<div class="col-sm-3">	
														<label class="control-label col-md-12 col-sm-12 col-xs-12">Monto V5 </label>
														<input type="number" step="0.01" ng-model="monto_v5" class="form-control col-md-5 col-xs-10">
													</div>
												</div>
											</div>
			
											<div class="x_panel">
												<div class="row">
													<div class="x_title">
														<div class="col-md-6">
															<h3>D&iacuteas</h3>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-sm-3">
														<label class="control-label col-md-12 col-sm-12 col-xs-12">D&iacuteas E1 </label>
														<input type="number" step="0.01" ng-model="dias_e1" class="form-control col-md-5 col-xs-10">
													</div>	
													<div class="col-sm-3">												
														<label class="control-label col-md-12 col-sm-12 col-xs-12">D&iacuteas E2 </label>
														<input type="number" step="0.01" ng-model="dias_e2" class="form-control col-md-5 col-xs-10">
													</div>												
													<div class="col-sm-3">	
														<label class="control-label col-md-12 col-sm-12 col-xs-12">D&iacuteas V1 </label>
														<input type="number" step="0.01" ng-model="dias_v1" class="form-control col-md-5 col-xs-10">
													</div>
													<div class="col-sm-3">												
														<label class="control-label col-md-12 col-sm-12 col-xs-12">D&iacuteas V2 </label>														
														<input type="number" step="0.01" ng-model="dias_v2" class="form-control col-md-5 col-xs-10">
													</div>
												</div>
												<div class="row" style="padding-top: 10px" ng-if="mostrar_semestrales == true">
													<div class="col-sm-3">
														<label class="control-label col-md-12 col-sm-12 col-xs-12">D&iacuteas V3 </label>
														<input type="number" step="0.01" ng-model="dias_v3" class="form-control col-md-5 col-xs-10">
													</div>	
													<div class="col-sm-3">												
														<label class="control-label col-md-12 col-sm-12 col-xs-12">D&iacuteas V4 </label>
														<input type="number" step="0.01" ng-model="dias_v4" class="form-control col-md-5 col-xs-10">
													</div>												
													<div class="col-sm-3">	
														<label class="control-label col-md-12 col-sm-12 col-xs-12">D&iacuteas V5 </label>
														<input type="number" step="0.01" ng-model="dias_v5" class="form-control col-md-5 col-xs-10">
													</div>
												</div>
											</div>	

											<div class="x_panel">
												<div class="row">
													<div class="x_title">
														<div class="col-md-6">
															<h3>Informaci&oacuten de cotizaci&oacuten</h3>
														</div>
													</div>
												</div>
												<div class="row">
													<span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>
													<div class="col-sm-4">
														<label class="control-label col-md-12 col-sm-12 col-xs-12">Cantidad de Empleados </label>
														<div class="col-md-12 col-sm-12 col-xs-10">
															<input type="number" step="1" ng-model="cantidad_empleados" class="form-control col-md-4 col-sm-4 col-xs-10">
														</div>
													</div>	
													<div class="col-sm-4">												
														<label class="control-label col-md-12 col-sm-12 col-xs-12">Cantidad de Sitios </label>
														<div class="col-md-12 col-sm-12 col-xs-10">
															<input type="number" step="1" ng-model="cantidad_sitios" class="form-control col-md-4  col-sm-4 col-xs-10">
														</div>
													</div>
													<div class="col-sm-4">												
														<label class="control-label col-md-12 col-sm-12 col-xs-12">Importe Certificado </label>
														<div class="col-md-12 col-sm-12 col-xs-10">
															<input type="number" step="1" ng-model="importe_certificado" class="form-control col-md-4  col-sm-4 col-xs-10">
														</div>
													</div>	
												</div>
											</div>										
										</div>
										<div class="x_panel">
											<div class="row">
												<div class="form-group">
													<div class="col-sm-4">	
														<label class="control-label col-md-12 col-sm-12 col-xs-12">Certificado Acreditado EMA</label>
														<select ng-model="certificado" id="cmbCertificado" class="form-control col-md-7 col-xs-12"> 
															<option value="" ng-selected="true" disabled>Seleccione una opción</option>
															<option value="1">SI</option>
															<option value="0">NO</option>
														</select>
													</div>
													<div class="col-sm-4">	
														<label class="control-label col-md-12 col-sm-12 col-xs-12">Viáticos Incluidos</label>
														<select ng-model="viaticos" id="cmbViaticos" ng-change="cambioViaticos()" class="form-control col-md-7 col-xs-12"> 
															<option value="" ng-selected="true" disabled>Seleccione una opción</option>
															<option value="1">SI</option>
															<option value="0">NO</option>
														</select>
													</div>
													<div class="col-sm-4">	
														<label class="control-label col-md-12 col-sm-12 col-xs-12">IVA Incluido</label>
														<select ng-model="iva" id="cmbIva" class="form-control col-md-7 col-xs-12"> 
															<option value="" ng-selected="true" disabled>Seleccione una opción</option>
															<option value="1">SI</option>
															<option value="0">NO</option>
														</select>
													</div>
												</div>
											</div>
											<div class="row" style="padding-top: 10px" ng-if="mostrar_viaticos_anual == true">
												<div class="col-sm-3">
													<label class="control-label col-md-12 col-sm-12 col-xs-12">Vi&aacuteticos E1 </label>
													<input type="number" step="0.01" ng-model="viaticos_e1" class="form-control col-md-5 col-xs-10">
												</div>	
												<div class="col-sm-3">												
													<label class="control-label col-md-12 col-sm-12 col-xs-12">Vi&aacuteticos E2 </label>
													<input type="number" step="0.01" ng-model="viaticos_e2" class="form-control col-md-5 col-xs-10">
												</div>												
												<div class="col-sm-3">	
													<label class="control-label col-md-12 col-sm-12 col-xs-12">Vi&aacuteticos V1 </label>
													<input type="number" step="0.01" ng-model="viaticos_v1" class="form-control col-md-5 col-xs-10">
												</div>
												<div class="col-sm-3">												
													<label class="control-label col-md-12 col-sm-12 col-xs-12">Vi&aacuteticos V2 </label>														
													<input type="number" step="0.01" ng-model="viaticos_v2" class="form-control col-md-5 col-xs-10">
												</div>
											</div>
											<div class="row" style="padding-top: 10px" ng-if="mostrar_viaticos_semestral == true">
												<div class="col-sm-3">
													<label class="control-label col-md-12 col-sm-12 col-xs-12">Vi&aacuteticos V3 </label>
													<input type="number" step="0.01" ng-model="viaticos_v3" class="form-control col-md-5 col-xs-10">
												</div>	
												<div class="col-sm-3">												
													<label class="control-label col-md-12 col-sm-12 col-xs-12">Vi&aacuteticos V4 </label>
													<input type="number" step="0.01" ng-model="viaticos_v4" class="form-control col-md-5 col-xs-10">
												</div>												
												<div class="col-sm-3">	
													<label class="control-label col-md-12 col-sm-12 col-xs-12">Vi&aacuteticos V5 </label>
													<input type="number" step="0.01" ng-model="viaticos_v5" class="form-control col-md-5 col-xs-10">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="form-group" align="right">
												<span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>
												<button type="button" class="btn btn-primary" id="btnGuardarCotizacion" ng-click="guardarCotizacion()" >Guardar</button>
											</div>
										</div>
									</div>
								</div>
								<!-- Fin Tab Cotizacion-->
								<!-- Tab Areas-->
								<div role="tabpanel" class="tab-pane fade" id="tab_areas" aria-labelledby="profile-tab">
									<div id="areas">
										<div class="row">
											<span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>
											<div class="x_panel">
												<div class="row">
													<div class="x_title">
														<div class="col-md-6">
															<h3>Informaci&oacuten sobre el servicio</h3>
														</div>
													</div>
												</div>
												<div class = "row">
													<table class="table table-striped">
															<thead class="thead-dark">
																<tr>
																	<th>Producto</th>
																	<th>Departamento</th>
																	<th>&Aacuterea</th>
																</tr>
															</thead>
															<tbody>
																<tr ng-repeat="producto in ProductosProspecto">
																	<td>{{producto.nombre}}</td>
																	<td>{{producto.departamento}}</td>
																	<td>{{producto.area}}</td>
																	<td>
																		<button type="button" class="btn btn-primary btn-xs btn-imnc btnEditar" ng-click="editarProducto(producto.id)" style=	"float: right;"><i class="fa fa-edit"> </i> Editar
																		</button>
																	</td>
																																		<td>
																		<button type="button" class="btn btn-primary btn-xs btn-imnc btnEliminar" ng-click="eliminarProducto(producto.id)" style=	"float: right;"><i class="fa fa-trash"> </i> Eliminar
																		</button>
																	</td>
																</tr>
															</tbody>
														</table>
													<div class = "table-responsive">
														
													</div>
												</div>
												<div class="row">
													<button type="button" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc" style="float: right;" ng-click="agregarProducto()"><i class="fa fa-plus"> </i> Agregar producto
													</button>
												</div>
												<!--
												<div class="row">													
													<div class="form-group">
														<div class="col-sm-4">	
															<label class="control-label col-md-12 col-sm-12 col-xs-12">&Aacuterea</label>
															<select ng-model="areas" ng-change="areas_cambio()" class="form-control col-md-7 col-xs-12" ng-options="area.id as area.nombre for area in Areas"> 
																<option value="" ng-selected="true" disabled>Seleccione una opción</option>
															</select>
														</div>
														<div class="col-sm-4">	
															<label class="control-label col-md-12 col-sm-12 col-xs-12">Departamento</label>
															<select ng-model="departamentos" ng-change="departamentos_cambio()" class="form-control col-md-7 col-xs-12" ng-options="departamento.id as departamento.nombre for departamento in Departamentos"> 
																<option value="" ng-selected="true" disabled>Seleccione una opción</option>
															</select>
														</div>
														<div class="col-sm-4">	
															<label class="control-label col-md-12 col-sm-12 col-xs-12">Productos</label>
															<select ng-model="productos" ng-change="productos_cambio()" class="form-control col-md-7 col-xs-12" ng-options="producto.id as producto.nombre for producto in Productos"> 
																<option value="" ng-selected="true" disabled>Seleccione una opción</option>
															</select>
														</div>
													</div>												
												</div>
												<div class="row" style="padding-top: 10px">
													<div class="form-group" align="right">
														<label class="control-label col-md-12 col-sm-12 col-xs-12">Descripci&oacuten del producto</label>
														<textarea rows="4" cols="50" name="desc_producto" id="desc_producto" class="form-control col-md-7 col-xs-12" ng-model="desc_producto" ng-disabled="true" data-parsley-id="2324">
														</textarea>														
													</div>
												</div>
												
												<div class="row" style="padding-top: 10px">
													<button type="button" class="btn btn-primary" ng-click="guardarAreas()" id="btnGuardarAreas">Guardar</button>
												</div>
												-->
											</div>
										</div>
									</div>
								</div>
								<!--Fin Tab Area-->
								<div role="tabpanel" class="tab-pane fade" id="tab_calendario" aria-labelledby="profile-tab">
									<!-- ///////////////////////// CALENDARIO //////////////////////////////-->
									<script type="text/javascript" src="controllers/cita_calendario.js"></script>
									<div ng-controller="cita_calendario_controller">
										<div id="calendario" class="cal1"></div>


										<!-- MODAL CREATE-->
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
									<!-- /////////////////////////FIN CALENDARIO ////////////////////////////-->
								</div>
							</div>
						</div>
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
		              <label class="control-label col-md-4 col-sm-4 col-xs-12">Domicilio del Contacto
		              </label>
		              <div class="col-md-6 col-sm-6 col-xs-12">
		                <select class="form-control col-md-7 col-xs-12" id="domicilioContacto" ng-model="domicilioContacto"
		                	ng-options="dom.ID as dom.NOMBRE for dom in listaDomicilios">
							<option value="">---Seleccione un domicilio---</option>
		                </select>
		              </div>
		            </div>

					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Correo:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="correo">
							<span id="correoerror" class="text-danger"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Correo2:</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="correo2">
							<span id="correoerror" class="text-danger"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Telefono:
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
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Puesto:
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
<!-- Modal insertar/actualizar producto-->
<div class="modal fade" id="modalInsertarActualizarProductoProspecto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="modalTituloProductoProspecto">Insertar/actualizar</h4>
			</div>
			<div class="modal-body">
				<form name="form2" id="form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
					<div class="form-group">
						<label class="control-label">&Aacuterea</label>
						<select ng-model="areas" ng-change="areas_cambio()" class="form-control" style="margin-top:10px" ng-options="area.id as area.nombre for area in Areas"> 
							<option value="" ng-selected="true" disabled>Seleccione una opción</option>
						</select>
					</div>

					<div class="form-group">	
						<label class="control-label">Departamento</label>
						<select ng-model="departamentos" ng-change="departamentos_cambio()" style="margin-top:10px" class="form-control" ng-options="departamento.id as departamento.nombre for departamento in Departamentos"> 
							<option value="" ng-selected="true" disabled>Seleccione una opción</option>
						</select>
		            </div>

					<div class="form-group">
						<label class="control-label">Productos</label>
						<select ng-model="productos" ng-change="productos_cambio()" style="margin-top:10px" class="form-control" ng-options="producto.id as producto.nombre for producto in Productos"> 
							<option value="" ng-selected="true" disabled>Seleccione una opción</option>
						</select>
					</div>
					<div class="form-group">
						<label class="control-label">Descripci&oacuten del producto</label>
						<textarea rows="4" cols="50" name="desc_producto" id="desc_producto" class="form-control" style="margin-top:10px" ng-model="desc_producto" ng-disabled="true" data-parsley-id="2324">
						</textarea>	
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary" ng-click="guardarProductoProspecto()" id="btnGuardarProductoProspecto">Guardar</button>
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
<!-- Modal Confirmación-->
<div class="modal fade" id="modalConfirmacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
				<h4 class="modal-title" id="modalTitulo">Confirmación</h4>
			</div>
			<div class="modal-body">
				<form id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
					<div class="form-group text-center">
						<label class="control-label col-md-12 " >Esta seguro que desea eliminar el producto? </label>
					</div>
				
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-primary" id="btnEliminar">Aceptar</button>
			</div>
			</div>
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

<!-- Modal Generar PDF-->
<div class="modal fade" id="modalGenerarPDF" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="modalTitulo">Generar PDF</h4>
			</div>
			<div class="modal-body">
				<form id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
					<div class="form-group text-center">
						<label class="control-label col-md-12 " >Esta seguro que desea generar el PDF? </label>
					</div>
					
					<div class="form-group">
						<label class="control-label">Contactos</label>
						<select class="form-control" id="contactoprospecto1">
                  
						</select>
					</div>
					<div class="form-group">
						<label class="control-label">Domicilio</label>
						<select class="form-control" id="domicilioprospecto1">
                  
						</select>
					</div>
				</form>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
				<a type="button" class="btn btn-primary" id="btnGenerar" >Generar</a>
			</div>
			
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