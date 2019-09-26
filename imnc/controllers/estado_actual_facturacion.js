app.controller('estado_actual_facturacion_controller', ['$scope', '$http', function($scope, $http) {
	var repCantFactxEstad = document.getElementById("RepCantFactxEstad");
	var repMontoFactxEstad = document.getElementById("RepMontoFactxEstad");
	var repCarteraVencida = document.getElementById("RepCarteraVencida");

// Graficar cantidad de facturas según estado
$scope.graficaRepCantFactxEstad = function(){    
	$.ajax({
		type:'GET',
		dataType: 'json',
		url:global_apiserver+"/facturacion_resumen/GetCountByStatus/",
		success:function(data){ 
			var datos=[];
			var estado=[];
			var fondo=[]; 
			colores=["#6cafdb","#c1e1f7","#0099ff","#16365d","#2067a2","#4e9ad9","#84c7ff","#0660ac","#0a64fe","#0000fe"];			
			for(var i = 0 ; i < data.length ; i++){				
				datos[i] = data[i].cantidad;
				estado[i] = data[i].estatus;
				fondo[i]=colores[i];
			}			

			var mybarChart = new Chart(repCantFactxEstad, {
				type: 'pie',
				data: {
                    labels: estado,
					datasets: [{data: datos,					  
					  backgroundColor: fondo}]					
				}	
			});	        
		},
		error:()=>{console.log("ERROR: ejecutando consulta cant fact x estado ")}
	});
};

// Graficar monto de facturas según estado
$scope.graficaRepMontoFactxEstad = function(){    
	$.ajax({
		type:'GET',
		dataType: 'json',
		url:global_apiserver+"/facturacion_resumen/GetAmountByStatus/",
		success:function(data){
			var datos=[];
			var estado=[];
			var fondo=[];	
			colores=["#6cafdb","#c1e1f7","#0099ff","#16365d","#2067a2","#4e9ad9","#84c7ff","0660ac","0a64fe","0000fe"];
			//["red","blue","lime","black","pink","green","purple","gray","black","orange"];
			//rgba(Math.round( Math.random( ) * 255 ),Math.round( Math.random( ) * 255 ),Math.round( Math.random( ) * 255 ),0.7)';
			for(var i = 0 ; i < data.length ; i++){				
				datos[i] = data[i].amount;
				estado[i] = data[i].estatus;
				fondo[i]=colores[i];
			}			

			var mybarChart = new Chart(repMontoFactxEstad, {
				type: 'pie',
				data: {
                    labels: estado,
					datasets: [{data: datos,					  
					  backgroundColor: fondo}]					
				}	
			});	        
		},
		error:()=>{console.log("ERROR: ejecutando consulta monto fact x estado ")}
	});
};

// Graficar cartera vencida
$scope.graficaRepCarteraVencida = function(){         
	$.ajax({
		type:'GET',
		dataType: 'json',
		url:global_apiserver+"/facturacion_resumen/GetExpiratedWallet/",
		success:function(data){				
			var datos=[];
			var estado=[];
			var fondo=[];
			//4 tonalidades de azul mate ordenados del más claro al más oscuro en correspondecia
			//a lo significativo en tiempo de vejes de las facturas. El backend los devuelve ordenados			
			colores=["#c1e1f7","#6cafdb","#2067a2","#16365d"];
			for(var i = 0 ; i < data.length ; i++){				
				datos[i] = data[i].total;
				estado[i] = data[i].diasvencida;
				fondo[i]=colores[i];
			}						
			var mybarChart = new Chart(repCarteraVencida, {
				type: 'pie',
				data: {
                    labels: estado,
					datasets: [{data: datos,					  
					  backgroundColor: fondo}]					
				}	
			});	        
		},
		error:()=>{console.log("ERROR: ejecutando consulta cartera vencida")}
	});
};

	$scope.graficaRepCantFactxEstad();
	$scope.graficaRepMontoFactxEstad();
	$scope.graficaRepCarteraVencida();	
    

}])