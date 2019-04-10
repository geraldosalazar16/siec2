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

$scope.graficaLoginU = function(){
		$.ajax({
			type:'GET',
			dataType: 'json',
			url:global_apiserver+"/reporte/getLogins/?usuario="+usuarioID,
			success:function(data){
				var logins = data;
				var meses = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
				var mybarChart = new Chart(loginChartU, {

					type: 'bar',
					data: {
						labels: meses,
						datasets: [{

							label: 'Total',
							fill: false,
							backgroundColor: "rgba(190,112,8,0.8)",
							data: logins,
						}]
					},

					options: {
						title: {
							display: true,
							text: 'Inicios de Sesión por Meses'
						},
						responsive: true,
						scales: {
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
	$scope.graficaProductosU = function(){
		$.ajax({
			type:'GET',
			dataType: 'json',
			url:global_apiserver+"/reporte/getProductos/?usuario="+usuarioID,
			success:function(data){
				var productos = data;
				var meses = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
				var mybarChart = new Chart(productosChartU, {

					type: 'bar',
					data: {
						labels: meses,
						datasets: [
							{
								label: "CSG",
								backgroundColor: "rgba(205,145,14,0.5)",
								data: productos[1]
							}, {
								label: "ES",
								backgroundColor: "rgba(17,89,205,0.5)",
								data: productos[2]
							},
							{
								label: "CIFA",
								backgroundColor: "rgba(44,205,12,0.5)",
								data: productos[3]
							},
							{
								label: "EP",
								backgroundColor: "rgba(88,14,12,0.5)",
								data: productos[4]
							}


						]
					},

					options: {
						responsive: true,
						title: {
							display: true,
							text: 'Productos creados por Servicios / Meses'
						},
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

	$scope.graficaCotizacionesU = function(){
		$.ajax({
			type:'GET',
			dataType: 'json',
			url:global_apiserver+"/reporte/getCotizaciones/?usuario="+usuarioID,
			success:function(data){
				var cotizaciones = data;
				var meses = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
				var mybarChart = new Chart(cotizacionesChartU, {

					type: 'bar',
					data: {
						labels: meses,
						datasets: [
							{
								label: "CSG",
								backgroundColor: "rgba(204,25,6,0.5)",
								data: cotizaciones[1]
							}, {
								label: "ES",
								backgroundColor: "rgba(12,7,204,0.5)",
								data: cotizaciones[2]
							},
							{
								label: "CIFA",
								backgroundColor: "rgba(14,92,204,0.5)",
								data: cotizaciones[3]
							},
							{
								label: "EP",
								backgroundColor: "rgba(190,150,8,0.8)",
								data: cotizaciones[4]
							}

						]
					},

					options: {
						title: {
							display: true,
							text: 'Cotizaciones creadas por Servicios / Meses'
						},
						responsive: true,
						scales: {
							xAxes: [{
								stacked: true

							}],
							yAxes: [{
								stacked: true
							}]
						}
					},
				});

			}
		});

	};

	$scope.graficaAuditoriasU = function(){
		$.ajax({
			type:'GET',
			dataType: 'json',
			url:global_apiserver+"/reporte/getAuditorias/?usuario="+usuarioID,
			success:function(data){
				var auditorias = data;
				var meses = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
				var mybarChart = new Chart(auditoriasChartU, {

					type: 'bar',
					data: {
						labels: meses,
						datasets: [
							{
								label: "CSG",
								backgroundColor: "rgba(190,42,25,0.81)",
								data: auditorias[1]
							}, {
								label: "ES",
								backgroundColor: "rgba(6,7,190,0.81)",
								data: auditorias[2]
							}
							, {
								label: "EP",
								backgroundColor: "rgba(6,7,190,0.81)",
								data: auditorias[4]
							}
						]
					},

					options: {
						title: {
							display: true,
							text: 'Auditorias creadas por Servicios / Meses'
						},
						responsive: true,

					},
				});

			}
		});

	};

$scope.graficaCompetenciaU();
$scope.graficaOrigenU();
$scope.graficaEstatusU();
$scope.graficaEntidadU();
$scope.getUsuario();
$scope.graficaLoginU();
$scope.graficaProductosU();
$scope.graficaCotizacionesU();
$scope.graficaAuditoriasU();
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