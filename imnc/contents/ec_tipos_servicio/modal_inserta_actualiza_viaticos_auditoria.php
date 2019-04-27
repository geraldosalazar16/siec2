<!-- Modal insertar/actualizar-->
  <div class="modal fade" id="modalInsertarActualizarViaticosAuditoria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"  data-backdrop="static" data-keyboard="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="modalTituloSitiosEC">{{modal_titulo_viaticos}}</h4>
                    </div>
                    <div class="modal-body"> 
                        <form name="exampleFormViaticosAud">
							
                            <div class='form-group' >
                                <label>Total Vi&aacuteticos <span class="required">*</span></label>
                                <input type="text" class="form-control"  id="formDataViaticosAud" ng-model="formDataViaticosAud.MONTO" required
                                ng-class="{ error: exampleFormViaticosAud.x.ID.$error.required && !exampleViaticosAud.$pristine}" >
							</div>
							
                            <input type="submit" class="btn btn-success pull-right mt-2" ng-click="submitFormViaticosAud(formDataViaticosAud)" ng-disabled="!exampleFormViaticosAud.$valid" value="Guardar"/>
                        </form>
						
                    </div>                                  
                    <div class="modal-footer">
                       
                    </div>
                </div>
            </div>
        </div>
