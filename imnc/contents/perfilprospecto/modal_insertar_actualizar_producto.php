<!-- Modal insertar/actualizar producto-->
<div class="modal fade" id="modalInsertarActualizarProductoProspecto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="modalTituloProductoProspecto">Insertar/actualizar</h4>
			</div>
			<div class="modal-body">
				<form name="form2" id="form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
					<div class="form-group">
						<label class="control-label">Servicio</label>
						<select ng-model="areas" ng-change="areas_cambio()" class="form-control" style="margin-top:10px" ng-options="area.id as area.nombre for area in Areas"> 
							<option value="" ng-selected="true" disabled>Seleccione una opción</option>
						</select>
					</div>

					<div class="form-group">	
						<label class="control-label">Tipo de servicio</label>
						<select ng-model="departamentos" ng-change="departamentos_cambio()" style="margin-top:10px" class="form-control" ng-options="departamento.id as departamento.nombre for departamento in Departamentos"> 
							<option value="" ng-selected="true" disabled>Seleccione una opción</option>
						</select>
		            </div>
					<!--
					<div class="form-group">
						<label class="control-label">Norma</label>
						<select ng-model="productos" ng-change="productos_cambio()" style="margin-top:10px" class="form-control" 
						ng-options="producto.id as producto.nombre for producto in Productos"> 
							<option value="" ng-selected="true" disabled>Seleccione una opción</option>
						</select>
					</div>
					-->
					<div class="form-group">
						<label class="control-label">Normas</label>
						<multiple-autocomplete ng-model="productos" 
						object-property="ID_NORMA"
						suggestions-arr="Productos">
						</multiple-autocomplete>
					</div>
					<div class="form-group">
						<label class="control-label">Alcance</label>
						<textarea rows="4" cols="50" name="alcance_producto" id="alcance_producto" class="form-control" style="margin-top:10px" ng-model="alcance_producto" data-parsley-id="2324">
						</textarea>	
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary" ng-click="guardarProductoProspecto()" id="btnGuardarProductoProspecto">Guardar</button>
			</div>
		</div>
	</div>
</div>