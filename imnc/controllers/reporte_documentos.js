/*
	Creación del controlador con el nombre "reporte_documentos_controller".
*/
app.controller('reporte_documentos_controller',['$scope',function($scope){

$scope.search = {}; //objeto para guardar los datos a filtrar
$scope.search.NombreTarea="";
var id_user = sessionStorage.getItem("id_usuario");
//Titulo que aparece en el html
	$scope.titulo = 'Tabla de Documentos';


/*
		Trae los registros de la tabla Catalogo Documentos
*/
$scope.getCatDocumentoAll= function(){
	$.ajax({
		type:'GET',
//		url:global_apiserver+"/ver_expedientes/getCatDocumentoAll/",
		url:global_apiserver+"/ver_expedientes/getCatNombreDocumentoAll/",
		success:function(data){
			$scope.$apply(function(){
				$(".select2_single").select2({});
				$scope.catalogoDocumentos=angular.fromJson(data);
			})

		}
	});
};	

/*
		Trae los registros de la tabla Clientes
*/
$scope.getClientesAll= function(){
	$.ajax({
		type:'GET',
		url:global_apiserver+"/clientes/getAll/",
		success:function(data){
			$scope.$apply(function(){
				$(".select2_single").select2({});
				$scope.clientes=angular.fromJson(data);
			})

		}
	});
};	
/*
		Trae los registros de la tabla Servicios
*/
$scope.getServiciosAll= function(){
	$.ajax({
		type:'GET',
		url:global_apiserver+"/servicios/getAll/",
		success:function(data){
			$scope.$apply(function(){
				$(".select2_single").select2({});
				$scope.servicios=angular.fromJson(data);
			})

		}
	});
};	
/*
		Trae los registros de la tabla Etapas
*/
$scope.getEtapasAll= function(){
	$.ajax({
		type:'GET',
		url:global_apiserver+"/etapas_proceso/getAll/",
		success:function(data){
			$scope.$apply(function(){
				$(".select2_single").select2({});
				$scope.etapas=angular.fromJson(data);
			})

		}
	});
};
/*
		Trae los registros de la tabla Secciones
*/
$scope.getSeccionAll= function(){
	$.ajax({
		type:'GET',
		url:global_apiserver+"/ver_expedientes/getSeccionAll/",
		success:function(data){
			$scope.$apply(function(){
				$(".select2_single").select2({});
				$scope.secciones=angular.fromJson(data);
			})

		}
	});
};		

/*		
		Función para cdo se clickea el boton VER TODOS.
*/
$scope.filtrartodos = function() {

	var tablaDatos1 = new Array();
	var indice1=0;
	$.post( global_apiserver + "/ver_expedientes/reportesDocumentos/", function( response ) {
		response = JSON.parse(response);
		$scope.FECHA_ACTUAL = response.FECHA_ACTUAL;
		$.each(response, function( indice, datos ) {
			$.each(datos, function( indice, datos1 ) {

			tablaDatos1[indice1] = angular.fromJson(datos1);
			indice1+=1;
		  });
	   });
	   $scope.tablaDatos =  tablaDatos1;
	   $scope.$apply();
	});
}

/*		
		Función para cdo se clickea el boton FILTRAR.
*/
$scope.filtrar = function() {
		
	 var filtros = {
       
        DOCUMENTO:$("#catDocumentos").val(),
        CLIENTE:$("#catClientes").val(),
        SERVICIO:$("#catServicio").val(),
		ETAPA:$("#catEtapa").val(),
		SECCION:$("#catSeccion").val(),
		ESTADO:$("#catEstado").val(),
    };

		
	var tablaDatos1 = new Array();
	var indice1=0;
	$.post( global_apiserver + "/ver_expedientes/reportesDocumentos/",JSON.stringify(filtros), function( response ) {
		response = JSON.parse(response);
		$scope.FECHA_ACTUAL = response.FECHA_ACTUAL;
		$.each(response, function( indice, datos ) {
			$.each(datos, function( indice, datos1 ) {

			tablaDatos1[indice1] = angular.fromJson(datos1);
			indice1+=1;
		  });
	   });
	   $scope.tablaDatos =  tablaDatos1;
	   $scope.$apply();
	});

}


$(document).ready(function () {
	
	$scope.getCatDocumentoAll();
	$scope.getClientesAll();
	$scope.getServiciosAll();
	$scope.getEtapasAll();
	$scope.getSeccionAll();
	

});
	

}]);

	/*
		Función que recibe el título y el texto de un cuadro de notificación.
	*/
	function notify(titulo, texto,tipo) {
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

