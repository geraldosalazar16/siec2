<!-- Modal Generar PDF-->
<div class="modal fade" id="modalGenerarPDF" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="modalTitulo">Generar PDF</h4>
			</div>
			<div class="modal-body">
				<form id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
					<div class="form-group text-center">
						<label class="control-label col-md-12 " >Esta seguro que desea generar el PDF? </label>
					</div>
					
					<div class="form-group">
						<label class="control-label">Contactos</label>
						<select class="form-control" id="contactoprospecto1">
                  
						</select>
					</div>
					<div class="form-group">
						<label class="control-label">Domicilio</label>
						<select class="form-control" id="domicilioprospecto1">
                  
						</select>
					</div>
				</form>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
				<a type="button" class="btn btn-primary" id="btnGenerar" >Generar</a>
			</div>
			
			</div>
		</div>
	</div>
</div>