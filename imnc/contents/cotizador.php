<div class="right_col" role="main"  ng-controller="cotizador_controller as $ctrl" ng-init='despliega_cotizaciones()' ng-cloak>
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <div class="row">
            <p><h2>Cotizaciones</h2></p>
            <p>
              <button type="button" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc" style="float: right;"  
              ng-click='modal_cotizacion_insertar()'
              ng-if='modulo_permisos["registrar"] == 1'>
                <i class="fa fa-plus"> </i> Agregar cotización
              </button>
            </p>
          </div>
        </div>
        
        <div class="x-title">
          <div class="row">
            <div class="form-group col-md-6">
              <label class="control-label" for="selectFiltroServicio">Filtrar por Servicio</label>
              <select id="selectFiltroServicio" ng-model="selectFiltroServicio"
                  ng-change="servicioFiltroChange()"
                  ng-options="servicio.ID as servicio.NOMBRE for servicio in Servicios" class="form-control">
                     <option value="" selected disabled>-- selecciona un servicio --</option>
                  </select>
            </div>
          </div>          
        </div>
        
        <div class="x_content">
          <table class="table table-striped responsive-utilities jambo_table bulk_action">
            <thead>
              <tr class="headings">
                <th class="column-title">ID</th>
                <th class="column-title">
                  Descripci&oacuten
                </th>
                <th class="column-title">Folio</th>
                <th class="column-title">Estado</th>
                <!--<th class="column-title">{{titulo_columna_tarifa}}</th>-->
                <th class="column-title"></th>
                <th class="column-title"></th>
                <th class="column-title"></th>
				        <th class="column-title"></th>
              </tr>
            </thead>

            <tbody>
              <tr class="even pointer" ng-repeat="cotizacion in arr_cotizaciones">
                <td>{{$index + 1}}</td>
                <td>
                  <strong>{{cotizacion.NOMBRE_VISTA}}</strong><br>
                  <strong>Tipo de servicio: </strong>{{cotizacion.TIPOS_SERVICIO.NOMBRE}}
				  <i ng-if="cotizacion.TIPOS_SERVICIO.ID==16"><br><strong>Actividad Econ&oacutemica: </strong> {{cotizacion.SECTOR}}</i>
                  <i ng-if="cotizacion.TIPOS_SERVICIO.ID==17"><br><strong>Norma: </strong>{{cotizacion.NORMA[0].ID_NORMA}}</i>
				  <i ng-if="cotizacion.TIPOS_SERVICIO.ID==18"><br><strong>Dictamen o Constancia: </strong>{{cotizacion.DICTAMEN_O_CONSTANCIA}}</i>
				  <i ng-if="cotizacion.SERVICIO.ID!=3 && cotizacion.TIPOS_SERVICIO.ID!=18 && cotizacion.TIPOS_SERVICIO.ID!=19"><br><strong>Tarifa día auditor: </strong>{{cotizacion.VALOR_TARIFA | currency}}</i>
				  <i ng-if="cotizacion.SERVICIO.ID==3"><br><strong>Modalidad:</strong> {{cotizacion.CURSO.MODALIDAD}}</i>
                  <i ng-if="cotizacion.SERVICIO.ID==3"><br><strong>Curso:</strong> {{cotizacion.CURSO.NOMBRE_CURSO}}</i>
                  <br ng-if="cotizacion.SERVICIO.ID==3">
                  <a type="link" class="btn btn-primary btn-xs btn-success btnVerEnlace"
                  ng-if="cotizacion.SERVICIO.ID==3 && cotizacion.CURSO.URL_PARTICIPANTES"
                  ng-click="mostrar_enlace(cotizacion.CURSO.URL_PARTICIPANTES)">
                      <i class="fa fa-bullseye"></i> Enlace para cargar participantes
                    </a>
                  <!--<p ng-if="cotizacion.SERVICIO.ID==3 && cotizacion.CURSO.URL_PARTICIPANTES"><strong>URL para cargar participantes:</strong> {{cotizacion.CURSO.URL_PARTICIPANTES}}</p>-->
                </td>
                <td>{{cotizacion.FOLIO}}</td>
                <td>{{cotizacion.ESTADO.ESTATUS_SEGUIMIENTO}}</td>
                <!--<td>{{cotizacion.VALOR_TARIFA | currency}}</td>-->
                <td>
                  <button type="button" class="btn btn-primary btn-xs btn-imnc btnEditar" ng-click="modal_cotizacion_editar(cotizacion.ID)"
                  ng-if='modulo_permisos["editar"] == 1 && cotizacion.ESTADO.ESTATUS_SEGUIMIENTO != "Firmado"' style="float: right;">
                    <i class="fa fa-edit"></i> Editar datos
                  </button>
                </td>
                <td>
                  <button type="button" class="btn btn-primary btn-xs btn-imnc btnEditar" ng-click="eliminar_cotizacion(cotizacion.ID)"
                  ng-if='modulo_permisos["editar"] == 1' style="float: right;">
                    <i class="fa fa-trash"></i> Eliminar cotización
                  </button>
                </td>
                <td>

                  <div ng-show = "cotizacion.SERVICIO.ID == 3">
                    <a type="button" class="btn btn-primary btn-xs btn-success btnGenerarCotizacion" style="float: right;">
                      <i class="fa fa-bullseye"></i> Generar cotización
                    </a>	
                  </div>
                  <div ng-show = "cotizacion.SERVICIO.ID == 3 && cotizacion.ESTADO.ESTATUS_SEGUIMIENTO == 'Firmado'">
                    <a type="button" class="btn btn-primary btn-xs btn-success btnGenerarServicio" 
                    style="float: right;" ng-click="generar_servicio(cotizacion)">
                      <i class="fa fa-plus"></i> Crear servicio
                    </a>	
                  </div>
                  <div ng-show = "cotizacion.ID_TIPO_SERVICIO == 1 || cotizacion.ID_TIPO_SERVICIO == 2 || cotizacion.ID_TIPO_SERVICIO == 12 || cotizacion.ID_TIPO_SERVICIO == 20">
                    <a type="button" class="btn btn-primary btn-xs btn-success btnVerCotizacion" href="./?pagina=ver_cotizacion&id_cotizacion={{cotizacion.ID}}" style="float: right;">
                      <i class="fa fa-bullseye"></i> Ver cotización
                    </a>	
                  </div>
                  <div ng-show = "cotizacion.ID_TIPO_SERVICIO == 16">
                    <a type="button" class="btn btn-primary btn-xs btn-success btnVerCotizacion" href="./?pagina=ver_cotizacion_CIL&id_cotizacion={{cotizacion.ID}}" style="float: right;">
                      <i class="fa fa-bullseye"></i> Ver cotización
                    </a>	
                  </div>
                  <div ng-show = "cotizacion.ID_TIPO_SERVICIO == 17">
                    <a type="button" class="btn btn-primary btn-xs btn-success btnVerCotizacion" href="./?pagina=ver_cotizacion_TUR&id_cotizacion={{cotizacion.ID}}" style="float: right;">
                      <i class="fa fa-bullseye"></i> Ver cotización
                    </a>	
                  </div>
				  <div ng-show = "cotizacion.ID_TIPO_SERVICIO == 18">
                    <a type="button" class="btn btn-primary btn-xs btn-success btnVerCotizacion" href="./?pagina=ver_cotizacion_INF_COM&id_cotizacion={{cotizacion.ID}}" style="float: right;">
                      <i class="fa fa-bullseye"></i> Ver cotización
                    </a>	
                  </div>
				  <div ng-show = "cotizacion.ID_TIPO_SERVICIO == 19">
						<a type="button" class="btn btn-primary btn-xs btn-success btnVerCotizacion" href="./?pagina=ver_cotizacion_CPER&id_cotizacion={{cotizacion.ID}}" style="float: right;">
							<i class="fa fa-bullseye"></i> Ver cotización
						</a>	
					</div>

                </td>
				        <td>
                  <a type="button" class="btn btn-primary btn-xs btn-success btnVerCotizacion" href="./?pagina=registro_expediente&id={{cotizacion.ID}}&id_entidad=4" style="float: right;">
                    <i class="fa fa-home"></i> Ver expedientes
                  </a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal insertar/actualizar-->
  <div class="modal fade" id="modalInsertarActualizarCotizacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modalTituloCotizacion">Insertar/Actualizar</h4>
        </div>
        <div class="modal-body">
            <form id="demo-form2" style="margin-top: -20px;">
              <div class="form-group form-vertical" style="display: none;">
                <label class="control-label col-md-12">ID <span class="required">*</span></label>
                <div class="col-md-12">
                  <input type="text" ng-model="cotizacion_insertar_editar.ID" id="txtID"  placeholder="asignado automáticamente" required="required" class="form-control col-md-7 col-xs-12" disabled>
                </div>
              </div>

              <div class="form-group form-vertical" >
                <label class="control-label col-md-6">Tipo de entidad</label>
                <div class="col-md-12" style="text-align:center">
                  <label class="radio-inline"><input type="radio" ng-model="bandera" value="0" name="prospecto-radio">Prospecto&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                  <label class="radio-inline">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" ng-model="bandera" value="1" name="clienteradio">Cliente</label>
                </div>
              </div>

              <div class="form-group form-vertical" id="comboProspecto" ng-if="bandera==0">
                <label class="control-label col-md-12">Prospecto <span class="required">*</span></label>
                <div class="col-md-12">
                  <select id="selectProspecto" ng-model="cotizacion_insertar_editar.PROSPECTO" 
                  class="form-control" ng-change="cambioProspecto(cotizacion_insertar_editar.PROSPECTO)"
                  ng-options="prospecto as prospecto.NOMBRE for prospecto in arr_prospectos track by prospecto.ID">
                     <option value="" selected disabled>-- selecciona un prospecto --</option>
                  </select>
                </div>
              </div>

              <div class="form-group form-vertical" id="comboCliente" ng-if="bandera==1">
                <label class="control-label col-md-12">Cliente <span class="required">*</span></label>
                <div class="col-md-12">
                  <select id="selectCliente"
                  ng-model="cotizacion_insertar_editar.CLIENTE" class="form-control"
                  ng-options="cliente as cliente.NOMBRE for cliente in arr_clientes track by cliente.ID"
                  ng-change="cambioCliente()">
                     <option value="" selected disabled>-- selecciona un cliente --</option>
                  </select>
                </div>
              </div>

              <div class="form-group form-vertical">
                <label class="control-label col-md-4 col-sm-4 col-xs-12">Estado de la cotización<span class="required">*</span></label>
                <div class="col-md-12">
                  <select ng-model="cotizacion_insertar_editar.ESTADO_SEG" required="required" class="form-control"
                  ng-options="estseg as estseg.DESCRIPCION for estseg in Estatus_seguimiento track by estseg.ID">
                    <option value="" selected disabled>---Seleccione un estatus---</option>
                  </select>
                </div>
              </div>

              <div class="form-group form-vertical" >
                <label class="control-label col-md-12"> Folio  <span class="required">*</span></label>
                <div class="col-md-4">
                  <input type="text" ng-model="cotizacion_insertar_editar.FOLIO_INICIALES"  required="required" class="form-control"
                  ng-change="cotizacion_insertar_editar.FOLIO_INICIALES = cotizacion_insertar_editar.FOLIO_INICIALES.toUpperCase()">
                  <div style="float: left;">
                    <span style="font-size: 11px;">Iniciales del Ejecutivo</span>
                  </div>
                </div>
                <div class="col-md-4">
                  <input type="text" ng-model="cotizacion_insertar_editar.FOLIO_SERVICIO"  required="required" class="form-control"
                  ng-change="cotizacion_insertar_editar.FOLIO_SERVICIO = cotizacion_insertar_editar.FOLIO_SERVICIO.toUpperCase()">
                  <div style="float: left;">
                    <span style="font-size: 11px;">Iniciales del Servicio</span>
                  </div>
                </div>
              </div>

              <div class="form-group form-vertical" ng-if='bandera==1'>
                <label class="control-label col-md-12">Servicio contratado<span class="required">*</span></label>
                <div class="col-md-12">
                  <select id="selectReferencia" ng-model="cotizacion_insertar_editar.REFERENCIA"
                  ng-change="cambioReferencia()"
                  ng-disabled="opcion_guardar_cotizacion == 'editar'"
                  ng-options="referencia as referencia.VALOR for referencia in Referencias" class="form-control">
                     <option value="" selected disabled>-- selecciona una referencia --</option>
                  </select>
                </div>
              </div>

              <div class="form-group form-vertical">
                <label class="control-label col-md-12">Servicio <span class="required">*</span></label>
                <div class="col-md-12">
                  <select id="selectServicio" ng-model="cotizacion_insertar_editar.ID_SERVICIO"
                  ng-options="servicio as servicio.NOMBRE for servicio in Servicios"
                  ng-change ="cambio_servicio()" class="form-control"
                  ng-disabled="bandera==1">
                     <option value="" selected disabled>-- selecciona un servicio --</option>
                  </select>
                </div>
              </div>

              <div class="form-group form-vertical">
                <label class="control-label col-md-12">{{lblTipoServicio}}<span class="required">*</span></label>
                <div class="col-md-12">
                  <select id="selectTipoServicio" ng-model="cotizacion_insertar_editar.ID_TIPO_SERVICIO" class="form-control"
                  ng-options="item_servicio as item_servicio.NOMBRE for item_servicio in Tipos_Servicio"
                  ng-change="cambio_tipo_servicio()"
                  ng-disabled="bandera==1">
                     <option value="" selected disabled>-- selecciona un tipo de servicio --</option>
                  </select>
                </div>
              </div>
             
             <div class="form-group form-vertical" ng-show="cotizacion_insertar_editar.ID_TIPO_SERVICIO.ID == 17 && cotizacion_insertar_editar.ID_SERVICIO.ID != 3">
                <label class="control-label col-md-12">Norma <span class="required">*</span></label>
                <div class="col-md-12" ng-show="opcion_guardar_cotizacion=='insertar'">
                  <select id="selectNorma" ng-model="normas_cotizacion[0]" class="form-control"
                  ng-options="norma as norma.ID_NORMA for norma in Normas"
				          ng-disabled="opcion_guardar_cotizacion=='editar'">
                     <option value="" selected disabled>-- selecciona una norma --</option>
                  </select>
                </div>

                <div class="col-md-12" ng-show="opcion_guardar_cotizacion=='editar'">                  
                  <input type="numeric" ng-model="normas_cotizacion[0].ID_NORMA"
                  ng-disabled="opcion_guardar_cotizacion=='editar'"
                  required="required" class="form-control col-md-7 col-xs-12">
                </div>
				      </div>              
           
              <div class="form-group form-vertical" ng-show="cotizacion_insertar_editar.ID_TIPO_SERVICIO.ID != 17 && cotizacion_insertar_editar.ID_SERVICIO.ID != 3" >
                <label class="control-label col-md-12">Normas</label>
                <div class="col-md-12">
                      <multiple-autocomplete ng-model="normas_cotizacion"
                      object-property="ID_NORMA"
                      suggestions-arr="Normas">
                      </multiple-autocomplete>
                </div>
			        </div>

              <!-- Esto se oculta con ng-show porque por el momento no se va a usar -->
              <div class="form-group form-vertical" ng-if='bandera==1' ng-show="false">
                <label class="control-label col-md-12">Etapa<span class="required">*</span></label>
                <div class="col-md-12">
                  <select id="selectEtapa" ng-model="cotizacion_insertar_editar.ETAPA"
                  ng-options="etapa.ID as etapa.NOMBRE for etapa in Etapas" class="form-control">
                     <option value="" selected disabled>-- selecciona una etapa --</option>
                  </select>
                </div>
              </div>

              <div class="form-group form-vertical"  ng-show="cotizacion_insertar_editar.ID_SERVICIO.ID == 1">
                <label class="control-label col-md-12">Complejidad <span class="required">*</span></label>
                <div class="col-md-12">
                  <select id="complejidad" ng-model="cotizacion_insertar_editar.COMPLEJIDAD" class="form-control">
                    <option value="" selected disabled>-- selecciona  la complejidad --</option>
                    <option value="alta">Alta</option>
                    <option value="media" selected>Media</option>
                    <option value="baja" selected>Baja</option>
                    <option value="limitada" selected>Limitada</option>
                  </select>
                </div>
              </div>

            

              <div class="form-group form-vertical" ng-show="cotizacion_insertar_editar.ID_SERVICIO.ID != 3  && cotizacion_insertar_editar.ID_TIPO_SERVICIO.ID != 19">

                <label class="control-label col-md-4 col-sm-4 col-xs-12">Tarifa por Día Auditor<span class="required">*</span></label>
                <div class="col-md-12">
                  <select ng-model="cotizacion_insertar_editar.TARIFA" required="required" class="form-control"
                  ng-options="item_tarifa.id as item_tarifa.descripcion for item_tarifa in Tarifa_Cotizacion" ng-disabled="cotizacion_insertar_editar.ID_TIPO_SERVICIO.ID == 18">
                    <option value="" selected disabled>---Seleccione una tarifa---</option>
                  </select>
                </div>
              </div>

              <div class="form-group form-vertical"  ng-show="cotizacion_insertar_editar.ID_SERVICIO.ID == 1" >
                <label class="control-label col-md-12">Descuento (%)</label>
                <div class="col-md-12">
                  <input type="numeric" ng-model="cotizacion_insertar_editar.DESCUENTO" required="required" class="form-control col-md-7 col-xs-12">
                </div>
              </div>
			  <div class="form-group form-vertical" ng-show="false">
                <label class="control-label col-md-12">Aumento (%)</label>
                <div class="col-md-12">
                  <input type="numeric" ng-model="cotizacion_insertar_editar.AUMENTO" required="required" class="form-control col-md-7 col-xs-12">
                </div>
              </div>
              <!-- Esta opción es solo para integrales -->
              <div class="form-group form-vertical" ng-show="cotizacion_insertar_editar.ID_TIPO_SERVICIO.ID == 20">
                <label class="control-label col-md-12">Capacidad de realizar auditoría combinada (%)</label>
                <div class="col-md-12">
                  <input type="numeric" ng-model="cotizacion_insertar_editar.COMBINADA" required="required" class="form-control col-md-7 col-xs-12">
                </div>
              </div>
			        <!-- Esta opción es solo para Certificacion de Igualdad Laboral -->
              <div class="form-group form-vertical" ng-show="cotizacion_insertar_editar.ID_TIPO_SERVICIO.ID == 16">
                <label class="control-label col-md-12">Actividad Econ&oacutemica </label>
                <div class="col-md-12">
                  <select id="complejidad" ng-model="cotizacion_insertar_editar.ACTIVIDAD_ECONOMICA" class="form-control">
                    <option value="" selected disabled>-- selecciona  la actividad económica --</option>
                    <option value="Industria">Industria</option>
                    <option value="Comercio" selected>Comercio</option>
                    <option value="Servicios" selected>Servicios</option>
                   </select>
                </div>
              </div>
				     <!-- Esta opción es solo para Unidad de verificación de información comercial -->
              <div class="form-group form-vertical" ng-show="cotizacion_insertar_editar.ID_TIPO_SERVICIO.ID == 18">
                <label class="control-label col-md-12">Dictamen o Constancia</label>
                <div class="col-md-12">
                  <select id="complejidad" ng-model="cotizacion_insertar_editar.DICTAMEN_CONSTANCIA" class="form-control" ng-change="cambio_dictamen_constancia()">
                    <option value="" selected disabled>-- selecciona  --</option>
                    <option value="Dictamen">Dictamen</option>
                    <option value="Constancia" selected>Constancia</option>
                    
                   </select>
                </div>
              </div>
              <!-- Solo mostrar para CIFA-->
              <div class="form-group form-vertical" ng-show="cotizacion_insertar_editar.ID_SERVICIO.ID == 3">
                <label class="control-label col-md-12">Tipo de persona: {{tipo_persona}}</label>
              </div>
					    
              <!-- Solo mostrar para CIFA-->
              <div class="form-group form-vertical" ng-show="cotizacion_insertar_editar.ID_SERVICIO.ID == 3">
                <label class="control-label col-md-12">Modalidad del curso </label>
                <div class="col-md-12">
                  <select ng-model="modalidades" class="form-control" 
                  ng-change="onChangeModalidades(cotizacion_insertar_editar.ID_TIPO_SERVICIO.ID)">
                    <option  value="" ng-selected="tipo_persona == 'Moral'" disabled>Seleccione una opción</option>
                    <option value="programado" ng-selected="tipo_persona == 'Física' || modalidades=='programado'">Programado</option>
                    <option value="insitu" ng-selected="modalidades == 'insitu'" ng-disabled="tipo_persona == 'Física'">In Situ</option>
                    <option value="diplomado" disabled >Diplomado</option>
                  </select>
                </div>
              </div>              

					    <!-- Solo mostrar para CIFA-->
              <div class="form-group form-vertical" ng-show="cotizacion_insertar_editar.ID_SERVICIO.ID == 3 ">
                <label class="control-label col-md-12" id="labelCurso">Cursos Programados </label>
                <div class="col-md-12">
                <select ng-model="cursos_programados" class="form-control"
                  ng-options="curso.id as curso.nombre  for curso in Cursos">
                    <option value="" ng-selected="true" disabled>Seleccione una opción</option>
                  </select>
                </div>
              </div>					    

              <!-- Solo mostrar para CIFA-->
              <div class="form-group form-vertical" ng-show="cotizacion_insertar_editar.ID_SERVICIO.ID == 3 && modalidades == 'insitu'">
                <label class="control-label col-md-12">Cantidad de Participantes </label>
                <div class="col-md-12">
                  <input type="text" class="form-control" name="cantidad_participantes" id="cantidad_participantes" ng-model="cantidad_participantes"   required>
                  <span id="txtcantidad_participanteserror" class="text-danger"></span>
                </div>
              </div>                     

            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" opcion="{{opcion_guardar_cotizacion}}" id="btnGuardarUsuario" ng-click="cotizacion_guardar()">Guardar</button>
        </div>
      </div>
    </div>
  </div>
</div>
