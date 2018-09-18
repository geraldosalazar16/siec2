<div class="right_col" role="main">

        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>Clientes</h3>
              <?php
              if ($modulo_permisos["CLIENTES"]["extraer"] == 1) {
                  echo '<div class="dropdown" style="margin-bottom: 15px;">';
                  echo '  <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">';
                  echo '  <i class="fa fa-cloud-download" aria-hidden="true"></i> Exportar todos';
                  echo '  <span class="caret"></span></button>';
                  echo '  <ul class="dropdown-menu">';
                  echo '    <li><a href="./generar/csv/clientes/" target="_blank">CSV</a></li>';
                  echo '  </ul>';
                  echo '</div>';
              } 
              ?>
            </div>
            <?php
              if ($modulo_permisos["CLIENTES"]["registrar"] == 1) {
                  echo '<div class="title_right">';
                  echo '  <button type="button" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc" style="float: right;"> ';
                  echo '    <i class="fa fa-plus"></i> Agregar cliente ';
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
            <div class="x_content">
              <div class="col-md-4">
                <form class="form-horizontal form-label-left ng-pristine ng-valid">
                  <div class="form-group">
                    <label>Nombre de cliente </label>
                    <div class="input-group" style="width: 100%;">
                          <input type="text" class="form-control input-filtro" id="txtFiltroNombreCliente">
                          <!-- insert this line -->
                          <span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>
                          <select id="txtFiltroNombreClienteContains" class="form-control" style="font-size: 10px;">
                              <option value="" selected>Comienza con</option>
                              <option value="1">Contenido en</option>
                          </select>
                    </div>
                  </div>
                 <div class="form-group">
                    <label>Entidad federativa</label>
                    <div class="input-group" style="width: 100%;">
                          <input type="text" class="form-control input-filtro" id="txtFiltroEntidadFederativa">
                          <!-- insert this line -->
                          <span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>
                          <select id="txtFiltroEntidadFederativaContains" class="form-control" style="font-size: 10px;">
                              <option value="" selected>Comienza con</option>
                              <option value="1">Contenido en</option>
                          </select>
                    </div>
                  </div>
                </form>
              </div>

              <div class="col-md-4">
                <form class="form-horizontal form-label-left ng-pristine ng-valid">
                  <div class="form-group">
                    <label><?php echo $str_rfc; ?> </label>
                    <div class="input-group" style="width: 100%;">
                         <input type="text" class="form-control input-filtro" id="txtFiltroRFC">
                          <!-- insert this line -->
                          <span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>
                          <select id="txtFiltroRFCContains" class="form-control" style="font-size: 10px;">
                              <option value="" selected>Comienza con</option>
                              <option value="1">Contenido en</option>
                          </select>
                    </div>
                    
                  </div>
                 <div class="form-group">
                    <label>Municipio</label>
                    <div class="input-group" style="width: 100%;">
                          <input type="text" class="form-control input-filtro" id="txtFiltroMunicipio">
                          <!-- insert this line -->
                          <span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>
                          <select id="txtFiltroMunicipioContains" class="form-control" style="font-size: 10px;">
                              <option value="" selected>Comienza con</option>
                              <option value="1">Contenido en</option>
                          </select>
                    </div>
                  </div>
                </form>
              </div>

              <div class="col-md-4">
                <form class="form-horizontal form-label-left ng-pristine ng-valid">
                  <div class="form-group">
                    <label>Nombre de contacto </label>
                    <div class="input-group" style="width: 100%;">
                         <input type="text" class="form-control input-filtro" id="txtFiltroNombreContacto">
                          <!-- insert this line -->
                          <span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>
                          <select id="txtFiltroNombreContactoContains" class="form-control" style="font-size: 10px;">
                              <option value="" selected>Comienza con</option>
                              <option value="1">Contenido en</option>
                          </select>
                    </div>
                  </div>
                 <div class="form-group">
                    <label>Código postal</label>
                    <div class="input-group" style="width: 100%;">
                          <input type="text" class="form-control input-filtro" id="txtFiltroCodigoPostal">
                          <!-- insert this line -->
                          <span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>
                          <select id="txtFiltroCodigoPostalContains" class="form-control" style="font-size: 10px;">
                              <option value="" selected>Comienza con</option>
                              <option value="1">Contenido en</option>
                          </select>
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
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="false">&times;</span></button>
        <h4 class="modal-title">Subir imagen</h4>
      </div>
      <div class="modal-body">
        <div id="singleupload">
          <div class="ajax-file-upload" style="position: relative; overflow: hidden; cursor: default;">
            Upload
            <form method="POST" action= global_apiserver + "/clientes/uploadImagen/" enctype="multipart/form-data" style="margin: 0px; padding: 0px;">
                <input type="file" id="ajax-upload-id-1460599196294" name="myfile" accept="*" style="position: absolute; cursor: pointer; top: 0px; width: 100%; height: 100%; left: 0px; z-index: 100; opacity: 0;">
            </form>
          </div>
          <div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal insertar/actualizar-->
<div class="modal fade" id="modalInsertarActualizar" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTitulo">Insertar/Actualizar</h4>
      </div>
      <div class="modal-body">
          <form id="demo-form2" data-parsley-validate="" class="" novalidate="">
            <div class="form-group form-vertical">
              <label class="control-label col-md-12 col-sm-12 col-xs-12">Nombre del cliente <span class="required">*</span>
              </label>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <input type="text" id="txtNombre" required="required" placeholder="SONORA INDUSTRIAL AZUCARERA, S. DE R. L." class="form-control col-md-7 col-xs-12">
              </div>
            </div>
            <div class="form-group form-vertical">
              <label class="control-label col-md-12 col-sm-12 col-xs-12" for="txtRfc"><?php echo $str_rfc; ?>  <span class="required">*</span>
              <div style="float: right;"><input type="checkbox" id="chkRfc"> <span style="font-size: 11px;">No tiene RFC</span></div>
              </label>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <input type="text" id="txtRfc" data-inputmask="'mask': '<?php echo $str_mascara_rfc; ?>'" placeholder="SIA090305XXX" name="txtRfc" required="required" class="form-control col-md-7 col-xs-12">
              </div>
            </div>
            <div class="form-group form-vertical">
              <label class="control-label col-md-12 col-sm-12 col-xs-12">Es facturatario <span class="required">*</span>
              </label>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <select class="form-control" id="cmbEsFacturario">
                  <option value="" selected disabled> -- elige una opción -- </option>
                  <option value="S">si</option>
                  <option value="N">no</option>
                </select>
              </div>
            </div>
            
             <div class="form-group form-vertical">
              <label class="control-label col-md-12 col-sm-12 col-xs-12" >Nombre del facturario <span class="required">*</span> 
              </label> 
              <div class="col-md-12 col-sm-12 col-xs-12">
                <input type="text" id="txtClienteFacturario" required="required" class="form-control col-md-7 col-xs-12" >
              </div>
            </div>
            <div class="form-group form-vertical">
              <label class="control-label col-md-12 col-sm-12 col-xs-12">RFC del facturario <span class="required">*</span>
              </label>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <input type="text" id="txtRFCFac"  data-inputmask="'mask': '<?php echo $str_mascara_rfc; ?>'" required="required"  class="form-control col-md-7 col-xs-12" disabled>
              </div>
            </div>
            <div class="form-group form-vertical">
              <label class="control-label col-md-12 col-sm-12 col-xs-12" for="cmbTPersona"><?php echo $str_tipo_persona; ?>  <span class="required">*</span>
              </label>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <select class="form-control" id="cmbTPersona">
                  <option value="elige" selected disabled> -- elige una opción -- </option>
                </select>
                
              </div>
            </div>
            <div class="form-group form-vertical">
              <label class="control-label col-md-12 col-sm-12 col-xs-12" for="cmbTEntidad"><?php echo $str_tipo_entidad; ?>  <span class="required">*</span>
              </label>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <select class="form-control" id="cmbTEntidad">
                  <option value="elige" selected disabled> -- elige una opción -- </option>
                </select>
                
              </div>
            </div>
            
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="btnCerrar">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnGuardar">Guardar</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal insertar como prospecto-->
<div class="modal fade" id="modalAgregarProspecto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalAgregarProspectoTitulo">Agregar como prospecto</h4>
      </div>
      <div class="modal-body">
         <form name="form2" id="form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
		 
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Empresa:<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="nombre" id="nombre" class="form-control col-md-7 col-xs-12" ng-model="nombre">
				        <span id="nombreerror" class="text-danger"></span>
			        </div>
            </div>            

            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">RFC
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="rfc" data-inputmask="'mask': '<?php echo $str_mascara_rfc; ?> '" placeholder="PELJ900412XXX" class="form-control col-md-7 col-xs-12">
              </div>
            </div>
			
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Giro:
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="giro" class="form-control col-md-7 col-xs-12" ng-model="giro">
              </div>
            </div>
			
			<div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Origen
              <span class="required">*</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select ng-model="origen" id = "cmbOrigen" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324" ng-options="ori.id_origen as ori.origen for ori in Origenes">
                </select>
                <span id="origenerror" class="text-danger"></span>
              </div>
            </div>
			<!--
			<div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Tipo de Servicio
              <span class="required">*</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select ng-model="tipo_servicio" id= "cmbTipoServicio" ng-disabled="habilitar_tipo_servicio == false" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324" ng-options="tipos.id_tipo_servicio as tipos.tipo_servicio for tipos in TiposServicio">
                </select>
                <span id="tiposservicioerror" class="text-danger"></span>
              </div>
            </div>
			-->
			<div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Competencia</label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select ng-model="competencia" id= "cmbCompetencias" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324" ng-options="com.id_competencia as com.competencia for com in Competencia">
                </select>
                <span id="origenerror" class="text-danger"></span>
              </div>
            </div>
			
			<div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Estatus seguimiento
              <span class="required">*</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select ng-model="estatus_seguimiento" id= "cmbEstatusSeguimiento" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324" ng-options="estseg.id_estatus_seguimiento as estseg.estatus_seguimiento for estseg in Estatus_seguimiento">
                </select>
                <span id="origenerror" class="text-danger"></span>
              </div>
            </div>
			
			<div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Tipo de contrato
              <span class="required">*</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select ng-model="tipo_contrato" id= "cmbTipoContrato" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324" ng-options="tipcon.id_tipo_contrato as tipcon.tipo_contrato for tipcon in Tipo_contrato">
                </select>
                <span id="origenerror" class="text-danger"></span>
              </div>
            </div>
			
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Usuario Secundario
              <span class="required">*</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select ng-model="usuarios" id="cmbUsuarios" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324" ng-options="usuario.id as usuario.descripcion for usuario in Usuarios">
                </select>
                <span id="usuarioserror" class="text-danger"></span>
              </div>
            </div>
			<!--
			<div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Departamento
              <span class="required">*</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select ng-model="departamentos" id="cmbDepartamentos" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324" ng-options="dep.id as dep.nombre for dep in Departamentos">
                </select>
                <span id="usuarioserror" class="text-danger"></span>
              </div>
            </div>
			
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Habilitado<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="checkbox" class="form-control col-md-7 col-xs-12 selector noshadow" ng-model="cbhabilitado" data-parsley-id="2324" ><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
            -->
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default">Cerrar</button>
        <button type="button" class="btn btn-primary" ng-click="guardar()"  id="btnGuardarProspecto">Guardar</button>
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

</script>