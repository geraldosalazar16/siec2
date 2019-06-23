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
                    <div class='form-group'>
                        <label for="razon_social">Razón social<span class="required">*</span></label>
                        <select
                                ng-model="formDataSolicitud.razon_social"
                                ng-options="razon_social as razon_social.NOMBRE for razon_social in listaRazonesSociales track by razon_social.RFC"
                                class="form-control" id="razon_social" name="razon_social" required
                                ng-class="{ error: solicitudForm.razon_social.$error.required && !solicitudForm.$pristine}"
                        >
                        </select>
                    </div>
                    <div class='form-group'>
                        <label for="estatus">Estatus<span class="required">*</span></label>
                        <select
                                ng-model="formDataSolicitud.estatus"
                                ng-options="estatus.ID as estatus.ESTATUS for estatus in listaEstatus"
                                class="form-control" id="estatus" name="estatus" required
                                ng-class="{ error: solicitudForm.estatus.$error.required && !solicitudForm.$pristine}"
                        >
                        </select>
                    </div>
                    <div class='form-group'>
                        <label for="forma_pago">Forma de pago<span class="required">*</span></label>
                        <select
                                ng-model="formDataSolicitud.forma_pago"
                                ng-options="forma_pago.ID as forma_pago.NOMBRE for forma_pago in listaFormasPago"
                                class="form-control" id="forma_pago" name="forma_pago" required
                                ng-class="{ error: solicitudForm.forma_pago.$error.required && !solicitudForm.$pristine}"
                        >
                        </select>
                    </div>
                    <div class='form-group'>
                        <label for="metodo_pago">Método de pago<span class="required">*</span></label>
                        <select
                                ng-model="formDataSolicitud.metodo_pago"
                                ng-options="metodo_pago.ID as metodo_pago.NOMBRE for metodo_pago in listaMetodosPago"
                                class="form-control" id="forma_pago" name="forma_pago" required
                                ng-class="{ error: solicitudForm.metodo_pago.$error.required && !solicitudForm.$pristine}"
                        >
                        </select>
                    </div>
                    <div class='form-group'>
                        <label for="uso_factura">Uso de la factura<span class="required">*</span></label>
                        <select
                                ng-model="formDataSolicitud.uso_factura"
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
                                ng-model="formDataSolicitud.monto"
                                ng-class="{ error: solicitudForm.monto.$error.required && !solicitudForm.$pristine}"
                        >
                    </div>
                    <div class="form-group">
                        <label for="orden_compra_requerida">Se requiere orden de compra?<span class="required">*</span></label>
                        <input
                                type="checkbox" id="orden_compra_requerida" name="orden_compra_requerida"
                                ng-model="formDataSolicitud.orden_compra_requerida"
                                class="form-control check-noshadow"
                        >
                    </div>
                    <div class="form-group">
                        <label for="facturar_viaticos_requerido">Se requiere facturar viáticos?<span class="required">*</span></label>
                        <input
                                type="checkbox" id="facturar_viaticos_requerido" name="facturar_viaticos_requerido"
                                ng-model="formDataSolicitud.facturar_viaticos_requerido"
                                class="form-control check-noshadow"
                        >
                    </div>
                    <div class="form-group">
                        <label for="subir_factura_portal">Se requiere cargar la factura a algún portal?<span class="required">*</span></label>
                        <input
                                type="checkbox" id="subir_factura_portal" name="subir_factura_portal"
                                ng-model="formDataSolicitud.subir_factura_portal"
                                class="form-control check-noshadow"
                        >
                    </div>
                    <div class="form-group" ng-show="formDataSolicitud.subir_factura_portal" >
                        <label for="portal">URL del portal<span class="required">*</span></label>
                        <input
                                type="text" class="form-control" id="portal" name="portal"
                                ng-model="formDataSolicitud.portal"
                                ng-class="{ error: solicitudForm.portal.$error.required && !solicitudForm.$pristine && solicitudForm.subir_factura_portal}"
                        >
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea
                                rows='4' ng-model="formDataSolicitud.descripcion"
                                cols='50' type='text'
                                class="form-control"
                        >
                            </textarea>
                    </div>

                    <input type="submit" class="btn btn-success pull-right mt-2" ng-click="submitFormSolicitud(formDataSolicitud)" ng-disabled="!solicitudForm.$valid" value="Guardar"/>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
