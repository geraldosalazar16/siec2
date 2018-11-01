<!-- MODAL CREATE-->
<div class="modal fade" id="modalCrearEvento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalCrearEventoTitulo">Nuevo Evento</h4>
      </div>
      <div class="modal-body">
          <form name="form2" id="form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="evento">Evento <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="evento" required="required" class="form-control col-md-7 col-xs-12">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="fecha_inicio">Fecha Inicio <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="fecha_inicio" required="required" class="form-control col-md-7 col-xs-12">
              </div>
              <span id="fechainicioerror" class="text-danger"></span>
            </div>

            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="fecha_fin">Fecha Fin <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="fecha_fin" required="required" class="form-control col-md-7 col-xs-12">
              </div>
              <span id="fechafinerror" class="text-danger"></span>
            </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnEliminarEvento">Eliminar</button>
        <button type="button" class="btn btn-primary" id="btnGuardarEvento">Guardar</button>
      </div>
    </div>
  </div>
</div>