
<span ng-controller="archivos_expediente_controller">
<style>
  .check-noshadow {
    -webkit-box-shadow: none;
    -moz-box-shadow: none;
    box-shadow: none;
  }
</style>
<script src="js/ngFileUpload/ng-file-upload-shim.min.js"></script>
<script src="js/ngFileUpload/ng-file-upload.min.js"></script>
<div class="right_col" role="main">
  <div class="row">
    <div class="col-md-10 col-sm-10 col-xs-10" id="tabla-file">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>{{titulo}}</h2></p>
        <p>
          <button type="button" ng-click="editar()" class="btn btn-primary btn-xs btn-imnc" style="float: right;"> 
            <i class="fa fa-plus"> </i> Editar 
          </button>
          <button type="button" ng-click="validar()" class="btn btn-primary btn-xs btn-imnc" style="float: right;"> 
            <i class="fa fa-plus"> </i> Validar 
          </button>
        </p>
          <div class="clearfix"></div>
        </div>

        <div class="x_content">
          <table class="table table-striped responsive-utilities jambo_table bulk_action">
            <thead>
              <tr class="headings">
                <th class="column-title">ID</th>
                <th class="column-title">Nombre Documento</th>
                <th class="column-title">Obligatorio</th>
                <th class="column-title">Nombre Archivo</th>
                <th class="column-title">Fecha de vencimiento inicial</th>
                <th class="column-title">Fecha de vencimiento final</th>
                <th class="column-title">Calificación</th>
              </tr>
            </thead>

            <tbody ng-repeat="x in form.archivosDocumentosDetalles">
              <tr>
                <td>{{x.ID_ARCHIVO_EXPEDIENTE}}</td>
                <td>{{x.NOMBRE_DOCUMENTO}}</td>
                <td>{{x.OBLIGATORIO == 1? "SI" : "NO"}}</td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
              </tr>
              <tr ng-repeat="F in x.ARCHIVOS" >
                <td> </td>
                <td> </td>
                <td> </td>
                <td><a href="./ExpedienteArchivos.php?codigo={{F.ID_ENCRIPTADO}}"> {{F.NOMBRE_ARCHIVO}} </a></td>
                <td> {{F.FECHA_VENCIMIENTO_INICIAL}}</td>
                <td> {{F.FECHA_VENCIMIENTO_FINAL}}</td>
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
            <input type="hidden" id="txtId" ng-model = "form.ID_EXPEDIENTE_ENTIDAD" />
            
            <!-- ARCHIVOS DE DOCUMENTOS DEL EXPEDIENTE -->
            <div id = "form-archivos">
              <h4><label class="col-md-12 col-sm-12 col-xs-12">Archivos: </label></h4>
              <div ng-repeat="doc in form.archivosDocumentos" ng-init= "doc_index = $index">
                
                <div class="form-group">
                  <label class="col-md-12 col-sm-12 col-xs-12">{{ doc.NOMBRE_DOCUMENTO }} {{ doc.OBLIGATORIO == 1? "(obligatorio)":""}}</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="hidden" id="txtId" ng-model = "doc.ID_ARCHIVO_EXPEDIENTE" />
                    <input type="hidden" id="txtId" ng-model = "doc.ID_EXPEDIENTE_DOCUMENTO" />
                  </div>
                </div>
                <!-- ULTIMO ARCHIVO DE DOCUMENTO SUBIDO -->
                <div class="old-archivo">
                  <input type="hidden" id="txtId" ng-model = "doc.ID_ULT_ARCHIVO" />
                  <div class="form-group" ng-hide="accion == 'insertar'">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12">Último Archivo: </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" readonly ng-model="doc.ULT_NOMBRE_ARCHIVO" class="form-control col-md-5 col-xs-10" data-parsley-id="2324">
                    </div>
                  </div>
                  <div class="form-group" ng-hide="accion != 'validar'">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12">Validación: </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="checkbox" required="required" ng-model="doc.ULT_VALIDACION" class="form-control col-md-5 col-xs-10 check-noshadow" data-parsley-id="2324" ng-true-value="'1'" ng-false-value="'0'" ng-change = "doc.CAMBIO = true">
                      <span id="valida-error-{{doc_index}}" class="text-danger"></span>
                    </div>
                  </div>
                </div>
                <!--NUEVOS ARCHIVOS DE DOCUMENTOS -->
                <div ng-hide="accion == 'validar'">
                  <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12">Nuevo Archivo: </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <div class="btn btn-primary" ngf-select="getFileName(doc.FILE, doc_index)" ng-model="doc.FILE" id="archivo-input-{{doc_index}}" 
                        name="file-{{doc_index}}" ngf-max-size="20MB" >Select</div>
                        <span>{{doc.NOMBRE_ARCHIVO}}</span>
                      <span id="archivo-error-{{doc_index}}" class="text-danger" ></span>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12">Fecha de vencimiento Inicial: </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="date" required="required" ng-model="doc.FECHA_VENCIMIENTO_INICIAL" class="form-control col-md-5 col-xs-10"   
                      data-parsley-id="2324">
                      <span id="vencimiento-inicial-error-{{doc_index}}" class="text-danger"></span>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12">Fecha de vencimiento Final: </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="date" required="required" ng-model="doc.FECHA_VENCIMIENTO_FINAL" class="form-control col-md-5 col-xs-10" data-parsley-id="2324">
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
