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


    // Abrir modal para editar
    $scope.modal_usuario_pass = function(id_usuario){
              var http_request = {
            method: 'GET',
            url: global_apiserver + "/usuariosc/getById/?id="+id_usuario,
        };

        $http(http_request).success(function(data) {
            if(data) {
                $scope.usuario_insertar_editar = data;
            }
            else  {
                console.log("No hay datos");
            }
        }).error(function(response) {
            console.log("Error al generar petición: " + response);
        });

        $('#modalActualizarPass').modal('show');
    }

  $scope.usuario_guardar_pass = function(){
      $scope.usuario_insertar_editar.ID_USUARIO = sessionStorage.getItem("id_usuario");
      if($scope.usuario_insertar_editar.PASSWORD == $scope.usuario_insertar_editar.COMFIRM_PASSWORD)
      {
          $.post(global_apiserver + "/usuariosc/updatePass/",angular.toJson($scope.usuario_insertar_editar), function(respuesta){
              notify("Éxito", "El password fue actualizado", "success");
              $('#modalActualizarPass').modal('hide');
              $scope.despliega_usuarios();
          });

      }
      else {
          notify("Error", "Los passwords no son iguales", "error");
      }
  }
 	
  $scope.usuario_guardar = function(){
    $scope.usuario_insertar_editar.ID_USUARIO = sessionStorage.getItem("id_usuario");
    $scope.usuario_insertar_editar.MODULOS = {};
      var j = 0;
      for(var i = 0 ; i < $scope.arr_modulos.length; i++){
          if(typeof $scope.arr_modulos[i].VALOR !== "undefined")
          {
              var object = $scope.arr_modulos[i].VALOR;
              object.MODULO = $scope.arr_modulos[i].ID;
              $scope.usuario_insertar_editar.MODULOS[j++] = object;
          }

      }

    if ($scope.opcion_guardar_usuario == 'insertar') {
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
