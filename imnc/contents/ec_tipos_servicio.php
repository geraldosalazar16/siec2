<span ng-controller="ec_tipos_servicio_controller">
<div class="right_col" role="main" >
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>{{titulo}}</h2></p>
       
         
              <p ng-if='modulo_permisos["catalogos"] == 1'>
				<button type="button" id="btnNuevo" ng-click="agregar_info_auditoria()" class="btn btn-primary btn-xs btn-imnc" style="float: right;"> 
					<i class="fa fa-plus"> </i> {{titulo_boton_info_auditoria}}
				</button>
              </p>
           
        
        
          <div class="clearfix"></div>
        </div>

        <div class="x_content">
			
				<ul class="list-unstyled user_data">
					<li ><b>
					Cliente:<i> {{DatosServicio.NOMBRE_CLIENTE}}</i></b>
					</li>

					<li ><b>
					Servicio: <i> {{DatosServicio.NOMBRE_SERVICIO}}</i></b>
					</li>

					<li ><b>
					Tr&aacutemite: <i> {{DatosServicio.NOMBRE_ETAPA}}</i></b>
					</li>
	
					<li ><b>
					Referencia: <i> {{DatosServicio.REFERENCIA}}</i></b>
					</li>

				</ul>
			
				
				
				<div class="" role="tabpanel" data-example-id="togglable-tabs">
							<ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
								<li role="presentation" class="active">
									<a href="#tab_informacion" id="tab_informacion-tab"  role="tab" data-toggle="tab" aria-expanded="true" ng-click="DatosInformacion()">
                          			Informaci&oacuten </a>
								</li>
								
								<li role="presentation" class="" ng-if="DatosServicio.ID_SERVICIO == 1">
									<a href="#tab_sectores" id="tab_sectores-tab"  role="tab"  data-toggle="tab" aria-expanded="false">
									Sectores</a>
								</li>						
								
								<li role="presentation" class=""> <!-- ng-if="DatosServicio.ID_SERVICIO == 1">-->
								<a href="#tab_sitios" id="tab_sitios-tab"  role="tab" data-toggle="tab" aria-expanded="true" >
                          			Sitios</a>
								</li>
								
								<li role="presentation" class="" ng-if="DatosServicio.ID_SERVICIO == 1">
								<a href="#tab_auditorias" id="tab_auditorias-tab"  role="tab" data-toggle="tab" aria-expanded="true" >
                          			Auditor&iacuteas </a>
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
											<td> {{x.SECTORES_ID}}-{{x.SECTORES_ID_TIPO_SERVICIO}}-{{x.SECTORES_ANHIO}}</td>
											<td>{{x.NOMBRE_SECTOR}}</td>
								<!--			<td>{{x.PRINCIPAL}}</td>	-->
											<td>
												<p ng-if='modulo_permisos["registrar"] == 1'>
													<button type="button"  ng-click="agregar_editar_sector('editar',x.ID_SERVICIO_CLIENTE_ETAPA,x.ID_SECTOR)" class="btn btn-primary btn-xs btn-imnc" style="float: right;"> 
														<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar sector 
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
										</tr>
									</thead>
									<tbody>
										<tr ng-repeat="x in SitiosServicio" class="ng-scope  even pointer" ng-if="DatosServicio.ID_SERVICIO == 2">
											<td> {{x.ACRONIMO}}<br>{{x.NOMBRE_DOMICILIO}}</td>
											<td ng-init="CargarDatosSitiosEC(x.ID_CLIENTE_DOMICILIO)">
												<ul class="list-unstyled user_data">
													<li ng-repeat="y in DatosSitiosEC">
														{{y.NOMBRE_META_SITIOS}}:
														<i ng-show="y.TIPO_META_SITIOS!=2"> {{y.VALOR}}</i>
														<i ng-show="y.TIPO_META_SITIOS==2" ng-init="mostrarvalorselectsitios(y.VALOR,$index)"> {{resp1[$index]}}</i>
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
										</tr>
									</tbody>
								</table>
								</div>
								<div role="tabpanel" class="tab-pane fade" id="tab_auditorias" aria-labelledby="profile-tab" ng-if="DatosServicio.ID_SERVICIO == 1">
									<div class="x_title">
										<p><h2>Auditor&iacuteas </h2></p>
											<p ng-if='modulo_permisos["registrar"] == 1'>
											<button type="button" ng-click="agregar_editar_auditorias('insertar')" class="btn btn-primary btn-xs btn-imnc" style="float: right;"> 
												<i class="fa fa-plus"> </i> Agregar auditor&iacuteas 
											</button>
										</p>
										<div class="clearfix"></div>
									</div>

                                    <table class="table table-striped responsive-utilities jambo_table bulk_action">
									<thead>
										<tr class="headings">
											<th class="column-title">Fechas</th>
											<th class="column-title">D&iacuteas auditor</th>
											<th class="column-title">Tipo y status de auditor&iacutea </th>
											<th class="column-title">Sitios de auditor&iacutea </th>
											<th class="column-title">Grupo de auditores</th>
											<th class="column-title"></th>
											<th class="column-title"></th>								
										</tr>
									</thead>
									<tbody>
										<tr ng-repeat-start="x in DatosAuditoriasSG" ng-if="DatosServicio.ID_SERVICIO == 1" class="ng-scope  even pointer"  >
											<td> 
												<table>
													<tr>
														<td>
															<datepicker date-format="yyyy-MM-dd" date-min-limit="{{FechaHoy}}" date-typer="true" button-prev='<i class="fa fa-arrow-circle-left"></i>' button-next='<i class="fa fa-arrow-circle-right"></i>' >
																<input type="text"  ng-model="txtInsertarFechas[x.TIPO_AUDITORIA]" placeholder="Selecciona las fechas" data-parsley-id="2324" class="txtFechasAuditoria" />
															</datepicker>
															
														</td>
														<td>
															<button class="btn btn-primary btn-xs btn-imnc" ng-click="agregar_editar_fechasAuditoria(x.ID_SERVICIO_CLIENTE_ETAPA,x.TIPO_AUDITORIA,'insertar',x.CICLO)" >Agregar Fechas</button>
														</td>
														<td>
														</td>
													</tr>
													<tr ng-repeat = "z in x.AUDITORIA_FECHAS">
														<td>
												
															<datepicker date-format="yyyy-MM-dd"  button-prev='<i class="fa fa-arrow-circle-left"></i>' button-next='<i class="fa fa-arrow-circle-right"></i>' date-min-limit="{{FechaHoy}}"  >
																<input type="text"  ng-model="txtFechasAuditoria[z.ID]"  data-parsley-id="2324" class="txtFechasAuditoria" id="txtFechasAuditoria-{{z.ID}}" />
															</datepicker>
														</td>
														<td>
															<button class="btn btn-primary btn-xs btn-imnc" ng-click="agregar_editar_fechasAuditoria(x.ID_SERVICIO_CLIENTE_ETAPA,x.TIPO_AUDITORIA,'editar',x.CICLO,z.ID)" >Editar Fechas</button>
														</td>
														<td>
															<p ng-if='modulo_permisos["editar"] == 1'>
																	<button class="btn btn-primary btn-xs" ng-click="eliminar_fechasAuditoria(z.ID)"><i class="fa fa-trash" aria-hidden="true"></i></button>
															</p>
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
											<td>
												<p ng-if='modulo_permisos["registrar"] == 1'>
													<button type="button"  ng-click="agregar_editar_auditorias('editar',x.ID_SERVICIO_CLIENTE_ETAPA,x.TIPO_AUDITORIA,x.CICLO)" class="btn btn-primary btn-xs btn-imnc" style="float: right;"> 
														<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar auditor&iacuteas 
													</button>
												</p>
											</td>
											<td>
												<p ng-if='modulo_permisos["registrar"] == 1'>
													<button type="button"  ng-click="agregar_editar_auditorias('editar',x.ID_SERVICIO_CLIENTE_ETAPA,x.TIPO_AUDITORIA)" class="btn btn-primary btn-xs btn-imnc" style="float: right;"> 
														<i class="fa fa-download" aria-hidden="true"></i> Notificaci&oacuten 
													</button>
												</p>
											</td>
										</tr>
										
										<!--++++++++++++++++++++Sitios de Auditoria++++++++++++++++++++-->
										<tr  ng-if="DatosServicio.ID_SERVICIO == 1" class="collapse out" id="collapse-{{id_servicio_cliente_etapa}}-{{x.TIPO_AUDITORIA}}-{{x.CICLO}}-sitios-auditoria">
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
										<tr ng-repeat-end ng-if="DatosServicio.ID_SERVICIO == 1" class="collapse out" id="collapse-{{id_servicio_cliente_etapa}}-{{x.TIPO_AUDITORIA}}-{{x.CICLO}}-grupo-auditoria">
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
															<td> {{w.ID_ROL}} </td>
															<td> {{w.NOMBRE_SERVICIO}} </td>
															<td>
																<table>
																	<tr>
																		<td>
																			<datepicker date-format="yyyy-MM-dd"  button-prev='<i class="fa fa-arrow-circle-left"></i>' button-next='<i class="fa fa-arrow-circle-right"></i>' date-enabled-dates="{{GenerarArregloFecha(x.AUDITORIA_FECHAS)}}"  >
																				<input type="text"  ng-model="txtInsertarFechasGrupo[w.ID_PERSONAL_TECNICO_CALIF]" placeholder="Fechas" data-parsley-id="2324" class="txtFechasAuditoria" style="width:100px;" />
																			</datepicker>
																		</td>
																		<td>
																			<button class="btn btn-primary btn-xs btn-imnc" ng-click="agregar_editar_fechasAuditoriaGrupo(x.ID_SERVICIO_CLIENTE_ETAPA,x.TIPO_AUDITORIA,x.CICLO,w.ID_PERSONAL_TECNICO_CALIF)" >Agregar Fechas</button>
																		</td>
																	</tr>
																</table>
																
																
				<!--												<label>Asignar fechas  <span class="required">*</span></label>
				<datepicker date-format="yyyy-MM-dd"  button-prev='<i class="fa fa-arrow-circle-left"></i>' button-next='<i class="fa fa-arrow-circle-right"></i>' date-enabled-dates="{{FechaPrueba}}"  >
						<input type="text"  ng-model="formDataGrupoAuditor.Fecha"  data-parsley-id="2324" class="form-control" id="formDataGrupoAuditor.Fecha" required ng-class="{ error: exampleFormGrupoAuditor.Fecha.$error.required && !exampleFormGrupoAuditor.$pristine}"  />
				</datepicker>-->
															<ul class="list-unstyled user_data">
																<li ng-repeat="r in x.AUDITORES_FECHAS[w.ID_PERSONAL_TECNICO_CALIF]">
																	{{mostrarFecha(r.FECHA)}}
														
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
										<!--++++++++++++++++++++++++++++++++++++++++++++++++++++++++++-->
									</tbody>

									
								</table>
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
 
  include "ec_tipos_servicio/modal_explorar_sitios_auditoria.php";
  include "ec_tipos_servicio/modal_explorar_auditores_grupo_auditoria.php";
  include "ec_tipos_servicio/modal_inserta_actualiza_auditoria_grupo_auditores.php";
  include "ec_tipos_servicio/modal_confirmacion.php";
  ?>
</span>