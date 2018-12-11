<link rel="stylesheet" type="text/css" href="css/jquery-ui-themes/jquery-ui.min.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui-themes/timepicker.css">
<script type="text/javascript" src="js/jquery-ui.js"></script>

<script src="js/ngFileUpload/ng-file-upload-shim.min.js"></script>
<script src="js/ngFileUpload/ng-file-upload.min.js"></script>

<script type="text/javascript" src="js/datepicker/timepicker.js"></script>
<script type="text/javascript" src="js/notify.js"></script>
<span ng-controller="cursos_programados_controller">
<div class="right_col" role="main">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12"><!--</div>col-md-3 col-sm-6 col-xs-6">-->
			<div class="">
				<div class="x_title">
					<p><h2>Cursos Programados</h2></p>
                    <?php
                    if ($modulo_permisos["SERVICIOS"]["registrar"] == 1) {
                        echo '<p>';
                        echo '  <button type="button" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc" style="float: right;" ng-click="openModalInsertarModificar('."'insertar'".')"> ';
                        echo '    <i class="fa fa-plus"> </i> Agregar Curso ';
                        echo '  </button>';
                        echo '</p>';
                    }
                    ?>
					<div class="clearfix"></div>
                    <div class="x_content">
                       <div id='calendar'></div>
                    </div>

				</div>
            </div>

		</div>

    </div>

    <!-- Modal Mostrar Datos-->
  <div class="modal fade"  id="modalMostrar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
       data-backdrop="static" data-keyboard="true">
            <div class="modal-dialog" role="document" >
                <div id="divMostrar">
                <div class="modal-content">
                    <div style="margin: 20px;">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <button type="button" class="btn btn-green btn-sm "> <span class="glyphicon glyphicon-time"></span>  Duración: {{txtDuracion}}</button>

                    </div>
                    <div class="modal-body" style="margin: 20px; border-radius:10px; background-color: rgba(255,250,49,0.23);">

                            <div class="form-group">
                                <label>Curso: {{txtCurso}}</label>
                            </div>
                           <div class="form-group">
                                <label>Instructor: {{txtInstructor}}</label>
                           </div>
                           <div class="form-group">
                               <label>Período: {{txtFechas}}</label>
                           </div>
                           <div class="form-group">
                                <label>Mínimo de Personas: {{txtMinimo}}</label>
                           </div>


                </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-default" ng-click="eliminaEvento()" id="btnEliminar">Eliminar</button>
                        <button type="button" class="btn btn-primary" ng-click="openModalInsertarModificar('editar')" id="btnEditar">&nbsp;&nbsp;&nbsp;&nbsp;Editar&nbsp;&nbsp;&nbsp;&nbsp;</button>


                    </div>
            </div>
                </div>
                <div id="divInsertar">
                                    <div class="modal-content">
                    <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="modalTitulo">{{modal_titulo}}</h4>
                    </div>
                    <div class="modal-body">
                        <form name="exampleForm">
                            <div class="form-group">
                                <label for="select_curso">Curso<span class="required">*</span></label>
                                <select ng-model="formData.selectCurso" ng-options="curso.ID_CURSO as curso.NOMBRE for curso in cursos"
                                        class="form-control" id="selectCurso" name="selectCurso" ng-change='' required
                                        ng-class="{ error: exampleForm.select_curso.$error.required && !exampleForm.$pristine}"  >
                                    <option value="">---Seleccione un Curso---</option>
                                </select>
                            <span id="txtcursoerror" class="text-danger"></span>
                            </div>
                            <div class="form-group">
                                <label for="select_instructor">Instructor<span class="required">*</span></label>
                                <select ng-model="formData.instructor" ng-options="instructor.ID as instructor.NOMBRE+' '+instructor.APELLIDO_PATERNO+' '+instructor.APELLIDO_MATERNO for instructor in instructores"
                                        class="form-control" id="instructor" name="instructor" ng-change='' required
                                        ng-class="{ error: exampleForm.select_instructor.$error.required && !exampleForm.$pristine}"  >
                                    <option value="">---Seleccione un Instructor---</option>
                                </select>
                            <span id="txtinstructorerror" class="text-danger"></span>
                            </div>

                            <div class="form-group">
								<label for="txtfechaI">Fecha Inicio<span class="required">*</span></label>
								<div>
									<input type="text" class="form-control" id="fecha_inicio" name="fecha_inicio" ng-model="formData.fecha_inicio" placeholder="dia / mes / año"  required
                                           ng-class="{ error: exampleForm.txtfechaI.$error.required && !exampleForm.$pristine}" >
									<span id="fechainicioerror" class="text-danger"></span>
								</div>
							</div>

                            <div class="form-group">
								<label for="txtfechaF">Fecha Fin<span class="required">*</span></label>
								<div>
									<input type="text" class="form-control" id="fecha_fin"  name="fecha_fin"  ng-model="formData.fecha_fin" placeholder="dia / mes / año" required
                                           ng-class="{ error: exampleForm.txtfechaI.$error.required && !exampleForm.$pristine}" >
									<span id="fechafinerror" class="text-danger"></span>
								</div>
							</div>

                            <div class='form-group'>
                                <label for="txt_minimo">Mínimo de Personas<span class="required">*</span></label>
                                <input type="text" class="form-control" name="minimo" id="minimo" ng-model="formData.minimo"   required
                                       ng-class="{ error: exampleForm.txt_minimo.$error.required && !exampleForm.$pristine}" >
                            <span id="txtminimoerror" class="text-danger"></span>
                            </div>




                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" ng-click="submitForm(formData)" id="btnGuardarUsuario">Guardar</button>


                    </div>
            </div>
                </div>
        </div>
  </div>

</div>
</span>




