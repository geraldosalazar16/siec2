!-- Modal Detalles Cliente-->
<div class="modal fade" id="modalInsertarActualizarCliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="modalTituloCliente">Insertar/Actualizar</h4>
			</div>
			<div class="modal-body">
				<form id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Nombre <span class="required">*</span>
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="txtNombre" required="required" class="form-control col-md-7 col-xs-12">
							<input type="hidden" id="id_prospecto" value="">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtRfc">RFC  <span class="required">*</span>
							<div style="float: right;"><input type="checkbox" id="chkRfc"> <span style="font-size: 11px;">No tiene RFC</span></div>
              			</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="txtRfc" data-inputmask="'mask': '<?php echo $str_mascara_rfc; ?> '" placeholder="PELJ900412XXX" name="txtRfc" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324">
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Es facturatario <span class="required">*</span>
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<select class="form-control" id="cmbEsFacturario">
								<option value="" selected disabled>-- elige una opción --</option>
								<option value="S">si</option>
								<option value="N">no</option>
							</select>
							<!-- <input type="text" id="txtEs_Fac" placeholder="S" name="txtEs_Fac" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324"><ul class="parsley-errors-list" id="parsley-id-2324"></ul> -->
						</div>
					</div>
					
					
					
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Cliente facturario<span class="required">*</span>
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="txtClienteFacturario" required="required" class="form-control" disabled>
						</div>
					</div>
					
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">RFC del facturario<span class="required">*</span>
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="txtRFCFac"  data-inputmask="'mask': '<?php echo $str_mascara_rfc; ?>'" required="required"  class="form-control" disabled>
							</div>
					</div>
					
					
					
					
					
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12" for="cmbTPersona">Tipo de persona  <span class="required">*</span>
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<select class="form-control" id="cmbTPersona">
								<option value="elige" selected disabled>-elige una opción-</option>
							</select>
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12" for="cmbTEntidad">Tipo de entidad  <span class="required">*</span>
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<select class="form-control" id="cmbTEntidad">
								<option value="elige" selected disabled>-elige una opción-</option>
							</select>
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary" id="btnGuardarCliente">Guardar</button>
			</div>
		</div>
	</div>
</div>