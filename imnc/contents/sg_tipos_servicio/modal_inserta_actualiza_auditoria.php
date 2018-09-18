
<!-- Modal insertar/actualizar Auditoria-->
<div class="modal fade" id="modalInsertarActualizarAuditoria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTituloAuditoria">Insertar/Actualizar</h4>
      </div>
      <div class="modal-body">
			
          <form id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtClaveAuditoria">Clave de <?php echo strtolower($str_auditoria); ?><span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtClaveAuditoria" placeholder="asignado automáticamente" class="form-control col-md-7 col-xs-12" data-parsley-id="4103"><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtClaveTipoServicioAuditoria">Clave de tipo de servicio<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtClaveTipoServicioAuditoria" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324"><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
           <!--  <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Fecha inicio <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="txtFechaInicioAuditoria" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text" placeholder="Formato: dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" data-parsley-id="4827"><ul class="parsley-errors-list" id="parsley-id-4827"></ul>
              </div>
            </div>-->

            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">No Aplicar Regla de Muestreo
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input  type="checkbox" class="form-control col-md-7 col-xs-12" id="chkNoMetodo"></input>
                <span style="font-size: 11px; float: right;">Se solicitan más sitios a auditar</span>
              </div>
            </div>

            <div class="form-group" hidden id="divSitiosAuditoria">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Sitios a Auditar<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="txtSitiosAuditoria" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text"data-parsley-id="4827"><ul class="parsley-errors-list" id="parsley-id-4827"></ul>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Días auditor<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="txtDuracionAuditoria" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text"data-parsley-id="4827"><ul class="parsley-errors-list" id="parsley-id-4827"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="claveTipoServicio">Tipo de <?php echo strtolower($str_auditoria);?> <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" id="cmbTipoAuditoria">
                  
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="claveStatusServicio">Status de <?php echo strtolower($str_auditoria);?> <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" id="cmbStatusAuditoria">
                  
                </select>
              </div>
            </div>
          </form>		
      </div>
      <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnGuardarAuditoria">Guardar</button>	
      </div>
    </div>
  </div>
</div>