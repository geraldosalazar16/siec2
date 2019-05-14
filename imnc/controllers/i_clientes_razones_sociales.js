
/*
	Creación del controlador con el nombre "clientes_facturacion_controller".
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
            $scope.CLIENTES_DATOS_FACTURACION = response.data[0];
			$scope.accion_fact=response.data.length;
			
			
		});
		
    
}	
	
/*		
		Función para limpiar la información del módelo y que no se quede guardada
		después de realizar alguna transacción.
*/
$scope.limpiaCampos = function(){
	$scope.formData.Nombre="";
	$scope.formData.RFC ="";
	
	
}

/*		
		Función para hacer que aparezca el formulario de agregar razones sociales. 
*/
$scope.InsertarRazonSocial = function(){
	$scope.limpiaCampos();
		$scope.modal_titulo = 'Insertar Razón Social';
		$scope.accion_fact =0;
	
	/*if($scope.accion_fact == 1){
		$scope.modal_titulo = 'Editar Datos Facturacion';
		$scope.accion_fact =1;
		$scope.formData.formadePago=$scope.CLIENTES_DATOS_FACTURACION.ID_FORMA_D_PAGO;
		$scope.formData.metododePago =$scope.CLIENTES_DATOS_FACTURACION.ID_METODO_D_PAGO;
		$scope.formData.usodelaFactura=$scope.CLIENTES_DATOS_FACTURACION.ID_USO_D_L_FACTURA;
		
	}*/
	
	$("#modalInsertarActualizarRS").modal("show");

}
// ==============================================================================
// ***** 	FUNCION PARA EL BOTON GUARDAR DEL MODAL	INSERTAR/ACTUALIZAR 	*****
// ==============================================================================
$scope.submitForm = function (formData) {
	var datos = {
            CLIENTE: global_id_cliente ,
            NOMBRE:	$scope.formData.Nombre ,
			RFC:	$scope.formData.RFC,
            ID_USUARIO:sessionStorage.getItem("id_usuario")
          };
		  if($scope.accion_fact == 0){
			$http.post(global_apiserver + "/i_clientes_razones_sociales/insert/",datos).
				then(function(response){
			
                if(response.data.resultado=="ok"){
                    notify('&Eacutexito','Se han insertado los datos de facturacion','success');
                    
					$scope.clientes_datos_facturacion();
				
				}
                else{
                    notify('Error',response.data.mensaje,'error');
                }
                $("#modalInsertarActualizar").modal("hide");
            });
		  }
		  if($scope.accion_fact == 1){
			$http.post(global_apiserver + "/i_clientes_datos_facturacion/update/",datos).
				then(function(response){
			
                if(response.data.resultado=="ok"){
                    notify('&Eacutexito','Se han editado los datos de facturacion','success');
                    
					$scope.clientes_datos_facturacion();
				
				}
                else{
                    notify('Error',response.data.mensaje,'error');
                }
                $("#modalInsertarActualizar").modal("hide");
            });
		  }
}
/*		
		Función para traer las Formas de Pago.
*/
$scope.funcionFormasDePago = function(){
	$.ajax({
		type:'GET',
		url:global_apiserver+"/i_formas_d_pago/getAll/",
		success:function(data){
			$scope.$apply(function(){
			
				$scope.formadePagos=angular.fromJson(data);
			})

		}
	});
}
/*		
		Función para traer los Metodos de Pago.
*/
$scope.funcionMetodosDePago = function(){
	$.ajax({
		type:'GET',
		url:global_apiserver+"/i_metodos_d_pago/getAll/",
		success:function(data){
			$scope.$apply(function(){
			
				$scope.metododePagos=angular.fromJson(data);
			})

		}
	});
}
/*		
		Función para traer los Usos de la Factura.
*/
$scope.funcionUsoDeLaFactura = function(){
	$.ajax({
		type:'GET',
		url:global_apiserver+"/i_uso_d_l_factura/getAll/",
		success:function(data){
			$scope.$apply(function(){
			
				$scope.usodelaFacturas=angular.fromJson(data);
			})

		}
	});
}

 $('#RFC').keyup(function(){
      $(this).val($(this).val().toUpperCase());
    });

$(document).ready(function () {
	$scope.funcionFormasDePago();
	$scope.funcionMetodosDePago();
	$scope.funcionUsoDeLaFactura();
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
