<!-- Modal Detalles Contacto -->
<div class="modal fade" id="modalDetallesContacto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="modalTitulo">Detalles</h4>
			</div>
			<div class="modal-body">
				<form name="form2" id="form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Nombre:
              			</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="nombre_contacto" data-parsley-id="2324" readonly="false">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Nombre del Domicilio:
              			</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="nombre_domicilioContacto" readonly="false">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Correo:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="correo" data-parsley-id="2324" readonly="false">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Telefono:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="telefono" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Celular:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="celular" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Puesto:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="puesto" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Datos Adicionales
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<textarea rows="4" cols="50" name="datos_adicionales" id="datos_adicionales" class="form-control col-md-7 col-xs-12" 
							ng-model="datos_adicionales" readonly> </textarea>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Id Usuario Registro:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="usuarioReg" required="required" class="form-control col-md-7 col-xs-12" ng-model="id_usuario_creacion_contacto" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Id Usuario Modifico:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="id_usuario_modificacion_contacto" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Fecha Registro:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="fecha_creacion_contacto" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Fecha Modifico:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="fechaMod" required="required" class="form-control col-md-7 col-xs-12" ng-model="fecha_modificacion_contacto" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
					<label class="control-label col-md-4 col-sm-4 col-xs-12">ACTIVO:<span class="required">*</span>
              </label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                           <input type="checkbox" class="form-control col-md-7 col-xs-12 selector noshadow" ng-model="contactohabilitado" disabled="disabled" data-parsley-id="2324" >
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