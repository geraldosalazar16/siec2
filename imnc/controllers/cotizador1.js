app.controller("cotizador_controller", ['$scope','$window', '$http','$document', function($scope,$window,$http,$document){
  $scope.modulo_permisos =  global_permisos["COTIZADOR"];
  $scope.arr_cotizaciones = [];
  $scope.arr_prospectos = [];
  $scope.arr_clientes = [];
  $scope.arr_tramites = [];
  $scope.cotizacion_insertar_editar = {};
  $scope.bandera = 0;

  function fill_select_tipo_servicio(){
    //recibe la url del php que se ejecutará
    $http.get(  global_apiserver + "/tipos_servicio/getAll/?filtro=vigentes")
        .then(function( response ) {//se ejecuta cuando la petición fue correcta
          $scope.Tipos_Servicio = response.data.map(function(item){
            return{
              ID : item.ID,
              NOMBRE : item.NOMBRE
            }
          });
      },
      function (response){});
  }

  $scope.fill_select_estatus = function(seleccionado){
    //recibe la url del php que se ejecutará
    $http.get(  global_apiserver + "/prospecto_estatus_seguimiento/getAll/")
        .then(function( response ) {//se ejecuta cuando la petición fue correcta
          var des = "";
          $scope.Estatus_seguimiento = response.data.map(function(item){
            if(item.ID == seleccionado)
              des = item.DESCRIPCION;
            return{
              ID : item.ID,
              DESCRIPCION : item.DESCRIPCION
            }
          });
          $scope.cotizacion_insertar_editar.ESTADO_SEG = { ID : seleccionado, DESCRIPCION : des };
      },
      function (response){});
  }

  $scope.fill_select_tarifa = function(){
    //recibe la url del php que se ejecutará
    $http.get(  global_apiserver + "/tarifa_cotizacion/getAll/")
        .then(function( response ) {//se ejecuta cuando la petición fue correcta
          $scope.Tarifa_Cotizacion = response.data.map(function(item){
            return{
              tarifa : item.TARIFA,
              descripcion : item.DESCRIPCION + " - $" + item.TARIFA 
            }
          });
      },
      function (response){});
  }
  // Llena combo prospectos
  $scope.fill_select_clientes = function (seleccionado){ 
    var http_request = {
      method: 'GET',
      url: global_apiserver + "/clientes/getAll/",
    };

    $http(http_request).success(function(data) {
      if(data) { 
        $scope.arr_clientes = data;
        //$("#selectCliente").val(seleccionado);
        $scope.cotizacion_insertar_editar.CLIENTE = {ID : seleccionado};
      } 
      else  {
        console.log("No hay datos");
      }
    }).error(function(response) {
      console.log("Error al generar petición: " + response);
    });
  }


  // Llena combo prospectos
  $scope.fill_select_prospectos = function (seleccionado){ 
    var http_request = {
      method: 'GET',
      url: global_apiserver + "/prospecto/getAll/",
    };

    $http(http_request).success(function(data) {
      if(data) { 
        $scope.arr_prospectos = data;
        //$("#selectProspecto").val(seleccionado);
        $scope.cotizacion_insertar_editar.PROSPECTO = { ID : seleccionado };
      } 
      else  {
        console.log("No hay datos");
      }
    }).error(function(response) {
      console.log("Error al generar petición: " + response);
    });
  }

  // Pinta tabla de cotizaciones
  $scope.despliega_cotizaciones = function () {
    $scope.fill_select_prospectos("");
    $scope.fill_select_clientes("");
    $scope.fill_select_estatus("");
    $scope.fill_select_tarifa();
    fill_select_tipo_servicio()
    var http_request = {
      method: 'GET',
      url: global_apiserver + "/cotizaciones/getAll/",
    };

    $http(http_request).success(function(data) {
      if(data) {
        $scope.arr_cotizaciones = data;
      } 
      else  {
        console.log("No hay datos");
      }
    }).error(function(response) {
      console.log("Error al generar petición: " + response);
    });
  }

  // Abrir modal para insertar
  $scope.modal_cotizacion_insertar = function(){
    $('#modalTituloCotizacion').html("Agregar cotización");
    //$('#btnGuardarUsuario').attr("opcion", "insertar");
    $scope.opcion_guardar_cotizacion = "insertar";
    $scope.cotizacion_insertar_editar = {};
    $scope.bandera = 0;
    $scope.fill_select_prospectos("");
    $scope.fill_select_clientes("");
    $scope.fill_select_estatus("");
    $scope.fill_select_tarifa();
    fill_select_tipo_servicio();
    $scope.changeReferencia();
    $('#modalInsertarActualizarCotizacion').modal('show');
  }

  // Abrir modal para editar
  $scope.modal_cotizacion_editar = function(id_cotizacion){
    $('#modalTituloCotizacion').html("Editar datos");
    //$('#btnGuardarUsuario').attr("opcion", "editar");
    $scope.opcion_guardar_cotizacion = "editar";
    var http_request = {
      method: 'GET',
      url: global_apiserver + "/cotizaciones/getById/?id="+id_cotizacion,
    };
    $http(http_request).success(function(data) {
      if(data) { 
        $scope.cotizacion_insertar_editar = data[0];
        $scope.bandera = data[0].BANDERA;
        $scope.fill_select_estatus(data[0].ESTADO_COTIZACION);
      } 
      else  {
        console.log("No hay datos");
      }
	  //console.log(data[0].BANDERA);
      if($scope.bandera == 1){
        $scope.fill_select_prospectos("");
        $scope.fill_select_clientes(data[0].ID_PROSPECTO);
      }
      else{
        $scope.fill_select_prospectos(data[0].ID_PROSPECTO);
        $scope.fill_select_clientes("");
      }
	  
    }).error(function(response) {
      console.log("Error al generar petición: " + response);
    });

    $('#modalInsertarActualizarCotizacion').modal('show');
  }

  $scope.cotizacion_guardar = function(){
	  var id_entidad = 0;
	if($scope.bandera == 0){
		 id_entidad = $scope.cotizacion_insertar_editar.PROSPECTO.ID;
	}else{
		id_entidad = $scope.cotizacion_insertar_editar.CLIENTE.ID;
	}
    var cotizacion = {
		ID : $scope.cotizacion_insertar_editar.ID,
		ID_PROSPECTO : id_entidad, 
		ID_SERVICIO : $scope.cotizacion_insertar_editar.ID_SERVICIO,
		ID_TIPO_SERVICIO : $scope.cotizacion_insertar_editar.ID_TIPO_SERVICIO,
    FOLIO_INICIALES : $scope.cotizacion_insertar_editar.FOLIO_INICIALES,
    FOLIO_SERVICIO : $scope.cotizacion_insertar_editar.FOLIO_SERVICIO,
    ESTADO_COTIZACION : $scope.cotizacion_insertar_editar.ESTADO_SEG.ID,
		REFERENCIA : $scope.cotizacion_insertar_editar.REFERENCIA,
    TARIFA : $scope.cotizacion_insertar_editar.TARIFA,
    DESCUENTO : $scope.cotizacion_insertar_editar.DESCUENTO,
		SG_INTEGRAL : $scope.cotizacion_insertar_editar.SG_INTEGRAL,
		BANDERA : $scope.bandera,
    COMPLEJIDAD : $scope.cotizacion_insertar_editar.COMPLEJIDAD,
		ID_USUARIO : sessionStorage.getItem("id_usuario")
	}

    //console.log($scope.cotizacion_insertar_editar);
    if ($scope.opcion_guardar_cotizacion == 'insertar') {
       var http_request = {
        method: 'POST',
        url: global_apiserver + "/cotizaciones/insert/",
        data: angular.toJson(cotizacion)
      };
    }
    else if ($scope.opcion_guardar_cotizacion == 'editar'){
      var http_request = {
        method: 'POST',
        url: global_apiserver + "/cotizaciones/update/",
        data: angular.toJson(cotizacion)
      };
    }
   
    $http(http_request).success(function(data) {
      if(data) { 
        if (data.resultado == "ok") {
           notify("Éxito", "Se han guardado los cambios", "success");
           $('#modalInsertarActualizarCotizacion').modal('hide');
           $scope.despliega_cotizaciones();
        }
        else{
          notify("Error", data.mensaje, "error");
        }
      } 
      else  {
        console.log("No hay datos");
      }
    }).error(function(response) {
      console.log("Error al generar petición: " + response);
    });

  }

  ///////////////////////////////////////////////////////////////////////////////////////////////////////////
//		FUNCION PARA GENERAR REFERENCIA
///////////////////////////////////////////////////////////////////////////////////////////////////////////
	$scope.GenerarReferenciaProspecto =  function() {
	  
		$.getJSON( global_apiserver + "/prospecto/generarReferencia/?id="+$("#selectProspecto").val(), function( response ) {
			$scope.cotizacion_insertar_editar.REFERENCIA=response.REFERENCIA;
			
			$scope.$apply()
       });
		
   };
 $scope.changeReferencia = function(){  
   $( "#selectProspecto").change(function() {
		$scope.GenerarReferenciaProspecto();
   });
 }
///////////////////////////////////////////////////////////////////////////////////////////////////////////

  // Funcion que recarga la pagina
  $scope.reload = function(){
    $window.location.reload();
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
}]);