<!-- Modal insertar/actualizar Sitios-->
<div class="modal fade" id="modalInsertarActualizarParticipante" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTituloParticipante">{{modal_titulo_participante}}</h4>
      </div>
      <div class="modal-body">
		<form name="formParticipante">
            <div class="form-group">
                <label>Razón Social de su Empresa/Institución (o indicar si es "Independiente")<span class="required">*</span></label>
                <div>
                    <input type="text" class="form-control" id="razonSocial" name="razonSocial" ng-model="formDataParticipante.razonSocial"  required
                            ng-class="{ error: formParticipante.razonSocial.$error.required && !formParticipante.$pristine}" >
                    <span id="razonSocialerror" class="text-danger" ></span>
                </div>
            </div>

            <div class="form-group">
                <label>Correo electrónico<span class="required">*</span></label>
                <div>
                    <input type="text" class="form-control" id="emailParticipante" name="emailParticipante" ng-model="formDataParticipante.emailParticipante"  required
                      ng-class="{ error: formParticipante.emailParticipante.$error.required && !formParticipante.$pristine}" >
                    <span id="emailParticipanteerror" class="text-danger" ></span>
                </div>
            </div>

            <div class="form-group">
                <label>Teléfono<span class="required">*</span></label>
                <div>
                    <input type="text" class="form-control" id="telefonoParticipante" name="telefonoParticipante" ng-model="formDataParticipante.telefonoParticipante"  required
                           ng-class="{ error: formParticipante.telefonoParticipante.$error.required && !formParticipante.$pristine}" >
                    <span id="telefonoParticipanteerror" class="text-danger" ></span>
                </div>
            </div>

            <div class="form-group">
                <label>CURP del participante<span class="required">*</span></label>
                <div>
                    <input type="text" class="form-control" id="curpParticipante" name="curpParticipante" ng-model="formDataParticipante.curpParticipante"  required
                           ng-class="{ error: formParticipante.curpParticipante.$error.required && !formParticipante.$pristine}"
                          >
                    <span id="curpParticipanteerror" class="text-danger" ></span>
                </div>
            </div>
            <div class="form-group">
                <label>RFC de su organización<span class="required">*</span></label>
                <div>
                    <input type="text" class="form-control" id="rfcParticipante" name="rfcParticipante" ng-model="formDataParticipante.rfcParticipante"  required
                           ng-class="{ error: formParticipante.rfcParticipante.$error.required && !formParticipante.$pristine}" >
                    <span id="rfcParticipanteerror" class="text-danger" ></span>
                </div>
            </div>
            <div class="form-group">
                <label>Estado del que nos visita<span class="required">*</span></label>
                <select ng-model="formDataParticipante.estadoParticipante" ng-options="estado.ENTIDAD_FEDERATIVA as estado.ENTIDAD_FEDERATIVA for estado in estados"
                        class="form-control" id="estadoParticipante" name="estadoParticipante"  required
                        ng-class="{ error: formParticipante.estadoParticipante.$error.required && !formParticipante.$pristine}" >
                    <option value="">---Seleccione un Estado---</option>
                </select>
                <span id="estadoParticipanteerror" class="text-danger"></span>
            </div>
           <!-- <div class="form-group">
                <label>Estado del que nos visita<span class="required">*</span></label>
                <div>
                    <input type="text" class="form-control" id="estadoParticipante" name="estadoParticipante" ng-model="formDataParticipante.estadoParticipante"  required
                           ng-class="{ error: formParticipante.estadoParticipante.$error.required && !formParticipante.$pristine}" >
                    <span id="estadoParticipanteerror" class="text-danger" ></span>
                </div>
            </div>-->
            <div class="form-group">
                <label>Nombre del ejecutivo comercial que le atendió<span class="required">*</span></label>
                <div>
                    <input type="text" class="form-control" id="comercialParticipante" name="comercialParticipante" ng-model="formDataParticipante.comercialParticipante"
                           ng-class="{ error: formParticipante.comercialParticipante.$error.required && !formParticipante.$pristine}" >
                    <span id="comercialParticipanteerror" class="text-danger" ></span>
                </div>
            </div>


            <div class="modal-footer">
                <input type="submit" id="btnSaveParticipante" class="btn btn-success pull-right" ng-click="submitFormParticipante(formDataParticipante)"  value="Guardar"/>
            </div>

        </form>
      </div>

    </div>
  </div>
</div>