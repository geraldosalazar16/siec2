
/*
	Creación del controlador con el nombre "tipo_servicios_controller".
*/

app.controller('dictaminacion_controller',['$scope','$http',function($scope,$http){
	
	//INICIALIZACION DE VARIABLES
	$scope.titulo = 'Dictaminación';		//Titulo que aparece en el html
	$scope.modulo_permisos =  global_permisos["SERVICIOS"];
	$scope.DatosServicio = {};
	$scope.DatosDictaminaciones = {};
	$scope.formDataFechaCertificado = {};
	$scope.selectPendientesDictaminadas = "Pendientes";
	$scope.titulo_tabla = 'Servicios Pendientes';
	/*
		Funcion para traer los datos de un dictaminador
	*/
	$scope.Funcion_Datos_Dictaminador= function() {		
	
		var id_usuario = sessionStorage.getItem("id_usuario");
		$http.get(  global_apiserver + "/dictaminador_tiposervicio/getByIdUsuario/?id_usuario="+id_usuario)
			.then(function( response ){
			
			$scope.DatosServicio = response.data;
			
		});		
	};		
	/*
		Funcion para traer las solicitudes pendientes del dictaminador
	*/
	$scope.Funcion_Dictaminaciones_Pendientes_x_Usuario = function() {		
	
		var id_usuario = sessionStorage.getItem("id_usuario");
		$http.get(  global_apiserver + "/dictaminaciones/getPendientesByUsuario/?id_usuario="+id_usuario)
			.then(function( response ){
			
			$scope.DatosDictaminaciones = response.data;
			
		});		
	};		
	/*
		Funcion para traer las solicitudes dictaminadas del dictaminador
	*/
	$scope.Funcion_Dictaminaciones_Dictaminadas_x_Usuario = function() {		
	
		var id_usuario = sessionStorage.getItem("id_usuario");
		$http.get(  global_apiserver + "/dictaminaciones/getDictaminadasByUsuario/?id_usuario="+id_usuario)
			.then(function( response ){
			
			$scope.DatosDictaminaciones = response.data;
			
		});		
	};		

	/*
		Funcion para cambiar el estado de la dictaminacion
	*/
	$scope.editarStatus = function(id,estado){
		// A PARTIR DE AQUI ABRO EL MODAL PARA SELECCIONAR LA FECHA DEL CERTIFICADO
			$scope.formDataFechaCertificado.ID = id;
			$scope.formDataFechaCertificado.STATUS = estado;
				
		if(estado == 1){
			$('#fecha_inicio').datepicker("setDate", new Date());
			$scope.formDataFechaCertificado.FECHA_CERTIFICADO = $('#fecha_inicio').val();
			$("#modalFechaCertificado").modal("show");
		}
		else{
			$scope.formDataFechaCertificado.FECHA_CERTIFICADO = "";
			$scope.submitFormFechaCertificado($scope.formDataFechaCertificado);
		}
		
		
		
	}	
	$scope.submitFormFechaCertificado = function(formDataFechaCertificado){
		var datos	=	{
				ID	:	formDataFechaCertificado.ID,
				STATUS	:	formDataFechaCertificado.STATUS,
				FECHA_CERTIFICADO : formDataFechaCertificado.FECHA_CERTIFICADO,
				ID_USUARIO:	sessionStorage.getItem("id_usuario")
			};
			
			$.post(global_apiserver + "/dictaminaciones/update/", JSON.stringify(datos), function(respuesta){
				respuesta = JSON.parse(respuesta);
				if (respuesta.resultado == "ok") {
					notify("Éxito", "Se han enviado la auditoria a dictaminar","success");
					$scope.Funcion_Dictaminaciones_Pendientes_x_Usuario();
				}
				else {
					notify('Error',respuesta.mensaje,'error');
				}
				$("#modalFechaCertificado").modal("hide");	
			});
	}
	/*
		Funcion para elegir entre Pendientes y Dictaminadas
	*/
	$scope.cambio_solicitudes =function(){
		if($scope.selectPendientesDictaminadas == "Pendientes"){
			$scope.Funcion_Dictaminaciones_Pendientes_x_Usuario();
			$scope.titulo_tabla = 'Servicios Pendientes';
		}
		if($scope.selectPendientesDictaminadas == "Dictaminadas"){
			$scope.Funcion_Dictaminaciones_Dictaminadas_x_Usuario();
			$scope.titulo_tabla = 'Servicios Dictaminados';
		}
	}
// ======================================================================
// *****	FUNCION PARA MOSTRAR FECHA CON FORMATO DD/MM/AAAA		*****
// ======================================================================
$scope.mostrarFecha = function(fecha){
	
	return fecha.substring(0,4)+"-"+fecha.substring(4,6)+"-"+fecha.substring(6,8);
}
function onCalendar() {
	$('#fecha_inicio').datepicker({
                    dateFormat: "yy-mm-dd",
                    minDate: "+0D",
					onSelect: function (dateText, ins) {
                        $scope.formDataFechaCertificado.FECHA_CERTIFICADO = dateText;
                    }
                }).css("display", "inline-block");
			
				
}





onCalendar();
$scope.Funcion_Datos_Dictaminador();	
$scope.Funcion_Dictaminaciones_Pendientes_x_Usuario();
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
