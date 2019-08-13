<style>
  .check-noshadow {
    -webkit-box-shadow: none;
    -moz-box-shadow: none;
    box-shadow: none;
  }
</style>
<span ng-controller="solicitudes_facturacion_controller">
    <div class="right_col " role="main">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <p><h2>Solicitudes de facturación</h2></p>

                    <?php
                    if ($modulo_permisos["FACTURACION"]["registrar"] == 1) {
                        echo '<p>';
                        echo '  <button type="button" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc" style="float: right;" ng-click="abrirModalCrearSolicitud()"> ';
                        echo '    <i class="fa fa-plus"> </i> Agregar nueva solicitud ';
                        echo '  </button>';
                        echo '</p>';
                    } 
                    ?>

                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="x_panel">
                        <div class="x_content">

                            <div class="row">
                                <div class="clearfix"></div>
                                <table class="table table-striped responsive-utilities jambo_table bulk_action">
                                <thead>
                                    <tr class="headings">
                                    <th class="column-title" width="5%">ID</th>
                                    <th class="column-title" width="30%">Informaci&oacuten</th>
                                    <th class="column-title" width="10%">Estatus</th>
                                    <th class="column-title" width="30%">Detalles</th>
                                    <th class="column-title" width="20%">Monto</th>
                                    <th class="column-title" width="5%"></th>
                                    </tr>
                                </thead>

                                <tbody >
                                    <tr ng-repeat="solicitud in listaSolicitudes" class="ng-scope  even pointer">
                                        <td>{{solicitud.ID}}</td>
                                        <td>Cliente:<strong>{{solicitud.CLIENTE}}</strong><br>
                                            Ciclo: <strong>{{solicitud.CICLO}}</strong><br>
                                            Auditor&iacutea: <strong>{{solicitud.AUDITORIA}}</strong><br>
                                            Tipo de servicio: <strong>{{solicitud.TIPO_SERVICIO}}</strong><br>
                                        </td>
                                        <td>{{solicitud.ESTATUS}}</td>   
                                        <td>
                                            Forma de pago: <strong>{{solicitud.FORMA_PAGO || 'Por definir'}}</strong><br>
                                            Método de pago: <strong>{{solicitud.METODO_PAGO || 'Por definir'}}</strong><br>
                                            Uso de la factura: <strong>{{solicitud.USO_FACTURA || 'Por definir'}}</strong><br>
                                            Razón Social: <strong>{{solicitud.RAZON_SOCIAL || 'Por definir'}}</strong><br>
                                            RFC: <strong>{{solicitud.RFC || 'Por definir'}}</strong><br>
                                        </td>
                                        <td>
                                            Monto: <strong>{{solicitud.MONTO | currency}}</strong><br>
                                            Requiere orden de compra: <strong>{{solicitud.REQUIERE_ORDEN_COMPRA || 'Por definir'}}</strong><span><br>
                                            Facturar viáticos: <strong>{{solicitud.FACTURAR_VIATICOS || 'Por definir'}}</strong><span><br>
                                            Cargar en portal: <strong>{{solicitud.SUBIR_FACTURA_PORTAL || 'Por definir'}}</strong><span><br>
                                            <span ng-if="solicitud.SUBIR_FACTURA_PORTAL === 'S'">Portal: <strong>{{solicitud.PORTAL}}</strong><span><br>
                                        </td>                                     
                                        <td>
                                            <div class="btn-group">                                
                                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" > Opciones
                                                    <span class="caret"></span>
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <ul class="dropdown-menu pull-right">
                                                    <li>
                                                        <a	ng-click="abrirModalEditarSolicitud(solicitud.ID)"> 
                                                        <span class="labelAcordeon"	>Editar solicitud</span></a>                                                    
                                                    </li>
                                                    <li>
                                                        <a	ng-click="procesarSolicitud(solicitud)"> 
                                                        <span class="labelAcordeon"	>Procesar solicitud</span></a>                                                        
                                                    </li>
                                                    <li>
                                                        <a	href="./?pagina=solicitudes_facturacion_detalles&id={{solicitud.ID}}">
                                                        <span class="labelAcordeon"	>Ver detalles</span></a>                                                        
                                                    </li>
                                                    <li>
                                                        <a	ng-click="verHistoricoSolicitud(solicitud)"> 
                                                        <span class="labelAcordeon"	>Ver Histórico</span></a>                                                        
                                                    </li>
<!--                                                    <li>-->
<!--                                                        <a	ng-click="verDocumentosSolicitud(solicitud)"> -->
<!--                                                        <span class="labelAcordeon"	>Ver Documentos</span></a>                                                        -->
<!--                                                    </li>-->
                                                    <li ng-show="solicitud.ID_ESTATUS === 6">
                                                        <a	ng-click="agregarComplementosSolicitud(solicitud)"> 
                                                        <span class="labelAcordeon"	>Agregar complementos</span></a>                                                        
                                                    </li>
                                                </ul>                                   
                                            </div>
                                        </td>                                
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Modales -->
    <!-- Modal insertar/actualizar solicitud-->
    <div class="modal fade" id="modalInsertarActualizarSolicitud" tabindex="false" role="dialog" aria-labelledby="myModalLabel"
    data-backdrop="static" data-keyboard="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="modalTitulo">{{modal_titulo}}</h4>
                </div>
                <div class="modal-body"> 
                    <form name="solicitudForm">
                        <div class='form-group' ng-show="accion=='insertar'">
                            <label for="sce">Servicio</label>
                            <select name="sce" id="sce"
                                ng-model="formData.sce" 
                                class="select2_single form-control" id="sce" 
                                ng-change="cambioSCE()"
                                ng-options="sce.ID as sce.NOMBRE_CLIENTE for sce in listaServicios"
                            >
							</select>
                        </div>
                        <div class='form-group' ng-show="accion=='editar'">
                            <label for="sce">Servicio</label>
                            <input class="form-control" type="text" ng-model="formData.nombre_cliente" disabled>
							</select>
                        </div>
                        <div class='form-group'>
                            <label for="auditoria">Auditoría<span class="required">*</span></label>
                            <select 
                                ng-model="formData.auditoria" 
                                ng-options="auditoria as auditoria.DESC for auditoria in listaAuditorias track by auditoria.DESC" 
                                class="form-control" id="auditoria" name="auditoria" required
                                ng-class="{ error: solicitudForm.auditoria.$error.required && !solicitudForm.$pristine}"
                            >
                            </select>
                        </div>
                        <div class='form-group'>
                            <label for="razon_social">Razón social<span class="required">*</span></label>
                            <select 
                                ng-model="formData.razon_social" 
                                ng-options="razon_social as razon_social.NOMBRE for razon_social in listaRazonesSociales track by razon_social.RFC" 
                                class="form-control" id="razon_social" name="razon_social" required
                                ng-class="{ error: solicitudForm.razon_social.$error.required && !solicitudForm.$pristine}"
                            >
                            </select>
                        </div>
                        <div class='form-group'>
                            <label for="estatus">Estatus<span class="required">*</span></label>
                            <select 
                                ng-model="formData.estatus" 
                                ng-options="estatus.ID as estatus.ESTATUS for estatus in listaEstatus" 
                                class="form-control" id="estatus" name="estatus" required
                                ng-class="{ error: solicitudForm.estatus.$error.required && !solicitudForm.$pristine}"
                            >
                            </select>
                        </div>
                        <div class='form-group'>
                            <label for="forma_pago">Forma de pago<span class="required">*</span></label>
                            <select 
                                ng-model="formData.forma_pago" 
                                ng-options="forma_pago.ID as forma_pago.NOMBRE for forma_pago in listaFormasPago" 
                                class="form-control" id="forma_pago" name="forma_pago" required
                                ng-class="{ error: solicitudForm.forma_pago.$error.required && !solicitudForm.$pristine}"
                            >
                            </select>
                        </div>
                        <div class='form-group'>
                            <label for="metodo_pago">Método de pago<span class="required">*</span></label>
                            <select 
                                ng-model="formData.metodo_pago" 
                                ng-options="metodo_pago.ID as metodo_pago.NOMBRE for metodo_pago in listaMetodosPago" 
                                class="form-control" id="forma_pago" name="forma_pago" required
                                ng-class="{ error: solicitudForm.metodo_pago.$error.required && !solicitudForm.$pristine}"
                            >
                            </select>
                        </div>
                        <div class='form-group'>
                            <label for="uso_factura">Uso de la factura<span class="required">*</span></label>
                            <select 
                                ng-model="formData.uso_factura" 
                                ng-options="uso_factura.ID as uso_factura.NOMBRE for uso_factura in listaUsosFactura" 
                                class="form-control" id="uso_factura" name="uso_factura" required
                                ng-class="{ error: solicitudForm.uso_factura.$error.required && !solicitudForm.$pristine}"
                            >
                            </select>
                        </div>
                        <div class="form-group" >
                            <label  for="monto">Monto de la factura<span class="required">*</span></label>
                            <input 
                                type="number" class="form-control" id="monto" name="monto"
                                ng-model="formData.monto" 
                                ng-class="{ error: solicitudForm.monto.$error.required && !solicitudForm.$pristine}" 
                            >
                        </div>
                        <div class="form-group">
                            <label for="orden_compra_requerida">Se requiere orden de compra?<span class="required">*</span></label>
                            <input 
                                type="checkbox" id="orden_compra_requerida" name="orden_compra_requerida"
                                ng-model="formData.orden_compra_requerida" 
                                class="form-control check-noshadow"
                            >
                        </div>
                        <div class="form-group">
                            <label for="facturar_viaticos_requerido">Se requiere facturar viáticos?<span class="required">*</span></label>
                            <input 
                                type="checkbox" id="facturar_viaticos_requerido" name="facturar_viaticos_requerido"
                                ng-model="formData.facturar_viaticos_requerido" 
                                class="form-control check-noshadow"
                            >
                        </div>
                        <div class="form-group">
                            <label for="subir_factura_portal">Se requiere cargar la factura a algún portal?<span class="required">*</span></label>
                            <input 
                                type="checkbox" id="subir_factura_portal" name="subir_factura_portal"
                                ng-model="formData.subir_factura_portal" 
                                class="form-control check-noshadow"
                            >
                        </div>
                        <div class="form-group" ng-show="formData.subir_factura_portal" >
                            <label for="portal">URL del portal<span class="required">*</span></label>
                            <input 
                                type="text" class="form-control" id="portal" name="portal"
                                ng-model="formData.portal"
                                ng-class="{ error: solicitudForm.portal.$error.required && !solicitudForm.$pristine && solicitudForm.subir_factura_portal}" 
                            >
                        </div>
                        <div class="form-group">
						    <label for="descripcion">Descripción</label>
                            <textarea 
                                rows='4' ng-model="formData.descripcion" 
                                cols='50' type='text' 
                                class="form-control" 
                            >
                            </textarea> 	
						</div>                    
                            
                        <input type="submit" class="btn btn-success pull-right mt-2" ng-click="submitForm(formData)" ng-disabled="!solicitudForm.$valid" value="Guardar"/>
                    </form>
                </div>                                  
                <div class="modal-footer">                        
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalProcesarSolicitud" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-backdrop="static" data-keyboard="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="modalTitulo">Procesar Solicitud</h4>
                </div>
                <div class="modal-body"> 
                    <div class="bs-stepper">
                        <div class="bs-stepper-header" role="tablist">
                            <!-- your steps here -->
                            <div class="step" data-target="#seleccionar_estatus">
                            <button type="button" class="step-trigger" role="tab" aria-controls="seleccionar-estatus" id="seleccionar-estatus-trigger">
                                <span class="bs-stepper-circle">1</span>
                                <span class="bs-stepper-label">Seleccionar estatus</span>
                            </button>
                            </div>
                            <div class="line"></div>
                            <div class="step" data-target="#information-part">
                            <button type="button" class="step-trigger" role="tab" aria-controls="information-part" id="information-part-trigger">
                                <span class="bs-stepper-circle">2</span>
                                <span class="bs-stepper-label">Validación</span>
                            </button>
                            </div>
                        </div>
                        <div class="bs-stepper-content">
                            <!-- your steps content here -->
                            <div id="seleccionar_estatus" class="content" role="tabpanel" aria-labelledby="seleccionar-estatus-trigger">
                                <form name="estatusForm">
                                    <div class='form-group'>
                                        <label for="edicion_estatus">Seleccione una acción</label>
                                        <select 
                                            class="form-control" required
                                            name="edicion_estatus" id="edicion_estatus"
                                            ng-model="edicion_estatus" 
                                            ng-options="estatus as estatus.label for estatus in posibles_estatus"
                                        >
                                        </select>
                                    </div>
                                    <input type="submit" class="btn btn-success pull-right mt-2" ng-click="stepperNext()" ng-disabled="!estatusForm.$valid" value="Siguiente"/> 
                                </form>
                            </div>
                            <div id="information-part" class="content" role="tabpanel" aria-labelledby="information-part-trigger">   
                                
                                <form name="emitirForm" ng-show="transition.name == 'emitir'">    
                                    <div class='form-group'>
                                        <label for="pdf">Cargar pdf</label>
                                        <input type="file" class="form-control" id="pdfFile" ng-model="pdfFile" valid-file>
                                    </div>
                                    <div class='form-group'>
                                        <label for="xml">Cargar xml</label>
                                        <input type="file" class="form-control" id="xmlFile" ng-model="xmlFile" valid-file>
                                    </div>
                                    <input type="submit" class="btn btn-success pull-right mt-2" ng-click="stepperFinish()" ng-disabled="!emitirForm.$valid" value="Finalizar"/> 
                                </form>

                                <form name="suspenderForm" ng-show="transition.name == 'suspender'">    
                                    <div class='form-group'>
                                        <label for="comentarios">Comentarios<span>*</span></label>
                                        <textarea required
                                            rows='4' ng-model="comentariosSuspension" 
                                            cols='50' type='text' 
                                            class="form-control" 
                                        >
                                        </textarea>
                                    </div>
                                    <input type="submit" class="btn btn-success pull-right mt-2" ng-click="stepperFinish()" ng-disabled="!suspenderForm.$valid" value="Finalizar"/> 
                                </form>

                                <form name="pagoParcialForm" ng-show="transition.name == 'pagoParcial'">    
                                    <div class='form-group'>
                                        <label for="pdfPagoParcialFile">Cargar pdf complemento</label>
                                        <input type="file" class="form-control" id="pdfPagoParcialFile" ng-model="pdfPagoParcialFile" valid-file>
                                    </div>
                                    <div class='form-group'>
                                        <label for="xmlPagoParcialFile">Cargar xml complemento</label>
                                        <input type="file" class="form-control" id="xmlPagoParcialFile" ng-model="xmlPagoParcialFile" valid-file>
                                    </div>
                                    <div class='form-group'>
                                        <label for="compPagoParcialFile">Cargar evidencia de pago</label>
                                        <input type="file" class="form-control" id="compPagoParcialFile" ng-model="compPagoParcialFile" valid-file>
                                    </div>
                                    <input type="submit" class="btn btn-success pull-right mt-2" ng-click="stepperFinish()" ng-disabled="!pagoParcialForm.$valid" value="Finalizar"/> 
                                </form>

                                <form name="liquidarForm" ng-show="mostrarFormProceso('liquidarForm', transition.name)">    
                                    <div class='form-group'>
                                        <label for="compLiquidarFile">Cargar evidencia de pago</label>
                                        <input type="file" class="form-control" id="compLiquidarFile" ng-model="compLiquidarFile" valid-file>
                                    </div>
                                    <input type="submit" class="btn btn-success pull-right mt-2" ng-click="stepperFinish()" ng-disabled="!liquidarForm.$valid" value="Finalizar"/> 
                                </form>

                                <form name="cancelarForm" ng-show="mostrarFormProceso('cancelarForm',transition.name)">    
                                    <div class='form-group'>
                                        <label for="pdfCancelarFile">Cargar pdf cancelación</label>
                                        <input type="file" class="form-control" id="pdfCancelarFile" ng-model="pdfCancelarFile" valid-file>
                                    </div>
                                    <div class='form-group'>
                                        <label for="xmlCancelarFile">Cargar xml cancelación</label>
                                        <input type="file" class="form-control" id="xmlCancelarFile" ng-model="xmlCancelarFile" valid-file>
                                    </div>
                                    <input type="submit" class="btn btn-success pull-right mt-2" ng-click="stepperFinish()" ng-disabled="!cancelarForm.$valid" value="Finalizar"/> 
                                </form>
                            </div>
                        </div>
                    </div>                                  
            </div>
        </div>
    </div>
    </div>

   <div class="modal fade" id="modalHistorico" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        data-backdrop="static" data-keyboard="true">
        <div class="modal-dialog" role="document" id="modal-size">
            <div class="modal-content">
                <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="modalTitulo">Histórico</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-striped responsive-utilities jambo_table bulk_action">
                        <thead>
                            <tr class="headings">
                            <th class="column-title">Cambio</th>
                            <th class="column-title">Descripción</th>
                            <th class="column-title">Fecha</th>
                            <th class="column-title">Usuario</th>
                            </tr>
                        </thead>

                        <tbody >
                            <tr ng-repeat="historico in listaHistoricos" class="ng-scope  even pointer">
                                <td>{{historico.CAMBIO}}</td>
                                <td>{{historico.DESCRIPCION}}</td>
                                <td>{{historico.FECHA +" "+ historico.HORA}}</td>
                                <td>{{historico.NOMBRE}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
   </div>
</span>
