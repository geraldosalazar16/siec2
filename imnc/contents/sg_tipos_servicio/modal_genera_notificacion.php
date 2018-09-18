<!-- Modal insertar/actualizar Tipos de Servicio-->
<div class="modal fade" id="modalGeneraNotificacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTituloGeneraNotificacion">CONFIGURA LA NOTIFICACIÓN</h4>
      </div>
      <div class="modal-body">
          <form id="formGeneraNotificacionPDF" target="VentanaNotificacionPDF" method="POST" action="./generar/pdf/notificacion_servicio/" style="margin-top: -20px;">
            <input type="hidden" id="inputIdAuditoria" name="ID_AUDITORIA" value="" />
            <input type="hidden" id="inputNombreUsuario" name="nombreUsuario" value="" />
            <div class="form-group form-vertical">
              <label class="control-label col-md-12"> Domicilio <span class="required">*</span>
              </label>
              <div class="col-md-12">
                <select class="form-control" id="cmbDomicilioNotificacionPDF"  name="cmbDomicilioNotificacionPDF">
                  
                </select>
              </div>
            </div>
			<div class="form-group form-vertical">
              <label class="control-label col-md-12"> Tipo de notificación <span class="required">*</span>
              </label>
              <div class="col-md-12">
                <select class="form-control" name="cmbTipoNotificacionPDF">
                  <option value="" selected disabled>-- elige una opción --</option>
                  <option value="Notificación / Programa de Auditoría">Notificación</option>
                  <option value="Notificación de Cambios / Programa de Auditoría">Notificación de cambios</option>
                </select>
              </div>
            </div>

            <div class="form-group form-vertical">
              <label class="control-label col-md-12"> Tipo de cambios <span class="required">*</span>
              </label>
              <div class="col-md-12">
                <select class="form-control" name="cmbTipoCambiosPDF">
                  <option value="y" selected>Ninguno</option>
                  <option value="hemos realizado cambios de técnicos">Cambios de técnicos</option>
                  <option value="hemos realizado cambios de fechas">Cambios de fechas</option>
                  <option value="hemos realizado cambios de técnicos y de fechas">Cambios de técnicos y de fechas</option>
                  <option value="y a los cambio solicitados">Cambios solicitados</option>
                </select>
              </div>
            </div>

            <div class="form-group form-vertical">
              <label class="control-label col-md-12"> ¿Certificacón o mantenimiento? <span class="required">*</span>
              </label>
              <div class="col-md-12">
                <select class="form-control" name="cmbCertificacionMantenimientoPDF">
                  <option value="" selected disabled>-- elige una opción --</option>
                  <option value="su proceso de certificación">Certificación</option>
                  <option value="el mantenimiento">Mantenimiento</option>
                </select>
              </div>
            </div>

            <div class="form-group form-vertical">
              <label class="control-label col-md-12" >Nota adicional 1: </label>
              <div class="col-md-12">
                <textarea name="txtNota1PDF" class="form-control col-md-7 col-xs-12" rows="5" required="required"></textarea>
              </div>
            </div>

            <div class="form-group form-vertical">
              <label class="control-label col-md-12" >Nota adicional 2: </label>
              <div class="col-md-12">
                <textarea name="txtNota2PDF" class="form-control col-md-7 col-xs-12" rows="5" required="required"></textarea>
              </div>
            </div>

            <div class="form-group form-vertical">
              <label class="control-label col-md-12" >Nota adicional 3: </label>
              <div class="col-md-12">
                <textarea name="txtNota3PDF" class="form-control col-md-7 col-xs-12" rows="5" ></textarea>
              </div>
            </div>

            <div class="form-group form-vertical">
              <label class="control-label col-md-12" >¿Quién autoriza?  <span class="required">*</span></label>
              <div class="col-md-12">
                <input type="text" name="txtNombreAutorizaPDF" required="required" class="form-control col-md-7 col-xs-12" >
              </div>
            </div>

            <div class="form-group form-vertical">
              <label class="control-label col-md-12" >Cargo de quién autoriza  <span class="required">*</span></label>
              <div class="col-md-12">
                <input type="text" name="txtCargoAutorizaPDF" required="required" class="form-control col-md-7 col-xs-12" >
              </div>
            </div>
            
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnGenerarNotificacionPDF">Generar PDF</button>
      </div>
    </div>
  </div>
</div>