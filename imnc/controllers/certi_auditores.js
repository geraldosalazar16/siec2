
/*
	Creación del controlador con el nombre "certi_auditores_controller".
*/

app.controller('certi_auditores_controller',['$scope',function($scope){
//Titulo que aparece en el html
	
	$scope.titulo = 'CERTI AUDITORES';
		
	$scope.tntPortfolio = [
    {
        "cliente": "Globalia (Air Europa)",
        "dia1": "Metodologías ágiles y soporte al desarrollo",
        "dia2": "agilismo, iOS",
		"dia3": "Metodologías ágiles y soporte al desarrollo",
        "dia4": "agilismo, iOS",
		"dia5": "Metodologías ágiles y soporte al desarrollo",
        "dia6": "agilismo, iOS",
		"dia7": "Metodologías ágiles y soporte al desarrollo",
        "dia8": "agilismo, iOS"
    },
    {
        "cliente": "Tinsa",
         "dia1": "Metodologías ágiles y soporte al desarrollo",
        "dia2": "agilismo, iOS",
		"dia3": "Metodologías ágiles y soporte al desarrollo",
        "dia4": "agilismo, iOS",
		"dia5": "Metodologías ágiles y soporte al desarrollo",
        "dia6": "agilismo, iOS",
		"dia7": "Metodologías ágiles y soporte al desarrollo",
        "dia8": "agilismo, iOS"
    },
    {
        "cliente": "Casa del Libro",
        "dia1": "Metodologías ágiles y soporte al desarrollo",
        "dia2": "agilismo, iOS",
		"dia3": "Metodologías ágiles y soporte al desarrollo",
        "dia4": "agilismo, iOS",
		"dia5": "Metodologías ágiles y soporte al desarrollo",
        "dia6": "agilismo, iOS",
		"dia7": "Metodologías ágiles y soporte al desarrollo",
        "dia8": "agilismo, iOS"
    }
  ];
	 $scope.gridOptions = {};
  $scope.cabecera = [{field: 'cliente', displayName: 'Cliente', width: '30%', pinnedLeft: true }];
  for(var i =1; i< 9; i++){
	  var a = {field: 'dia'+i, displayName: 'Dia'+i};
	   $scope.cabecera.push(a);
  }
  $scope.gridOptions = {
    enableSorting: true,
	enableColumnMenus: false,
	columnDefs: [
			$scope.cabecera
           
        ],
		rowHeight: '35px',
  };
  $scope.gridOptions.minimumColumnSize = 100;
  $scope.gridOptions.data =  $scope.tntPortfolio;
  
  
/*		
		Función para actualizar la tabla con los registros en la BD.
*/
$scope.tipos_servicio = function() {

	var tablaDatos1 = new Array();
	var indice1=0;
	$.post(  global_apiserver + "/tipos_servicio/getAll/", function( response ) {
		response = JSON.parse(response);
		$.each(response, function( indice, datos ) {
			

			tablaDatos1[indice1] = angular.fromJson(datos);
			indice1+=1;
		 
	   });
	   $scope.tablaDatos =  tablaDatos1;
	   $scope.$apply();
	});
}	
	
/*		
		Función para limpiar la información del módelo y que no se quede guardada
		después de realizar alguna transacción.
*/
$scope.limpiaCampos = function(){
	
	
}

/*		
		Función para hacer que aparezca el formulario de agregar tipos_servicio. Cambiamos el
		atributo de "accion" del boton guardar para tener una referencia a que tipo
		transacción se va a hacer (actualizar o insertar)
*/
$scope.InsertarTipoServicio = function(){

	

}
/*
		Función para hacer que aparezca el formulario de editar. Recibe de parámetro
		el id del tipo de servicio que se va a editar. Cambiamos el
		atributo de "accion" del boton guardar para tener una referencia a que tipo
		transacción se va a hacer (actualizar o insertar) y obtenemos la información
		del registro que se va a obtener para cambiar los valores en el módelo.
		
*/
$scope.EditarTipoServicio	=	function(tipo_servicio_id){
	
}
/*
		Función para hacer que desaparezca el formulario de agregar o editar y
		limpiamos los campos del módelo.
*/
$scope.cerrar = function() {		

	
		
};	
/*
		Valida si la información que tiene el módelo es suficiente apra agregar
		el nuevo registro. Aquí se modifica el valor de "$scope.respuesta" para checar
		la validez del módelo.
		Primero se verifica que los campos no sean nulos y en el caso del Acronimo
		se verifica que no se repita para ese Servicio.
		Además se muestra el error conrrespondiente en las etiquetas con los
		id "txtAcronimoerror","txtNombreerror","clavesServicioerror" y "txtTextoReferror".
*/
$scope.valida_agregar = function(){
		$scope.respuesta = 1;
		if($scope.txtAcronimo.length > 0 && $scope.claveServicio.length > 0){	
			$.ajax({
				type:'GET',
				dataType: 'json',
				async: false,
				url:global_apiserver + "/tipos_servicio/getIfExist/?acronimo="+$scope.txtAcronimo+"&id_servicio="+$scope.claveServicio,
				success: function(data){
					if(data.cantidad > 0){
						$scope.respuesta =  0;	
						notify("Error","Ya esta registrado este tipo de servicio para este Servicio","error");						
						//$("#nombreerror").text("Ya esta registrado este tipo de servicio para este Servicio");
						
					}else{
						$("#nombreerror").text("");
					}
				}
			});
		}else{
			
			
		}
		if($scope.txtNombre.length == 0){
			$scope.respuesta =  0;
			$("#txtNombreerror").text("No debe estar vacio");
		}else{
			$("#txtNombreerror").text("");
		}
		if($scope.txtTextoRef.length == 0){
			$scope.respuesta =  0;
			$("#txtTextoReferror").text("No debe estar vacio");
		}else{
			$("#txtTextoReferror").text("");
		}
		if($scope.txtAcronimo.length == 0){
			$scope.respuesta =  0;
			$("#txtAcronimoerror").text("No debe estar vacio");
		}else{
			$("#txtAcronimoerror").text("");
		}
		if($scope.claveServicio.length == 0){
			$scope.respuesta =  0;
			$("#claveServicioerror").text("No debe estar vacio");
		}else{
			$("#claveServicioerror").text("");
		}
	}

/*
		Se checa si es válida la modificación. 
		Con los id "xxxerror" mostramos
		el error correspondiente.
*/
	
	$scope.valida_editar = function(){
		
	}	
/*		
		Esta función nos sirve para hacer el insert o update. Se checa cual de las
		dos transacciones se debe de hacer. Al finalizar la transacción se actualiza
		la tabla.
*/
$scope.guardarTipoServicios = function(){
	
}
/*	Funcion para insertar los datos	*/
$scope.insertar	=	function(){

	
}
/*	Funcion para editar los datos	*/
$scope.editar	=	function(){

	
}
/*		
		Función para traer las claves de servicio.
*/
$scope.funcionClaveServicio = function(){
	
}

/*
	Funcion para traer las normas de este servicio
*/
$scope.funcionparalistanormas = function(){
   
	
}
/*
	Funcion para traer las normas que ya estan asociadas a ese servicio
*/
$scope.funciontiposervicionormas = function(id_tipo_servicio){
	
}


$(document).ready(function () {
	$scope.tipos_servicio();
	$scope.funcionClaveServicio();
	$scope.funcionparalistanormas(); 
	$scope.limpiaCampos();


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
