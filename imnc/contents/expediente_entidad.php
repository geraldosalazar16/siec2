<span ng-controller="expediente_entidad_controller">
<style>
  .check-noshadow {
    -webkit-box-shadow: none;
    -moz-box-shadow: none;
    box-shadow: none;
  }
  .break-word {
    width: 100%;
    white-space: nowrap;
    position: relative;
    overflow: hidden;
    text-overflow: ellipsis;
  }
}
</style>
<div class="right_col" role="main">
  <div class="row">
    <div class="col-md-9 col-sm-9 col-xs-9">
      <div class="x_panel">
        <div class="x_title">
          <!--<div class="input-group">
           <span class="input-group-addon">Buscar:</span>
           <input type="text" class="form-control" ng-model="test" placeholder="Por Entidad o Expediente">
          </div>-->
        <p><h2 class="break-word">{{titulo}}</h2></p>
        <p>
		   
          <button type="button" ng-click="agregar(1)" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc"
          ng-if='modulo_permisos["registrar"] == 1' style="float: right;"> 
            <i class="fa fa-plus"> </i> Agregar Entidad
          </button>
		  <button type="button" ng-click="agregar(0)" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc"
      ng-if='modulo_permisos["registrar"] == 1' style="float: right;"> 
            <i class="fa fa-plus"> </i> Agregar Tr&aacute;mite
          </button>
		 
			    <a href="./?pagina=tipo_expediente" class="btn btn-primary btn-xs btn-imnc" style="float: right;">
            <i class="fa fa-edit"> </i> Regresar
          </a>
        </p>
          <div class="clearfix"></div>
        </div>

        <div class="x_content">

          <table class="table table-striped responsive-utilities jambo_table bulk_action">
            <thead>
              <tr class="headings">
                <th class="column-title">#</th>
                <th class="column-title">Entidad</th>
                <th class="column-title">Expediente</th>
                <th class="column-title">Vigente&nbsp;&nbsp;</th>
        <th class="column-title">&nbsp;</th>
              </tr>
            </thead>

            <tbody>
        <!--<tr ng-repeat="x in expedienteentidad | filter:test">-->
		<tr ng-repeat="x in expedienteentidad" class="ng-scope even pointer">
          <td>{{$index + 1}}</td>
          <td>{{x.ENTIDAD}}</td>
          <td>{{x.EXPEDIENTE}}</td>
          <td ng-if="x.ESTADO == 1">Vigente</td>
          <td ng-if="x.ESTADO == 0">No Vigente</td>  
          <td ng-if="x.ESTADO == 0">
            <button type="button" ng-click="activar(x.ID, x.ESTADO)" class="btn btn-primary btn-xs btn-imnc btnEditar"
            ng-if='modulo_permisos["editar"] == 1' style="float: right;">
              <i class="fa fa-edit"> </i> Activar
            </button>
          </td>
		  <td ng-if="x.ESTADO == 1">
            <button type="button" ng-click="activar(x.ID, x.ESTADO)" class="btn btn-primary btn-xs btn-imnc btnEditar"
            ng-if='modulo_permisos["editar"] == 1' style="float: right;">
              <i class="fa fa-edit"> </i> Desactivar
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
        <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
        <h4 class="modal-title" id="modalTitulo">Insertar/Actualizar</h4>
      </div>
      <div class="modal-body">
          <form id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
            <div class="form-group">
                 <span id="errorexpedienteentidad" class="text-danger"></span>
              <label class="control-label col-md-4 col-sm-4 col-xs-12" id="tipo">Entidad<span class="required">*</span>
              </label>
              
              <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="hidden" id="txtId" />
        <select ng-model="selectelementos" id="selectelementos" class="form-control col-md-7 col-xs-12">
        <option ng-repeat="item in comboelementos" value="{{item.ID}}">{{item.DESCRIPCION}}</option>
        </select>
        <span id="errorentidad" class="text-danger"></span>
                <ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div> 
            </div>
            
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" ng-click="cerrar()" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" ng-click="guardar()"  id="btnGuardar">Guardar</button>
      </div>
    </div>
  </div>
</div>
