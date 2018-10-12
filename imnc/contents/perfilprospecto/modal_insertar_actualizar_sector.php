<!-- Modal insertar/actualizar Sector-->
<div class="modal fade" id="modalInsertarActualizarTServSector" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="modalTituloSectores">{{modal_titulo_sector}}</h4>
			</div>
			<div class="modal-body">
				<form name="form2" id="form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
					
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Sector
						<span class="required">*</span></label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<select ng-model="cmb_sectores" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324" 
							ng-options="sector.ID_SECTOR as sector.NOMBRE for sector in SectoresTipoServicio">
								<option value="">---Seleccione un sector---</option>
							</select>
							<span id="cmbsectoreserror" class="text-danger"></span>
						</div>
					</div>
			
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default">Cerrar</button>
				<button type="button" class="btn btn-primary" ng-click="guardarSector()" id="btnGuardarProspecto">Guardar</button>
				
			</div>
		</div>
	</div>
</div>