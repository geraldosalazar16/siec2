<span ng-controller="registro_expediente_controller">
<style>
  .check-noshadow {
    -webkit-box-shadow: none;
    -moz-box-shadow: none;
    box-shadow: none;
  }
  .break-word {
    width: 100%;
    white-space: nowrap;
    position: relative;
    overflow: hidden;
    text-overflow: ellipsis;
  }
}
</style>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-themes/jquery-ui.min.css">
<script src="js/ngFileUpload/ng-file-upload-shim.min.js"></script>
<script src="js/ngFileUpload/ng-file-upload.min.js"></script>
<div class="right_col" role="main">
  <div class="row">
    <div class="col-md-10 col-sm-10 col-xs-10" id="tabla-exp">
      <div class="x_panel">
        <div class="x_title">
        <p><h2 class="break-word">{{titulo}}</h2></p>
          <div class="clearfix"></div>
        </div>

        <div class="x_content">

          <table class="table table-striped responsive-utilities jambo_table bulk_action">
            <thead>
              <tr class="headings">
                <th class="column-title">#</th>
                <th class="column-title">Nombre Expediente</th>
                <th class="column-title">Validación</th>
				        <th class="column-title">&nbsp;</th>
              </tr>
            </thead>

            <tbody>
    				<tr ng-repeat="x in registroExpediente" class = "ng-scope even pointer">
    					<td>{{$index + 1}}</td>
    					<td>{{x.NOMBRE_EXPEDIENTE_ENTIDAD}}</td>
              <td>{{(x.VALIDO == 1? "Válido" : "No Válido")}}</td> 
    					<td>
    						<button type="button"  ng-click="editar(x.ID, false)" class="btn btn-primary btn-xs btn-imnc btnEditar"
                ng-if='modulo_permisos["documentos"] == 1' style="float: right;">
    							<i class="fa fa-file-pdf-o"> </i> Subir Archivos
    						</button>
                <button type="button" ng-click="validar(x.ID, false)" class="btn btn-primary btn-xs btn-imnc btnEditar"
                ng-if='modulo_permisos["validar"] == 1' style="float: right;">
                  <i class="fa fa-check-square"> </i> Validar
                </button>
                 <button type="button" ng-click="detalles(x.ID)" class="btn btn-primary btn-xs btn-imnc btnEditar" style="float: right;">
                  <i class="fa fa-edit"> </i> Ver Expediente
                </button>
    					</td>
    				</tr>
            </tbody>

          </table>	
        </div>
      </div>
    </div>

    <div class="col-md-10 col-sm-10 col-xs-10" id="tabla-file" hidden>
      <div class="x_panel">
        <div class="x_title">
        <p><h2 class="break-word">{{titulo}}</h2></p>
        <p>
          <button type="button" ng-click="editar(form.ID, true)" class="btn btn-primary btn-xs btn-imnc"
          ng-if='modulo_permisos["documentos"] == 1' style="float: right;"> 
            <i class="fa fa-file-pdf-o"> </i> Subir Archivos 
          </button>
          <button type="button" ng-click="validar(form.ID, true)" class="btn btn-primary btn-xs btn-imnc"
          ng-if='modulo_permisos["validar"] == 1' style="float: right;"> 
            <i class="fa fa-check-square"> </i> Validar 
          </button>
          <button type="button" ng-click="cerrarDetails()" class="btn btn-primary btn-xs btn-imnc" style="float: right;"> 
            <i class="fa fa-hand-o-left"> </i> Regresar 
          </button>
        </p>
          <div class="clearfix"></div>
        </div>

        <div class="x_content">
          <table class="table table-striped responsive-utilities jambo_table bulk_action">
            <thead>
              <tr class="headings">
                <th class="column-title">#</th>
                <th class="column-title">Nombre Documento</th>
                <th class="column-title">Obligatorio</th>
                <th class="column-title">Nombre Archivo</th>
                <th class="column-title">Fecha de vencimiento inicial</th>
                <th class="column-title">Fecha de vencimiento final</th>
                <th class="column-title">Calificación</th>
              </tr>
            </thead>

            <tbody ng-repeat="x in form.archivosDocumentosDetalles" class = "ng-scope even pointer">
              <tr>
              	<td>{{$index + 1}}</td>
                <td>{{x.NOMBRE_DOCUMENTO}}</td>
                <td>{{x.OBLIGATORIO == 1? "SI" : "NO"}}</td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
              </tr>
              <tr ng-repeat="F in x.ARCHIVOS | orderBy:'-ID_ARCHIVO'" class = "ng-scope even pointer">
                <td> </td>
                <td> </td>
                <td> </td>
                <td><a href="{{file_url}}?entidad=0&codigo={{F.ID_ENCRIPTADO}}" target="_blank"> {{F.NOMBRE_ARCHIVO}} </a></td>
                <td> {{F.FECHA_VENCIMIENTO_INICIAL}}</td>
                <td ng-style="fechas(F.FECHA_VENCIMIENTO_FINAL)"> {{F.FECHA_VENCIMIENTO_FINAL}}</td>
                <td> {{F.VALIDACION == 1? "Válido" : "No Válido"}}</td>
              </tr>
            </tbody>
          </table>
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
          <form id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" enctype="multipart/form-data">
            <!-- Nombre del Cliente-->
            <input type="hidden" id="txtId" ng-model = "form.ID" />
            <input type="hidden" id="txtId" ng-model = "form.ID_REGISTRO" />
            <!-- COMBOBOX DE TIPO DE EXPEDIENTE POR ENTIDAD
            <div class="form-group" id = "form-expediente">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Tipo de Expediente<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select id="txtExpediente" ng-model = "form.EXPEDIENTE_ENTIDAD" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324" ng-options="exp.nombre for exp in expedientes" 
                ng-change="archivosPorExpediente(form.EXPEDIENTE_ENTIDAD)"><option value="">-Seleccione un Expediente-</option></select>
                <span id="expediente-error" class="text-danger"></span>
              </div>
            </div>-->
            <input type="hidden" id="txtId" ng-model = "form.ID_EXPEDIENTE_ENTIDAD" />
            <!-- ARCHIVOS DE DOCUMENTOS DEL EXPEDIENTE -->
            <div id = "form-archivos">
              <h2><label class="col-md-12 col-sm-12 col-xs-12">Documentos: </label></h2>
              <div ng-repeat="doc in form.archivosDocumentos" on-finish-render="ngRepeatFinished" ng-init= "doc_index = $index">
                
                <div class="form-group">
                  <span class="col-md-12 col-sm-12 col-xs-12" style="font-weight:bold;font-size:14px;">{{ doc.NOMBRE_DOCUMENTO }} {{ doc.OBLIGATORIO == 1? "(obligatorio)":""}}</span>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="hidden" id="txtId" ng-model = "doc.ID_ARCHIVO_EXPEDIENTE" />
                    <input type="hidden" id="txtId" ng-model = "doc.ID_EXPEDIENTE_DOCUMENTO" />
                  </div>
                </div>
                <!-- ULTIMO ARCHIVO DE DOCUMENTO SUBIDO -->
                <div class="old-archivo">
                  <input type="hidden" id="txtId" ng-model = "doc.ID_ULT_ARCHIVO" />
                  <div class="form-group" ng-hide="accion == 'insertar'">
                    <span class="control-label col-md-4 col-sm-4 col-xs-12">Último Archivo: </span>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <a href="{{file_url}}?entidad=0&codigo={{doc.ID_ULT_ENCRIPTADO}}" readonly ng-if="doc.ULT_NOMBRE_ARCHIVO != null" target="_blank" class="form-control col-md-5 col-xs-10"> {{doc.ULT_NOMBRE_ARCHIVO}} </a>
                      <input type="text"  class="form-control col-md-5 col-xs-10" readonly ng-if="doc.ULT_NOMBRE_ARCHIVO == null">
                    </div>
                  </div>
                  <div class="form-group" ng-hide="accion != 'validar'">
                    <span class="control-label col-md-4 col-sm-4 col-xs-12">Validación: </span>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="checkbox" required="required" ng-model="doc.ULT_VALIDACION" class="form-control col-md-5 col-xs-10 check-noshadow" data-parsley-id="2324" ng-true-value="'1'" ng-false-value ="'0'" ng-change = "doc.CAMBIO = true">
                      <span id="valida-error-{{doc_index}}" class="text-danger"></span>
                    </div>
                  </div>
                </div>
                <!--NUEVOS ARCHIVOS DE DOCUMENTOS -->
                <div ng-hide="accion == 'validar'">
                  <div class="form-group">
                    <span class="control-label col-md-4 col-sm-4 col-xs-12">Nuevo Archivo: </span>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <div class="btn btn-primary" ngf-select="getFileName(doc.FILE, doc_index)" ng-model="doc.FILE" id="archivo-input-{{doc_index}}" 
                        name="file-{{doc_index}}" ngf-accept="'.pdf'" >Select</div>
                        <span>{{doc.NOMBRE_ARCHIVO}}</span>
                        <button type="button" class="close" ng-click="delFileName(doc_index)"><span aria-hidden="true">&times;</span></button>
                      <span id="archivo-error-{{doc_index}}" class="text-danger" ></span>
                    </div>
                  </div>

                  <div class="form-group">
                    <span class="control-label col-md-4 col-sm-4 col-xs-12">Fecha de vencimiento Inicial: </span>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" required="required" ng-model="doc.FECHA_VENCIMIENTO_INICIAL" class="form-control col-md-5 col-xs-10 dateInput" 
                      id="startDate-{{doc_index}}" data-parsley-id="2324" data-index = "{{doc_index}}">
                      <span id="vencimiento-inicial-error-{{doc_index}}" class="text-danger"></span>
                    </div>
                  </div>

                  <div class="form-group">
                    <span class="control-label col-md-4 col-sm-4 col-xs-12">Fecha de vencimiento Final: </span>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" required="required" ng-model="doc.FECHA_VENCIMIENTO_FINAL" class="form-control col-md-5 col-xs-10 dateEndInput" 
                      id="endDate-{{doc_index}}" data-parsley-id="2324" data-index = "{{doc_index}}">
                      <span id="vencimiento-final-error-{{doc_index}}" class="text-danger"></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" ng-click="guardar()" id="btnGuardar">Guardar</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="js/notify.js"></script>

