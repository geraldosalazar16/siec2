<div class="right_col" role="main"  ng-controller="ver_cotizacion_TUR_controller as $ctrl" ng-init='despliega_cotizacion()' ng-cloak>
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>Detalle de cotización</h2></p>
      
		      <p>
            <button type="button" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc" style="float: right;"  
            ng-click='modal_cotizacion_generar()'
            ng-if='modulo_permisos["editar"] == 1 && !bl_cotizado'>
              <i class="fa fa-file"></i> Generar cotización
            </button>
          </p>
          <p>
          <button type="button" class="btn btn-primary btn-xs btn-imnc btnEditar"
            ng-click="modal_insertar_servicio(tramites_cotizacion)"
            ng-if='modulo_permisos["editar"] == 1 && bl_firmado && obj_cotizacion.BANDERA == 0' style="float: right;">
            <i class="fa fa-plus"></i>Crear Servicio
            </button>
          <button type="button" class="btn btn-primary btn-xs btn-imnc btnEditar"
            ng-click="modal_insertar_servicio(tramites_cotizacion)"
            ng-if='modulo_permisos["editar"] == 1 && bl_firmado && obj_cotizacion.BANDERA == 1' style="float: right;">
            <i class="fa fa-send"></i> Enviar eventos a programación
          </button>
          </p>
          <div class="clearfix"></div>
        </div>

        <div class="x_content">
          <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-6">
              <ul class="list-unstyled user_data">
                  <li id="lbProspecto" ng-if="obj_cotizacion.BANDERA == 0">
                    Prospecto: {{obj_cotizacion.NOMBRE_VISTA}}
                  </li>
                  <li id="lbCliente" ng-if="obj_cotizacion.BANDERA == 1">
                    Cliente: {{obj_cotizacion.NOMBRE_VISTA}}
                  </li>
                  <li id="lbTipoServicio">
                    Tipo de servicio: {{obj_cotizacion.TIPOS_SERVICIO.NOMBRE}}
                  </li>
                  <li id="lbNorma">
                    Normas:
                    <div ng-repeat="norma in obj_cotizacion.NORMAS">
                      <span>{{$index+1}}- {{norma.ID_NORMA}}</span>
                    </div>
                  </li>
                  <li id="lbFolio">
                    Folio: {{obj_cotizacion.FOLIO}}
                  </li>
                   <li id="lbReferencia" ng-if='bl_cotizado'>
                    Referencia: {{obj_cotizacion.REFERENCIA}}
                  </li>
                  <li id="lbEstado">
                    Estado: {{obj_cotizacion.ESTADO.ESTATUS_SEGUIMIENTO}}
                  </li>
                  <li id="lbTarifa">
                    Tarifa por Día Auditor: {{obj_cotizacion.TARIFA_COMPLETA.TARIFA | currency}}
                  </li>
                  <li id="lbDias">
                    TOTAL DIAS COTIZACION: {{obj_cotizacion.TOTAL_DIAS_COTIZACION}}
                  </li>
                  <li id="lbCOt">
                    TOTAL COTIZACION: {{obj_cotizacion.TOTAL_COTIZACION | currency}}
                  </li>
      <!--            <li id="lbDesc">
                    Descuento para la cotización: {{obj_cotizacion.DESCUENTO != null? obj_cotizacion.DESCUENTO : 0}}%
                  </li>
				  <li id="lbAum">
                    Aumento para la cotización: {{obj_cotizacion.AUMENTO != null? obj_cotizacion.AUMENTO : 0}}%
                  </li> 
                  <li id="lbCotDes">
                    TOTAL COTIZACION c/descuento: {{obj_cotizacion.TOTAL_COTIZACION_DES | currency}}
                  </li> -->
                  <div ng-if="obj_cotizacion.ID_COTIZACION_ANT != null">
                    <li id="lbCotAnt">
                      TOTAL COTIZACION ANTERIOR: {{obj_cotizacion.TOTAL_COTIZACION_ANT | currency}}
                    </li>
                     <li id="lbDelta">
                      <b>DIFERENCIA : {{obj_cotizacion.DELTA_COTIZACION | currency}}</b>
                    </li>
                  </div>
              </ul>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-4">
              <ul class="list-unstyled user_data">
                  <li>
                    <button type="button" id="btnInsertarTramite" class="btn btn-primary btn-xs btn-imnc" ng-click='modal_tramite_insertar()'
                    ng-if='modulo_permisos["registrar"] == 1 && !bl_cotizado'>
                      <i class="fa fa-plus"> </i> Agregar trámite
                    </button>
                  </li>
              </ul>
            </div>
          </div>
          <table class="table table-striped responsive-utilities jambo_table bulk_action" style="margin: 25px 0px 45px;">
            <thead>
              <tr class="headings">
                <th class="column-title">Tipo</th>
                <th class="column-title">Días de Auditoría</th>
				 <th class="column-title">Descuento</th>
				<th class="column-title">Aumento</th>
	<!--			<th class="column-title">Reduccion</th>	-->
                <th class="column-title">Costo</th>
                <th class="column-title">Costo total<span style="font-size: 10px;"> *costos adicionales incluidos</span></th>
                <th class="column-title"></th>
              </tr>
            </thead>

            <tbody>
              <tr class="even pointer" ng-repeat="tramites_cotizacion in arr_tramites_cotizacion">
                <td>{{tramites_cotizacion.TIPO }}</td>
                <td>Días total: <b>{{tramites_cotizacion.DIAS_AUDITORIA }}</b><br>
				
				</td>
				<td>{{tramites_cotizacion.DESCUENTO != null? tramites_cotizacion.DESCUENTO+"%" : "--" }}</td>
				<td>{{tramites_cotizacion.AUMENTO != null? tramites_cotizacion.AUMENTO+"%" : "--" }}</td>
	<!--			<td>{{tramites_cotizacion.REDUCCION != null? tramites_cotizacion.REDUCCION+"%" : "--" }}</td> -->
                <td>{{tramites_cotizacion.TRAMITE_COSTO_DES | currency }}</td>
                <td>{{tramites_cotizacion.TRAMITE_COSTO_TOTAL | currency }}</td>
                <td>
                  <button type="button" class="btn btn-primary btn-xs btn-imnc btnEditar" ng-click="modal_tramite_editar(tramites_cotizacion.ID)"
                  ng-if='modulo_permisos["editar"] == 1 && !bl_cotizado' style="float: right;">
                    Editar
                  </button>
                  <button type="button" class="btn btn-primary btn-xs btn-imnc btnEditar" 
                  ng-click="mostrar_tramite_sitios(tramites_cotizacion.ID)"
                  style="float: right;">
                    Mostrar cotización
                  </button>
                  
                  <a type="button" class="btn btn-primary btn-xs btn-imnc btnEditar"
                  href="./?pagina=sg_tipos_servicio&id_serv_cli_et={{tramites_cotizacion.ID_SERVICIO_CLIENTE}}"
                  ng-if='tramites_cotizacion.ID_SERVICIO_CLIENTE != null' style="float: right;">
                    Ver Auditoría
                  </a>
                </td>
              </tr>
            </tbody>
          </table>

          <div id="sitio_tramite" hidden>
            <div class="col-md-4 col-sm-4 col-xs-4">
              <ul class="list-unstyled user_data">
                  <li id="lbTramite">
                    <h2><b>Trámite: {{obj_cotizacion_tramite.ETAPA}}</b></h2>
                  </li>
                  <li id="lbTotalSitios">
                    Total de sitios en cotización: {{obj_cotizacion_tramite.COUNT_SITIOS.SITIOS_A_VISITAR}}
                  </li>
                  <li id="lbSitiosVisitar">
                    Sitios a visitar: {{obj_cotizacion_tramite.COUNT_SITIOS.TOTAL_SITIOS}} de {{obj_cotizacion_tramite.COUNT_SITIOS.SITIOS_A_VISITAR}}
                  </li>
                  <li id="lbRestriccion">
                    {{obj_cotizacion_tramite.COUNT_SITIOS.RESTRICCIONES_SITIOS}}
                  </li>
                  <li>
                    <button type="button" id="btnInsertarSitio" class="btn btn-primary btn-xs btn-imnc" ng-click='modal_sitio_insertar()'
                    ng-if='modulo_permisos["registrar"] == 1 && !bl_cotizado'>
                      <i class="fa fa-plus"> </i> Agregar sitio
                    </button>
					<button type="button" id="btnInsertarTarifa" class="btn btn-primary btn-xs btn-imnc" ng-click='modal_tarifa_adicional_insertar()'
                    ng-if='modulo_permisos["registrar"] == 1 && !bl_cotizado'>
                      <i class="fa fa-plus"> </i> Agregar Tarifa Adicional
                    </button>
                  </li>
              </ul>
            </div>
			<br>	
          <table class="table table-striped responsive-utilities jambo_table bulk_action" style="margin: 25px 0px 45px;">
            <thead>
              <tr class="headings">
                <th class="column-title">#</th>
                <th class="column-title">Descripción</th>
                <th class="column-title">Tarifa Adicional</th>
                <th class="column-title">Cantidad</th>
                <th class="column-title">Subtotal Tarifa Adicional</th>
                <th class="column-title"></th>
              </tr>
            </thead>

            <tbody>
              <tr class="even pointer" ng-repeat="tarifa_cotizacion in arr_tarifa_adicional_cotizacion">
                <td>{{$index + 1}}</td>
                <td>{{tarifa_cotizacion.DESCRIPCION}}</td>
                <td>{{tarifa_cotizacion.TARIFA}}</td>
                <td>{{tarifa_cotizacion.CANTIDAD}}</td>
                <td>{{tarifa_cotizacion.SUBTOTAL}}</td>
                <td>
                  <button type="button" class="btn btn-primary btn-xs btn-imnc btnEliminar" ng-click="modal_tarifa_adicional_eliminar(tarifa_cotizacion.ID)"
                    ng-if='modulo_permisos["editar"] == 1 && !bl_cotizado' style="float: right;">
                    <i class="fa fa-trash"></i>
                  </button>
                  <button type="button" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc" style="float: right;"
                    ng-click='modal_tarifa_adicional_editar(tarifa_cotizacion.ID)' ng-if='modulo_permisos["editar"] == 1 && !bl_cotizado'>
                    <i class="fa fa-edit"> </i> Editar Tarifa Adicional
                  </button>
                </td>

              </tr>
            </tbody>

          </table>

          <table class="table table-striped responsive-utilities jambo_table bulk_action" style="margin: 25px 0px 45px;">
            <thead>
              <tr class="headings">
                <th class="column-title">#</th>
                <th class="column-title">Nombre del sitio</th>
  <!--              <th class="column-title">Detalles</th>	-->
                <th class="column-title" ng-if="obj_cotizacion.NORMAS[0].ID_NORMA=='NMX-AA-120-SCFI-2006'||obj_cotizacion.NORMAS[0].ID_NORMA=='NMX-AA-120-SCFI-2016'">Longitud de la playa</th>
 <!--               <th class="column-title">Días de auditoría</th>	-->
                <th class="column-title">Por visitar</th>
                <th class="column-title"></th>
              </tr>
            </thead>

            <tbody>
              <tr class="even pointer" ng-repeat="sitios_cotizacion in arr_sitios_cotizacion">
                <td>{{$index + 1}}</td>
                <td>
                  {{sitios_cotizacion.NOMBRE }}<br>
                 
                </td>
      <!--          <td>
                  ¿Temporal o fijo? {{sitios_cotizacion.TEMPORAL_O_FIJO}} <br>
                  ¿Matriz o principal? {{sitios_cotizacion.MATRIZ_PRINCIPAL}}<br>
                  
                </td> -->
                <td ng-if="obj_cotizacion.NORMAS[0].ID_NORMA=='NMX-AA-120-SCFI-2006'||obj_cotizacion.NORMAS[0].ID_NORMA=='NMX-AA-120-SCFI-2016'">{{sitios_cotizacion.LONGITUD_PLAYA}}</td>
<!--				<td>
				Días base: <b>{{sitios_cotizacion.DIAS_AUDITORIA}}</b><br>
				Días encuesta: <b>{{sitios_cotizacion.DIAS_ENCUESTA}}</b><br>
				</td>	-->
 
                <td>
                  <input type="checkbox" class="flat" ng-click="actualiza_sitio_seleccionado(sitios_cotizacion.ID)"
                  ng-checked="sitios_cotizacion.SELECCIONADO == 1" ng-disabled="bl_cotizado || modulo_permisos['editar'] != 1">
                </td>
                <td>
                  <button type="button" class="btn btn-primary btn-xs btn-imnc btnEliminar" ng-click="modal_cotizacion_sitio_eliminar(sitios_cotizacion.ID)"
                    ng-if='modulo_permisos["editar"] == 1 && !bl_cotizado' style="float: right;">
                    <i class="fa fa-trash"></i>
                  </button>
                  <button type="button" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc" style="float: right;"
                    ng-click='modal_sitio_editar(sitios_cotizacion.ID)' ng-if='modulo_permisos["editar"] == 1 && !bl_cotizado'>
                    <i class="fa fa-edit"> </i> Editar sitio
                  </button>
                </td>

              </tr>
            </tbody>
          </table>

            <form name="form2" id="form2" class="form-horizontal form-label-left" ng-if="arr_sitios_cotizacion.length >= 1" hidden>
                  <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-10">Cotizar con el total de empleados</label>
                      <div class="col-md-1 col-sm-1 col-xs-1">
                        <input type="checkbox" class="form-control col-md-7 col-xs-12 selector noshadow" ng-model="bl_sum_empleados">
                      </div>
                  </div>
            </form>

            <div class="alert alert-danger alert-dismissible fade in" role="alert" ng-hide="obj_cotizacion_tramite.RESTRICCIONES.length == 0">
              <ul class="list-unstyled user_data" ng-repeat="restriccion in obj_cotizacion_tramite.RESTRICCIONES">
                  <li>
                    {{restriccion}}
                  </li>
              </ul>
            </div>

            <div class="col-md-8 col-md-offset-1 col-sm-8 col-xs-8" ng-hide="obj_cotizacion_tramite.RESTRICCIONES.length > 0">
              <ul class="list-unstyled user_data">
                 <li id="lbTotla" style="font-size: 20px;" ng-if="obj_cotizacion.NORMAS[0].ID_NORMA=='NMX-AA-120-SCFI-2006'||obj_cotizacion.NORMAS[0].ID_NORMA=='NMX-AA-120-SCFI-2016'">
					Longitud de la playa: {{obj_cotizacion_tramite.LONGITUD_PLAYA}} <br>
				</li>
				<li id="lbTotla" style="font-size: 20px;">
                    Días de auditoría: {{obj_cotizacion_tramite.TOTAL_DIAS_AUDITORIA}} <br>
				</li>
				<li id="lbTotla" style="font-size: 20px;">
                    Tarifa de Día Auditor<!-- <span style="font-size: 12px;"> *c/ descuento</span>-->: {{obj_cotizacion_tramite.TARIFA | currency}}<br>
				</li>
				<li id="lbTotla" style="font-size: 20px;">
					Costo Inicial: {{obj_cotizacion_tramite.COSTO_INICIAL | currency}}<br>
                </li>
				<li id="lbTotla" style="font-size: 20px;">
					Costo de auditoría con descuento : {{obj_cotizacion_tramite.COSTO_DESCUENTO | currency}}<br>
                </li>
				<li id="lbTotla" style="font-size: 20px;">
					Viáticos: {{obj_cotizacion_tramite.VIATICOS | currency}}<br>
                </li>
				<li id="lbTotla" style="font-size: 20px;">
				Costo Total de auditoría<span style="font-size: 12px;"> *costos adicionales incluidos</span>
                    : {{obj_cotizacion_tramite.COSTO_TOTAL | currency}}
                 </li>
              </ul>
            </div>
          </div>
          <!--FIN DIV-->
        </div>
      </div>
    </div>
  </div>

  <!-- Modal insertar/actualizar datos generales de la cotización-->
  <div class="modal fade" id="modalInsertarActualizarCotizacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modalTituloCotizacion">Insertar/Actualizar</h4>
        </div>
        <div class="modal-body">
            <form id="demo-form2" style="margin-top: -20px;">
              <div class="form-group form-vertical">
                <label class="control-label col-md-12">Prospecto/Cliente</label>
                <div class="col-md-12">
                  <input type="text" ng-model="obj_cotizacion.NOMBRE_VISTA"  required="required" class="form-control col-md-7 col-xs-12" readonly>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12">Estado de la cotización<span class="required">*</span></label>
                <div class="col-md-12">
                  <select ng-model="cotizacion_insertar_editar.ESTADO_SEG" required="required" class="form-control"
                  ng-options="estseg as estseg.DESCRIPCION for estseg in Estatus_seguimiento track by estseg.ID">
                    <option value="" selected disabled>---Seleccione un estatus---</option>
                  </select>
                </div>
              </div>

              <div class="form-group form-vertical" >
                <label class="control-label col-md-12"> Folio <span class="required">*</span></label>
                <div class="col-md-4">
                  <input type="text" ng-model="cotizacion_insertar_editar.FOLIO_INICIALES"  required="required" class="form-control"
                  ng-change="cotizacion_insertar_editar.FOLIO_INICIALES = cotizacion_insertar_editar.FOLIO_INICIALES.toUpperCase()">
                  <div style="float: left;"><span style="font-size: 11px;">Iniciales del Ejecutivo</span></div>
                </div>
                 <div class="col-md-4">
                  <input type="text" ng-model="cotizacion_insertar_editar.FOLIO_SERVICIO"  required="required" class="form-control"
                  ng-change="cotizacion_insertar_editar.FOLIO_SERVICIO = cotizacion_insertar_editar.FOLIO_SERVICIO.toUpperCase()">
                  <div style="float: left;"><span style="font-size: 11px;">Iniciales del Servicio</span></div>
                </div>
              </div>
              <!--
              <div class="form-group form-vertical"
              ng-if='cotizacion_insertar_editar.ESTADO_SEG.DESCRIPCION == "Cotizado" || cotizacion_insertar_editar.ESTADO_SEG.DESCRIPCION == "Firmado"'>
                <label class="control-label col-md-12">Referencia</label>
                <div class="col-md-12">
                  <input type="text" ng-model="cotizacion_insertar_editar.REFERENCIA" required="required" class="form-control col-md-7 col-xs-12"
                  ng-change="cotizacion_insertar_editar.REFERENCIA = cotizacion_insertar_editar.REFERENCIA.toUpperCase()">
                </div>
              </div>
              -->
              <div class="form-group form-vertical">
                <label class="control-label col-md-12">Servicio <span class="required">*</span></label>
                <div class="col-md-12">
                  <select id="selectServicio" ng-model="cotizacion_insertar_editar.ID_SERVICIO" class="form-control">
                     <option value="1">Certificación de Sistemas de Gestión</option>
                  </select>
                </div>
              </div>
              <div class="form-group form-vertical">
                <label class="control-label col-md-12">Tipo de servicio <span class="required">*</span></label>
                <div class="col-md-12">
                  <select id="selectTipoServicio" ng-model="cotizacion_insertar_editar.ID_TIPO_SERVICIO" class="form-control"
                  ng-options="item_servicio.ID as item_servicio.NOMBRE for item_servicio in Tipos_Servicio">
                  </select>
                </div>
              </div>
              <div class="form-group form-vertical">
                <label class="control-label col-md-12">Norma <span class="required">*</span></label>
                <div class="col-md-12">
                  <select id="selectNorma" ng-model="cotizacion_insertar_editar.ID_NORMA" class="form-control"
                  ng-options="norma.ID_NORMA as norma.ID_NORMA for norma in item_servicio.NORMAS">
                  </select>
                </div>
              </div>
              <div class="form-group form-vertical">
                <label class="control-label col-md-12">Complejidad <span class="required">*</span></label>
                <div class="col-md-12">
                  <select id="complejidad" ng-model="cotizacion_insertar_editar.COMPLEJIDAD" class="form-control">
                    <option value="" selected disabled>-- selecciona la complejidad --</option>
                    <option value="alta">Alta</option>
                    <option value="media" selected>Media</option>
                    <option value="baja" selected>Baja</option>
                    <option value="limitada" selected>Limitada</option>
                  </select>
                </div>
              </div>

              <div class="form-group" ng-if="opcion_guardar_cotizacion == 'editar'">
                <label class="control-label col-md-4 col-sm-4 col-xs-12">Tarifa Actual</label>
                <div class="col-md-12">
                  <input type="text" ng-model="cotizacion_insertar_editar.TARIFA"  required="required" class="form-control col-md-7 col-xs-12" readonly>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12">Tarifa por Día Auditor<span class="required">*</span></label>
                <div class="col-md-12">
                  <select ng-model="cotizacion_insertar_editar.TARIFA" required="required" class="form-control"
                  ng-options="item_tarifa.tarifa as item_tarifa.descripcion for item_tarifa in Tarifa_Cotizacion">
                    <option value="" selected disabled>---Seleccione una tarifa---</option>
                  </select>
                </div>
              </div>

              <div class="form-group form-vertical">
                <label class="control-label col-md-12">Descuento (%)</label>
                <div class="col-md-12">
                  <input type="numeric" ng-model="cotizacion_insertar_editar.DESCUENTO" required="required" class="form-control col-md-7 col-xs-12">
                </div>
              </div>

              <div class="form-group form-vertical">
                <label class="control-label col-md-12">¿Es SG integral? <span class="required">*</span></label>
                <div class="col-md-12">
                  <select id="selectSGIntegral" ng-model="cotizacion_insertar_editar.SG_INTEGRAL" class="form-control">
                     <option value="" selected disabled>-- selecciona una opción --</option>
                     <option value="si">Si</option>
                     <option value="no">No</option>
                  </select>
                </div>
              </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" id="btnGuardarUsuario" ng-click="cotizacion_guardar()">Guardar</button>
        </div>
      </div>
    </div>
  </div>

 <!-- Modal insertar/actualizar datos generales de la tarifa adicional-->
  <div class="modal fade" id="modalInsertarActualizarTarifaAdicional" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modalTituloTarifaAdicional">Insertar/Actualizar</h4>
        </div>
        <div class="modal-body">
            <form id="demo-form2" style="margin-top: -20px;">
              <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12">Tarifa Adicional<span class="required">*</span></label>
                <div class="col-md-12">
                  <select ng-model="obj_tarifa_adicional.ID_TARIFA_ADICIONAL" required="required" class="form-control"
                  ng-options="item_tarifa.ID as item_tarifa.DESCRIPCION for item_tarifa in arr_tarifa_adicional">
                    <option value="" selected disabled>---Seleccione una tarifa---</option>
                  </select>
                </div>
              </div>

              <div class="form-group form-vertical">
                <label class="control-label col-md-12">Cantidad<span class="required">*</span></label>
                <div class="col-md-12">
                  <input type="numeric" ng-model="obj_tarifa_adicional.CANTIDAD" required="required" class="form-control col-md-7 col-xs-12">
                </div>
              </div>

            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" id="btnGuardarUsuario" ng-click="tarifa_adicional_guardar()">Guardar</button>
        </div>
      </div>
    </div>
  </div>


  <!-- Modal insertar/actualizar datos generales de la cotización-->
  <div class="modal fade" id="modalActualizarCotizacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modalActualizarTituloCotizacion">Actualizar Cotización</h4>
        </div>
        <div class="modal-body">
            <form id="demo-form2" style="margin-top: -20px;">
              <div class="form-group form-vertical">
                <label class="control-label col-md-12">Prospecto/Cliente</label>
                <div class="col-md-12">
                  <input type="text" ng-model="obj_cotizacion.NOMBRE_VISTA"  required="required" class="form-control col-md-7 col-xs-12" readonly>
                </div>
              </div>
              <div class="form-group form-vertical">
                <label class="control-label col-md-4 col-sm-4 col-xs-12">Nuevo Folio</label>
                <div class="col-md-12">
                  <input type="text" ng-model="obj_cotizacion.NUEVO_FOLIO"  required="required" class="form-control col-md-7 col-xs-12" readonly>
                </div>
              </div>
              <div class="form-group form-vertical">
                <label class="control-label col-md-4 col-sm-4 col-xs-12">Referencia</label>
                <div class="col-md-12">
                  <input type="text" ng-model="obj_cotizacion.REFERENCIA"  required="required" class="form-control col-md-7 col-xs-12" readonly>
                </div>
              </div>

              <div class="form-group form-vertical">
                <label class="control-label col-md-12">Tipo de servicio</label>
                <div class="col-md-12">
                  <input type="text" ng-model="obj_cotizacion.TIPOS_SERVICIO.NOMBRE"  required="required" class="form-control col-md-7 col-xs-12" readonly>
                </div>
              </div>
               <div class="form-group form-vertical">
                <label class="control-label col-md-12">Norma</label>
                <div class="col-md-12">
                  <input type="text" ng-model="obj_cotizacion.NORMA.ID"  required="required" class="form-control col-md-7 col-xs-12" readonly>
                </div>
              </div>
              <div class="form-group form-vertical">
                <label class="control-label col-md-4 col-sm-4 col-xs-12">Tarifa Actual</label>
                <div class="col-md-12">
                  <input type="text" ng-model="obj_cotizacion.TARIFA"  required="required" class="form-control col-md-7 col-xs-12" readonly>
                </div>
              </div>
              <div class="form-group form-vertical">
                <label class="control-label col-md-4 col-sm-4 col-xs-12">Descuento (%)</label>
                <div class="col-md-12">
                  <input type="text" ng-model="obj_cotizacion.DESCUENTO"  required="required" class="form-control col-md-7 col-xs-12" readonly>
                </div>
              </div>
              <div class="form-group form-vertical">
                <label class="control-label col-md-4 col-sm-4 col-xs-12">¿Es SG integral?</label>
                <div class="col-md-12">
                  <input type="text" ng-model="obj_cotizacion.SG_INTEGRAL"  required="required" class="form-control col-md-7 col-xs-12" readonly>
                </div>
              </div>
              <div class="form-group form-vertical">
                <label class="control-label col-md-12">El Estado de la Cotización se cambiará al valor por default(Por Cotizar)</label>
              </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" id="btnGuardarUsuario" ng-click="cotizacion_actualizar()">Actualizar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal insertar/actualizar datos generales de la tramite-->
  <div class="modal fade" id="modalInsertarActualizarTramite" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modalTituloTramite">Insertar/Actualizar</h4>
        </div>
        <div class="modal-body">
            <form id="demo-form2" style="margin-top: -20px;">
              <div class="form-group form-vertical">
                <label class="control-label col-md-12">Evento  <span class="required">*</span></label>
                <div class="col-md-12">
                  <select id="selectTramite" ng-model="tramite_insertar_editar.ID_TIPO_AUDITORIA" class="form-control" ng-options="tramite.ID as tramite.TIPO for tramite in arr_tramites">
                     <option value="" selected disabled>-- selecciona un trámite --</option>
                  </select>
                </div>
              </div>

              <div class="form-group form-vertical" >
                <label class="control-label col-md-12"> Viáticos</label>
                <div class="col-md-12">
                  <input type="numeric" ng-model="tramite_insertar_editar.VIATICOS"  required="required" class="form-control col-md-7 col-xs-12">
                </div>
              </div>

              <div class="form-group form-vertical">
                <label class="control-label col-md-12">Descuento(%)</label>
                <div class="col-md-12">
                  <input type="numeric" ng-model="tramite_insertar_editar.DESCUENTO" required="required" class="form-control col-md-7 col-xs-12">
                </div>
              </div>
				<div class="form-group form-vertical">
					<label class="control-label col-md-12">Aumento(%)</label>
					<div class="col-md-12">
						<input type="numeric" ng-model="tramite_insertar_editar.AUMENTO" required="required" class="form-control col-md-7 col-xs-12">
					</div>
				</div>	
	<!--			<div class="form-group form-vertical">
					<label class="control-label col-md-12">Reduccion(%)</label>
					<div class="col-md-12">
						<input type="numeric" ng-model="tramite_insertar_editar.REDUCCION" required="required" class="form-control col-md-7 col-xs-12">
					</div>
				</div>	
				<div class="form-group form-vertical" ng-show="false">
					<label class="control-label col-md-12">D&iacuteas para multisitio</label>
					<div class="col-md-12">
						<input type="numeric" ng-model="tramite_insertar_editar.DIAS_MULTISITIO" required="required" class="form-control col-md-7 col-xs-12">
					</div>
				</div>	-->
         
             
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" opcion="{{opcion_guardar_tramite}}" id="btnGuardar" ng-click="tramite_guardar()">Guardar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal insertar sitio para la cotización-->
  <div class="modal fade" id="modalInsertarActualizarSitioCotizacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modalTituloSitioCotizacion">Insertar/Actualizar</h4>
        </div>
        <div class="modal-body">
            <form id="demo-form2" style="margin-top: -20px;">
               <div class="form-group form-vertical">
                <label class="control-label col-md-12">Nombre del Domicilio <span class="required">*</span></label>
                <div class="col-md-12">
                  <select id="selectDom" ng-model="obj_sitio.ID_DOMICILIO_SITIO" class="form-control" ng-options="dom.ID as dom.NOMBRE for dom in listaDomicilios">
                     <option value="" selected disabled>-- selecciona un domicilio --</option>
                  </select>
                </div>
              </div>
			  <div class="form-group form-vertical"  ng-if="obj_cotizacion.NORMAS[0].ID_NORMA=='NMX-AA-120-SCFI-2006'||obj_cotizacion.NORMAS[0].ID_NORMA=='NMX-AA-120-SCFI-2016'">
                <label class="control-label col-md-12">Longitud de la playa <span class="required">*</span></label>
                <div class="col-md-12">
                  <input type="numeric" ng-model="obj_sitio.LONGITUD_PLAYA" required="required" class="form-control col-md-7 col-xs-12">
                </div>
              </div>
<!--              <div class="form-group form-vertical">
                <label class="control-label col-md-12">Factor de Reducción (%)<span class="required">*</span></label>
                <div class="col-md-12">
                  <input type="numeric" ng-model="obj_sitio.FACTOR_REDUCCION" required="required" class="form-control col-md-7 col-xs-12">
                </div>
              </div>
               <div class="form-group form-vertical">
                <label class="control-label col-md-12">Factor de Ampliación (%)<span class="required">*</span></label>
                <div class="col-md-12">
                  <input type="numeric" ng-model="obj_sitio.FACTOR_AMPLIACION" required="required" class="form-control col-md-7 col-xs-12">
                </div>
              </div>
              <div class="form-group form-vertical">
                <label class="control-label col-md-12">Justificación <span class="required">*</span></label>
                <div class="col-md-12">
                  <textarea rows="4" cols="50" ng-model="obj_sitio.JUSTIFICACION" required="required" class="form-control col-md-7 col-xs-12">
                  </textarea>
                </div>
              </div>
              <div class="form-group form-vertical">
                <label class="control-label col-md-12">¿Temporal o fijo? <span class="required">*</span></label>
                <div class="col-md-12">
                  <select  ng-model="obj_sitio.TEMPORAL_O_FIJO" class="form-control">
                     <option value="" selected disabled>-- selecciona una opción --</option>
                     <option value="temporal">Temporal</option>
                     <option value="fijo">Fijo</option>
                  </select>
                </div>
              </div>
              <div class="form-group form-vertical">
                <label class="control-label col-md-12">¿Matriz principal? <span class="required">*</span></label>
                <div class="col-md-12">
                  <select  ng-model="obj_sitio.MATRIZ_PRINCIPAL" class="form-control">
                     <option value="" selected disabled>-- selecciona una opción --</option>
                     <option value="si">Si</option>
                     <option value="no">No</option>
                  </select>
                </div>
              </div>  -->
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary"  id="btnGuardarUsuario" ng-click="cotizacion_sitio_guardar()">Guardar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalAgregarEventos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modalTituloAgregarEventos">Agregar eventos</h4>
        </div>
        <div class="modal-body">
            <form id="demo-form2" style="margin-top: -20px;">

              <div class="form-group form-vertical">
                <label class="control-label col-md-12">Referencia</label>
                <div class="col-md-12">
                  <input type="text" ng-model="servicio_insertar.REFERENCIA"  required="required" class="form-control col-md-7 col-xs-12" readonly>
                </div>
              </div>

              <div class="form-group form-vertical" ng-if="obj_cotizacion.BANDERA == 0">
                <label class="control-label col-md-12">Cliente </label>
                <div class="col-md-12">
                  <input type="text" ng-model="servicio_insertar.NOMBRE_CLIENTE"  required="required" class="form-control col-md-7 col-xs-12" readonly>
                </div>
              </div>

              <div class="form-group form-vertical">
                <label class="control-label col-md-12">Servicio</label>
                <div class="col-md-12">
                  <input type="text" ng-model="servicio_insertar.NOMBRE_SERVICIO"  required="required" class="form-control col-md-7 col-xs-12" readonly>
                </div>
              </div>

              <div class="form-group form-vertical">
                <label class="control-label col-md-12">Tipo de servicio</label>
                <div class="col-md-12">
                  <input type="text" ng-model="servicio_insertar.NOMBRE_TIPO_SERVICIO"  required="required" class="form-control col-md-7 col-xs-12" readonly>
                </div>
              </div>

              <div class="form-group form-vertical">
					      <label class="control-label col-md-12">Normas</label>
                    <div class="col-md-12" ng-repeat="norma in obj_cotizacion.NORMAS">
                        <span>{{norma.ID_NORMA}}</span>
                    </div>
				      </div>
              <!--
              <div class="form-group form-vertical">
                <label class="control-label col-md-12">Norma</label>
                <div class="col-md-12">
                  <input type="text" ng-model="servicio_insertar.NOMBRE_NORMA"  required="required" class="form-control col-md-7 col-xs-12" readonly>
                </div>
              </div>
              -->
              <div class="form-group form-vertical" id="etapa">
                <label class="control-label col-md-12" for="etapa">Etapa<span class="required">*</span>
                </label>
                <div class="col-md-12">
                  <select class="form-control"
                  ng-model="servicio_insertar.ID_ETAPA"
                  ng-change="cambioEtapa()"
                  ng-options="etapa.ID as etapa.NOMBRE for etapa in Etapas">
                  </select>
                </div>
              </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" id="btnGuardarUsuario" ng-click="crear_servicio()">Guardar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalAddServicio" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modalTituloServicio">Crear Servicio</h4>
        </div>
        <div class="modal-body">
            <form id="demo-form2" style="margin-top: -20px;">

              <div class="form-group form-vertical">
                <label class="control-label col-md-12">Referencia</label>
                <div class="col-md-12">
                  <input type="text" ng-model="servicio_insertar.REFERENCIA"  required="required" class="form-control col-md-7 col-xs-12" readonly>
                </div>
              </div>

              <div class="form-group form-vertical" ng-if="obj_cotizacion.BANDERA == 0">
                <label class="control-label col-md-12">Cliente </label>
                <div class="col-md-12">
                  <input type="text" ng-model="servicio_insertar.NOMBRE_CLIENTE"  required="required" class="form-control col-md-7 col-xs-12" readonly>
                </div>
              </div>

              <div class="form-group form-vertical">
                <label class="control-label col-md-12">Servicio</label>
                <div class="col-md-12">
                  <input type="text" ng-model="servicio_insertar.NOMBRE_SERVICIO"  required="required" class="form-control col-md-7 col-xs-12" readonly>
                </div>
              </div>

              <div class="form-group form-vertical">
                <label class="control-label col-md-12">Tipo de servicio</label>
                <div class="col-md-12">
                  <input type="text" ng-model="servicio_insertar.NOMBRE_TIPO_SERVICIO"  required="required" class="form-control col-md-7 col-xs-12" readonly>
                </div>
              </div>

              <div class="form-group form-vertical">
					      <label class="control-label col-md-12">Normas</label>
                    <div class="col-md-12" ng-repeat="norma in obj_cotizacion.NORMAS">
                        <span>{{norma.ID_NORMA}}</span>
                    </div>
				      </div>
              <!--
              <div class="form-group form-vertical">
                <label class="control-label col-md-12">Norma</label>
                <div class="col-md-12">
                  <input type="text" ng-model="servicio_insertar.NOMBRE_NORMA"  required="required" class="form-control col-md-7 col-xs-12" readonly>
                </div>
              </div>
              -->
              <div class="form-group form-vertical" id="etapa">
                <label class="control-label col-md-12" for="etapa">Etapa<span class="required">*</span>
                </label>
                <div class="col-md-12">
                  <select class="form-control"
                  ng-model="servicio_insertar.ID_ETAPA"
                  ng-change="cambioEtapa()"
                  ng-options="etapa.ID as etapa.NOMBRE for etapa in Etapas">
                  </select>
                </div>
              </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" id="btnGuardarUsuario" ng-click="crear_servicio()">Guardar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalAddServicioCliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modalTituloServicioCliente">Agregar eventos al Servicio</h4>
        </div>
        <div class="modal-body">
            <form id="demo-form2" style="margin-top: -20px;">

              <div class="form-group form-vertical">
                <label class="control-label col-md-12">Referencia</label>
                <div class="col-md-12">
                  <input type="text" ng-model="servicio_insertar.REFERENCIA"  required="required" class="form-control col-md-7 col-xs-12" readonly>
                </div>
              </div>

              <div class="form-group form-vertical" ng-if="obj_cotizacion.BANDERA == 0">
                <label class="control-label col-md-12">Cliente </label>
                <div class="col-md-12">
                  <input type="text" ng-model="servicio_insertar.NOMBRE_CLIENTE"  required="required" class="form-control col-md-7 col-xs-12" readonly>
                </div>
              </div>

              <div class="form-group form-vertical">
                <label class="control-label col-md-12">Servicio</label>
                <div class="col-md-12">
                  <input type="text" ng-model="servicio_insertar.NOMBRE_SERVICIO"  required="required" class="form-control col-md-7 col-xs-12" readonly>
                </div>
              </div>

              <div class="form-group form-vertical">
                <label class="control-label col-md-12">Tipo de servicio</label>
                <div class="col-md-12">
                  <input type="text" ng-model="servicio_insertar.NOMBRE_TIPO_SERVICIO"  required="required" class="form-control col-md-7 col-xs-12" readonly>
                </div>
              </div>

              <div class="form-group form-vertical">
					      <label class="control-label col-md-12">Normas</label>
                    <div class="col-md-12" ng-repeat="norma in obj_cotizacion.NORMAS">
                        <span>{{norma.ID_NORMA}}</span>
                    </div>
				      </div>
              <!--
              <div class="form-group form-vertical">
                <label class="control-label col-md-12">Norma</label>
                <div class="col-md-12">
                  <input type="text" ng-model="servicio_insertar.NOMBRE_NORMA"  required="required" class="form-control col-md-7 col-xs-12" readonly>
                </div>
              </div>
              -->
              <div class="form-group form-vertical" id="etapaCliente">
                <label class="control-label col-md-12" for="renovacion">Es renovación?<span class="required">*</span>
                </label>
                <div class="col-md-12">
                  <select class="form-control"
                  ng-model="servicio_insertar.ES_RENOVACION">
                    <option value = 'S' ng-selected="true">Si</option>
                    <option value='N'>No</option>
                  </select>
                  <span class="info mt-4">Cuando es renovación los eventos se agregan al siguiente ciclo del servicio. Cuando no, se agregan al ciclo actual</span>
                </div>
              </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" id="btnGuardarUsuario" ng-click="cargar_eventos_servicio()">Guardar</button>
        </div>
      </div>
    </div>
  </div>
<!--**************************************************************************************-->
<div class="modal fade" id="modalGenerarCotizacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTituloGenerarCotizacion">Generar Cotizacion</h4>
      </div>
      <div class="modal-body">
		<form name="exampleFormGenCotizacion" > <!-- target="VentanaGenerarPDF_TUR" method="POST" action="./generar/pdf/cotizacion_propuesta_tur/index.php"> -->
				<div  class='form-group'>
					<div class='form-group  col-md-4 col-xs-4 col-sm-4'>
						<label class="control-label col-md-4 col-xs-4 col-sm-4">Trámite</label>
					</div>
					<div class='form-group  col-md-4 col-xs-4 col-sm-4'>
						<label class="control-label col-md-4 col-xs-4 col-sm-4">Monto</label>
					</div>
					<div class='form-group  col-md-4 col-xs-4 col-sm-4'>
						<label class="control-label col-md-4 col-xs-4 col-sm-4">Viaticos</label>
					</div>
				</div>

				<div class='form-group' ng-repeat="x in arr_tramites_cotizacion" ng-init="tram_index = $index" >
					<div class='form-group  col-md-4 col-xs-4 col-sm-4'>
						<input type="text" class="form-control" id="formDataGenCotizacion.tramites[$index].TIPO"  ng-model="formDataGenCotizacion.tramites[$index].TIPO" required ng-class="{ error: exampleFormGenCotizacion.tramite.x.$error.required && !exampleForm.$pristine}" disabled >
					</div>
					<div class='form-group  col-md-4 col-xs-4 col-sm-4'>
						<input type="text" class="form-control"  ng-model="formDataGenCotizacion.tramites[$index].TRAMITE_COSTO" required ng-class="{ error: exampleFormGenCotizacion.monto.x.$error.required && !exampleForm.$pristine}" disabled >
					</div>
					<div class='form-group  col-md-4 col-xs-4 col-sm-4'>
						<input type="text" class="form-control"  ng-model="formDataGenCotizacion.tramites[$index].VIATICOS" required ng-class="{ error: exampleFormGenCotizacion.viaticos.x.$error.required && !exampleForm.$pristine}" disabled >
					</div>
					<div class='form-group' ng-repeat="y in tarifa_adicional_tramite_cotizacion_by_tramite[tram_index]" >
						<div class='form-group  col-md-9 col-xs-9 col-sm-9'>
							<input type="text" class="form-control"  ng-model="formDataGenCotizacion.descripcion[tram_index][$index].DESCRIPCION" required ng-class="{ error: exampleFormGenCotizacion.descipcion.$error.required && !exampleForm.$pristine}" ng-if="formDataGenCotizacion.descripcion[tram_index][$index].DESCRIPCION!=''" disabled > 
						</div>
						<div class='form-group  col-md-3 col-xs-3 col-sm-3'>
							<input type="text" class="form-control"  ng-model="formDataGenCotizacion.descripcion[tram_index][$index].TARIFA" required ng-class="{ error: exampleFormGenCotizacion.tarifa.$error.required && !exampleForm.$pristine}" disabled >
						</div>

					</div>

				</div>

				<div class="form-group" ng-if="obj_cotizacion.BANDERA == 0">
						<label class="control-label">Contactos</label>
						<select class="form-control" id="formDataGenCotizacion.contactoprospecto1" ng-model="formDataGenCotizacion.contactoprospecto1"  ng-options="ContactoProspecto1.ID as ContactoProspecto1.NOMBRE for ContactoProspecto1 in ContactoProspectos1"  class="form-control" required ng-class="{ error: exampleFormGenCotizacion.contactoprospecto1.$error.required && !exampleForm.$pristine}" >

						</select>
					</div>
					<div class="form-group" ng-if="obj_cotizacion.BANDERA == 0">
						<label class="control-label">Domicilio</label>
						<select class="form-control" id="formDataGenCotizacion.domicilioprospecto1" ng-model="formDataGenCotizacion.domicilioprospecto1"  ng-options="DomicilioProspecto1.ID as DomicilioProspecto1.NOMBRE for DomicilioProspecto1 in DomicilioProspectos1"  class="form-control" required ng-class="{ error: exampleFormGenCotizacion.domicilioprospecto1.$error.required && !exampleForm.$pristine}" >

						</select>
					</div>
				<div class="form-group" ng-if="obj_cotizacion.BANDERA == 1">
					<label class="control-label">Contactos</label>
					<select class="form-control" id="formDataGenCotizacion.contactoprospecto1" ng-model="formDataGenCotizacion.contactoprospecto1"  ng-options="ContactoCliente1.ID as ContactoCliente1.NOMBRE_CONTACTO for ContactoCliente1 in ContactoClientes1"  class="form-control" required ng-class="{ error: exampleFormGenCotizacion.contactoprospecto1.$error.required && !exampleForm.$pristine}" >

					</select>
				</div>
				<div class="form-group" ng-if="obj_cotizacion.BANDERA == 1">
					<label class="control-label">Domicilio</label>
					<select class="form-control" id="formDataGenCotizacion.domicilioprospecto1" ng-model="formDataGenCotizacion.domicilioprospecto1"  ng-options="DomicilioCliente1.ID as DomicilioCliente1.NOMBRE for DomicilioCliente1 in DomicilioClientes1"  class="form-control" required ng-class="{ error: exampleFormGenCotizacion.domicilioprospecto1.$error.required && !exampleForm.$pristine}" >

					</select>
				</div>
			<input type="submit" class="btn btn-success pull-right mt-2" ng-click="submitFormGenCotizacion(formDataGenCotizacion)" ng-disabled="!exampleFormGenCotizacion.$valid" value="Generar PDF"/>
          </form>
      </div>
      <div class="modal-footer">

      </div>
    </div>
  </div>
</div>
  <!--****************************************************************************************-->
</div>


<script type="text/javascript">
  <?php
    echo "var global_id_cotizacion = " .$_REQUEST["id_cotizacion"] . ";";
  ?>
</script>
