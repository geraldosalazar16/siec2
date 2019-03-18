app.controller('indicadores_controller', ['$scope', function($scope,$http) { 
/***********************************************************************/
	var IndEnvPlanAud = document.getElementById("IndEnvPlanAudChart");
	var repCertVigHist = document.getElementById("RepCertVigHistChart");
	var repCompContr = document.getElementById("repCompContrChart");
	var repCompContrHist = document.getElementById("repCompContrHistChart");
	var repMezclaPort = document.getElementById("repMezclaPortChart");
	var repMezclaPortHist = document.getElementById("repMezclaPortHistChart");
	var repCertEmitSG = document.getElementById("repCertEmitSGChart");
	var repCertEmitSGHist = document.getElementById("repCertEmitSGHistChart");
	var repServRealizSG = document.getElementById("repServRealizSGChart");
	var repServRealizSGHist = document.getElementById("repServRealizSGHistChart");
	var repDiasAudSG = document.getElementById("repDiasAudSGChart");
	var repDiasAudSGHist = document.getElementById("repDiasAudSGHistChart");
	var hoy = new Date();
	$scope.ano_actual = hoy.getFullYear();
// REPORTES CERTIFICADOS VIGENTES	
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
						data: data.Z,
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


$scope.graficaIndEnvPlanAud();

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