<!-- Modal Detalles-->
<div class="modal fade" id="modalDetallesProspecto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="modalTituloProspecto">Detalles</h4>
			</div>
			<div class="modal-body">
				<form name="form2" id="form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Nombre:<span class="required">*</span>
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="rfc" required="required" class="form-control col-md-7 col-xs-12" ng-model="nombre" data-parsley-id="2324" readonly="false">
						</div>
					</div>

				<div class="form-group">
	              <label class="control-label col-md-4 col-sm-4 col-xs-12">Origen
	              </label>
	              <div class="col-md-6 col-sm-6 col-xs-12">
	                <input type="text" id="origen-det" required="required" class="form-control col-md-7 col-xs-12" ng-model="nombre_origen" data-parsley-id="2324" readonly="false" ><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
	              </div>
	            </div>
	            
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">RFC
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="rfc" required="required" class="form-control col-md-7 col-xs-12" ng-model="rfc" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Giro:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="giro" required="required" class="form-control col-md-7 col-xs-12" ng-model="giro" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Comentario:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="comentario" required="required" class="form-control col-md-7 col-xs-12" ng-model="comentario" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Id Usuario Registro:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="usarioReg" required="required" class="form-control col-md-7 col-xs-12" ng-model="id_usuario_creacion" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Id Usuario modificacion:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="usuarioMod" required="required" class="form-control col-md-7 col-xs-12" ng-model="id_usuario_modificacion" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Fecha Registro:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="fechaReg" required="required" class="form-control col-md-7 col-xs-12" ng-model="fecha_creacion" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Fecha modificacion:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="fechaMod" required="required" class="form-control col-md-7 col-xs-12" ng-model="fecha_modificacion" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Habilitado<span class="required">*</span>
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="checkbox" class="form-control col-md-7 col-xs-12 selector noshadow" disabled="disabled" ng-model="cbhabilitado" data-parsley-id="2324">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default">Cerrar</button>
			</div>
		</div>
	</div>
</div>