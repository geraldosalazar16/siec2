<div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3><?php echo $str_personal_tecnico; ?></h3>
              <?php
              if ($modulo_permisos["AUDITORES"]["extraer"] == 1) {
                  echo '<div class="dropdown" style="margin-bottom: 15px;">';
                  echo '  <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">';
                  echo '  <i class="fa fa-cloud-download" aria-hidden="true"></i> Exportar todos';
                  echo '  <span class="caret"></span></button>';
                  echo '  <ul class="dropdown-menu">';
                  echo '    <li><a href="./generar/csv/personal_tecnico/" target="_blank">CSV</a></li>';
                  echo '  </ul>';
                  echo '</div>';
              } 
              ?>
            </div>
            <?php
              if ($modulo_permisos["AUDITORES"]["registrar"] == 1) {
                  echo '<div class="title_right">';
                  echo '  <button type="button" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc" style="float: right;"> ';
                  echo '      <i class="fa fa-plus"> </i> Agregar ' .  strtolower($str_personal_tecnico_singular);
                  echo '  </button>';
                  echo '</div>';
              } 
            ?>
            
          </div>
          <div class="clearfix"></div>

          <div class="x_panel">
            <div class="x_title">
              <h2>Filtros <small></small></h2>
              <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                </li>
              </ul>
              <div class="clearfix"></div>
            </div>
            <div class="x_content" >
              <div class="col-md-4">
                <form class="form-horizontal form-label-left">
                  <div class="form-group">
                    <label>Nombre de <?php echo strtolower($str_personal_tecnico_singular); ?> </label>
                    <div class="input-group" style="width: 100%;">
                          <input type="text" class="form-control input-filtro" id="txtNombreAuditor">
                          <!-- insert this line -->
                          <span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>
                          <select id="txtNombreAuditorContains" class="form-control" style="font-size: 10px;">
                              <option value="" selected>Comienza con</option>
                              <option value="1">Contenido en</option>
                          </select>
                    </div>
                    
                  </div>
                 <div class="form-group">
                    <label>Entidad federativa</label>
                    <div class="input-group" style="width: 100%;">
                          <input type="text" class="form-control input-filtro"  id="txtEntidadFederativa">
                          <!-- insert this line -->
                          <span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>
                          <select id="txtEntidadFederativaContains" class="form-control" style="font-size: 10px;">
                              <option value="" selected>Comienza con</option>
                              <option value="1">Contenido en</option>
                          </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Registro en calificación</label>
                    <div class="input-group" style="width: 100%;">
                          <input type="text" class="form-control input-filtro"  id="txtRegistroCalif">
                          <!-- insert this line -->
                          <span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>
                          <select id="txtRegistroCalifContains" class="form-control" style="font-size: 10px;">
                              <option value="" selected>Comienza con</option>
                              <option value="1">Contenido en</option>
                          </select>
                    </div>
                  </div>
                </form>
              </div>

              <div class="col-md-4">
                <form class="form-horizontal form-label-left">
                  <div class="form-group">
                    <label>Apellido paterno de <?php echo strtolower($str_personal_tecnico_singular); ?> </label>
                    <div class="input-group" style="width: 100%;">
                          <input type="text" class="form-control input-filtro" id="txtApellidoPaterno">
                          <!-- insert this line -->
                          <span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>
                          <select id="txtApellidoPaternoContains" class="form-control" style="font-size: 10px;">
                              <option value="" selected>Comienza con</option>
                              <option value="1">Contenido en</option>
                          </select>
                    </div>
                  </div>
				  <!-- Esta se sustituye por la lista desplegable pero es el mismo filtro -->
                 <div class="form-group" style="display: none"> 
                    <label>Clave de sector</label>
                    <div class="input-group" style="width: 100%;">
                          <input type="text" class="form-control input-filtro"  id="txtClaveSector">
                          <!-- insert this line -->
                          <span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Nombre de sector</label>
                    <div class="input-group" style="width: 100%;">
                          <input type="text" class="form-control input-filtro"  id="txtNombreSector">
                          <!-- insert this line -->
                          <span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>
                          <select id="txtNombreSectorContains" class="form-control" style="font-size: 10px;">
                              <option value="" selected>Comienza con</option>
                              <option value="1">Contenido en</option>
                          </select>
                    </div>
                  </div>
                </form>
              </div>

              <div class="col-md-4">
                <form class="form-horizontal form-label-left">
				<!-- Este elemento no se muestra a petición del IMNC-->
                  <div class="form-group" style="display: none">
                    <label>Apellido materno de <?php echo strtolower($str_personal_tecnico_singular); ?> </label>
                    <div class="input-group" style="width: 100%;">
                           <input type="text" class="form-control input-filtro" id="txtApellidoMaterno">
                          <!-- insert this line -->
                          <span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>
                          <select id="txtApellidoMaternoContains" class="form-control" style="font-size: 10px;">
                              <option value="" selected>Comienza con</option>
                              <option value="1">Contenido en</option>
                          </select>
                    </div>
                  </div>
				  <!-- En su lugar se puso el filtro de activo o inactivo-->
				  <div class="form-group" >
                    <label>Activo o Inactivo</label>
                    <div class="input-group" style="width: 100%;">
                        <select id="cmbActivo" class="form-control">
							<option value="TODOS" selected>Todos</option>
                            <option value="activo">Activos</option>
							<option value="inactivo">Inactivos</option>
                        </select>
                    </div>
                  </div>
                 <div class="form-group">
                    <label>Clave de tipo de servicio</label>
                    <div class="input-group" style="width: 100%;">
                          <input type="text" class="form-control input-filtro"  id="txtClaveTipoServicio">
                          <!-- insert this line -->
                          <span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>
                    </div>
                  </div>
				  <!-- Este elemento no se muestra a petición del IMNC-->
                  <div class="form-group" style="display: none">
                    <label>Nombre de tipo de servicio</label>
                    <div class="input-group" style="width: 100%;">
                           <input type="text" class="form-control input-filtro"  id="txtNombreTipoServicio">
                          <!-- insert this line -->
                          <span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>
                          <select id="txtNombreTipoServicioContains" class="form-control" style="font-size: 10px;">
                              <option value="" selected>Comienza con</option>
                              <option value="1">Contenido en</option>
                          </select>
                    </div>
                  </div>
				  <!-- En su lugar se puso el filtro de sector IAF-->
				  <div class="form-group" >
                    <label>Sector IAF</label>
                    <div class="input-group">
                        <select id="cmbSectoresIAF" class="form-control col-md-6">
                        </select>
                    </div>
                    </div>
                  </div>
                </form>
              </div>
              <div class="form-group">
                <div class="col-md-3 col-sm-3 col-xs-12 col-md-offset-9">
                  <button type="button" class="btn btn-success" id="btnLimpiarFiltros">Ver todos</button>
                  <button type="button" class="btn btn-primary" id="btnFiltrar">Filtrar</button>
                </div>
              </div>
            </div>
          </div>

     <div class="row">
		<p id="cantidad_auditores">
		
		</p>
	 </div>
          <div class="row">
            <div class="col-md-12">
              <div class="x_panel">
                <div class="x_content">

                  <div class="row">

                    <div class="clearfix"></div>

                    <span id="area_fichas" style="font-size: 12px; line-height: 20px;">
                      <!--Se carga on load-->
                    </span>
                  </div>

                </div>
              </div>
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
        <div id="singleupload">
          <div class="ajax-file-upload" style="position: relative; overflow: hidden; cursor: default;">
            Upload
            <form method="POST" action=global_apiserver + "/personal_tecnico/uploadImagen/" enctype="multipart/form-data" style="margin: 0px; padding: 0px;">
                <input type="file" id="ajax-upload-id-1460599196294" name="myfile" accept="*" style="position: absolute; cursor: pointer; top: 0px; width: 100%; height: 100%; left: 0px; z-index: 100; opacity: 0;">
            </form>
          </div>
          <div>
          <!--es necesario este div-->
          </div>
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
          <form id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtNombre">Nombre(s) <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtNombre" placeholder="ejemplo: Juan Carlos" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="4103"><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtPaterno">Apellido Paterno  <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtPaterno" placeholder="ejemplo: Pérez" name="txtPaterno" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324"><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtMaterno">Apellido Materno  <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtMaterno" placeholder="ejemplo: López" name="txtMaterno" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324"><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtIniciales">Iniciales  <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtIniciales" data-inputmask="'mask': 'aaa[a]'"  placeholder="ejemplo: JCPL" name="txtIniciales" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324"><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Fecha de nacimiento  <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="txtFecNac" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text" placeholder="Formato: dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" data-parsley-id="4827"><ul class="parsley-errors-list" id="parsley-id-4827"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtCurp"><?php echo $str_curp; ?>  <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtCurp"  data-inputmask="'mask': '<?php echo $str_mascara_curp; ?>'" placeholder="ejemplo: PELJ900412XXXXXXXX" name="txtCurp" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324"><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12"><?php echo $str_rfc; ?>  <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtRfc" data-inputmask="'mask': '<?php echo $str_mascara_rfc; ?>'" placeholder="ejemplo: PELJ900412XXX" name="txtRfc" required="required" class="form-control col-md-7 col-xs-12">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtTelefonoFijo">Teléfono Fijo  <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtTelefonoFijo" placeholder="ejemplo: 55-35-54-44" name="txtTelefonoFijo" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324"><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtTelefonoCelular">Teléfono Celular 
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtTelefonoCelular" placeholder="ejemplo: 55-37-35-54-44" name="txtTelefonoCelular" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324"><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtEmail">Email 1 <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtEmail" placeholder="correo@ejemplo.com" name="txtEmail" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324"><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
			<div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtEmail">Email 2 
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtEmail2" placeholder="correo@ejemplo.com" name="txtEmail2" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324"><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
			<div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtRfc">Auditor  <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" id="cmbAuditor">
                  <option value="elige" selected disabled>-elige una opción-</option>
                  <option value="1" >Externo</option>
                  <option value="0" >Interno</option>
                </select>
                <ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtRfc">Status  <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" id="cmbEstado">
                  <option value="elige" selected disabled>-elige una opción-</option>
                  <option value="activo" >activo</option>
                  <option value="inactivo" >inactivo</option>
                </select>
                <ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>

          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnGuardar">Guardar</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

<?php

  echo "var str_rfc = '" . $str_rfc . "';";
  echo "var str_curp = '" . $str_curp . "';";
  echo "var str_tipo_entidad = '" . $str_tipo_entidad . "';";
  echo "var str_tipo_persona = '" . $str_tipo_persona . "';";
?>

function draw_ficha_personal_tecnico(objeto)
{
  var fec_nac = objeto.FECHA_NACIMIENTO;
  fec_nac = fec_nac.substring(6,8)+"/"+fec_nac.substring(4,6)+"/"+fec_nac.substring(0,4);
  if (objeto.IMAGEN_BASE64 === null){
    imagenHtml = '  <img src="./pictures/user.png" style="width: 95px; height: 95px; cursor: pointer;" alt="" class="img-circle img-responsive btnSubirImagen" auditor="'+objeto.ID+'">';
  }
  else
  {
    imagenHtml = '  <img src="'+objeto.IMAGEN_BASE64+'" style="width: 95px; height: 95px; cursor: pointer;" alt="" class="img-circle img-responsive btnSubirImagen" auditor="'+objeto.ID+'">';
  }
  strHtml = '';
  strHtml += '<div class="col-md-4 col-sm-4 col-xs-12 animated fadeInDown">';
  strHtml += '  <div class="well profile_view">';
  strHtml += '    <div class="col-sm-12" style="height: 300px;">';
  strHtml += '      <div class="left col-xs-9">';
  strHtml += '        <h5>'+objeto.NOMBRE+' '+objeto.APELLIDO_PATERNO+' '+objeto.APELLIDO_MATERNO+'</h5>';
  strHtml += '        <ul class="list-unstyled">';
  strHtml += '          <li><strong>Iniciales: </strong> '+objeto.INICIALES+'</li>';
  strHtml += '          <li><strong>Nombre: </strong> '+objeto.NOMBRE+'</li>';
  strHtml += '          <li><strong>A. Paterno: </strong> '+objeto.APELLIDO_PATERNO+'</li>';
  strHtml += '          <li><strong>A. Materno: </strong> '+objeto.APELLIDO_MATERNO+'</li>';
  strHtml += '          <li><strong>Fec. Nac: </strong> '+fec_nac+'</li>';
  strHtml += '          <li><strong>'+str_curp+': </strong> '+objeto.CURP+'</li>';
  strHtml += '          <li><strong>'+str_rfc+': </strong> '+objeto.RFC+'</li>';
  strHtml += '          <li><strong>Teléfono Fijo: </strong> '+objeto.TELEFONO_FIJO+'</li>';
  strHtml += '          <li><strong>Teléfono Celular: </strong> '+objeto.TELEFONO_CELULAR+'</li>';
  strHtml += '          <li><strong>Email: </strong> '+objeto.EMAIL+'</li>';
  if(objeto.EMAIL2 != ""){
    strHtml += '          <li><strong>Email2: </strong> '+objeto.EMAIL2+'</li>';
  }
  strHtml += '          <li><strong>Auditor: </strong> '+((objeto.PADRON ==1)?"Externo" : "Interno")+' </li>';
  strHtml += '          <li>'+objeto.STATUS+'</li>';
  strHtml += '        </ul>';
  strHtml += '      </div>';
  strHtml += '      <div class="right col-xs-3 text-center" style="padding: 0px;">';
  strHtml += imagenHtml;
  strHtml += '      </div>';
  strHtml += '    </div>';
  strHtml += '    <div class="col-xs-12 bottom text-center">';
  strHtml += '      <div class="col-xs-12 col-sm-12 emphasis">';
  if (global_permisos["AUDITORES"]["editar"] == 1) {
      strHtml += '        <button type="button" class="btn btn-primary btn-xs btn-imnc btnEditar" auditor="'+objeto.ID+'" style="float: right; font-size: 11px;">';
      strHtml += '            <i class="fa fa-edit"> </i> Editar </button>';
  }
  strHtml += '        <a href="./?pagina=auditor_perfil&tab=calificaciones&id='+objeto.ID+'" class="btn btn-primary btn-xs btn-imnc" style="float: right; font-size: 11px;">';
  strHtml += '            <i class="fa fa-star"> </i> Calificaciones </a>';
  strHtml += '        <a href="./?pagina=auditor_perfil&tab=domicilios&id='+objeto.ID+'" class="btn btn-primary btn-xs btn-imnc" style="float: right; font-size: 11px;">';
  strHtml += '            <i class="fa fa-home"> </i> Domicilios </a>';
   strHtml += '        <a href="./?pagina=auditor_perfil&tab=agenda&id='+objeto.ID+'" class="btn btn-primary btn-xs btn-imnc" style="float: right; font-size: 11px;">';
  strHtml += '            <i class="fa fa-calendar"> </i> Agenda </a>';
  strHtml += '            <a href="./?pagina=registro_expediente&id='+objeto.ID+'&id_entidad=2" class="btn btn-primary btn-xs btn-imnc" style="float: right;">';
  strHtml += '            <i class="fa fa-home"> </i> Expedientes </a>';
  strHtml += '      </div>';
  strHtml += '    </div>';
  strHtml += '  </div>';
  strHtml += '</div>';


  return strHtml;
}

function notify(titulo, texto, tipo) {
    new PNotify({
        title: titulo,
        text: texto,
        type: tipo,
        nonblock: {
            nonblock: true,
            nonblock_opacity: .2
        },
        delay: 2500
    });
}


function clear_modal_insertar_actualizar(){
  $("#txtIniciales").val("");
  $("#txtNombre").val("");
  $("#txtPaterno").val("");
  $("#txtMaterno").val("");
  $("#txtFecNac").val("");
  $("#txtCurp").val("");
  $("#txtRfc").val("");
  $("#txtTelefonoFijo").val("");
  $("#txtTelefonoCelular").val("");
  $("#txtEmail").val("");
  $("#txtEmail2").val("");
  $("#cmbAuditor").val("elige");
  $("#cmbEstado").val("elige");
}

function fill_modal_insertar_actualizar(id_personal_tecnico){
  $.getJSON( global_apiserver + "/personal_tecnico/getById/?id="+id_personal_tecnico, function( response ) {
        var fec_nac = response.FECHA_NACIMIENTO;
        fec_nac = fec_nac.substring(6,8)+"/"+fec_nac.substring(4,6)+"/"+fec_nac.substring(0,4);
        $("#txtIniciales").val(response.INICIALES);
        $("#txtNombre").val(response.NOMBRE);
        $("#txtPaterno").val(response.APELLIDO_PATERNO);
        $("#txtMaterno").val(response.APELLIDO_MATERNO);
        $("#txtFecNac").val(fec_nac);
        $("#txtCurp").val(response.CURP);
        $("#txtRfc").val(response.RFC);
        $("#txtTelefonoFijo").val(response.TELEFONO_FIJO);
        $("#txtTelefonoCelular").val(response.TELEFONO_CELULAR);
        $("#txtEmail").val(response.EMAIL);
		$("#txtEmail2").val(response.EMAIL2);
		$("#cmbAuditor").val(response.PADRON);
        $("#cmbEstado").val(response.STATUS);
    
     });
  
}

function draw_all_fichas(){
    $(".loading").show();
     jQuery('html, body').animate({scrollTop : 0},500);
   $.getJSON( global_apiserver + "/personal_tecnico/getAll/", function( response ) {
        //console.log();
        document.getElementById("cantidad_auditores").innerText = 'Cantidad de Auditores: '+response.length;
        $("#area_fichas").html("");
        $.each(response, function( index, objeto ) {
          $("#area_fichas").append(draw_ficha_personal_tecnico(objeto));  
        });
        listener_btn_editar();
        listener_btn_subir_imagen();
        $(".loading").hide();
     });
}

function draw_fichas_con_filtro(){
    var filtros = {
      NOMBRE:$("#txtNombreAuditor").val(),
      NOMBRE_CONTAINS:$("#txtNombreAuditorContains").val(),
      APELLIDO_MATERNO:$("#txtApellidoMaterno").val(),
      APELLIDO_MATERNO_CONTAINS:$("#txtApellidoMaternoContains").val(),
      APELLIDO_PATERNO:$("#txtApellidoPaterno").val(),
      APELLIDO_PATERNO_CONTAINS:$("#txtApellidoPaternoContains").val(),
      ENTIDAD_FEDERATIVA:$("#txtEntidadFederativa").val(),
      ENTIDAD_FEDERATIVA_CONTAINS:$("#txtEntidadFederativaContains").val(),
      REGISTRO_CALIFICACION:$("#txtRegistroCalif").val(),
      REGISTRO_CALIFICACION_CONTAINS:$("#txtRegistroCalifContains").val(),
      CLAVE_SECTOR:$("#cmbSectoresIAF").val(),
      NOMBRE_SECTOR:$("#txtNombreSector").val(),
      NOMBRE_SECTOR_CONTAINS:$("#txtNombreSectorContains").val(),
      CLAVE_TIPO_SERVICIO:$("#txtClaveTipoServicio").val(),
      NOMBRE_TIPO_SERVICIO:$("#txtNombreTipoServicio").val(),
      NOMBRE_TIPO_SERVICIO_CONTAINS:$("#txtNombreTipoServicioContains").val(),
	  ACTIVO:$("#cmbActivo").val()
    };
    $(".loading").show();
     jQuery('html, body').animate({scrollTop : 0},500);
    $.post(global_apiserver + "/personal_tecnico/getByFiltro/", JSON.stringify(filtros), function(respuesta){
        response = JSON.parse(respuesta);
        document.getElementById("cantidad_auditores").innerText = 'Cantidad de Auditores: '+response.length;
        $("#area_fichas").html("");
        if (response.length == 0) {
           $("#area_fichas").html("No se encontraron resultados");
        }
        $.each(response, function( index, objeto ) {
          $("#area_fichas").append(draw_ficha_personal_tecnico(objeto));  
        });
        listener_btn_editar();
        listener_btn_subir_imagen();
        $(".loading").hide();
    });
}

  function listener_txt_apellido_paterno(){
    $('#txtPaterno').keyup(function(){
      $(this).val($(this).val().toUpperCase());
    });
  }

  function listener_txt_apellido_materno(){
    $('#txtMaterno').keyup(function(){
      $(this).val($(this).val().toUpperCase());
    });
  }

  function listener_txt_curp(){
    $('#txtCurp').keyup(function(){
      $(this).val($(this).val().toUpperCase());
    });
  }

  function listener_txt_rfc(){
    $('#txtRfc').keyup(function(){
      $(this).val($(this).val().toUpperCase());
    });
  }

  function listener_txt_iniciales(){
    $('#txtIniciales').keyup(function(){
      $(this).val($(this).val().toUpperCase());
    });
  }

function listener_btn_limpiar_filtros(){
  $( "#btnLimpiarFiltros" ).click(function() {
    $(".input-filtro").val("");
    draw_all_fichas();
  });
}

function listener_btn_filtrar(){
  $( "#btnFiltrar" ).click(function() {
      draw_fichas_con_filtro();
  });
}

function listener_btn_nuevo(){
  $( "#btnNuevo" ).click(function() {
    $("#btnGuardar").attr("accion","insertar");
    $("#modalTitulo").html("Insertar nuevo registro");
    clear_modal_insertar_actualizar();
    $("#modalInsertarActualizar").modal("show");
  });
}

function listener_btn_editar(){
  $( ".btnEditar" ).click(function() {
    $("#btnGuardar").attr("accion","editar");
    $("#btnGuardar").attr("idPersonalTecnico",$(this).attr("auditor"));
    $("#modalTitulo").html("Editar registro");
    fill_modal_insertar_actualizar($(this).attr("auditor"));
    $("#modalInsertarActualizar").modal("show");
  });
}

function listener_btn_subir_imagen(){
  $( ".btnSubirImagen" ).click(function() {
    var _id_personal_tecnico = $(this).attr("auditor");
    var uploadObj = $("#singleupload").uploadFile({
      url:global_apiserver + "/personal_tecnico/uploadImagen/",
      multiple:false,
      dragDrop:false,
      maxFileCount:1,
      acceptFiles:"image/*",
      fileName:"myfile",
      formData: {"id_personal_tecnico":_id_personal_tecnico}, 
      onSuccess:function(files,data,xhr,pd)
      {
        $("#modalSubirImagen").modal("hide");
        notify("Éxito", "La imagen ha cambiado", "success");
        draw_all_fichas();
        uploadObj.reset();
        //document.location = "./?pagina=auditores";
      }
    });
    $("#modalSubirImagen").modal("show");
  });
}


function listener_btn_guardar(){
  $( "#btnGuardar" ).click(function() {
    if ($("#btnGuardar").attr("accion") == "insertar")
    {
      insertar();
    }
    else if ($("#btnGuardar").attr("accion") == "editar")
    {
      editar();
    }
  });
}

function hide_modal_inserta_actualiza(){
  $("#modalInsertarActualizar").modal("hide");
}

function insertar(){
    var fec_nac = $("#txtFecNac").val();
    fec_nac = fec_nac.substring(6,10)+fec_nac.substring(3,5)+fec_nac.substring(0,2);
    var personal_tecnico = {
      NOMBRE:$("#txtNombre").val(),
      APELLIDO_MATERNO:$("#txtMaterno").val(),
      APELLIDO_PATERNO:$("#txtPaterno").val(),
      INICIALES:$("#txtIniciales").val(),
      FECHA_NACIMIENTO:fec_nac,
      CURP:$("#txtCurp").val(),
      RFC:$("#txtRfc").val(),
      TELEFONO_FIJO:$("#txtTelefonoFijo").val(),
      TELEFONO_CELULAR:$("#txtTelefonoCelular").val(),
      EMAIL:$("#txtEmail").val(),
	  EMAIL2:$("#txtEmail2").val(),
	  PADRON:$("#cmbAuditor").val(),
      STATUS:$("#cmbEstado").val(),
      ID_USUARIO:sessionStorage.getItem("id_usuario")
    };
    $.post(global_apiserver + "/personal_tecnico/insert/", JSON.stringify(personal_tecnico), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
          hide_modal_inserta_actualiza();
          notify("Éxito", "Se ha insertado un nuevo registro", "success");
          draw_all_fichas();
        }
        else{
          notify("Error", respuesta.mensaje, "error");
        }
    });
}

function editar(){
    var fec_nac = $("#txtFecNac").val();
    fec_nac = fec_nac.substring(6,10)+fec_nac.substring(3,5)+fec_nac.substring(0,2);
    var personal_tecnico = {
      ID:$("#btnGuardar").attr("idPersonalTecnico"),
      NOMBRE:$("#txtNombre").val(),
      APELLIDO_MATERNO:$("#txtMaterno").val(),
      APELLIDO_PATERNO:$("#txtPaterno").val(),
      INICIALES:$("#txtIniciales").val(),
      FECHA_NACIMIENTO:fec_nac,
      CURP:$("#txtCurp").val(),
      RFC:$("#txtRfc").val(),
      TELEFONO_FIJO:$("#txtTelefonoFijo").val(),
      TELEFONO_CELULAR:$("#txtTelefonoCelular").val(),
      EMAIL:$("#txtEmail").val(),
	  EMAIL2:$("#txtEmail2").val(),
	  PADRON:$("#cmbAuditor").val(),
      STATUS:$("#cmbEstado").val(),
      ID_USUARIO:sessionStorage.getItem("id_usuario")
    };
    $.post(global_apiserver + "/personal_tecnico/update/", JSON.stringify(personal_tecnico), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
          hide_modal_inserta_actualiza();
          notify("Éxito", "Se han actualizado los datos", "success");
          draw_all_fichas();
        }
        else{
          notify("Error", respuesta.mensaje, "error");
        }
    });
}
function fill_cmb_sectores(seleccionado){
  $("#cmbSectoresIAF").html('<option value="TODOS" selected>-- elige una opción --</option>');
  $.getJSON(  global_apiserver + "/sectores/getAll/", function( response ) {
    $.each(response, function( indice, objTserv ) {
      $("#cmbSectoresIAF").append('<option value="'+objTserv.ID+'">'+objTserv.ID+'-'+objTserv.ID_TIPO_SERVICIO+'-'+objTserv.NOMBRE+'</option>'); 
    });
    $("#cmbSectoresIAF").val(seleccionado);
  });
}
  $( window ).load(function() {
      //draw_all_fichas();
      listener_btn_nuevo();
      listener_btn_guardar();
      listener_btn_limpiar_filtros();
      listener_btn_filtrar();
      listener_txt_apellido_paterno();
      listener_txt_apellido_materno();
      listener_txt_curp();
      listener_txt_rfc();
      listener_txt_iniciales();
	  fill_cmb_sectores("TODOS");
  });
</script>
