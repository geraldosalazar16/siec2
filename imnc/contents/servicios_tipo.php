<span ng-controller="tipo_servicio_controller">
<div class="right_col" role="main">
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>{{titulo}}</h2></p>
        <?php
          if ($modulo_permisos["SERVICIOS"]["catalogos"] == 1) {
              echo '<p>';
              echo '  <button type="button" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc" style="float: right;" ng-click="InsertarTipoServicio()"> ';
              echo '    <i class="fa fa-plus"> </i> Agregar tipo de servicio';
              echo '  </button>';
              echo '</p>';
          } 
        ?>
        
          <div class="clearfix"></div>
        </div>

        <div class="x_content">

          <table class="table table-striped responsive-utilities jambo_table bulk_action">
            <thead>
              <tr class="headings">
                <th class="column-title">Clave de tipo de servicio</th>
                <th class="column-title">Nombre de tipo de servicio</th>
                <th class="column-title">Nombre Servicio</th>
                <th class="column-title">Texto para Referencia</th>
				<th class="column-title">Normas</th>
                <th class="column-title"></th>
              </tr>
            </thead>

            <tbody>
				<tr ng-repeat="x in tablaDatos" class="ng-scope even pointer">
					<td>{{x.ACRONIMO}}</td>
					<td>{{x.NOMBRE}}</td>
					<td>{{x.NOMBRE_SERVICIO}}</td>					
					<td>{{x.ID_REFERENCIA}}</td>
					<td>{{x.NORMA_ID}}</td>
					<td >
					<?php
						if ($modulo_permisos["SERVICIOS"]["catalogos"] == 1 ) {
							echo '    <button type="button" class="btn btn-primary btn-xs btn-imnc btnEditar" id_tipo_servicio="{{x.ID}}" style="float: right;" ng-click="EditarTipoServicio(x.ID)"> ';
							echo '      <i class="fa fa-edit"> </i> Editar tipo de servicio';
							echo '    </button>';
						}					
					?>
					</td>	
					
					
				</tr>
            </tbody>

          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal insertar/actualizar-->
<div class="modal fade" id="modalInsertarActualizar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTitulo">Insertar/Actualizar</h4>
      </div>
      <div class="modal-body">
          <form id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtClave">Clave de tipo de servicio <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtAcronimo" ng-model="txtAcronimo" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="4103"><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
				<span id="txtAcronimoerror" class="text-danger"></span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtClave">Nombre de tipo de servicio <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtNombre" ng-model="txtNombre" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="4103"><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
				<span id="txtNombreerror" class="text-danger"></span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtRfc">Clave de servicio  <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12" >
                <select class="form-control" ng-model="claveServicio" ng-show="opcioninsertareditar">
					<option ng-repeat="option in clave_Servicio" value="{{option.ID}}">{{option.ACRONIMO}}</option>
                </select>
				<input type="text" id="claveServicio1" ng-model="claveServicio1" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="4103" ng-show="opcioninsertareditar1">
                <ul class="parsley-errors-list" id="parsley-id-2324"></ul>
				<span id="claveServicioerror" class="text-danger"></span>
              </div>
			  
            </div>
			<div class="form-group">
				<label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtRfc">Norma  
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<multiple-autocomplete ng-model="selectedList" 
						object-property="ID"
						suggestions-arr="optionsList">
					</multiple-autocomplete>
				</div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Texto para Referencia<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="txtTextoRef" ng-model="txtTextoRef" class="form-control col-md-7 col-xs-12" required="required" type="text" data-parsley-id="4827"><ul class="parsley-errors-list" id="parsley-id-4827"></ul>
				<span id="txtTextoReferror" class="text-danger"></span>
              </div>
            </div>
 <?php /*           <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Fecha de fin <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="txtTer" ng-model="txtTer" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text" placeholder="Formato: dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" data-parsley-id="4827"><ul class="parsley-errors-list" id="parsley-id-4827"></ul>
              </div>
            </div>
			<div class="form-group" id="divcheckactualizar">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">¿Es actualización?
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="checkbox" class="form-control col-md-7 col-xs-12 selector noshadow" id="checkactualizacion" data-parsley-id="2324" ><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
			<div class="form-group" id="divactualizar" hidden>
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Copiar los sectores de :
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                 <select class="form-control" id="cmbCopiaServicio">
                  
                </select>
              </div>
            </div>
			<div class="form-group" id="divactualizaranio" hidden>
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Año :
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                 <input type="text" id="txtAnio" class="form-control col-md-7 col-xs-12" data-parsley-id="4103"><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
              </div>
            </div>  */?>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" ng-click="cerrar()">Cerrar</button>
        <button type="button" class="btn btn-primary" ng-click="guardarTipoServicios()" id="btnGuardar">Guardar</button>
      </div>
    </div>
  </div>
</div>
</span>