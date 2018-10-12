<!-- Modal insertar/actualizar de Domicilio-->
<div class="modal fade" id="modalInsertarActualizarDomicilio" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="modalTitulo2">Insertar/Actualizar</h4>
			</div>
			<div class="modal-body">
				<form name="form2" id="form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Nombre:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control col-md-7 col-xs-12" ng-model="nombre_domicilio">
							<span id="nombreerror" class="text-danger"></span>
						</div>
					</div>


            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">País<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
              	<select class="select2_single form-control col-md-7 col-xs-12" id="autocompletePais">

                </select>
                <!--<input  type="text" class="form-control col-md-7 col-xs-12" ng-model="pais" id="autocompletePais"></input>-->
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Código Postal<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
              	<input type="text" id="txtCP" required="required" ng-model="codigo_postal" class="form-control col-md-7 col-xs-12">
                <select class="select2_single form-control col-md-7 col-xs-12" id="autocompleteCP" style="display: none;">
                </select>
              </div>
            </div>

            <div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Calle:<span class="required">*</span>
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="calle">
						    <span id="calleerror" class="text-danger"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Numero Exterior:<span class="required">*</span>
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="numero_exterior">
						    <span id="numero_exteriorerror" class="text-danger"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Numero interior:
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="numero_interior">
						</div>
					</div>


	 		<div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Colonia<span class="required">*</span>
              	<div style="float: right;" id="campoChkCol"><input type="checkbox" ng-model="chkColonia" ng-change="colonia_checkbox()" id="chkColonia">
               		<span style="font-size: 11px;">No encuentra colonia</span>
               	</div>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12" id="campoSelectColonia">
              	<input type="text" id="txtColonia" required="required" ng-model="colonia" class="form-control col-md-7 col-xs-12">
                <select class="select2_single form-control col-md-7 col-xs-12" id="autocompleteColonia" style="display: none;">

                </select>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12" id="auxColonia" hidden>
              	<input type="text" required="required" class="form-control col-md-7 col-xs-12" readonly>
              </div>
            </div>
			
			<div class="form-group" id="campoNuevaColonia" hidden>
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Nueva Colonia<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
              	<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="colonia">
              </div>
            </div>	

			<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Estado:<span class="required">*</span>
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="estado" id="txtEstado">
						    <span id="estadoerror" class="text-danger"></span>
						</div>
			</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Municipio:<span class="required">*</span>
              </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" class="form-control col-md-7 col-xs-12" ng-model="municipio" id="txtMunicipio">
						    <span id="municipioerror" class="text-danger"></span>
						</div>
					</div>
				
					
					 <div class="form-group">
                         <label class="control-label col-md-4 col-sm-4 col-xs-12">Domicilio para Facturación Fiscal:<span class="required">*</span>
              </label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                           <input type="checkbox" class="form-control col-md-7 col-xs-12 selector noshadow" ng-model="fiscalhabilitado" data-parsley-id="2324" >
                    </div>
                    </div>
                    <div class="form-group">
                         <label class="control-label col-md-4 col-sm-4 col-xs-12">¿Oficina central?<span class="required">*</span>
              </label>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                           <input type="checkbox" class="form-control col-md-7 col-xs-12 selector noshadow" ng-model="domiciliohabilitado" data-parsley-id="2324" >
                    </div>
                    </div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" ng-click="cerrarDomicilio()">Cerrar</button>
				<button type="button" class="btn btn-primary" ng-click="guardarDomicilio()" id="btnGuardarDomicilio">Guardar</button>
			</div>
		</div>
	</div>
</div>