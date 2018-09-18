app.controller("usuarios_controller", ['$scope','$window', '$http','$document', function($scope,$window,$http,$document){

  $scope.arr_usuarios = [];
  $scope.arr_perfiles = [];
  $scope.usuario_insertar_editar = {};

   // Llena combo perfiles
  $scope.fill_select_perfiles = function (seleccionado){ 
    var http_request = {
      method: 'GET',
      url: global_apiserver + "/tipos_perfiles/getAll/",
    };

    $http(http_request).success(function(data) {
      if(data) { 
        $scope.arr_perfiles = data;
        $("#cmbPerfiles").val(seleccionado);
      } 
      else  {
        console.log("No hay datos");
      }
    }).error(function(response) {
      console.log("Error al generar petición: " + response);
    });
  }

  // Pinta tabla de usuarios
  $scope.despliega_usuarios = function () {
    $scope.fill_select_perfiles("");
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
    $scope.fill_select_perfiles("");
    $('#modalTituloUsuarios').html("Agregar usuario");
    //$('#btnGuardarUsuario').attr("opcion", "insertar");
    $scope.opcion_guardar_usuario = "insertar";
    $scope.usuario_insertar_editar = {};

    $('#modalInsertarActualizarUsuarios').modal('show');
  }

  // Abrir modal para editar
  $scope.modal_usuario_editar = function(id_usuario){
    $scope.fill_select_perfiles("");
    $('#modalTituloUsuarios').html("Editar usuario");
    //$('#btnGuardarUsuario').attr("opcion", "editar");
    $scope.opcion_guardar_usuario = "editar";
    
    var http_request = {
      method: 'GET',
      url: global_apiserver + "/usuarios/getById/?id="+id_usuario,
    };

    $http(http_request).success(function(data) {
      if(data) { 
        $scope.fill_select_perfiles(data.ID_PERFIL);
        $scope.usuario_insertar_editar = data;
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
    $scope.usuario_insertar_editar.ID_PERFIL = $("#cmbPerfiles").val();
    console.log($scope.usuario_insertar_editar);
    if ($scope.opcion_guardar_usuario == 'insertar') {
       var http_request = {
        method: 'POST',
        url: global_apiserver + "/usuarios/insert/",
        data: angular.toJson($scope.usuario_insertar_editar)
      };
    }
    else if ($scope.opcion_guardar_usuario = 'editar'){
      var http_request = {
        method: 'POST',
        url: global_apiserver + "/usuarios/update/",
        data: angular.toJson($scope.usuario_insertar_editar)
      };
    }
   
    $http(http_request).success(function(data) {
      if(data) { 
        if (data.resultado == "ok") {
           notify("Éxito", "Se han guardado los cambios", "success");
           $('#modalInsertarActualizarUsuarios').modal('hide');
           $scope.despliega_usuarios();
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