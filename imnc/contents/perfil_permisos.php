<span ng-controller="perfil_permisos_controller">
<div class="right_col" role="main">
  <div class="row">
    <div class="col-md-6 col-sm-6 col-xs-6">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>{{titulo}}</h2></p>
        
          <div class="clearfix"></div>
        </div>

        <div class="x_content">

          <table class="table table-striped responsive-utilities jambo_table bulk_action">
            <thead>
              <tr class="headings">
                <th class="column-title">Permiso</th>
                <th class="column-title"></th>
              </tr>
            </thead>

            <tbody>
				<tr ng-repeat="x in permisos_perfil" class="ng-scope even pointer">
					<td style="font-size:large;">{{x.PERMISO}}</td>
					<td>
						<input type="checkbox"  ng-hide="x.VALOR== 1" ng-click="cambio(x.ID_PERFIL_PERMISOS,1)" id="permiso1_{{x.ID_PERFIL_PERMISOS}}" class="form-control col-md-7 col-xs-12">
						<input type="checkbox" checked="checked" ng-click="cambio(x.ID_PERFIL_PERMISOS,0)" ng-hide="x.VALOR== 0" id="permiso0_{{x.ID_PERFIL_PERMISOS}}" class="form-control col-md-7 col-xs-12">
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
<!--
<div class="modal fade" id="modalInsertarActualizar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTitulo">Insertar/Actualizar</h4>
      </div>
      <div class="modal-body">
          <form name="form2" id="form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
            
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">{{etiqueta}}<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="descripcion" required="required" class="form-control col-md-7 col-xs-12" ng-model="descripcion" data-parsley-id="2324" ><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
				<span id="descripcionerror" class="text-danger"></span>
              </div>
            </div>
			<div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Descripcion<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
				<input type="hidden" id="txtId" ng-model="id"/>
                <input type="text" name="descripcion" id="descripcion" class="form-control col-md-7 col-xs-12" ng-model="descripcion" data-parsley-id="2324"><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
				<span id="descripcionerror" class="text-danger"></span>
			  </div>
            </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" ng-click="cerrar()">Cerrar</button>
        <button type="button" class="btn btn-primary" ng-click="guardar()"  id="btnGuardar">Guardar</button>
      </div>
    </div>
  </div>
</div>
-->