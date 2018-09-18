<!-- Modal insertar/actualizar domicilio-->
<div class="modal fade" id="modalInsertarActualizarDomicilio" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTituloDomicilio">Insertar/Actualizar</h4>
      </div>
      <div class="modal-body">
          <form id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">¿Es fiscal? <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" id="cmbEsFiscal">
                  <option value="si">si</option>
                  <option value="no" selected>no</option>
                </select>
                <ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Nombre de domicilio <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtNomDom" required="required" class="form-control col-md-7 col-xs-12">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">País  <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <!-- <input type="text" id="txtPais" required="required" class="form-control col-md-7 col-xs-12"> -->
                <select class="select2_single form-control col-md-7 col-xs-12" id="autocompletePais" >

                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" >C.P. <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" id="txtCP" required="required" class="form-control col-md-7 col-xs-12">
                <select class="select2_single form-control col-md-7 col-xs-12" id="autocompleteCP" style="display: none;">

                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtCalle">Calle <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtCalle" required="required" class="form-control col-md-7 col-xs-12">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtNoExt">No. Exterior  <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtNoExt" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324"><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtNoInt">No. Interior 
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtNoInt" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324"><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtColonia">Colonia/Barrio  <span class="required">*</span>
			         <div style="float: right;" id="campoChkCol"><input type="checkbox" id="chkCol"><span style="font-size: 11px;">No encuentra la colonia</span></div>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12" id="campoSelectColonia">
                <input type="text" id="txtColonia" required="required" class="form-control col-md-7 col-xs-12">
                <select class="select2_single form-control col-md-7 col-xs-12" id="autocompleteColonia" style="display: none;">

                </select>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12" id="auxColonia" hidden>
                <input type="text" required="required" class="form-control col-md-7 col-xs-12" readonly>
              </div>
            </div>
			<div id="newColonia" class="form-group" hidden>
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Colonia<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input  type="text" class="form-control col-md-7 col-xs-12" id="nuevaColonia"></input>
                <ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtDelegacion">Delegacion/Municipio  <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtDelegacion" required="required" class="form-control col-md-7 col-xs-12">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtEntidadFederativa">Entidad Federativa <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtEntidadFederativa" required="required" class="form-control col-md-7 col-xs-12">
              </div>
            </div>
            
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnGuardarDomicilio">Guardar</button>
      </div>
    </div>
  </div>
</div>
