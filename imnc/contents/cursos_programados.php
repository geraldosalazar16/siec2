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
					<div class="clearfix"></div>
                    <div class="x_content" style="margin-top: 20px;">
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
                           <div class="form-group">
                                <label>Etapa: {{txtEtapa}}</label>
                           </div>


                </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cerrar</button>
                      <!--  <button type="button" class="btn btn-default" ng-click="eliminaEvento()" id="btnEliminar">Eliminar</button>
                        <button type="button" class="btn btn-primary" ng-click="openModalInsertarModificar('editar')" id="btnEditar">&nbsp;&nbsp;&nbsp;&nbsp;Editar&nbsp;&nbsp;&nbsp;&nbsp;</button> -->
                         <div class="btn-group">

										<button type="button" class="btn btn-primary btn-sm dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" > Opciones
											<span class="caret"></span>
											<span class="sr-only">Toggle Dropdown</span>
										</button>
										<ul class="dropdown-menu pull-right">
											<li>
												<a ng-click="openModalInsertarModificar('editar')">
												<span class="labelAcordeon"	>Editar Curso Programado</span></a>

											</li>
                                            <li>
												<a ng-click="openModalParticipantes()">
												<span class="labelAcordeon"	>Participantes</span></a>

											</li>
                                             <li>
												<a ng-click="openModalHistorico()">
												<span class="labelAcordeon"	>Ver Histórico</span></a>

											</li>
                                            <li>
												<a ng-click="eliminaEvento()">
												<span class="labelAcordeon"	>Eliminar</span></a>

											</li>
                                        </ul>
                                    </div>

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
                            <div class="form-group" >
                                <label for="selectEtapa">Etapa<span class="required">*</span></label>
                              <select ng-model="formData.selectEtapa" id="selectEtapa" name="selectEtapa" ng-disabled="enVerde == false"
                                         ng-options="etapa.ID as etapa.NOMBRE for etapa in Etapas"
                                        class="form-control">
                                </select>

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
                <!-- Ver Historico -->
                <div id="divVerHistorico">
                <div class="modal-content">
                    <div class="modal-header">
					<button type="button" ng-click="cerrarHistorico()" style="float:right;font-size:21px;font-weight:700;line-height:1;color:#000;text-shadow:0 1px 0 #fff;filter:alpha(opacity=20);opacity:.2">&times;</button>
                    <h4 class="modal-title" id="modalTitulo">Histórico</h4>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped responsive-utilities jambo_table bulk_action">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Descripción</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="x in Historial" class="ng-scope  even pointer">
                                <td>{{x.ID}}</td>
                                <td ng-if="x.MODIFICACION == 'MODIFICANDO CURSO'">El d&iacutea {{FuncionFecha(x.FECHA)}} el usuario {{x.NOMBRE_USUARIO}} modificó el curso programado con los datos: <ul class="list-unstyled" style="font-size: 10px;"><li><strong>Estado actual:</strong> [ {{x.ESTADO_ACTUAL}} ]</li><li style="color: #919191;"><strong>Estado anterior:</strong> [ {{x.ESTADO_ANTERIOR}} ]</li></ul> </td>
                                <td ng-if="x.MODIFICACION == 'NUEVO CURSO'">El d&iacutea {{FuncionFecha(x.FECHA)}} el usuario {{x.NOMBRE_USUARIO}} agregó un nuevo curso programado con los datos: <ul class="list-unstyled" style="font-size: 10px;"><li><strong>{{x.ESTADO_ACTUAL}}</strong></li></ul></td>
                                <td ng-if="x.MODIFICACION == 'ELIMINO CURSO'">El d&iacutea {{FuncionFecha(x.FECHA)}} el usuario {{x.NOMBRE_USUARIO}} eliminó el curso programado con los datos: <ul class="list-unstyled" style="font-size: 10px;"><li><strong>{{x.ESTADO_ANTERIOR}}</strong></li></ul></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>

                <!-- Ver Participantes -->
                <div id="divVerParticipantes">
                <div class="modal-content">
                    <div class="modal-header">
					<button type="button" ng-click="cerrar('divVerParticipantes')" style="float:right;font-size:21px;font-weight:700;line-height:1;color:#000;text-shadow:0 1px 0 #fff;filter:alpha(opacity=20);opacity:.2"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="modalTitulo">Participantes</h4>
                    </div>
                    <div class="modal-body" style="min-height: 400px;">
                         <button type="button" ng-click="openModalInsertParticipantes()" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc" style="float: right;"> + Agregar Participante</button>
                        <br>
                        <table class="table table-striped responsive-utilities jambo_table bulk_action" style="margin-top: 20px;" >
                            <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Correo Electrónico</th>
                                <th>Teléfono</th>
                                <th>CURP</th>
                                <th>Perfil</th>
                                <th>Estado</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="(key, item) in participantes" class="ng-scope  even pointer">
                                <td>{{item.NOMBRE}}</td>
                                <td>{{item.EMAIL}}</td>
                                <td>{{item.TELEFONO}}</td>
                                <td>{{item.CURP}}</td>
                                <td>{{item.PERFIL}}</td>
                                <td>{{item.ID_ESTADO}}</td>
                                <td>
                                    <button type="button" ng-click="openModalInsertParticipantes(key)" class="btn btn-primary btn-xs btn-imnc "> Editar</button>
                                    <button type="button" ng-click="eliminarParticipantes(key)" class="btn btn-primary btn-xs btn-imnc "> Eliminar</button>
                                </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>

                <!-- Ver InsertParticipante -->
                <div id="divVerInsertParticipantes">
                <div class="modal-content">
                    <div class="modal-header">
					<button type="button" ng-click="cerrarInsertParticipante()" style="float:right;font-size:21px;font-weight:700;line-height:1;color:#000;text-shadow:0 1px 0 #fff;filter:alpha(opacity=20);opacity:.2"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="modalTitulo">{{titulo_participante_modal}}</h4>
                    </div>
                    <div class="modal-body">
                        <form>
                         <div class="form-group" ng-show="visible_check">
                         <div class="checkbox-inline" style="margin-top: 10px;">
                            <label style="vertical-align: middle; margin-bottom: 10px; ">
                              <input  id="tiene_cliente" type="checkbox" ng-model="formDataParticipante.tiene_cliente" class="checkbox"  value="true"  > Pertenece a un cliente
                            </label>
                            <select ng-model="formDataParticipante.select_cliente" ng-options="cliente.NOMBRE for cliente in clientes track by cliente.ID"
                                     class="form-control" id="tiene_cliente" name="tiene_cliente"  required
                                    ng-show="formDataParticipante.tiene_cliente == true" >
                                    <option value="">---Seleccione un Estado---</option>
                            </select>
                             <span class="text-danger" >{{error_tiene_cliente}}</span>
                         </div>
                         </div>
                        <div class="form-group">
								<label for="nombre_participante">Nombre<span class="required">*</span></label>
								<div>
									<input type="text" class="form-control" id="nombre_participante" name="nombre_participante" ng-model="formDataParticipante.nombre_participante"  required
                                        ng-disabled="nombredisabled"   ng-change="error_nombre_participante = (formDataParticipante.nombre_participante?'':'Complete este campo')"     ng-class="{ error: exampleForm.nombre_participante.$error.required && !exampleForm.$pristine}" >
									<span class="text-danger" >{{error_nombre_participante}}</span>
								</div>
						</div>
                        <div class="form-group">
								<label for="email_participante">Correo Electrónico<span class="required">*</span></label>
								<div>
									<input type="text" class="form-control" id="email_participante" name="email_participante" ng-model="formDataParticipante.email_participante"  required
                                           ng-change="error_email_participante = (validar_email(formDataParticipante.email_participante)?'':'Correo electrónico Inválido')"   ng-class="{ error: exampleForm.email_participante.$error.required && !exampleForm.$pristine}" >
									<span  class="text-danger" >{{error_email_participante}}</span>
								</div>
						</div>
                         <div class="form-group">
								<label for="telefono_participante">Telefono<span class="required">*</span></label>
								<div>
									<input type="text" class="form-control" id="telefono_participante" name="telefono_participante" ng-model="formDataParticipante.telefono_participante"  required
                                           ng-change="error_telefono_participante = (validar_telefono(formDataParticipante.telefono_participante)?'':'Teléfono Inválido')"   ng-class="{ error: exampleForm.telefono_participante.$error.required && !exampleForm.$pristine}" >
									<span  class="text-danger" >{{error_telefono_participante}}</span>
								</div>
						</div>
                         <div class="form-group">
								<label for="curp_participante">CURP<span class="required">*</span></label>
								<div>
									<input type="text" class="form-control" id="curp_participante" name="curp_participante" ng-model="formDataParticipante.curp_participante"  required
                                           ng-change="error_curp_participante = (curpValida(formDataParticipante.curp_participante)?'':'CURP Inválido')"   ng-class="{ error: exampleForm.curp_participante.$error.required && !exampleForm.$pristine}" >
									<span  class="text-danger" >{{error_curp_participante}}</span>
								</div>
						</div>
                        <div class="form-group">
								<label for="perfil_participante">Perfil<span class="required">*</span></label>
								<div>
									<input type="text" class="form-control" id="perfil_participante" name="perfil_participante" ng-model="formDataParticipante.perfil_participante"  required
                                           ng-change="error_perfil_participante = (formDataParticipante.perfil_participante?'':'Complete este campo')"  ng-class="{ error: exampleForm.perfil_participante.$error.required && !exampleForm.$pristine}" >
									<span  class="text-danger" >{{error_perfil_participante}}</span>
								</div>
						</div>
                        <div class="form-group">
                                <label for="estado_participante">Estado<span class="required">*</span></label>
                                <select ng-model="formDataParticipante.estado_participante" ng-options="estado.ENTIDAD_FEDERATIVA as estado.ENTIDAD_FEDERATIVA for estado in estados"
                                        ng-change="error_estado_participante = (formDataParticipante.estado_participante?'':'Complete este campo')" class="form-control" id="estado_participante" name="estado_participante"  required
                                        ng-class="{ error: exampleForm.estado_participante.$error.required && !exampleForm.$pristine}" >
                                    <option value="">---Seleccione un Estado---</option>
                                </select>
                            <span class="text-danger">{{error_estado_participante}}</span>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" ng-click="cerrarInsertParticipante()">Cancelar</button>
                        <button ng-if="accion_p == 'editar'" type="button" class="btn btn-primary" ng-click="submitParticipante('editar')" id="btnGuardarParticipante">Guardar</button>
                        <button ng-if="accion_p != 'editar'"type="button" class="btn btn-primary" ng-click="submitParticipante('insertar')" id="btnGuardarParticipante">Guardar</button>


                    </div>
                </div>
                </div>

        </div>
  </div>


<?php } ?>
</div>
</span>




