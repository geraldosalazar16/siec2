app.controller('expediente_entidades_controller', ['$scope', function($scope) { 
$scope.titulo = 'Expediente Entidades';

/*funcion que trae todos los AUDITORES, CLIENTES, PROSPECTO
con su tipo, nombre de la persona, el expediente y si es valido o no*/
$scope.actualizaTabla = function(){
      $.ajax({
      	type:'GET',
      	url:global_apiserver + "/ex_expediente_reporte/getAll/",
      	success:function(data){
      		$scope.$apply(function(){
      			$scope.expedienteentidades=angular.fromJson(data);
      		});
      	}
      });
};
/*Trae los registros de la tabla ex_tabla_entidades*/
$scope.getEntidades= function(){
	$.ajax({
		type:'GET',
		url:global_apiserver+"/ex_expediente_reporte/getAllEntidad/",
		success:function(data){
			$scope.$apply(function(){
				$scope.entidades=angular.fromJson(data);
			})

		}
	});
};

$scope.actualizaTabla();
$scope.getEntidades();
}]);