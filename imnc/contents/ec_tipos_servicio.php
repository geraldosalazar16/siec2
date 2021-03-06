<span ng-controller="ec_tipos_servicio_controller">
<div class="right_col" role="main" >
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>{{titulo}}</h2></p>
       
         
              <p ng-if='modulo_permisos["catalogos"] == 1 && DatosServicio.ID_SERVICIO != 3'>
				<button type="button" id="btnNuevo" ng-click="agregar_info_auditoria()" class="btn btn-primary btn-xs btn-imnc" style="float: right;"> 
					<i class="fa fa-plus"> </i> {{titulo_boton_info_auditoria}}
				</button>
              </p>
           
        
        
          <div class="clearfix"></div>
        </div>

        <div class="x_content">
			
				<ul class="list-unstyled user_data" style="display: inline-block !important;">
					<li ><b>
					Cliente:<i> {{DatosServicio.NOMBRE_CLIENTE}}</i></b>
					</li>

					<li ><b>
					Servicio: <i> {{DatosServicio.NOMBRE_SERVICIO}}</i></b>
					</li>

                    <li ng-if="DatosServicio.ID_SERVICIO == 3" ><b>
					Curso: <i> {{DatosServicio.NOMBRE_CURSO}}</i></b>
					</li>

                    <li ng-if="DatosServicio.ID_SERVICIO == 3" ><b>
					Cantidad de Participantes: <i> {{DatosServicio.CANTIDAD_PARTICIPANTES}}</i></b>
					</li>

					<li ><b>
					Etapa: <i> {{DatosServicio.NOMBRE_ETAPA}}</i></b>
					</li>
	
					<li ><b>
					Referencia: <i> {{DatosServicio.REFERENCIA}}</i></b>
					</li>

                     <li ng-if="DatosServicio.ID_SERVICIO == 3" ><b>
                            URL Generada: <i><textarea style="width: 100%; border: transparent;" type="text" ng-model="url" ng-init="url = DatosServicio.URL_PARTICIPANTES"></textarea></i></b>
					</li>
				</ul>
				
				<div class="pull-right subir">
					<a	class="btn" ng-show="DatosServicio.ID_ETAPA_PROCESO !=13" href="./?pagina=ver_expediente&id={{DatosServicio.ID}}&id_entidad=5"> 
						<span class="labelAcordeon"	>Ver expedientes</span></a>
					<a	class="btn" ng-show="DatosServicio.ID_ETAPA_PROCESO ==13" href="./?pagina=ver_expediente&id={{DatosServicio.ID_REFERENCIA_SEG}}&id_entidad=5"> 
						<span class="labelAcordeon"	>Ver expedientes</span></a>
				</div>
				
				<div class="" role="tabpanel" data-example-id="togglable-tabs">
							<ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
								<li role="presentation" class="active"  ng-if="DatosServicio.ID_SERVICIO != 3">
									<a href="#tab_informacion" id="tab_informacion-tab"  role="tab" data-toggle="tab" aria-expanded="true" ng-click="DatosInformacion()">
                          			Informaci&oacuten </a>
								</li>
								
								<li role="presentation" class="" ng-if="DatosServicio.ID_SERVICIO == 1">
									<a href="#tab_sectores" id="tab_sectores-tab"  role="tab"  data-toggle="tab" aria-expanded="false">
									Sectores</a>
								</li>						
								
								<li role="presentation" class="" ng-if="DatosServicio.ID_SERVICIO != 3"> <!-- ng-if="DatosServicio.ID_SERVICIO == 1">-->
								<a href="#tab_sitios" id="tab_sitios-tab"  role="tab" data-toggle="tab" aria-expanded="true" >
                          			Sitios</a>
								</li>
								
								<li role="presentation" class="" ng-if="DatosServicio.ID_SERVICIO == 1 || DatosServicio.ID_SERVICIO == 2 || DatosServicio.ID_SERVICIO == 4"> <!-- ng-if="DatosServicio.ID_SERVICIO == 1">-->
								<a href="#tab_auditorias" id="tab_auditorias-tab"  role="tab" data-toggle="tab" aria-expanded="true" >
                          			Auditor&iacuteas </a>
								</li>

                                <li role="presentation" class="active" ng-if="DatosServicio.ID_SERVICIO == 3"> <!-- ng-if="DatosServicio.ID_SERVICIO == 1">-->
								<a href="#tab_participantes" id="tab_participantes-tab"  role="tab" data-toggle="tab" aria-expanded="true"  >
                          			Participantes </a>
								</li>

                                 <li role="presentation" class="" ng-if="DatosServicio.ID_SERVICIO == 3"> <!-- ng-if="DatosServicio.ID_SERVICIO == 1">-->
								<a href="#tab_configuracion" id="tab_configuracion-tab"  role="tab" data-toggle="tab" aria-expanded="true" >
                          			Configuración </a>
								</li>
								<li role="presentation" class="" ng-if="DatosServicio.ID_SERVICIO == 1 || DatosServicio.ID_SERVICIO == 2 || DatosServicio.ID_SERVICIO == 4"> <!-- ng-if="DatosServicio.ID_SERVICIO == 1">-->
								<a href="#tab_gastos_auditorias" id="tab_gastos_auditorias-tab"  role="tab" data-toggle="tab" aria-expanded="true" >
                          			Gastos Auditor&iacuteas </a>
								</li>
							</ul>
							<div id="myTabContent" class="tab-content">
								<div role="tabpanel" class="tab-pane fade active in" id="tab_informacion" aria-labelledby="home-tab">
									<ul class="list-unstyled user_data">
										<li ng-repeat="x in ValoresMetaDatos">
											{{x.NOMBRE_META_SCE}} :
											<i ng-show="x.TIPO_META_SCE!=2 && x.ID_META_SCE!=11 && x.ID_META_SCE !=12 && x.ID_META_SCE != 38 && x.ID_META_SCE !=35" > {{x.VALOR}}</i>
											<i ng-show="x.TIPO_META_SCE==2 && x.ID_META_SCE!=12" ng-init="mostrarvalorselect(x.VALOR,$index)"> {{resp[$index]}}</i>
											<i ng-if="x.ID_META_SCE == 11" ng-init="FuncionTurnos(x.VALOR)">{{num_turnos}}</i>
											<div ng-if="x.ID_META_SCE == 11" >
												
											 <div ng-repeat="y in respTurn">	
												Turno {{$index+1}}:<i ng-init="mostrarvalorselect(y.T,$index)"> {{resp[$index]}}</i><br>
												Personal de Turno {{$index+1}}:<i> {{y.PT}}</i>
											</div>	
												
											</div>
											<i ng-if="x.ID_META_SCE == 12" ng-init="FuncionTipoSolucion(x.VALOR,$index)">{{resp[$index]}}</i>
											<div	ng-if="x.ID_META_SCE == 12 && respTS == 8">
												Especificar tipo solucion: <i>{{respETS}}</i>
											</div>
											<div	ng-if="x.ID_META_SCE==35" ng-init="FuncionDiscapacidad(x.VALOR)">
												<i> {{NDisc}}</i> <br>
												Tipo de Discapacidad :<i ng-if="NDisc>0">{{TDisc}}</i>
											</div>
											<div ng-if="x.ID_META_SCE == 38" ng-init="FuncionAnoMes(x.VALOR)">
												<i ng-if="Ano < 2"> {{Ano}} A&ntildeo </i>
												<i ng-if="Ano >1"> {{Ano}} A&ntildeo </i>
												<i> con </i>
												<i ng-if="Mes <2"> {{Mes}} Mes </i>
												<i ng-if="Mes> 1"> {{Mes}} Meses </i>		
											</div>
										</li>
									</ul>
								</div>
								<div role="tabpanel" class="tab-pane fade" id="tab_sectores" aria-labelledby="profile-tab"	ng-if="DatosServicio.ID_SERVICIO == 1">
									<div class="x_title">
										<p><h2>Sectores del servicio</h2></p>
											<p ng-if='modulo_permisos["registrar"] == 1'>
											<button type="button" ng-click="agregar_editar_sector('insertar')" class="btn btn-primary btn-xs btn-imnc" style="float: right;"> 
												<i class="fa fa-plus"> </i> Agregar sector 
											</button>
										</p>
										<div class="clearfix"></div>
									</div>
									<table class="table table-striped responsive-utilities jambo_table bulk_action">
									<thead>
										<tr class="headings">
											<th class="column-title">Clave del sector</th>
											<th class="column-title">Nombre del sector</th>
								<!--			<th class="column-title">Principal</th>	-->
											<th class="column-title"></th>
										</tr>
									</thead>
									<tbody>
										<tr ng-repeat="x in SectoresServicio" class="ng-scope  even pointer">
											<td> {{x.SECTORES_ID}}-{{x.ACRONIMO}}-{{x.SECTORES_ANHIO}}</td>
											<td>{{x.NOMBRE_SECTOR}}</td>
								<!--			<td>{{x.PRINCIPAL}}</td>	-->
											<td>
												<p ng-if='modulo_permisos["registrar"] == 1'>
													<button type="button"  ng-click="agregar_editar_sector('editar',x.ID_SERVICIO_CLIENTE_ETAPA,x.ID_SECTOR)" class="btn btn-primary btn-xs btn-imnc" style="float: right;"> 
														<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar sector 
													</button>
												</p>
											</td>
											<td>
												<p ng-if='modulo_permisos["editar"] == 1'>
													<button type="button"  ng-click="eliminar_sector(x.ID_SERVICIO_CLIENTE_ETAPA,x.ID_SECTOR)" class="btn btn-primary btn-xs btn-imnc" style="float: right;"> 
														<i class="fa fa-trash" aria-hidden="true"></i> Eliminar sector 
													</button>
												</p>
											</td>
										</tr>
									</tbody>
								</table>
								</div>
								<div role="tabpanel" class="tab-pane fade" id="tab_sitios" aria-labelledby="profile-tab" ><!--ng-if="DatosServicio.ID_SERVICIO == 1" -->
									<div class="x_title">
										<p><h2>Sitios del servicio</h2></p>
											<p ng-if='modulo_permisos["registrar"] == 1'>
											<button type="button" ng-click="agregar_editar_sitio('insertar')" class="btn btn-primary btn-xs btn-imnc" style="float: right;"> 
												<i class="fa fa-plus"> </i> Agregar sitio 
											</button>
										</p>
										<div class="clearfix"></div>
									</div>
									<table class="table table-striped responsive-utilities jambo_table bulk_action">
									<thead>
										<tr class="headings">
											<th class="column-title">Tipo de servicio y domicilio</th>
											<th class="column-title">Informaci&oacuten del sitio</th>
											<th class="column-title"></th>
											<th class="column-title"></th>
										</tr>
									</thead>
									<tbody>
										<tr ng-repeat="x in SitiosServicio" class="ng-scope  even pointer" ng-if="DatosServicio.ID_SERVICIO == 2 || DatosServicio.ID_SERVICIO == 4">
											<td> {{x.ACRONIMO}}<br>{{x.NOMBRE_DOMICILIO}}</td>
											<td ng-init="CargarDatosSitiosEC(x.ID_CLIENTE_DOMICILIO)">
												<ul class="list-unstyled user_data">
													<li ng-repeat="y in DatosSitiosEC[x.ID_CLIENTE_DOMICILIO]">
														{{y.NOMBRE_META_SITIOS}}:
														<i ng-show="y.TIPO_META_SITIOS!=2"> {{y.VALOR}}</i>
														<i ng-show="y.TIPO_META_SITIOS==2" ng-init="mostrarvalorselectsitios(y.VALOR)"> {{resp1[y.VALOR]}}</i>
													</li>
												</ul>
											</td>
											<td>
												<p ng-if='modulo_permisos["registrar"] == 1'>
													<button type="button"  ng-click="agregar_editar_sitio('editar',x.ID_CLIENTE_DOMICILIO)" class="btn btn-primary btn-xs btn-imnc" style="float: right;"> 
														<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar sitio 
													</button>
												</p>
											</td>
											<td>
												<p ng-if='modulo_permisos["editar"] == 1'>
													<button type="button"  ng-click="eliminar_sitio(x.ID_CLIENTE_DOMICILIO)" class="btn btn-primary btn-xs btn-imnc" style="float: right;"> 
														<i class="fa fa-trash" aria-hidden="true"></i> Eliminar sitio 
													</button>
												</p>
											</td>
										</tr>
										<tr ng-repeat="x in SitiosServicioSG" class="ng-scope  even pointer" ng-if="DatosServicio.ID_SERVICIO == 1">
											<td>{{x.ACRONIMO}}<br>{{x.NOMBRE_DOMICILIO}}</td>
											<td ng-init="CargarDatosSitiosEC(x.ID_CLIENTE_DOMICILIO)">
												<ul class="list-unstyled user_data">
													<li>No. turnos: {{x.CANTIDAD_TURNOS}}</li>
													<li>No. total empleados: {{x.NUMERO_TOTAL_EMPLEADOS}}</li>
													<li>No. empleados para certificaci&oacuten: {{x.NUMERO_EMPLEADOS_CERTIFICACION}}</li>
													<li>Cantidad de procesos: {{x.CANTIDAD_DE_PROCESOS}}</li>
													<li>?Temporal o Fijo?: {{x.TEMPORAL_O_FIJO}}</li>
													
												</ul>
											</td>
											<td>
												<p ng-if='modulo_permisos["registrar"] == 1'>
													<button type="button"  ng-click="agregar_editar_sitio('editar',x.ID_CLIENTE_DOMICILIO)" class="btn btn-primary btn-xs btn-imnc" style="float: right;"> 
														<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar sitio 
													</button>
												</p>
											</td>
											<td>
												<p ng-if='modulo_permisos["editar"] == 1'>
													<button type="button"  ng-click="eliminar_sitio(x.ID_CLIENTE_DOMICILIO)" class="btn btn-primary btn-xs btn-imnc" style="float: right;"> 
														<i class="fa fa-trash" aria-hidden="true"></i> Eliminar sitio 
													</button>
												</p>
											</td>
										</tr>
									</tbody>
								</table>
								</div>
								<div role="tabpanel" class="tab-pane fade" id="tab_auditorias" aria-labelledby="profile-tab" ng-show="DatosServicio.ID_SERVICIO == 1 || DatosServicio.ID_SERVICIO ==2 || DatosServicio.ID_SERVICIO ==4"> <!-- ng-if="DatosServicio.ID_SERVICIO == 1" -->
									<div class="x_title">
										<p><h2>Auditor&iacuteas </h2></p>
											<p ng-if='modulo_permisos["registrar"] == 1'>
											<button type="button" ng-click="agregar_editar_auditorias('insertar')" class="btn btn-primary btn-xs btn-imnc" style="float: right;" ng-disabled="DatosServicio.NombreCiclo !=0 && DatosServicio.NombreCiclo !=  DatosServicio.CICLO"> 
												<i class="fa fa-plus"> </i> Agregar auditor&iacuteas 
											</button>
										</p>
										<div class="clearfix"></div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-1" for="Ciclos"><h4><strong>Ciclo: </strong></h4><span class="required"></span>
										</label>
										<div class="col-md-2 col-sm-2 col-xs-2">
											<select class="form-control" id="NombreCiclo" ng-model="DatosServicio.NombreCiclo" ng-options="ciclo1.VAL as ciclo1.NOMBRE  for ciclo1 in CICLO1" ng-change="cambioCiclo()">
                  
											</select>
											<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
										</div>
									</div>	
                                    <table class="table table-striped responsive-utilities jambo_table bulk_action">
									<thead>
										<tr class="headings">
											<th class="column-title">Fechas</th>
											<th class="column-title">D&iacuteas auditor</th>
											<th class="column-title">Tipo y status de auditor&iacutea </th>
											<th class="column-title">Monto</th>
											<th class="column-title">Sitios de auditor&iacutea </th>
											<th class="column-title">Grupo de auditores</th>
											<th ng-if="DatosServicio.ID_SERVICIO == 1" class="column-title">Estado Facturaci&oacuten</th>
											<!-- <th ng-if="DatosServicio.NombreCiclo==0" class="column-title">Estado Facturaci&oacuten</th> -->
											<th class="column-title"></th>
										<!--	<th class="column-title"></th>
											<th class="column-title"></th>
											<th class="column-title">Estado Dictaminaci&oacuten </th> -->
										</tr>
									</thead>
									<tbody>
										<tr ng-repeat-start="x in DatosAuditoriasSG" ng-if="DatosServicio.ID_SERVICIO == 1" class="ng-scope  even pointer"  >
											<td> 

												<table>

													<tr>
														<td>
															
															<input type='text' placeholder="Selecciona las fechas" data-parsley-id="2324" class="txtFechasAuditoria" id="txtInsertarFechas-{{x.TIPO_AUDITORIA}}-{{x.CICLO}}" ng-model="txtInsertarFechas[x.TIPO_AUDITORIA]"  data-date-format='yyyy-mm-dd' data-multiple-dates="true" date-min-limit='{{GenerarFechaHoy()}}' fecha-inicio='{{GenerarFechaHoy()}}'
															jqdatepicker />
														
														</td>
														<td>
															<button class="btn btn-primary btn-xs btn-imnc" ng-click="agregar_editar_fechasAuditoria(x.ID_SERVICIO_CLIENTE_ETAPA,x.TIPO_AUDITORIA,'insertar',x.CICLO)" >Agregar Fechas</button>
														</td>
														<td>
														</td>
													</tr>
													<tr ng-repeat = "z in x.AUDITORIA_FECHAS">
														<td>
															<input type='text' placeholder="Selecciona las fechas" data-parsley-id="2324" class="txtFechasAuditoria" id="txtFechasAuditoria{{z.ID}}" ng-model="txtFechasAuditoria[z.ID]"  data-date-format='yyyy-mm-dd' data-multiple-dates="false" date-min-limit='{{GenerarFechaHoy()}}' fecha-inicio='{{GenerarFechaInicio(txtFechasAuditoria[z.ID])}}' jqdatepicker />

														</td>
														<td>
															<button class="btn btn-primary btn-xs btn-imnc" ng-click="agregar_editar_fechasAuditoria(x.ID_SERVICIO_CLIENTE_ETAPA,x.TIPO_AUDITORIA,'editar',x.CICLO,z.ID)" >Guardar fecha</button>
														</td>
														<td>
															<button class="btn btn-primary btn-xs btn-imnc" ng-if='modulo_permisos["editar"] == 1' ng-click="eliminar_fechasAuditoria(z.ID)"><i class="fa fa-trash" aria-hidden="true"></i></button>
														</td>		
													</tr>	
												</table>
											</td>
											<td>
												<ul class="list-unstyled user_data">
													<li>
														{{x.DURACION_DIAS}}		
													</li>
													<li>
														{{x.RESTRICCIONES_DIA_AUDITOR}}		
													</li>
												</ul>
												
											</td>
											<td>Tipo: {{x.TIPO}} <br> Ciclo: {{x.CICLO}}<br>Status: {{x.STATUS}}</td>
											<td>{{x.MONTO | currency}}</td>
											<td>
													<button class="btn btn-success btn-xs btnSitiosAuditoria" ng-click="btnSitiosAuditoria(x.ID_SERVICIO_CLIENTE_ETAPA,x.TIPO_AUDITORIA,x.CICLO)" >{{x.SITIOS_ASOCIADOS}} sitios</button>
													<ul class="list-unstyled user_data">
														<li ng-repeat="n in x.RESTRICCIONES_SITIOS">
															{{n}}		
														</li>
														
													</ul>
													
											</td>
											<td> 
													<button class="btn btn-success btn-xs btnGrupoAuditoria"  ng-click="btnGrupoAuditoria(x.ID_SERVICIO_CLIENTE_ETAPA,x.TIPO_AUDITORIA,x.CICLO)" >{{x.AUDITORES_ASOCIADOS}} auditores</button>
													<ul class="list-unstyled user_data">
														<li ng-repeat="m in x.RESTRICCIONES_GRUPOS">
															{{m}}		
														</li>
														
													</ul>
											</td>
											<td ng-if="DatosServicio.ID_SERVICIO == 1">
												<ul ng-if='x.ESTADO_FACTURACION == "Sin Solicitud"' class="list-unstyled user_data">
													<li >
														{{x.ESTADO_FACTURACION}}
													</li>
												</ul>
												<ul ng-if='x.ESTADO_FACTURACION != "Sin Solicitud"' class="list-unstyled user_data">
													<li ng-repeat = "hhh in x.ESTADO_FACTURACION">
													{{$index+1}}- {{hhh.ESTATUS}}
													</li>
												</ul>
													
											</td>
											<!-- <td ng-if="x.importe>0" >{{x.estado_fact}} por {{x.importe | currency}}</td> -->
											<td>
												<div class="btn-group">
													<button type="button" class="btn btn-primary btn-xs btn-imnc " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opciones   
														<span class="caret"></span>
														 <span class="sr-only">Toggle Dropdown</span>
													</button>
													<ul class="dropdown-menu">
														<li>
															<a ng-click="agregar_editar_auditorias('editar',x.ID_SERVICIO_CLIENTE_ETAPA,x.TIPO_AUDITORIA,x.CICLO)"> <span class="labelAcordeon" >Editar auditor&iacuteas </span>  </a>
														</li>
														<li>
															<a ng-click='modal_generar_notificacion(DatosServicio.ID_SERVICIO,x.ID_SERVICIO_CLIENTE_ETAPA,x.TIPO_AUDITORIA,x.CICLO)'> <span class="labelAcordeon" > Notificaci&oacuten  </span>  </a>
														</li>
														<li ng-if='modulo_permisos["registrar"] == 1 && x.SOLICITUD'>
															<a ng-click='openModalEditarSolicitud(x.TIPO_AUDITORIA,x.CICLO,x.SOLICITUD)'> <span class="labelAcordeon" > Editar Facturación  </span>  </a>
														</li>
														<li ng-if='modulo_permisos["registrar"] == 1 && !x.SOLICITUD'>
															<a ng-click='openModalCrearSolicitud(x.TIPO_AUDITORIA,x.CICLO)'> <span class="labelAcordeon" > Solicitar Facturación  </span>  </a>
														</li>
														<li ng-if="x.ESTADO_DICTAMINACION == 'Pendiente Solicitud'">
															<a  ng-click='modal_dictaminacion(x.ID_SERVICIO_CLIENTE_ETAPA,x.TIPO_AUDITORIA,x.CICLO)' > <span class="labelAcordeon" >Solicitar Dictaminaci&oacuten </span>  </a>
														</li>
													</ul>
												</div>
												<p ng-show="x.ESTADO_DICTAMINACION =='0'">Estado Dictaminaci&oacuten: Solicitud enviada</p>
												<p ng-show="x.ESTADO_DICTAMINACION =='1'">Estado Dictaminaci&oacuten: Dictaminaci&oacuten aprobada</p>
												<p ng-show="x.ESTADO_DICTAMINACION =='2'">Estado Dictaminaci&oacuten: Dictaminaci&oacuten negada</p>
												<!--<p ng-if='modulo_permisos["registrar"] == 1'>
													<button type="button"  ng-click="agregar_editar_auditorias('editar',x.ID_SERVICIO_CLIENTE_ETAPA,x.TIPO_AUDITORIA,x.CICLO)" class="btn btn-primary btn-xs btn-imnc" style="float: right;"> 
														<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar auditor&iacuteas 
													</button>
												</p> -->
											</td>
										<!--	<td>
												<p ng-if='modulo_permisos["registrar"] == 1'>
													<button type="button"  ng-click='modal_generar_notificacion(DatosServicio.ID_SERVICIO,x.ID_SERVICIO_CLIENTE_ETAPA,x.TIPO_AUDITORIA,x.CICLO)' class="btn btn-primary btn-xs btn-imnc" style="float: right;">
														<i class="fa fa-download" aria-hidden="true"></i> Notificaci&oacuten 
													</button>
												</p>
											</td>
                                            <td>
												<p ng-if='modulo_permisos["registrar"] == 1 && x.SOLICITUD'>
													<button type="button"  ng-click='openModalEditarSolicitud(x.TIPO_AUDITORIA,x.CICLO,x.SOLICITUD)' class="btn btn-primary btn-xs btn-imnc" style="float: right;">
														<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar Facturación
													</button>
												</p>
                                                <p ng-if='modulo_permisos["registrar"] == 1 && !x.SOLICITUD'>
													<button type="button"  ng-click='openModalCrearSolicitud(x.TIPO_AUDITORIA,x.CICLO)' class="btn btn-primary btn-xs btn-imnc" style="float: right;">
														<i class="fa fa-send-o" aria-hidden="true"></i> Solicitar Facturación
													</button>
												</p>
											</td>
											<td>
												
												<p ng-if='modulo_permisos["registrar"] == 1'>
													<button type="button"  ng-click='modal_dictaminacion(x.ID_SERVICIO_CLIENTE_ETAPA,x.TIPO_AUDITORIA,x.CICLO)' class="btn btn-primary btn-xs btn-imnc" style="float: right;" ng-disabled="x.ESTADO_DICTAMINACION != 'Pendiente Solicitud'"> 
														<i class="fa fa-send" aria-hidden="true"></i> Solicitar Dictaminaci&oacuten 
													</button>
												</p>
												<p ng-show="x.ESTADO_DICTAMINACION =='0'">Solicitud enviada</p>
												<p ng-show="x.ESTADO_DICTAMINACION =='1'">Dictaminaci&oacuten aprobada</p>
												<p ng-show="x.ESTADO_DICTAMINACION =='2'">Dictaminaci&oacuten negada</p>
												
											</td> -->
										</tr>
										
										<!--++++++++++++++++++++Sitios de Auditoria++++++++++++++++++++-->
										<tr  ng-if="DatosServicio.ID_SERVICIO == 1" ng-show="x.mostrandoSectoresSitios">
											<td colspan="13">
												<table class="table subtable">
													<caption>Sitios de auditor&iacutea
														<p ng-if='modulo_permisos["registrar"] == 1'>
															<button type="button" class="btn btn-success btn-xs btnInsertaSitiosAuditoria" ng-click="btnInsertaSitiosAuditoria(id_servicio_cliente_etapa,x.TIPO_AUDITORIA,x.ID_CLIENTE_DOMICILIO,x.CICLO)" style="float: right;"> 
																<i class="fa fa-plus" aria-hidden="true"></i>  Agregar sitio de auditor&iacuteas
															</button>
														</p>
													</caption>
													<thead>
														 <tr>
															<th>Clave del Tipo de Servicio</th>
															<th>Clave Domicilio del Cliente</th>
															<th>Cantidad de Turnos</th>
															<th>Numero Total de Empleados</th>
															<th>Numero de Empleados con Certificacion</th>
															<th>Cantidad de Procesos</th>
															<th>Duracion</th>
															<th>Dias de Auditoria</th>
															<th></th>
														</tr>
													</thead>
													<tbody>
														<tr ng-repeat="y in x.SITIOS">
															<td> {{y.NOMBRE}} </td>
															<td> {{y.NOMBRE_DOMICILIO}} </td>
															<td> {{y.CANTIDAD_TURNOS}} </td>
															<td> {{y.NUMERO_TOTAL_EMPLEADOS}} </td>
															<td> {{y.NUMERO_EMPLEADOS_CERTIFICACION}} </td>
															<td> {{y.CANTIDAD_DE_PROCESOS}} </td>
															<td> {{y.TEMPORAL_O_FIJO}} </td>
															<td ng-if="y.DURACION_DIAS > 0" > {{y.DURACION_DIAS}} </td>
															<td ng-if="y.DURACION_DIAS == 0" > N/A </td>
															<td> 
																<p ng-if='modulo_permisos["editar"] == 1'>
																	<button class="btn btn-primary btn-xs btnEliminaSitioAuditoria" ng-click="eliminar_sitio_auditoria(id_servicio_cliente_etapa,x.TIPO_AUDITORIA,y.ID_CLIENTE_DOMICILIO,x.CICLO)"><i class="fa fa-trash" aria-hidden="true"></i></button>
																</p>
															</td>
														</tr>
													</tbody>
												</table>
											</td>
										</tr>
										<!--++++++++++++++++++++++++++++++++++++++++++++++++++++++++++-->
										<!--++++++++++++++++++++GRUPO DE AUDITORES++++++++++++++++++-->
										<tr ng-repeat-end ng-if="DatosServicio.ID_SERVICIO == 1" ng-show="x.mostrandoSectoresAuditor" >
											<td colspan="13">
												<table class="table subtable">
													<caption>Grupo de auditores
														<p ng-if='modulo_permisos["registrar"] == 1'>
															<button type="button" class="btn btn-success btn-xs btnInsertaGrupoAuditoria" ng-click="btnInsertaGrupoAuditoria(id_servicio_cliente_etapa,x.TIPO_AUDITORIA,x.CICLO)"  style="float: right;"> 
																<i class="fa fa-plus" aria-hidden="true"></i>  Agregar auditor a grupo
															</button>
														</p>
													</caption>
													<thead>
														 <tr>
															<th>Nombre completo</th>
															<th>Email</th>
															<th>Registro</th>
															<th>Rol en auditor&iacutea </th>
															<th>Tipo de Servicio </th>
															<th>Fechas asignadas </th>
															<th></th>
														</tr>
													</thead>
													<tbody>
														<tr ng-repeat="w in x.AUDITORES">
															<td>{{w.NOMBRE}} {{w.APELLIDO_PATERNO}} {{w.APELLIDO_MATERNO}} </td>
															<td> {{w.EMAIL}} </td>
															<td> {{w.REGISTRO}} </td>
															<td> {{w.ACRONIMO}} </td>
															<td> {{w.NOMBRE_SERVICIO}} </td>
															<td>
																<table>
																	<tr>
																		<td>
																			<input type='text' placeholder="Fechas" data-parsley-id="2324" class="txtFechasAuditoria" 
																			id="txtInsertarFechasGrupo-{{x.TIPO_AUDITORIA}}-{{x.CICLO}}-{{w.ID_PERSONAL_TECNICO_CALIF}}" 
																			ng-model="txtInsertarFechasGrupo[w.ID_PERSONAL_TECNICO_CALIF]"  data-date-format='yyyy-mm-dd' 
																			data-multiple-dates="true" 
																			data-days-allowed="{{GenerarArregloFecha(x.AUDITORIA_FECHAS)}}" 
																			fecha-inicio='{{GenerarFechaHoy()}}'
																			jqdatepicker />
																		
																		</td>
																		<td>
																			<button class="btn btn-primary btn-xs btn-imnc" ng-click="agregar_editar_fechasAuditoriaGrupo(x.ID_SERVICIO_CLIENTE_ETAPA,x.TIPO_AUDITORIA,x.CICLO,w.ID_PERSONAL_TECNICO_CALIF,w.AUDITORES_NORMAS)" >Agregar Fechas</button>
																		</td>
																	</tr>
																</table>
																
																
				
															<ul class="list-unstyled user_data">
																<li ng-repeat="r in x.AUDITORES_FECHAS[w.ID_PERSONAL_TECNICO_CALIF]">
																	<table>
																		<tr>
																			<td>{{mostrarFecha(r.FECHA)}} {{mostrarNorma(r.ID_NORMA)}}</td>																	
																			<td>
																				<p ng-if='modulo_permisos["editar"] == 1'>
																				<button class="btn btn-primary btn-xs btnEliminaFechaGrupoAuditoria" ng-click="eliminar_fechasAuditoriaGrupo(r.ID)"><i class="fa fa-trash" aria-hidden="true"></i></button>
																				</p>
																			</td>	
																		</tr>
																	</table>
																</li>
															</ul>	
															</td>
															<td> 
																<p ng-if='modulo_permisos["editar"] == 1'>
																	<button class="btn btn-primary btn-xs btnEliminaGrupoAuditoria" ng-click="eliminar_grupo_auditoria(id_servicio_cliente_etapa,x.TIPO_AUDITORIA,x.CICLO,w.ID_PERSONAL_TECNICO_CALIF)"><i class="fa fa-trash" aria-hidden="true"></i></button>
																</p>
															</td>
														</tr>
													</tbody>
												</table>
											</td>
										</tr>
										<tr ng-repeat-start="xx in DatosAuditoriasEC" ng-if="DatosServicio.ID_SERVICIO == 2 || DatosServicio.ID_SERVICIO == 4" class="ng-scope  even pointer"  >
											<td>	
												<table>
													<tr>
														<td>
															<input type='text' placeholder="Selecciona las fechas" data-parsley-id="2324" class="txtFechasAuditoria" id="txtInsertarFechas-{{xx.TIPO_AUDITORIA}}-{{xx.CICLO}}" ng-model="txtInsertarFechas[xx.TIPO_AUDITORIA]"  data-date-format='yyyy-mm-dd' data-multiple-dates="true"  fecha-inicio='{{GenerarFechaHoy()}}' jqdatepicker />
														
														</td> 
															
														<td>
															<button class="btn btn-primary btn-xs btn-imnc" ng-click="agregar_editar_fechasAuditoria(xx.ID_SERVICIO_CLIENTE_ETAPA,xx.TIPO_AUDITORIA,'insertar',xx.CICLO)" >Agregar Fechas</button>
														</td>
														<td>
														</td>
													</tr>
													<tr ng-repeat = "z in xx.AUDITORIA_FECHAS">
														<td>		
															<input type='text' placeholder="Selecciona las fechas" data-parsley-id="2324" class="txtFechasAuditoria" id="txtFechasAuditoria{{z.ID}}" ng-model="txtFechasAuditoria[z.ID]"  data-date-format='yyyy-mm-dd' data-multiple-dates="false" fecha-inicio='{{GenerarFechaInicio(txtFechasAuditoria[z.ID])}}' jqdatepicker />
														
														</td>
														<td>
															<button class="btn btn-primary btn-xs btn-imnc" ng-click="
															agregar_editar_fechasAuditoria(xx.ID_SERVICIO_CLIENTE_ETAPA,xx.TIPO_AUDITORIA,'editar',xx.CICLO,z.ID)" >Guardar fecha</button>
														</td>
														<td>
															<button class="btn btn-primary btn-xs btn-imnc" ng-if='modulo_permisos["editar"] == 1' ng-click="eliminar_fechasAuditoria(z.ID)"><i class="fa fa-trash" aria-hidden="true"></i></button>
														</td>		
													</tr>	
												</table>			
											</td>
											<td>
												<ul class="list-unstyled user_data">
													<li>
														{{xx.DURACION_DIAS}}		
													</li>
												
												</ul>
												
											</td>
											<td>Tipo: {{xx.TIPO}} <br> Ciclo: {{xx.CICLO}}<br>Status: {{xx.STATUS}}</td>
											<!-- faltaba esta columna para monto -->
											<td></td>
											<td>
													<button class="btn btn-success btn-xs btnSitiosAuditoria" ng-click="btnSitiosAuditoriaEC(xx.ID_SERVICIO_CLIENTE_ETAPA,xx.TIPO_AUDITORIA,xx.CICLO)" >{{xx.SITIOS_ASOCIADOS}} sitios</button>
												<!--	<ul class="list-unstyled user_data">
														<li ng-repeat="n in x.RESTRICCIONES_SITIOS">
															{{n}}		
														</li>
														
													</ul>
													-->
											</td>
											<td> 
													<button class="btn btn-success btn-xs btnGrupoAuditoria"  ng-click="btnGrupoAuditoriaEC(xx.ID_SERVICIO_CLIENTE_ETAPA,xx.TIPO_AUDITORIA,xx.CICLO)" >{{xx.AUDITORES_ASOCIADOS}} auditores</button>
												<!--	<ul class="list-unstyled user_data">
														<li ng-repeat="m in x.RESTRICCIONES_GRUPOS">
															{{m}}		
														</li>
														
													</ul>	-->
											</td>
											<td>
												<p ng-if='modulo_permisos["registrar"] == 1'>
													<button type="button"  ng-click="agregar_editar_auditorias('editar',xx.ID_SERVICIO_CLIENTE_ETAPA,xx.TIPO_AUDITORIA,xx.CICLO)" class="btn btn-primary btn-xs btn-imnc" style="float: right;"> 
														<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar auditor&iacuteas 
													</button>
												</p>
											</td>
											<td>
												<p ng-if='modulo_permisos["registrar"] == 1'>
													<button type="button"  ng-click="modal_generar_notificacion(DatosServicio.ID_SERVICIO,xx.ID_SERVICIO_CLIENTE_ETAPA,xx.TIPO_AUDITORIA,xx.CICLO)" class="btn btn-primary btn-xs btn-imnc" style="float: right;">
														<i class="fa fa-download" aria-hidden="true"></i> Notificaci&oacuten 
													</button>
												</p>
											</td>
                                            <td>

											</td>
											<td>
												
												<p ng-if='modulo_permisos["registrar"] == 1'>
													<button type="button"  ng-click='modal_dictaminacion(xx.ID_SERVICIO_CLIENTE_ETAPA,xx.TIPO_AUDITORIA,xx.CICLO)' class="btn btn-primary btn-xs btn-imnc" style="float: right;" ng-disabled="xx.ESTADO_DICTAMINACION != 'Pendiente Solicitud'"> 
														<i class="fa fa-send" aria-hidden="true"></i> Solicitar Dictaminaci&oacuten 
													</button>
												</p>
												<p ng-show="xx.ESTADO_DICTAMINACION =='0'">Solicitud enviada</p>
												<p ng-show="xx.ESTADO_DICTAMINACION =='1'">Dictaminaci&oacuten aprobada</p>
												<p ng-show="xx.ESTADO_DICTAMINACION =='2'">Dictaminaci&oacuten negada</p>
												
											</td>
										</tr>
										<!--++++++++++++++++++++Sitios de Auditoria++++++++++++++++++++-->
										<tr  ng-if="DatosServicio.ID_SERVICIO == 2 || DatosServicio.ID_SERVICIO == 4" ng-show="xx.mostrandoSectoresSitios">
											<td colspan="13">
												<table class="table subtable">
													<caption>Sitios de auditor&iacutea
														<p ng-if='modulo_permisos["registrar"] == 1'>
															<button type="button" class="btn btn-success btn-xs btnInsertaSitiosAuditoria" ng-click="btnInsertaSitiosAuditoriaEC(id_servicio_cliente_etapa,xx.TIPO_AUDITORIA,xx.ID_CLIENTE_DOMICILIO,xx.CICLO)" style="float: right;"> 
																<i class="fa fa-plus" aria-hidden="true"></i>  Agregar sitio de auditor&iacuteas
															</button>
														</p>
													</caption>
													<thead>
														 <tr>
															<th>Clave del Tipo de Servicio</th>
															<th>Nombre Domicilio del Cliente</th>
															<th>Dias de Auditoria</th>
															<th>Datos</th>
															<th></th>
														</tr>
													</thead>
													<tbody>
														<tr ng-repeat="y in xx.SITIOS">
															<td> {{y.NOMBRE}} </td>
															<td> {{y.NOMBRE_DOMICILIO}} </td>
															<td ng-if="y.DURACION_DIAS > 0" > {{y.DURACION_DIAS}} </td>
															<td ng-if="y.DURACION_DIAS == 0" > N/A </td>
															<td> 
																<ul class="list-unstyled user_data">
																	<li  ng-repeat = "f in y.DATOS">
																		{{f.NOMBRE_META_SITIOS}}: 
																		<i ng-show="f.TIPO_META_SITIOS!=2"> {{f.VALOR}}</i>
																		<i ng-show="f.TIPO_META_SITIOS==2" ng-init="mostrarvalorselectsitios(f.VALOR)"> {{resp1[f.VALOR]}}</i>
																	</li>
																</ul>
															</td>
															<td> 
																<p ng-if='modulo_permisos["editar"] == 1'>
																	<button class="btn btn-primary btn-xs btnEliminaSitioAuditoria" ng-click="eliminar_sitio_auditoria(xx.ID_SERVICIO_CLIENTE_ETAPA,xx.TIPO_AUDITORIA,y.ID_CLIENTE_DOMICILIO,xx.CICLO)"><i class="fa fa-trash" aria-hidden="true"></i></button>
																</p>
															</td>
														</tr>
													</tbody>
												</table>
											</td>
										</tr>	

										<!--++++++++++++++++++++GRUPO DE AUDITORES++++++++++++++++++-->
										<tr ng-repeat-end ng-if="DatosServicio.ID_SERVICIO == 2 || DatosServicio.ID_SERVICIO == 4" ng-show="xx.mostrandoSectoresAuditor">	
											<td colspan="13">
												<table class="table subtable">
													<caption>Grupo de auditores
														<p ng-if='modulo_permisos["registrar"] == 1'>
															<button type="button" class="btn btn-success btn-xs btnInsertaGrupoAuditoriaEC" ng-click="btnInsertaGrupoAuditoriaEC(id_servicio_cliente_etapa,xx.TIPO_AUDITORIA,xx.CICLO)"  style="float: right;"> 
																<i class="fa fa-plus" aria-hidden="true"></i>  Agregar auditor a grupo
															</button>
														</p>
													</caption>
													<thead>
														 <tr>
															<th>Nombre completo</th>
															<th>Email</th>
															<th>Registro</th>
															<th>Rol en auditor&iacutea </th>
															<th>Tipo de Servicio </th>
															<th>Fechas asignadas </th>
															<th></th>
														</tr>
													</thead>
													<tbody>
														<tr ng-repeat="w in xx.AUDITORES">
															<td>{{w.NOMBRE}} {{w.APELLIDO_PATERNO}} {{w.APELLIDO_MATERNO}} </td>
															<td> {{w.EMAIL}} </td>
															<td> {{w.REGISTRO}} </td>
															<td> {{w.ACRONIMO}} </td>
															<td> {{w.NOMBRE_SERVICIO}} </td>
															<td>
																<table>
																	<tr>
																		<td>
																			<input type='text' placeholder="Fechas" data-parsley-id="2324" class="txtFechasAuditoria" 
																			id="txtInsertarFechasGrupo-{{xx.TIPO_AUDITORIA}}-{{xx.CICLO}}-{{w.ID_PERSONAL_TECNICO_CALIF}}" 
																			ng-model="txtInsertarFechasGrupo[w.ID_PERSONAL_TECNICO_CALIF]"  data-date-format='yyyy-mm-dd' 
																			data-multiple-dates="true" 
																			data-days-allowed="{{GenerarArregloFecha(xx.AUDITORIA_FECHAS)}}" 
																			fecha-inicio='{{GenerarFechaHoy()}}'
																			jqdatepicker />
																	
																		</td>
																		<td>
																			<button class="btn btn-primary btn-xs btn-imnc" ng-click="agregar_editar_fechasAuditoriaGrupo(xx.ID_SERVICIO_CLIENTE_ETAPA,xx.TIPO_AUDITORIA,xx.CICLO,w.ID_PERSONAL_TECNICO_CALIF)" >Agregar Fechas</button>
																		</td>
																	</tr>
																</table>
																
				
															<ul class="list-unstyled user_data">
																<li ng-repeat="r in xx.AUDITORES_FECHAS[w.ID_PERSONAL_TECNICO_CALIF]">
																	{{mostrarFecha(r.FECHA)}}
														
																</li>
															</ul>	
															</td>
															<td> 
																<p ng-if='modulo_permisos["editar"] == 1'>
																	<button class="btn btn-primary btn-xs btnEliminaGrupoAuditoria" ng-click="eliminar_grupo_auditoria(id_servicio_cliente_etapa,xx.TIPO_AUDITORIA,xx.CICLO,w.ID_PERSONAL_TECNICO_CALIF)"><i class="fa fa-trash" aria-hidden="true"></i></button>
																</p>
															</td>
														</tr>	
													</tbody>
												</table>
											</td>
										</tr>										
										<!--++++++++++++++++++++++++++++++++++++++++++++++++++++++++++-->
									</tbody>

									
								</table>
								</div>
													

                                <div role="tabpanel" class="tab-pane fade active in" id="tab_participantes" aria-labelledby="home-tab" ng-if="DatosServicio.ID_SERVICIO == 3" ><!--ng-if="DatosServicio.ID_SERVICIO == 1" -->
                                    <div class="x_title">
                                        <p><h2>Participantes</h2></p>
                                        <p ng-if='modulo_permisos["registrar"] == 1'>
											<button type="button" ng-click="openModalInsertarModificarParticipante('insertar')" class="btn btn-primary btn-xs btn-imnc" style="float: right;">
												<i class="fa fa-plus"> </i> Agregar Participante
											</button>
										</p>
										<div class="clearfix"></div></div>

                                    <table class="table table-striped responsive-utilities jambo_table bulk_action">
									<thead>
										<tr class="headings">
											<th class="column-title">Información del Participante</th>
											<th class="column-title"></th>
										</tr>
									</thead>
									<tbody>
                                        <tr ng-repeat="x in participantes" class="ng-scope  even pointer" ng-if="DatosServicio.ID_SERVICIO == 3">
                                            <td>
                                                <table  style="background-color: transparent">
                                                    <tr>
                                                        <td>Razón Social: <strong>{{x.RAZON_ENTIDAD}}</strong> </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Correo electrónico: <strong>{{x.EMAIL}}</strong></td>
                                                     </tr>
                                                    <tr>
                                                        <td>Teléfono: <strong>{{x.TELEFONO}}</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td>CURP del participante: <strong>{{x.CURP}}</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td>RFC de su organización: <strong>{{x.RFC}}</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Estado del que nos visita: <strong>{{x.ID_ESTADO}}</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Ejecutivo comercial que le atendió: <strong>{{x.EJECUTIVO}}</strong></td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td>
                                                <button type="button"  ng-click="openModalInsertarModificarParticipante('editar',x.ID)"  class="btn btn-primary btn-xs btn-imnc" style="float: right;">
												<i class="glyphicon glyphicon-edit"> </i> Editar Participante
											</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    </table>

                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="tab_configuracion" aria-labelledby="profile-tab" ><!--ng-if="DatosServicio.ID_SERVICIO == 1" -->
                                    <div class="x_title">
                                        <p><h2>Agregar datos adicionales del Curso</h2></p>
                                        <button type="submit"  class="btn btn-primary pull-right" ng-click="submitFormConfiguracion(formDataConfiguracion)" ng-if="flag==true">Guardar</button>
                                        <button type="submit"  class="btn btn-primary pull-right" ng-click="showFormConfiguracion()" ng-if="flag==false">Editar</button>

										<div class="clearfix"></div>
                                    </div>
                                     <div ng-if='modulo_permisos["registrar"] == 1'>
                                         <form  name="formConfiguracion" class="form-horizontal" role="form">
                                             <div class="form-group">
                                                <label for="fecha_fin" class="col-sm-2 control-label">Sitio*</label>
                                                <div class="col-xs-6 col-md-4">
                                                  <select ng-model="formDataConfiguracion.selectSitio" ng-options="sitio.ID as sitio.NOMBRE_DOMICILIO+'  CALLE: '+sitio.CALLE  for sitio in sitios"
                                                          class="form-control" id="selectSitio" name="selectSitio" required
                                                          ng-class="{ error: formConfiguracion.select_curso.$error.required && !formConfiguracion.$pristine}" ng-show="flag==true" >
                                                        <option value="">---Seleccione un Sitio---</option>
                                                    </select>
                                                    <span id="selectSitioerror" class="text-danger"></span>
                                                    <label  class="control-label" style="color: #1b1613;text-align: left;" ng-show="flag==false">{{configuracion.NOMBRE_SITIO}}</label>
                                                </div>
                                              </div>
                                              <div class="form-group">
                                                <label for="fecha_inicio_participante" class="col-sm-2 control-label">Fecha Inicio*</label>
                                                <div class="col-xs-6 col-md-4">
                                                  <input type="text" class="form-control" id="fecha_inicio_participante" name="fecha_inicio_participante" ng-model="formDataConfiguracion.fecha_inicio_participante" placeholder="dia / mes / año"  required
                                                         ng-class="{ error: formConfiguracion.fecha_inicio_participante.$error.required && !formConfiguracion.$pristine}" ng-show="flag==true">
									              <span id="fechainicioerror" class="text-danger"></span>
                                                  <label  class="control-label" style="color: #1b1613;text-align: left;" ng-show="flag==false">{{configuracion.FECHA_INICIO}}</label>
                                                </div>
                                              </div>
                                             <div class="form-group">
                                                <label for="fecha_fin_participante" class="col-sm-2 control-label">Fecha Fin*</label>
                                                <div class="col-xs-6 col-md-4">
                                                  <input type="text" class="form-control" id="fecha_fin_participante"  name="fecha_fin_participante"  ng-model="formDataConfiguracion.fecha_fin_participante" placeholder="dia / mes / año" required
                                                         ng-class="{ error: formConfiguracion.fecha_fin_participante.$error.required && !formConfiguracion.$pristine}" ng-show="flag==true">
									              <span id="fechafinerror" class="text-danger"></span>
                                                    <label  class="control-label" style="color: #1b1613;text-align: left;" ng-show="flag==false">{{configuracion.FECHA_FIN}}</label>
                                                </div>
                                              </div>
                                             <div class="form-group">
                                                <label  class="col-sm-2 control-label">Instructor*</label>
                                                <div class="col-xs-12 col-md-8">
                                                  <table class="table" style="background-color: transparent" ng-show="flag==true" >
                                                        <tr>
                                                            <td style="width: 70%;"><input  id="btnInstructor" type="button" class="form-control btn" ng-click="openModalMostarInst()" value="Selecciona un Instructor" ></td>
                                                            <td style="width: 30%;">
                                                            <div class="checkbox-inline"  >
                                                            <label>
                                                              <input  id="chckVerTodos" type="checkbox" ng-model="formDataConfiguracion.chckVerTodos" class="checkbox"  value="true" > Ver Todos
                                                            </label>
                                                          </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2"><span id="instructorerror" class="text-danger"></span></td>
                                                        </tr>
                                                    </table>
                                                    <label  class="control-label" style="color: #1b1613;text-align: left;" ng-show="flag==false">{{configuracion.NOMBRE_INSTRUCTOR}}</label>
                                                </div>
                                              </div>
                                         </form>
										</div>
                                </div>
								<div role="tabpanel" class="tab-pane fade" id="tab_gastos_auditorias" aria-labelledby="profile-tab"	ng-if="DatosServicio.ID_SERVICIO == 1 || DatosServicio.ID_SERVICIO == 2 || DatosServicio.ID_SERVICIO == 4">
									<div class="x_title">
										<p><h2>Gastos de auditor&iacuteas </h2></p>
									
										<div class="clearfix"></div>
									</div>
									<div>
										<ul class="list-unstyled user_data" style="display: inline-block !important;">
											<li>
												<p><b>
												Total Vi&aacuteticos Servicio:</b> {{GastosAuditorias.TOTAL_VIATICOS | currency}} </p>
												
											</li>

											<li><b>
												Total Gastos Servicio:</b> &nbsp;&nbsp;{{GastosAuditorias.TOTAL_GASTOS | currency}}
											</li>
										</ul>
									</div> 
									
									<div class='panel-group' id='accordion' role='tablist' aria-multiselectable='true'>
										<div class='panel panel-default' ng-repeat="xyz in GastosAuditorias.AUDITORIAS">
											<div class='panel-heading' id='heading{{$index}}' role='tab'>
												<ul class="nav navbar-right panel_toolbox">
												<li ng-show="xyz.mostrandoSectoresGastosAuditor == false">
													<a class="collapse-link" ng-click="changePrueba(xyz.ID_SERVICIO_CLIENTE_ETAPA,xyz.TIPO_AUDITORIA,xyz.CICLO)"><i class="fa fa-chevron-down"></i></a>
												</li>
												<li ng-show="xyz.mostrandoSectoresGastosAuditor == true">
													<a class="collapse-link" ng-click="changePrueba(xyz.ID_SERVICIO_CLIENTE_ETAPA,xyz.TIPO_AUDITORIA,xyz.CICLO)"><i class="fa fa-chevron-up"></i></a>
												</li>
											</ul>
												<b>Ciclo:</b> {{xyz.CICLO}}<br>
												<b>Tipo Auditoria:</b> {{xyz.TIPO}} <br>
												<b>Total Gastos:</b> {{xyz.TOTAL_GASTOS | currency}}<br>
												<b>Total Vi&aacuteticos:</b> {{xyz.TOTAL_VIATICOS | currency}} <br>
												<b>Diferencia:</b> {{xyz.TOTAL_GASTOS-xyz.TOTAL_VIATICOS | currency}}<br>
												<p ng-if='modulo_permisos["registrar"] == 1'>
														<button type="button" ng-click="agregar_editar_viaticos(xyz.ID_SERVICIO_CLIENTE_ETAPA,xyz.TIPO_AUDITORIA,xyz.CICLO)" class="btn btn-primary btn-xs btn-imnc" style="float: left;"> 
															<i class="fa fa-plus"> </i> Editar vi&aacuteticos auditor&iacuteas 
														</button>
													</p> <br>
											</div>
											<div ng-show="xyz.mostrandoSectoresGastosAuditor == true" role='tabpanel' aria-labelledby='heading{{$index}}'>
												<div class='panel-body'>
													<p>
														<h2>Auditores </h2>
													</p>
															
													<table class="table table-striped responsive-utilities jambo_table bulk_action">
														<thead>
															<tr class="headings">
																<th class="column-title">Auditor</th>
																<th class="column-title" ng-repeat="xyz2 in CATALOGO_GASTOS">{{xyz2.NOMBRE}}</th>
																<th class="column-title">Total auditor</th>	
																<th class="column-title">Total auditor sin IVA</th>															
																<th class="column-title"></th>
															</tr>
														</thead>
														<tbody>
															<tr ng-repeat='xyz1 in xyz.AUDITORES' class="ng-scope  even pointer">
																<td>{{xyz1.NOMBRE}} {{xyz1.APELLIDO_MATERNO}} {{xyz1.APELLIDO_PATERNO}}</td>
																<td ng-repeat="xyz3 in xyz1.MONTO">{{xyz3.VALOR | currency}}</td>
															
																<td>{{xyz1.TOTAL_AUDITOR | currency}}</td>
																<td>{{xyz1.TOTAL_AUDITOR_SIN_IVA | currency}}</td>
																<td>
																	<p ng-if='modulo_permisos["registrar"] == 1'>
																		<button type="button"  ng-click="agregar_editar_gastos('auditor',xyz.ID_SERVICIO_CLIENTE_ETAPA,xyz.TIPO_AUDITORIA,xyz.CICLO,xyz1.ID_PERSONAL_TECNICO_CALIF)" class="btn btn-primary btn-xs btn-imnc" style="float: right;"> 
																			<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar gastos 
																		</button>
																	</p>
																</td>
																
															</tr>
														</tbody>
													</table>
													<p><h2>Experto T&eacutecnico </h2></p>
										
													<table class="table table-striped responsive-utilities jambo_table bulk_action">
														<thead>
															<tr class="headings">
																<th class="column-title">Experto T&eacutecnico </th>
																<th class="column-title" ng-repeat="xyz2 in CATALOGO_GASTOS">{{xyz2.NOMBRE}}</th>
																<th class="column-title">Total experto t&eacutecnico </th>	
																<th class="column-title">Total experto t&eacutecnico sin IVA</th>																	
																<th class="column-title"></th>
															</tr>
														</thead>
														<tbody>
															<tr ng-repeat='xyz1 in xyz.EXP_TECNICOS' class="ng-scope  even pointer">
																<td>{{xyz1.NOMBRE}} {{xyz1.APELLIDO_MATERNO}} {{xyz1.APELLIDO_PATERNO}}</td>
																<td ng-repeat="xyz3 in xyz1.MONTO">{{xyz3.VALOR | currency}}</td>
																<td>{{xyz1.TOTAL_AUDITOR | currency}}</td>
																<td>{{xyz1.TOTAL_AUDITOR_SIN_IVA | currency}}</td>
																<td>
																	<p ng-if='modulo_permisos["registrar"] == 1'>
																		<button type="button"  ng-click="agregar_editar_gastos('exptec',xyz.ID_SERVICIO_CLIENTE_ETAPA,xyz.TIPO_AUDITORIA,xyz.CICLO,xyz1.ID_PERSONAL_TECNICO_CALIF)" class="btn btn-primary btn-xs btn-imnc" style="float: right;"> 
																			<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar gastos 
																		</button>
																	</p>
																</td>
																
															</tr>
														</tbody>
													</table>
												</div>	
											</div>
										</div>
										
									</div>
								</div>		
				
        </div>
      </div>
    </div>
  </div>
 </div> 
 <?php 
 
  include "ec_tipos_servicio/modal_agregar_informacion_auditoria.php";
  include "ec_tipos_servicio/modal_inserta_actualiza_sectores.php";
  include "ec_tipos_servicio/modal_inserta_actualiza_sitios.php";
  include "ec_tipos_servicio/modal_inserta_actualiza_sitios_ec.php";
  include "ec_tipos_servicio/modal_inserta_actualiza_auditoria.php";
  include "ec_tipos_servicio/modal_inserta_actualiza_auditoria_ec.php";
 
  include "ec_tipos_servicio/modal_explorar_sitios_auditoria.php";
  include "ec_tipos_servicio/modal_explorar_sitios_auditoria_ec.php";
  include "ec_tipos_servicio/modal_explorar_auditores_grupo_auditoria.php";
  include "ec_tipos_servicio/modal_explorar_auditores_grupo_auditoria_ec.php";
  include "ec_tipos_servicio/modal_inserta_actualiza_auditoria_grupo_auditores.php";
  include "ec_tipos_servicio/modal_fecha_norma_tipo_servicio_integral.php";
  include "ec_tipos_servicio/modal_genera_notificacion.php";
  include "ec_tipos_servicio/modal_inserta_actualiza_gastos_auditoria.php";
   include "ec_tipos_servicio/modal_inserta_actualiza_viaticos_auditoria.php";
  include "ec_tipos_servicio/modal_dictaminacion.php";
  include "ec_tipos_servicio/modal_facturacion.php";
  include "ec_tipos_servicio/modal_confirmacion.php";
  include "ec_tipos_servicio/modal_inserta_actualiza_participante.php";
  include "ec_tipos_servicio/modal_select_instructor.php";

  ?>
</span>
