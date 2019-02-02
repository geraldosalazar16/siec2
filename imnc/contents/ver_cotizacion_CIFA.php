<div class="right_col" role="main"  ng-controller="ver_cotizacion_INF_COM_controller as $ctrl" ng-init='despliega_cotizacion()' ng-cloak>
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
                  <li id="lbReferencia" ng-if='bl_cotizado'>
                    Referencia: {{obj_cotizacion.REFERENCIA}}
                  </li>
                  <li id="lbEstado">
                    Estado: {{obj_cotizacion.ESTADO.ESTATUS_SEGUIMIENTO}}
                  </li>
                  <li id="lbModalidad">
                    Modalidad: Curso {{obj_cotizacion.MODALIDAD}}
                  </li>
				  <li id="lbNombre" ng-if='obj_cotizacion.MODALIDAD == "insitu"'>
                    Nombre : {{obj_cotizacion.CURSOS.NOMBRE}}
                  </li>
				  <li id="lbTarifa" ng-if='obj_cotizacion.MODALIDAD == "insitu"'>
                    Precio por d&iacutea : {{obj_cotizacion.CURSOS.PRECIO_INSITU | currency}}
                  </li>
                 <li id="lbDias" ng-if='obj_cotizacion.MODALIDAD == "insitu"'>
                    Total de d&iacuteas : {{obj_cotizacion.CURSOS.DIAS_INSITU}}
                  </li>
				  <li id="lbNombre" ng-if='obj_cotizacion.MODALIDAD == "programado"'>
                    Nombre : {{obj_cotizacion.CURSOS[0].NOMBRE}}
                  </li>
				  
				  <li id="lbTarifa" ng-if='obj_cotizacion.MODALIDAD == "programado"'>
                    Precio por participante : {{obj_cotizacion.CURSOS[0].PRECIO_PROGRAMADO | currency}}
                  </li>
                 <li id="lbDias" ng-if='obj_cotizacion.MODALIDAD == "programado"'>
                    Total de d&iacuteas : {{obj_cotizacion.CURSOS[0].DIAS_PROGRAMADO}}
                  </li>
				  <li id="lbCantParticipantes">
                    Cantidad de Participantes : {{obj_cotizacion.CANT_PARTICIPANTES}}
                  </li>
                  <li id="lbCOt">
                    TOTAL COTIZACION: {{obj_cotizacion.TOTAL_COTIZACION | currency}}
                  </li>
      
                 
              </ul>
            </div>
           
          </div>
          

          <div>			
            <div class="col-md-4 col-sm-4 col-xs-4">
              <ul class="list-unstyled user_data">
                  <li id="lbTotalSitios" ng-if='obj_cotizacion.MODALIDAD == "insitu"'>
                    Total de sitios en cotización: {{obj_cotizacion_datos.COUNT_SITIOS.SITIOS_A_VISITAR}}
                  </li>
                  <li id="lbSitiosVisitar" ng-if='obj_cotizacion.MODALIDAD == "insitu"'>
                    Sitios a visitar: {{obj_cotizacion_datos.COUNT_SITIOS.TOTAL_SITIOS}} de {{obj_cotizacion_datos.COUNT_SITIOS.SITIOS_A_VISITAR}}
                  </li>
                  <li id="lbRestriccion" ng-if='obj_cotizacion.MODALIDAD == "insitu"'>
                    {{obj_cotizacion_datos.COUNT_SITIOS.RESTRICCIONES_SITIOS}}
                  </li>
                  <li>
                    <button type="button" id="btnInsertarSitio" class="btn btn-primary btn-xs btn-imnc" ng-click='modal_sitio_insertar()'
                    ng-if='modulo_permisos["registrar"] == 1 && !bl_cotizado && obj_cotizacion.MODALIDAD == "insitu"' >
                      <i class="fa fa-plus"> </i> Agregar sitio
                    </button>
					<button type="button" id="btnInsertarTarifa" class="btn btn-primary btn-xs btn-imnc" ng-click='modal_tarifa_adicional_insertar()'
                    ng-if='modulo_permisos["registrar"] == 1 && !bl_cotizado'>
                      <i class="fa fa-plus"> </i> Agregar Tarifa Adicional
                    </button>
                  </li>
              </ul>
            </div>

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

          <table class="table table-striped responsive-utilities jambo_table bulk_action" style="margin: 25px 0px 45px;" ng-if='obj_cotizacion.MODALIDAD == "insitu"'>
            <thead>
              <tr class="headings">
                <th class="column-title">#</th>
                <th class="column-title">Nombre del sitio</th>
				 <th class="column-title">Subtotal por sitio</th>
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
				<td ng-if="sitios_cotizacion.SELECCIONADO == 1">
                  {{obj_cotizacion_datos.VALOR_POR_SITIO }}<br>
                 
                </td>
				<td ng-if="sitios_cotizacion.SELECCIONADO == 0">
                  0<br>
                 
                </td>
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

           <div class="alert alert-danger alert-dismissible fade in" role="alert" ng-hide="obj_cotizacion_datos.RESTRICCIONES.length == 0" ng-if='obj_cotizacion.MODALIDAD == "insitu"'>
              <ul class="list-unstyled user_data" ng-repeat="restriccion in obj_cotizacion_datos.RESTRICCIONES">
                  <li>
                    {{restriccion}}
                  </li>
              </ul>
            </div>

            
          </div> 	
          <!--FIN DIV-->
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
		<form name="exampleFormGenCotizacion" target="VentanaGenerarPDF_CIL" method="POST" ><!-- action="./generar/pdf/cotizacion_propuesta_cil/index.php" -->
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
