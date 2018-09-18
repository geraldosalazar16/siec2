
<!-- Modal insertar/actualizar calif_sector-->
<div class="modal fade" id="modalInsertarActualizarCalifSector" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTituloCalifSector">Insertar/Actualizar</h4>
      </div>
      <div class="modal-body">
          <form id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="cmbSector">Sector <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" id="cmbSector">
                  
                </select>
                <ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
			
			<div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="cmbSector">Sector NACE<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" id="cmbSectorNACE">
                  
                </select>
                <ul class="parsley-errors-list" id="parsley-id-2324"></ul>
				<div class="checkbox">
                    <label class="">
                    <input type="checkbox" id="chkSectorNaceNA" value=""> N/A
                  </label>
                </div>
              </div>
            </div>
			<!--
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtSectorNace">Sector NACE <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" style="z-index: 1;" id="txtSectorNace" data-inputmask="'mask': '(9{+}[.{0,1}][9{*}][.{0,1}][9{*}])|(N/A)'" required="required" class="form-control col-md-7 col-xs-12">
                <div class="checkbox">
                    <label class="">
                    <input type="checkbox" id="chkSectorNaceNA" value=""> N/A
                  </label>
                </div>
              </div>
            </div>
			-->
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtEsquema">Esquema de certificación <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtEsquema" required="required" class="form-control col-md-7 col-xs-12">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtAlcance">Alcance <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtAlcance" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="4103"><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtAprobacion">Aprobación UVIC <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtAprobacion" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="4103"><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Fecha de inicio  <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="txtFecIniCalifSector" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text" placeholder="Formato: dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" data-parsley-id="4827"><ul class="parsley-errors-list" id="parsley-id-4827"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Fecha de fin  <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="txtFecFinCalifSector" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text" placeholder="Formato: dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" data-parsley-id="4827"><ul class="parsley-errors-list" id="parsley-id-4827"></ul>
              </div>
            </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnGuardarCalifSector">Guardar</button>
      </div>
    </div>
  </div>
</div>
