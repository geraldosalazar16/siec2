app.controller('cat_documentos_controller',['$scope','$http' ,function($scope,$http){
	$scope.modal_titulo = "Insertar documento";
	$scope.id_documento = 0; //No hay documento seleccionado
	$scope.formData = {};
	
	$scope.agregar_documento = function(){
		$scope.modal_titulo = "Insertar documento";
		$scope.accion = "insertar";
		clear_modal_insertar_actualizar();
		$("#modalInsertarActualizar").modal("show");	
	}
	$scope.editar_documento = function(id_documento){
		$scope.modal_titulo = "Editar documento";
		$scope.accion = "editar";
		$scope.id_documento = id_documento;
		llenar_modal_insertar_actualizar();
		$("#modalInsertarActualizar").modal("show");	
	}
	function llenar_modal_insertar_actualizar(){
		var documento_obtenido = 
		$scope.Documentos.find(function(element,index,array){
            return element.ID == $scope.id_documento
        });
		$scope.formData.nombre = documento_obtenido.NOMBRE;
        $scope.formData.descripcion = documento_obtenido.DESCRIPCION;
        $scope.formData.etapa = documento_obtenido.ID_ETAPA;
        $scope.formData.seccion = documento_obtenido.ID_SECCION;
	}
	function clear_modal_insertar_actualizar(){
		$scope.id_documento = 0;
		$scope.formData.nombre = "";
        $scope.formData.descripcion = "";
        $scope.formData.etapa = "";
        $scope.formData.seccion = "";
	}
	function cargarDocumentos(){
		$http.get(  global_apiserver + "/ver_expedientes/listadoDocumentos/")
		.then(function( response ){
			$scope.Documentos = response.data;
		});
	}
	function cargarEtapas(){
		$http.get(  global_apiserver + "/etapas_proceso/getAll/")
		.then(function( response ){
			$scope.Etapas = response.data;
		});
	}
	function cargarSecciones(){
		$http.get(  global_apiserver + "/secciones/getAll/")
		.then(function( response ){
			$scope.Secciones = response.data;
		});
	}
	$scope.submitForm = function (formData) {
        //alert('Form submitted with' + JSON.stringify(formData));
        if($scope.accion == 'insertar'){
            var datos = {
                nombre: formData.nombre,
                descripcion: formData.descripcion,
                id_etapa: $scope.formData.etapa,
                id_seccion: $scope.formData.seccion
            };
            $http.post(global_apiserver + "/ver_expedientes/guardarCatDocumento/",datos).
            then(function(response){
                if(response){
					notify('Éxito','El tipo de documento se guardó correctamente','success');
                    cargarDocumentos();
                }
                else{
                    notify('Error','No se pudo guardar el tipo de documento','error');
                }
                $("#modalInsertarActualizar").modal("hide");
            });
        }
        else if($scope.accion == 'editar'){
            var datos = {
				id: $scope.id_documento,
                nombre: formData.nombre,
                descripcion: formData.descripcion,
                id_etapa: $scope.formData.etapa,
                id_seccion: $scope.formData.seccion
            };
            $http.post(global_apiserver + "/ver_expedientes/editarCatDocumento/",datos).
            then(function(response){
                if(response){
					notify('Éxito','El tipo de documento se editó correctamente','success');
                    cargarDocumentos();
                }
                else{
                    notify('Error','No se pudo guardar los cambios','error');
                }
                $("#modalInsertarActualizar").modal("hide");
            });
        }
    };
	cargarEtapas();
	cargarSecciones();
	cargarDocumentos();
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