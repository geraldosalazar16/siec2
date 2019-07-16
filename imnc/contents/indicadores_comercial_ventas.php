<span ng-controller="indicadores_comercial_ventas_controller">
    <style>
.expander
{
    margin-left: 10px;
}
.expander-h3
{
    border-radius: 5px;
    padding:5px 5px 5px 20px;
    margin: 3px;
    font-size: 18px;
    cursor:pointer;
    -moz-box-shadow:0 1px 2px #CCC;
    -webkit-box-shadow:0 1px 2px #CCC;
    box-shadow:0 1px 2px #CCC;


}
.expanded{
    background-color: rgba(177,131,11,0.32);
}
.collapsed{
    background-color: white;
}
.expander-div
{
    color:#000000;
    background-color: white;
    border-radius: 10px;
    border: rgba(156, 178, 182, 0.32) 1px solid;
    padding:20px;
    width: 98%;
    margin: auto;
}

</style>
<div class="right_col" role="main" >
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>Indicadores de Venta</h2></p>

          <div class="clearfix"></div>
        </div>

        <div class="x_content">
          <button type="button" class="btn btn-success" ng-click="openModalBuscar()" ><i class="fa fa-search"></i> Filtrar</button>
          <button type="button" class="btn btn-primary" ng-click="submitBuscarTodos()" ><i class="fa fa-search"></i> Todos</button>

        <div class="x_title">
            <p><h2>Resultados</h2> <button ng-if="prospectos.length > 0" type="button" class="btn btn-primary btn-sm pull-right" ng-click="exportExcel()" ><i class="fa fa-file-excel-o"></i> Exportar Excel</button></p>
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
        <form>
               <div class="form-group">
                   <select ng-model="formData.column" id="column" name="colunm" multiple="multiple" size="10" name="duallistbox_demo1[]" title="duallistbox_demo1[]" ></select>
                <span class="text-danger" >{{error_column}}</span>
              </div>

                <div class="form-group">
                    <div class="row">
                     <div class="col-md-6">
                        <label for="txtfechaI">Fecha Inicio<span class="required">*</span></label>
                        <input type="text" class="form-control" id="fecha_inicio" name="fecha_inicio" ng-model="formData.fecha_inicio" placeholder="dia / mes / año"  required
                               ng-class="{ error: exampleForm.txtfechaI.$error.required && !exampleForm.$pristine}" >
                        <span class="text-danger">{{error_finicio}}</span>
                    </div>
                    <div class="col-md-6">
                         <label for="txtfechaF">Fecha Fin<span class="required">*</span></label>
                        <input type="text" class="form-control" id="fecha_fin"  name="fecha_fin"  ng-model="formData.fecha_fin" placeholder="dia / mes / año" required
                               ng-class="{ error: exampleForm.txtfechaI.$error.required && !exampleForm.$pristine}" >
                        <span class="text-danger">{{error_ffin}}</span>
                    </div>
                    </div>
			</div>
             <div class="form-group">
                <label for="Servicio">Servicio<span class="required">*</span></label>
                <select ng-model="formData.servicio" ng-options="servicio.ID as servicio.NOMBRE for servicio in Servicios"
                        class="form-control" id="servicio" name="servicio" ng-change='changeServicio(formData.servicio)' required>
                    <option value="" disabled>---Seleccione un Servicio---</option>
                </select>
                       <span class="text-danger">{{error_servicio}}</span>
             </div>
            <div class="form-group">
                <label  for="tipoServicio">Tipo de Servicio<span class="required">*</span></label>
                <select ng-model="formData.tipoServicio" ng-options="tipoServicio.ID as tipoServicio.NOMBRE for tipoServicio in tipoServicios"
                        class="form-control" id="tipoServicio" name="tipoServicio">
                    <option value="" disabled>---Seleccione un Tipo de Servicio---</option>

                </select>
            <span class="text-danger">{{error_tipo_servicio}}</span>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="btnCerrar">Cancelar</button>
        <button type="button" class="btn btn-primary" ng-click="submitBuscarFiltrados()" >Filtrar Datos</button>
      </div>
    </div>
  </div>
</div>
</div>

</span>
