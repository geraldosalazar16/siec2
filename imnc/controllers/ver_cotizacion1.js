app.controller("ver_cotizacion_controller", ['$scope','$window', '$http','$document', function($scope,$window,$http,$document){
  $scope.modulo_permisos =  global_permisos["COTIZADOR"];
  $scope.arr_prospectos = [];
  $scope.arr_sitios_cotizacion = [];
  $scope.arr_actividad = [];
  $scope.obj_cotizacion = {};
  $scope.obj_sitio = {};
  $scope.obj_integracion = {};
  $scope.chkboxes = [];
  $scope.listaDomicilios = [];
  $scope.cmb_list = {};
  $scope.check_cmb_list = [];
  $scope.descripcion_cambio = [];
  $scope.bl_sum_empleados = false;
  $scope.bl_firmado = false;
  $scope.bl_cotizado = false;
  $scope.chkActv = false;
  $scope.intgBol = false;
  $scope.addServicio = false;
  var accion_tarifa = "";
  var current_tramite = 0;
  var accion_tramite = "";
  var accion_sitio = "";

  $scope.get_factor_integracion = function(){
    if(!Boolean($scope.obj_integracion.X) || !Boolean($scope.obj_integracion.Y) ){
      return 0;
    }
    if($scope.obj_integracion.X <= 0 || $scope.obj_integracion.Y <= 0){
      $scope.tramite_insertar_editar.FACTOR_INTEGRACION = 0;
      return 0;
    }
    $http.get(  global_apiserver + "/cotizacion_nivel_integracion/getPorcentaje/?x="+ $scope.obj_integracion.X  +"&y=" + $scope.obj_integracion.Y).then(function( response ) {
      $scope.tramite_insertar_editar.FACTOR_INTEGRACION = response.data[0].VALOR;
    }
    ,function (response){});
  }

  function  fill_cmb_tarifa_adicional(){
    $http.get(  global_apiserver + "/tarifa_cotizacion_adicional/getAll/").then(function( response ) {
      $scope.arr_tarifa_adicional = response.data.map(function(item){
          return {
            ID : item.ID,
            DESCRIPCION : item.DESCRIPCION + " - $" + item.TARIFA 
          };
      });
      $scope.chkActv = false;
    }
    ,function (response){});
  }

  function  fill_cmb_actividad(){
    $http.get(  global_apiserver + "/sg_actividad/getAll/").then(function( response ) {
      $scope.arr_actividad = response.data.map(function(item){
          return {
            ID : item.ID,
            ACTIVIDAD : item.ACTIVIDAD
          };
      });
      $scope.chkActv = false;
    }
    ,function (response){});
  }

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
          $scope.obj_cotizacion.ESTADO_SEG = { ID : seleccionado, DESCRIPCION : des };
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

  $scope.fill_select_tramites = function (){ 
    var http_request = {
      method: 'GET',
      url: global_apiserver + "/etapas_proceso/getByIdServicio/?id="+$scope.obj_cotizacion.ID_SERVICIO,
    };

    $http(http_request).success(function(data) {
      if(data) { 
        $scope.arr_tramites = data;
      } 
      else  {
        console.log("No hay datos");
      }
    }).error(function(response) {
      console.log("Error al generar petición: " + response);
    });
  }

  $scope.fill_select_domicilios = function (){ 
    var url="";
    if($scope.obj_cotizacion.BANDERA == 0){
      url = "/prospecto_domicilio/getAll/?id="+ $scope.obj_cotizacion.ID_PROSPECTO;
    }
    else if($scope.obj_cotizacion.BANDERA == 1){
      url = "/clientes_domicilios/getByClient/?id="+ $scope.obj_cotizacion.ID_PROSPECTO;
    }
    var http_request = {
      method: 'GET',
      url: global_apiserver + url,
    };

    $http(http_request).success(function(data) {
      if(data) { 
        $scope.listaDomicilios = data;
      } 
      else  {
        console.log("No hay datos");
      }
    }).error(function(response) {
      console.log("Error al generar petición: " + response);
    });
  }

  function fill_select_clientes(){ 
    var http_request = {
      method: 'GET',
      url: global_apiserver + "/clientes/getAll/",
    };

    $http(http_request).success(function(data) {
      if(data) { 
        $scope.arr_clientes = data;
      } 
      else  {
        console.log("No hay datos");
      }
    }).error(function(response) {
      console.log("Error al generar petición: " + response);
    });
  }

  function fill_cmb_referencia(tramite, cliente){
    var http_request = {
      method: 'GET',
      url: global_apiserver + "/servicio_cliente_etapa/getReferenciaByTramite/?tramite=" + tramite + "&cliente=" + cliente,
    };

    $http(http_request).success(function(data) {
      if(data) { 
        $scope.arr_servicios = data;
      } 
      else  {
        console.log("No hay datos");
      }
    }).error(function(response) {
      console.log("Error al generar petición: " + response);
    });
  }

  function cotizacion_ant(id_ant){
    var http_request = {
      method: 'GET',
      url: global_apiserver + "/cotizaciones/getById/?id="+id_ant,
    };
    $http(http_request).success(function(data) {
      if(data) {
        var obj_cotizacion_ant = data[0];
        $scope.obj_cotizacion.TOTAL_COTIZACION_ANT = obj_cotizacion_ant.TOTAL_COTIZACION_DES;
        $scope.obj_cotizacion.DELTA_COTIZACION = $scope.obj_cotizacion.TOTAL_COTIZACION_DES - obj_cotizacion_ant.TOTAL_COTIZACION_DES;
      } 
      else  {
        console.log("No hay datos");
      }
    }).error(function(response) {
      console.log("Error al generar petición: " + response);
    }); 
  }

  // Pinta tabla de cotizaciones  despliega_cotizacion
  $scope.despliega_cotizacion = function () {
    var http_request = {
      method: 'GET',
      url: global_apiserver + "/cotizaciones/getById/?id="+global_id_cotizacion,
    };
    $http(http_request).success(function(data) {
      if(data) {
        $scope.obj_cotizacion = data[0];
        $scope.arr_tramites_cotizacion = data[0].COTIZACION_TRAMITES;
        $scope.bl_cotizado = data[0].ESTADO.ESTATUS_SEGUIMIENTO == "Firmado" || data[0].ESTADO.ESTATUS_SEGUIMIENTO == "Cotizado";
        $scope.bl_firmado = data[0].ESTADO.ESTATUS_SEGUIMIENTO == "Firmado";
        $scope.mostrar_tramite_sitios(current_tramite);
        $scope.fill_select_domicilios();
        $scope.fill_select_tramites();
        $scope.fill_select_tarifa();
        $scope.fill_select_estatus(data[0].ESTADO_COTIZACION);
        fill_select_tipo_servicio();
        fill_cmb_tarifa_adicional();
        fill_checkbox_cambio();
        fill_cmb_actividad();
        fill_select_clientes();
        if($scope.obj_cotizacion.ID_COTIZACION_ANT != null){
          if($scope.obj_cotizacion.TOTAL_COTIZACION_ANT == null){
            cotizacion_ant($scope.obj_cotizacion.ID_COTIZACION_ANT);
          }
          else{
            $scope.obj_cotizacion.DELTA_COTIZACION = $scope.obj_cotizacion.TOTAL_COTIZACION_DES - $scope.obj_cotizacion.TOTAL_COTIZACION_ANT;
          }
        }
      } 
      else  {
        console.log("No hay datos");
      }
    }).error(function(response) {
      console.log("Error al generar petición: " + response);
    });
  }

  $scope.modal_crear_servicio = function(tramite, addServ, obj_tramite_aux){
    $scope.addServicio = addServ;
    $scope.obj_tramite = obj_tramite_aux;
    $scope.obj_servicio = { ID_NORMA : $scope.obj_cotizacion.NORMA.ID, REFERENCIA : $scope.obj_cotizacion.REFERENCIA };
     $scope.obj_servicio.ID_SERVICIO_CLIENTE = 0;
    if($scope.obj_cotizacion.BANDERA == 1){
      $scope.obj_servicio.ID_CLIENTE = $scope.obj_cotizacion.ID_PROSPECTO;
    }
    else if($scope.obj_cotizacion.BANDERA == 0 && Boolean($scope.obj_cotizacion.CLIENTE)){
      $scope.obj_servicio.ID_CLIENTE = $scope.obj_cotizacion.CLIENTE.ID;
      $scope.obj_servicio.NOMBRE_CLIENTE = $scope.obj_cotizacion.CLIENTE.NOMBRE;
    }
    else{
      $scope.obj_servicio.ID_CLIENTE = "";
      $scope.obj_servicio.NOMBRE_CLIENTE = "No hay cliente asociado al prospecto";
    }
    if(addServ){
      fill_cmb_referencia(tramite, $scope.obj_servicio.ID_CLIENTE);
    }
    $('#modalAddServicio').modal('show');
  }

  // Abrir modal para editar
  $scope.modal_cotizacion_editar = function(){
    $scope.cotizacion_insertar_editar = $scope.obj_cotizacion;
    $scope.opcion_guardar_cotizacion = "editar";
    $('#modalTituloCotizacion').html("Editar datos");
    $('#modalInsertarActualizarCotizacion').modal('show');
  }

  $scope.modal_cotizacion_actualizar = function(){
    $scope.opcion_guardar_cotizacion = "actualizar";
    if($scope.obj_cotizacion.ESTADO.ESTATUS_SEGUIMIENTO == "Firmado"){  
      $scope.obj_cotizacion.FOLIO_UPDATE = "E";
      $scope.obj_cotizacion.NUEVO_FOLIO = $scope.obj_cotizacion.FOLIO + "-E";
    }
    else if($scope.obj_cotizacion.ESTADO.ESTATUS_SEGUIMIENTO == "Cotizado"){
      $scope.obj_cotizacion.FOLIO_UPDATE = !Boolean($scope.obj_cotizacion.FOLIO_UPDATE) || $scope.obj_cotizacion.FOLIO_UPDATE == ""?
        "1" : parseInt($scope.obj_cotizacion.FOLIO_UPDATE) + 1;
      $scope.obj_cotizacion.NUEVO_FOLIO = $scope.obj_cotizacion.FOLIO + "-" + $scope.obj_cotizacion.FOLIO_UPDATE;
    }
    $('#modalActualizarCotizacion').modal('show');
  }

  $scope.modal_tarifa_adicional_insertar = function(){
    $scope.obj_tarifa_adicional = { CANTIDAD : 1};
    accion_tarifa = "insertar";
    $('#modalTituloTarifaAdicional').html("Insertar tarifa adicional");
    $('#modalInsertarActualizarTarifaAdicional').modal('show');
  }
  // Abrir modal para insertar
  $scope.modal_sitio_insertar = function(){
    $scope.obj_sitio = {};
    accion_sitio = "insertar";
    $scope.chkActv = false;
    $('#modalTituloSitioCotizacion').html("Insertar sitio");
    $('#modalInsertarActualizarSitioCotizacion').modal('show');
  }
  // Abrir modal para insertar
  $scope.modal_tramite_insertar = function(){
    $scope.tramite_insertar_editar = {};
    $scope.obj_integracion = {};
    clean_checkbox_cambio();
    accion_tramite ="insertar";
    $('#modalTituloTramite').html("Insertar tramite");
    $('#modalInsertarActualizarTramite').modal('show');
  }

  $scope.modal_tarifa_adicional_editar = function(id){
    var http_request = {
      method: 'GET',
      url: global_apiserver + "/cotizacion_tarifa_adicional/getById/?id="+id,
    };

    $http(http_request).success(function(data) {
      if(data) { 
        $scope.obj_tarifa_adicional = data; 
        accion_tarifa = "editar";
        $('#modalTituloTarifaAdicional').html("Editar tarifa adicional");
        $('#modalInsertarActualizarTarifaAdicional').modal('show');
      } 
      else  {
        console.log("No hay datos");
      }
    }).error(function(response) {
      console.log("Error al generar petición: " + response);
    });
  }

  $scope.modal_sitio_editar = function(id){
    var http_request = {
      method: 'GET',
      url: global_apiserver + "/cotizacion_sitios/getById/?id="+id,
    };

    $http(http_request).success(function(data) {
      if(data) { 
        $scope.obj_sitio = data; 
        $scope.chkActv = false;
        accion_sitio = "editar";
        $('#modalTituloSitioCotizacion').html("Editar sitio");
        $('#modalInsertarActualizarSitioCotizacion').modal('show');
      } 
      else  {
        console.log("No hay datos");
      }
    }).error(function(response) {
      console.log("Error al generar petición: " + response);
    });
  }

   // Abrir modal para insertar
  $scope.modal_tramite_editar = function(id){
    var http_request = {
      method: 'GET',
      url: global_apiserver + "/cotizaciones_tramites/getById/?id="+id,
    };

    $http(http_request).success(function(data) {
      if(data) { 
        $scope.tramite_insertar_editar = data; 
        accion_tramite = "editar";
        $scope.obj_integracion = {};
        get_cambios_servicios(data.ID, data.CAMBIO);
        $('#modalTituloTramite').html("Editar tramite");
        $('#modalInsertarActualizarTramite').modal('show');
      } 
      else  {
        console.log("No hay datos");
      }
    }).error(function(response) {
      console.log("Error al generar petición: " + response);
    });
  }

  // Abrir modal para insertar
  $scope.mostrar_tramite_sitios = function(id){
    current_tramite = id;
    if(id == 0){
      $scope.arr_sitios_cotizacion = [];
      $scope.arr_tramites_tarifa_adicional = [];
      $scope.obj_cotizacion_tramite = {};
      $('#sitio_tramite').hide();
      return false;
    }
    var http_request = {
      method: 'GET',
      url: global_apiserver + "/cotizaciones_tramites/getSitios/?id="+id +"&cotizacion="+global_id_cotizacion,
    };
    $http(http_request).success(function(data) {
      if(data) { 
        $scope.obj_cotizacion_tramite = data;
        $scope.arr_sitios_cotizacion = data.COTIZACION_SITIOS;
        $scope.arr_tarifa_adicional_cotizacion = data.COTIZACION_TARIFA_ADICIONAL;
        $('#sitio_tramite').show();
      } 
      else  {
        console.log("No hay datos");
      }
    }).error(function(response) {
      console.log("Error al generar petición: " + response);
    });
  }

  $scope.crear_servicio = function(){
    var cotizacion =  $scope.obj_cotizacion;
    cotizacion.OBJ_TRAMITE = $scope.obj_tramite;
    cotizacion.OBJ_SERVICIO = $scope.obj_servicio;
    cotizacion.ID_USUARIO = sessionStorage.getItem("id_usuario");

    var http_request = {
        method: 'POST',
        url: global_apiserver + "/cotizaciones/createServicio/",
        data: angular.toJson(cotizacion)
    };
   
    $http(http_request).success(function(data) {
      if(data) { 
        if (data.resultado == "ok") {
           notify("Éxito", "Se han guardado los cambios", "success");
           $('#modalAddServicio').modal('hide');
           $scope.despliega_cotizacion();
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

  $scope.actualizar_servicio = function(obj_tramite){
    var cotizacion =  $scope.obj_cotizacion;
    cotizacion.OBJ_TRAMITE = obj_tramite;
    cotizacion.ID_USUARIO = sessionStorage.getItem("id_usuario");
    if($scope.obj_cotizacion.BANDERA == 1){
     cotizacion.ID_CLIENTE = $scope.obj_cotizacion.ID_PROSPECTO;
    }
    else if($scope.obj_cotizacion.BANDERA == 0 && Boolean($scope.obj_cotizacion.CLIENTE)){
     cotizacion.ID_CLIENTE = $scope.obj_cotizacion.CLIENTE.ID;
    }
    else{
     cotizacion.ID_CLIENTE = "";
    }

    var http_request = {
        method: 'POST',
        url: global_apiserver + "/cotizaciones/actualizarServicio/",
        data: angular.toJson(cotizacion)
    };
   
    $http(http_request).success(function(data) {
      if(data) { 
        if (data.resultado == "ok") {
           notify("Éxito", "Se han guardado los cambios", "success");
           $('#modalAddServicio').modal('hide');
           $scope.despliega_cotizacion();
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

  $scope.tramite_guardar = function(){
    var tramite = {
      ID : $scope.tramite_insertar_editar.ID,
      ID_COTIZACION : global_id_cotizacion, 
      ID_ETAPA_PROCESO : $scope.tramite_insertar_editar.ID_ETAPA_PROCESO,
      VIATICOS : $scope.tramite_insertar_editar.VIATICOS,
      DESCUENTO : $scope.tramite_insertar_editar.DESCUENTO,
      FACTOR_INTEGRACION : $scope.tramite_insertar_editar.FACTOR_INTEGRACION,
      JUSTIFICACION : $scope.tramite_insertar_editar.JUSTIFICACION,
      CAMBIO : $scope.tramite_insertar_editar.CAMBIO,
      SG_INTEGRAL : $scope.obj_cotizacion.SG_INTEGRAL,
      ID_USUARIO : sessionStorage.getItem("id_usuario"),

    }
    
    if (accion_tramite == 'insertar') {
       var http_request = {
        method: 'POST',
        url: global_apiserver + "/cotizaciones_tramites/insert/",
        data: angular.toJson(tramite)
      };
    }
    else if (accion_tramite == 'editar'){
      var http_request = {
        method: 'POST',
        url: global_apiserver + "/cotizaciones_tramites/update/",
        data: angular.toJson(tramite)
      };
    }
   
    $http(http_request).success(function(data) {
      if(data) { 
        if (data.resultado == "ok") {
          if(tramite.CAMBIO == 'S') { actualizar_cambios(data.id); }
           notify("Éxito", "Se han guardado los cambios", "success");
           $('#modalInsertarActualizarTramite').modal('hide');
           $scope.despliega_cotizacion();
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

  $scope.insertar_actividad = function(){
    if($scope.chkActv){
      var actividad = {
          ACTIVIDAD : $scope.obj_sitio.nuevaActividad,
          ID_USUARIO:sessionStorage.getItem("id_usuario")
      };
      $.post( global_apiserver + "/sg_actividad/insert/", JSON.stringify(actividad), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
          console.log("actividad insertada");
          $scope.obj_sitio.ID_ACTIVIDAD = respuesta.ID;
          $scope.cotizacion_sitio_guardar()
        }
        else{
          notify("Error", respuesta.mensaje, "error");
        }
      });
    }
    else{
      $scope.cotizacion_sitio_guardar();
    }
  }

  $scope.tarifa_adicional_guardar = function(){
    $scope.obj_tarifa_adicional.ID_TRAMITE = current_tramite;
    $scope.obj_tarifa_adicional.ID_USUARIO= sessionStorage.getItem("id_usuario");

    if (accion_tarifa == 'insertar') {
      var http_request = {
        method: 'POST',
        url: global_apiserver + "/cotizacion_tarifa_adicional/insert/",
        data: angular.toJson($scope.obj_tarifa_adicional)
      };
    }
    else if (accion_tarifa == 'editar'){
      var http_request = {
        method: 'POST',
        url: global_apiserver + "/cotizacion_tarifa_adicional/update/",
        data: angular.toJson($scope.obj_tarifa_adicional)
      };
    }
    $http(http_request).success(function(data) {
      if(data) { 
        if (data.resultado == "ok") {
           notify("Éxito", "Se han guardado los cambios", "success");
           $('#modalInsertarActualizarTarifaAdicional').modal('hide');
           $scope.despliega_cotizacion();
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


  $scope.cotizacion_sitio_guardar = function(){
    $scope.obj_sitio.ID_COTIZACION = current_tramite;
    $scope.obj_sitio.ID_USUARIO= sessionStorage.getItem("id_usuario");

    if (accion_sitio == 'insertar') {
      var http_request = {
        method: 'POST',
        url: global_apiserver + "/cotizacion_sitios/insert/",
        data: angular.toJson($scope.obj_sitio)
      };
    }
    else if (accion_sitio == 'editar'){
      var http_request = {
        method: 'POST',
        url: global_apiserver + "/cotizacion_sitios/update/",
        data: angular.toJson($scope.obj_sitio)
      };
    }
    $http(http_request).success(function(data) {
      if(data) { 
        if (data.resultado == "ok") {
           notify("Éxito", "Se han guardado los cambios", "success");
           $('#modalInsertarActualizarSitioCotizacion').modal('hide');
           $scope.despliega_cotizacion();
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

  $scope.modal_tarifa_adicional_eliminar = function(id){
    var http_request = {
    method: 'GET',
    url: global_apiserver + "/cotizacion_tarifa_adicional/delete/?id="+id,
    };
    $http(http_request).success(function(data) {
      if(data) { 
        if (data.resultado == "ok") {
           notify("Éxito", "Se han eliminado el registro", "success");
           $scope.despliega_cotizacion();
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

  $scope.modal_cotizacion_sitio_eliminar = function(id_cotizacion_sitio){
    var http_request = {
    method: 'GET',
    url: global_apiserver + "/cotizacion_sitios/delete/?id="+id_cotizacion_sitio,
    };
   
    $http(http_request).success(function(data) {
      if(data) { 
        if (data.resultado == "ok") {
           notify("Éxito", "Se han eliminado el registro", "success");
           $scope.despliega_cotizacion();
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

  $scope.actualiza_sitio_seleccionado = function(id_sitio){
    var http_request = {
      method: 'GET',
      url: global_apiserver + "/cotizacion_sitios/updateSeleccionado/?id="+id_sitio,
    };
   
    $http(http_request).success(function(data) {
      if(data) { 
        if (data.resultado == "ok") {
           //notify("Éxito", "Se han eliminado el registro", "success");
           $scope.despliega_cotizacion();
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

  $scope.cotizacion_actualizar = function(){
    var cotizacion = $scope.obj_cotizacion;
    cotizacion.ESTADO_COTIZACION = 1;
    cotizacion.ID_USUARIO = sessionStorage.getItem("id_usuario");
    var http_request = {
        method: 'POST',
        url: global_apiserver + "/cotizaciones/actualizarCotizacion/",
        data: angular.toJson(cotizacion)
    };
    $http(http_request).success(function(data) {
      if(data) { 
        if (data.resultado == "ok") {
           notify("Éxito", "Se ha creado una nueva Cotización\n", "success");
           $('#modalActualizarCotizacion').modal('hide');
           $scope.despliega_cotizacion();
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

  $scope.cotizacion_guardar = function(){
    if ($scope.opcion_guardar_cotizacion == 'editar'){
      var cotizacion = {
        ID : $scope.cotizacion_insertar_editar.ID,
        ID_PROSPECTO : $scope.cotizacion_insertar_editar.ID_PROSPECTO, 
        ID_SERVICIO : $scope.cotizacion_insertar_editar.ID_SERVICIO,
        ID_TIPO_SERVICIO : $scope.cotizacion_insertar_editar.ID_TIPO_SERVICIO,
        FOLIO_INICIALES : $scope.cotizacion_insertar_editar.FOLIO_INICIALES,
        FOLIO_SERVICIO : $scope.cotizacion_insertar_editar.FOLIO_SERVICIO,
        ESTADO_COTIZACION : $scope.cotizacion_insertar_editar.ESTADO_SEG.ID,
        REFERENCIA : $scope.cotizacion_insertar_editar.REFERENCIA,
        TARIFA : $scope.cotizacion_insertar_editar.TARIFA,
        DESCUENTO : $scope.cotizacion_insertar_editar.DESCUENTO,
        SG_INTEGRAL : $scope.cotizacion_insertar_editar.SG_INTEGRAL,
        BANDERA : $scope.cotizacion_insertar_editar.BANDERA,
        COMPLEJIDAD : $scope.cotizacion_insertar_editar.COMPLEJIDAD,
        ID_USUARIO : sessionStorage.getItem("id_usuario")
      }
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
           $scope.despliega_cotizacion();
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

  $scope.change_cmb_cambio = function(op){
    if(op =='S'){
       $(".cambioCheckbox").show();
    }else{
      $.each($scope.cmb_list,function(index,item){
        $scope.cmb_list[index].CAMBIO = false;
      });
      var aux = $scope.cmb_list;
      clean_checkbox_cambio();
      $scope.cmb_list = aux;
    }
  }

  $scope.check_checkbox_cambio = function(ID, check){
    if(check){
      if ($scope.cmb_list[ID] != undefined && !$scope.cmb_list[ID].NUEVO){
          var aux_id = $scope.cmb_list[ID].ID;
          $scope.cmb_list[ID] = {ID_CAMBIO : ID, CAMBIO: true, NUEVO: false, ID : aux_id};
      }
      else{
          $scope.cmb_list[ID] = {ID_CAMBIO : ID, CAMBIO: true, NUEVO: true, ID : 0};
      }
      $("#campoDescripcionCambio-"+ ID).show();          
    }
    else{
      $("#campoDescripcionCambio-"+ ID).hide();
      $scope.descripcion_cambio[ID] = "";
      if ($scope.cmb_list[ID] != undefined && !$scope.cmb_list[ID].NUEVO){
        var aux_id = $scope.cmb_list[ID].ID;
        $scope.cmb_list[ID] = {ID_CAMBIO : ID, CAMBIO: false, NUEVO: false, ID : aux_id};
      }
      else{
        delete $scope.cmb_list[ID];
      }
    }
  }
   function fill_checkbox_cambio(){
    $.getJSON(  global_apiserver + "/servicio_cambio/getAll/", function( response ) {
      $scope.arr_cambio = response;
    });
  }
  //funcion get by id cambios
  function get_cambios_servicios(id_tramite, op){
    clean_checkbox_cambio();
    if(op =='S'){
       $(".cambioCheckbox").show();
    }
    $.getJSON(  global_apiserver + "/servicio_cotizacion_cambio/getById/?id="+id_tramite, function( response ) {
      response.forEach(function(item,index){
        $scope.cmb_list[item.ID_CAMBIO] = {ID_CAMBIO : item.ID_CAMBIO, CAMBIO: true, NUEVO: false, ID : item.ID};
        $scope.check_cmb_list[item.ID_CAMBIO] = true;
        $scope.descripcion_cambio[item.ID_CAMBIO] = item.DESCRIPCION;
        $scope.$apply();
        $("#campoDescripcionCambio-"+item.ID_CAMBIO).show();
      });
    });
  }
  //funcion insertar, borrar actualizar cambios
  function actualizar_cambios(id_tramite){
    var cmb_insert = {
      "insert" : [], "update" : [] , "delete" : []
    };
    $.each($scope.cmb_list,function(index,item){
      var aux = {
          ID : item.ID,
          ID_TRAMITE : id_tramite,
          ID_CAMBIO : item.ID_CAMBIO,
          DESCRIPCION : $scope.descripcion_cambio[item.ID_CAMBIO],
          ID_USUARIO : sessionStorage.getItem("id_usuario")
      }
      if(item.NUEVO && item.CAMBIO){
        cmb_insert["insert"].push(aux);
      }
      else if(!item.NUEVO && item.CAMBIO){
        cmb_insert["update"].push(aux);
      }
      else if(!item.NUEVO && !item.CAMBIO){
        cmb_insert["delete"].push(aux);
      }
    });
      var cmb_post = [];
      if(cmb_insert["delete"].length > 0){
          cmb_post.push("delete");
      }
      if(cmb_insert["update"].length > 0){
        cmb_post.push("update");
      }
      if(cmb_insert["insert"].length > 0){
        cmb_post.push("insert");
      }
      for (var i = 0; i < cmb_post.length; i++) {
        $.post( global_apiserver + "/servicio_cotizacion_cambio/"+cmb_post[i]+"/", JSON.stringify(cmb_insert[cmb_post[i]]), function(respuesta){
            respuesta = JSON.parse(respuesta);
            if (respuesta.resultado == "ok") {
              console.log("cambio con exito");
            }
            else{
               notify("Error", respuesta.mensaje, "error");
            }
        });
      }
  }

  function clean_checkbox_cambio(){
    $(".cambioCheckbox").hide();
    $scope.check_cmb_list = [];
    $scope.descripcion_cambio = [];
    $("[id^='campoDescripcionCambio']").hide();
    $scope.cmb_list = {};
  }

}]);