<!-- Modal insertar/actualizar Certificado-->
<div class="modal fade" id="modalInsertarActualizarCertificado" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTituloCertificado">Insertar/Actualizar</h4>
      </div>
      <div class="modal-body">
          <form id="demo-form2" data-parsley-validate="" novalidate="" style="margin-top: -20px;">
            <div class="form-group form-vertical" style="display: none;">
              <label class="control-label col-md-12">ID de certificado</label>
              <div class="col-md-12">
                <input type="text" id="txtIdCertificado" placeholder="asignado automaticamente" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="4103"><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
              </div>
            </div>
            <div class="form-group form-vertical">
              <label class="control-label col-md-12">Clave de certificado <span class="required">*</span>
              </label>
              <div class="col-md-12">
                <input type="text" id="txtClaveCertificado" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="4103"><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
              </div>
            </div>
            <div class="form-group form-vertical">
              <label class="control-label col-md-12">Fecha de inicio  <span class="required">*</span>
              </label>
              <div class="col-md-12">
                <input id="txtFechaInicioCertificado" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text" placeholder="Formato: dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" data-parsley-id="4827"><ul class="parsley-errors-list" id="parsley-id-4827"></ul>
              </div>
            </div>
            <div class="form-group form-vertical">
              <label class="control-label col-md-12">Fecha de fin  <span class="required">*</span>
              </label>
              <div class="col-md-12">
                <input id="txtFechaFinCertificado" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text" placeholder="Formato: dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" data-parsley-id="4827"><ul class="parsley-errors-list" id="parsley-id-4827"></ul>
              </div>
            </div>
            <div class="form-group form-vertical">
              <label class="control-label col-md-12">Fecha de renovación  <span class="required">*</span>
              </label>
              <div class="col-md-12">
                <input id="txtFechaRenovacionCertificado" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text" placeholder="Formato: dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" data-parsley-id="4827"><ul class="parsley-errors-list" id="parsley-id-4827"></ul>
              </div>
            </div>            
            <div class="form-group form-vertical">
              <label class="control-label col-md-12">Periodicidad <span class="required">*</span>
              </label>
              <div class="col-md-12">
                <select class="form-control" id="cmbPeriodicidadCertificado">
                  <option selected disabled value="">-- selecciona una opción --</option>
                  <option value="3"> 3 meses</option>
                  <option value="6"> 6 meses</option>
                  <option value="12">12 meses</option>
                </select>
                <ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
             <!-- Acreditación -->
            <div class="form-group form-vertical">
              <label class="control-label col-md-12">Acreditación <span class="required">*</span>
              </label>
              <div class="col-md-12">
                <input type="text" id="txtAcreditacionCertificado" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="4103"><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
              </div>
            </div>
            <!-- Fecha de inicio de acreditación -->
            <div class="form-group form-vertical">
              <label class="control-label col-md-12">Fecha de inicio de acreditación  <span class="required">*</span>
              </label>
              <div class="col-md-12">
                <input id="txtFechaInicioAcreditacion" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text" placeholder="Formato: dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" data-parsley-id="4827"><ul class="parsley-errors-list" id="parsley-id-4827"></ul>
              </div>
            </div>
            <!-- Fecha de fin de acreditación -->
            <div class="form-group form-vertical">
              <label class="control-label col-md-12">Fecha de fin de acreditación  <span class="required">*</span>
              </label>
              <div class="col-md-12">
                <input id="txtFechaFinAcreditacion" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text" placeholder="Formato: dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" data-parsley-id="4827"><ul class="parsley-errors-list" id="parsley-id-4827"></ul>
              </div>
            </div>
            <!-- Estatus -->
            <div class="form-group form-vertical">
              <label class="control-label col-md-12">Estatus <span class="required">*</span>
              </label>
              <div class="col-md-12">
                <select class="form-control" id="cmbEstatusCertificado">
                  <option selected disabled value="">-- selecciona una opción --</option>
                  <option value="vigente">vigente</option>
                  <option value="suspendido">suspendido</option>
                  <option value="cancelado">cancelado</option>
                </select>
                <ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
            <!-- Fecha de suspension -->
            <div class="form-group form-vertical form-certificado-suspension" style="display: none;"> 
              <label class="control-label col-md-12">Fecha de suspensión  <span class="required">*</span>
              </label>
              <div class="col-md-12">
                <input id="txtFechaSuspensionCertificado" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text" placeholder="Formato: dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" ">
              </div>
            </div>
            <!-- Motivo de suspension -->
            <div class="form-group form-vertical form-certificado-suspension" style="display: none; margin-bottom: 85px;">
              <label class="control-label col-md-12" >Motivo de suspensión <span class="required"></span>
              </label>
              <div class="col-md-12">
                <textarea class="form-control col-md-7 col-xs-12" id="txtMotivoSuspensionCertificado" rows="5" required="required">
                </textarea>
              </div>
            </div>
            <!-- Fecha de cancelacion -->
            <div class="form-group form-vertical form-certificado-cancelacion" style="display: none;">
              <label class="control-label col-md-12">Fecha de cancelacion  <span class="required">*</span>
              </label>
              <div class="col-md-12">
                <input id="txtFechaCancelacionCertificado" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text" placeholder="Formato: dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'">
              </div>
            </div>
             <!-- Motivo de cancelacion -->
            <div class="form-group form-vertical form-certificado-cancelacion" style="display: none;  margin-bottom: 85px;">
              <label class="control-label col-md-12" >Motivo de cancelación <span class="required"></span>
              </label>
              <div class="col-md-12">
                <textarea class="form-control col-md-7 col-xs-12" id="txtMotivoCancelacionCertificado" rows="5" required="required">
                </textarea>
              </div>
            </div>
            <!-- Archivo del certificado -->
            <div class="form-group form-vertical">
              <label class="control-label col-md-12">Archivo del certificado <span class="required">*</span>
              </label>
              <div class="col-md-12 text-center">
                <div class="subirArchivoCertificado" style="display: none;">Cargar archivo</div>
                <div class="editarArchivoCertificado" style="display: none;">
                  <a class="hrefVerArchivoCertificado" target="_blank"><img src="<?php echo 'diff/'.$global_diffname.'/pdf-icon.png'; ?>" width="100px"></a>
                  <br><br>
                  <button class="btn btn-xs btn-primary" id="btnReemplazarArchivoCertificado" data-toggle="tooltip" data-placement="left" data-original-title="Reemplazar archivo">
                    <i style="font-size: 12px;" class="fa fa-exchange" aria-hidden="true"></i>
                  </button>
                  <a target="_blank" class="btn btn-xs btn-primary hrefVerArchivoCertificado" data-toggle="tooltip" data-placement="right" data-original-title="Ver archivo">
                    <i style="font-size: 12px;" class="fa fa-eye" aria-hidden="true"></i>
                  </a>
                </div>
              </div>
            </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnGuardarCertificado">Guardar</button>
      </div>
    </div>
  </div>
</div>