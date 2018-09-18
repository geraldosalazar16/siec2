<span ng-controller="prospecto_controller">
<div class="right_col" role="main">
	<div class="row">
		<div class="col-md-10">
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
					<div class="col-sm-12">	
						<label class="control-label col-md-12 col-sm-12 col-xs-12">Filtrar por nombre de prospecto</label>
						<select id="cmbProspectos" ng-model="listado_prospecto" class="select2_single form-control col-md-7 col-xs-12" ng-options="prospecto.ID as prospecto.NOMBRE for prospecto in Prospectos"> 
							<option value="elige" ng-selected="true" disabled>Seleccione un prospecto</option>
						</select>						
					<a href="./?pagina=perfilprospecto&id={{prospecto_seleccionado}}&entidad=1" ng-if='modulo_permisos["editar"] == 1 && mostrar_perfil_seleccionado' class="btn btn-primary btn-xs btn-success" style="float: left;margin-top:10px;"><i class="fa fa-home"> </i>Perfil</a>	
						<button type="button" style="margin-top:10px" ng-if="mostrar_editar_seleccionado == true" ng-click="editar(prospecto_seleccionado)" class="btn btn-primary btn-xs btn-imnc btnEditar" style="margin-top:10px;">
							<i class="fa fa-edit"> </i> Editar
						</button>
						
						<button type="button" style="margin-top:10px" ng-click="detalles(prospecto_seleccionado)" ng-if='mostrar_detalles_seleccionado' class="btn btn-primary btn-xs btn-info">
							Detalles
						</button>          
						<a href="./?pagina=registro_expediente&id={{prospecto_seleccionado}}&id_entidad=3" class="btn btn-primary btn-xs btn-imnc" ng-if='modulo_permisos["documentos"] == 1&&mostrar_expedientes_seleccionado' style="margin-top:10px;"><i class="fa fa-home"> </i> Expedientes </a>
					</div>
				</div>
				<!-- Aca se muestra la cantidad de prospectos asignados al comercial-->
				<div>
					<p>
						Cantidad de prospectos asignados al comercial: {{cantidad_prospectos}}
					</p>
				</div>
				<div class="x_content">
					<table class="table table-striped responsive-utilities jambo_table bulk_action">
						<thead>
							<tr class="headings">
								<th class="column-title">Nombre</th>
								<th class="column-title">RFC</th>
								<th class="column-title">Porcentaje</th>
								<th class="column-title">Estatus</th>
								<th class="column-title">Tipo Contrato</th>
								  
								<th class="column-title">&nbsp;</th>
								<th class="column-title">&nbsp;</th>
								<th class="column-title">&nbsp;</th>
							</tr>
						</thead>

						<tbody>
							<tr ng-repeat="x in prospecto" class="ng-scope even pointer">
								<!--<td>{{x.ID}}</td>-->
								<td>{{x.NOMBRE}}</td>
								<td>{{x.RFC}}</td>
								<td>{{x.PORCENTAJE}}</td>
								<td>{{x.NOMBRE_ESTATUS_SEGUIMIENTO}}</td>
								<td>{{x.NOMBRE_TIPO_CONTRATO}}</td>
								<td>
									<button type="button" ng-if='modulo_permisos["editar"] == 1' ng-click="editar(x.ID)" class="btn btn-primary btn-xs btn-imnc btnEditar" style="float: right;">
										<i class="fa fa-edit"> </i> Editar
									</button>
								</td>
								<td>
									<a href="./?pagina=perfilprospecto&id={{x.ID}}&entidad=1" class="btn btn-primary btn-xs btn-success" style="float: right;"><i class="fa fa-home"> </i>Perfil</a>
								</td>
								<td>
									<button type="button"  ng-click="detalles(x.ID)" class="btn btn-primary btn-xs btn-info">Detalles </button>          
									<a href="./?pagina=registro_expediente&id={{x.ID}}&id_entidad=3" class="btn btn-primary btn-xs btn-imnc" ng-if='modulo_permisos["documentos"] == 1' style="float: right;">            <i class="fa fa-home"> </i> Expedientes </a>
							
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
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Empresa:<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="nombre" id="nombre" class="form-control col-md-7 col-xs-12" ng-model="nombre">
				        <span id="nombreerror" class="text-danger"></span>
			        </div>
            </div>
            

            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">RFC
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="rfc" data-inputmask="'mask': '<?php echo $str_mascara_rfc; ?> '" placeholder="PELJ900412XXX" class="form-control col-md-7 col-xs-12">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Giro:
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="giro" class="form-control col-md-7 col-xs-12" ng-model="giro">
              </div>
            </div>
			<div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Origen
              <span class="required">*</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select ng-model="origen" ng-disabled="habilitar_origen == false"required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324" ng-options="ori.id_origen as ori.origen for ori in Origenes">
                  <option value="">---Seleccione un origen---</option>
                </select>
                <span id="origenerror" class="text-danger"></span>
              </div>
            </div>
			<!--
			<div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Tipo de Servicio
              <span class="required">*</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select ng-model="tipo_servicio" ng-disabled="habilitar_tipo_servicio == false" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324" ng-options="tipos.id_tipo_servicio as tipos.tipo_servicio for tipos in TiposServicio">
                  <option value="">---Seleccione un tipo de servicio---</option>
                </select>
                <span id="tiposservicioerror" class="text-danger"></span>
              </div>
            </div>
			-->
			<div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Competencia</label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select ng-model="competencia" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324" ng-options="com.id_competencia as com.competencia for com in Competencia">
                  <option value="">---Seleccione una competencia---</option>
                </select>
                <span id="origenerror" class="text-danger"></span>
              </div>
            </div>
			<div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Estatus seguimiento
              <span class="required">*</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select ng-model="estatus_seguimiento" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324" ng-options="estseg.id_estatus_seguimiento as estseg.estatus_seguimiento for estseg in Estatus_seguimiento">
                  <option value="">---Seleccione un estatus---</option>
                </select>
                <span id="origenerror" class="text-danger"></span>
              </div>
            </div>
			<div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Tipo de contrato
              <span class="required">*</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select ng-model="tipo_contrato" ng-disabled="habilitar_tipo_contrato == false" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324" ng-options="tipcon.id_tipo_contrato as tipcon.tipo_contrato for tipcon in Tipo_contrato">
                  <option value="">---Seleccione un tipo de contrato---</option>
                </select>
                <span id="origenerror" class="text-danger"></span>
              </div>
            </div>
			<!--
			<div class="form-group" ng-if='modulo_permisos["asignar_prospecto"] == 1'>
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Usuario Principal
              <span class="required">*</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select ng-model="usuarioPrincipal" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324" ng-options="usuario_principal.id as usuario_principal.descripcion for usuario_principal in Usuarios_principal">
                  <option value="">---Seleccione un usuario---</option>
                </select>
                <span id="usuarioserror" class="text-danger"></span>
              </div>
            </div>
			-->
			<div class="form-group" ng-show="mostrarUsuarioPrincipal">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Usuario Principal
              <span class="required">*</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select ng-model="usuariosP" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324" ng-options="usuario.id as usuario.descripcion for usuario in Usuarios">
                  <option value="">---Seleccione un usuario---</option>
                </select>
                <span id="usuarioserror" class="text-danger"></span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Usuario Secundario
              <span class="required">*</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select ng-model="usuarios" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324" ng-options="usuario.id as usuario.descripcion for usuario in Usuarios">
                  <option value="">---Seleccione un usuario---</option>
                </select>
                <span id="usuarioserror" class="text-danger"></span>
              </div>
            </div>
			<!--
			<div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Departamento
              <span class="required">*</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select ng-model="departamentos" ng-disabled="habilitar_departamento == false" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324" ng-options="dep.id as dep.nombre for dep in Departamentos">
                  <option value="">---Seleccione un departamento---</option>
                </select>
                <span id="usuarioserror" class="text-danger"></span>
              </div>
            </div>
			<!--
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Habilitado<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="checkbox" class="form-control col-md-7 col-xs-12 selector noshadow" ng-model="cbhabilitado" data-parsley-id="2324" ><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
            -->
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default">Cerrar</button>
        <button type="button" class="btn btn-primary" ng-click="guardar()"  id="btnGuardar">Guardar</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal Detalles-->
<div class="modal fade" id="modalDetalles" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTitulo">Detalles</h4>
      </div>
      <div class="modal-body">
          <form name="form2" id="form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Nombre:<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="nombre" required="required" class="form-control col-md-7 col-xs-12" ng-model="nombre" data-parsley-id="2324" 
                readonly="false" >
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Origen
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="origen-det" required="required" class="form-control col-md-7 col-xs-12" ng-model="nombre_origen" data-parsley-id="2324" readonly="false" ><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
			<div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Principal competencia
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="competencia-det" required="required" class="form-control col-md-7 col-xs-12" ng-model="nombre_competencia" data-parsley-id="2324" readonly="false" ><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
			<div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Estatus seguimiento
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="estatus_seguimiento-det" required="required" class="form-control col-md-7 col-xs-12" ng-model="nombre_estatus_seguimiento" data-parsley-id="2324" readonly="false" ><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
			
			<div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Tipo contrato
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="tipo_contrato-det" required="required" class="form-control col-md-7 col-xs-12" ng-model="nombre_tipo_contrato" data-parsley-id="2324" readonly="false" ><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">RFC
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="rfc-det" required="required" class="form-control col-md-7 col-xs-12" ng-model="rfc" data-parsley-id="2324" readonly="false" ><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Giro:
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="giro" required="required" class="form-control col-md-7 col-xs-12" ng-model="giro" data-parsley-id="2324" readonly="false"><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
            
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Id Usuario creacion:
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="id_usuario_creacion" required="required" class="form-control col-md-7 col-xs-12" ng-model="id_usuario_creacion" data-parsley-id="2324" readonly="false"><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Id Usuario modificacion:
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="id_usuario_modificacion" required="required" class="form-control col-md-7 col-xs-12" ng-model="id_usuario_modificacion" data-parsley-id="2324" readonly="false"><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Fecha creacion:
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="fecha_creacion" required="required" class="form-control col-md-7 col-xs-12" ng-model="fecha_creacion" data-parsley-id="2324" readonly="false"><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Fecha modificacion:
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="fecha_modificacion" required="required" class="form-control col-md-7 col-xs-12" ng-model="fecha_modificacion" data-parsley-id="2324" readonly="false"><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Habilitado<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="checkbox" class="form-control col-md-7 col-xs-12 selector noshadow"  disabled="disabled" ng-model="cbhabilitado" data-parsley-id="2324" ><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" ng-click="cerrarDetalle()">Cerrar</button>
      </div>
    </div>
  </div>
</div>

