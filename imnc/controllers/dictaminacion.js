
/*
	Creación del controlador con el nombre "tipo_servicios_controller".
*/

app.controller('dictaminacion_controller',['$scope',function($scope){
//Titulo que aparece en el html
	
	$scope.titulo = 'Dictaminacion';
	
	
	
	
	

	

/*		
		Función para enviar correos prueba
*/
$scope.EnviarCorreoPrueba = function(){

	$.post( global_apiserver + "/dictaminacion/enviarCorreo/", function(respuesta){
      respuesta = JSON.parse(respuesta);
      if (respuesta.resultado == "ok") {
        notify("Éxito", "Se ha enviado correo correctamente", "success");
        
      }
      else{
          notify("Error", respuesta.mensaje, "error");
        }
      
  });
}	




$(document).ready(function () {
	
	
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
