<!-- Modal insertar/actualizar Tipos de Servicio-->
<div class="modal fade" id="modalDictaminacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTituloDictaminacion">ENVIAR A DICTAMINACION</h4>
      </div>
      <div class="modal-body">
	  
		<form name="exampleFormDictaminacion" >
			<input type="hidden" id="inputDictIdSCE" ng-model="formDataDictaminacion.inputDictIdSCE" />
			<input type="hidden" id="inputDictIdTA" ng-model="formDataDictaminacion.inputDictIdTA" />
			<input type="hidden" id="inputDictCiclo" ng-model="formDataDictaminacion.inputDictCiclo" />
            <input type="hidden" id="inputNombreUsuario" name="nombreUsuario" value="" />
            <div class="form-group">
				<label class="control-label"> Dictaminador <span class="required">*</span></label>
				<select class="form-control" name="cmbDomicilioNotificacionPDF" ng-model="formDataDictaminacion.Dictaminador" ng-options="Dictaminador.ID as Dictaminador.NOMBRE_USUARIO for Dictaminador in Dictaminadores" required ng-class="{ error: exampleFormDictaminacion.cmbDomicilioNotificacionPDF.$error.required && !exampleFormDictaminacion.$pristine}" ><!----> 
                </select>
			</div>
	
            <input type="submit" class="btn btn-success pull-right mt-2" ng-click="submitFormDictaminacion(formDataDictaminacion)" ng-disabled="!exampleFormDictaminacion.$valid" value="Enviar a Dictaminar"/>
          </form>
		  
      </div>
      <div class="modal-footer">
        
      </div>
    </div>
  </div>
</div>