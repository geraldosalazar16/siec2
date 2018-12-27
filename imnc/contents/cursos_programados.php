<link rel="stylesheet" type="text/css" href="css/jquery-ui-themes/jquery-ui.min.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui-themes/timepicker.css">
<script type="text/javascript" src="js/jquery-ui.js"></script>

<script src="js/ngFileUpload/ng-file-upload-shim.min.js"></script>
<script src="js/ngFileUpload/ng-file-upload.min.js"></script>

<script type="text/javascript" src="js/datepicker/timepicker.js"></script>
<script type="text/javascript" src="js/notify.js"></script>
<span ng-controller="cursos_programados_controller">
<div class="right_col" role="main">
<?php
if ($modulo_permisos["SERVICIOS"]["registrar"] == 1) {
    ?>

	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12"><!--</div>col-md-3 col-sm-6 col-xs-6">-->
			<div class="">
				<div class="x_title">
					<p><h2>Cursos Programados</h2></p>
                   <?php
                        echo '<p>';
                        echo '  <button type="button" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc" style="float: right;" ng-click="openModalInsertarModificar('."'insertar'".')"> ';
                        echo '    <i class="fa fa-plus"> </i> Agregar Curso ';
                        echo '  </button>';
                        echo '</p>';

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
            <div class="modal-dialog" role="document" id="modal-size" >
                <div id="divMostrar">
                <div class="modal-content">
                    <div style="margin: 20px;">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <button type="button" class="btn btn-green btn-sm "> <span class="glyphicon glyphicon-time"></span>  Duración: {{txtDuracion}}</button>
                    </div>
                    <div class="modal-body" style="margin: 20px; border-radius:10px; background-color: rgba(255,250,49,0.23);">
                            <div class="form-group">
                                <label>Referencia: {{txtReferencia}}</label>
                            </div>
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
								<label for="referencia">Referencia<span class="required">*</span></label>
								<div>
									<input type="text" class="form-control" id="referencia" name="referencia" ng-model="formData.referencia"  required
                                           disabled  ng-class="{ error: exampleForm.txtfechaI.$error.required && !exampleForm.$pristine}" >
									<span id="referenciaerror" class="text-danger" ></span>
								</div>
							</div>
                            <div class="form-group">
                                <label for="select_curso">Curso<span class="required">*</span></label>
                                <select ng-model="formData.selectCurso" ng-options="curso.ID_CURSO as curso.NOMBRE for curso in cursos"
                                        class="form-control" id="selectCurso" name="selectCurso" ng-change='onSelectedCurso()' required
                                        ng-class="{ error: exampleForm.select_curso.$error.required && !exampleForm.$pristine}" ng-disabled="accion=='editar'" >
                                    <option value="">---Seleccione un Curso---</option>
                                </select>
                            <span id="txtcursoerror" class="text-danger"></span>
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
                            <div class="form-group">
                                <label for="select_instructor">Instructor<span class="required">*</span></label>
                                <table class="table" >
                                    <tr>
                                        <td style="width: 70%;"><input  id="btnInstructor" type="button" class="form-control btn btn-blue" ng-click="openModalMostarInst()" value="Selecciona un Instructor" ></td>
                                        <td style="width: 30%;">
                                        <div class="checkbox-inline"  >
                                        <label>
                                          <input  id="chckVerTodos" type="checkbox" ng-model="formData.chckVerTodos" class="checkbox"  value="true" > Ver Todos
                                        </label>
                                      </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><span id="txtinstructorerror" class="text-danger"></span></td>
                                    </tr>
                                </table>

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
                <!-- Modal Mostrar Instructores-->
                <div id="divInstructor">
                     <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" ng-click="cerrarInstructores()" style="float:right;font-size:21px;font-weight:700;line-height:1;color:#000;text-shadow:0 1px 0 #fff;filter:alpha(opacity=20);opacity:.2">&times;</button>
                        <h4 class="modal-title" id="modalTituloInst">Seleccione un Instructor</h4>
                      </div>
                      <div class="modal-body" id="body-modalIns" style="overflow: auto;">
                         <h2 style="color: #1c1c1c;">Para Curso: {{ nombre_curso }}</h2>
                          <div class="form-group pull-right">
                                <label for="select_instructor">Buscar:</label>
                                <input class="form-control" type="search" ng-model="formData.searchText">
                            </div>
                          <table class="table table-hover" style="background-color: transparent">
                          <thead id="thead-modal-explora-sitios">
                            <tr>
                                <th style="width: 40%;">Datos del Instructor</th>
                                <th style="width: 25%;">Roles</th>
                                <th style="width: 25%;">Calif. Cursos</th>
                                <th style="width: 10%;"></th>
                            </tr>
                          </thead>
                          <tbody id="tbody-modal-explora-sitios">
                            <tr ng-repeat="instructor in instructoresCursos | filter:formData.searchText">
                                <td td style="font-size: 12px;">
                                    <table style="background-color: transparent">
                                        <tr>
                                        <td>
                                             <strong><label ng-if="id_instructor == instructor.ID" style="color: #1c1c1c;">{{instructor.NOMBRE}} <span class="glyphicon glyphicon-ok" aria-hidden="true"></span></label></strong>
                                             <strong><label ng-if="id_instructor != instructor.ID" style="color: #1c1c1c;">{{instructor.NOMBRE}}</label></strong>
                                        </td>
                                        </tr>
                                        <tr>
                                        <td>
                                             {{instructor.STATUS}}<br>
                                        </td>
                                        </tr>
                                    </table>

                                    <input type="text" id="lb-{{instructor.ID}}" value="{{instructor.NOMBRE}}" hidden>


                                </td>
                                <td style="font-size: 11px;">

                                        <div ng-repeat="rol in instructor.ROLES">
                                            <label> {{rol.ROL}} <span ng-if="rol.ID_ROL == 7"  class="glyphicon glyphicon-ok" aria-hidden="true"></span></label>
                                        </div>
                                </td>
                                <td style="font-size: 11px;">
                                        <div ng-repeat="curso in instructor.CURSOS">
                                            <label>{{curso.NOMBRE_CURSO}} <span  ng-if="id_curso == curso.ID_CURSO" class="glyphicon glyphicon-ok" aria-hidden="true"></span></label>
                                        </div>
                                </td>

                                <td>
                                    <button  type="button"  class="btn btn-default btn-xs" style="float: right;" disabled  ng-if="instructor.STATUS=='inactivo' || instructor.ISROL==false || instructor.ISCURSO == false"> seleccionar </button>
                                    <button  id="btn-{{instructor.ID}}" type="button" class="btn btn-primary btn-xs btn-imnc " style="float: right;" ng-if="instructor.STATUS=='activo' && instructor.ISROL==true && instructor.ISCURSO == true" ng-click="onSelectInstructor(instructor.ID)" ng-disabled="id_instructor == instructor.ID"> seleccionar</button>
                                    <div  style="font-size: 9px;" id="error-{{instructor.ID}}" hidden></div>
                                </td>
                            </tr>

                          </tbody>
                        </table>
                      </div>
                    </div>
                </div>
        </div>
  </div>


<?php } ?>
</div>
</span>




