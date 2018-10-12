<!-- Modal insertar/actualizarProspecto-->
<div class="modal fade" id="modalInsertarActualizarProspecto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="modalTituloProspecto2">Insertar/Actualizar</h4>
			</div>
			<div class="modal-body">
				<form name="form2" id="form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Nombre:<span class="required">*</span>
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control col-md-7 col-xs-12" ng-model="nombre_prospecto">
							<span id="nombreProspectoerror" class="text-danger"></span>
						</div>
					</div>
			<div class="form-group">
				<label class="control-label col-md-4 col-sm-4 col-xs-12">Origen
				<span class="required">*</span></label>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<select ng-model="origen" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324" ng-options="ori.id_origen as ori.origen for ori in Origenes">
						<option value="">---Seleccione un origen---</option>
					</select>
					<span id="origenerror" class="text-danger"></span>
				</div>
			</div>
			<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">RFC</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="rfc" required="required" class="form-control col-md-7 col-xs-12" ng-model="rfc">
						</div>
			</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Giro:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="giro" required="required" class="form-control col-md-7 col-xs-12" ng-model="giro">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Comentario:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="comentario" required="required" class="form-control col-md-7 col-xs-12" ng-model="comentario">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Habilitado<span class="required">*</span>
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="checkbox" class="form-control col-md-7 col-xs-12 selector noshadow" ng-model="cbhabilitado" data-parsley-id="2324">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default">Cerrar</button>
				<button type="button" class="btn btn-primary" ng-click="guardarProspecto()" id="btnGuardarProspecto">Guardar</button>
				
			</div>
		</div>
	</div>
</div>