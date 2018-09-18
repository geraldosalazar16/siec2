app.controller("perfiles_controller", ['$scope','$window', '$http','$document', function($scope,$window,$http,$document){

  $scope.arr_perfiles = [];
  $scope.arr_permisos = [];
  $scope.obj_perfil = {};

  // Pinta tabla de perfiles
  $scope.despliega_perfiles = function () {
    var http_request = {
      method: 'GET',
      url: global_apiserver + "/tipos_perfiles/getAll/",
    };

    $http(http_request).success(function(data) {
      if(data) {
        $scope.arr_perfiles = data;
      } 
      else  {
        console.log("No hay datos");
      }
    }).error(function(response) {
      console.log("Error al generar petición: " + response);
    });
  }

  // Abrir modal para insertar
  $scope.modal_perfil_insertar = function(){
    $('#modalTituloPerfiles').html("Agregar perfíl");
    //$('#btnGuardarPerfil').attr("opcion", "insertar");
    $scope.opcion_guardar_perfil = "insertar";
    $scope.obj_perfil = {};

    var http_request = {
      method: 'GET',
      url: global_apiserver + "/tipos_permisos/getAll/",
    };

    $http(http_request).success(function(data) {
      if(data) { 
        $scope.arr_permisos = data;
      } 
      else  {
        console.log("No hay datos");
      }
    }).error(function(response) {
      console.log("Error al generar petición: " + response);
    });

    $('#modalInsertarActualizarPerfiles').modal('show');
  }

  // Abrir modal para editar
  $scope.modal_perfil_editar = function(id_perfil){
    $('#modalTituloPerfiles').html("Editar perfíl");
    //$('#btnGuardarPerfil').attr("opcion", "editar");
    $scope.opcion_guardar_perfil = "editar";
    
    var http_request = {
      method: 'GET',
      url: global_apiserver + "/tipos_perfiles/getById/?id="+id_perfil,
    };

    $http(http_request).success(function(data) {
      if(data) { 
        $scope.obj_perfil = data;
        $scope.arr_permisos = data.PERMISOS;
      } 
      else  {
        console.log("No hay datos");
      }
    }).error(function(response) {
      console.log("Error al generar petición: " + response);
    });

    $('#modalInsertarActualizarPerfiles').modal('show');
  }

  $scope.perfil_guardar = function(){
    $scope.obj_perfil.ID_USUARIO = sessionStorage.getItem("id_usuario");
    $scope.obj_perfil.PERMISOS = $('.chkPermisos:checked').map(function() {return this.value;}).get().join(',');
    
    if ($scope.opcion_guardar_perfil == 'insertar') {
       var http_request = {
        method: 'POST',
        url: global_apiserver + "/tipos_perfiles/insert/",
        data: angular.toJson($scope.obj_perfil)
      };
    }
    else if ($scope.opcion_guardar_perfil = 'editar'){
      var http_request = {
        method: 'POST',
        url: global_apiserver + "/tipos_perfiles/update/",
        data: angular.toJson($scope.obj_perfil)
      };
    }
   
    $http(http_request).success(function(data) {
      if(data) { 
        if (data.resultado == "ok") {
           notify("Éxito", "Se han guardado los cambios", "success");
           $('#modalInsertarActualizarPerfiles').modal('hide');
           $scope.despliega_perfiles();
        }
        else{
          notify("Error", data.mensaje, "error");
        }
      } 
      else  {
        console.log("No hay datos");
      }
    }).error(function(response) {
      console.log("Error al generar petición: " + response);
    });

  }

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


