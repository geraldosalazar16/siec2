/**
 * @ngdoc controller
 * @name controller:uso_factura
 *
 * @description
 *
 *
 * @requires $scope
 * */
app.controller('uso_factura_controller',['$scope','$http',function($scope,$http){
    $scope.formData = {};
    $scope.formas_pagos = [];

// ===================================================================
// ***** 		FUNCION PARA CARGAR LAS USO FACTURA    	 *****
// ===================================================================
    function loadUsoFacturas(){
        $http.get(  global_apiserver + "/uso_factura/getAll/")
            .then(function( response ){
                $scope.uso_facturas = response.data;
            });
    }
// =======================================================================================
// ***** 		            ACCION BOTON AGREGAR USO FACTURA                      *****
// =======================================================================================
    $scope.onAgregar = function(){
        openModalUsoFactura();
}
// =======================================================================================
// ***** 		            ACCION BOTON AGREGAR USO FACTURA                       *****
// =======================================================================================
    $scope.onEditar = function(id){
        openModalUsoFactura(id);
    }
// =======================================================================================
// ***** 		   FUNCION PARA ABRIR MODAL INSERTAR UPDATE USO FACTURA             *****
// =======================================================================================
    async function openModalUsoFactura(id) {
        $scope.modal_titulo = "Agregar Uso del Factura";
        $scope.accion = "insertar";
        clear_modal_insertar_actualizar();
        if (id) {
            $scope.accion = "editar";
            $scope.modal_titulo = "Modificando Uso del Factura";
            try {
                let response = await $http.get(`${global_apiserver}/uso_factura/getById/?id=${id}`);
                const forma_pago = response.data;
                $scope.formData.ID = forma_pago.ID;
                $scope.formData.CLAVE = forma_pago.CLAVE;
                $scope.formData.NOMBRE = forma_pago.NOMBRE;
                $scope.$apply();

            } catch (error) {
                notify('Error', error.message, 'error');
            }


        }
        $("#modalInsertarActualizar").modal("show");
    }
// ===========================================================================
// ***** 		Funcion para limpiar las variables del modal			 *****
// ===========================================================================
    function clear_modal_insertar_actualizar(){
        $scope.formData = {};
    }
// =======================================================================================
// *****     Función para validar los campos del formulario antes de Guardar		 *****
// =======================================================================================
        function validar_formulario() {
            $scope.respuesta = 1;
            var setfocus = null;
                if (typeof $scope.formData.NOMBRE !== "undefined") {
                    if ($scope.formData.NOMBRE.length == 0) {
                        $scope.respuesta = 0;
                        $scope.error_nombre = "Complete este campo";
                        setfocus = "NOMBRE";
                    } else {
                        $scope.error_nombre = "";
                    }
                } else {
                    $scope.respuesta = 0;
                    $scope.error_nombre = "Complete este campo";
                    setfocus = "NOMBRE";
                }
                if (typeof $scope.formData.CLAVE !== "undefined") {
                    if ($scope.formData.CLAVE.length == 0) {
                        $scope.respuesta = 0;
                        $scope.error_clave = "Complete este campo";
                        setfocus = "CLAVE";
                    } else {
                        $scope.error_clave = "";
                    }
                } else {
                    $scope.respuesta = 0;
                    $scope.error_clave = "Complete este campo";
                    setfocus = "CLAVE";
                }

            if(setfocus != null)
            {
                $('#'+setfocus).focus();
            }
        }
// ===========================================================================
// ***** 			FUNCION PARA EL BOTON GUARDAR DEL MODAL				 *****
// ===========================================================================
        $scope.submitForm = function () {

            validar_formulario();
            if($scope.respuesta == 1){
                if($scope.accion == "insertar")
                {
                    insertar();
                }
                if($scope.accion == "editar")
                {
                    editar();
                }
            }
        }
// ===========================================================================
// ***** 			FUNCION PARA INSERTAR NUEVO USO FACTURA			 *****
// ===========================================================================
        function insertar()
        {
            $scope.formData.id_usuario = sessionStorage.getItem("id_usuario");
            $http.post(`${global_apiserver}/uso_factura/insert/`, $scope.formData)
                .then(response => {
                    if (response.data.resultado === 'error') {
                        notify('Error', response.data.mensaje, 'error')
                    } else {
                        notify('Éxito', 'Se ha agregado un nuevo uso del la factura', 'success');
                        loadUsoFacturas();
                        $("#modalInsertarActualizar").modal("hide");
                    }
                })
                .catch(error => notify('Error', error.message, 'error'))
        }

// ===========================================================================
// ***** 			FUNCION PARA INSERTAR NUEVO USO FACTURA     		 *****
// ===========================================================================
    function editar()
    {
        $scope.formData.id_usuario = sessionStorage.getItem("id_usuario");
        $http.post(`${global_apiserver}/uso_factura/update/`, $scope.formData)
            .then(response => {
                if (response.data.resultado === 'error') {
                    notify('Error', response.data.mensaje, 'error')
                } else {
                    notify('Éxito', 'Se ha modificado el uso del la factura', 'success');
                    loadUsoFacturas();
                    $("#modalInsertarActualizar").modal("hide");

                }
            })
            .catch(error => notify('Error', error.message, 'error'))
    }

//============================================================================================
    $(document).ready(function () {
        loadUsoFacturas();
 
});
	
}]);

function notify(titulo, texto, tipo){
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

