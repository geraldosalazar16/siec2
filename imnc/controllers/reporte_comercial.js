app.controller('reporte_comercial_controller', ['$scope', function($scope){
	$scope.estatus = "";
	$scope.search = {};
	$scope.search.NOMBRE = "";
	$scope.search.INICIALES = "";
	$scope.search.CONTRATO = "";
	$scope.search.COTIZACION = "";
	$scope.search.ESTATUS = "";
	
	
	$scope.envia = function(){

			FECHA_INICIO = $("#datepicker").val().replace(/-/g,"");
			FECHA_FIN =   $("#datepicker2").val().replace(/-/g,"");
			NOMBRE = $scope.search.NOMBRE;
			EJECUTIVO =  $scope.search.INICIALES;
			CONTRATO = $scope.search.CONTRATO;
			COTIZACION = $scope.search.COTIZACION;
			BANDERA = "1";
			ESTATUS = $scope.ESTATUS;
			document.location.href = global_apiserver + "/cotizaciones/getByFiltro/?NOMBRE="+NOMBRE+"&FECHA_INICIO="+FECHA_INICIO+"&FECHA_FIN="+FECHA_FIN+"&EJECUTIVO="+EJECUTIVO+"&CONTRATO="+CONTRATO+"&COTIZACION="+COTIZACION+"&BANDERA="+BANDERA+"&ESTATUS="+ESTATUS;
		
		
	}
	
	
	
	
	$scope.lista = function(){
		$scope.titulo = "Reportes";
		$scope.numero = "#";
		$scope.nombre = "Descripcion";
		$scope.search = {};
		$.ajax({
			type:'GET',
			url:global_apiserver + "/prospecto_estatus_seguimiento/getAll/",
			success: function(data){
				$scope.$apply(function(){
					$scope.estatus = angular.fromJson(data);

				});
			}
		});
		
		
		/*$.ajax({
			type:'GET',
			url:global_apiserver + "/cita_calendario/reporte/",
			success: function(data){
				$scope.$apply(function(){
                $scope.calendario = angular.fromJson(data);
				$scope.fechas=[	
				{id : "-01-", nombre : "Enero"},
				{id : "-02-", nombre : "Febrero"},
				{id : "-03-", nombre : "Marzo"},
				{id : "-04-", nombre : "Abril"},
				{id : "-05-", nombre : "Mayo"},
				{id : "-06-", nombre : "Junio"},
				{id : "-07-", nombre : "Julio"},
				{id : "-08-", nombre : "Agosto"},
				{id : "-09-", nombre : "Septiembre"},
				{id : "-10-", nombre : "Octubre"},
				{id : "-11-", nombre : "Noviembre"},
				{id : "-12-", nombre : "Diciembre"},
				];
				$scope.currentMonth = "Buscar...";
				$scope.setCurrentMonth = function(month){
					$scope.currentMonth = month;
				}
				
			
				});
			}
		});*/
	}
	/*$scope.range = function(value, index, array) {
		var fecha = Date.parse(value.FECHA_INICIO);
        var inicio = Date.parse($scope.iFecha);
        var fin = Date.parse($scope.fFecha);
        var valInicio = true;
        var valFin = true;
        var val1 = true;
        var val2 = true;
        var val3 = true;
        console.log(value.FOLIO);
        if(Boolean($scope.search.NOMBRE))
       		var val1 = value.NOMBRE.toUpperCase().includes($scope.search.NOMBRE.toUpperCase());
       	if(Boolean($scope.search.ASUNTO))
       		var val2 = value.ASUNTO.toUpperCase().includes($scope.search.ASUNTO.toUpperCase());
       	if(Boolean($scope.search.DESCRIPCION))
       		var val2 = value.DESCRIPCION.toUpperCase().includes($scope.search.DESCRIPCION.toUpperCase());
       	if(Boolean($scope.search.FOLIO))
       		var val2 = value.FOLIO.toUpperCase().includes($scope.search.FOLIO.toUpperCase());
       	if(Boolean(inicio))
       		var valFin = fecha > inicio;
       	if(Boolean(fin))
       		var valFin = fecha < fin;
		return valInicio && valFin && val1 && val2 && val3;
	}*/

	$scope.lista();


$(document).ready(function(){
	 
$('#datepicker').datepicker({
                    dateFormat: "yy-mm-dd",
                    showButtonPanel: true ,
                    onSelect: function (dateText, ins) {
                        $scope.iFecha = dateText;
                        $scope.$apply();

                    }
        }).css("display", "inline-block");
$('#datepicker2').datepicker({
                    dateFormat: "yy-mm-dd",
                    showButtonPanel: true , 
                    onSelect: function (dateText, ins) {
                        $scope.fFecha = dateText;
                        $scope.$apply();
                    }
        }).css("display", "inline-block");
})

}]);
