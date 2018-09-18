<?php 
  $id_cliente = $_REQUEST["id"];
?>
	<link rel="stylesheet" type="text/css" href="css/jquery-ui-themes/jquery-ui.min.css">
	<link rel="stylesheet" type="text/css" href="css/jquery-ui-themes/timepicker.css">
	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script src="js/ngFileUpload/ng-file-upload-shim.min.js"></script>
	<script src="js/ngFileUpload/ng-file-upload.min.js"></script>
	<script type="text/javascript" src="js/datepicker/timepicker.js"></script>
<style type="text/css">
    caption{
      text-align: left;
      color: black;
      font-size: 15px;
    }

    .accordion-contactos {
        border-left: 1px solid rgba(158, 124, 68, 0.48);
        border-bottom: 1px solid rgba(158, 124, 68, 0.48);
        border-right: 1px solid rgba(158, 124, 68, 0.48);
        margin-bottom: 25px;
    }

    address {
      margin-bottom: 0px;
    }

    span.domicilio{
      font-size: 18px;
    }

</style>

<div class="right_col" role="main">

  <div class="">

   
    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Perfil del cliente</h2>
            
            <div class="clearfix"></div>
          </div>
          <div class="x_content">

            <div class="col-md-3 col-sm-3 col-xs-12 profile_left">

              <div class="profile_img">

                <!-- end of image cropping -->
                <div id="crop-avatar">
                  <!-- Current avatar -->
                  <div class="avatar-view" title="Change the avatar">
                    <img src="../pictures/user.png" alt="Avatar" id="imgCliente">
                  </div>

                  <!-- Loading state -->
                  <div class="loading" aria-label="Loading" role="img" tabindex="-1"></div>
                </div>
                <!-- end of image cropping -->

              </div>
              <h3 id="lbNombre">cargando...</h3>

              <ul class="list-unstyled user_data">

                <li id="lbRfc">
                  cargando...
                </li>

                <li id="lbEsFac">
                  cargando...
                </li>

                <li id="lbTieneFac">
                  cargando...
                </li>

              </ul>

              <br/>

            </div>
            <div class="col-md-9 col-sm-9 col-xs-12">

 

              <div class="" role="tabpanel" data-example-id="togglable-tabs">
                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                  <li role="presentation" class="active"><a href="#tab_domicilios" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Domicilios</a>
                  </li>
				  <li role="presentation" class=""><a href="#tab_calendario" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">
                          Calendario</a>
								

								</li>
                </ul>
                <div id="myTabContent" class="tab-content">
                  <div role="tabpanel" class="tab-pane fade active in" id="tab_domicilios" aria-labelledby="profile-tab">
                    <div>
                    <?php
                        if ($modulo_permisos["CLIENTES"]["registrar"] == 1) {
                            echo '<button type="button" id="btnNuevoDomicilio" class="btn btn-primary btn-xs btn-imnc" style="float: right;"> ';
                            echo '  <i class="fa fa-plus"> </i> Agregar domicilio ';
                            echo '</button>';
                        } 
                      ?>
                      
                    </div>
                    <div class="col-sm-10 invoice-col" id="bodyDomicilios">
                    
                    </div>

                  </div>
				  <div role="tabpanel" class="tab-pane fade" id="tab_calendario" aria-labelledby="profile-tab">
									<!-- ///////////////////////// CALENDARIO //////////////////////////////-->
									<script type="text/javascript" src="controllers/cita_calendario.js"></script>
									<div ng-controller="cita_calendario_controller">
										<div id="calendario" class="cal1"></div>


									<!-- MODAL CREATE-->
										<div class="modal fade" id="modalCreateEvento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
											<div class="modal-dialog" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
														<h4 class="modal-title" id="modalTitulo">Nuevo Evento</h4>
													</div>
													<div class="modal-body">
														<form name="form2" id="form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" enctype="multipart/form-data">
															<div class="form-group">
																<label class="control-label col-md-4 col-sm-4 col-xs-12">Asunto
																<span class="required">*</span></label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																	<input type="text" name="asunto" id="asunto" class="form-control col-md-7 col-xs-12" ng-model="form.asunto" data-parsley-id="2324">
																	<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
																	<span id="asuntoerror" class="text-danger"></span>
																</div>
															</div>

															<div class="form-group">
																<label class="control-label col-md-4 col-sm-4 col-xs-12">Folio</label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																<select ng-model="form.id_cotizacion" required="required" 
																	class="form-control col-md-7 col-xs-12" data-parsley-id="2324" ng-options="fol.id_cotizacion as fol.folio for fol in Folios">
																		<option value="">---Seleccione un Asunto---</option>
																	</select>
																	
																	<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
																	<span id="folioerror" class="text-danger"></span>
																</div>
															</div>

															<div class="form-group">
																<label class="control-label col-md-4 col-sm-4 col-xs-12">Tipo Asunto
																<span class="required">*</span></label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																	<select ng-model="form.tipo_asunto" required="required" 
																	class="form-control col-md-7 col-xs-12" data-parsley-id="2324" ng-options="asu.id_tipo_asunto as asu.descripcion for asu in Asuntos">
																		<option value="">---Seleccione un Asunto---</option>
																	</select>
																	<span id="tipoasuntoerror" class="text-danger"></span>
																</div>
															</div>

															<div class="form-group" ng-if="Usuario.permisos == 'admin'">
																<label class="control-label col-md-4 col-sm-4 col-xs-12">Asignar Usuario
																<span class="required">*</span></label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																	<select ng-model="form.usuario_asignado" required="required" 
																	class="form-control col-md-7 col-xs-12" data-parsley-id="2324" 
																	ng-options="user.id_usuarios as user.nombre for user in Lista_Usuarios">
																		<option value="">---Seleccione un Usuario---</option>
																	</select>
																	<span id="usuarioasignadoerror" class="text-danger"></span>
																</div>
															</div>

															<div class="form-group">
																<label class="control-label col-md-4 col-sm-4 col-xs-12">Factibilidad
																<span class="required">*</span></label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																	<select ng-model="form.porcentaje" required="required" 
																	class="form-control col-md-7 col-xs-12" 
																	ng-change = "des_porcentaje = form.porcentaje.descripcion"
																	ng-options="per as per.porcentaje for per in Porcentajes track by per.id_porcentaje">
																		<option value="">---Seleccione un porcentaje---</option>
																	</select>
																	<span id="porcentajeerror" class="text-danger"></span>
																</div>
																<br />
																
															</div>
															<div class="form-group">
																<label class="control-label col-md-4 col-sm-4 col-xs-12">Descripcion de Factabilidad</label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																	<input type="text" class="form-control col-md-7 col-xs-12" ng-model="des_porcentaje" readonly>
																</div>
															</div>

															<div class="form-group">
																<label class="control-label col-md-4 col-sm-4 col-xs-12">Estado de Propuesta
																<span class="required">*</span></label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																	<select ng-model="form.propuesta_estado" required="required" 
																	class="form-control col-md-7 col-xs-12" data-parsley-id="2324" ng-options="est.id_estado as est.estado for est in PropuestasEstados">
																		<option value="">---Seleccione un estado---</option>
																	</select>
																	<span id="propuestaestadoerror" class="text-danger"></span>
																</div>
															</div>

															<div id="fechas">
																<div class="form-group">
																<label class="control-label col-md-4 col-sm-4 col-xs-12">Fecha Inicio: </label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																	<input type="text" readonly ng-model="form.fecha_inicio" 
																	class="form-control col-md-5 col-xs-10">
																</div>
																</div>
																<div class="form-group">
																<label class="control-label col-md-4 col-sm-4 col-xs-12">Fecha Fin: </label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																	<input type="text" readonly ng-model="form.fecha_fin" 
																	class="form-control col-md-5 col-xs-10">
																</div>
																</div>
															</div>


															<div class="form-group" id="FI">
																<label class="control-label col-md-4 col-sm-4 col-xs-12">Fecha Inicio
																<span class="required">*</span></label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																	<input type="text" required="required" ng-model="form.fecha_inicio" class="form-control col-md-5 col-xs-10" id="fecha_inicio" data-parsley-id="2324">
																	<span id="fechainicioerror" class="text-danger"></span>
																</div>
															</div>

															<div class="form-group" id="HI">
																<label class="control-label col-md-4 col-sm-4 col-xs-12">Hora Inicio
																<span class="required">*</span></label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																	<input type="texttext" required="required" ng-model="form.hora_inicio" class="form-control col-md-5 col-xs-10" id="hora_inicio" data-parsley-id="2324">
																	<span id="horainicioerror" class="text-danger"></span>
																</div>
															</div>

															<div class="form-group" id="HF">
																<label class="control-label col-md-4 col-sm-4 col-xs-12">Hora Fin
																<span class="required">*</span></label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																	<input type="text" required="required" ng-model="form.hora_fin" class="form-control col-md-5 col-xs-10" id="hora_fin" data-parsley-id="2324">
																	<span id="horafinerror" class="text-danger"></span>
																</div>
															</div>


															<div class="form-group" hidden>
																<label class="control-label col-md-4 col-sm-4 col-xs-12">Recordatorio
																<span class="required">*</span></label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																	<input type="number" name="recordatorio" id="recordatorio" class="form-control col-md-7 col-xs-12" ng-model="form.recordatorio" data-parsley-id="2324">
																	<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
																	<span id="recordatorioerror" class="text-danger"></span>
																</div>
															</div>

															<div class="form-group">
																<label class="control-label col-md-4 col-sm-4 col-xs-12">Observaciones
																</label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																	<textarea rows="4" cols="50" name="observaciones" id="observaciones" class="form-control col-md-7 col-xs-12" ng-model="form.observaciones" data-parsley-id="2324">
																	</textarea>
																	<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
																	<span id="observacioneserror" class="text-danger"></span>
																</div>
															</div>

															<div class="form-group">
											                	<label class="control-label col-md-4 col-sm-4 col-xs-12">Archivo: </label>
											                    <div class="col-md-6 col-sm-6 col-xs-12">
											                      <div class="btn btn-primary" ngf-select="getFileName(form.FILE)" ng-model="form.FILE"
											                        ngf-accept="'.pdf'" >Agregar</div>
											                      <span id="archivo-error" class="text-danger" ></span>
											                    </div>
											                </div>

											                <div class="form-group" ng-repeat="C in form.CITA_ARCHIVOS">
											                    <div class="col-md-12 col-sm-12 col-xs-12">
											                    	<span> {{C.name}} </span>
											                    	<button type="button" class="close" ng-click="delFileName(C.name)">
											                    	<span aria-hidden="true">&times;</span></button>
											                    </div>
											                </div>

											                <div id="up_files" class="form-group" ng-if="accion=='editar'">
											                	<label class="col-md-12 col-sm-12 col-xs-12">Archivos Subidos: </label>
											                	<div  ng-repeat="a in form.archivos">
											                    <div class="col-md-12 col-sm-12 col-xs-12">
											                    	<a href="{{file_url}}?entidad=1&codigo={{a.id_encriptado}}" target="_blank">  {{a.nombre_archivo}} </a>
											                    </div>
											                    </div>
											                </div>

														</form>
													</div>
													<div class="modal-footer">
												
														<a type="button" class="btn btn-primary" id="hist-button"
														href="./?pagina=prospecto_cita_historial&id_cita={{form.id_calendario}}">Historial</a>
														<button type="button" class="btn btn-default" ng-click="cerrar()">Cerrar</button>
														<button type="button" class="btn btn-primary" ng-click="guardar()" id="btnGuardar">Guardar</button>
													</div>
												</div>
											</div>
										</div>
									
									
									<!-- /////////////////////////FIN CALENDARIO ////////////////////////////-->
								</div>
                </div>
				
							
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php 

  include "cliente_perfil/modal_insertar_actualizar_domicilio.php";
  include "cliente_perfil/modal_insertar_actualizar_contacto_domicilio.php";

?>



<script type="text/javascript">

<?php
  echo "var global_id_cliente = '" . $id_cliente . "'";
?>

</script>
<script type="text/javascript" src="js/notify.js"></script>
<script type="text/javascript">
	$( document ).ready( function () {
		$( 'a[data-toggle="tab"]' ).on( 'shown.bs.tab', function ( e ) {
			$( '#calendario' ).fullCalendar( 'render' );
		} );
		$( '#myTab a:first' ).tab( 'show' );
	} )
</script>
