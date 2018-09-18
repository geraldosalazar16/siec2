<span ng-controller="tabla_entidades_controller">
<style>
.selector.noshadow {
	-webkit-box-shadow: none;
	-moz-box-shadow: none;
	box-shadow: none;
	}
</style>
<div class="right_col" role="main">
  <div class="row">
    <div class="col-md-10 col-sm-10 col-xs-10">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>{{titulo}}</h2></p>
        <p>
          <button type="button" ng-click="agregar()" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc" style="float: right;"> 
            <i class="fa fa-plus"> </i> Agregar 
          </button>
        </p>
          <div class="clearfix"></div>
        </div>

        <div class="x_content">

          <table class="table table-striped responsive-utilities jambo_table bulk_action">
            <thead>
              <tr class="headings">
                <th class="column-title">ID</th>
                <th class="column-title">TABLA</th>
                <th class="column-title">DESCRIPCI&Oacute;N</th>
				<th class="column-title">&nbsp;</th>
              </tr>
            </thead>

            <tbody>
				<tr ng-repeat="x in tablaentidades" class="ng-scope even pointer">
					<td>{{x.ID}}</td>					
					<td>{{x.TABLA}}</td>
					<td>{{x.DESCRIPCION}}</td>
					<!--<td ng-if="x.VIGENTE == 1">Vigente</td>
					<td ng-if="x.VIGENTE == 0">No Vigente</td>-->
					
					<td >
						<button type="button" ng-click="editar(x.ID)"  class="btn btn-primary btn-xs btn-imnc btnEditar" style="float: left;">
							<i class="fa fa-edit"> </i> Editar	 
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
        <h4 class="modal-title" id="modalTitulo">Insertar/Actualizar</h4>
      </div>
      <div class="modal-body">
          <form id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Tabla<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="nombre" class="form-control col-md-7 col-xs-12" ng-model="nombre" data-parsley-id="2324"><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
				<span id="nombreerror" class="text-danger"></span>
              </div>
            </div>
			<div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Descripcion<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
				<input type="hidden" ng-model="id"/>
                <input type="text" id="descripcion" class="form-control col-md-7 col-xs-12" ng-model="descripcion" data-parsley-id="2324"><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
				<span id="descripcionerror" class="text-danger"></span>
              </div>
            </div>
            
			<!--<div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Vigente<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="checkbox" class="form-control col-md-7 col-xs-12 selector noshadow" ng-model="cbvigente" data-parsley-id="2324" ><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>-->
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" ng-click="cerrar()">Cerrar</button>
        <button type="button" class="btn btn-primary" ng-click="guardar()"  id="btnGuardar">Guardar</button>
      </div>
    </div>
  </div>
</div>

