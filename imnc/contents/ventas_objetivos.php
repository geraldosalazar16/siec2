<span ng-controller="ventas_objetivos_controller">
<div class="right_col" role="main">
  <div class="row">
    <div class="col-md-6 col-sm-6 col-xs-6">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>{{titulo}}</h2></p>
        <p>
          <button type="button" ng-click="mostrarModalAgregarAnio('insert')" id="btnNuevo" 
          class="btn btn-primary btn-xs btn-imnc"
          ng-if='modulo_permisos["registrar"] == 1' style="float: right;"> 
            <i class="fa fa-plus"> </i> Agregar año
          </button>
        </p>
          <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <div class="row form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="anioSelect">Año</span>
                <select ng-model="anioSelect" required="required" class="form-control" id="anio"
                    ng-options="anio.ID as anio.ANIO for anio in Anios"
                    ng-change="cambioAnio()">
                </select>
                <button type="button" ng-click="mostrarModalAgregarAnio('edit')" id="btnNuevo" 
                class="btn btn-primary btn-xs btn-imnc"
                ng-if='modulo_permisos["editar"] == 1 && anioSelect' style="float: right;"> 
                    <i class="fa fa-edit"> </i> Editar año
                </button>
            </div>
            <div class="row" style="margin-top: 25px;">
                <h3>Objetivo anual: {{montoAnioMostrar | currency}}</h3>
                <span class="text-danger" ng-show="errores">{{errores}}</span>
                <table  class="table table-striped responsive-utilities jambo_table bulk_action">
                    <thead>
                    <tr class="headings">
                        <th class="column-title">Mes</th>
                        <th class="column-title">Objetivo</th>
                        <th class="column-title">&nbsp;</th>
                    </tr>
                    </thead>

                    <tbody>
                        <tr ng-repeat="mes in Meses" class="ng-scope even pointer">
                            <td>{{mes.MES}}</td>
                            <td>{{mes.MONTO}}</td>					
                            <td>
                                <button type="button" ng-click="mostrarModalEditarMes(mes)" class="btn btn-primary btn-xs btn-imnc btnEditar"
                                    ng-if='modulo_permisos["editar"] == 1' style="float: right;">
                                    <i class="fa fa-edit"> </i> Editar Monto
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
</div>

<div class="modal fade" id="modalGuardarMes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="modalTitulo">Editar datos</h4>
			</div>
			<div class="modal-body">
                <form name="form2" id="form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
                <div class="form-group">
					<label class="control-label col-md-4 col-sm-4 col-xs-12">Mes
                    </label>
					<div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" disabled="true" class="form-control col-md-7 col-xs-12" 
                        ng-model="mes">
					</div>
                </div>
                <div class="form-group">
					<label class="control-label col-md-4 col-sm-4 col-xs-12">Monto
                    </label>
					<div class="col-md-6 col-sm-6 col-xs-12">
                        <input class="form-control col-md-7 col-xs-12" 
                        ng-model="montoMes">
					</div>
                </div>
                </form>
            </div>
			<div class="modal-footer">
                <button type="button" class="btn btn-default">Cerrar</button>
                <button type="button" class="btn btn-primary" ng-click="editarMes()"  id="btnGuardar">Guardar</button>
            </div>
		</div>
	</div>
</div>

<div class="modal fade" id="modalGuardarAnio" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="modalTitulo">insertar Año</h4>
			</div>
			<div class="modal-body">
                <form name="form2" id="form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
                <div class="form-group">
					<label class="control-label col-md-4 col-sm-4 col-xs-12">Año
                    </label>
					<div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" class="form-control col-md-7 col-xs-12" 
                        ng-model="anio">
					</div>
                </div>
                <div class="form-group">
					<label class="control-label col-md-4 col-sm-4 col-xs-12">Monto
                    </label>
					<div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" class="form-control col-md-7 col-xs-12" 
                        ng-model="montoAnio">
					</div>
                </div>
                </form>
            </div>
			<div class="modal-footer">
                <button type="button" class="btn btn-default">Cerrar</button>
                <button type="button" class="btn btn-primary" ng-click="guardarAnio()"  id="btnGuardar">Guardar</button>
            </div>
		</div>
	</div>
</div>

</span>
