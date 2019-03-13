<!-- Modal insertar/actualizar Tipos de Servicio-->
<div class="modal fade" id="modalGeneraNotificacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTituloGeneraNotificacion">CONFIGURA LA NOTIFICACIÓN</h4>
      </div>
      <div class="modal-body">
	  
		<form id="exampleFormGeneraNotificacionPDF" name="exampleFormGeneraNotificacionPDF"  target="VentanaNotificacionPDF" method="POST" action="./generar/pdf/notificacion_servicio/index.php" >
			<input type="hidden" id="inputIdSCE" name="ID_SCE" value="" />
			<input type="hidden" id="inputIdTA" name="ID_TA" value="" />
			<input type="hidden" id="inputCiclo" name="CICLO" value="" />
			<input type="hidden" id="inputServicio" name="SERVICIO" value="" />
            <input type="hidden" id="inputNombreUsuario" name="nombreUsuario" value="" />
            <input type="hidden" id="inputNotas" name="txtNotas" value="" />
            <input type="hidden" id="inputNotasEdit" name="txtNotasEdit" value="" />
            <input type="hidden" id="inputDomicilio" name="DOMICILIO" value="" />
            <input type="hidden" id="inputSave" name="SAVE" value="" />
            <div class="form-group">
				<label class="control-label"> Domicilio <span class="required">*</span></label>
				<select class="form-control" name="cmbDomicilioNotificacionPDF" ng-model="formDataGeneraNotificacionPDF.cmbDomicilioNotificacionPDF" ng-options="Domicilio.ID as Domicilio.NOMBRE_DOMICILIO for Domicilio in Domicilios" required ng-class="{ error: exampleFormGeneraNotificacionPDF.cmbDomicilioNotificacionPDF.$error.required && !exampleFormGeneraNotificacionPDF.$pristine}" ><!---->
                </select>
			</div>
            <div class="form-group" style="margin-top: 20px;margin-bottom: 20px;">
                <label class="control-label"> Seleccione el Tipo de Servicio <span class="required">*</span></label><br>
                    <label class="checkbox-inline">
                        <input  id="chckIMNC" type="checkbox" ng-model="formDataGeneraNotificacionPDF.chckIMNC" class="checkbox"  value="true" name="CHCK1" > Servicio en instalaciones del IMNC
                    </label>
                    <label class="checkbox-inline">
                        <input  id="chckSitio" type="checkbox" ng-model="formDataGeneraNotificacionPDF.chckSitio" class="checkbox"  value="true" name="CHCK2" ng-checked="true" ng-init="formDataGeneraNotificacionPDF.chckSitio=true"> Servicio en Sitio
                    </label>

                <label class="text-danger" ng-if="chck_error" style="margin-top: 10px;">Debe seleccionar al menos una opción</label>
            </div>
            <div class="form-group">
              <label class="control-label" >Nota adicional: </label>
              <textarea name="txtNotaPDF" id="txtNotaPDF" class="form-control" rows="5" ng-model="formDataGeneraNotificacionPDF.txtNotaPDF" ></textarea>
              <input type="button" class="btn"  ng-click="addNote(formDataGeneraNotificacionPDF.txtNotaPDF)" value="+ Agregar Nota">
                <br>
                <table class="table" style="width: 100%" ng-if="countnotas>0" >
                    <thead>
                    <th width="10px">#</th>
                    <th width="80%">Nota</th>
                    <th width="10px"></th>
                    </thead>
                    <tr ng-repeat="(index,item) in notas">
                        <td ><strong>{{index+3}}</strong></td>
                        <td style="text-align: justify">{{item}}</td>
                        <td>
                            <input type="button" class="btn btn-default btn-sm pull-right"  ng-click="deleteNote(index)" value=" - ">
                        </td>
                    </tr>
                    <tr></tr>
                </table>
            </div>


            <!--            <div class="form-group">
                          <label class="control-label" >¿Quién autoriza?  <span class="required">*</span></label>

                            <input type="text" name="txtNombreAutorizaPDF"  class="form-control" ng-model="formDataGeneraNotificacionPDF.txtNombreAutorizaPDF"  required ng-class="{ error: exampleFormGeneraNotificacionPDF.txtNombreAutorizaPDF.$error.required && !exampleFormGeneraNotificacionPDF.$pristine}" >

                        </div>-->
            <!--
                        <div class="form-group" >
                            <label class="control-label" >Cargo de quién autoriza  <span class="required">*</span></label>
                            <input type="text" name="txtCargoAutorizaPDF"  class="form-control"  ng-model="formDataGeneraNotificacionPDF.txtCargoAutorizaPDF"  required ng-class="{ error: exampleFormGeneraNotificacionPDF.txtCargoAutorizaPDF.$error.required && !exampleFormGeneraNotificacionPDF.$pristine}">
                        </div>
                        -->
            <input type="button" class="btn btn-success pull-right mt-2" ng-click="submitFormGeneraNotificacionPDF('save')" ng-disabled="!exampleFormGeneraNotificacionPDF.$valid" value="Guardar y Generar PDF"/>
            <input type="button" class="btn btn-success pull-right mt-2" ng-click="submitFormGeneraNotificacionPDF()" ng-disabled="!exampleFormGeneraNotificacionPDF.$valid" value="Generar PDF"/>
          </form>
		  
      </div>
      <div class="modal-footer">
        
      </div>
    </div>
  </div>
</div>