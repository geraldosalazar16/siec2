app.controller('ver_graficas_usuario_controller', ['$scope', function($scope,$http) { 

var origengu = document.getElementById("origenChartU");
var estatusgu = document.getElementById("estatusChartU");
var competenciagu = document.getElementById("competenciaChartU");
var entidadgu = document.getElementById("entidadChartU");

var origenU = [];
var cantidadOrigenU = [];
var estatusU = [];
var cantidadEstatusU = [];
var competenciaU = [];
var cantidadCompetenciaU = [];
var entidadU = [];
var cantidadEntidadU= [];

var usuarioID = getQueryVariable("id");

$scope.nombre_usuario = "";

$scope.getUsuario = function(){
	$.ajax({
		type:'GET',
		dataType: 'json',
		url:global_apiserver+"/usuariosc/getById/?id="+usuarioID,
		success:function(data){
			$scope.nombre_usuario = data.NOMBRE;
			$scope.$apply();
		}
	});
}

$scope.graficaOrigenU = function(){
	$.ajax({
		type:'GET',
		dataType: 'json',
		url:global_apiserver+"/reporte/getCantidadOrigen/?usuario="+usuarioID,
		success:function(data){
			for(var i = 0 ; i < data.length ; i++){
				origenU[i] = data[i].ORIGEN;
				cantidadOrigenU[i] = data[i].CONTADOR;
			}
			var mybarChart = new Chart(origengu, {
				type: 'bar',
				data: {
					labels: origenU,
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
		url:global_apiserver+"/reporte/getCantidadEstatusSeguimiento/?usuario="+usuarioID,
		success:function(data){
			for(var i = 0 ; i < data.length ; i++){
				estatusU[i] = data[i].ESTATUS_SEGUIMIENTO;
				cantidadEstatusU[i] = data[i].CONTADOR;
			}
			var mybarChart = new Chart(estatusgu, {
				type: 'bar',
				data: {
					labels: estatusU,
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
		url:global_apiserver+"/reporte/getCantidadCompetencia/?usuario="+usuarioID,
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
					labels: competenciaU,
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
		url:global_apiserver+"/reporte/getCantidadEntidad/?usuario="+usuarioID,
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
					labels: entidadU,
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

$scope.graficaCompetenciaU();
$scope.graficaOrigenU();
$scope.graficaEstatusU();
$scope.graficaEntidadU();
$scope.getUsuario();
}]);


function getQueryVariable(variable) {
	  var query = window.location.search.substring(1);
	  var vars = query.split("&");
	  for (var i=0;i<vars.length;i++) {
		var pair = vars[i].split("=");
		if (pair[0] == variable) {
		  return pair[1];
		}
	  } 
	  alert('Query Variable ' + variable + ' not found');
	}