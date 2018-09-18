
app.controller('prospecto_cita_historial_controller',['$scope',function($scope){
    //Titulo de la pagina.
	$scope.titulo='Historial de Citas';
	$scope.id_cita = getQueryVariable("id_cita");
	$scope.actualizaTabla=function(){
		$.ajax({
			type:'GET',
			url:global_apiserver+"/cita_calendario/getHistorial/?id="+$scope.id_cita,
			success:function(data){
				console.log(data);
				$scope.$apply(function(){
					$scope.historial=angular.fromJson(data);
				});
			}
		});
	};

	$scope.actualizaTabla();

}]);
function getQueryVariable(variable) {
	  var query = window.location.search.substring(1);
	  var vars = query.split("&");
	  for (var i=0;i<vars.length;i++) {
		var pair = vars[i].split("=");
		if (pair[0] == variable) {
		  return pair[1];
		}
	  } 
	  alert('Query Variable ' + variable + ' not found');
	}

