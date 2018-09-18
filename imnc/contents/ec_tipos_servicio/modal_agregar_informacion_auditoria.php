<!-- Modal insertar/actualizar-->
  <div class="modal fade" id="modalAgregarInformacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-backdrop="static" data-keyboard="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="modalTitulo">{{modal_titulo}}</h4>
                    </div>
                    <div class="modal-body"> 
                        <form name="exampleForm" >
                            <div class='form-group' ng-repeat="x in MetaDatos">
                                <label>{{x.NOMBRE}}<span class="required">*</span></label>
                                <input type="text" class="form-control"  id="formData.input{{x.ID}}" ng-model="formData.input[x.ID]" required
                                ng-class="{ error: exampleForm.x.ID.$error.required && !exampleForm.$pristine}" ng-if="x.TIPO==0"  ng-change='MD{{x.ID}}(formData.input[x.ID])'>
								<textarea  type='text' class="form-control" ng-model="formData.input[x.ID]" cols='50' rows='4' required='required' ng-class="{ error: exampleForm.x.ID.$error.required && !exampleForm.$pristine}" ng-if="x.TIPO==1" ></textarea>
								<select ng-model="formData.input[x.ID]" ng-options="y.ID as y.OPCION for y in Ys[x.ID]"  class="form-control" ng-change='ys()' required ng-class="{ error: exampleForm.y.$error.required && !exampleForm.$pristine}" ng-if="x.TIPO==2" ></select>	
								<div class="form-group" ng-show="formData.input[x.ID] > 0 && x.ID==11 " ng-repeat="z in DatosTurnos" >
									<label>Turno {{$index + 1}}<span class="required">*</span></label>
										<select ng-model="formData.Turno[$index]" ng-options="y.ID as y.OPCION for y in Ys[x.ID]"  class="form-control" required ng-class="{ error: exampleForm.y.$error.required && !exampleForm.$pristine}" ></select>
									<label>Personal por turno {{$index + 1}}<span class="required">*</span></label>
									<input type="text" class="form-control"   ng-model="formData.Pers_Turno[$index]" required
									ng-class="{ error: exampleForm.x.ID.$error.required && !exampleForm.$pristine}" ng-if="x.TIPO==0"  >
								</div>	
								<div class="form-group" ng-if="formData.input[x.ID] == 8 && x.ID == 12">
										<label> Especificar tipo de soluci&oacuten <span class="required">*</span></label>
										<textarea  type='text' class="form-control" ng-model="formData.TipoSolucion" cols='50' rows='4' required='required' ng-class="{ error: exampleForm.x.ID.$error.required && !exampleForm.$pristine}" ></textarea>
								</div>
								<div class="form-group" ng-if="formData.input[x.ID] > 0 && x.ID == 35">
										<label> Se&ntildeale los tipos de discapacidad que presentan <span class="required">*</span></label>
										<textarea  type='text' class="form-control" ng-model="formData.TipoDiscapacidad" cols='50' rows='4' required='required' ng-class="{ error: exampleForm.x.ID.$error.required && !exampleForm.$pristine}" ></textarea>
								</div>
								<div	class="form-group" ng-model="formData.input[x.ID]" ng-if="x.ID == 38">
									<div class="col-md-6" >
										<label> A&ntildeo <span class="required">*</span></label>
										<select ng-model="formData.Ano"  class="form-control" required ng-class="{ error: exampleForm.y.$error.required && !exampleForm.$pristine}"  >
											<option value="0">0 a&ntildeo </option>
											<option value="1">1 a&ntildeo </option>
											<option value="2">2 a&ntildeos </option>
											<option value="3">3 a&ntildeos </option>
											<option value="4">4 a&ntildeos </option>
											<option value="5">5 a&ntildeos </option>
											<option value="6">6 a&ntildeos </option>
											<option value="7">7 a&ntildeos </option>
											<option value="8">8 a&ntildeos </option>
											<option value="9">9 a&ntildeos </option>
											<option value="10">10 a&ntildeos </option>
											<option value="11">11 a&ntildeos </option>
										</select>
									</div>
									<div class="col-md-6">
										<label> Mes <span class="required">*</span></label>
										<select ng-model="formData.Mes"  class="form-control" required ng-class="{ error: exampleForm.y.$error.required && !exampleForm.$pristine}"  >
											<option value="0">0 mes </option>
											<option value="1">1 mes </option>
											<option value="2">2 meses </option>
											<option value="3">3 meses </option>
											<option value="4">4 meses </option>
											<option value="5">5 meses </option>
											<option value="6">6 meses </option>
											<option value="7">7 meses </option>
											<option value="8">8 meses </option>
											<option value="9">9 meses </option>
											<option value="10">10 meses </option>
											<option value="11">11 meses </option>
										</select>
									</div>
								</div>
                            </div>
							
                            <input type="submit" class="btn btn-success pull-right mt-2" ng-click="submitForm(formData)" ng-disabled="!exampleForm.$valid" value="Guardar"/>
                        </form>
                    </div>                                  
                    <div class="modal-footer">
                       
                    </div>
                </div>
            </div>
        </div>
