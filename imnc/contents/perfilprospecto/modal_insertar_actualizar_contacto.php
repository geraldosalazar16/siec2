<!-- Modal insertar/actualizar de contacto-->
<div class="modal fade" id="modalInsertarActualizarContacto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="modalTituloContactoProspecto">Insertar/actualizar</h4>
			</div>
			<div class="modal-body">
				<form name="form2" id="form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Nombre<span class="required">*</span>
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control col-md-7 col-xs-12" ng-model="nombre_contacto">
							<span id="nombreerror" class="text-danger"></span>
						</div>
					</div>

					 <div class="form-group">
		              <label class="control-label col-md-4 col-sm-4 col-xs-12">Domicilio del Contacto<span class="required">*</span>
		              </label>
		              <div class="col-md-6 col-sm-6 col-xs-12">
		                <select class="form-control col-md-7 col-xs-12" id="domicilioContacto" ng-model="domicilioContacto"
		                	ng-options="dom.ID as dom.NOMBRE for dom in listaDomicilios">
							<option value="">---Seleccione un domicilio---</option>
		                </select>
		              </div>
		            </div>

					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Correo<span class="required">*</span>
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="correo">
							<span id="correoerror" class="text-danger"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Correo2</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="correo2">
							<span id="correoerror" class="text-danger"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Telefono<span class="required">*</span>
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="tel" required="required" class="form-control col-md-7 col-xs-12" ng-model="telefono">
							<span id="telefonoerror" class="text-danger"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Celular
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="tel" required="required" class="form-control col-md-7 col-xs-12" ng-model="celular">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Puesto<span class="required">*</span>
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="puesto">
							<span id="puestoerror" class="text-danger"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Datos Adicionales
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<textarea rows="4" cols="50" name="datos_adicionales" id="datos_adicionales" class="form-control col-md-7 col-xs-12" 
							ng-model="datos_adicionales"> </textarea>
						</div>
					</div>
				<div class="form-group">
					<label class="control-label col-md-4 col-sm-4 col-xs-12">ACTIVO:<span class="required">*</span>
              		</label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                           <input type="checkbox" class="form-control col-md-7 col-xs-12 selector noshadow" ng-model="contactohabilitado" data-parsley-id="2324" >
                    </div>
                    </div>

				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary" ng-click="guardarContacto()" id="btnGuardarContacto">Guardar</button>
			</div>
		</div>
	</div>
</div>