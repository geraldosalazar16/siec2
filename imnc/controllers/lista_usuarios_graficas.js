app.controller("lista_usuarios_graficas_controller", ['$scope','$window', '$http','$document', function($scope,$window,$http,$document){

  $scope.arr_usuarios = [];

  
  

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

   
	$scope.ver_graficas = function(ID){
		location.href = "./?pagina=ver_graficas_usuario&id="+ID;
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