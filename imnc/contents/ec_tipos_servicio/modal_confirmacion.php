<!-- Modal Confirmación-->
<div class="modal fade" id="modalConfirmacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
        <h4 class="modal-title" id="modalTitulo">Confirmaci&oacuten</h4>
      </div>
      <div class="modal-body">
          <form id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
            <div class="form-group text-center">
              <label class="control-label col-md-12 " >Esta seguro que desea eliminar el registro? </label>
            </div>
            
			<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
			<button type="button" class="btn btn-primary" id="btnEliminar" ng-click="EliminarFecha()">Aceptar</button>
			</div>
    </div>
  </div>
</div>
</div>
</div>