app.controller('serv_contrat_tipos_cambios_controller',['$scope','$http' ,function($scope,$http){
$scope.titulo = "Tipos de cambios para servicios contratados";
	$scope.modal_titulo = "Insertar tipo de cambio";
	$scope.id_cambio = 0; //No hay documento seleccionado
	$scope.formData = {};
	
	$scope.agregar_cambio = function(){
		$scope.modal_titulo = "Insertar tipo de cambio";
		$scope.accion = "insertar";
		clear_modal_insertar_actualizar();
		$("#modalInsertarActualizar").modal("show");	
	}
	$scope.editar_cambio = function(id_cambio){
		$scope.modal_titulo = "Insertar tipo de cambio";
		$scope.accion = "editar";
		$scope.id_cambio = id_cambio;
		llenar_modal_insertar_actualizar();
		$("#modalInsertarActualizar").modal("show");	
	}
	function llenar_modal_insertar_actualizar(){
		var cambio_obtenido = 
		$scope.Tipos_Cambios.find(function(element,index,array){
            return element.ID == $scope.id_cambio
        });
		$scope.formData.nombre = cambio_obtenido.NOMBRE;
        $scope.formData.descripcion = cambio_obtenido.DESCRIPCION;
        
	}
	function clear_modal_insertar_actualizar(){
		$scope.id_cambio = 0;
		$scope.formData.nombre = "";
        $scope.formData.descripcion = "";
        
	}
	function cargarCambios(){
		$http.get(  global_apiserver + "/i_servicios_contratados_tipos_cambios/getAll/")
		.then(function( response ){
			$scope.Tipos_Cambios = response.data;
		});
	}

	$scope.submitForm = function (formData) {
        //alert('Form submitted with' + JSON.stringify(formData));
        if($scope.accion == 'insertar'){
            var datos = {
                NOMBRE: formData.nombre,
                DESCRIPCION: formData.descripcion,
               
            };
            $http.post(global_apiserver + "/i_servicios_contratados_tipos_cambios/insert/",datos).
            then(function(response){
                if(response){
					notify('Éxito','El tipo de cambio se guardó correctamente','success');
                    cargarCambios();
                }
                else{
                    notify('Error','No se pudo guardar el tipo de cambio','error');
                }
                $("#modalInsertarActualizar").modal("hide");
            });
        }
        else if($scope.accion == 'editar'){
            var datos = {
				ID: $scope.id_cambio,
                NOMBRE: formData.nombre,
                DESCRIPCION: formData.descripcion,
                
            };
            $http.post(global_apiserver + "/i_servicios_contratados_tipos_cambios/update/",datos).
            then(function(response){
			
                if(response){
					notify('Éxito','El tipo de cambio se editó correctamente','success');
                    cargarCambios();
                }
                else{
                    notify('Error','No se pudo guardar los cambios','error');
                }
                $("#modalInsertarActualizar").modal("hide");
            });
        }
    };
	
	cargarCambios();
}]);
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