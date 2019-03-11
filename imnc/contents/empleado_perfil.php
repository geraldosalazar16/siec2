<span ng-controller="empleado_perfil_controller">
<div class="right_col" role="main">

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Perfil del empleado</h2>
            
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <?php if ($modulo_permisos["EMPLEADOS"]["ver"] == 1) {?>
            <div class="col-md-3 col-sm-3 col-xs-12 profile_left">

              <div class="profile_img">

                <!-- end of image cropping -->
                <div id="crop-avatar">
                  <!-- Current avatar -->
                  <div class="avatar-view" title="Change the avatar">
                    <img ng-if="empleado.IMAGEN_BASE64 === null" src="./pictures/user.png" alt="Avatar" >
                    <img ng-if="empleado.IMAGEN_BASE64 !== null" src="{{empleado.IMAGEN_BASE64}}" alt="Avatar" >
                  </div>
                  <!-- Loading state -->
                  <div class="loading" aria-label="Loading" role="img" tabindex="-1"></div>
                </div>
                <!-- end of image cropping -->

              </div>
              <h3  style="line-height: 30px;">{{empleado.NOMBRE+' '+empleado.APELLIDO_PATERNO+' '+empleado.APELLIDO_MATERNO}}</h3>

              <ul class="list-unstyled user_data">
                  <li><strong>No.: </strong> {{empleado.NO_EMPLEADO}}</li>
                  <li><strong>Fecha. Nac: </strong> {{empleado.FECHA_NACIMIENTO}}</li>
                  <li><strong>Edad: </strong> {{calcular_edad(empleado.FECHA_NACIMIENTO)}} años</li>
                  <li><strong>CURP: </strong> {{empleado.CURP}}</li>
                  <li><strong>Sexo: </strong> {{empleado.SEXO | uppercase}}</li>
                  <li><strong>Estado Civil: </strong> {{empleado.ESTADO_CIVIL | uppercase}}</li>
                  <li><strong>No. Seguro Social: </strong> {{empleado.NO_SEGURO_SOCIAL}}</li>
                  <li><strong>Teléfono: </strong> {{empleado.TELEFONO}}</li>
                  <li><strong>Email: </strong> {{empleado.EMAIL}}</li>
                  <li><strong>Dirección: </strong> {{ empleado.DIRECCION }}</li>
              </ul>

              <br/>

            </div>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <div class="" role="tabpanel" data-example-id="togglable-tabs">
                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                  <li role="presentation" class="active"><a href="#tab_ficha" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Ficha</a>
                  </li>
                  <li role="presentation" class=""><a href="#tab_activos_fijos" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Activos Fijos</a>
                  </li>
                </ul>
                <div id="myTabContent" class="tab-content">
                  <!-- Tab fichas -->
                  <div role="tabpanel" class="tab-pane fade active in" id="tab_ficha" aria-labelledby="profile-tab">
                    <div class="x_title">
                                        <p><h2>Datos</h2></p>
                                         <?php
                                        if ($modulo_permisos["EMPLEADOS"]["registrar"] == 1) {?>
                                         <button type="submit"  class="btn btn-primary pull-right" ng-click="submitFormFicha(formDataFicha)" ng-if="flag==true">Guardar</button>
                                        <button type="submit"  class="btn btn-primary pull-right" ng-click="showFormConfiguracion()" ng-if="flag==false">Editar</button>
                                        <?php }  ?>
										<div class="clearfix"></div>
                    </div>
                    <div class="col-sm-10 invoice-col" >
                       <div  style="margin-left: 30px; margin-top: 30px;">
                         <form class="form-horizontal" role="form">
                            <div class="form-group">
								<label for="antiguedad">Antigüedad<span class="required">*</span></label>
								<div>
									<input type="text" class="form-control" id="antiguedad" name="antiguedad" ng-model="formDataFicha.antiguedad"
                                           ng-change="antiguedad_error = (validar_numeros('antiguedad')?'':'No debe estar vacio')" required ng-show="flag==true" >
									<span class="text-danger" >{{antiguedad_error}}</span>
                                    <label  class="control-label" style="color: #1b1613;text-align: left;" ng-show="flag==false">{{ (ficha.ANTIGUEDAD?ficha.ANTIGUEDAD:0)}} años</label>
								</div>
							</div>
                            <div class="form-group">
								<label for="seguro">Seguro de gastos médicos mayores<span class="required">*</span></label>
								<div>
									<input type="text" class="form-control" id="seguro" name="seguro" ng-model="formDataFicha.seguro"
                                           ng-change="seguro_error = (formDataFicha.seguro?'':'No debe estar vacio')" required ng-show="flag==true" >
									<span class="text-danger" >{{seguro_error}}</span>
                                    <label  class="control-label" style="color: #1b1613;text-align: left;" ng-show="flag==false">{{ficha.SEGURO_GASTOS_MEDICOS}}</label>
								</div>
							</div>
                             <div class="form-group">
								<label for="vacaciones">Días de Vacaciones<span class="required">*</span></label>
								<div>
									<input type="text" class="form-control" id="vacaciones" name="vacaciones" ng-model="formDataFicha.vacaciones"
                                           ng-change="vacaciones_error = (validar_numeros('vacaciones')?'':'No debe estar vacio')" required ng-show="flag==true" >
									<span class="text-danger" >{{vacaciones_error}}</span>
                                    <label  class="control-label" style="color: #1b1613;text-align: left;" ng-show="flag==false">{{ficha.DIAS_VACACIONES}} {{(ficha.DIAS_VACACIONES==1?' día':' días')}}</label>
								</div>
							</div>
                            <div class="form-group">
								<label for="prestamo_caja">Prestamos Caja de Ahorro<span class="required">*</span></label>
								<div>
									<input type="text" class="form-control" id="prestamo_caja" name="prestamo_caja" ng-model="formDataFicha.prestamo_caja"
                                           ng-change="prestamo_caja_error = (validar_numeros('prestamo_caja')?'':'No debe estar vacio')" required ng-show="flag==true" >
									<span class="text-danger" >{{prestamo_caja_error}}</span>
                                    <label  class="control-label" style="color: #1b1613;text-align: left;" ng-show="flag==false">{{ficha.PRESTAMOS_CAJA}} pesos</label>
								</div>
							</div>
                            <div class="form-group">
								<label for="prestamo_imnc">Prestamos IMNC<span class="required">*</span></label>
								<div>
									<input type="text" class="form-control" id="prestamo_imnc" name="prestamo_imnc" ng-model="formDataFicha.prestamo_imnc"
                                           ng-change="prestamo_imnc_error = (validar_numeros('prestamo_imnc')?'':'No debe estar vacio')" required ng-show="flag==true" >
									<span class="text-danger" >{{prestamo_imnc_error}}</span>
                                    <label  class="control-label" style="color: #1b1613;text-align: left;" ng-show="flag==false">{{ficha.PRESTAMOS_IMNC}} pesos</label>
								</div>
							</div>
                    </div>
                    </div>


                  </div>
                  <!-- Tab activos fijos -->
                  <div role="tabpanel" class="tab-pane fade" id="tab_activos_fijos" aria-labelledby="profile-tab">
                      <div class="x_title">
                          <h2>Mobiliario</h2>
                      <?php
                      if ($modulo_permisos["EMPLEADOS"]["registrar"] == 1) {?>
                          <button type="submit"  class="btn btn-primary pull-right" ng-click="openModalInsertUpdateM()" ng-if="flag==false">Editar Mobiliario</button>
                      <?php }  ?>
                          <div class="clearfix"></div>
                    </div>
                         <table class="table table-striped responsive-utilities jambo_table bulk_action">
                          <thead>
                            <tr class="headings">
                              <th class="column-title">Escritorio</th>
                              <th class="column-title">Silla</th>
                              <th class="column-title">Telefono fijo</th>
                              <th class="column-title">Movil</th>
                              <th class="column-title"></th>
                            </tr>
                          </thead>
                          <tbody  style="font-size: 12px;" class="ng-scope even pointer" >
                          <tr>
                              <th class="column-title">{{activos.MOBILIARIO.ESCRITORIO}}</th>
                              <th class="column-title">{{activos.MOBILIARIO.SILLA}}</th>
                              <th class="column-title">{{activos.MOBILIARIO.TELEFONO_FIJO}}</th>
                              <th class="column-title">{{activos.MOBILIARIO.MOVIL}}</th>
                              <th class="column-title"></th>
                          </tr>
                          </tbody>
                        </table>

                         <div class="x_title">
                          <h2>Equipos</h2>
                      <?php
                      if ($modulo_permisos["EMPLEADOS"]["registrar"] == 1) {?>
                          <button type="submit"  class="btn btn-primary pull-right" ng-click="openModalInsertUpdateE()" ng-if="flag==false">Editar Equipos</button>
                      <?php }  ?>
                          <div class="clearfix"></div>
                    </div>
                         <table class="table table-striped responsive-utilities jambo_table bulk_action">
                          <thead>
                            <tr class="headings">
                              <th class="column-title">Computadora</th>
                              <th class="column-title">Modelo</th>
                              <th class="column-title">Software </th>
                              <th class="column-title">Licenciamiento</th>
                              <th class="column-title"></th>
                            </tr>
                          </thead>
                          <tbody  style="font-size: 12px;" class="ng-scope even pointer" >
                          <tr>
                              <th class="column-title">{{activos.EQUIPOS.COMPUTADORA}}</th>
                              <th class="column-title">{{activos.EQUIPOS.MODELO}}</th>
                              <th class="column-title">{{activos.EQUIPOS.SOFTWARE}}</th>
                              <th class="column-title">{{activos.EQUIPOS.LICENCIAMIENTO}}</th>
                              <th class="column-title"></th>
                          </tr>
                          </tbody>
                        </table>
                  </div>
                </div>
              </div>
            </div>
              <?php }else{ echo '<h5>No tiene permisos para acceder a estos datos</h5>';}?>
          </div>
        </div>
      </div>
    </div>
    <!--MODAL INSERTAR EDITAR MOBILIARIO-->
    <div class="modal fade"  id="modalInsertUpdateM" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         data-backdrop="static" data-keyboard="true">
            <div class="modal-dialog" role="document" id="modal-size" >
                <div class="modal-content">
                   <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="modalTitulo">{{modal_mobiliario}}</h4>
                    </div>
                    <div class="modal-body">
                         <form>
                            <div class="form-group">
								<label for="escritorio">Escritorio</label>
								<div>
									<input type="text" class="form-control" id="escritorio" name="escritorio" ng-model="formDataActivo.escritorio"
                                     required >
									<span class="text-danger" ></span>
								</div>
							</div>
                            <div class="form-group">
								<label for="silla">Silla</label>
								<div>
									<input type="text" class="form-control" id="silla" name="silla" ng-model="formDataActivo.silla"
                                           required >
									<span class="text-danger" ></span>
								</div>
							</div>
                            <div class="form-group">
								<label for="telefono">Teléfono Fijo </label>
								<div>
									<input type="text" class="form-control" id="telefono" name="telefono" ng-model="formDataActivo.telefono"
                                           required >
									<span class="text-danger" ></span>
								</div>
							</div>
                            <div class="form-group">
								<label for="movil">Movil </label>
								<div>
									<input type="text" class="form-control" id="movil" name="movil" ng-model="formDataActivo.movil"
                                           required >
									<span class="text-danger" ></span>
								</div>
							</div>

                         </form>
                    </div>
                     <div class="modal-footer">
                         <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cerrar</button>
                         <button type="button" class="btn btn-primary btn-sm"  ng-click="editarM(formDataActivo)">Guardar</button>
                     </div>
                </div>
            </div>
    </div>

    <!--MODAL INSERTAR EDITAR EQUIPOS-->
    <div class="modal fade"  id="modalInsertUpdateE" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         data-backdrop="static" data-keyboard="true">
            <div class="modal-dialog" role="document" id="modal-size" >
                <div class="modal-content">
                   <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="modalTitulo">{{modal_equipos}}</h4>
                    </div>
                    <div class="modal-body">
                         <form>
                            <div class="form-group">
								<label for="computadora">Computadora</label>
								<div>
									<input type="text" class="form-control" id="computadora" name="computadora" ng-model="formDataActivo.computadora"
                                           required >
									<span class="text-danger" ></span>
								</div>
							</div>
                            <div class="form-group">
								<label for="modelo">Modelo</label>
								<div>
									<input type="text" class="form-control" id="modelo" name="modelo" ng-model="formDataActivo.modelo"
                                           required >
									<span class="text-danger" ></span>
								</div>
							</div>
                            <div class="form-group">
								<label for="software">Software</label>
								<div>
									<input type="text" class="form-control" id="software" name="software" ng-model="formDataActivo.software"
                                           required >
									<span class="text-danger" ></span>
								</div>
							</div>
                            <div class="form-group">
								<label for="licenciamiento">Licenciamiento </label>
								<div>
									<input type="text" class="form-control" id="licenciamiento" name="licenciamiento" ng-model="formDataActivo.licenciamiento"
                                           required >
									<span class="text-danger" ></span>
								</div>
							</div>

                         </form>
                    </div>
                     <div class="modal-footer">
                         <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cerrar</button>
                         <button type="button" class="btn btn-primary btn-sm"  ng-click="editarE(formDataActivo)">Guardar</button>
                     </div>
                </div>
            </div>
    </div>
</div>

<script type="text/javascript">

  <?php
    echo "var tab_seleccionada = '".$_REQUEST["tab"]."';";
  ?>

</script>
</span>