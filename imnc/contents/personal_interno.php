<?php
/**
 * Created by PhpStorm.
 * User: bmyorth
 * Date: 27/02/2019
 * Time: 14:35
 */
?>
<span ng-controller="empleados_controller">
<div class="right_col" role="main">
        <div class="page-title">
            <div class="title_left">
                <h3>Personal Interno</h3>
            </div>
                <?php
                if ($modulo_permisos["EMPLEADOS"]["registrar"] == 1) {
                    echo '<div class="title_right">';
                    echo '  <button type="button" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc" style="float: right;" ng-click="openModalInsertUpdate()"> ';
                    echo '      <i class="fa fa-plus"> </i> Agregar empleado';
                    echo '  </button>';
                    echo '</div>';
                }
                ?>
        </div>

        <div class="clearfix"></div>

    <div class="x_panel">
        <div class="x_title">
            <h2>Filtros </h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content" >
            <form class="form-horizontal form-label-left">
              <div class="col-md-4">
                    <div class="form-group">
                        <label>No. de empleado</label>
                        <div class="input-group">
                            <input type="text" class="form-control input-filtro" id="txtNo">
                            <!-- insert this line -->
                        </div>
                    </div>
                    <div class="form-group">
                          <label>Edad</label>
                          <div class="input-group" >
                              <input type="text" class="form-control input-filtro text-right" id="txtEdad">
                               <span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>
                                <select id="txtEdadContains" class="form-control" style="font-size: 10px;">
                                    <option value="EXACTO" selected>Exacto</option>
                                    <option value="0">Menores</option>
                                    <option value="1">Mayores</option>
                                </select>
                          </div>
                    </div>
                    <div class="form-group">
                      <label>CURP</label>
                      <div class="input-group" style="width: 100%;">
                          <input type="text" class="form-control input-filtro" id="txtCurp">
                          <!-- insert this line -->
                          <span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>
                          <select id="txtCurpContains" class="form-control" style="font-size: 10px;">
                              <option value="" selected>Comienza con</option>
                              <option value="1">Contenido en</option>
                          </select>
                      </div>
                    </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                    <label>Nombre</label>
                    <div class="input-group" style="width: 100%;">
                        <input type="text" class="form-control input-filtro" id="txtNombre">
                        <!-- insert this line -->
                        <span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>
                        <select id="txtNombreContains" class="form-control" style="font-size: 10px;">
                            <option value="" selected>Comienza con</option>
                            <option value="1">Contenido en</option>
                        </select>
                    </div>
                </div>
                <div class="form-group" >
                      <label>Sexo</label>
                      <div class="input-group" style="width: 100%;">
                          <select id="cmbSexo" class="form-control">
                              <option value="TODOS" selected>Todos</option>
                              <option value="masculino">Masculino</option>
                              <option value="femenino">Femenino</option>
                          </select>
                      </div>
                </div>
                <div class="form-group">
                      <label>No. Seguro social</label>
                      <div class="input-group" style="width: 100%;">
                          <input type="text" class="form-control input-filtro" id="txtNoSS">
                          <!-- insert this line -->
                          <span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>
                          <select id="txtNoSSContains" class="form-control" style="font-size: 10px;">
                              <option value="" selected>Comienza con</option>
                              <option value="1">Contenido en</option>
                          </select>
                      </div>
                </div>
              </div>
              <div class="col-md-4">
                    <div class="form-group">
                        <label>Apellido paterno</label>
                        <div class="input-group" style="width: 100%;">
                            <input type="text" class="form-control input-filtro" id="txtAPaterno">
                            <!-- insert this line -->
                            <span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>
                            <select id="txtAPaternoContains" class="form-control" style="font-size: 10px;">
                                <option value="" selected>Comienza con</option>
                                <option value="1">Contenido en</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" >
                      <label>Estado civil</label>
                      <div class="input-group" style="width: 100%;">
                          <select id="cmbEstadoCivil" class="form-control">
                              <option value="TODOS" selected>Todos</option>
                              <option value="soltero">Soltero</option>
                              <option value="casado">Casado</option>
                              <option value="divorciado">Divorciado</option>
                              <option value="viudo">Viudo</option>
                          </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label>Alta/Baja </label>
                      <div class="input-group" style="width: 100%;">
                          <select id="cmbEstado" class="form-control">
                              <option value="TODOS" selected>Todos</option>
                              <option value="0">Alta</option>
                              <option value="1">Baja</option>
                          </select>
                      </div>
                    </div>
              </div>

            </form>
        </div>

        <div class="form-group">
            <div class="col-md-3 col-sm-3 col-xs-12 col-md-offset-9">
                <button type="button" class="btn btn-success" ng-click="cargaFichas()">Ver todos</button>
                <button type="button" class="btn btn-primary" ng-click="cargaFichasFiltradas()">Filtrar</button>
            </div>
        </div>
    </div>

    <div class="row" ng-show="total>0" style="margin-left: 20px;">
        <p>
          Cantidad empleados mostrados: {{ total }}
        </p>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_content">

                    <div class="row">

                        <div class="clearfix"></div>
                        <span ng-if="error_filtro == true">No se encontró ningun empleado para ese filtro</span>
                        <span  ng-repeat="(key, item) in fichas" style="font-size: 12px; line-height: 20px;">
                             <div class="col-md-4 col-sm-4 col-xs-12 animated fadeInDown">
                              <div class="well profile_view">
                                <div class="col-sm-12" style="min-height: 300px;">
                                  <div class="left col-xs-9">
                                    <h4>{{item.NOMBRE+' '+item.APELLIDO_PATERNO+' '+item.APELLIDO_MATERNO}}</h4>
                                    <ul class="list-unstyled">
                                      <li><strong>No.: </strong> {{item.NO_EMPLEADO}}</li>
                                      <li><strong>Fecha. Nac: </strong> {{formatFecha(item.FECHA_NACIMIENTO)}}</li>
                                      <li><strong>Edad: </strong> {{calcular_edad(formatFecha(item.FECHA_NACIMIENTO))}} años</li>
                                      <li><strong>CURP: </strong> {{item.CURP}}</li>
                                      <li><strong>Sexo: </strong> {{item.SEXO | uppercase}}</li>
                                      <li><strong>Estado Civil: </strong> {{item.ESTADO_CIVIL | uppercase}}</li>
                                      <li><strong>No. Seguro Social: </strong> {{item.NO_SEGURO_SOCIAL}}</li>
                                      <li><strong>Teléfono: </strong> {{item.TELEFONO}}</li>
                                      <li><strong>Email: </strong> {{item.EMAIL}}</li>
                                      <li><strong>Dirección: </strong> {{ item.DIRECCION }}</li>
                                    </ul>
                                  </div>
                                  <div class="right col-xs-3 text-center" style="padding: 0px;">
                                   <img ng-if="item.IMAGEN_BASE64 === null" src="./pictures/user.png" style="width: 95px; height: 95px; cursor: pointer;" alt="" class="img-circle img-responsive " ng-click="uploadImageShow(item.NO_EMPLEADO)">
                                   <img ng-if="item.IMAGEN_BASE64 !== null" src="{{item.IMAGEN_BASE64}}" style="width: 95px; height: 95px; cursor: pointer;" alt="" class="img-circle img-responsive " ng-click="uploadImageShow(item.NO_EMPLEADO)">
                                  </div>
                                </div>
                                <div class="col-xs-12 bottom text-center">
                                <div class="col-xs-12 col-sm-12 emphasis">
                                     <?php
                                     if ($modulo_permisos["EMPLEADOS"]["editar"] == 1) {
                                         ?>
                                        <div class="title_right">
                                                 <a href="" class="btn btn-primary btn-xs btn-imnc" style="float: right; font-size: 11px;" ng-click="openModalInsertUpdate(item)">
                                                      <i class="fa fa-edit"> </i> Editar
                                                  </a>
                                                  <a href="./?pagina=empleado_perfil&tab=ficha&id={{item.NO_EMPLEADO}}" class="btn btn-primary btn-xs btn-imnc " style="float: right; font-size: 11px;">
                                                      <i class="fa fa-list-alt"> </i> Ficha de empleado
                                                  </a>
                                                  <a href="./?pagina=empleado_perfil&tab=activos&id={{item.NO_EMPLEADO}}" class="btn btn-primary btn-xs btn-imnc " style="float: right; font-size: 11px;">
                                                      <i class="fa fa-bank"> </i> Activos fijos
                                                  </a>
                                        </div>
                                    <?php }
                                     ?>
                                  </div>
                                </div>
                              </div>
                            </div>
                      <!--Se carga on load-->
                        </span>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!--MODAL INSERTAR EDITAR-->
    <div class="modal fade"  id="modalInsertUpdate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         data-backdrop="static" data-keyboard="true">
            <div class="modal-dialog" role="document" id="modal-size" >
                <div class="modal-content">
                   <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="modalTitulo">{{modal_titulo}}</h4>
                    </div>
                    <div class="modal-body">
                         <form>
                            <div class="form-group">
								<label for="no">No.<span class="required">*</span></label>
								<div>
									<input type="text" class="form-control" id="no" name="no" ng-model="formData.no"
                                     ng-change="noerror = (formData.no?'':'No debe estar vacio')" required ng-disabled="accion=='editar'">
									<span class="text-danger" >{{noerror}}</span>
								</div>
							</div>
                            <div class="form-group">
								<label for="nombre">Nombre<span class="required">*</span></label>
								<div>
									<input type="text" class="form-control" id="nombre" name="nombre" ng-model="formData.nombre"
                                           ng-change="nombreerror = (formData.nombre?'':'No debe estar vacio')"  required >
									<span class="text-danger" >{{nombreerror}}</span>
								</div>
							</div>
                            <div class="form-group">
								<label for="apellidoP">Apellido paterno<span class="required">*</span></label>
								<div>
									<input type="text" class="form-control" id="apellidoP" name="apellidoP" ng-model="formData.apellidoP"
                                           ng-change="apellidoPerror = (formData.apellidoP?'':'No debe estar vacio')"  required >
									<span  class="text-danger" >{{apellidoPerror}}</span>
								</div>
							</div>
                             <div class="form-group">
								<label for="apellidoM">Apellido materno<span class="required">*</span></label>
								<div>
									<input type="text" class="form-control" id="apellidoM" name="apellidoM" ng-model="formData.apellidoM"
                                           ng-change="apellidoMerror = (formData.apellidoM?'':'No debe estar vacio')"  required >
									<span  class="text-danger" >{{apellidoMerror}}</span>
								</div>
							</div>
                            <div class="form-group">
								<label for="curp">CURP<span class="required">*</span></label>
								<div>
									<input type="text" class="form-control" id="curp" name="curp" ng-model="formData.curp"
                                           ng-change="onChangeCURP()"  required >
									<span  class="text-danger" >{{curperror}}</span>
								</div>
							</div>
                            <div class="form-group">
								<label for="fecha_nacimiento">Fecha nacimiento<span class="required">*</span></label>
								<div>
									<input type="text" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" ng-model="formData.fecha_nacimiento" placeholder="dia / mes / año"
                                           ng-change="fecha_nacimientoerror = (formData.fecha_nacimiento?'':'No debe estar vacio')"  required >
									<span  class="text-danger" >{{fecha_nacimientoerror}}</span>
								</div>
							</div>
                            <div class="form-group">
								<label for="cmbSexo">Sexo<span class="required">*</span></label>
								<div>
                                     <select class="form-control" id="cmbSexo" name="cmbSexo" class="form-control" ng-model="formData.cmbSexo"
                                      ng-change="cmbSexoerror = (formData.cmbSexo?'':'No debe estar vacio')" required>
                                          <option value="" selected ng-disabled="true">Seleccione un sexo</option>
                                          <option value="masculino">Masculino</option>
                                          <option value="femenino">Femenino</option>
                                     </select>
									<span  class="text-danger" >{{cmbSexoerror}}</span>
								</div>
							</div>
                            <div class="form-group">
								<label for="estado_civil">Estado civil<span class="required">*</span></label>
								<div>
                                     <select  class="form-control" id="estado_civil" name="estado_civil" class="form-control" ng-model="formData.estado_civil"
                                             ng-change="estado_civilerror = (formData.estado_civil?'':'No debe estar vacio')" required>
                                          <option value="" selected ng-disabled="true">Seleccione un estado civil</option>
                                          <option ng-if="formData.cmbSexo=='masculino'" value="soltero">Soltero</option>
                                          <option ng-if="formData.cmbSexo=='masculino'" value="casado">Casado</option>
                                          <option ng-if="formData.cmbSexo=='masculino'" value="divorciado">Divorciado</option>
                                          <option ng-if="formData.cmbSexo=='masculino'" value="viudo">Viudo</option>

                                          <option ng-if="formData.cmbSexo=='femenino'" value="soltero">Soltera</option>
                                          <option ng-if="formData.cmbSexo=='femenino'" value="casado">Casada</option>
                                          <option ng-if="formData.cmbSexo=='femenino'" value="divorciado">Divorciada</option>
                                          <option ng-if="formData.cmbSexo=='femenino'" value="viudo">Viuda</option>
                                     </select>
									<span  class="text-danger" >{{estado_civilerror}}</span>
								</div>
							</div>
                            <div class="form-group">
								<label for="no_seguridad">No. Seguridad social<span class="required">*</span></label>
								<div>
									<input type="text" class="form-control" id="no_seguridad" name="no_seguridad" ng-model="formData.no_seguridad" placeholder="00-00-00-0000-0"
                                           ng-change="no_seguridaderror = (validar_no_seguridad(formData.no_seguridad)?'':'No. de Seguridad Social inválido')" required >
									<span class="text-danger" >{{no_seguridaderror}}</span>
								</div>
							</div>
                            <div class="form-group">
								<label for="telefono">Teléfono<span class="required">*</span></label>
								<div>
									<input type="text" class="form-control" id="telefono" name="telefono" ng-model="formData.telefono"
                                           ng-change="telefonoerror = (validar_telefono(formData.telefono)?'':'Teléfono inválido')" required >
									<span class="text-danger" >{{telefonoerror}}</span>
								</div>
							</div>
                            <div class="form-group">
								<label for="email">Correo electrónico<span class="required">*</span></label>
								<div>
									<input type="text" class="form-control" id="email" name="email" ng-model="formData.email">
                                          <!-- ng-change="emailerror = (validar_email(formData.email)?'':'Correo electrónico inválido')" required >-->
									<span class="text-danger" >{{emailerror}}</span>
								</div>
							</div>
                            <div class="form-group">
								<label for="direccion">Dirección<span class="required">*</span></label>
								<div>
									<textarea rows="3"  class="form-control" id="direccion" name="direccion" ng-model="formData.direccion"
                                              ng-change="direccionerror = (formData.direccion?'':'No debe estar vacio')" required ></textarea>
									<span class="text-danger" >{{direccionerror}}</span>
								</div>
							</div>
                             <div class="form-group" ng-show="accion == 'editar'">
								<label for="estado">Estado<span class="required">*</span></label>
								<div>
                                     <select class="form-control" id="estado" name="estado" class="form-control" ng-model="formData.estado"
                                           ng-init="formData.estado = 0"  ng-change="estadoerror = (formData.estado?'':'No debe estar vacio')" required>
                                         <option value="" selected ng-disabled="true">Seleccione un estado</option>
                                         <option value="0" selected>Alta</option>
                                         <option value="1">Baja</option>
                                     </select>
									<span  class="text-danger" >{{estadoerror}}</span>
								</div>
							</div>

                         </form>
                    </div>
                    <div class="modal-footer">
                 <button type="button" class="btn btn-default btn-sm pull-left"  ng-click="eliminar()">Eliminar</button>
                 <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cerrar</button>
                 <button type="button" class="btn btn-primary btn-sm"  ng-click="submitForm(formData)">Guardar</button>
            </div>
                </div>

                </div>

    </div>


        <!-- Modal insertar/actualizar-->
    <div class="modal fade" id="modalSubirImagen" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Subir imagen</h4>
          </div>
          <div class="modal-body">
              <div class="ajax-file-upload" style="position: relative; overflow: hidden; cursor: default;">
                Upload
                <form id="singleupload" method="POST"  enctype="multipart/form-data" style="margin: 0px; padding: 0px;">
                    <input type="file" id="ajax-upload-id-1460599196294" name="myfile" name="myfile" onchange="angular.element(this).scope().uploadFile(this.files)" accept="*" style="position: absolute; cursor: pointer; top: 0px; width: 100%; height: 100%; left: 0px; z-index: 100; opacity: 0;">
                </form>
              </div>
              <!--es necesario este div-->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
</span>


