app.controller("cotizador_controller", ['$scope','$window', '$http','$document', function($scope,$window,$http,$document){
  $scope.modulo_permisos =  global_permisos["COTIZADOR"];
  $scope.arr_cotizaciones = [];
  $scope.arr_prospectos = [];
  $scope.arr_clientes = [];
  $scope.arr_tramites = [];
  $scope.cotizacion_insertar_editar = {};
  $scope.bandera = 0;
  $scope.Normas = [];

  function fill_select_servicio () {
    //recibe la url del php que se ejecutará
    $http.get(  global_apiserver + "/servicios/getAll")
        .then(function( response ) {//se ejecuta cuando la petición fue correcta
          $scope.Servicios = response.data.map(function(item){
            return{
              ID : item.ID,
              NOMBRE : item.NOMBRE
            }
          });
      },
      function (response){});
  }
  $scope.cambio_servicio = function () {
    const servicio = $scope.cotizacion_insertar_editar.ID_SERVICIO;
    fill_select_etapa(servicio.ID);
    const tipos_servicio = $scope.Tipos_Servicio_Total;
    $scope.Tipos_Servicio = [];
    tipos_servicio.forEach(tipo_servicio => {
      if (tipo_servicio.ID_SERVICIO === servicio.ID) {
        $scope.Tipos_Servicio.push(tipo_servicio);
      }
    });
  }
  $scope.servicioFiltroChange = function () {
    const id_servicio = $scope.selectFiltroServicio;
    $scope.despliega_cotizaciones_filtradas(id_servicio);
  }
  function fill_select_tipo_servicio(){
    //recibe la url del php que se ejecutará
    $http.get(  global_apiserver + "/tipos_servicio/getList")
        .then(function( response ) {//se ejecuta cuando la petición fue correcta
          $scope.Tipos_Servicio_Total = response.data.map(function(item){
            return{
              ID : item.ID,
              NOMBRE : item.NOMBRE,
              ID_SERVICIO: item.ID_SERVICIO,
              NORMAS: item.NORMAS
            }
          });
      },
      function (response){});
  }
  $scope.cambio_tipo_servicio = function() {
    $scope.Normas = $scope.cotizacion_insertar_editar.ID_TIPO_SERVICIO.NORMAS;
    $scope.cotizacion_insertar_editar.NORMAS = [];
    if ($scope.Normas.length == 1) {
      $scope.cotizacion_insertar_editar.NORMAS.push($scope.Normas[0]);
    }
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
              id: item.ID,
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
  // Llena las etapas
  fill_select_etapa = function (id_servicio){
    //recibe la url del php que se ejecutará
    $http.get(  global_apiserver + "/etapas_proceso/getByIdServicio/?id="+id_servicio)
    .then(function( response ) {//se ejecuta cuando la petición fue correcta
      $scope.Etapas = response.data.map(function(item){
        return{
          ID: item.ID_ETAPA,
          NOMBRE : item.ETAPA
        }
      });
    },
    function (response){});
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
    fill_select_servicio();
    fill_select_tipo_servicio();
    $scope.titulo_columna_tarifa = 'Tarifa día auditor';
    $scope.titulo_columna_info = 'Prospecto, tipo de servicio y norma';
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

  // Cotizaciones filtradas
  $scope.despliega_cotizaciones_filtradas = function (id_servicio) {
    if(id_servicio != 3){
      $scope.titulo_columna_tarifa = 'Tarifa';
    } else {
      $scope.titulo_columna_tarifa = 'Tarifa día auditor';
      $scope.titulo_columna_info = 'Prospecto, módulo y curso';
    }
    var http_request = {
      method: 'GET',
      url: global_apiserver + "/cotizaciones/getAllByIdServicio/?id_servicio="+id_servicio,
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
    //Limpiar el listado de normas sugeridas
    $scope.Normas = [];
    //Limpiar el control de normas
    $scope.normas_cotizacion = [];

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
        $scope.normas_cotizacion = data[0].NORMAS;
        //SERVICIO, TIPO DE SERVICIO Y NORMA
        $scope.Servicios.forEach(servicio => {
          if(servicio.ID === data[0].ID_SERVICIO) {
            $scope.cotizacion_insertar_editar.ID_SERVICIO = servicio;
            $scope.cambio_servicio();
          }
        });
        $scope.Tipos_Servicio_Total.forEach(tipo_servicio => {
          if(tipo_servicio.ID === data[0].ID_TIPO_SERVICIO) {
            $scope.cotizacion_insertar_editar.ID_TIPO_SERVICIO = tipo_servicio;
            $scope.cambio_tipo_servicio();
          }
        });
    
        $scope.cotizacion_insertar_editar.ETAPA = data[0].ETAPA;
        $scope.bandera = data[0].BANDERA;
        $scope.fill_select_estatus(data[0].ESTADO_COTIZACION);
        
        //Cargar referencias cuando es una cotización para un cliente
        if($scope.bandera != 0){
          $scope.cambioCliente(data[0].REFERENCIA);
        }
		switch(parseInt($scope.cotizacion_insertar_editar.ID_TIPO_SERVICIO.ID)){
			case 16:
				$scope.cotizacion_insertar_editar.ACTIVIDAD_ECONOMICA = data[0].DETALLES[0].VALOR;
				break;
			case 17:
				
				break;
			default:
				break;
		}

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
      $('#modalInsertarActualizarCotizacion').modal('show');

    }).error(function(response) {
      console.log("Error al generar petición: " + response);
    });
  }

  $scope.cotizacion_guardar = function(){
    var cotizacion;
	  var id_entidad = 0;
    if($scope.bandera == 0){
      id_entidad = $scope.cotizacion_insertar_editar.PROSPECTO.ID;
      cotizacion = {
        ID : $scope.cotizacion_insertar_editar.ID,
        ID_PROSPECTO : id_entidad,
        ID_SERVICIO : $scope.cotizacion_insertar_editar.ID_SERVICIO.ID,
        ID_TIPO_SERVICIO : $scope.cotizacion_insertar_editar.ID_TIPO_SERVICIO.ID,
        NORMAS: $scope.normas_cotizacion,
        ETAPA: 0, //La etapa solo se usa para clientes
        FOLIO_INICIALES : $scope.cotizacion_insertar_editar.FOLIO_INICIALES,
        FOLIO_SERVICIO : $scope.cotizacion_insertar_editar.FOLIO_SERVICIO,
        ESTADO_COTIZACION : $scope.cotizacion_insertar_editar.ESTADO_SEG.ID,
        REFERENCIA : "",
        TARIFA : $scope.cotizacion_insertar_editar.TARIFA,
        DESCUENTO : $scope.cotizacion_insertar_editar.DESCUENTO,
		AUMENTO : $scope.cotizacion_insertar_editar.AUMENTO,
        SG_INTEGRAL : $scope.cotizacion_insertar_editar.SG_INTEGRAL,
        BANDERA : $scope.bandera,
        COMPLEJIDAD : $scope.cotizacion_insertar_editar.COMPLEJIDAD,
        COMBINADA: $scope.cotizacion_insertar_editar.COMBINADA,
		ACTIVIDAD_ECONOMICA: $scope.cotizacion_insertar_editar.ACTIVIDAD_ECONOMICA,
		ID_USUARIO : sessionStorage.getItem("id_usuario")
      }
    }else{
      id_entidad = $scope.cotizacion_insertar_editar.CLIENTE.ID;
      var cotizacion = {
        ID : $scope.cotizacion_insertar_editar.ID,
        ID_PROSPECTO : id_entidad,
        ID_SERVICIO : $scope.cotizacion_insertar_editar.ID_SERVICIO.ID,
        ID_TIPO_SERVICIO : $scope.cotizacion_insertar_editar.ID_TIPO_SERVICIO.ID,
        NORMAS: $scope.normas_cotizacion,
        //ETAPA: $scope.cotizacion_insertar_editar.ETAPA, se deja de usar por el momento
        ETAPA: 0, //Se pone a cero porque no se esta usando
        FOLIO_INICIALES : $scope.cotizacion_insertar_editar.FOLIO_INICIALES,
        FOLIO_SERVICIO : $scope.cotizacion_insertar_editar.FOLIO_SERVICIO,
        ESTADO_COTIZACION : $scope.cotizacion_insertar_editar.ESTADO_SEG.ID,
        REFERENCIA : $scope.cotizacion_insertar_editar.REFERENCIA.VALOR,
        TARIFA : $scope.cotizacion_insertar_editar.TARIFA,
        DESCUENTO : $scope.cotizacion_insertar_editar.DESCUENTO,
		AUMENTO : $scope.cotizacion_insertar_editar.AUMENTO,
        SG_INTEGRAL : $scope.cotizacion_insertar_editar.SG_INTEGRAL,
        BANDERA : $scope.bandera,
        COMPLEJIDAD : $scope.cotizacion_insertar_editar.COMPLEJIDAD,
        COMBINADA: $scope.cotizacion_insertar_editar.COMBINADA,
        ACTIVIDAD_ECONOMICA: $scope.cotizacion_insertar_editar.ACTIVIDAD_ECONOMICA,
        ID_USUARIO : sessionStorage.getItem("id_usuario")
      }
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
           $scope.despliega_cotizaciones();
        }
        else{
          notify("Error", data.mensaje, "error");
        }
      }
      else  {
        console.log("No hay datos");
      }
      $('#modalInsertarActualizarCotizacion').modal('hide');
    }).error(function(response) {
      console.log("Error al generar petición: " + response);
    });

  }
  $scope.eliminar_cotizacion = function(id_cotizacion){
    var datos = {
      id_cotizacion: id_cotizacion
    };
    $.confirm({
      title: 'Elimnando la cotización',
      content: 'Eliminar esta cotización es un proceso irreversible, estás seguro?',
      buttons: {
          cancel: {
              text: 'Cancelar'
          },
          irAuditoria: {
              text: 'Eliminar',
              btnClass: 'btn-blue',
              keys: ['enter', 'shift'],
              action: function(){
                $http.post(global_apiserver + "/cotizaciones/delete/",datos).
                then(function(response){
                  if(response.data.resultado == 'ok'){
                    notify('Éxito','Se ha eliminado la cotización','success');
                    $scope.despliega_cotizaciones();
                  } else {
                    notify('Error',response.data.mensaje,'error');
                  }
                  $("#modalAddServicio").modal("hide");
                });
              }
          }
      }
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
 $scope.cambioCliente = function(ref){
   //ref es la referencia a cargar para edicion
   $http.get(  global_apiserver + "/servicio_cliente_etapa/getReferenciaByClient/?cliente="+$scope.cotizacion_insertar_editar.CLIENTE.ID)
        .then(function( response ) {//se ejecuta cuando la petición fue correcta
          $scope.Referencias = response.data.map(function(item){
            return{
              ID_SERVICIO: item.ID_SERVICIO,
              ID_TIPO_SERVICIO: item.ID_TIPO_SERVICIO,
              ID_NORMA: item.ID_NORMA,
              ID_SCE : item.ID,
              VALOR : item.REFERENCIA
            }
          });
          if(ref){
            $scope.Referencias.forEach(referencia => {
              if(referencia.VALOR == ref){
                $scope.cotizacion_insertar_editar.REFERENCIA = referencia;
              }
            });
          }
      },
      function (response){});
 }
 $scope.cambioReferencia = function(){
   $scope.Servicios.forEach(servicio => {
      if(servicio.ID == $scope.cotizacion_insertar_editar.REFERENCIA.ID_SERVICIO){
        $scope.cotizacion_insertar_editar.ID_SERVICIO = servicio;
        fill_select_etapa(servicio.ID);
        const tipos_servicio = $scope.Tipos_Servicio_Total;
        $scope.Tipos_Servicio = [];
        tipos_servicio.forEach(tipo_servicio => {
          if (tipo_servicio.ID_SERVICIO === servicio.ID) {
            $scope.Tipos_Servicio.push(tipo_servicio);
          }
        });
        $scope.Tipos_Servicio_Total.forEach(tipo_servicio => {
          if(tipo_servicio.ID == $scope.cotizacion_insertar_editar.REFERENCIA.ID_TIPO_SERVICIO){
            $scope.cotizacion_insertar_editar.ID_TIPO_SERVICIO = tipo_servicio;
            $scope.Normas = $scope.cotizacion_insertar_editar.ID_TIPO_SERVICIO.NORMAS;
            $scope.Normas.forEach(norma => {
              if(norma.ID_NORMA == $scope.cotizacion_insertar_editar.REFERENCIA.ID_NORMA){
                $scope.cotizacion_insertar_editar.ID_NORMA = norma;
              }
           });
          }
        });
      }
   });

}
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
