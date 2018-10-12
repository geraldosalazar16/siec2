<!-- Modal Detalles Domicilio-->
<div class="modal fade" id="modalDetallesDomicilio" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="nombre_domicilio" data-parsley-id="2324" readonly="false">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Pais
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="pais" data-parsley-id="2324" readonly="false">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Estado:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="estado" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Municipio:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="municipio" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Colonia:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="colonia" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Codigo postal:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="codigo_postal" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Colonia:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="colonia" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Numero interior:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="numero_interior" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Numero exterior:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="numero_exterior" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					 
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Id Usuario Registro:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="usuarioMod" required="required" class="form-control col-md-7 col-xs-12" ng-model="id_usuario_creacion_domicilio" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Id Usuario modificacion:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="id_usuario_modificacion_domicilio" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Fecha Registro:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="fecha_creacion_domicilio" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Fecha modificacion:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="fechaMod" required="required" class="form-control col-md-7 col-xs-12" ng-model="fecha_modificacion_domicilio" data-parsley-id="2324" readonly="false">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
                         <label class="control-label col-md-4 col-sm-4 col-xs-12">Fiscal:<span class="required">*</span>
              </label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                           <input type="checkbox" class="form-control col-md-7 col-xs-12 selector noshadow" ng-model="fiscalhabilitado" disabled="disabled" data-parsley-id="2324" ><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
                    </div>
                    </div>
                    <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12">¿Oficina central?:<span class="required">*</span>
              </label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                           <input type="checkbox" class="form-control col-md-7 col-xs-12 selector noshadow" ng-model="domiciliohabilitado" disabled="disabled" data-parsley-id="2324" >
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