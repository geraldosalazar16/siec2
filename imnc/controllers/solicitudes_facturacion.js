// =======================================================================================
// ***** Creación del controlador con el nombre "solicitudes_facturacion_controller". *****
// =======================================================================================
app.controller('solicitudes_facturacion_controller', ['$scope', '$http', function($scope, $http) {

    $scope.modal_titulo = '';
    $scope.accion = '';
    $scope.formData = {};
    $scope.idSolicitud;

    $scope.listaSolicitudes = [];
    $scope.listaServicios = [];
    $scope.listaEstatus = [];
    $scope.listaFormasPago = [];
    $scope.listaMetodosPago = [];
    $scope.listaUsosFactura = [];
    $scope.listaAuditorias = [];
    $scope.listaRazonesSociales = [];
    $scope.posibles_estados = [];

    let state_machine;
    $scope.stepper;
    $scope.transition;
    /*
    transitions: [
        { name: 'emitir', from: 'Pendiente',  to: 'Emitida'},
        { name: 'suspender', from: 'Emitida', to: 'Suspendida'},
        { name: 'vencer', from: 'Emitida', to: 'Vencida'},
        { name: 'pagoParcial', from: 'Emitida', to: 'Pagada parcialmente'},
        { name: 'liquidarEmitida', from: 'Emitida', to: 'Pagada'},
        { name: 'liquidarSuspendida', from: 'Suspendida', to: 'Pagada'},
        { name: 'liquidarVencida', from: 'Vencida', to: 'Pagada'},
        { name: 'liquidarParcial', from: 'Pagada parcialmente', to: 'Pagada'},
        { name: 'cancelarEmitida', from: 'Emitida', to: 'cancelada'},
        { name: 'cancelarSuspendida', from: 'Suspendida', to: 'Cancelada'},
        { name: 'cancelarVencida', from: 'Vencida', to: 'Cancelada'},
        { name: 'cancelarParcial', from: 'Pagada parcialmente', to: 'Cancelada'},
      ]
      */
    const transitions_labels = [
        { name: 'emitir', label: 'Emitir', form: 'emitirForm'},
        { name: 'suspender', label: 'Suspender', form: 'suspenderForm'},
        { name: 'pagoParcial', label: 'Pago Parcial', form: 'pagoParcialForm'},
        { name: 'liquidarEmitida', label: 'Liquidar', form: 'pagoParcialForm'},
        { name: 'liquidarSuspendida', label: 'Liquidar', form: 'liquidarForm'},
        { name: 'liquidarVencida', label: 'Liquidar', form: 'liquidarForm'},
        { name: 'liquidarParcial', label: 'Liquidar', form: 'liquidarForm'},
        { name: 'cancelarEmitida', label: 'Cancelar', form: 'cancelarForm'},
        { name: 'cancelarSuspendida', label: 'Cancelar', form: 'cancelarForm'},
        { name: 'cancelarVencida', label: 'Cancelar', form: 'cancelarForm'},
        { name: 'cancelarParcial', label: 'Cancelar', form: 'cancelarForm'}
    ];

    // Abrir modal insertar/actualizar
    $scope.abrirModalCrearSolicitud = function() {
        $scope.modal_titulo = "Insertar solicitud";
        $scope.accion = "insertar";
        clear_modal_insertar_actualizar();
        $("#modalInsertarActualizarSolicitud").modal("show");
    }

    $scope.abrirModalEditarSolicitud = async function(id) {
        $scope.idSolicitud = id;
        $scope.modal_titulo = "Editar solicitud";
        $scope.accion = "editar";
        await fill_modal_insertar_actualizar();
        $("#modalInsertarActualizarSolicitud").modal("show");
    }

    function clear_modal_insertar_actualizar() {
        $scope.formData = {};
    }

    async function fill_modal_insertar_actualizar() {
        try {
            let response = await $http.get(`${global_apiserver}/facturacion_solicitudes/getById?id=${$scope.idSolicitud}`);
            const solicitud = response.data;
            $scope.formData.id = solicitud.ID;
            $scope.formData.id_sce = solicitud.ID_SERVICIO_CLIENTE_ETAPA;
            response = await $http.get(`${global_apiserver}/servicio_cliente_etapa/getById?id=${$scope.formData.id_sce}`);
            $scope.formData.nombre_cliente = response.data.NOMBRE_CLIENTE;
            // $("#sce").select2('data', {id: $scope.formData.id_sce, text: response.data.NOMBRE_CLIENTE}).trigger("change");
            // $("#sce").val(`string:"${$scope.formData.id_sce}"`);
            await cambioServicio($scope.formData.id_sce);
            await buscarAuditoria(solicitud);
            await buscarRazonSocial(solicitud); 
            $scope.formData.estatus = solicitud.ID_ESTATUS;  
            $scope.formData.forma_pago = solicitud.ID_FORMA_PAGO;  
            $scope.formData.metodo_pago = solicitud.ID_METODO_PAGO;  
            $scope.formData.uso_factura = solicitud.ID_USO_FACTURA;
            $scope.formData.monto = parseFloat(solicitud.MONTO); 
            if (solicitud.REQUIERE_ORDEN_COMPRA === 'S') {
                $scope.formData.orden_compra_requerida = true;
            } else {
                $scope.formData.orden_compra_requerida = false;
            }
            if (solicitud.FACTURAR_VIATICOS === 'S') {
                $scope.formData.facturar_viaticos_requerido = true;
            } else {
                $scope.formData.facturar_viaticos_requerido = false;
            }
            if (solicitud.SUBIR_FACTURA_PORTAL === 'S') {
                $scope.formData.subir_factura_portal = true;
                $scope.formData.portal = solicitud.PORTAL;
            } else {
                $scope.formData.subir_factura_portal = false;
            }
            $scope.formData.descripcion = solicitud.DESCRIPCION;
            $scope.$apply()
        } catch(error) {
            notify('Error', error.message, 'error');
        }
    }

    async function buscarAuditoria(solicitud) {
        $scope.formData.auditoria = $scope.listaAuditorias.find(auditoria => {
            return auditoria.TIPO_AUDITORIA === solicitud.ID_TIPO_AUDITORIA && auditoria.CICLO === solicitud.CICLO;
        });
        // $scope.$apply();
    }

    async function buscarRazonSocial(solicitud) {
        $scope.formData.razon_social = $scope.listaRazonesSociales.find(rs => {
            return rs.RFC === solicitud.RFC;
        });
        // $scope.$apply();
    }

    $('#sce').on('select2:select', async function (evt) {
        $scope.formData.id_sce = $("#sce").val().substring(7);
        await cambioServicio($scope.formData.id_sce);
    });
    
    async function cambioServicio(id_sce) {
        $scope.listaAuditorias = [];
        $scope.listaRazonesSociales = [];
		
        // Obtener todas las auditorias del servicio
        let response = await $http.get(`${global_apiserver}/i_sg_auditorias/getAllByIdServicio?id=${id_sce}`);
        if (response.data.resultado === 'error') {
            notify('Error', response.data.mensaje, 'error');
        } else {
            // Agregar descripción a cada auditoria
            response.data.forEach(aud => {
                aud.DESC = `Ciclo ${aud.CICLO} ${aud.TIPO}`;
                $scope.listaAuditorias.push(aud);
            });
            // Obtener las razones sociales del cliente asociado
            response = await $http.get(`${global_apiserver}/servicio_cliente_etapa/getById?id=${id_sce}`);
            if (response.data.resultado === 'error') {
                notify('Error', response.data.mensaje, 'error');
            } else {
                const id_cliente = response.data.ID_CLIENTE;
                // Obtener todas las razones sociales del cliente
                response = await $http.get(`${global_apiserver}/i_clientes_razones_sociales/getByIdCliente?id=${id_cliente}`);
                if (response.data.resultado === 'error') {
                    notify('Error', response.data.mensaje, 'error');
                } else {
                    $scope.listaRazonesSociales = response.data;
                }
            }
            $scope.$apply();
        }
    }

    $scope.verDetallesSolicitud = function(solicitud) {
        
    }

    $scope.verHistoricoSolicitud = function(solicitud) {
        
    }

    $scope.verDocumentosSolicitud = function(solicitud) {
        
    }

    $scope.agregarComplementosSolicitud = function(solicitud) {
        
    }

    $scope.procesarSolicitud = function(solicitud) {
        try {
           $scope.solicitudActual = solicitud;
            // Limpiar componentes
            $scope.edicion_estatus = '';
            $scope.stepper.reset();
            const estatus = inicializarStateMachine(solicitud);
            // Para poder mostrar textos descriptivos en el select
            cargarEstatus(estatus);
            $("#modalProcesarSolicitud").modal("show");
        } catch (error) {
            notify('Error', error.message, 'error');
        }
    }

    function cargarEstatus(estatus) {
        $scope.posibles_estatus = [];
        estatus.forEach(estatus => {
            transitions_labels.forEach(transaction => {
                if (transaction.name === estatus) {
                    $scope.posibles_estatus.push(transaction);
                }
            });
        });
        console.log($scope.posibles_estatus);
    }

    function inicializarStateMachine(solicitud) {
        // https://github.com/jakesgordon/javascript-state-machine
        state_machine = new StateMachine({
            init: solicitud.ESTATUS,
            transitions: [
              { name: 'emitir', from: 'Pendiente',  to: 'Emitida'},
              { name: 'suspender', from: 'Emitida', to: 'Suspendida'},
              { name: 'vencer', from: 'Emitida', to: 'Vencida'},
              { name: 'pagoParcial', from: 'Emitida', to: 'Pagada parcialmente'},
              { name: 'liquidarEmitida', from: 'Emitida', to: 'Pagada'},
              { name: 'liquidarSuspendida', from: 'Suspendida', to: 'Pagada'},
              { name: 'liquidarVencida', from: 'Vencida', to: 'Pagada'},
              { name: 'liquidarParcial', from: 'Pagada parcialmente', to: 'Pagada'},
              { name: 'cancelarEmitida', from: 'Emitida', to: 'cancelada'},
              { name: 'cancelarSuspendida', from: 'Suspendida', to: 'Cancelada'},
              { name: 'cancelarVencida', from: 'Vencida', to: 'Cancelada'},
              { name: 'cancelarParcial', from: 'Pagada parcialmente', to: 'Cancelada'},
            ],
            methods: {
              onEmitir:   function() { console.log('Emitida') },
              onSuspender:   function() { console.log('Suspendida')     },
              onVencer: function() { console.log('Vencida') },
              onPagoParcial: function() { console.log('Pagada Parcialmente') },
              onLiquidarEmitida: function() { console.log('Emitida Liquidada') },
              onCancelarEmitida: function() { console.log('Emitida pagada') }
            }
        });
        return state_machine.transitions();
    }

    $scope.stepperNext = function() {
        $scope.transition = $scope.edicion_estatus;
        if($scope.transition.name === 'emitir') {
            $('#pdfFile').val('');
            $('#xmlFile').val('');
        } else if ($scope.transition.name === 'emitir') {
            $scope.comentariosSuspension = '';
        } else if ($scope.transition.name === 'pagoParcial') {
            $('#pdfPagoParcialFile').val('');
            $('#xmlPagoParcialFile').val('');
            $('#compPagoParcialFile').val('');
        }
        if ($scope.transition.name.toLowerCase().includes('liquidar')) {
            $('#compLiquidarFile').val('');
        }
        if ($scope.transition.name.toLowerCase().includes('cancelar')) {
            $('#pdfCancelar').val('');
            $('#xmlCancelar').val('');
        }
        $scope.stepper.next();
    }

    $scope.stepperFinish = async function() {
        try {
            let estatus;
            let data;
            let response;
            switch ($scope.transition.name) {
                case 'emitir':
                    estatus = buscarIdEstatus('Emitida');
                    data = {
                        id_solicitud: $scope.solicitudActual.ID,
                        estatus_anterior: $scope.solicitudActual.ID_ESTATUS,
                        nuevo_estatus: estatus.ID,
                        descripcion: '',
                        id_usuario: sessionStorage.getItem("id_usuario")
                    }
                    response = await $http.post(`${global_apiserver}/facturacion_solicitudes/cambiarEstatus/`, data);
                    if (response.data.resultado === 'ok') {
                        // Guardar documentos
                        let formdata = new FormData();
                        const pdf = document.getElementById("pdfFile").files[0];
                        const xml = document.getElementById("xmlFile").files[0];
                        formdata.append(pdf.name, pdf);
                        formdata.append(xml.name, xml);
                        formdata.append('tipo_documento', 'FACTURA');
                        formdata.append('id_solicitud', $scope.solicitudActual.ID);
                        formdata.append('id_usuario', sessionStorage.getItem("id_usuario"));
                        let configuracion = {
                            headers: {
                                "Content-Type": undefined,
                            },
                            transformRequest: angular.identity,
                        };
                        response = await $http.post(`${global_apiserver}/facturacion_solicitud_documentos/insert/`, formdata, configuracion);
                        if (response.data.resultado === 'ok') {
                            listarSolicitudes();
                            notify('Éxito', 'Se ha actualizado el estatus de la solicitud correctamente', 'success');
                            $("#modalProcesarSolicitud").modal("hide");
                        } else {
                            notify('Error', response.data.mensaje, 'error');
                        }
                    } else {
                        notify('Error', response.data.mensaje, 'error');
                    }                
                    break;
                case 'suspender':
                    // Cambiar estatus a suspendida
                    estatus = buscarIdEstatus('Suspendida');
                    data = {
                        id_solicitud: $scope.solicitudActual.ID,
                        estatus_anterior: $scope.solicitudActual.ID_ESTATUS,
                        nuevo_estatus: estatus.ID,
                        descripcion: $scope.comentariosSuspension,
                        id_usuario: sessionStorage.getItem("id_usuario")
                    }
                    response = await $http.post(`${global_apiserver}/facturacion_solicitudes/cambiarEstatus/`, data);
                    if (response.data.resultado === 'ok') {
                        listarSolicitudes();
                        notify('Éxito', 'Se ha actualizado el estatus de la solicitud correctamente', 'success');
                        $("#modalProcesarSolicitud").modal("hide");
                    } else {
                        notify('Error', response.data.mensaje, 'error');
                    }
                    break;
                case 'pagoParcial':
                    estatus = buscarIdEstatus('Pagada parcialmente');
                    data = {
                        id_solicitud: $scope.solicitudActual.ID,
                        estatus_anterior: $scope.solicitudActual.ID_ESTATUS,
                        nuevo_estatus: estatus.ID,
                        descripcion: '',
                        id_usuario: sessionStorage.getItem("id_usuario")
                    }
                    response = await $http.post(`${global_apiserver}/facturacion_solicitudes/cambiarEstatus/`, data);
                    if (response.data.resultado === 'ok') {
                        // Guardar documentos
                        let formdata = new FormData();
                        const pdfPagoParcial = document.getElementById("pdfPagoParcialFile").files[0];
                        const xmlPagoParcial = document.getElementById("xmlPagoParcialFile").files[0];
                        const compPagoParcial = document.getElementById("compPagoParcialFile").files[0];
                        formdata.append(pdfPagoParcial.name, pdfPagoParcial);
                        formdata.append(xmlPagoParcial.name, xmlPagoParcial);
                        formdata.append(compPagoParcial.name, compPagoParcial);
                        formdata.append('tipo_documento', 'COMPLEMENTO');
                        formdata.append('id_solicitud', $scope.solicitudActual.ID);
                        formdata.append('id_usuario', sessionStorage.getItem("id_usuario"));
                        let configuracion = {
                            headers: {
                                "Content-Type": undefined,
                            },
                            transformRequest: angular.identity,
                        };
                        response = await $http.post(`${global_apiserver}/facturacion_solicitud_documentos/insert/`, formdata, configuracion);
                        if (response.data.resultado === 'ok') {
                            listarSolicitudes();
                            notify('Éxito', 'Se ha actualizado el estatus de la solicitud correctamente', 'success');
                            $("#modalProcesarSolicitud").modal("hide");
                        } else {
                            notify('Error', response.data.mensaje, 'error');
                        }
                    } else {
                        notify('Error', response.data.mensaje, 'error');
                    }                
                    break;
                case 'liquidarEmitida':
                    liquidarFactura();              
                    break;
                case 'cancelarEmitida':
                    cancelarFactura();              
                    break;
                case 'cancelarSuspendida':
                    cancelarFactura();              
                    break;
                case 'cancelarVencida':
                    cancelarFactura();              
                    break;
                case 'cancelarPagada':
                    cancelarFactura();              
                    break;
                case 'liquidarSuspendida':
                    liquidarFactura();              
                    break;
                case 'liquidarVencida':
                    liquidarFactura();              
                    break;
                case 'liquidarVencida':
                    liquidarFactura();              
                    break;
                case 'liquidarParcial':
                    liquidarFactura();              
                    break;
                default:
                    break;
            }
        } catch (error) {
            notify('Error', error.message, 'error');
        }
    }

    function buscarIdEstatus(nombreEstatus) {
        return $scope.listaEstatus.find(estatus => estatus.ESTATUS === nombreEstatus);
    }

    async function liquidarFactura() {
        const estatus = buscarIdEstatus('Pagada');
        const data = {
            id_solicitud: $scope.solicitudActual.ID,
            estatus_anterior: $scope.solicitudActual.ID_ESTATUS,
            nuevo_estatus: estatus.ID,
            descripcion: '',
            id_usuario: sessionStorage.getItem("id_usuario")
        }
        let response = await $http.post(`${global_apiserver}/facturacion_solicitudes/cambiarEstatus/`, data);
        if (response.data.resultado === 'ok') {
            // Guardar documentos
            let formdata = new FormData();
            const compLiquidar = document.getElementById("compLiquidarFile").files[0];
            formdata.append(compLiquidar.name, compLiquidar);
            formdata.append('tipo_documento', 'COMPROBANTE_PAGO_LIQUIDACION');
            formdata.append('id_solicitud', $scope.solicitudActual.ID);
            formdata.append('id_usuario', sessionStorage.getItem("id_usuario"));
            let configuracion = {
                headers: {
                    "Content-Type": undefined,
                },
                transformRequest: angular.identity,
            };
            response = await $http.post(`${global_apiserver}/facturacion_solicitud_documentos/insert/`, formdata, configuracion);
            if (response.data.resultado === 'ok') {
                listarSolicitudes();
                notify('Éxito', 'Se ha actualizado el estatus de la solicitud correctamente', 'success');
                $("#modalProcesarSolicitud").modal("hide");
            } else {
                notify('Error', response.data.mensaje, 'error');
            }
        } else {
            notify('Error', response.data.mensaje, 'error');
        }   
    }

    async function cancelarFactura() {
        const estatus = buscarIdEstatus('Cancelada');
        const data = {
            id_solicitud: $scope.solicitudActual.ID,
            estatus_anterior: $scope.solicitudActual.ID_ESTATUS,
            nuevo_estatus: estatus.ID,
            descripcion: '',
            id_usuario: sessionStorage.getItem("id_usuario")
        }
        let response = await $http.post(`${global_apiserver}/facturacion_solicitudes/cambiarEstatus/`, data);
        if (response.data.resultado === 'ok') {
            // Guardar documentos
            let formdata = new FormData();
            const pdfCancelar = document.getElementById("pdfCancelarFile").files[0];
            const xmlCancelar = document.getElementById("xmlCancelarFile").files[0];
                        
            formdata.append(pdfCancelar.name, pdfCancelar);
            formdata.append(xmlCancelar.name, xmlCancelar);
            formdata.append('tipo_documento', 'CANCELACION');
            formdata.append('id_solicitud', $scope.solicitudActual.ID);
            formdata.append('id_usuario', sessionStorage.getItem("id_usuario"));
            let configuracion = {
                headers: {
                    "Content-Type": undefined,
                },
                transformRequest: angular.identity,
            };
            response = await $http.post(`${global_apiserver}/facturacion_solicitud_documentos/insert/`, formdata, configuracion);
            if (response.data.resultado === 'ok') {
                listarSolicitudes();
                notify('Éxito', 'Se ha actualizado el estatus de la solicitud correctamente', 'success');
                $("#modalProcesarSolicitud").modal("hide");
            } else {
                notify('Error', response.data.mensaje, 'error');
            }
        } else {
            notify('Error', response.data.mensaje, 'error');
        } 
    }

    $scope.mostrarFormProceso = function(form, transitionName) {
        for (const transition of transitions_labels) {
            if (transition.name === transitionName) {
                if (transition.form === form) {
                    return true;
                }
            }
        }
        return false;
    }

    $scope.submitForm = function() {
        $scope.formData.id_usuario = sessionStorage.getItem("id_usuario");
        if ($scope.accion === 'insertar') {
            $http.post(`${global_apiserver}/facturacion_solicitudes/insert/`, $scope.formData)
            .then(response => {
                if (response.data.resultado === 'error') {
                    notify('Error', response.data.mensaje, 'error')
                } else {
                    notify('Éxito', 'Se ha creado una nueva solicitud', 'success');
                    $("#modalInsertarActualizarSolicitud").modal("hide");
                    listarSolicitudes();
                }
            })
            .catch(error => notify('Error', error.message, 'error'))
        } else if ($scope.accion === 'editar') {
            $http.post(`${global_apiserver}/facturacion_solicitudes/update/`, $scope.formData)
            .then(response => {
                if (response.data.resultado === 'error') {
                    notify('Error', response.data.mensaje, 'error')
                } else {
                    notify('Éxito', 'Se ha modificado la solicitud', 'success');
                    $("#modalInsertarActualizarSolicitud").modal("hide");
                    listarSolicitudes();
                }
            })
            .catch(error => notify('Error', error.message, 'error'))
        }
    }

    // Listar todas las solicitudes
    function listarSolicitudes() {
        $http.get(`${global_apiserver}/facturacion_solicitudes/getAll/`)
        .then(response => {
            if (response.data.resultado === 'error') {
                notify('Error', response.data.mensaje, 'error')
            } else {
                $scope.listaSolicitudes = response.data;
            }
        })
        .catch(error => notify('Error', error.message, 'error'))
    }

    // Listar todas las servicios (SCE)
    function listarServicios() {
        $http.get(`${global_apiserver}/servicio_cliente_etapa/getAll/`)
        .then(response => {
            if (response.data.resultado === 'error') {
                notify('Error', response.data.mensaje, 'error')
            } else {
                $scope.listaServicios = response.data;
            }
        })
        .catch(error => notify('Error', error.message, 'error'))
    }

    // Listar los estatus
    function listarEstatus() {
        $http.get(`${global_apiserver}/facturacion_solicitudes_estatus/getAll/`)
        .then(response => {
            if (response.data.resultado === 'error') {
                notify('Error', response.data.mensaje, 'error')
            } else {
                $scope.listaEstatus = response.data;
            }
        })
        .catch(error => notify('Error', error.message, 'error'))
    }

    // Listar las formas de pago
    function listarFormasPago() {
        $http.get(`${global_apiserver}/facturacion_forma_pago/getAll/`)
        .then(response => {
            if (response.data.resultado === 'error') {
                notify('Error', response.data.mensaje, 'error')
            } else {
                $scope.listaFormasPago = response.data;
            }
        })
        .catch(error => notify('Error', error.message, 'error'))
    }

    // Listar los métodos de pago
    function listarMetodosPago() {
        $http.get(`${global_apiserver}/facturacion_metodos_pago/getAll/`)
        .then(response => {
            if (response.data.resultado === 'error') {
                notify('Error', response.data.mensaje, 'error')
            } else {
                $scope.listaMetodosPago = response.data;
            }
        })
        .catch(error => notify('Error', error.message, 'error'))
    }

    // Listar los usos de la factura
    function listarUsosFactura() {
        $http.get(`${global_apiserver}/facturacion_uso_factura/getAll/`)
        .then(response => {
            if (response.data.resultado === 'error') {
                notify('Error', response.data.mensaje, 'error')
            } else {
                $scope.listaUsosFactura = response.data;
            }
        })
        .catch(error => notify('Error', error.message, 'error'))
    }

    function inicializacion() {
        $('#sce').select2();
    }

    $(document).ready(function () {
        $scope.stepper = new Stepper($('.bs-stepper')[0]);
    });

    // Entry point
    inicializacion();
    listarServicios();
    listarEstatus()
    listarFormasPago();
    listarMetodosPago();
    listarUsosFactura();
    listarSolicitudes();
}]);



// ================================================================================
// *****                       Funciones de uso común                         *****
// ================================================================================

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

