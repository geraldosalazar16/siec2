<div class="right_col" role="main" ng-controller="lista_usuarios_graficas_controller" ng-init='despliega_usuarios()' ng-cloak>

  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>Lista de usuarios </h2></p>
       
          <div class="clearfix"></div>
        </div>

        <div class="x_content">

          <table class="table table-striped responsive-utilities jambo_table bulk_action">
            <thead>
              <tr class="headings">
                <!--<th>
                  <input type="checkbox" id="check-all" class="flat">
                </th>-->
                <th class="column-title"></th>
                <th class="column-title">#</th>
                <th class="column-title">Nombre completo</th>
                <th class="column-title"></th>
              </tr>
            </thead>

            <tbody>
              <tr class="even pointer" ng-repeat="usuario in arr_usuarios">
                <td><img src="images/user.png" alt="" style="width: 35px;"></td>
                 <td>{{$index + 1}}</td>
                <td>{{usuario.NOMBRE}}</td>
                <td>
                  <button type="button" class="btn btn-primary btn-xs btn-imnc btnEditar" ng-click="ver_graficas(usuario.ID)" style="float: right;">       
                    <i class="fa fa-edit"></i> Ver gráficas 
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
  <div class="modal fade" id="modalInsertarActualizarUsuarios" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modalTituloUsuarios">Insertar/Actualizar</h4>
        </div>
        <div class="modal-body">
            <form id="demo-form2" style="margin-top: -20px;">
              <div class="form-group form-vertical" style="display: none;">
                <label class="control-label col-md-12">ID <span class="required">*</span></label>
                <div class="col-md-12">
                  <input type="text" ng-model="usuario_insertar_editar.ID" id="txtID"  placeholder="asignado automáticamente" required="required" class="form-control col-md-7 col-xs-12" disabled>
                </div>
              </div>
              <div class="form-group form-vertical">
                <label class="control-label col-md-12" >Nombre completo <span class="required">*</span></label>
                <div class="col-md-12">
                  <input type="text"  ng-model="usuario_insertar_editar.NOMBRE" id="txtNombreCompleto" required="required" class="form-control col-md-7 col-xs-12">
                </div>
              </div>
              <div class="form-group form-vertical">
                <label class="control-label col-md-12">Usuario <span class="required">*</span></label>
                <div class="col-md-12">
                   <input ng-model="usuario_insertar_editar.USUARIO" type="text" id="txtUsuario" required="required" class="form-control col-md-7 col-xs-12">
                </div>
              </div>
              <div class="form-group form-vertical">
                <label class="control-label col-md-12">Email <span class="required">*</span></label>
                <div class="col-md-12">
                   <input type="text" ng-model="usuario_insertar_editar.EMAIL"  id="txtEmail" required="required" class="form-control col-md-7 col-xs-12">
                </div>
              </div>
               <div class="form-group form-vertical">
                <label class="control-label col-md-12">Password <span class="required">*</span></label>
                <div class="col-md-12">
                   <input type="password" ng-model="usuario_insertar_editar.PASSWORD"  id="txtPass" required="required" class="form-control col-md-7 col-xs-12">
                </div>
              </div>
              <div class="form-group form-vertical" ng-repeat="modulo in arr_modulos">
                <label class="control-label col-md-12" >{{modulo.DESCRIPCION}} <span class="required">*</span></label>
                <div class="col-md-12">
                  <select class="form-control" ng-model='modulo.VALOR' id="{{modulo.DESCRIPCION}}" ng-options="perfil as perfil.DESCRIPCION for perfil in arr_perfiles track by perfil.ID">
                    <option value="" selected disabled>-- selecciona un perfíl --</option>
                  </select>
                </div>
              </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" opcion="{{opcion_guardar_usuario}}" id="btnGuardarUsuario" ng-click="usuario_guardar()">Guardar</button>
        </div>
      </div>
    </div>
  </div>

</div>



