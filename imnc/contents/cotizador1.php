<div class="right_col" role="main"  ng-controller="cotizador_controller as $ctrl" ng-init='despliega_cotizaciones()' ng-cloak>
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>Cotizaciones</h2></p>
        <p>
          <button type="button" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc" style="float: right;"  ng-click='modal_cotizacion_insertar()'
          ng-if='modulo_permisos["registrar"] == 1'> 
            <i class="fa fa-plus"> </i> Agregar cotización 
          </button>
        </p>
          <div class="clearfix"></div>
        </div>

        <div class="x_content">

          <table class="table table-striped responsive-utilities jambo_table bulk_action">
            <thead>
              <tr class="headings">
                <th class="column-title">ID</th>
                <th class="column-title">
                  Prospecto, tipo de servicio y norma
                </th>
                <th class="column-title">Folio</th>
                <th class="column-title">Estado</th>
                <th class="column-title">Tarifa día auditor</th>
                <th class="column-title">¿SG Integral?</th>
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
                  {{cotizacion.TIPOS_SERVICIO.NOMBRE}}<br>
                  <i>{{cotizacion.NORMA.ID}}</i>
                </td>
                <td>{{cotizacion.FOLIO}}</td>
                <td>{{cotizacion.ESTADO.ESTATUS_SEGUIMIENTO}}</td>
                <td>{{cotizacion.TARIFA | currency}}</td>
                <td>{{cotizacion.SG_INTEGRAL}}</td>
                <td>
                  <button type="button" class="btn btn-primary btn-xs btn-imnc btnEditar" ng-click="modal_cotizacion_editar(cotizacion.ID)"
                  ng-if='modulo_permisos["editar"] == 1 && cotizacion.ESTADO.ESTATUS_SEGUIMIENTO != "Firmado"' style="float: right;">
                    <i class="fa fa-edit"></i> Editar datos
                  </button>
                </td>
                <td>
                  <a type="button" class="btn btn-primary btn-xs btn-success btnVerCotizacion" href="./?pagina=ver_cotizacion&id_cotizacion={{cotizacion.ID}}" style="float: right;">
                    <i class="fa fa-bullseye"></i> Ver cotización 
                  </a>
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
                  <select id="selectProspecto" ng-model="cotizacion_insertar_editar.PROSPECTO" class="form-control" ng-options="prospecto as prospecto.NOMBRE for prospecto in arr_prospectos track by prospecto.ID">
                     <option value="" selected disabled>-- selecciona un prospecto --</option>
                  </select>
                </div>
              </div>

              <div class="form-group form-vertical" id="comboCliente" ng-if="bandera==1">
                <label class="control-label col-md-12">Cliente <span class="required">*</span></label>
                <div class="col-md-12">
                  <select id="selectCliente" ng-model="cotizacion_insertar_editar.CLIENTE" class="form-control" ng-options="cliente as cliente.NOMBRE for cliente in arr_clientes track by cliente.ID">
                     <option value="" selected disabled>-- selecciona un cliente --</option>
                  </select>
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
                <label class="control-label col-md-12"> Folio  <span class="required">*</span></label>
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

              <div class="form-group form-vertical" 
                ng-if='cotizacion_insertar_editar.ESTADO_SEG.DESCRIPCION == "Cotizado" || cotizacion_insertar_editar.ESTADO_SEG.DESCRIPCION == "Firmado"'>
                <label class="control-label col-md-12">Referencia</label>
                <div class="col-md-12">
                  <input type="text" ng-model="cotizacion_insertar_editar.REFERENCIA" required="required" class="form-control col-md-7 col-xs-12"
                  ng-change="cotizacion_insertar_editar.REFERENCIA = cotizacion_insertar_editar.REFERENCIA.toUpperCase()">
                </div>
              </div>

              <div class="form-group form-vertical">
                <label class="control-label col-md-12">Servicio <span class="required">*</span></label>
                <div class="col-md-12">
                  <select id="selectServicio" ng-model="cotizacion_insertar_editar.ID_SERVICIO" ng-change ="fill_select_tramites('')" class="form-control">
                     <option value="" selected disabled>-- selecciona un servicio --</option>
                     <option value="CSG">Certificación de Sistemas de Gestión</option>
                  </select>
                </div>
              </div>
              <div class="form-group form-vertical">
                <label class="control-label col-md-12">Tipo de servicio <span class="required">*</span></label>
                <div class="col-md-12">
                  <select id="selectTipoServicio" ng-model="cotizacion_insertar_editar.ID_TIPO_SERVICIO" class="form-control"
                  ng-options="item_servicio.ID as item_servicio.NOMBRE for item_servicio in Tipos_Servicio">
                     <option value="" selected disabled>-- selecciona un tipo de servicio --</option>
                  </select>
                </div>
              </div>

              <div class="form-group form-vertical">
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
          <button type="button" class="btn btn-primary" opcion="{{opcion_guardar_cotizacion}}" id="btnGuardarUsuario" ng-click="cotizacion_guardar()">Guardar</button>
        </div>
      </div>
    </div>
  </div>

</div>