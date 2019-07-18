app.controller('estado_actual_facturacion_controller', ['$scope', '$http', function($scope, $http) {
    var repCantFactxEstad = document.getElementById("RepCantFactxEstad");
    console.log("Llamado");
   /* QUITAR 
    $scope.prospecto_seguimiento = [];
    $scope.formData = {};
    $scope.prospectos = []; */

// Graficar cantidad de facturas según estado
$scope.graficaRepCantFactxEstad = function(){
    console.log("entro");    
	$.ajax({
		type:'GET',
		dataType: 'json',
		url:global_apiserver+"/facturacion_resumen/GetCountByStatus/",
		success:function(data){
            console.log("entro aquí tambiém");
            console.log(data);
            datos=[];
            estado={};
            data.forEach(function(valor,indice){
                estado.label=valor.estatus;
                console.log("estado: ",valor.estatus);
                estado.data=valor.cantidad;
                estado.backgroundColor='rgb(Math.round( Math.random( ) * 255 ),Math.round( Math.random( ) * 255 ),Math.round( Math.random( ) * 255 ),0.7)';
                datos.push(estado);
            });

			var mybarChart = new Chart(repCantFactxEstad, {
				type: 'bar',
				data: {
                    labels: datos.estatus,
                    datasets: datos
					/*datasets: [{
						label: 'AUDITORES EXTERNOS (%)',
						backgroundColor: 'rgba(255, 0, 0, 0.7)',
						data: data.Y1,
					},{
						label: 'AUDITORES INTERNOS (%)',
						backgroundColor: 'rgba(0, 0, 255, 0.7)',
						data: data.Y2,
					}]*/
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
	        console.log("chart: ",mybarChart.data);
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

    console.log("paso 1");
    $scope.graficaRepCantFactxEstad();
    console.log("paso 2");

}])