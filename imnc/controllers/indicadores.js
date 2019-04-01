app.controller('indicadores_controller', ['$scope', function($scope,$http) { 
/***********************************************************************/
	var IndEnvPlanAud = document.getElementById("IndEnvPlanAudChart");
	var IndEnvPlanAud1 = document.getElementById("IndEnvPlanAudChart1");
	var IndProgOportVig = document.getElementById("IndProgOportVigChart");
	var IndProgOportRen = document.getElementById("IndProgOportRenChart");
	var IndTiempoEntregInf = document.getElementById("IndTiempoEntregInfChart");
	var IndTomDeDec = document.getElementById("IndTomDeDecChart").getContext('2d');
	
	var hoy = new Date();
	$scope.ano_actual = hoy.getFullYear();
// REPORTES ENVIO PLAN AUDITORIA	
$scope.graficaIndEnvPlanAud = function(){
	$.ajax({
		type:'GET',
		dataType: 'json',
		url:global_apiserver+"/i_indicadores/getEnvioPlanAuditoria/",
		success:function(data){
		
			var mybarChart = new Chart(IndEnvPlanAud, {
				type: 'bar',
				data: {
					labels: data.X,
					datasets: [{
						label: 'Auditorias enviadas con su plan (%)',
						backgroundColor: 'rgba(255, 0, 0, 0.5)',
						data: data.Z1,
					}]
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
								beginAtZero: true,   
								steps: 10,
								stepValue: 5,
								max: 100
							}
						}]	
					}
					
				}
				
			});
			
		
		}
	});
};
// REPORTES ENVIO PLAN AUDITORIA	
$scope.graficaIndEnvPlanAud1 = function(){
	$.ajax({
		type:'GET',
		dataType: 'json',
		url:global_apiserver+"/i_indicadores/getEnvioPlanAuditoria/",
		success:function(data){
		
			var mybarChart = new Chart(IndEnvPlanAud1, {
				type: 'bar',
				data: {
					labels: data.X,
					datasets: [{
						label: 'Auditorias enviadas con su plan que cumplen los 5 dias (%)',
						backgroundColor: 'rgba(255, 0, 0, 0.5)',
						data: data.Z1,
					},{
						label: 'Auditorias enviadas con su plan que no cumplen los 5 dias(%)',
						backgroundColor: 'rgba(0, 255, 0, 0.5)',
						data: data.Z2,
					},{
						label: 'Auditorias enviadas sin su plan (%)',
						backgroundColor: 'rgba(0, 0, 255, 0.5)',
						data: data.Z3,
					}]
				},
				options: {
					
					scales: {
						xAxes: [{
							stacked: true

						}],
						yAxes: [{
							stacked: true,
							ticks: {
								beginAtZero: true,   
								steps: 10,
								stepValue: 5,
								max: 100
							}
						}]	
					}
					
				}	
				
			});
			
		
		}
	});
};
// REPORTES PROGRAMACIONES OPORTUNAS VIGILANCIAS	
$scope.graficaIndProgOportVigChart = function(){
	$.ajax({
		type:'GET',
		dataType: 'json',
		url:global_apiserver+"/i_indicadores/getProgramacionesOportunasVig/",
		success:function(data){
		
			var mybarChart = new Chart(IndProgOportVig, {
				type: 'bar',
				data: {
					labels: data.X,
					datasets: [{
						label: 'Auditorias de vigilancias con programacion oportuna que cumplen con la regla de los 30 dias(%)',
						backgroundColor: 'rgba(255, 0, 0, 0.5)',
						data: data.Z1,
					},{
						label: 'Auditorias de vigilancias con programacion oportuna que no cumplen con la regla de los 30 dias(%)',
						backgroundColor: 'rgba(0, 0, 255, 0.5)',
						data: data.Z2,
					}]
				},
				options: {
					
					scales: {
						xAxes: [{
							stacked: true

						}],
						yAxes: [{
							stacked: true,
							ticks: {
								beginAtZero: true,   
								steps: 10,
								stepValue: 5,
								max: 100
							}
						}]	
					}
					
				}	
				
			});
			
		
		}
	});
};
// REPORTES PROGRAMACIONES OPORTUNAS RENOVACION	
$scope.graficaIndProgOportRenChart = function(){
	$.ajax({
		type:'GET',
		dataType: 'json',
		url:global_apiserver+"/i_indicadores/getProgramacionesOportunasRen/",
		success:function(data){
		
			var mybarChart = new Chart(IndProgOportRen, {
				type: 'bar',
				data: {
					labels: data.X,
					datasets: [{
						label: 'Auditorias de renovacion con programacion oportuna que cumplen con la regla de los 30 dias(%)',
						backgroundColor: 'rgba(255, 0, 0, 0.5)',
						data: data.Z1,
					},{
						label: 'Auditorias de renovacion con programacion oportuna que no cumplen con la regla de los 30 dias(%)',
						backgroundColor: 'rgba(0, 0, 255, 0.5)',
						data: data.Z2,
					}]
				},
				options: {
					
					scales: {
						xAxes: [{
							stacked: true

						}],
						yAxes: [{
							stacked: true,
							ticks: {
								beginAtZero: true,   
								steps: 10,
								stepValue: 5,
								max: 100
							}
						}]	
					}
					
				}	
				
			});
			
		
		}
	});
};
// REPORTES TIEMPO DE ENTREGA DEL INFORME	
$scope.graficaIndTiempoEntregInf = function(){
	$.ajax({
		type:'GET',
		dataType: 'json',
		url:global_apiserver+"/i_indicadores/getTiempoEntregaInforme/",
		success:function(data){
		
			var mybarChart = new Chart(IndTiempoEntregInf, {
				type: 'bar',
				data: {
					labels: data.X,
					datasets: [{
						label: 'Reportes de auditoria con su informe que cumplen los 7 dias (%)',
						backgroundColor: 'rgba(255, 0, 0, 0.5)',
						data: data.Z1,
					},{
						label: 'Reportes de auditoria con su informe que no cumplen los 7 dias(%)',
						backgroundColor: 'rgba(0, 255, 0, 0.5)',
						data: data.Z2,
					},{
						label: 'Reportes de auditoria sin su informe (%)',
						backgroundColor: 'rgba(0, 0, 255, 0.5)',
						data: data.Z3,
					}]
				},
				options: {
					
					scales: {
						xAxes: [{
							stacked: true

						}],
						yAxes: [{
							stacked: true,
							ticks: {
								beginAtZero: true,   
								steps: 10,
								stepValue: 5,
								max: 100
							}
						}]	
					}
					
				}	
				
			});
			
		
		}
	});
};
// REPORTES TOMA DE DECISION	
$scope.graficaIndTomDeDec = function(){
	$.ajax({
		type:'GET',
		dataType: 'json',
		url:global_apiserver+"/i_indicadores/getTomaDeDecision/",
		success:function(data){
		
			var mybarChart = new Chart(IndTomDeDec, {
				type: 'bar',
				data: {
					labels: data.X,
					datasets: data.Y
				},
				options: {
				
					scales: {
						xAxes: [{
							stacked: true

						}],
						yAxes: [{
							stacked: true,
							ticks: {
								beginAtZero: true,   
								steps: 10,
								stepValue: 5,
								max: 100
							}
						}]	
					}
					
				}	
				
			});
			
		
		}
	});
};
$scope.graficaIndEnvPlanAud();
$scope.graficaIndEnvPlanAud1();
$scope.graficaIndProgOportVigChart();
$scope.graficaIndProgOportRenChart();
$scope.graficaIndTiempoEntregInf();
$scope.graficaIndTomDeDec();
/***********************************************************************/	

function notify(titulo, texto, tipo) {
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

//$scope.graficaOrigen();
//$scope.graficaEstatus();
//$scope.graficaEntidad();
//$scope.graficaCompetenciaU();
//$scope.graficaOrigenU();
//$scope.graficaEstatusU();
//$scope.graficaEntidadU();
//onCalendar();
 
}]);