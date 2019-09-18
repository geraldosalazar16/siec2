app.controller('dashboard_controller', ['$scope', '$http',function($scope,$http) { 
/***********************************************************************/
	var repCertVig = document.getElementById("RepCertVigChart");
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
$scope.graficaRepCertVig = function(){
	$.ajax({
		type:'GET',
		dataType: 'json',
		url:global_apiserver+"/i_reportes/getCantidadServiciosContratados1/",
		success:function(data){
		
			var mybarChart = new Chart(repCertVig, {
				type: 'line',
				data: {
					labels: data.X,
					datasets: [{
						label: 'Calidad',
						fill : false,
						borderColor: 'rgba(255, 0, 0, 1)',
						borderWidth: 1,
						data: data.Y1,
					},{
						label: 'Ambiente',
						fill : false,
						borderColor: 'rgba(255, 255, 0, 1)',
						borderWidth: 1,
						data: data.Y2,
					},{
						label: 'SAST',
						fill : false,
						borderColor: 'rgba(0, 255, 0, 1)',
						borderWidth: 1,
						data: data.Y3,
					},{
						label: 'Integral',
						fill : false,
						borderColor: 'rgba(0, 255, 255, 1)',
						borderWidth: 1,
						data: data.Y4,
					},{
						label: 'Energia',
						fill : false,
						borderColor: 'rgba(0, 0, 255, 1)',
						borderWidth: 1,
						data: data.Y5,
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
								beginAtZero: true
							}
						}]	
					}
					
				}
			});
	
		}
	});
};
// REPORTES CERTIFICADOS VIGENTES HISTORICO	
$scope.graficaRepCertVigHist = function(){
	$.ajax({
		type:'GET',
		dataType: 'json',
		url:global_apiserver+"/i_reportes/getCantidadServiciosContratadosHist/",
		success:function(data){
			
			var mybarChart = new Chart(repCertVigHist, {
				type: 'bar',
				data: {
					labels: data.X,
					datasets: [{
						label: 'Calidad',
						backgroundColor: 'rgba(255, 0, 0, 0.5)',
						data: data.Y1,
					},{
						label: 'Ambiente',
						backgroundColor: 'rgba(255, 255, 0, 0.5)',
						data: data.Y2,
					},{
						label: 'SAST',
						backgroundColor: 'rgba(0, 255, 0, 0.5)',
						data: data.Y3,
					},{
						label: 'Integral',
						backgroundColor: 'rgba(0, 255, 255, 0.5)',
						data: data.Y4,
					},{
						label: 'Energia',
						backgroundColor: 'rgba(0, 0, 255, 0.5)',
						data: data.Y5,
					}]
				},
								
			});
	
		}
	});
};

// REPORTES COMPARATIVA CONTRATACION	
$scope.graficaRepCompContr = function(){
	$.ajax({
		type:'GET',
		dataType: 'json',
		url:global_apiserver+"/i_reportes/getCompContratacion/",
		success:function(data){
			
			var mybarChart = new Chart(repCompContr, {
				type: 'line',
				data: {
					labels: data.X,
					datasets: [{
						label: 'AUDITORES EXTERNOS',
						fill : false,
						borderColor: 'rgba(255, 0, 0, 1)',
						borderWidth: 1,
						data: data.Y1,
					},{
						label: 'AUDITORES INTERNOS',
						fill : false,
						borderColor: 'rgba(0, 0, 255, 1)',
						borderWidth: 1,
						data: data.Y2,
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
								beginAtZero: true
							}
						}]	
					}
					
				}
			});
	
		}
	});
};

// REPORTES COMPARATIVA CONTRATACION HISTORICO	
$scope.graficaRepCompContrHist = function(){
	$.ajax({
		type:'GET',
		dataType: 'json',
		url:global_apiserver+"/i_reportes/getCompContratacionHist/",
		success:function(data){
			
			var mybarChart = new Chart(repCompContrHist, {
				type: 'bar',
				data: {
					labels: data.X,
					datasets: [{
						label: 'AUDITORES EXTERNOS (%)',
						backgroundColor: 'rgba(255, 0, 0, 0.7)',
						data: data.Y1,
					},{
						label: 'AUDITORES INTERNOS (%)',
						backgroundColor: 'rgba(0, 0, 255, 0.7)',
						data: data.Y2,
					}]
				},

				options: {
					
					scales: {
						xAxes: [{
							stacked: true

						}],
						yAxes: [{
							stacked: true
						}]	
					}
					
				}	
			});
	
		}
	});
};

// REPORTES MEZCLA DE PORTAFOLIO	
$scope.graficaRepMezclaPort = function(){
	$.ajax({
		type:'GET',
		dataType: 'json',
		url:global_apiserver+"/i_reportes/getMezclaPortafolio/",
		success:function(data){
			
			var mybarChart = new Chart(repMezclaPort, {
				type: 'line',
				data: {
					labels: data.X,
					datasets: [{
						label: 'SECTOR PUBLICO',
						fill : false,
						borderColor: 'rgba(255, 0, 0, 1)',
						borderWidth: 1,
						data: data.Y1,
					},{
						label: 'SECTOR PRIVADO',
						fill : false,
						borderColor: 'rgba(0, 0, 255, 1)',
						borderWidth: 1,
						data: data.Y2,
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
								beginAtZero: true
							}
						}]	
					}
					
				}
			});
	
		}
	});
};

// REPORTES COMPARATIVA CONTRATACION HISTORICO	
$scope.graficaRepMezclaPortHist = function(){
	$.ajax({
		type:'GET',
		dataType: 'json',
		url:global_apiserver+"/i_reportes/getMezclaPortafolioHist/",
		success:function(data){
			
			var mybarChart = new Chart(repMezclaPortHist, {
				type: 'bar',
				data: {
					labels: data.X,
					datasets: [{
						label: 'SECTOR PUBLICO (%)',
						backgroundColor: 'rgba(255, 0, 0, 0.7)',
						data: data.Y1,
					},{
						label: 'SECTOR PRIVADO (%)',
						backgroundColor: 'rgba(0, 0, 255, 0.7)',
						data: data.Y2,
					}]
				},

				options: {
					
					scales: {
						xAxes: [{
							stacked: true

						}],
						yAxes: [{
							stacked: true
						}]	
					}
					
				}	
			});
	
		}
	});
};

// REPORTES CERTIFICADOS EMITIDOS SG	
$scope.graficarepCertEmitSG = function(){
	$.ajax({
		type:'GET',
		dataType: 'json',
		url:global_apiserver+"/i_reportes/getCertEmitSG/",
		success:function(data){
			
			var mybarChart = new Chart(repCertEmitSG, {
				type: 'bar',
				data: {
					labels: data.X,
					datasets: [{
						label: 'Cantidad Certificados Emitidos de SG',
						backgroundColor: 'rgba(255, 0, 0, 0.5)',
						data: data.Y1,
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
								beginAtZero: true
							}
						}]	
					}
					
				}
			});
	
		}
	});
};
// REPORTES CERTIFICADOS EMITIDOS SG HISTORICO	
$scope.graficarepCertEmitSGHist = function(){
	$.ajax({
		type:'GET',
		dataType: 'json',
		url:global_apiserver+"/i_reportes/getCertEmitSGHist/",
		success:function(data){
			
			var mybarChart = new Chart(repCertEmitSGHist, {
				type: 'bar',
				data: {
					labels: data.X,
					datasets: [{
						label: 'Cantidad Certificados Emitidos de SG',
						backgroundColor: 'rgba(255, 0, 0, 0.5)',
						data: data.Y1,
					}]
				},

				options: {
					
					scales: {
						xAxes: [{
							stacked: true

						}],
						yAxes: [{
							stacked: true
						}]	
					}
					
				}	
			});
	
		}
	});
};

// REPORTES SERVICIOS REALIZADOS SG	
$scope.graficarepServRealizSG = function(){
	$.ajax({
		type:'GET',
		dataType: 'json',
		url:global_apiserver+"/i_reportes/getServRealizSG/",
		success:function(data){
			
			var mybarChart = new Chart(repServRealizSG, {
				type: 'bar',
				data: {
					labels: data.X,
					datasets: [{
						label: 'Cantidad Servicios Realizados de SG',
						backgroundColor: 'rgba(255, 0, 0, 0.5)',
						data: data.Y1,
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
								beginAtZero: true
							}
						}]	
					}
					
				}
			});
	
		}
	});
};

// REPORTES SERVICIOS REALIZADOS SG HISTORICO	
$scope.graficarepServRealizSGHist = function(){
	$.ajax({
		type:'GET',
		dataType: 'json',
		url:global_apiserver+"/i_reportes/getServRealizSGHist/",
		success:function(data){
			
			var mybarChart = new Chart(repServRealizSGHist, {
				type: 'bar',
				data: {
					labels: data.X,
					datasets: [{
						label: 'Cantidad Servicios Realizados de SG',
						backgroundColor: 'rgba(255, 0, 0, 0.5)',
						data: data.Y1,
					}]
				},

				options: {
					
					scales: {
						xAxes: [{
							stacked: true

						}],
						yAxes: [{
							stacked: true
						}]	
					}
					
				}	
			});
	
		}
	});
};


// REPORTES DIAS AUDITOR SG	
$scope.graficarepDiasAudSG = function(){
	$.ajax({
		type:'GET',
		dataType: 'json',
		url:global_apiserver+"/i_reportes/getDiasAudSG/",
		success:function(data){
			
			var mybarChart = new Chart(repDiasAudSG, {
				type: 'bar',
				data: {
					labels: data.X,
					datasets: [{
						label: 'Cantidad Dias Auditor de SG',
						backgroundColor: 'rgba(255, 0, 0, 0.5)',
						data: data.Y1,
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
								beginAtZero: true
							}
						}]	
					}
					
				}
			});
	
		}
	});
}

// REPORTES DIAS AUDITOR SG	HISTORICO	
$scope.graficarepDiasAudSGHist = function(){
	$.ajax({
		type:'GET',
		dataType: 'json',
		url:global_apiserver+"/i_reportes/getDiasAudSGHist/",
		success:function(data){
			
			var mybarChart = new Chart(repDiasAudSGHist, {
				type: 'bar',
				data: {
					labels: data.X,
					datasets: [{
						label: 'Cantidad Dias Auditor de SG',
						backgroundColor: 'rgba(255, 0, 0, 0.5)',
						data: data.Y1,
					}]
				},

				options: {
					
					scales: {
						xAxes: [{
							stacked: true

						}],
						yAxes: [{
							stacked: true
						}]	
					}
					
				}	
			});
	
		}
	});
};

//Actualizar los estados de solicitudes q estén vencidas
function ActualizarSolicitVenc() {
	data = {            
		id_usuario: sessionStorage.getItem("id_usuario")
	}
	$http.post(`${global_apiserver}/facturacion_solicitudes/ActualizarEstatusTodo/`, data)
	.then(response=>{
	if (response.data.resultado != 'ok') {
		notify('Error', response.data.message, 'error');
	}
	 else
		notify('Éxito', 'Se actualizaron estados de solicitudes', 'success');})
	.catch(error => notify('Error', error.message, 'error'))

}

ActualizarSolicitVenc();
$scope.graficaRepCertVig();
$scope.graficaRepCertVigHist();
$scope.graficaRepCompContr();
$scope.graficaRepCompContrHist();
$scope.graficaRepMezclaPort();
$scope.graficaRepMezclaPortHist();
$scope.graficarepCertEmitSG();
$scope.graficarepCertEmitSGHist();
$scope.graficarepServRealizSG();
$scope.graficarepServRealizSGHist();
$scope.graficarepDiasAudSG();
$scope.graficarepDiasAudSGHist();
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


}]);