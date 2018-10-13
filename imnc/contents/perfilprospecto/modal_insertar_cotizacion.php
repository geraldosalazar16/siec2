<!-- Modal insertar/actualizar-->
<div class="modal fade" id="modalInsertarActualizarCotizacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modalTituloCotizacion">Insertar Cotización</h4>
        </div>
        <div class="modal-body">
            <form id="demo-form2" style="margin-top: -20px;">

              <div class="form-group form-vertical">
                <label class="control-label col-md-4 col-sm-4 col-xs-12">Estado de la cotización<span class="required">*</span></label>
                <div class="col-md-12">
                  <select ng-model="cotizacion_insertar_editar.ESTADO_SEG" required="required" class="form-control" 
                  ng-options="estseg as estseg.DESCRIPCION for estseg in EstatusSeguimiento track by estseg.ID">
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

              <div class="form-group form-vertical">
				        <label class="control-label col-md-12">Servicio</label>
                    <div class="col-md-12">
                        <select ng-model="cotizacion_insertar_editar.ID_SERVICIO" 
                        ng-change="cotizacion_insertar_editar.ID_SERVICIO.cambio()"
                        ng-disabled="true" 
                        class="form-control"
                        ng-options="servicio.id as servicio.nombre for servicio in Areas"> 
                            <option value="" ng-selected="true" disabled>Seleccione una opción</option>
                        </select>
                    </div>
			        </div>

              <div class="form-group form-vertical">	
					      <label class="control-label col-md-12">Tipo de servicio</label>
                    <div class="col-md-12">
                        <select ng-model="cotizacion_insertar_editar.ID_TIPO_SERVICIO" 
                        ng-change="cotizacion_insertar_editar.ID_TIPO_SERVICIO.cambio()" 
                        ng-disabled="true" class="form-control" 
                        ng-options="tipo_servicio.id as tipo_servicio.nombre for tipo_servicio in Departamentos"> 
                            <option value="" ng-selected="true" disabled>Seleccione una opción</option>
                        </select>
                    </div>
		          </div>

              <div class="form-group form-vertical">
					      <label class="control-label col-md-12">Normas</label>
                    <div class="col-md-12" ng-repeat="norma in cotizacion_insertar_editar.NORMAS">
                        <span>{{norma.ID_NORMA}}</span>
                    </div>
				      </div>
              <!--
              <div class="form-group form-vertical">
                <label class="control-label col-md-12">Etapa<span class="required">*</span></label>
                <div class="col-md-12">
                  <select id="selectEtapa" ng-model="cotizacion_insertar_editar.ETAPA" 
                  ng-options="etapa.ID_ETAPA as etapa.ETAPA for etapa in Etapas" class="form-control">
                     <option value="" selected disabled>-- selecciona una etapa --</option>
                  </select>
                </div>
              </div>
              -->
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

              <div class="form-group form-vertical">
                <label class="control-label col-md-4 col-sm-4 col-xs-12">Tarifa por Día Auditor<span class="required">*</span></label>
                <div class="col-md-12">
                  <select ng-model="cotizacion_insertar_editar.TARIFA" required="required" class="form-control" 
                  ng-options="item_tarifa.ID as item_tarifa.DESCRIPCION for item_tarifa in Tarifas">
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
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" id="btnGuardarUsuario" ng-click="cotizacion_guardar()">Guardar</button>
        </div>
      </div>
    </div>
  </div>