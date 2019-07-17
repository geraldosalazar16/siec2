app.controller('cat_documentos_controller',['$scope','$http' ,function($scope,$http){
	$scope.modal_titulo = "Insertar documento";
	$scope.id_documento = 0; //No hay documento seleccionado
	$scope.formData = {};
	$scope.filtroServ = '';
	$scope.filtroTS = '';
	$scope.filtroEtapa = '';
	$scope.filtroSecciones = '';
	$scope.agregar_documento = function(){
		$scope.modal_titulo = "Insertar documento";
		$scope.accion = "insertar";
		clear_modal_insertar_actualizar();
		$scope.formData.servicio= $scope.filtroServ ;
		$scope.formData.tiposServicio= $scope.filtroTS ;
		$scope.formData.etapa = $scope.filtroEtapa;
        $scope.formData.seccion = $scope.filtroSecciones;
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
		$scope.formData.tiposServicio = documento_obtenido.ID_TIPO_SERVICIO;
		$scope.formData.servicio= documento_obtenido.ID_SERVICIO;
	}
	function clear_modal_insertar_actualizar(){
		$scope.id_documento = 0;
		$scope.formData.nombre = "";
        $scope.formData.descripcion = "";
		$scope.formData.servicio = "";
		$scope.formData.tiposServicio = "";
        $scope.formData.etapa = "";
        $scope.formData.seccion = "";
	}
	function cargarDocumentos(){
		if($scope.filtroServ == null){$scope.filtroServ='';}
		if($scope.filtroTS == null){$scope.filtroTS='';}
		if($scope.filtroEtapa == null){$scope.filtroEtapa='';}
		if($scope.filtroSecciones == null){$scope.filtroSecciones='';}
		
		$http.get(  global_apiserver + "/ver_expedientes/listadoDocumentos/?id_serv="+$scope.filtroServ+"&id_ts="+$scope.filtroTS+"&etapa="+$scope.filtroEtapa+"&seccion="+$scope.filtroSecciones)
		.then(function( response ){
			$scope.Documentos = response.data;
		});
	}
	function cargarEtapas(){
		if($scope.filtroServ == ''){
			$http.get(  global_apiserver + "/etapas_proceso/getAll/")
				.then(function( response ){
				$scope.Etapas = response.data;
			});
		}
		else{
			$http.get(  global_apiserver + "/etapas_proceso/getByIdServicio/?id="+$scope.filtroServ)
				.then(function( response ){
				$scope.Etapas = response.data;
			});
		}
		
	}
	function cargarSecciones(){
		$http.get(  global_apiserver + "/secciones/getAll/")
		.then(function( response ){
			$scope.Secciones = response.data;
		});
	}
	function cargarServicios(){	
		$http.get(  global_apiserver + "/servicios/getAll/")
		.then(function( response ){
			$scope.Servicios = response.data;
		});
		
	}
	function cargartiposServicios(){
		if($scope.filtroServ == ''){
			$http.get(  global_apiserver + "/tipos_servicio/getAll/")
				.then(function( response ){
				$scope.tiposServicios = response.data;
			});
		}
		else{
			$http.get(  global_apiserver + "/tipos_servicio/getByService/?id="+$scope.filtroServ)
				.then(function( response ){
				$scope.tiposServicios = response.data;
			});
		}
		
	}
	$scope.cambioServicio= function(){
		cargartiposServicios();
		cargarEtapas();
		cargarDocumentos();
	}
	$scope.cambiotiposServicio= function(){
		
		cargarDocumentos();
	}
	$scope.cambioSeccion= function(){
		
		cargarDocumentos();
	}
	$scope.cambioEtapa= function(){
		
		cargarDocumentos();
	}
	$scope.cambioServicioModal= function(){
		
		$scope.filtroServ = $scope.formData.servicio;
		
		cargartiposServicios();
		cargarEtapas();
		cargarDocumentos();
		
	}
	$scope.cambiotiposServicioModal= function(){
		
		$scope.filtroTS = $scope.formData.tiposServicio;
		cargarDocumentos();
	}

	$scope.cambioSeccionModal= function(){
				
		$scope.filtroSecciones = $scope.formData.seccion;
		cargarDocumentos();
	}	
	$scope.cambioEtapaModal= function(){
		
		$scope.filtroEtapa = $scope.formData.etapa;
		cargarDocumentos();
	}
	$scope.submitForm = function (formData) {
        //alert('Form submitted with' + JSON.stringify(formData));
        if($scope.accion == 'insertar'){
            var datos = {
                NOMBRE: formData.nombre,
                DESCRIPCION: formData.descripcion?formData.descripcion:'',
				SERVICIO: $scope.formData.servicio,
				TIPO_SERVICIO: $scope.formData.tiposServicio,
                ETAPA: $scope.formData.etapa,
                SECCION: $scope.formData.seccion
            };
            $http.post(global_apiserver + "/ver_expedientes/guardarCatDocumento/",JSON.stringify(datos)).
            then(function(response){
                if(response.data.resultado == 'ok'){
					notify('Éxito','El tipo de documento se guardó correctamente','success');
                    cargarDocumentos();
                }
                else{
                    notify('Error',response.data.mensaje,'error');
                }
                $("#modalInsertarActualizar").modal("hide");
            });
        }
        else if($scope.accion == 'editar'){
            var datos = {
                ID: $scope.id_documento,
                NOMBRE: formData.nombre,
                DESCRIPCION: formData.descripcion?formData.descripcion:'',
				SERVICIO: $scope.formData.servicio,
				TIPO_SERVICIO: $scope.formData.tiposServicio,
                ETAPA: $scope.formData.etapa,
                SECCION: $scope.formData.seccion
            };
            $http.post(global_apiserver + "/ver_expedientes/editarCatDocumento/",datos).
            then(function(response){
                if(response.data.resultado == 'ok'){
					notify('Éxito','El tipo de documento se editó correctamente','success');
                    cargarDocumentos();
                }
                else{
                    notify('Error','No se pudo guardar los cambios. '+response.data.mensaje,'error');
                }
                $("#modalInsertarActualizar").modal("hide");
            });
        }
    };
	cargarEtapas();
	cargarSecciones();
	cargarDocumentos();
	cargarServicios();
	cargartiposServicios();
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
