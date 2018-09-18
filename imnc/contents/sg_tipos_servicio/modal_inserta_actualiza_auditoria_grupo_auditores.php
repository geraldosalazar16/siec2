<!-- Modal insertar/actualizar Grupo Auditoria-->
<div class="modal fade" id="modalInsertarActualizarGrupoAuditoria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTituloGrupoAuditoria">Insertar/Actualizar</h4>
      </div>
      <div class="modal-body">
          <form id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
            <div class="form-group" style="display: none;">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtClave-Grupo">Clave  <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtClave-Grupo" placeholder="asignado automáticamente" class="form-control col-md-7 col-xs-12" data-parsley-id="4103"><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
              </div>
            </div>
            <div class="form-group" style="display: none;">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtClaveAuditoria-Grupo">Clave de <?php echo strtolower($str_auditoria); ?> <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtClaveAuditoria-Grupo" class="form-control col-md-7 col-xs-12" data-parsley-id="4103"><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtClavePTCalif-Grupo"><?php echo $str_personal_tecnico_singular; ?> <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="input-group">
                <input type="text" class="form-control" id="txtClavePTCalif-Grupo"  required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="4103" readonly><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
                <span class="input-group-btn">
                    <button type="button" class="btn btn-success" id="btnExplorarGrupo">Explorar</button>
                </span>
              </div>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="ClaveSec">Rol en grupo<span class="required"></span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" id="cmbRol">
                  
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Asignar fechas  <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control col-md-7 col-xs-12" id="txtFechasGrupoAuditor" placeholder="Selecciona las fechas" style="cursor: pointer;" readonly> 
              </div>
            </div>
           
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnGuardarGrupoAuditoria">Guardar</button>
      </div>
    </div>
  </div>
</div>