<link rel="stylesheet" type="text/css" href="css/jquery-ui-themes/jquery-ui.min.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui-themes/timepicker.css">
<script type="text/javascript" src="js/jquery-ui.js"></script>

<script src="js/ngFileUpload/ng-file-upload-shim.min.js"></script>
<script src="js/ngFileUpload/ng-file-upload.min.js"></script>

<script type="text/javascript" src="js/datepicker/timepicker.js"></script>
<script type="text/javascript" src="js/notify.js"></script>

<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
        <?php
        // if ($modulo_permisos["CLIENTES"]["extraer"] == 1) {
                  echo '<div class="dropdown" >';
                  echo '  <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">';
                  echo '  <i class="fa fa-cloud-download" aria-hidden="true"></i> Reporte Plan vs Programado';
                  echo '  <span class="caret"></span></button>';
                  echo '  <ul class="dropdown-menu">';
                  echo '    <li><a href="./generar/csv/cita_calendario_servicios/" target="_blank">CSV</a></li>';
                  echo '  </ul>';
                  echo '</div>';
             // } 
             ?>
        </div>
    </div>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12"><!--</div>col-md-3 col-sm-6 col-xs-6">-->
			<div class="x_panel">
				<div class="x_title">
					<p><h2>Filtros</h2></p>
					<div class="clearfix"></div>
				</div>
				<div class="x_content text-center">
					<div class="form-group" style="text-align: left;">
						<label>Tipos de servicio</label>
						<div>
						  <select class="select2_single form-control" id="selectTiposServicio" >
							
						  </select>
						</div>
					</div>

					<div class="form-group" style="text-align: left;">
						<label>Sectores</label>
						<div>
							<select class="select2_single form-control" id="selectSectores" >
						
							</select>
						</div>
					</div>

					<div class="form-group" style="text-align: left;">
						<label>Referencias</label>
						<div>
							<select class="select2_single form-control" id="selectReferencias" >
						
							</select>
						</div>
					</div>

					<div class="form-group" style="text-align: left;">
						<label>Clientes</label>
						<div>
							<select class="select2_single form-control" id="selectClientes" >
                
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-12 col-sm-12 col-xs-12>
			<div class="x_panel">
				<div class="x_title">
					<p><h2>Agenda de servicios</h2></p>
        
					</p>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
				   <div id='calendar'></div>
				</div>
		</div>
    </div>
	<!-- Modal Editar tarea -->
	<div ng-controller="tareas_controller">
		<div class="modal fade" id="modalCreateEvento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="modalTitulo">Nueva tarea</h4>
					</div>
					<div class="modal-body">
						<form name="form2" id="form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" enctype="multipart/form-data">
																
							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12">Tarea</label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<select ng-model="cmbTarea" ng-disabled="editar_tipo_tarea === false" required="required" 
									class="form-control col-md-7 col-xs-12" data-parsley-id="2324" ng-options="tarea.id_tarea as tarea.nombre_tarea for tarea in Tareas">
										<option value="">---Seleccione una Tarea---</option>
									</select>
																		
									<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
									<span id="tareaerror" class="text-danger"></span>
								</div>
							</div>
							
							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12">Auditor&iacutea Relacionada 
									<span class="required">*</span>
								</label>
					
								<div class="col-md-6 col-sm-6 col-xs-12">
									<select ng-model="cmbAuditorias" required="required" 
									class="form-control col-md-7 col-xs-12" data-parsley-id="2324" ng-options="auditoria as auditoria.nombre for auditoria in Auditorias">
										<option value="">---Seleccione una Auditoría---</option>
									</select>
																		
									<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
									<span id="auditoriaerror" class="text-danger"></span>
								</div>
							</div>
																
							<div class="form-group" id="FI">
								<label class="control-label col-md-4 col-sm-4 col-xs-12">Fecha Inicio
									<span class="required">*</span>
								</label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" required="required" ng-model="fecha_inicio" class="form-control col-md-5 col-xs-10" id="fecha_inicio" data-parsley-id="2324">
									<span id="fechainicioerror" class="text-danger"></span>
								</div>
							</div>

							<div class="form-group" id="HI">
								<label class="control-label col-md-4 col-sm-4 col-xs-12">Hora Inicio
									<span class="required">*</span>
								</label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="texttext" required="required" ng-model="hora_inicio" class="form-control col-md-5 col-xs-10" id="hora_inicio" data-parsley-id="2324">
									<span id="horainicioerror" class="text-danger"></span>
								</div>
							</div>
																
							<div class="form-group" id="FF">
								<label class="control-label col-md-4 col-sm-4 col-xs-12">Fecha Fin
									<span class="required">*</span>
								</label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" required="required" ng-model="fecha_fin" class="form-control col-md-5 col-xs-10" id="fecha_fin" data-parsley-id="2324">
									<span id="fechafinerror" class="text-danger"></span>
								</div>
							</div>

							<div class="form-group" id="HF">
								<label class="control-label col-md-4 col-sm-4 col-xs-12">Hora Fin
									<span class="required">*</span>
								</label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" required="required" ng-model="hora_fin" class="form-control col-md-5 col-xs-10" id="hora_fin" data-parsley-id="2324">
									<span id="horafinerror" class="text-danger"></span>
								</div>
							</div>
													
							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" ng-if="editar_descripcion_tarea">{{desc_modificacion_tarea}}</label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<textarea rows="4" cols="50" name="observaciones" id="observaciones" class="form-control col-md-7 col-xs-12" ng-if="editar_descripcion_tarea" ng-model="observaciones" data-parsley-id="2324">
									</textarea>
									<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
									<span id="observacioneserror" class="text-danger"></span>
								</div>
							</div>
																
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" ng-click="cerrar()">Cerrar</button>
						<button type="button" class="btn btn-primary" ng-click="guardar()" id="btnGuardar">Guardar</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>



