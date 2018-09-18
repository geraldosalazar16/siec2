<!-- Modal insertar/actualizar Tipos de Servicio-->
<div class="modal fade" id="modalInsertarActualizarTServ" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTituloServicio">Insertar/Actualizar</h4>
      </div>
      <div class="modal-body">
          <form id="demo-form2" data-parsley-validate="" novalidate="" style="margin-top: -20px;">
            <div class="form-group form-vertical" style="display: none;">
              <label class="control-label col-md-12" for="txtClave">Clave<span class="required"></span>
              </label>
              <div class="col-md-12">
                <input type="text" id="txtClave" placeholder="asignado automaticamente" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="4103"><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
              </div>
            </div>
            <div class="form-group form-vertical" style="display: none;">
              <label class="control-label col-md-12" for="txtClaveSCE">Clave de servicio contratado  <span class="required">*</span>
              </label>
              <div class="col-md-12">
                <input type="text" id="txtClaveSCE" placeholder="1" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="4103"><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
              </div>
            </div>
            <div class="form-group form-vertical">
              <label class="control-label col-md-12" for="claveTipoServicio">Tipo de servicio  <span class="required">*</span>
              </label>
              <div class="col-md-12">
                <select class="form-control" id="claveTipoServicio">
                  
                </select>
                <ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
            <div class="form-group form-vertical">
              <label class="control-label col-md-12" for="txtClaveNorma">Norma  <span class="required">*</span>
              </label>
              <div class="col-md-12">
                <!-- <select class="form-control" id="claveNorma">
                  
                </select> -->
                <input type="text" id="txtClaveNorma" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="4103" disabled=""><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
                <ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
            <div class="form-group form-vertical" id="complejidadForm" hidden>
              <label class="control-label col-md-12" for="complejidad">Complejidad  <span class="required">*</span>
              </label>
              <div class="col-md-12">
                <select class="form-control" id="txtcomplejidad">
                  <option value=""  disabled>-elige una opción-</option>
                  <option value="alta">Alta</option>
                  <option value="media">Media</option>
                  <option value="baja">Baja</option>
                </select>
                <ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
            <div class="form-group form-vertical" <?php if ($global_diffname == "onac"){echo 'style="display:none;"';} ?> >
              <label class="control-label col-md-12" for="txtTotalEmpleados">Total de empleados <span class="required">*</span>
              </label>
              <div class="col-md-12">
                <input type="number" id="txtTotalEmpleados"  required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="4103"><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
              </div>
            </div>
            <div class="form-group form-vertical">
              <label class="control-label col-md-12" for="txtTotalEmpleadosCertificacion">Total de empleados para certificación <span class="required">*</span>
              </label>
              <div class="col-md-12">
                <input type="number" id="txtTotalEmpleadosCertificacion"  required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="4103"><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
              </div>
            </div>
            <div class="form-group form-vertical" <?php if ($global_diffname == "onac"){echo 'style="display:none;"';} ?>>
              <label class="control-label col-md-12" for="txtTurnos" >Turnos <span class="required">*</span>
              </label>
              <div class="col-md-12">
                <input type="number" id="txtTurnos" required="required" class="form-control col-md-7 col-xs-12">
              </div>
            </div>
            <div class="form-group form-vertical">
              <label class="control-label col-md-12">Multisitios  <span class="required">*</span>
              </label>
              <div class="col-md-12">
                <select class="form-control" id="multisitios">

                </select>
              </div>
            </div>
            <div class="form-group form-vertical">
              <label class="control-label col-md-12">Condiciones de seguridad  <span class="required">*</span>
              </label>
              <div class="col-md-12">
                <textarea class="form-control col-md-7 col-xs-12" id="condicionesSeguridad" rows="5"  required="required">
                  
                </textarea>   
              </div>
            </div>
            <div class="form-group form-vertical">
              <label class="control-label col-md-12" style="margin-top: 25px;">Alcance <span class="required">*</span></label>
              <div class="col-md-12">
                <textarea class="form-control col-md-7 col-xs-12" data-parsley-id="4103" id="txtAlcance" rows="5"  required="required">
                  
                </textarea>
                <ul class="parsley-errors-list" id="parsley-id-4103"></ul>
              </div>
            </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnGuardar">Guardar</button>
      </div>
    </div>
  </div>
</div>