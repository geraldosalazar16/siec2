<div class="right_col" role="main" ng-controller="status_auditoria_controller as $ctrl" ng-init='despliega_tipos_auditoria()' ng-cloak>

  <div class="row">
    <div class="col-md-6 col-sm-6 col-xs-6">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>Status de <?php echo strtolower($str_auditoria);?> </h2></p>
        <?php
          if ($modulo_permisos["SERVICIOS"]["registrar"] == 1 ) {
              echo '<p>';
              echo '  <button type="button" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc" style="float: right;" ng-click="modal_status_auditoria_insertar()"> ';
              echo '    <i class="fa fa-plus"> </i> Agregar ' . strtolower($str_auditoria);
              echo '  </button>';
              echo '</p>';
          } 
        ?>
        
          <div class="clearfix"></div>
        </div>

        <div class="x_content">

          <table class="table table-striped responsive-utilities jambo_table bulk_action">
            <thead>
              <tr class="headings">
                <th class="column-title">Clave</th>
                <th class="column-title">Status</th>
                <th class="column-title"></th>
              </tr>
            </thead>

            <tbody>
              <tr class="even pointer" ng-repeat="status_auditoria in arr_status_auditoria">
                <td>{{status_auditoria.ID}}</td>
                <td>{{status_auditoria.STATUS}}</td>
                <td>
                  <?php
                    if ($modulo_permisos["SERVICIOS"]["editar"] == 1) {
                        echo '<button type="button" class="btn btn-primary btn-xs btn-imnc btnEditar" ng-click="modal_status_auditoria_editar(status_auditoria.ID)" style="float: right;">       ';
                        echo '  <i class="fa fa-edit"></i> Editar ' . strtolower($str_auditoria);
                        echo '</button>';
                    } 
                  ?> 
                  
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal insertar/actualizar-->
  <div class="modal fade" id="modalInsertarActualizarStatusAuditoria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modalTituloStatusAuditoria">Insertar/Actualizar</h4>
        </div>
        <div class="modal-body">
            <form id="demo-form2" style="margin-top: -20px;">
              <div class="form-group form-vertical">
                <label class="control-label col-md-12">Clave <span class="required">*</span></label>
                <div class="col-md-12">
                  <input type="text" ng-model="status_auditoria_insertar_editar.ID" id="txtID"  required="required" class="form-control col-md-7 col-xs-12">
                </div>
              </div>
              <div class="form-group form-vertical">
                <label class="control-label col-md-12" > Status <span class="required">*</span></label>
                <div class="col-md-12">
                  <input type="text"  ng-model="status_auditoria_insertar_editar.STATUS" id="txtNombreCompleto" required="required" class="form-control col-md-7 col-xs-12">
                </div>
              </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" opcion="{{opcion_guardar_status_auditoria}}" id="btnGuardarStatusAuditoria" ng-click="status_auditoria_guardar()">Guardar</button>
        </div>
      </div>
    </div>
  </div>

</div>



