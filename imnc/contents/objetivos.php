<span ng-controller="objetivos_controller">
<div class="right_col" role="main">
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>Objetivos</h2></p>
        <?php
          if ($modulo_permisos["SERVICIOS"]["catalogos"] == 1) {
              echo '<p>';
              echo '  <button type="button" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc" style="float: right;" ng-click="openModalInsertar()"> ';
              echo '    <i class="fa fa-plus"> </i> Agregar Objetivo';
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
                <th class="column-title" style="width:40%">Objetivo</th>
                <th class="column-title" style="width:30%">Valor</th>
				<th class="column-title" style="width:30%">Periodicidad</th>
                <th class="column-title"></th>
              </tr>
            </thead>

            <tbody>
				<tr ng-repeat="x in objetivos" class="ng-scope even pointer">
					<td>{{x.NOMBRE}} {{x.ID_PERIODICIDAD==1?" del ": " de " }} {{x.VALOR_PERIODICIDAD}}</td>
					<td>{{x.VALOR_OBJETIVO}}</td>
                    <td>{{x.NOMBRE_PERIODICIDAD}}</td>
					<td >
					<?php
						if ($modulo_permisos["SERVICIOS"]["catalogos"] == 1 ) {
							echo '    <button type="button" class="btn btn-primary btn-xs btn-imnc btnEditar" style="float: right;" ng-click="openModalEditar(x)"> ';
							echo '      <i class="fa fa-edit"> </i> Editar curso';
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
          <form id="objetivosForm" name="objetivosForm"  class="form-horizontal form-label-left" >
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="nombre_objetivo">Nombre del Objetivo <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="nombre_objetivo" name="nombre_objetivo" ng-model="formData.nombre_objetivo" required class="form-control col-md-7 col-xs-12" data-parsley-id="4103" ><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
				<span id="nombre_objetivo_error" class="text-danger"></span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="periodicidad">Periodicidad  <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12" >
              
              <select ng-model="formData.periodicidad" ng-options="p.ID as p.NOMBRE for p in periodicidad"
                                class="form-control" id="periodicidad" name="periodicidad" ng-change='cambio_periodicidad(formData.periodicidad)' required>
                  <option value="" disabled>---Seleccione la Periodicidad---</option>
              </select>
				<span id="periodicidad_error" class="text-danger"></span>
              </div>
			  
            </div>
            <div class="form-group" ng-if="formData.periodicidad==1">
            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="valor_periodicidad">Ingrese el año <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="number" id="valor_periodicidad" name="valor_periodicidad" ng-model="formData.valor_periodicidad" required class="form-control col-md-7 col-xs-12" data-parsley-id="4103" ><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
		        <span id="valor_periodicidad_error" class="text-danger"></span>
              </div>
      
            </div>
            <div class="form-group" ng-if="formData.periodicidad==2">
            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="valor_periodicidad">Mes<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                  <select ng-model="formData.valor_periodicidad" ng-options="m.nombre as m.nombre  for m in meses"
                          class="form-control" id="valor_periodicidad" name="valor_periodicidad" required>
                      <option value="">---Seleccione un mes---</option>
                  </select>
                   <span id="valor_periodicidad_error" class="text-danger"></span>
              </div>

            </div>

            <div class="form-group" ng-show="formData.periodicidad">
            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="monto">Monto<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" id="monto" name="monto" ng-model="formData.monto" required class="form-control col-md-7 col-xs-12" data-parsley-id="4103" >
                <ul class="parsley-errors-list" id="parsley-id-4103"></ul>
                <span id="monto_error" class="text-danger"></span>
              </div>
            </div>
           
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" ng-click="guardarObjetivo()" ng-disabled="!objetivosForm.$valid" id="btnGuardar">Guardar</button>
      </div>
    </div>
  </div>
</div>
</span>
