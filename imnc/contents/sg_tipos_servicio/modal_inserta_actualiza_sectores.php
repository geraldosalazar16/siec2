<!--Modal insertar/actualizar Sectores-->
<div class="modal fade" id="modalInsertarActualizarTServSector" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTituloSector">Insertar/Actualizar</h4>
      </div>
      <div class="modal-body">
          <form id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
            <div class="form-group form-vertical2" style="display: none;">
              <label class="col-md-12">Clave del tipo de servicio<span class="required">*</span></label>
              <div class="col-md-12">
                <input type="text" id="ClaveSgTServ" placeholder="" required="required" class="form-control col-md-7 col-xs-12">
              </div>
            </div>
            <div class="form-group form-vertical2">
              <label class="col-md-12">Nombre del sector<span class="required"></span>
              </label>
              <div class="col-md-12">
                <select class="form-control" id="ClaveSec">
                  
                </select>
              </div>
            </div>
            <div class="form-group form-vertical2">
              <label class="col-md-12">Principal<span class="required">*</span>
              </label>
              <div class="col-md-12">
                <select class="form-control" id="PrincipalSec">
                  
                </select>
                
              </div>
            </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnGuardarSector">Guardar</button>
      </div>
    </div>
  </div>
</div>