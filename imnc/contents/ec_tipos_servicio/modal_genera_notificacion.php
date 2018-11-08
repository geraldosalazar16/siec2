<!-- Modal insertar/actualizar Tipos de Servicio-->
<div class="modal fade" id="modalGeneraNotificacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTituloGeneraNotificacion">CONFIGURA LA NOTIFICACIÓN</h4>
      </div>
      <div class="modal-body">
	  
		<form name="exampleFormGeneraNotificacionPDF"  target="VentanaNotificacionPDF" method="POST" action="./generar/pdf/notificacion_servicio/index.php" >
			<input type="hidden" id="inputIdSCE" name="ID_SCE" value="" />
			<input type="hidden" id="inputIdTA" name="ID_TA" value="" />
			<input type="hidden" id="inputCiclo" name="CICLO" value="" />
            <input type="hidden" id="inputNombreUsuario" name="nombreUsuario" value="" />
            <div class="form-group">
				<label class="control-label"> Domicilio <span class="required">*</span></label>
				<select class="form-control" name="cmbDomicilioNotificacionPDF" ng-model="formDataGeneraNotificacionPDF.cmbDomicilioNotificacionPDF" ng-options="Domicilio.ID as Domicilio.NOMBRE_DOMICILIO for Domicilio in Domicilios" required ng-class="{ error: exampleFormGeneraNotificacionPDF.cmbDomicilioNotificacionPDF.$error.required && !exampleFormGeneraNotificacionPDF.$pristine}" ><!----> 
                </select>
			</div>
	<!--		<div class="form-group" hidden>
				<label class="control-label"> Tipo de notificación <span class="required">*</span></label>
				<select class="form-control" name="cmbTipoNotificacionPDF" ng-model="formDataGeneraNotificacionPDF.cmbTipoNotificacionPDF"  requiredng-class="{ error: exampleFormGeneraNotificacionPDF.cmbTipoNotificacionPDF.$error.required && !exampleFormGeneraNotificacionPDF.$pristine}">
                  <option value="" selected disabled>-- elige una opción --</option>
                  <option value="Notificación / Programa de Auditoría">Notificación</option>
                  <option value="Notificación de Cambios / Programa de Auditoría">Notificación de cambios</option>
				</select>
			</div>		--> 
<!--
            <div class="form-group" hidden>
				<label class="control-label"> Tipo de cambios <span class="required">*</span></label>
				<select class="form-control" name="cmbTipoCambiosPDF" ng-model="formDataGeneraNotificacionPDF.cmbTipoCambiosPDF"   required ng-class="{ error: exampleFormGeneraNotificacionPDF.cmbTipoCambiosPDF.$error.required && !exampleFormGeneraNotificacionPDF.$pristine}">
					<option value="y" selected>Ninguno</option>
					<option value="hemos realizado cambios de técnicos">Cambios de técnicos</option>
					<option value="hemos realizado cambios de fechas">Cambios de fechas</option>
					<option value="hemos realizado cambios de técnicos y de fechas">Cambios de técnicos y de fechas</option>
					<option value="y a los cambio solicitados">Cambios solicitados</option>
                </select>
            </div>		--> 
<!--
            <div class="form-group" hidden>
              <label class="control-label"> ¿Certificacón o mantenimiento? <span class="required">*</span></label>
              <select class="form-control" name="cmbCertificacionMantenimientoPDF" ng-model="formDataGeneraNotificacionPDF.cmbCertificacionMantenimientoPDF"   required ng-class="{ error: exampleFormGeneraNotificacionPDF.cmbCertificacionMantenimientoPDF.$error.required && !exampleFormGeneraNotificacionPDF.$pristine}" >
                  <option value="" selected disabled>-- elige una opción --</option>
                  <option value="su proceso de certificación">Certificación</option>
                  <option value="el mantenimiento">Mantenimiento</option>
                </select> 
            </div>
--> 
            <div class="form-group">
              <label class="control-label" >Nota adicional 1: </label>
              <textarea name="txtNota1PDF" class="form-control" rows="5" ng-model="formDataGeneraNotificacionPDF.txtNota1PDF" ></textarea>
             
            </div>

            <div class="form-group">
              <label class="control-label" >Nota adicional 2: </label>
              <textarea name="txtNota2PDF" class="form-control" rows="5" ng-model="formDataGeneraNotificacionPDF.txtNota2PDF"  ></textarea>
            </div>

            <div class="form-group">
              <label class="control-label" >Nota adicional 3: </label>
             
                <textarea name="txtNota3PDF" class="form-control" rows="5" ng-model="formDataGeneraNotificacionPDF.txtNota3PDF" ></textarea>
             
            </div>

            <div class="form-group">
              <label class="control-label" >¿Quién autoriza?  <span class="required">*</span></label>
              
                <input type="text" name="txtNombreAutorizaPDF"  class="form-control" ng-model="formDataGeneraNotificacionPDF.txtNombreAutorizaPDF"  required ng-class="{ error: exampleFormGeneraNotificacionPDF.txtNombreAutorizaPDF.$error.required && !exampleFormGeneraNotificacionPDF.$pristine}" ><!----> 
             
            </div>
<!--
            <div class="form-group" >
				<label class="control-label" >Cargo de quién autoriza  <span class="required">*</span></label>
				<input type="text" name="txtCargoAutorizaPDF"  class="form-control"  ng-model="formDataGeneraNotificacionPDF.txtCargoAutorizaPDF"  required ng-class="{ error: exampleFormGeneraNotificacionPDF.txtCargoAutorizaPDF.$error.required && !exampleFormGeneraNotificacionPDF.$pristine}">
            </div>
			-->
            <input type="submit" class="btn btn-success pull-right mt-2" ng-click="submitFormGeneraNotificacionPDF(formDataGeneraNotificacionPDF)" ng-disabled="!exampleFormGeneraNotificacionPDF.$valid" value="Generar PDF"/>
          </form>
		  
      </div>
      <div class="modal-footer">
        
      </div>
    </div>
  </div>
</div>