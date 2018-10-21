<!-- Modal insertar/actualizar-->
  <div class="modal fade" id="modalInsertarActualizar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-backdrop="static" data-keyboard="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="modalTitulo">{{modal_titulo}}</h4>
                    </div>
                    <div class="modal-body"> 
                        <form name="exampleForm">
                            <div class='form-group'>
                                <label for="txtReferencia">Referencia<span class="required">*</span></label>
                                <input type="text" class="form-control" name="txtReferencia" id="txtReferencia" ng-model="formData.txtReferencia" required
                                ng-class="{ error: exampleForm.txtReferencia.$error.required && !exampleForm.$pristine}" ng-disabled="formData.txtReferencia || accion=='editar'">
                            </div>
                            <div class="form-group">
                                <label for="claveCliente">Cliente<span class="required">*</span></label>
                                <select ng-model="formData.claveCliente" ng-options="claveCliente.ID as claveCliente.NOMBRE for claveCliente in claveClientes" 
                                class="form-control" id="claveCliente" name="claveCliente" ng-change='cambioclaveCliente()' required
                                ng-class="{ error: exampleForm.claveCliente.$error.required && !exampleForm.$pristine}" ng-disabled="accion=='editar'"></select>
                            </div>
                            <div class="form-group">
                                <label for="claveServicio">Servicio<span class="required">*</span></label>
                                <select ng-model="formData.claveServicio" ng-options="claveServicio.ID as claveServicio.NOMBRE for claveServicio in claveServicios" 
                                class="form-control" id="claveServicio" name="claveServicio" ng-change='cambioclaveServicio(formData.claveServicio)' required
                                ng-class="{ error: exampleForm.claveServicio.$error.required && !exampleForm.$pristine}" ng-disabled="accion=='editar'"></select>
                            </div>
							<div class="form-group">
                                <label for="sel_tipoServicio">Tipo de Servicio para generar Referencia<span class="required">*</span></label>
                                <select ng-model="formData.sel_tipoServicio" ng-options="sel_tipoServicio.ID as sel_tipoServicio.NOMBRE for sel_tipoServicio in sel_tipoServicios" 
                                class="form-control" id="sel_tipoServicio" name="sel_tipoServicio" ng-change='cambiosel_tipoServicio(formData.sel_tipoServicio)' required
                                ng-class="{ error: exampleForm.sel_tipoServicio.$error.required && !exampleForm.$pristine}" ng-disabled="!formData.claveServicio || accion=='editar'" ></select>
                            </div>
							<div class="form-group">
                                <label for="Norma">Norma<span class="required">*</span></label>
                                <multiple-autocomplete ng-model="formData.Normas" 
                                object-property="ID_NORMA"
                                suggestions-arr="Normas"
                                ng-class="{ error: exampleForm.Norma.$error.required && !exampleForm.$pristine}" 
                                ng-disabled="!formData.sel_tipoServicio || accion=='editar'" >
                                </multiple-autocomplete>
                                <!--
                                <select ng-model="formData.Norma" ng-options="Norma.ID_NORMA as Norma.ID_NORMA for Norma in Normas" 
                                class="form-control" id="Norma" name="Norma" ng-change='cambioNorma()' required
                                ng-class="{ error: exampleForm.Norma.$error.required && !exampleForm.$pristine}" 
                                ng-disabled="!formData.sel_tipoServicio || accion=='editar'" ></select>
                                -->
                            </div>
							 <div class="form-group">
                                <label for="etapa">Etapa<span class="required">*</span></label>
                                <select ng-model="formData.etapa" ng-options="etapa.ID_ETAPA as etapa.ETAPA for etapa in Etapas" 
                                class="form-control" id="etapa" name="etapa" ng-change='cambioEtapa()' required
                                ng-class="{ error: exampleForm.etapa.$error.required && !exampleForm.$pristine}" ng-if="accion == 'editar'" ng-disabled="!formData.claveServicio"></select>
								<select ng-model="formData.etapa" ng-options="etapa.ID_ETAPA as etapa.ETAPA for etapa in Etapas1" 
                                class="form-control" id="etapa" name="etapa" ng-change='cambioEtapa()' required
                                ng-class="{ error: exampleForm.etapa.$error.required && !exampleForm.$pristine}" ng-if="accion == 'insertar' && formData.claveServicio==1" ng-disabled="!formData.claveServicio"></select>
								<select ng-model="formData.etapa" ng-options="etapa.ID_ETAPA as etapa.ETAPA for etapa in Etapas2" 
                                class="form-control" id="etapa" name="etapa" ng-change='cambioEtapa()' required
                                ng-class="{ error: exampleForm.etapa.$error.required && !exampleForm.$pristine}" ng-if="accion == 'insertar' && formData.claveServicio==2" ng-disabled="!formData.claveServicio"></select>
                            </div>
							<div class="form-group" ng-show="accion=='editar'">
                                <label for="cambio">¿Hay Cambio?</label>
                                <select ng-model="formData.cambio"  
                                class="form-control" id="cambio" name="cambio" ng-disabled='formData.cambio=="S"' 
                                ng-class="{ error: exampleForm.cambio.$error.required && !exampleForm.$pristine}" >
									<option value="S">Si</option>
									<option value="N">No</option>
								</select>
                            </div>
							<!--
							<div class="form-group" ng-show="accion=='editar' && formData.cambio=='S'" ng-repeat="y in Cambios">
								<label >
									<input type="checkbox" checklist-model="formData.chk.ID" checklist-value="y.ID"> {{y.NOMBRE}}
								</label>
								<div ng-if="formData.chk.ID == y.ID">
									<label class='control-label col-md-12' >Descripción del Cambio {{y.CAMBIO}}<span class='required'>*</span></label>
									<div class='col-md-12' >
										<textarea rows='4' ng-model="formData.descripcion[$index]" cols='50' type='text' required='required' class="'form-control col-md-7 col-xs-12' " ></textarea> 
									</div>	
								</div>
							</div>	-->
						  	<div class="form-group" ng-show="accion=='editar' && formData.cambio=='S'" ng-repeat="y in Cambios" >
								
                              <label >
									<input type='checkbox' id="formData.chk{{y.ID}}" ng-model="formData.chk[y.ID]"  ng-disabled="formData.chk[y.ID]"/>
										{{y.NOMBRE}}
								</label>
								<div ng-if="formData.chk[y.ID]">
									<label class='control-label col-md-12' id_cambio="y.ID">Descripción del Cambio {{y.CAMBIO}}<span class='required'>*</span></label>
									<div class='col-md-12' >
										<textarea rows='4' ng-model="formData.descripcion[y.ID]" cols='50' type='text' required='required' class="'form-control col-md-7 col-xs-12' " ></textarea> 
									</div>	
								</div>
                            </div>	
                            <input type="submit" class="btn btn-success pull-right mt-2" ng-click="submitForm(formData)" ng-disabled="!exampleForm.$valid" value="Guardar"/>
                        </form>
                    </div>                                  
                    <div class="modal-footer">
                        <!--
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" ng-click="guardarUsuario()" id="btnGuardarUsuario">Save</button>
                        -->
                        
                    </div>
                </div>
            </div>
        </div>

<!-- 
<div class="modal fade" id="modalInsertarActualizar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTitulo">Insertar/Actualizar</h4>
      </div>
      <div class="modal-body">
          <form id="demo-form2" data-parsley-validate="" class="" novalidate="" style="margin-top: -20px;">
            <div class="form-group form-vertical" style="display: none;">
              <label class="control-label col-md-12" for="txtClave">Clave<span class="required"></span>
              </label>
              <div class="col-md-12">
                <input type="text" id="txtClave" ng-model="txtClave" placeholder="asignado automáticamente" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="4103"><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
				<span id="txtClaveerror" class="text-danger"></span>
              </div>
            </div>
            <div class="form-group form-vertical">
              <label class="control-label col-md-12" for="txtReferencia">Referencia <span class="required">*</span>
              </label>
              <div class="col-md-12">
                <input type="text" id="txtReferencia" ng-model="txtReferencia" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="4103" ><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
				<span id="txtReferenciaerror" class="text-danger"></span>
              </div>
            </div>
            <div class="form-group form-vertical">
              <label class="control-label col-md-12">Cliente <span class="required">*</span>
              </label>
              <div class="col-md-12">
                <select class="form-control" ng-model="claveCliente" >
					<option value="" selected>--elige una opcion--</option>
					<option ng-repeat="option in clave_Cliente" value="{{option.ID}}">{{option.NOMBRE}}</option>
                </select>
                <ul class="parsley-errors-list" id="parsley-id-2324"></ul>
				<span id="claveClienteerror" class="text-danger"></span>
              </div>
            </div>
            <div class="form-group form-vertical">
              <label class="control-label col-md-12" >Servicio <span class="required">*</span>
              </label>
              <div class="col-md-12">
                <select class="form-control" ng-model="claveServicio"id="claveServicio">
					<option value="" selected>--elige una opcion--</option>
					<option ng-repeat="option in clave_Servicio" value="{{option.ID}}">{{option.NOMBRE}}</option>
                </select>
                <ul class="parsley-errors-list" id="parsley-id-2324"></ul>
				<span id="claveServicioerror" class="text-danger"></span>
              </div>
            </div>
			<div class="form-group form-vertical">
              <label class="control-label col-md-12" for="sel_tipoServicio">Tipo Servicio para generar referencia
              </label>
              <div class="col-md-12">
                <select class="form-control" id="sel_tipoServicio">
                  
                </select>
                <ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>

            <div class="form-group form-vertical">
              <label class="control-label col-md-12" for="claveEtapaProceso">Trámite <span class="required">*</span>
              </label>
              <div class="col-md-12">
                <select class="form-control" id="claveEtapaProceso">
                  
                </select>
                <ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
			
			
            <div class="form-group form-vertical" id="campoReferenciaSeguimiento" hidden>
              <label class="control-label col-md-12" for="claveReferenciaSeguimiento">Referencia del Seguimiento<span class="required">*</span>
              </label>
              <div class="col-md-12">
                <select class="form-control" id="claveReferenciaSeguimiento">
                  
                </select>
                <ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>

            <div class="form-group form-vertical" id="campoSgIntegral" style="display: none;">
              <label class="control-label col-md-12" id="lblIntegral">¿Es integral? <span class="required">*</span>
              </label>
              <div class="col-md-12">
                <select class="form-control" id="sgIntegral">

                </select>
              </div>
            </div>
             <div class="form-group form-vertical" id="campoDescripcion" style="display: none;">
              <label class="control-label col-md-12" for="txtDescripcion" >Descripción del Trámite<span class="required">*</span>
              </label>
              <div class="col-md-12" >
                <textarea rows="4" id="txtDescripcion" cols="50" type="text" required="required" class="form-control col-md-7 col-xs-12"  data-parsley-id="2324">
                </textarea>
                <ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
            <div class="form-group form-vertical">
              <label class="control-label col-md-12">¿Hay Cambio? <span class="required">*</span>
              </label>
              <div class="col-md-12">
                <select class="form-control" id="cambio">

                </select>
              </div>
            </div>

            <div id="cambioCheckbox" hidden>

            </div>
            <div id="cambioDescripcionForm">
               
            </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnGuardar">Guardar</button>
      </div>
    </div>
  </div>
</div>
-->