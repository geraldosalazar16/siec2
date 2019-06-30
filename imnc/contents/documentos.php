<div class="right_col" role="main" ng-controller="cat_documentos_controller">
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>Cat&aacute;logo de documentos</h2></p>
        <?php
          if ($modulo_permisos["SERVICIOS"]["catalogos"] == 1) {
              echo '<p>';
              echo '  <button type="button" id="btnNuevo" ng-click="agregar_documento()" class="btn btn-primary btn-xs btn-imnc" style="float: right;"> ';
              echo '    <i class="fa fa-plus"> </i> Agregar documento';
              echo '  </button>';
              echo '</p>';
          } 
        ?>
        
          <div class="clearfix"></div>
        </div>

        <div class="x_content">
			<div class="form-group col-md-3">
                <label>Servicios</label>
                <select ng-model="filtroServ" ng-options="Servicio.ID as Servicio.NOMBRE for Servicio in Servicios" 
                                class="form-control" ng-change='cambioServicio()' >
					 <option value='' selected enabled>---Ver Todos---</option>
				</select>
				
            </div>
			<div class="form-group col-md-3">
                <label>Tipos de Servicio</label>
                <select ng-model="filtroTS" ng-options="tiposServicio.ID as tiposServicio.NOMBRE for tiposServicio in tiposServicios" 
                                class="form-control" ng-change='cambiotiposServicio()' >
					<option value='' selected enabled>---Ver Todos---</option>	
				</select>	
            </div>
			<div class="form-group col-md-3">
                <label>Etapas</label>
                <select ng-model="filtroEtapa" ng-options="etapa.ID_ETAPA as etapa.ETAPA for etapa in Etapas" 
                                class="form-control" ng-change='cambioEtapa()' >
					<option value='' selected enabled>---Ver Todos---</option>
				</select>
            </div>
			<div class="form-group col-md-3">
                <label>Secciones</label>
                <select ng-model="filtroSecciones" ng-options="seccion.ID as seccion.NOMBRE_SECCION for seccion in Secciones" 
                                class="form-control" ng-change='cambioSeccion()' >
					<option value='' selected enabled>---Ver Todos---</option>
				</select>
            </div>
          <table class="table table-striped responsive-utilities">
            <thead>
              <tr class="headings">
                <th class="column-title">Nombre del documento</th>
                <th class="column-title">Descripci&oacuten </th>
				<th class="column-title">Servicio </th>
				<th class="column-title">Tipo de servicio </th>
                <th class="column-title">Etapa</th>
                <th class="column-title">Secci&oacuten </th>
				<th class="column-title"></th>
              </tr>
            </thead>

            <tbody id="tbodyServicios">
				<tr ng-repeat="doc in Documentos">
					<td>{{doc.NOMBRE}}</td>
					<td>{{doc.DESCRIPCION}}</td>
					<td>{{doc.NOMBRE_SERVICIO}}</td>
					<td>{{doc.NOMBRE_TIPO_SERVICIO}}</td>
					<td>{{doc.ETAPA}}</td>
					<td>{{doc.NOMBRE_SECCION}}</td>
					<td>
						<button type="button" ng-click="editar_documento(doc.ID)" class="btn btn-primary btn-xs btn-imnc btnEditar" style="float: right;"> 
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
  
  <div class="modal fade" id="modalInsertarActualizar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-backdrop="static" data-keyboard="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="modalTitulo">{{modal_titulo}}</h4>
                    </div>
                    <div class="modal-body"> 
                        <form name="exampleForm">
                            <div class='form-group'>
                                <label for="nombre">Nombre<span class="required">*</span></label>
                                <input type="text" class="form-control" name="nombre" id="nombre" ng-model="formData.nombre" required
                                ng-class="{ error: exampleForm.nombre.$error.required && !exampleForm.$pristine}">
                            </div>
                            <div class="form-group">
                                <label for="descripcion">Descripci&oacuten</label>
                                <input type="text" class="form-control" name="descripcion" id="descripcion" ng-model="formData.descripcion">
                            </div>
							<div class="form-group">
                                <label for="servicio">Servicio<span class="required">*</span></label>
                                <select ng-model="formData.servicio" ng-options="servicio.ID as servicio.NOMBRE for servicio in Servicios" 
                                class="form-control" id="servicio" name="servicio" ng-change='cambioServicioModal()' required
                                ng-class="{ error: exampleForm.servicio.$error.required && !exampleForm.$pristine}"></select>
                            </div>
							<div class="form-group">
                                <label for="tiposServicio">Tipo de servicio<span class="required">*</span></label>
                                <select ng-model="formData.tiposServicio" ng-options="tiposServicio.ID as tiposServicio.NOMBRE for tiposServicio in tiposServicios" 
                                class="form-control" id="tiposServicio" name="tiposServicio" ng-change='cambiotiposServicioModal()' required
                                ng-class="{ error: exampleForm.tiposServicio.$error.required && !exampleForm.$pristine}"></select>
                            </div>
							<div class="form-group">
                                <label for="seccion">Secci&oacuten <span class="required">*</span></label>
                                <select ng-model="formData.seccion" ng-options="seccion.ID as seccion.NOMBRE_SECCION for seccion in Secciones" 
                                class="form-control" id="seccion" name="seccion" ng-change='cambioSeccionModal()' required
                                ng-class="{ error: exampleForm.seccion.$error.required && !exampleForm.$pristine}"></select>
                            </div>
                            <div class="form-group">
                                <label for="etapa">Etapa<span class="required">*</span></label>
                                <select ng-model="formData.etapa" ng-options="etapa.ID_ETAPA as etapa.ETAPA for etapa in Etapas" 
                                class="form-control" id="etapa" name="etapa" ng-change='cambioEtapaModal()' required
                                ng-class="{ error: exampleForm.etapa.$error.required && !exampleForm.$pristine}"></select>
                            </div>
							
                            <input type="submit" class="btn btn-success pull-right mt-2" ng-click="submitForm(formData)" ng-disabled="!exampleForm.$valid" value="Guardar"/>
                        </form>
                    </div>                                  
                    <div class="modal-footer">
                        <!--
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" ng-click="guardarUsuario()" id="btnGuardarUsuario">Save</button>
                        -->
                        
                    </div>
                </div>
            </div>
        </div>
</div>

<!-- Modal insertar/actualizar-->
<!--
<div class="modal fade" id="modalInsertarActualizar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" ng-controller="cat_documentos_controller">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTitulo">{{accion}}</h4>
      </div>
      <div class="modal-body">
          <form id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="nombre">Nombre<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="nombre" ng-model="nombre" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="4103"><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="descripcion">Descripci&oacuten
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="descripcion" ng-model="descripcion" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="4103"><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="etapa" >Etapa <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" id="etapa" ng-model="cmbSecciones" ng-options="etapa.ID_ETAPA as etapa.ETAPA for etapa in Etapas">
                  
                </select>
                <ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="seccion">Secci&oacuten <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" id="seccion" ng-model="cmbSecciones" ng-options="seccion.ID as seccion.NOMBRE_SECCION for seccion in Secciones">
                  
                </select>
                <ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnGuardar" ng-click="guardarDocumento()">Guardar</button>
      </div>
    </div>
  </div>
</div>
-->