<?php $year = date("Y"); ?>
<span ng-controller="indicadores_comercial_plan_real_controller">
    <style>
.text-dark{
    color: #0a0a0a;
    font-size: 12px;
}
.table-border
{
    border: #735626 1px solid;
}
.table-border tr td {
    border: #735626 1px solid;
}
</style>
<div class="right_col" role="main" >
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>Indicadores de Venta Plan / Real</h2></p>

          <div class="clearfix"></div>
        </div>

        <div class="x_content">
          <button type="button" class="btn btn-success" ng-click="openModalBuscar('<?php echo $year?>')" ><i class="fa fa-search"></i> Filtrar</button>
        <div class="x_title">
            <p><h2>RESULTADO DE VENTAS {{mes}}</h2> <button ng-if="prospectos.length > 0" type="button" class="btn btn-primary btn-sm pull-right" ng-click="exportExcel()" ><i class="fa fa-file-excel-o"></i> Exportar Excel</button></p>
           <div class="clearfix"></div>
		</div>
            <div id="search" style="margin-bottom: 20px;"></div>
        <div id="expander" class="expander">
        </div>

        </div>
    </div>
   </div>
      <!-- Modal insertar/actualizar-->
<div class="modal fade" id="modalbuscar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" >Filtros</h4>
      </div>
      <div class="modal-body">
        <form id="objetivosForm" name="objetivosForm"  class="form-horizontal form-label-left">
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
              <input type="number" id="valor_periodicidad" name="valor_periodicidad" ng-model="formData.valor_periodicidad"  required class="form-control col-md-7 col-xs-12" data-parsley-id="4103" ><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
		        <span id="valor_periodicidad_error" class="text-danger"></span>
              </div>

            </div>
            <div class="form-group" ng-if="formData.periodicidad==2">
            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="valor_periodicidad">Mes<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                  <select ng-model="formData.valor_periodicidad" ng-options="m.id as m.nombre  for m in meses"
                          class="form-control" id="valor_periodicidad" name="valor_periodicidad" required>
                      <option value="">---Seleccione un mes---</option>
                  </select>
                   <span id="valor_periodicidad_error" class="text-danger"></span>
              </div>

            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="btnCerrar">Cancelar</button>
        <button type="button" class="btn btn-primary" ng-click="submitBuscarFiltrados()" ng-disabled="!objetivosForm.$valid" >Filtrar Datos</button>
      </div>
    </div>
  </div>
</div>
</div>

</span>
