/**
 * @ngdoc controller
 * @name controller:metodos_programados
 *
 * @description
 *
 *
 * @requires $scope
 * */
app.controller('metodos_pago_controller',['$scope','$http',function($scope,$http){
    $scope.formData = {};
    $scope.metodos_pagos = [];

// ===================================================================
// ***** 		FUNCION PARA CARGAR LAS METODO DE PAGO      	 *****
// ===================================================================
    function loadMetodosPago(){
        $http.get(  global_apiserver + "/metodos_pago/getAll/")
            .then(function( response ){
                $scope.metodos_pagos = response.data;
            });
    }
// =======================================================================================
// ***** 		            ACCION BOTON AGREGAR METODO DE PAGO                       *****
// =======================================================================================
    $scope.onAgregar = function(){
        openModalMetodosPago();
}
// =======================================================================================
// ***** 		            ACCION BOTON AGREGAR METODO DE PAGO                       *****
// =======================================================================================
    $scope.onEditar = function(id){
        openModalMetodosPago(id);
    }
// =======================================================================================
// ***** 		   FUNCION PARA ABRIR MODAL INSERTAR UPDATE METODO PAGO             *****
// =======================================================================================
    async function openModalMetodosPago(id) {
        $scope.modal_titulo = "Agregar Método de Pago";
        $scope.accion = "insertar";
        clear_modal_insertar_actualizar();
        if (id) {
            $scope.accion = "editar";
            $scope.modal_titulo = "Modificando Método de Pago";
            try {
                let response = await $http.get(`${global_apiserver}/metodos_pago/getById/?id=${id}`);
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
// ***** 			FUNCION PARA INSERTAR NUEVA METODO DE PAGO			 *****
// ===========================================================================
        function insertar()
        {
            $scope.formData.id_usuario = sessionStorage.getItem("id_usuario");
            $http.post(`${global_apiserver}/metodos_pago/insert/`, $scope.formData)
                .then(response => {
                    if (response.data.resultado === 'error') {
                        notify('Error', response.data.mensaje, 'error')
                    } else {
                        notify('Éxito', 'Se ha agregado un nuevo método de pago', 'success');
                        loadMetodosPago();
                        $("#modalInsertarActualizar").modal("hide");
                    }
                })
                .catch(error => notify('Error', error.message, 'error'))
        }

// ===========================================================================
// ***** 			FUNCION PARA INSERTAR NUEVA METODO DE PAGO			 *****
// ===========================================================================
    function editar()
    {
        $scope.formData.id_usuario = sessionStorage.getItem("id_usuario");
        $http.post(`${global_apiserver}/metodos_pago/update/`, $scope.formData)
            .then(response => {
                if (response.data.resultado === 'error') {
                    notify('Error', response.data.mensaje, 'error')
                } else {
                    notify('Éxito', 'Se ha modificado la metodos de pago', 'success');
                    loadMetodosPago();
                    $("#modalInsertarActualizar").modal("hide");

                }
            })
            .catch(error => notify('Error', error.message, 'error'))
    }

//============================================================================================
    $(document).ready(function () {
        loadMetodosPago();
 
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

