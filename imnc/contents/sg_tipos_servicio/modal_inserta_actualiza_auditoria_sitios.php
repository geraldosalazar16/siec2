
<!-- Modal insertar/actualizar Sitios Auditoria-->
<div class="modal fade" id="modalInsertarActualizarSitiosAuditoria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTituloSitiosAuditoria">Insertar/Actualizar</h4>
      </div>
      <div class="modal-body">
          <form id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtClave-Audit">Clave <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtClave-Audit" placeholder="asignado automáticamente" class="form-control col-md-7 col-xs-12" data-parsley-id="4103"><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtClaveAuditoria-Audit">Clave de <?php echo strtolower($str_auditoria); ?><span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtClaveAuditoria-Audit" class="form-control col-md-7 col-xs-12" data-parsley-id="4103"><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtClaveSitio-Audit">Clave de sitio<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="input-group">
                  <input type="text" class="form-control" id="txtClaveSitio-Audit"  required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="4103" readonly><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
                  <span class="input-group-btn">
                      <button type="button" class="btn btn-success" id="btnExplorarSitios">Explorar</button>
                  </span>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">
                Nombre del sitio <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtNombreSitio-Audit" class="form-control col-md-7 col-xs-12" disabled>
              </div>
            </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnGuardarSitiosAuditoria">Guardar</button>
      </div>
    </div>
  </div>
</div>