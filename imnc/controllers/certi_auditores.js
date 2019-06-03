
/*
	Creación del controlador con el nombre "certi_auditores_controller".
*/

app.controller('certi_auditores_controller',['$scope','$http' ,function($scope,$http){
//Titulo que aparece en el html
	
	$scope.titulo = 'CERTI AUDITORES';
	$scope.columns = [];
	$scope.data = [];

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

//$scope.gridApi.core.refresh();

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
	$scope.columns.push({field: 'ID', displayName: 'ID',width: '5%',pinnedLeft: true,enableColumnMenu: false });
	$scope.columns.push({field: 'Auditor', displayName: 'Auditor',width: '30%', pinnedLeft: true,enableColumnMenu: false });
	 for(var i =1; i<= diaMayor; i++){
	  var abc1='';
	  var dia = '';
	  var fecha = (parseInt(mes)+1)+'/'+i+'/'+ano;
	   abc1 = moment(fecha).format('ddd');
	  var a = {
	  	  field: 'd'+i,
		  displayName: abc1+' '+i,
		  cellTemplate: '<div style="cursor: pointer;"><div ng-click="grid.appScope.showDescription(row.entity.ID,'+"'"+i+"'"+')" title="{{COL_FIELD}}" class="ui-grid-cell-contents">{{COL_FIELD CUSTOM_FILTERS}}</div></div>',
		  // cellTemplate:'<div style="width: 100%; height: 30px; font-size: 13px"  ng-click="grid.appScope.showDescription(row,'+"'"+abc1+i+"'"+')">{{COL_FIELD CUSTOM_FILTERS}}</div>',
		  cellClass : function(grid,row,col,rowRenderIndex,colRenderIndex){
			  switch(grid.getCellValue(row,col)){
				  case 'Auditoria(C) para esta fecha.		':
					  return 'calidad';
					  break;
				  case 'Auditoria(A) para esta fecha.		':
					  return 'ambiente';
					  break;
				  case 'Auditoria(SAST) para esta fecha.		':
					  return 'sast';
					  break;
				  case 'Auditoria(SGEN) para esta fecha.		':
					  return 'sgen';
					  break;
				  default:
					  return 'def';
					  break;
			  }
		  }
	  };
	   $scope.columns.push(a);
	 }



	$scope.gridOptions = {
		enableSelectAll: false,
		enableRowHeaderSelection: false,
		enableGridMenu: true,
		enableSorting: true,
		columnDefs: $scope.columns,
		paginationPageSizes: [25, 50, 100, 200, 500],
		paginationPageSize: 5,
		rowHeight: '35px',
		minimumColumnSize: 100,
		multiSelect: false,
		enableRowSelection : true,
		onRegisterApi: function(gridApi) {
			$scope.gridApi = gridApi;
		},


			};

	getData(mes,ano);

}


	$scope.showDescription = function(elem_Id,valor){

		var obj_results = $scope.data;
		for (var i in obj_results){
			if (obj_results[i].ID === elem_Id){
				$scope.REFERENCIA = obj_results[i][valor].SCE.REFERENCIA;
				$scope.CLIENTE = obj_results[i][valor].SCE.NOMBRE;
				$scope.AUDITORES = obj_results[i][valor].SCE.AUDITORES;
				$scope.ID_SCE = obj_results[i][valor].SCE.ID;
				$scope.ID_AUDITOR = obj_results[i].ID;
				$("#modalDatosAuditoria").modal("show");
			}
		}
	};

async function getData(mes,anno)
    {
		// //Obtener todas las auditorias del servicio
		// let response = await $http.get(`${global_apiserver}/i_auditores_certi/getByMesyAno/?mes=${mes}&ano=${anno}`);
		// if (response.data.resultado === 'error') {
		// 	$scope.gridOptions.data = [];
		// } else {
		// 	$scope.gridOptions.data = response.data;
		// }
		$http.get(`${global_apiserver}/i_auditores_certi/getByMesyAno/?mes=${mes}&ano=${anno}`)
			.then(function( response ){
					$scope.data =  response.data;
					$scope.gridOptions.data = $scope.data;
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
