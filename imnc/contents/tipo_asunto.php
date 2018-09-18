<span ng-controller="tipo_asunto_controller">
<div class="right_col" role="main">
  <div class="row">
    <div class="col-md-6 col-sm-6 col-xs-6">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>{{titulo}}</h2></p>
        <p>
          <button type="button" ng-click="agregar()" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc" 
          ng-if='modulo_permisos["registrar"] == 1' style="float: right;"> 
            <i class="fa fa-plus"> </i> Agregar 
          </button>
        </p>
          <div class="clearfix"></div>
        </div>

        <div class="x_content">

          <table class="table table-striped responsive-utilities jambo_table bulk_action">
            <thead>
              <tr class="headings">
                <th class="column-title">#</th>
                <th class="column-title">Descripci&oacute;n</th>
                <th class="column-title ">Color</th>
                <th> </th>
              </tr>
            </thead>

            <tbody>
				<tr ng-repeat="x in tipoasunto" class="ng-scope even pointer">
					<td>{{$index + 1}}</td>
					<td>{{x.descripcion}}</td>
					<td> <i style="color: {{x.color}}" class="fa fa-certificate fa-3x"></i></td>
					<td>
						<button type="button" ng-click="editar(x.id_tipo_asunto)" class="btn btn-primary btn-xs btn-imnc btnEditar"
            ng-if='modulo_permisos["editar"] == 1' style="float: right;">
							<i class="fa fa-edit"> </i> Editar Asunto
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
          <form name="form2" id="form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Descripcion<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="descripcion" id="descripcion" class="form-control col-md-7 col-xs-12" ng-model="form.descripcion" data-parsley-id="2324"><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
				<span id="descripcionerror" class="text-danger"></span>
			  </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Color<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="color" id="color" required="required" class="form-control col-md-7 col-xs-12" ng-model="form.color" data-parsley-id="2324" ><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
				<span id="colorerror" class="text-danger"></span>
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


