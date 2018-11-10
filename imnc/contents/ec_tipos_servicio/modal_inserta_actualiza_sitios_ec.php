<!-- Modal insertar/actualizar-->
  <div class="modal fade" id="modalInsertarActualizarSitiosEC" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"  data-backdrop="static" data-keyboard="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="modalTituloSitiosEC">{{modal_titulo_sitio}}</h4>
                    </div>
                    <div class="modal-body"> 
                        <form name="exampleFormSitiosEC"  ng-show="cant_MetaDatosSitios != 0">
							<div class="form-group">
								<label 	for="cmbClaveClienteDomSitio">Nombre del sitio a auditar <span class="required">*</span></label>
								<select ng-model="formDataSitiosEC.cmbClaveClienteDomSitio" ng-options="ClientesDomicilio.ID as ClientesDomicilio.NOMBRE for ClientesDomicilio in ClientesDomicilios"  class="form-control" id="cmbClaveClienteDomSitio" name="cmbClaveClienteDomSitio" ng-change='cambiocmbClaveClienteDomSitiosEC()' required ng-class="{ error: exampleFormSitiosEC.cmbClaveClienteDomSitio.$error.required && !exampleFormSitiosEC.$pristine}"></select>
							</div>
                            <div class='form-group' ng-repeat="x in MetaDatosSitios">
                                <label>{{x.NOMBRE}}<span class="required">*</span></label>
                                <input type="text" class="form-control"  id="formDataSitiosEC.input{{x.ID}}" ng-model="formDataSitiosEC.input[x.ID]" required
                                ng-class="{ error: exampleFormSitiosEC.x.ID.$error.required && !exampleFormSitiosEC.$pristine}" ng-if="x.TIPO==0"  ng-change='MD{{x.ID}}(formDataSitiosEC.input[x.ID])'>
								<textarea  type='text' class="form-control" ng-model="formDataSitiosEC.input[x.ID]" cols='50' rows='4' required='required' ng-class="{ error: exampleFormSitiosEC.x.ID.$error.required && !exampleFormSitiosEC.$pristine}" ng-if="x.TIPO==1" ></textarea>
								<select ng-model="formDataSitiosEC.input[x.ID]" ng-options="y.ID as y.OPCION for y in YsSitios[x.ID]"  class="form-control" ng-change='ys()' required ng-class="{ error: exampleFormSitiosEC.y.$error.required && !exampleFormSitiosEC.$pristine}" ng-if="x.TIPO==2" ></select>	
								
								
							
							
                            </div>
							
                            <input type="submit" class="btn btn-success pull-right mt-2" ng-click="submitFormSitiosEC(formDataSitiosEC)" ng-disabled="!exampleFormSitiosEC.$valid" value="Guardar"/>
                        </form>
						<span ng-show="cant_MetaDatosSitios == 0">No puede insertar sitios para este tipo de servicio. Es necesario configurar sus metadatos.</span>
                    </div>                                  
                    <div class="modal-footer">
                       
                    </div>
                </div>
            </div>
        </div>
