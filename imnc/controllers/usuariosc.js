app.controller("usuariosc_controller", ['$scope','$window', '$http','$document', function($scope,$window,$http,$document){

  $scope.arr_usuarios = [];
  $scope.arr_perfiles = [];
  $scope.usuario_insertar_editar = {};
  $scope.arr_modulos = {};

  $scope.get_modulos_perfiles = function(){
	  $.post(global_apiserver + "/perfiles/getAll/", function(respuesta){

				$scope.arr_perfiles = JSON.parse(respuesta);
				 $.post(global_apiserver + "/modulos/getAll/", function(respuesta){
		  $scope.$apply(function(){
			 
				$scope.arr_modulos = JSON.parse(respuesta);
				});
				
		
	});

				
		
	});
	 
  }
  

  

  // Pinta tabla de usuarios
  $scope.despliega_usuarios = function () {
    //$scope.fill_select_perfiles("");
    var http_request = {
      method: 'GET',
      url: global_apiserver + "/usuarios/getAll/",
    };

    $http(http_request).success(function(data) {
      if(data) {
        $scope.arr_usuarios = data;
		console.log($scope.arr_usuarios);
      } 
      else  {
        console.log("No hay datos");
      }
    }).error(function(response) {
      console.log("Error al generar petición: " + response);
    });
  }

  // Abrir modal para insertar
  $scope.modal_usuario_insertar = function(){
 
    $('#modalTituloUsuarios').html("Agregar usuario");
    $scope.opcion_guardar_usuario = "insertar";
    $scope.usuario_insertar_editar = {};

    $('#modalInsertarActualizarUsuarios').modal('show');
  }

  // Abrir modal para editar
  $scope.modal_usuario_editar = function(id_usuario){
    $('#modalTituloUsuarios').html("Editar usuario");
    $scope.opcion_guardar_usuario = "editar";
    
    var http_request = {
      method: 'GET',
      url: global_apiserver + "/usuariosc/getById/?id="+id_usuario,
    };

    $http(http_request).success(function(data) {
      if(data) { 
	  console.log(data);
		
        $scope.usuario_insertar_editar = data;
		for(var i = 0 ; i < data.PERFIL.length;i++){
			$("#"+data.PERFIL[i].MODULO).val(data.PERFIL[i].ID_PERFIL);
			
		}
      } 
      else  {
        console.log("No hay datos");
      }
    }).error(function(response) {
      console.log("Error al generar petición: " + response);
    });

    $('#modalInsertarActualizarUsuarios').modal('show');
  }
 	
  $scope.usuario_guardar = function(){
    $scope.usuario_insertar_editar.ID_USUARIO = sessionStorage.getItem("id_usuario");
    $scope.usuario_insertar_editar.MODULOS = {};
	console.log("entra for");
	for(var i = 0 ; i < $scope.arr_modulos.length; i++){
		console.log(i);
		$scope.usuario_insertar_editar.MODULOS[i] = $scope.arr_modulos[i].VALOR;
		console.log($scope.arr_modulos[i].VALOR);
	}

console.log($scope.usuario_insertar_editar);
    if ($scope.opcion_guardar_usuario == 'insertar') {
for(var i = 0 ; i < $scope.arr_modulos.length; i++){
		$scope.usuario_insertar_editar.MODULOS[i] = $scope.arr_modulos[i].VALOR;
	}
		 $.post(global_apiserver + "/usuariosc/insert/",angular.toJson($scope.usuario_insertar_editar), function(respuesta){
			 notify("Éxito", "Se han guardado los cambios", "success");
           $('#modalInsertarActualizarUsuarios').modal('hide');
           $scope.despliega_usuarios();
		 });
       

    }
    else if ($scope.opcion_guardar_usuario = 'editar'){
      $.post(global_apiserver + "/usuariosc/update/",angular.toJson($scope.usuario_insertar_editar), function(respuesta){
			 notify("Éxito", "Se han guardado los cambios", "success");
           $('#modalInsertarActualizarUsuarios').modal('hide');
           $scope.despliega_usuarios();
		 });
    }
	
  
  }
$scope.get_modulos_perfiles();

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
}]);