
/*
	Creación del controlador con el nombre "indicadores_programacion_tasa_ocupacional_instituto_controller".
*/

app.controller('indicadores_programacion_tasa_ocupacional_instituto_controller',['$scope',function($scope,$http){
//Titulo que aparece en el html
	
	var f = new Date();
	$scope.titulo = 'Indicadores tasa ocupacional';
	$scope.texto_tabla = 'Año '+f.getFullYear();
	$scope.M = [
					{ID:0,NOMBRE:'Enero'},
					{ID:1,NOMBRE:'Febrero'},
					{ID:2,NOMBRE:'Marzo'},
					{ID:3,NOMBRE:'Abril'},
					{ID:4,NOMBRE:'Mayo'},
					{ID:5,NOMBRE:'Junio'},
					{ID:6,NOMBRE:'Julio'},
					{ID:7,NOMBRE:'Agosto'},
					{ID:8,NOMBRE:'Septiembre'},
					{ID:9,NOMBRE:'Octubre'},
					{ID:10,NOMBRE:'Noviembre'},
					{ID:11,NOMBRE:'Diciembre'}];
				
	$scope.mes_select = f.getMonth();
	var repCertVigHist = document.getElementById("RepCertVigHistChart");
	
/*		
		Función para actualizar la tabla con los registros en la BD.
*/
$scope.Traer_Datos = function() {

	$.post(  global_apiserver + "/i_reportes/getTasaOcupacionalInstituto/", function( response ) {
		response = JSON.parse(response);
			
	   $scope.tablaDatos =  response;
	   $scope.graficaTasaOcupInstituto();
	   $scope.$apply();
	});
}	
/*		
		Función para cuando existe un cambio en el select de los meses.
*/
$scope.CambioMes = function() {

	//alert($scope.mes_select);
	//$scope.graficaRepCertVigHist();
	
}	

// REPORTES TASA OCUPACIONAL HISTORICO POR AUDITOR	
$scope.graficaTasaOcupInstituto = function(id_auditor){
	/*
		window.onload = function(){
    var ctx = document.getElementById("canvas").getContext("2d");
    window.myLine = new Chart(ctx).Line(lineChartData, {
        scaleOverride : true,
        scaleSteps : 10,
        scaleStepWidth : 50,
        scaleStartValue : 0 
    });
}
	*/
	var EjeX = ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"];
	
	
	var EjeY = $scope.tablaDatos.TASA_OCUPACIONAL;
	$scope.texto_grafico = "Reporte Tasa Ocupacional del año para el Instituto";

			var mybarChart = new Chart(repCertVigHist, {
				type: 'bar',
				data: {
					labels: EjeX,
					datasets: [{
						label: 'Tasa Ocupacional',
						backgroundColor: 'rgba(255, 0, 0, 0.5)',
						data: EjeY,
						
					}],
					options: {
					scales: {
						
						yAxes: [{
							ticks: {
								beginAtZero: true,
								steps: 10,
                                stepValue: 10,
                                max: 100 //max value for the chart is 100
							}
						}]	
					}
										
				}
					
				},
				options: {
					scales: {
						xAxes: [{
							stacked: true,
							ticks: {
								autoSkip: false
							}
						}],
						yAxes: [{
							ticks: {
								beginAtZero: true
							}
						}]	
					}
										
				}
				
			});
	
	$("#modalHistoricoAuditor").modal("show");	
};
// =======================================================================================
// ***** 		                 	FUNCION EXPORTAR EXCEL                        *****
// =======================================================================================
    $scope.exportExcel = function() {
        var url = "./generar/xls/indicadores/tasa_ocupacional/index.php?mes="+$scope.mes_select;
        window.open(url,'_blank');
    }

$(document).ready(function () {
	$scope.Traer_Datos();
	



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
