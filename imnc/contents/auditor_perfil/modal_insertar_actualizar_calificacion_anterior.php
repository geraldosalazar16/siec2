<!-- Modal insertar/actualizar calificación-->
<div class="modal fade" id="modalInsertarActualizarCalifSectorAnterior" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTituloCalif">Actualizar Rol y Servicio</h4>
      </div>
      <div class="modal-body">
          <form id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="cmbTipoServicioActualizacion">Tipo de servicio <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
			  <input type="hidden" id="id_calificacion_input"/>
                <select class="form-control" id="cmbTipoServicioActualizacion">
                  
                </select>
                <ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
            </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnActualizaCalif">Actualizar</button>
      </div>
    </div>
  </div>
</div>
