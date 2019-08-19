
/*
	Creación del controlador con el nombre "certi_auditores_controller".
*/

app.controller('certi_auditores_controller',['$scope','$http' ,function($scope,$http){
//Titulo que aparece en el html
	
	$scope.titulo = 'EVENTOS POR FECHAS';
	$scope.selectServicio = null;
	$scope.selectTiposServicio= null;
	$scope.selectRol = null;
	$scope.selectSector = null;
	$scope.columns = [];
	$scope.data = [];
	$scope.formDataNuevoEvento = {};
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
$scope.mesSelect = $scope.mesActual;
$scope.anhioSelect = $scope.anoActual;
/*		
		Función para generar la fecha donde comenzara la tabla (FECHA ACTUAL) .
*/
$scope.InicializarSelectMonthYear = function(){
		
	
	 $('#txtDate').datepicker({
     changeMonth: true,
     changeYear: true,
     dateFormat: 'MM yy',
     
     onClose: function(input,inst) {
        var iMonth = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
        var iYear = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
		$scope.mesSelect = iMonth;
		$scope.anhioSelect = iYear;
        $(this).datepicker('setDate', new Date(iYear, iMonth, 1));
		//Creando Cabecera
		$scope.HeadingTable(iYear,iMonth);
		$('.ui-datepicker-calendar').hide();
		inst.dpDiv.removeClass('noCalendar');
		
     },
       
     beforeShow: function(input, inst) {
		inst.dpDiv.addClass('noCalendar');
		if ((selDate = $(this).val()).length > 0) 
		{
			iYear = selDate.substring(selDate.length - 4, selDate.length);
			iMonth = jQuery.inArray(selDate.substring(0, selDate.length - 5), $(this).datepicker('option', 'monthNames'));
			$(this).datepicker('option', 'defaultDate', new Date(iYear, iMonth, 1));
			$(this).datepicker('setDate', new Date(iYear, iMonth, 1));
			
		}
		
    }
	
	
  });
   $('#txtDate').focusin(function(){
			$('.ui-datepicker-calendar').css('display','none');
		});	
  
}	
 
/*		
		Función que crea la cabecera de la tabla de forma dinamica.
*/
$scope.HeadingTable = function(ano,mes){
	$scope.columns = [];
	$scope.data = [];
	$scope.gridOptions = {};
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
			  var valor = grid.getCellValue(row,col).split("-");
			  switch(valor[0]){
				  case "Auditoria(C)":
					  return 'calidad';
					  break;
				  case "Auditoria(A)":
					  return 'ambiente';
					  break;
				  case "Auditoria(SAST)":
					  return 'sast';
					  break;
				  case "Auditoria(SGEN)":
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
				try{
						$scope.REFERENCIA = obj_results[i][valor].SCE.REFERENCIA;
						$scope.CLIENTE = obj_results[i][valor].SCE.NOMBRE;
						$scope.AUDITORES = obj_results[i][valor].SCE.AUDITORES;
						$scope.ID_SCE = obj_results[i][valor].SCE.ID;
						$scope.ID_AUDITOR = obj_results[i].ID;
						$("#modalDatosAuditoria").modal("show");
				}catch (e) {

				}
				

			}
		}
	};

async function getData(mes,anno)
    {	
		var id_servicio = '';
		var id_tiposervicio = '';
		var id_rol = '';
		var id_sector = '';
		if($scope.selectServicio != null){
			id_servicio = $scope.selectServicio.ID;
		}
		if($scope.selectTiposServicio != null){
			id_tiposervicio = $scope.selectTiposServicio.ID;
		}
		if($scope.selectRol != null){
			id_rol = $scope.selectRol.ID;
		}
		if($scope.selectSector != null){
			id_sector = $scope.selectSector.ID;
		}
		 
		//
    	$http.get(`${global_apiserver}/i_auditores_certi/getByMesyAno/?mes=${mes}&ano=${anno}&id_servicio=${id_servicio}&id_tiposervicio=${id_tiposervicio}&id_rol=${id_rol}&id_sector=${id_sector}`)
			.then(function( response ){
					$scope.data =  response.data;
				    $scope.gridOptions.data= [];
					$scope.gridOptions.data = $scope.data;
					$scope.optionsList=angular.fromJson($scope.data);
			});
	}
	
// ================================================================================
// *****                  Funcion Para mostrar los Servicios                  *****
// ================================================================================
$scope.TodosServicios = function ()
{
	$http.get(`${global_apiserver}/servicios/getAll/`)
			.then(function( response ){
					$scope.Servicios =  response.data;
				    
			});
}
// ================================================================================
// *****             Funcion Para mostrar los TiposServicios                  *****
// ================================================================================
$scope.TodosTiposServicios = function (id)
{
	$http.get(`${global_apiserver}/tipos_servicio/getByService/?id=`+id)
			.then(function( response ){
					$scope.tiposServicios =  response.data;
				    
			});
}
// ================================================================================
// *****             Funcion Para mostrar los Roles                  *****
// ================================================================================
$scope.TodosRoles = function (id)
{
	$http.get(`${global_apiserver}/personal_tecnico_roles/getByIdTipoServicio/?id=`+id)
			.then(function( response ){
					$scope.Roles =  response.data;
				    
			});
}
// ================================================================================
// *****             Funcion Para mostrar los Roles                  *****
// ================================================================================
$scope.TodosSectores = function (id)
{
	$http.get(`${global_apiserver}/sectores/getByIdTipoServicio/?id_tipo_servicio=`+id)
			.then(function( response ){
					$scope.Sectores =  response.data;
				    
			});
}
// ================================================================================
// *****                 Funcion Para cdo cambia un Servicio                  *****
// ================================================================================
$scope.changeServicio = function ()
{
	if($scope.selectServicio !=null){
		$scope.selectServicioValor = true;
		$scope.TodosTiposServicios($scope.selectServicio.ID);
	}
	else{
		$scope.selectServicioValor = false;
		$scope.selectTipoServicioValor = false;
		$scope.selectSectorValor = false;
	}
	
}	
// ================================================================================
// *****            Funcion Para cdo cambia un Tipo Servicio                  *****
// ================================================================================
$scope.changeTipoServicio = function ()
{
	if($scope.selectTiposServicio !=null){
		$scope.selectTipoServicioValor = true;
		$scope.TodosRoles($scope.selectTiposServicio.ID);
		if($scope.selectServicio.ID == 1){
			$scope.selectSectorValor = true;
			$scope.TodosSectores($scope.selectTiposServicio.ID);
		}
		else{
			$scope.selectSectorValor = false;
		}
	}
	else{
		$scope.selectTipoServicioValor = false;
		$scope.selectSectorValor = false;
	}
	
}	
// ==============================================================================
// ***** 		    Funcion mostrar opciones para filtrar           		*****
// ==============================================================================
$scope.showFiltrar = function()
{
	$scope.mytoggle('divFitrar');
}
// ================================================================================
// *****                  Funcion Mostrar/Ocultar elementos                   *****
// ================================================================================
$scope.mytoggle = function (id)
{
	$("#"+id).toggle(function(){

	},function(){

	});
	$scope.selectServicioValor = false;
	$scope.selectTipoServicioValor = false;
	$scope.selectSectorValor = false;
	$scope.selectServicio = null;
	$scope.selectTiposServicio= null;
	$scope.selectRol = null;
	$scope.selectSector = null;
}
// ================================================================================
// *****                  onchange cancelar filtro                       *****
// ================================================================================
$scope.cancelFilter = function()
{
//	$scope.tabla_servicios();

	$scope.mytoggle('divFitrar');
	$scope.HeadingTable($scope.anhioSelect,$scope.mesSelect);

}
// ==============================================================================
// ***** 		    Funcion btn filtrar accion                   		*****
// ==============================================================================
$scope.cargaDatosFiltrados = function() {
	$scope.HeadingTable($scope.anhioSelect,$scope.mesSelect);
}

// ==============================================================================
// ***** 	  Funcion para cargar modal de agregar eventos                 	*****
// ==============================================================================
$scope.agregar_evento =  function(){
	$scope.clear_modal_eventos();
	$("#modalAgregarEvento").modal("show");
}
// ===============================================================================
// *****	Funcion para limpiar las variables del modal de agregar eventos	 *****
// ===============================================================================
$scope.clear_modal_eventos =  function(){
	
	$scope.formDataNuevoEvento	=	{};
	$scope.formDataNuevoEvento.AUDITORES = [];
	
	
}
/*
		Funcion para Agregar el Evento
	*/
$scope.submitFormNuevoEvento = function(formDataNuevoEvento){
	var datos	=	{
				EVENTO	:	formDataNuevoEvento.EVENTO,
				FECHA_INICIO	:	formDataNuevoEvento.FECHA_INICIO,
				FECHA_FIN : formDataNuevoEvento.FECHA_FIN,
				AUDITORES : formDataNuevoEvento.AUDITORES,
				ID_USUARIO:	sessionStorage.getItem("id_usuario")
			};
			
	$.post(global_apiserver + "/i_auditores_certi/insert/", JSON.stringify(datos), function(respuesta){
				respuesta = JSON.parse(respuesta);
				if (respuesta.resultado == "ok") {
					notify("Éxito", "Se ha insertado el evento","success");
					$scope.HeadingTable($scope.anhioSelect,$scope.mesSelect);
				}
				else {
					notify('Error',respuesta.mensaje,'error');
				}
				$("#modalAgregarEvento").modal("hide");	
			});		
}
function  onCalendar() {
	$('#fechaInicio').datepicker({
                    dateFormat: "yy-mm-dd",
                    minDate: "+0D",
					onSelect: function (dateText, ins) {
                        $scope.formDataNuevoEvento.FECHA_INICIO = dateText;
                    }
                }).css('display' , 'inline-block');
	$('#fechaFin').datepicker({
                    dateFormat: "yy-mm-dd",
                    minDate: "+0D",
					onSelect: function (dateText, ins) {
                        $scope.formDataNuevoEvento.FECHA_FIN = dateText;
                    }
                }).css('display' , 'inline-block');		
				
}	
$(document).ready(function () {
	onCalendar();
	$scope.TodosServicios();
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
