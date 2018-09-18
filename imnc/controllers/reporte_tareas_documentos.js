/*
	Creación del controlador con el nombre "reporte_tareas_documento_controller".
*/
app.controller('reporte_tareas_documento_controller',['$scope',function($scope){

$scope.search = {}; //objeto para guardar los datos a filtrar
$scope.search.NombreTarea="";
var id_user = sessionStorage.getItem("id_usuario");
//Titulo que aparece en el html
	$scope.titulo = 'Tabla de Tareas de Documentos';


/*
		Trae los registros de la tabla Catalogo Documentos
*/
$scope.getCatDocumentoAll= function(){
	$.ajax({
		type:'GET',
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
		Función para actualizar la tabla tareas documento si algun registro se ha ido de fecha de cumplimiento y pasa a estar incumplido.
*/
function Actualizar_Datos(){
	
	$.getJSON( global_apiserver + "/ver_expedientes/reportes/", function( response ) {
		$scope.FECHA_ACTUAL = response.FECHA_ACTUAL;
		$.each(response, function( indice, datos ) {
		
				
			if(($scope.FECHA_ACTUAL >= datos.FECHA_FIN+" "+datos.HORA_FIN)&&(datos.ESTADO == 0)){
				cambio_estado	=	-1;
				cambio_observaciones	= "Se incumplio la tarea en la fecha prevista";
				var tarea = {
							id	:	datos.ID,
							id_servicio : datos.ID_SERVICIO,
							///////////////////////////////////////////////
							id_catag_docum :	datos.ID_CATALOGO_DOCUMENTOS,
							ciclo 	:	datos.CICLO,
							nombre_tarea : datos.NOMBRE_TAREA,
							estado	:	cambio_estado,
							///////////////////////////////////////////////
				
							observaciones : cambio_observaciones,
							fecha_inicio : datos.FECHA_INICIO,
							fecha_fin : datos.FECHA_FIN,
							hora_inicio : datos.HORA_INICIO,
							hora_fin : datos.HORA_FIN,
							id_usuario_modificacion : id_user
					};
				
			$.post(global_apiserver + "/cita_calendario_documentos/update/", JSON.stringify(tarea), function(response){
					respuesta = JSON.parse(response);
				if (respuesta.resultado_tareas == "ok" && respuesta.resultado_hist == "ok") {
					
					notify("&Eacutexito", "Se ha modificado la tarea", "success");
					
				}
				else{
                    notify("Error", respuesta.mensaje, "error");
                }
			});	
				
			}	
				
			

			
		});
	});
}	
/*		
		Función para cdo se clickea el boton VER TODOS.
*/
$scope.filtrartodos = function() {

	
	$.post( global_apiserver + "/ver_expedientes/reportes/", function( response ) {
		response = JSON.parse(response);
		$scope.FECHA_ACTUAL = response.FECHA_ACTUAL;

		$scope.tablaDatos = angular.fromJson(response);
  
		  $scope.$apply();
	   });
}

/*		
		Función para cdo se clickea el boton FILTRAR.
*/
$scope.filtrar = function() {
		
	 var filtros = {
        NOMBRE_TAREA:$scope.search.NombreTarea,
        DOCUMENTO:$("#catDocumentos").val(),
        CLIENTE:$("#catClientes").val(),
        SERVICIO:$("#catServicio").val(),
		ESTADO:$("#catEstado").val(),
    };
 var aaaaa= 0;	
	$.post( global_apiserver + "/ver_expedientes/reportes/", JSON.stringify(filtros), function( response ) {
		response = JSON.parse(response);
		$scope.FECHA_ACTUAL = response.FECHA_ACTUAL;

		$scope.tablaDatos = angular.fromJson(response);
  
		  $scope.$apply();
	   });
}

$(document).ready(function () {
	
	$scope.getCatDocumentoAll();
	$scope.getClientesAll();
	$scope.getServiciosAll();
	Actualizar_Datos();

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

