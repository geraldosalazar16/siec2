<span ng-controller="metodos_pago_controller">
<div class="right_col" role="main">
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>Método de Pago</h2></p>
            <button type="button" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc" style="float: right;" ng-click="onAgregar()"><i class="fa fa-plus"> </i> Agregar Método de Pago
                </button>
          <div class="clearfix"></div>
        </div>

        <div class="x_content">

          <table class="table table-striped responsive-utilities jambo_table bulk_action">
            <thead>
              <tr class="headings">
                <th class="column-title" style="width:40%">Clave</th>
				<th class="column-title" style="width:30%">Nombre</th>
                <th class="column-title"></th>
              </tr>
            </thead>

            <tbody>
				<tr ng-repeat="x in metodos_pagos" class="ng-scope even pointer">
					<td>{{x.CLAVE}}</td>
					<td>{{x.NOMBRE}}</td>
					<td >
                        <button type="button"  class="btn btn-primary btn-xs btn-imnc" style="float: right;" ng-click="onEditar(x.ID)"><i class="fa fa-edit"> </i> Editar
                        </button>
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
        <h4 class="modal-title" id="modalTitulo">{{modal_titulo}}</h4>
      </div>
      <div class="modal-body">
          <form id="formulario" name="formulario"  class="form-horizontal form-label-left" >
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="CLAVE">Clave <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="CLAVE" ng-model="formData.CLAVE" required class="form-control col-md-7 col-xs-12" ng-class="{ error: formulario.CLAVE.$error.required && !formulario.$pristine}">
				<span id="error_clave" class="text-danger"></span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="NOMBRE">Nombre  <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12" >
              <input type="text" id="NOMBRE" ng-model="formData.NOMBRE" required class="form-control col-md-7 col-xs-12" ng-class="{ error: formulario.NOMBRE.$error.required && !formulario.$pristine}">
				<span id="error_nombre" class="text-danger"></span>
              </div>
            </div>
          </form>
      </div>
      <div class="modal-footer">
           <input type="submit" class="btn btn-success pull-right mt-2" ng-click="submitForm()" ng-disabled="!formulario.$valid" value="Guardar"/>
      </div>
    </div>
  </div>
</div>
</span>
