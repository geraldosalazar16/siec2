
/*
	Creación del controlador con el nombre "certi_auditores_controller".
*/

app.controller('certi_auditores_controller',['$scope','$http' ,function($scope,$http){
//Titulo que aparece en el html
	
	$scope.titulo = 'CERTI AUDITORES';
	//$scope.cabecera =this;	
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
/*	 $scope.gridOptions = {
    enableSorting: true,
	columnDefs: $scope.columns,
	enableColumnMenus: false,
	rowHeight: '35px',
  };*/
 //FECHA DE HOY
$scope.mesActual= moment().format('M'); 
$scope.anoActual= moment().format('YYYY'); 
/*		
		Función para generar la fecha donde comenzara la tabla (FECHA ACTUAL) .
*/
$scope.InicializarSelectMonthYear = function(){
	 $('#txtDate').datepicker({
     changeMonth: true,
     changeYear: true,
     dateFormat: 'MM yy',
       
     onClose: function() {
        var iMonth = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
        var iYear = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
        $(this).datepicker('setDate', new Date(iYear, iMonth, 1));
		//Creando Cabecera
		$scope.HeadingTable(iYear,iMonth);
     },
       
     beforeShow: function() {
		if ((selDate = $(this).val()).length > 0) 
		{
			iYear = selDate.substring(selDate.length - 4, selDate.length);
			iMonth = jQuery.inArray(selDate.substring(0, selDate.length - 5), $(this).datepicker('option', 'monthNames'));
			$(this).datepicker('option', 'defaultDate', new Date(iYear, iMonth, 1));
			$(this).datepicker('setDate', new Date(iYear, iMonth, 1));
		}
		
    }
	
	
  });
  
}	
/*		
		Función que crea la cabecera de la tabla de forma dinamica.
*/
$scope.HeadingTable = function(ano,mes){
	/**/
	
	//AQUI INICIALIZO gridOptions
	$scope.columns = [{field: 'Auditor', displayName: 'Auditor',width: '30%', pinnedLeft: true }];
 
 $scope.gridOptions.enableSorting =true;
 $scope.gridOptions.columnDefs =$scope.columns;
 $scope.gridOptions.enableColumnMenus =false;
 
 //$scope.gridOptions.rowHeight ='35px';
/* $scope.gridOptions.onRegisterApi= function(gridApi){ 
		$scope.gridApi = gridApi;
		
	}
*/

 // $scope.gridOptions.columnDefs = $scope.cabecera;
  $scope.gridOptions.minimumColumnSize = 100;
  
  
 // $scope.gridOptions.data =  $scope.tntPortfolio;

	/**/
	var diaMayor=0;
	switch(parseInt(mes)){
		case 0:
			diaMayor = 31;
			break;
		case 1:
			if(parseInt(ano)%4 == 0){
				diaMayor = 29;
			}
			else{
				diaMayor = 28;
			}
			
			break;
		case 2:
			diaMayor = 31;
			break;
		case 3:
			diaMayor = 30;
			break;
		case 4:
			diaMayor = 31;
			break;
		case 5:
			diaMayor = 30;
			break;
		case 6:
			diaMayor = 31;
			break;
		case 7:	
			diaMayor = 31;
			break;
		case 8:
			diaMayor = 30;
			break;
		case 9:
			diaMayor = 31;
			break;
		case 10:
			diaMayor = 30;
			break;
		case 11:
			diaMayor = 31;
			break;
	}
	
	
  //$scope.cabecera.columns = [{field: 'cliente', displayName: 'Cliente', width: '20%', pinnedLeft: true }];
	$scope.columns.splice(1);
  for(var i =1; i<= diaMayor; i++){
	  var abc1='';
	  var fecha = (parseInt(mes)+1)+'-'+i+'-'+ano;
	   abc1 = moment(fecha).format('ddd');
	  var a = {field: 'd'+i, displayName: abc1+' '+i};
	   $scope.columns.push(a);
  }
 
	//then later
	$http.get(  global_apiserver + "/i_auditores_certi/getByMesyAno/?mes="+mes+"&ano="+ano)
		.then(function( response ){
			$scope.gridOptions.data = response.data;
			
		});
//$scope.gridApi.core.refresh();
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
	$scope.funcionClaveServicio();
	$scope.funcionparalistanormas(); 
	$scope.limpiaCampos();
	$scope.InicializarSelectMonthYear();
	$('#txtDate').datepicker('setDate', new Date());
	$scope.HeadingTable($scope.anoActual,$scope.mesActual-1);
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
