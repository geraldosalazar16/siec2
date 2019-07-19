app.controller('estado_actual_facturacion_controller', ['$scope', '$http', function($scope, $http) {
	var repCantFactxEstad = document.getElementById("RepCantFactxEstad");
	var repMontoFactxEstad = document.getElementById("RepMontoFactxEstad");

	var datos=[];
	var estado=[];
	var fondo=[];      

// Graficar cantidad de facturas según estado
$scope.graficaRepCantFactxEstad = function(){    
	$.ajax({
		type:'GET',
		dataType: 'json',
		url:global_apiserver+"/facturacion_resumen/GetCountByStatus/",
		success:function(data){            
			colores=["red","blue","lime","black","pink","green","purple","gray","black","orange"];
			for(var i = 0 ; i < data.length ; i++){				
				datos[i] = data[i].cantidad;
				estado[i] = data[i].estatus;
				fondo[i]=colores[i];//rgba(Math.round( Math.random( ) * 255 ),Math.round( Math.random( ) * 255 ),Math.round( Math.random( ) * 255 ),0.7)';
			}			

			var mybarChart = new Chart(repCantFactxEstad, {
				type: 'pie',
				data: {
                    labels: estado,
					datasets: [{data: datos,					  
					  backgroundColor: fondo}]					
				}	
			});	        
		}
	});
};

// Graficar monto de facturas según estado
$scope.graficaRepMontoFactxEstad = function(){    
	$.ajax({
		type:'GET',
		dataType: 'json',
		url:global_apiserver+"/facturacion_resumen/GetAmountByStatus/",
		success:function(data){            
			colores=["red","blue","lime","black","pink","green","purple","gray","black","orange"];
			for(var i = 0 ; i < data.length ; i++){				
				datos[i] = data[i].amount;
				estado[i] = data[i].estatus;
				fondo[i]=colores[i];//rgba(Math.round( Math.random( ) * 255 ),Math.round( Math.random( ) * 255 ),Math.round( Math.random( ) * 255 ),0.7)';
			}			

			var mybarChart = new Chart(repMontoFactxEstad, {
				type: 'pie',
				data: {
                    labels: estado,
					datasets: [{data: datos,					  
					  backgroundColor: fondo}]					
				}	
			});	        
		}
	});
};

// ===================================================================
// ***** 		FUNCION PARA CARGAR PROSPECTO SEGUIMIENTO   	 *****
// ===================================================================
 /*   $scope.cargarProspectoSeguimiento= function(){
        $http.get(global_apiserver + "/prospecto_estatus_seguimiento/getAll/")
            .then(function( response ){
                $scope.prospecto_seguimiento = response.data;
            });
     }*/

	$scope.graficaRepCantFactxEstad();
	$scope.graficaRepMontoFactxEstad();
    

}])