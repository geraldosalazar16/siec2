
/*
	Creación del controlador con el nombre "clientes_razones_sociales_controller".
*/

app.controller('clientes_razones_sociales_controller',['$scope','$http',function($scope,$http){
//Titulo que aparece en el html
	
	$scope.formData = {};
	$scope.accion_fact = 0;
/*		
		Función para actualizar la tabla con los registros en la BD.
*/

$scope.clientes_datos_razones_sociales = function() {

	var tablaDatos1 = new Array();
	var indice1=0;
	
	$http.get(  global_apiserver + "/i_clientes_razones_sociales/getByIdCliente/?id="+global_id_cliente)
		.then(function( response ){
            $scope.CLIENTES_RAZONES_SOCIALES = response.data;
			
			
			
		});
		
    
}	
	
/*		
		Función para limpiar la información del módelo y que no se quede guardada
		después de realizar alguna acción.
*/
$scope.limpiaCampos = function(){
	$scope.formData.Nombre="";
	$scope.formData.RFC ="";
	$scope.formData.ID = "";
	$scope.formData.EF = "";
	
	
}

/*		
		Función para hacer que aparezca el formulario de agregar razones sociales. 
*/
$scope.InsertarRazonSocial = function(){
	$scope.limpiaCampos();
		$scope.modal_titulo = 'Insertar Razón Social';
		$scope.accion_fact =0;
	
	
	
	$("#modalInsertarActualizarRS").modal("show");

}
/*		
		Función para hacer que aparezca el formulario de editar razones sociales. 
*/
$scope.EditarRazonSocial = function(id,nomb,rfc,ef){
	$scope.limpiaCampos();
		$scope.modal_titulo = 'Editar Razón Social';
		$scope.accion_fact =1;
		$scope.formData.ID = id;
		$scope.formData.Nombre = nomb ;
		$scope.formData.RFC = rfc;
		$scope.formData.EF = ef;
	
	$("#modalInsertarActualizarRS").modal("show");

}
// ==============================================================================
// ***** 	FUNCION PARA EL BOTON GUARDAR DEL MODAL	INSERTAR/ACTUALIZAR 	*****
// ==============================================================================
$scope.submitForm = function (formData) {
	$scope.formData.RFC = $('#RFC').val();
	
		  if($scope.accion_fact == 0){
			  var datos = {
							CLIENTE: global_id_cliente ,
							NOMBRE:	$scope.formData.Nombre ,
							RFC:	$scope.formData.RFC,
							ID_USUARIO:sessionStorage.getItem("id_usuario")
						};
			$http.post(global_apiserver + "/i_clientes_razones_sociales/insert/",datos).
				then(function(response){
			
                if(response.data.resultado=="ok"){
                    notify('&Eacutexito','Se ha insertado la razon social','success');
                    
					$scope.clientes_datos_razones_sociales();
				
				}
                else{
                    notify('Error',response.data.mensaje,'error');
                }
                $("#modalInsertarActualizarRS").modal("hide");
            });
		  }
		  if($scope.accion_fact == 1){
			  var datos = {
							ID : $scope.formData.ID,
							EF : $scope.formData.EF,
							CLIENTE: global_id_cliente ,
							NOMBRE:	$scope.formData.Nombre ,
							RFC:	$scope.formData.RFC,
							ID_USUARIO:sessionStorage.getItem("id_usuario")
						};
			$http.post(global_apiserver + "/i_clientes_razones_sociales/update/",datos).
				then(function(response){
			
                if(response.data.resultado=="ok"){
                    notify('&Eacutexito','Se ha editado la razon social','success');
                    
					$scope.clientes_datos_razones_sociales();
				
				}
                else{
                    notify('Error',response.data.mensaje,'error');
                }
                $("#modalInsertarActualizarRS").modal("hide");
            });
		  }
}


 $('#RFC').keyup(function(){
      $(this).val($(this).val().toUpperCase());
    });

$(document).ready(function () {
	$scope.clientes_datos_razones_sociales();
	//$scope.limpiaCampos();


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
