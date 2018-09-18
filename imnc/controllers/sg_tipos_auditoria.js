app.controller("tipos_auditoria_controller", ['$scope','$window', '$http','$document', function($scope,$window,$http,$document){

  $scope.arr_tipos_auditoria = [];
  $scope.tipo_auditoria_insertar_editar = {};

  // Pinta tabla de tipos_auditoria
  $scope.despliega_tipos_auditoria = function () {
    var http_request = {
      method: 'GET',
      url: global_apiserver + "/sg_auditorias_tipos/getAll/",
    };

    $http(http_request).success(function(data) {
      if(data) {
        $scope.arr_tipos_auditoria = data;
      } 
      else  {
        console.log("No hay datos");
      }
    }).error(function(response) {
      console.log("Error al generar petición: " + response);
    });
  }

  // Abrir modal para insertar
  $scope.modal_tipo_auditoria_insertar = function(){
    $('#modalTituloTipoAuditoria').html("Agregar registro");
    //$('#btnGuardartipo_auditoria').attr("opcion", "insertar");
    $scope.opcion_guardar_tipo_auditoria = "insertar";
    $scope.tipo_auditoria_insertar_editar = {};
    $('#modalInsertarActualizarTipoAuditoria').modal('show');
  }

  // Abrir modal para editar
  $scope.modal_tipo_auditoria_editar = function(id_tipo_auditoria){
    $('#modalTituloTipoAuditoria').html("Editar registro");
    //$('#btnGuardartipo_auditoria').attr("opcion", "editar");
    $scope.opcion_guardar_tipo_auditoria = "editar";
    
    var http_request = {
      method: 'GET',
      url: global_apiserver + "/sg_auditorias_tipos/getById/?id="+id_tipo_auditoria,
    };

    $http(http_request).success(function(data) {
      if(data) { 
        $scope.tipo_auditoria_insertar_editar = data;
      } 
      else  {
        console.log("No hay datos");
      }
    }).error(function(response) {
      console.log("Error al generar petición: " + response);
    });

    $('#modalInsertarActualizarTipoAuditoria').modal('show');
  }

  $scope.tipo_auditoria_guardar = function(){
    $scope.tipo_auditoria_insertar_editar.ID_USUARIO = sessionStorage.getItem("id_usuario");
    if ($scope.opcion_guardar_tipo_auditoria == 'insertar') {
       var http_request = {
        method: 'POST',
        url: global_apiserver + "/sg_auditorias_tipos/insert/",
        data: angular.toJson($scope.tipo_auditoria_insertar_editar)
      };
    }
    else if ($scope.opcion_guardar_tipo_auditoria = 'editar'){
      var http_request = {
        method: 'POST',
        url: global_apiserver + "/sg_auditorias_tipos/update/",
        data: angular.toJson($scope.tipo_auditoria_insertar_editar)
      };
    }
   
    $http(http_request).success(function(data) {
      if(data) { 
        if (data.resultado == "ok") {
           notify("Éxito", "Se han guardado los cambios", "success");
           $('#modalInsertarActualizarTipoAuditoria').modal('hide');
           $scope.despliega_tipos_auditoria();
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

  // Funcion que recarga la pagina
  $scope.reload = function(){
    $window.location.reload();
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