
/*
	Creación del controlador con el nombre "indicadores_programacion_dias_auditor_controller".
*/

app.controller('indicadores_programacion_dias_auditor_controller',['$scope',function($scope){
//Titulo que aparece en el html
	
	var f = new Date();
	$scope.titulo = 'Indicadores días auditor';
	$scope.texto_tabla = 'Año '+f.getFullYear();
	
/*		
		Función para actualizar la tabla con los registros en la BD.
*/
$scope.Traer_Datos = function() {

	var tablaDatos1 = new Array();
	var indice1=0;
	$.post(  global_apiserver + "/i_reportes/getMontoAndDiasAudSG/", function( response ) {
		response = JSON.parse(response);
		
	   $scope.tablaDatos =  response;
	   $scope.$apply();
	});
}	


$(document).ready(function () {
	$scope.Traer_Datos();
	


});
	
}]);

function notify(titulo, texto, tipo){
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
