<!-- Modal insertar/actualizar-->
  <div class="modal fade" id="modalInsertarActualizarGastosAuditoria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"  data-backdrop="static" data-keyboard="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="modalTituloSitiosEC">{{modal_titulo_gastos}}</h4>
                    </div>
                    <div class="modal-body"> 
                        <form name="exampleFormGastosAud">
							
                            <div class='form-group' ng-repeat="x in CATALOGO_GASTOS">
                                <label>{{x.NOMBRE}}<span class="required">*</span></label>
                                <input type="text" class="form-control"  id="formDataGastosAud.input{{x.ID}}" ng-model="formDataGastosAud.input[$index].VALOR" required
								ng-disabled = "x.ID == 4 || x.ID == 6"
                                ng-class="{ error: exampleFormGastosAud.x.ID.$error.required && !exampleGastosAud.$pristine}" >
							</div>
							
                            <input type="submit" class="btn btn-success pull-right mt-2" ng-click="submitFormGastosAud(formDataGastosAud)" ng-disabled="!exampleFormGastosAud.$valid" value="Guardar"/>
                        </form>
						
                    </div>                                  
                    <div class="modal-footer">
                       
                    </div>
                </div>
            </div>
        </div>
