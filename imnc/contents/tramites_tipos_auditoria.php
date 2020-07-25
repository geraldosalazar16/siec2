<span ng-controller="tramites_tipos_auditoria_controller">
<div class="right_col" role="main">
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>{{titulo}}</h2></p>
        <?php
          if ($modulo_permisos["SERVICIOS"]["catalogos"] == 1) {
              echo '<p>';
              echo '  <button type="button" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc" style="float: right;" ng-click="InsertarTipoAuditoria()"> ';
              echo '    <i class="fa fa-plus"> </i> Agregar tipo de auditor&iacutea';
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
                <th class="column-title">Clave de tipo de auditor&iacutea </th>
                <th class="column-title">Nombre de tipo de auditor&iacutea</th>
                <th class="column-title">Nombre Servicio</th>
                <th class="column-title">Nombre Etapa</th>
				<th class="column-title"></th>
              </tr>
            </thead>

            <tbody>
				<tr ng-repeat="x in tablaDatos" class="ng-scope even pointer">
					<td>{{x.ACRONIMO_AUDITORIA}}</td>
					<td>{{x.TIPO_AUDITORIA}}</td>
					<td>{{x.NOMBRE_SERVICIO}}</td>					
					<td>{{x.NOMBRE_ETAPA}}</td>
					<td >
					<?php
						if ($modulo_permisos["SERVICIOS"]["catalogos"] == 1 ) {
							echo '    <button type="button" class="btn btn-primary btn-xs btn-imnc btnEditar" id_tipo_servicio="{{x.ID}}" style="float: right;" ng-click="EditarTipoAuditoria(x.ID)"> ';
							echo '      <i class="fa fa-edit"> </i> Editar tipo de auditor&iacutea';
							echo '    </button>';
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
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtClave">Clave de tipo de auditor&iacutea <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtAcronimo" ng-model="txtAcronimo" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="4103"><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
				<span id="txtAcronimoerror" class="text-danger"></span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtClave">Nombre de tipo de auditor&iacutea <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtNombre" ng-model="txtNombre" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="4103"><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
				<span id="txtNombreerror" class="text-danger"></span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtRfc">Nombre de servicio  <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12" >
                <select ng-model="claveServicio" ng-options="claveServicio.ID as claveServicio.NOMBRE for claveServicio in claveServicios" 
                   class="form-control" id="claveServicio" name="claveServicio" ng-change='cambioclaveServicio(claveServicio)' required
                   ></select>
				 <ul class="parsley-errors-list" id="parsley-id-4103"></ul>
				<span id="claveServicioerror" class="text-danger"></span>
              </div>
			  
            </div>
			<div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtRfc">Nombre de Etapas  <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12" >
                <select ng-model="etapa" ng-options="etapa.ID_ETAPA as etapa.ETAPA for etapa in Etapas" class="form-control" id="etapa" name="etapa" required ng-disabled="!claveServicio"></select>
				</select>
                <ul class="parsley-errors-list" id="parsley-id-2324"></ul>
				<span id="etapaerror" class="text-danger"></span>
              </div>
			  
            </div>
			
            
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn" ng-click="cerrar()">Cerrar</button>
        <button type="button" class="btn btn-primary" ng-click="guardarTipoAuditoria()" id="btnGuardar">Guardar</button>
      </div>
    </div>
  </div>
</div>
</span>