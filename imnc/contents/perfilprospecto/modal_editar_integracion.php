<!-- Modal Eliminar sector-->
<div class="modal fade" id="modalEditarIntegracion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
				<h4 class="modal-title" id="modalTitulo">Editar</h4>
			</div>
			<div class="modal-body">
				<form id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
					<div class="form-group text-center">
						<p> {{integracion_actual.PREGUNTA}}</p>
                        <select ng-model="respuestas_integracion"
                        class="form-control" style="margin-top:10px" 
                        ng-options="respuesta.RESPUESTA as respuesta.RESPUESTA for respuesta in Respuestas"> 
						</select>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-primary" ng-click="guardar_integracion()">Aceptar</button>
			</div>
		</div>
	</div>
</div>