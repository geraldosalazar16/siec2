app.controller('dashboard_controller', ['$scope', function($scope,$http) { 
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
var origeng = document.getElementById("origenChart");
var estatusg = document.getElementById("estatusChart");
var competenciag = document.getElementById("competenciaChart");
var entidadg = document.getElementById("entidadChart");
var origengu = document.getElementById("origenChartU");
var estatusgu = document.getElementById("estatusChartU");
var competenciagu = document.getElementById("competenciaChartU");
var entidadgu = document.getElementById("entidadChartU");
var origen = [];
var cantidadOrigen = [];
var estatus = [];
var cantidadEstatus = [];
var competencia = [];
var cantidadCompetencia = [];
var entidad = [];
var cantidadEntidad= [];

var origenU = [];
var cantidadOrigenU = [];
var estatusU = [];
var cantidadEstatusU = [];
var competenciaU = [];
var cantidadCompetenciaU = [];
var entidadU = [];
var cantidadEntidadU= [];
	
$scope.calcular_hit_rate_valor_certificaciones = function(){
	var datos = {
		f_ini : $("#fecha_inicio_cert").val(),
		f_fin : $("#fecha_fin_cert").val()
	}
	alert('En desarrollo');
	/*
	$.post(global_apiserver + "/reporte/hitRateValorCertificaciones/", JSON.stringify(datos), function(response){
		respuesta = JSON.parse(response);
		if (respuesta.resultado == "ok") {

			notify_success("Éxito", "Se ha insertado la tarea");
		}
		else{
			notify("Error", respuesta.mensaje, "error");
		}
	});
	*/
}
$scope.calcular_hit_rate_valor_recertificaciones = function(){
	var datos = {
		f_ini : $("#fecha_inicio_rec").val(),
		f_fin : $("#fecha_fin_rec").val()
	}
	alert('En desarrollo');
	
	$.post(global_apiserver + "/reporte/hitRateValorRecertificaciones/", JSON.stringify(datos), function(response){
		respuesta = JSON.parse(response);
		if (respuesta.resultado == "ok") {
			$scope.contratos_nuevos_recertificacion = 	respuesta.recertificaciones;
			$scope.ofertas_nuevas_recertificacion	=	respuesta.cotizaciones;
			if(respuesta.cotizaciones!=0){
				$scope.hit_rate_valor_recertificacion	=	respuesta.recertificaciones/respuesta.cotizaciones;
			}
			else{
				$scope.hit_rate_valor_recertificacion	=	"Cotizaciones no puede tener valor 0";
			}
			$scope.$apply();
			notify("Éxito", "Resultaos obtenidos");
		}
		else{
			notify("Error", respuesta.mensaje, "error");
		}
	});
	
}
$scope.graficaOrigen = function(){
	$.ajax({
		type:'GET',
		dataType: 'json',
		url:global_apiserver+"/reporte/getCantidadOrigen/",
		success:function(data){
			for(var i = 0 ; i < data.length ; i++){
				origen[i] = data[i].ORIGEN;
				cantidadOrigen[i] = data[i].CONTADOR;
			}
			var mybarChart = new Chart(origeng, {
				type: 'bar',
				data: {
					labels: origen,
					datasets: [{
						label: 'cantidad',
						backgroundColor: "#26B99A",
						data: cantidadOrigen
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

$scope.graficaEstatus= function(){
	$.ajax({
		type:'GET',
		dataType: 'json',
		url:global_apiserver+"/reporte/getCantidadEstatusSeguimiento/",
		success:function(data){
			for(var i = 0 ; i < data.length ; i++){
				estatus[i] = data[i].ESTATUS_SEGUIMIENTO;
				cantidadEstatus[i] = data[i].CONTADOR;
			}
			var mybarChart = new Chart(estatusg, {
				type: 'bar',
				data: {
					labels: estatus,
					datasets: [{
						label: 'cantidad',
						backgroundColor: "#26B99A",
						data: cantidadEstatus
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

$scope.graficaCompetencia = function(){
	$.ajax({
		type:'GET',
		dataType: 'json',
		url:global_apiserver+"/reporte/getCantidadCompetencia/",
		success:function(data){
			for(var i = 0 ; i < data.length ; i++){
				competencia[i] = data[i].COMPETENCIA;
				cantidadCompetencia[i] = data[i].CONTADOR;
			}
			var width = data.length*7;
			$("#competenciadivwidth").css("width",width+"%");
			var mybarChart = new Chart(competenciag, {
				
				type: 'bar',
				data: {
					labels: competencia,
					datasets: [{
						
						label: 'cantidad',
						backgroundColor: "#26B99A",
						data: cantidadCompetencia,
					}]
				},
				
				options: {
					
					responsive: true,
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

$scope.graficaEntidad = function(){
	$.ajax({
		type:'GET',
		dataType: 'json',
		url:global_apiserver+"/reporte/getCantidadEntidad/",
		success:function(data){
			for(var i = 0 ; i < data.length ; i++){
				entidad[i] = data[i].ENTIDAD_FEDERATIVA;
				cantidadEntidad[i] = data[i].CONTADOR;
			}
			var width = data.length*7;
			$("#entidaddivwidth").css("width",width+"%");
			var mybarChart = new Chart(entidadg, {
				
				type: 'bar',
				data: {
					labels: entidad,
					datasets: [{
						
						label: 'cantidad',
						backgroundColor: "#26B99A",
						data: cantidadEntidad,
					}]
				},
				
				options: {
					
					responsive: true,
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







$scope.graficaOrigenU = function(){
	$.ajax({
		type:'GET',
		dataType: 'json',
		url:global_apiserver+"/reporte/getCantidadOrigen/?usuario="+sessionStorage.getItem("id_usuario"),
		success:function(data){
			for(var i = 0 ; i < data.length ; i++){
				origenU[i] = data[i].ORIGEN;
				cantidadOrigenU[i] = data[i].CONTADOR;
			}
			var mybarChart = new Chart(origengu, {
				type: 'bar',
				data: {
					labels: origen,
					datasets: [{
						label: 'cantidad',
						backgroundColor: "#26B99A",
						data: cantidadOrigenU
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

$scope.graficaEstatusU= function(){
	$.ajax({
		type:'GET',
		dataType: 'json',
		url:global_apiserver+"/reporte/getCantidadEstatusSeguimiento/?usuario="+sessionStorage.getItem("id_usuario"),
		success:function(data){
			for(var i = 0 ; i < data.length ; i++){
				estatusU[i] = data[i].ESTATUS_SEGUIMIENTO;
				cantidadEstatusU[i] = data[i].CONTADOR;
			}
			var mybarChart = new Chart(estatusgu, {
				type: 'bar',
				data: {
					labels: estatus,
					datasets: [{
						label: 'cantidad',
						backgroundColor: "#26B99A",
						data: cantidadEstatusU
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

$scope.graficaCompetenciaU = function(){
	$.ajax({
		type:'GET',
		dataType: 'json',
		url:global_apiserver+"/reporte/getCantidadCompetencia/?usuario="+sessionStorage.getItem("id_usuario"),
		success:function(data){
			for(var i = 0 ; i < data.length ; i++){
				competenciaU[i] = data[i].COMPETENCIA;
				cantidadCompetenciaU[i] = data[i].CONTADOR;
			}
			var width = data.length*7;
			$("#competenciadivwidthu").css("width",width+"%");
			var mybarChart = new Chart(competenciagu, {
				
				type: 'bar',
				data: {
					labels: competencia,
					datasets: [{
						
						label: 'cantidad',
						backgroundColor: "#26B99A",
						data: cantidadCompetenciaU,
					}]
				},
				
				options: {
					
					responsive: true,
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

$scope.graficaEntidadU = function(){
	$.ajax({
		type:'GET',
		dataType: 'json',
		url:global_apiserver+"/reporte/getCantidadEntidad/?usuario="+sessionStorage.getItem("id_usuario"),
		success:function(data){
			for(var i = 0 ; i < data.length ; i++){
				entidadU[i] = data[i].ENTIDAD_FEDERATIVA;
				cantidadEntidadU[i] = data[i].CONTADOR;
			}
			var width = data.length*7;
			$("#entidaddivwidthu").css("width",width+"%");
			var mybarChart = new Chart(entidadgu, {
				
				type: 'bar',
				data: {
					labels: entidad,
					datasets: [{
						
						label: 'cantidad',
						backgroundColor: "#26B99A",
						data: cantidadEntidadU,
					}]
				},
				
				options: {
					
					responsive: true,
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
function onCalendar()
{

	/*///////////////////////////////////////////////////*/
	$('#fecha_inicio_cert').datepicker({
        dateFormat: "yy-mm-dd",
		onSelect: function (dateText, ins) {
            $scope.fecha_inicio_cert = dateText;
        }
    }).css("display", "inline-block");
				
	$('#fecha_fin_cert').datepicker({
        dateFormat: "yy-mm-dd",
        onSelect: function (dateText, ins) {
			$scope.fecha_fin_cert = dateText;
        }
    }).css("display", "inline-block");
	$('#fecha_inicio_rec').datepicker({
        dateFormat: "yy-mm-dd",
        onSelect: function (dateText, ins) {
            $scope.fecha_inicio_rec1 = dateText;
        }
    }).css("display", "inline-block");
				
	$('#fecha_fin_rec').datepicker({
        dateFormat: "yy-mm-dd",
        onSelect: function (dateText, ins) {
			$scope.fecha_fin_rec = dateText;
        }
    }).css("display", "inline-block");
	/*///////////////////////////////////////////////////*/
}
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