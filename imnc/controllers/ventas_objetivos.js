app.controller('ventas_objetivos_controller', ['$scope','$http', function($scope,$http) { 
	//Titulo que aparece en el html
	$scope.titulo = 'Objetivos de ventas';
	$scope.modulo_permisos =  global_permisos["SERVICIOS"];
    
    function cargarAnios(seleccionado){
        $http.get(  global_apiserver + "/ventas_objetivos_anuales/getAll/")
	  	.then(function( response ) {
	  		if(response.data){
                if(response.data.length > 0){
                    $scope.Anios = response.data;
                    if(!seleccionado){
                        setAnio($scope.Anios[0].ID);
                        $scope.montoAnioMostrar = $scope.Anios[0].MONTO;
                    } else {
                        setAnio(seleccionado);                        
                    }                    
                }                
            } else {
                notify('Error','error',response.data.mensaje);
            }	  			
		});
    }

    function setAnio(anio){
        $scope.anioSelect = anio;
        $scope.cambioAnio();
    }

    $scope.cambioAnio = function(){
        cargarMesesPorAnio();
        //Mostrar el monto del año
        $scope.Anios.forEach(anio => {
            if(anio.ID == $scope.anioSelect){
                $scope.montoAnioMostrar = anio.MONTO;
            }
        });
    }

    function cargarMesesPorAnio(){
        //Cargar los meses del año
        $http.get(  global_apiserver + "/ventas_objetivos_mensuales/getByAnio?id="+$scope.anioSelect)
	  	.then(function( response ) {
	  		if(response.data){
                $scope.Meses = response.data;
                validarMontoMeses(0);
            } else {
                notify('Error','error',response.data.mensaje);
            }	  			
        });
    }

    function validarMontoMeses(inicial){
        var total = parseFloat(inicial);
        $scope.Meses.forEach(mes => {
            total = total + parseFloat(mes.MONTO);
        });
        if(parseFloat(total) !== parseFloat($scope.montoAnioMostrar)){
            $scope.errores = 'La suma de los objetivos mensuales no coincide con el objetivo anual';
            return false;
        } else {            
            $scope.errores = null;
            return true;
        }
    }
    $scope.mostrarModalAgregarAnio = function(accion){
        $scope.accionAnio = accion;
        if(accion == 'insert'){
            clearModalAgregarAnio();
        } else {
            fillModalAgregarAnio();
        }        
        $("#modalGuardarAnio").modal("show");
    }
    function clearModalAgregarAnio(){
        $scope.anio = "";
        $scope.montoAnio = 0;
    }
    function fillModalAgregarAnio(){
        $scope.Anios.forEach(anio => {
            if(anio.ID == $scope.anioSelect){
                $scope.anio_actual = anio;
                $scope.anio = anio.ANIO;
                $scope.montoAnio= anio.MONTO;
            }
        });
    }
    $scope.guardarAnio = function(){
        if($scope.anio.length < 4 || !validarAnio($scope.anio)){
            notify('Error','error','El año no tiene el formato correcto');
        } else {            
            if($scope.accionAnio == 'insert'){
                var datos = {
                    ANIO: $scope.anio,
                    MONTO: $scope.montoAnio
                }
                $http.post(  global_apiserver + "/ventas_objetivos_anuales/insertAnio/", datos)
                .then(function( response ) {
                    if(response.data.resultado != 'error'){
                        notify('Éxito!','success','Se ha insertado el registro correctamente');
                        cargarAnios();
                        $("#modalGuardarAnio").modal("hide");
                    } else {
                        notify('Error','error',response.data.mensaje);
                    }	  			
                });
            } else if($scope.accionAnio == 'edit'){
                var datos = {
                    ID: $scope.anio_actual.ID,
                    ANIO: $scope.anio,
                    MONTO: $scope.montoAnio
                }
                $http.post(  global_apiserver + "/ventas_objetivos_anuales/updateAnio/", datos)
                .then(function( response ) {
                    if(response.data.resultado != 'error'){
                        notify('Éxito!','success','Se ha modificado el registro correctamente');
                        cargarAnios($scope.anio_actual.ID);
                        $("#modalGuardarAnio").modal("hide");
                    } else {
                        notify('Error','error',response.data.mensaje);
                    }	  			
                });
            }
            
        }
    }
    //Funciones de validación
    //Solo permite introducir numeros.
    function validarAnio(numero){
        return /^(([1-9]){1})([0-9]{3})/.test(numero);        
    }

    $scope.mostrarModalEditarMes = function(mes){
        $scope.mes_actual = mes;
        llenarModalAgregarMes();
        $("#modalGuardarMes").modal("show");
        
    }

    function llenarModalAgregarMes(){
        $scope.mes = $scope.mes_actual.MES;
        $scope.montoMes = $scope.mes_actual.MONTO;
    }

    $scope.editarMes = function(){
        var datos = {
            ID: $scope.mes_actual.ID,
            MONTO: $scope.montoMes
        }
        $http.post(  global_apiserver + "/ventas_objetivos_mensuales/update/", datos)
	  		.then(function( response ) {
	  			if(response.data.resultado != 'error'){
                    notify('Éxito!','success','Se ha modificado el registro correctamente');
                    cargarMesesPorAnio();
                    $("#modalGuardarMes").modal("hide");
                } else {
                    notify('Error','error',response.data.mensaje);
                }	  			
			});
    }
    //Entry point
    cargarAnios();
}]);
	/*
		Función que recibe el título y el texto de un cuadro de notificación.
	*/
	function notify(titulo, tipo, texto) {
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


