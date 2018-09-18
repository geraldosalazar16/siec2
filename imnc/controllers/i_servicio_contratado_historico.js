app.controller('servicio_contratado_historico_controller',['$scope','$http' ,function($scope,$http){
	$scope.titulo = "Servicio Contratado Historico";
	
	$scope.formData = {};
	$scope.prueba	= "PAGINA EN DESARROLLO";
	$scope.id_servicio_cliente_etapa = getQueryVariable("id_serv_cli_et");
	


// ==============================================================================
// ***** 	Funcion para obtener datos servicio contratado historico		*****
// ==============================================================================
	function TablaDatosHistorico(id_servicio){
		$http.get(  global_apiserver + "/i_servicio_contratado_historico/getByIdServicio/?id="+id_servicio)
		.then(function( response ){
			
			$scope.tablaDatos = response.data;
			
		});
	
	}		
		
	
// ==============================================================================
// ***** 	Funcion para obtener datos servicio contratado a partir del id	*****
// ==============================================================================
	function DatosServicioContratado(id_servicio){
		$http.get(  global_apiserver + "/servicio_cliente_etapa/getById/?id="+id_servicio)
		.then(function( response ){
			
			$scope.DatosServicio = response.data;
			
		});
	
	}	
// ==============================================================================
// ***** 			Funcion para obtener los datos de todas las etapas		*****
// ==============================================================================
function EtapasTodas(){
	$http.get(  global_apiserver + "/etapas_proceso/getAll")
		.then(function( response ){
			
			$scope.Etapas = response.data;
			
		});
}	
// ==============================================================================
// ***** 	Funcion para traer los tipos de cambios	*****
// ==============================================================================
	function cargarCambios(){
		$http.get(  global_apiserver + "/i_servicios_contratados_tipos_cambios/getAll/")
		.then(function( response ){
			$scope.Cambios = response.data;
			
		});
	}	
	
// ==============================================================================
// ***** 			Funcion para acomodar la fecha para mostrarla			*****
// ==============================================================================
$scope.FuncionFecha	=	function(fecha){
	var ano	=	fecha.substring(0,4);
	var mes	=	fecha.substring(4,6);
	var dia	=	fecha.substring(6,8);
	var mestexto = "";
	switch(mes){
		case "01":
			mestexto = "Enero";
			break;
		case "02":
			mestexto = "Febrero";
			break;
		case "03":
			mestexto = "Marzo";
			break;	
		case "04":
			mestexto = "Abril";
			break;	
		case "05":
			mestexto = "Mayo";
			break;	
		case "06":
			mestexto = "Junio";
			break;
		case "07":
			mestexto = "Julio";
			break;
		case "08":
			mestexto = "Agosto";
			break;	
		case "09":
			mestexto = "Septiembre";
			break;
		case "10":
			mestexto = "Octubre";
			break;	
		case "11":
			mestexto = "Noviembre";
			break;	
		case "12":
			mestexto = "Diciembre";
			break;	
		default:
			mestexto	= " ";
			break;
	}
	return dia+" de "+mestexto+" de "+ano;
}	

// ==============================================================================
// ***** 		Funcion para buscar el nombre de etapa  a partir del ID		*****
// ==============================================================================	
 $scope.NombreEtapa	=	function(id){
	
	if(typeof $scope.Etapas != "undefined"){
		var datos_etapas	=			$scope.Etapas.find(function(element,index,array){
										return element.ID_ETAPA == id 
									});
	
	
		return datos_etapas.ID ;
	}
}
// ==============================================================================
// ***** 		Funcion para buscar el tipo de cambio a partir del ID		*****
// ==============================================================================	
$scope.TipoCambio	=	function(texto){
	var	tc	=	texto.split("@#$"); 
	if(typeof $scope.Cambios != "undefined"){
		var datos_tipo_cambio	=			$scope.Cambios.find(function(element,index,array){
										return element.ID == tc[0] 
									});
	
	
		return datos_tipo_cambio.NOMBRE ;
	}
	
}
// ==============================================================================
// ***** 		Funcion para Mostrar la descripcion del cambio				*****
// ==============================================================================	
$scope.MostrarDescripcion	=	function(texto){
	var	tc	=	texto.split("@#$"); 
	return tc[3];
}
// ==============================================================================
// ***** 	Funcion para Mostrar el ciclo en que se realizo el cambio		*****
// ==============================================================================	
$scope.MostrarCiclo	=	function(texto){
	var	tc	=	texto.split("@#$"); 
	return tc[2];
}
// ==============================================================================
// ***** 	Funcion para Mostrar la etapa en que se realizo el cambio		*****
// ==============================================================================	
$scope.MostrarEtapa	=	function(texto){
	var	tc	=	texto.split("@#$"); 
	return $scope.NombreEtapa(tc[1]);
}

	TablaDatosHistorico($scope.id_servicio_cliente_etapa);
	DatosServicioContratado($scope.id_servicio_cliente_etapa);
	EtapasTodas();	
	cargarCambios();
	

}]);
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