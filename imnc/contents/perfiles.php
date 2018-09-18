<div class="right_col" role="main" ng-controller="perfiles_controller as $ctrl" ng-init='despliega_perfiles()' ng-cloak>

  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>Cat&aacute;logo de perfiles </h2></p>
        <p>
          <button type="button" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc" style="float: right;" ng-click='modal_perfil_insertar()'> 
            <i class="fa fa-plus"> </i> Agregar perfil
          </button>
        </p>
          <div class="clearfix"></div>
        </div>

        <div class="x_content">

          <table class="table table-striped responsive-utilities jambo_table bulk_action">
            <thead>
              <tr class="headings">
                <!--<th>
                  <input type="checkbox" id="check-all" class="flat">
                </th>-->
                <th class="column-title">Id</th>
                <th class="column-title">Perfíl</th>
                <th class="column-title">Descripción</th>
                <th class="column-title" style="width: 460px;">Permisos</th>
                <th class="column-title"></th>
              </tr>
            </thead>

            <tbody>
              <tr class="even pointer" ng-repeat="perfil in arr_perfiles">
                <td>{{perfil.ID}}</td>
                <td>{{perfil.PERFIL}}</td>
                <td>{{perfil.DESCRIPCION}}</td>
                <td>{{perfil.PERMISOS_DESPLIGUE_WEB}}</td>
                <td>
                  <button type="button" class="btn btn-primary btn-xs btn-imnc btnEditar" ng-click="modal_perfil_editar(perfil.ID)" style="float: right;">       
                    <i class="fa fa-edit"></i> Editar perfil 
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal insertar/actualizar-->
  <div class="modal fade" id="modalInsertarActualizarPerfiles" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modalTituloPerfiles">Insertar/Actualizar</h4>
        </div>
        <div class="modal-body">
            <form id="demo-form2" style="margin-top: -20px;">
              <div class="form-group form-vertical">
                <label class="control-label col-md-12">ID <span class="required">*</span></label>
                <div class="col-md-12">
                  <input type="text" ng-model="obj_perfil.ID"required="required" class="form-control col-md-7 col-xs-12" >
                </div>
              </div>
              <div class="form-group form-vertical">
                <label class="control-label col-md-12" > Perfíl <span class="required">*</span></label>
                <div class="col-md-12">
                  <input type="text"  ng-model="obj_perfil.PERFIL"  required="required" class="form-control col-md-7 col-xs-12">
                </div>
              </div>
              <div class="form-group form-vertical">
                <label class="control-label col-md-12">Descripcion <span class="required">*</span></label>
                <div class="col-md-12">
                   <input ng-model="obj_perfil.DESCRIPCION" type="text" required="required" class="form-control col-md-7 col-xs-12">
                </div>
              </div>
              <div class="form-group form-vertical">
                <label class="control-label col-md-12">Permisos <span class="required">*</span></label>
                <div class="col-md-12">
                  <div class="panel panel-default">
                    <div class="panel-body">

                      <div class="checkbox" ng-repeat="permiso in arr_permisos">
                        <label><input type="checkbox" class="chkPermisos" ng-checked="permiso.SELECCIONADO" value="{{permiso.PERMISO}}">{{permiso.NOMBRE}}</label>
                      </div>
                    </div>
                  </div>
                   <!-- <input ng-model="obj_perfil.DESCRIPCION" type="text" required="required" class="form-control col-md-7 col-xs-12"> -->
                </div>
              </div>

              
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" opcion="{{opcion_guardar_perfil}}" id="btnGuardarPerfil" ng-click="perfil_guardar()">Guardar</button>
        </div>
      </div>
    </div>
  </div>

</div>



