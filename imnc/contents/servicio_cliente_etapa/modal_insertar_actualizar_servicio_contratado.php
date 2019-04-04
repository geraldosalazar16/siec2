<!-- Modal insertar/actualizar-->
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
                                <label for="txtReferencia">Referencia<span class="required">*</span></label>
                                <input type="text" class="form-control" name="txtReferencia" id="txtReferencia" ng-model="formData.txtReferencia" required
                                ng-class="{ error: exampleForm.txtReferencia.$error.required && !exampleForm.$pristine}" ng-disabled="formData.txtReferencia || accion=='editar'">
                            </div>
                            <div class="form-group">
                                <label for="claveCliente">Cliente<span class="required">*</span></label>
                                <select ng-model="formData.claveCliente" ng-options="claveCliente.ID as claveCliente.NOMBRE for claveCliente in claveClientes" 
                                class="form-control" id="claveCliente" name="claveCliente" ng-change='cambioclaveCliente()' required
                                ng-class="{ error: exampleForm.claveCliente.$error.required && !exampleForm.$pristine}" ng-disabled="accion=='editar'"></select>
                            </div>
                            <div class="form-group">
                                <label for="claveServicio">Servicio<span class="required">*</span></label>
                                <select ng-model="formData.claveServicio" ng-options="claveServicio.ID as claveServicio.NOMBRE for claveServicio in claveServicios" 
                                class="form-control" id="claveServicio" name="claveServicio" ng-change='cambioclaveServicio(formData.claveServicio)' required
                                ng-class="{ error: exampleForm.claveServicio.$error.required && !exampleForm.$pristine}" ng-disabled="accion=='editar'"></select>
                            </div>
                            <div class="form-group">
                                <label id="txtsel_tipoServicio" for="sel_tipoServicio">Tipo de Servicio para generar Referencia<span class="required">*</span></label>
                                <select ng-model="formData.sel_tipoServicio" ng-options="sel_tipoServicio.ID as sel_tipoServicio.NOMBRE for sel_tipoServicio in sel_tipoServicios" 
                                class="form-control" id="sel_tipoServicio" name="sel_tipoServicio" ng-change='cambiosel_tipoServicio(formData.sel_tipoServicio)'
                                ng-class="{ error: exampleForm.sel_tipoServicio.$error.required && !exampleForm.$pristine}" ></select>
                            </div>
                            <!-- Deshabilitar la regla de bloqueo por inconsistencia de datos
							              <div class="form-group">
                                <label id="txtsel_tipoServicio" for="sel_tipoServicio">Tipo de Servicio para generar Referencia<span class="required">*</span></label>
                                <select ng-model="formData.sel_tipoServicio" ng-options="sel_tipoServicio.ID as sel_tipoServicio.NOMBRE for sel_tipoServicio in sel_tipoServicios" 
                                class="form-control" id="sel_tipoServicio" name="sel_tipoServicio" ng-change='cambiosel_tipoServicio(formData.sel_tipoServicio)' required
                                ng-class="{ error: exampleForm.sel_tipoServicio.$error.required && !exampleForm.$pristine}" ng-disabled="!formData.claveServicio || accion=='editar'" ></select>
                            </div>
                            -->
                            <div class="form-group" id="divCursos" hidden>
                                <label  for="sel_Cursos">Cursos<span class="required">*</span></label>
                                <select ng-model="formData.sel_Cursos" ng-options="sel_Curso.ID_CURSO as sel_Curso.NOMBRE for sel_Curso in sel_Cursos"
                                        class="form-control" id="sel_Cursos" name="sel_Cursos"
                                        ng-class="{ error: exampleForm.sel_Cursos.$error.required && !exampleForm.$pristine}" ng-disabled="!formData.claveServicio " ></select>
                            </div>
                            <div class="form-group" ng-show="formData.claveServicio==3">
                                <label  for="cantidad_participantes">Cantidad de Participantes<span class="required">*</span></label>
                                <input type="text" ng-model="formData.cantidad_participantes" class="form-control" id="cantidad_participantes" name="cantidad_participantes"
                                       ng-class="{ error: exampleForm.cantidad_participantes.$error.required && !exampleForm.$pristine}" ng-disabled="!formData.claveServicio " >
                            </div>
							<div class="form-group" id="divNorma">
                                <label for="Normas">Norma<span class="required">*</span></label>
                                 <multiple-autocomplete ng-model="formData.Normas" id="Normas"
                                 object-property="ID_NORMA"
                                 suggestions-arr="Normas"
                                 ng-class="{ error: exampleForm.Normas.$error.required && !exampleForm.$pristine}"
                                 ng-disabled="!formData.sel_tipoServicio || accion=='editar'" >
                                 </multiple-autocomplete>

                            </div>
							 <div class="form-group">
                                <label for="etapa">Etapa<span class="required">*</span></label>
                                <select ng-model="formData.etapa" ng-options="etapa.ID_ETAPA as etapa.ETAPA for etapa in Etapas" 
                                class="form-control" id="etapa" name="etapa" ng-change='cambioEtapa()' required
                                ng-class="{ error: exampleForm.etapa.$error.required && !exampleForm.$pristine}" ng-if="accion == 'editar'" ng-disabled="!formData.claveServicio"></select>
								<select ng-model="formData.etapa" ng-options="etapa.ID_ETAPA as etapa.ETAPA for etapa in Etapas1" 
                                class="form-control" id="etapa" name="etapa" ng-change='cambioEtapa()' required
                                ng-class="{ error: exampleForm.etapa.$error.required && !exampleForm.$pristine}" ng-if="accion == 'insertar' && formData.claveServicio==1" ng-disabled="!formData.claveServicio"></select>
								<select ng-model="formData.etapa" ng-options="etapa.ID_ETAPA as etapa.ETAPA for etapa in Etapas2" 
                                class="form-control" id="etapa" name="etapa" ng-change='cambioEtapa()' required
                                ng-class="{ error: exampleForm.etapa.$error.required && !exampleForm.$pristine}" ng-if="accion == 'insertar' && formData.claveServicio==2" ng-disabled="!formData.claveServicio"></select>
                                 <select ng-model="formData.etapa" ng-options="etapa.ID_ETAPA as etapa.ETAPA for etapa in Etapas"
                                         class="form-control" id="etapa" name="etapa" ng-change='cambioEtapa()' required
                                         ng-class="{ error: exampleForm.etapa.$error.required && !exampleForm.$pristine}" ng-if="accion == 'insertar' && formData.claveServicio==3" ng-disabled="!formData.claveServicio || accion == 'insertar'"></select>
								<select ng-model="formData.etapa" ng-options="etapa.ID_ETAPA as etapa.ETAPA for etapa in Etapas4" 
                                class="form-control" id="etapa" name="etapa" ng-change='cambioEtapa()' required
                                ng-class="{ error: exampleForm.etapa.$error.required && !exampleForm.$pristine}" ng-if="accion == 'insertar' && formData.claveServicio==4" ng-disabled="!formData.claveServicio"></select>		 
                            </div>
							     <!-- Esta opción es solo para Unidad de verificación de información comercial -->
							<div class="form-group" ng-show="formData.sel_tipoServicio == 18">
								<label for="Dict_const">Dictamen o Constancia<span class="required">*</span></label>
									<select ng-model="formData.DICTAMEN_CONSTANCIA" class="form-control" id="DICTAMEN_CONSTANCIA" name="DICTAMEN_CONSTANCIA" ng-change="cambio_dictamen_constancia(formData.DICTAMEN_CONSTANCIA)" 
									ng-class="{ error: exampleForm.DICTAMEN_CONSTANCIA.$error.required && !exampleForm.$pristine}" 
									ng-disabled="accion=='editar'">
										<option value="" selected disabled>-- selecciona  --</option>
										<option value="Dictamen">Dictamen</option>
										<option value="Constancia" selected>Constancia</option>
									</select>
								
							</div>
							<div class="form-group" ng-show="accion=='editar' && formData.claveServicio!=3">
                                <label for="cambio">¿Hay Cambio?</label>
                                <select ng-model="formData.cambio"  
                                class="form-control" id="cambio" name="cambio" ng-disabled='formData.cambio=="S"' 
                                ng-class="{ error: exampleForm.cambio.$error.required && !exampleForm.$pristine}" >
									<option value="S">Si</option>
									<option value="N">No</option>
								</select>
                            </div>
							<!--
							<div class="form-group" ng-show="accion=='editar' && formData.cambio=='S'" ng-repeat="y in Cambios">
								<label >
									<input type="checkbox" checklist-model="formData.chk.ID" checklist-value="y.ID"> {{y.NOMBRE}}
								</label>
								<div ng-if="formData.chk.ID == y.ID">
									<label class='control-label col-md-12' >Descripción del Cambio {{y.CAMBIO}}<span class='required'>*</span></label>
									<div class='col-md-12' >
										<textarea rows='4' ng-model="formData.descripcion[$index]" cols='50' type='text' required='required' class="'form-control col-md-7 col-xs-12' " ></textarea> 
									</div>	
								</div>
							</div>	-->
						  	<div class="form-group" ng-show="accion=='editar' && formData.cambio=='S' && formData.claveServicio!=3" ng-repeat="y in Cambios" >
								
                              <label >
									<input type='checkbox' id="formData.chk{{y.ID}}" ng-model="formData.chk[y.ID]"  ng-disabled="formData.chk[y.ID]"/>
										{{y.NOMBRE}}
								</label>
								<div ng-if="formData.chk[y.ID]">
									<label class='control-label col-md-12' id_cambio="y.ID">Descripción del Cambio {{y.CAMBIO}}<span class='required'>*</span></label>
									<div class='col-md-12' >
										<textarea rows='4' ng-model="formData.descripcion[y.ID]" cols='50' type='text' required='required' class="'form-control col-md-7 col-xs-12' " ></textarea> 
									</div>	
								</div>
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

