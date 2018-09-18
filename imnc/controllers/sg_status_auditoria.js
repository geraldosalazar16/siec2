app.controller("status_auditoria_controller", ['$scope','$window', '$http','$document', function($scope,$window,$http,$document){

  $scope.arr_status_auditoria = [];
  $scope.status_auditoria_insertar_editar = {};

  // Pinta tabla de tipos_auditoria
  $scope.despliega_tipos_auditoria = function () {
    var http_request = {
      method: 'GET',
      url: global_apiserver + "/sg_auditorias_status/getAll/",
    };

    $http(http_request).success(function(data) {
      if(data) {
        $scope.arr_status_auditoria = data;
      } 
      else  {
        console.log("No hay datos");
      }
    }).error(function(response) {
      console.log("Error al generar petición: " + response);
    });
  }

  // Abrir modal para insertar
  $scope.modal_status_auditoria_insertar = function(){
    $('#modalTituloStatusAuditoria').html("Agregar registro");
    //$('#btnGuardarstatus_auditoria').attr("opcion", "insertar");
    $scope.opcion_guardar_status_auditoria = "insertar";
    $scope.status_auditoria_insertar_editar = {};
    $('#modalInsertarActualizarStatusAuditoria').modal('show');
  }

  // Abrir modal para editar
  $scope.modal_status_auditoria_editar = function(id_status_auditoria){
    $('#modalTituloStatusAuditoria').html("Editar registro");
    //$('#btnGuardarstatus_auditoria').attr("opcion", "editar");
    $scope.opcion_guardar_status_auditoria = "editar";
    
    var http_request = {
      method: 'GET',
      url: global_apiserver + "/sg_auditorias_status/getById/?id="+id_status_auditoria,
    };

    $http(http_request).success(function(data) {
      if(data) { 
        $scope.status_auditoria_insertar_editar = data;
      } 
      else  {
        console.log("No hay datos");
      }
    }).error(function(response) {
      console.log("Error al generar petición: " + response);
    });

    $('#modalInsertarActualizarStatusAuditoria').modal('show');
  }

  $scope.status_auditoria_guardar = function(){
    $scope.status_auditoria_insertar_editar.ID_USUARIO = sessionStorage.getItem("id_usuario");
    if ($scope.opcion_guardar_status_auditoria == 'insertar') {
       var http_request = {
        method: 'POST',
        url: global_apiserver + "/sg_auditorias_status/insert/",
        data: angular.toJson($scope.status_auditoria_insertar_editar)
      };
    }
    else if ($scope.opcion_guardar_status_auditoria = 'editar'){
      var http_request = {
        method: 'POST',
        url: global_apiserver + "/sg_auditorias_status/update/",
        data: angular.toJson($scope.status_auditoria_insertar_editar)
      };
    }
   
    $http(http_request).success(function(data) {
      if(data) { 
        if (data.resultado == "ok") {
           notify("Éxito", "Se han guardado los cambios", "success");
           $('#modalInsertarActualizarStatusAuditoria').modal('hide');
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