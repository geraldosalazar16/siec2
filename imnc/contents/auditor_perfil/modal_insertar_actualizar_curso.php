<!-- Modal insertar/actualizar calificación-->
<div class="modal fade" id="modalInsertarActualizarCusro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTituloCurso">{{modal_curso_titulo}}</h4>
      </div>
      <div class="modal-body">
          <form id="exampleForm" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
              <div class="form-group">
                  <label class="control-label col-md-4 col-sm-4 col-xs-12" for="selectCurso">Curso  <span class="required">*</span>
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12" >
                      <select class="form-control" id="selectCurso">

                      </select>

                  </div>

              </div>
              <div class="form-group">
                  <label class="control-label col-md-4 col-sm-4 col-xs-12">Fecha de inicio  <span class="required">*</span>
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                      <input id="fechaInicioCurso" class="form-control col-md-7 col-xs-12 fecha-inicio" required="required" type="text" placeholder="Formato: dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" data-parsley-id="4827"><ul class="parsley-errors-list" id="parsley-id-4827"></ul>
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-4 col-sm-4 col-xs-12">Fecha de fin  <span class="required">*</span>
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                      <input id="fechaFinCurso" class="form-control col-md-7 col-xs-12 fecha-fin" required="required" type="text" placeholder="Formato: dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" data-parsley-id="4827"><ul class="parsley-errors-list" id="parsley-id-4827"></ul>
                  </div>
              </div>

          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnGuardarCurso">Guardar</button>
      </div>
    </div>
  </div>
</div>
