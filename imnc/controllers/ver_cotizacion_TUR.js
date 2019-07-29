app.controller("ver_cotizacion_TUR_controller", ['$scope','$window', '$http','$document', function($scope,$window,$http,$document){
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
  $scope.tipo_auditoria_e1 = false;
/*===================================================*/
$scope.formDataGenCotizacion = {};
$scope.formDataGenCotizacion.tramites={};
$scope.formDataGenCotizacion.descripcion=[[{}]];
$scope.formDataGenCotizacion.tarifa={};
$scope.tram_index = "";
$scope.formData = {};
/*===================================================*/

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
    /*
    var http_request = {
      method: 'GET',
      url: global_apiserver + "/etapas_proceso/getByIdServicio/?id="+$scope.obj_cotizacion.ID_SERVICIO,
    };
    */
    //Usar tipo de auditoría en vez de etapa
    var http_request = {
      method: 'GET',
      url: global_apiserver + "/i_sg_auditorias_tipos/getByIdServicio/?id="+$scope.obj_cotizacion.ID_SERVICIO,
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
        fill_checkbox_cambio();
        fill_select_clientes();
		fill_cmb_tarifa_adicional();
		
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

  $scope.modal_insertar_servicio = function(tramite){
    var nombre_cliente = '';
    var id_cliente = 0;
    var cliente_prospecto = 'prospecto';
    //Determinar si la cotización es para un prospecto o cliente
    if($scope.obj_cotizacion.BANDERA != 0) {
      nombre_cliente = $scope.obj_cotizacion.CLIENTE['NOMBRE'];
      id_cliente = $scope.obj_cotizacion.CLIENTE['ID'];
      cliente_prospecto = 'cliente';
    } else {
      nombre_cliente = $scope.obj_cotizacion.PROSPECTO['NOMBRE'];
      id_cliente = $scope.obj_cotizacion.PROSPECTO['ID'];
      cliente_prospecto = 'prospecto';
    }

    const nombre_servicio = $scope.obj_cotizacion.SERVICIO['NOMBRE'];
    const id_servicio = $scope.obj_cotizacion.SERVICIO['ID'];
    const nombre_tipo_servicio = $scope.obj_cotizacion.TIPOS_SERVICIO['NOMBRE'];
    const id_tipo_servicio = $scope.obj_cotizacion.TIPOS_SERVICIO['ID'];
    const normas = $scope.obj_cotizacion.NORMAS;
    if(cliente_prospecto == 'prospecto'){

      //Información del servicio a insertar para prospecto
      $scope.servicio_insertar = {
        ID_COTIZACION: $scope.obj_cotizacion.ID,
        CLIENTE_PROSPECTO: cliente_prospecto,
        ID_CLIENTE: id_cliente,
        NOMBRE_CLIENTE: nombre_cliente,
        ID_SERVICIO: id_servicio,
        NOMBRE_SERVICIO: nombre_servicio,
        ID_TIPO_SERVICIO: id_tipo_servicio,
        NOMBRE_TIPO_SERVICIO: nombre_tipo_servicio,
        ID_ETAPA:	"",
        NOMBRE_ETAPA: "",
        NORMAS: normas,
        REFERENCIA: "",
        CAMBIO	: "N",
        ID_USUARIO:	sessionStorage.getItem("id_usuario")
      };
      //Posibles etapas en las que se puede iniciar un servicio 
      //cuando la cotización es sobre un prospecto
      $scope.Etapas = [];
      if (id_servicio == 1) { //SG
        $scope.Etapas.push({ID:3,NOMBRE:"Asignación"});
        $scope.Etapas.push({ID:12,NOMBRE:"Transferencia"});
        $scope.servicio_insertar.ID_ETAPA = 3;
      } else {
		if (id_servicio == 2) {
			$scope.Etapas.push({ID:17,NOMBRE:"Asignación"});
			//$scope.Etapas = 17;
		}
      }

      //Se genera la referencia automáticamente
      //Ciclo 1 etapa 3 asignación 
		if(id_servicio == 1){
			generar_referencia("C1",3,id_tipo_servicio);
		}
		else if(id_servicio == 2){
			generar_referencia("C1",17,id_tipo_servicio);
		}
      $('#modalAddServicio').modal('show');
    } else {
      //Cargar la referencia del cliente
      var referencia = $scope.obj_cotizacion.REFERENCIA;
      
      //Información del servicio a insertar para cliente
      $scope.servicio_insertar = {
        ID_COTIZACION: $scope.obj_cotizacion.ID,
        CLIENTE_PROSPECTO: cliente_prospecto,
        ID_CLIENTE: id_cliente,
        NOMBRE_CLIENTE: nombre_cliente,
        ID_SERVICIO: id_servicio,
        NOMBRE_SERVICIO: nombre_servicio,
        ID_TIPO_SERVICIO: id_tipo_servicio,
        NOMBRE_TIPO_SERVICIO: nombre_tipo_servicio,
        NORMAS: normas,
        REFERENCIA: referencia,
        ES_RENOVACION: "S",
        CAMBIO	: "N",
        ID_USUARIO:	sessionStorage.getItem("id_usuario")
      };

      $('#modalAddServicioCliente').modal('show');
    } 
    
  }
  function generar_referencia(ref,etapa,tipo_servicio){
    if(!tipo_servicio)
    {
      tipo_servicio = "XXX";
    }
    if(!etapa)
    {
      etapa = "XX";
    }    
    
    $http.get(  global_apiserver + "/tipos_servicio/generarReferencia/?ref="+ref+"&etapa="+etapa+"&id="+tipo_servicio)
      .then(function( response ){        
        $scope.servicio_insertar.REFERENCIA	= response.data;        
    }); 
  }
  $scope.cambioEtapa	=	function(){
    var ref		=	"C1";
    var id_etapa	=$scope.servicio_insertar.ID_ETAPA;
    var	id_tipo_servicio	=	$scope.servicio_insertar.ID_TIPO_SERVICIO;
    generar_referencia(ref,id_etapa,id_tipo_servicio);
  }
  $scope.crear_servicio = function () {
    //Solo permitir SG, esto habrá que quitarlo cuando se incluya EC en el cotizador
 //   if($scope.servicio_insertar.ID_SERVICIO != 1){
 //     notify('Error','Solo se pueden crear servicios de Sistema de Gestión','error')
 //   } else {
      var datos = {
        ID_COTIZACION: $scope.servicio_insertar.ID_COTIZACION,
        CLIENTE_PROSPECTO: $scope.servicio_insertar.CLIENTE_PROSPECTO,
        ID_CLIENTE: $scope.servicio_insertar.ID_CLIENTE,
        ID_SERVICIO: $scope.servicio_insertar.ID_SERVICIO,
        ID_TIPO_SERVICIO: $scope.servicio_insertar.ID_TIPO_SERVICIO,
        ID_ETAPA_PROCESO:	$scope.servicio_insertar.ID_ETAPA,
        NORMAS: $scope.servicio_insertar.NORMAS,
        REFERENCIA: $scope.servicio_insertar.REFERENCIA,
        CAMBIO	: "N",
        ID_USUARIO:	sessionStorage.getItem("id_usuario")
      };
      
      $http.post(global_apiserver + "/servicio_cliente_etapa/insertDesdeCotizador/",datos).
      then(function(response){
        if(response.data.resultado == 'ok'){
          notify('Éxito','Se ha insertado un nuevo registro','success');        
        } else {
          notify('Error',response.data.mensaje,'error');
        }
        $("#modalAddServicio").modal("hide");
      });
  //  }
  }
  $scope.cargar_eventos_servicio = function () {
    //Solo permitir SG, esto habrá que quitarlo cuando se invcluya EC en el cotizador
 //   if($scope.servicio_insertar.ID_SERVICIO != 1){
 //     notify('Error','Esta funcionalidad solo sirve para Sistema de Gestión','error')
 //  } else {
      var datos = {
        ID_COTIZACION: $scope.servicio_insertar.ID_COTIZACION,
        CLIENTE_PROSPECTO: $scope.servicio_insertar.CLIENTE_PROSPECTO,
        ID_CLIENTE: $scope.servicio_insertar.ID_CLIENTE,
        ID_SERVICIO: $scope.servicio_insertar.ID_SERVICIO,
        ID_TIPO_SERVICIO: $scope.servicio_insertar.ID_TIPO_SERVICIO,
        NORMAS: $scope.servicio_insertar.NORMAS,
        REFERENCIA: $scope.servicio_insertar.REFERENCIA,
        CAMBIO	: "N",
        ES_RENOVACION : $scope.servicio_insertar.ES_RENOVACION,
        ID_USUARIO:	sessionStorage.getItem("id_usuario")
      };
      
      $http.post(global_apiserver + "/servicio_cliente_etapa/agregarEventosServicio/",datos).
      then(function(response){
        if(response.data.resultado == 'ok'){
          notify('Éxito','Se han agregado los eventos','success');        
        } else {
          notify('Error',response.data.mensaje,'error');
        }
        $("#modalAddServicioCliente").modal("hide");
      });
    //}
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
/*============================================================================================*/
//GENERAR COTIZACION
  $scope.modal_cotizacion_generar = function(){
		
		$("#id_prospecto").val($scope.obj_cotizacion.ID_PROSPECTO);
		$("#id_producto").val('');
		Contactos_Prospecto($scope.obj_cotizacion.ID_PROSPECTO);
		Domicilios_Prospecto($scope.obj_cotizacion.ID_PROSPECTO);
		Domicilios_Cliente($scope.obj_cotizacion.ID_PROSPECTO);
		Contactos_Cliente($scope.obj_cotizacion.ID_PROSPECTO);
		
		$scope.formDataGenCotizacion.tramites=$scope.arr_tramites_cotizacion;
		$scope.formDataGenCotizacion.descripcion=[];
		$scope.tarifa_adicional_tramite_cotizacion_by_tramite=[];
		
		
		for(var key in $scope.formDataGenCotizacion.tramites){
/*			
			/*===========================================================================*/
			 tramite_tarifa_adicional_by_tramite($scope.formDataGenCotizacion.tramites[key].ID,key);
			/*===========================================================================*/
			
		}
		
		$scope.formDataGenCotizacion.descripcion=$scope.tarifa_adicional_tramite_cotizacion_by_tramite;
		
    $('#modalTituloGenerarCotizacion').html("Editar datos");
    $('#modalGenerarCotizacion').modal('show');
  }
  /*==========================================================================*/
	$scope.submitFormGenCotizacion = function(formDataGenCotizacion){
	
		//window.open('', 'VentanaGenerarPDF_CIL');
		//document.getElementById('formDataGenCotizacion').submit();
		//var url = "./generar/pdf/cotizacion_propuesta/index.php?datos="+JSON.stringify(formDataGenCotizacion);
		$scope.id_producto="";
		var url = "./generar/pdf/cotizacion_propuesta_tur/index.php?id_prospecto="+$scope.obj_cotizacion.ID_PROSPECTO+"&id_producto="+$scope.id_producto+"&id_contacto="+$scope.formDataGenCotizacion.contactoprospecto1+"&id_domicilio="+$scope.formDataGenCotizacion.domicilioprospecto1+"&id_cotizacion="+$scope.obj_cotizacion.ID+"&tramites="+JSON.stringify(formDataGenCotizacion.tramites)+"&descripcion="+JSON.stringify(formDataGenCotizacion.descripcion);
		window.open(url,'_blank');
		$("#modalGenerarCotizacion").modal("hide");
	}
	 /*===================================================================================================*/
	 function Contactos_Prospecto(id){
	 //$scope.ContactoProspectos1 = {};
		$http.get(  global_apiserver + "/prospecto_contacto/getAll/?id="+id)
		.then(function( response ){
			$scope.ContactoProspectos1 = response.data;
			
		});
	 
	 }
	 function Domicilios_Prospecto(id){
	 //$scope.DommicilioProspectos1 = {};
		$http.get(  global_apiserver + "/prospecto_domicilio/getAll/?id="+id)
		.then(function( response ){
			$scope.DomicilioProspectos1 = response.data;
			
		});
	 
	 }
	 
	  function Contactos_Cliente(id){
	 //$scope.ContactoProspectos1 = {};
		$http.get(  global_apiserver + "/clientes_contactos/getByIdCliente/?id="+id)
		.then(function( response ){
			$scope.ContactoClientes1 = response.data;
			
		});
	 
	 }
	 
	 function Domicilios_Cliente(id){
	 //$scope.DommicilioProspectos1 = {};
		$http.get(  global_apiserver + "/clientes_domicilios/getByClient/?id="+id)
		.then(function( response ){
			$scope.DomicilioClientes1 = response.data;
			
		});
	 
	 }
	
	  function tramite_tarifa_adicional_by_tramite(id,key){
		$http.get(  global_apiserver + "/cotizacion_tarifa_adicional/getByIdTramite/?id="+id+"&id_cot="+global_id_cotizacion)
		.then(function( response ){
			
				$scope.tarifa_adicional_tramite_cotizacion_by_tramite[key] = response.data;
			
			
		});	
	 }
	 /*===================================================================================================*/
	 /*===================================================================================================*/
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
	$scope.tramite_insertar_editar.REVISION_DOCUMENTAL = '1';
    //clean_checkbox_cambio();
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

   $scope.tarifa_adicional_guardar = function(){
    $scope.obj_tarifa_adicional.ID_TRAMITE = current_tramite;
	$scope.obj_tarifa_adicional.ID_COTIZACION = global_id_cotizacion, 
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

  $scope.modal_sitio_editar = function(id){
    var http_request = {
      method: 'GET',
      url: global_apiserver + "/cotizacion_sitios_tur/getById/?id="+id,
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
      url: global_apiserver + "/cotizaciones_tramites_tur/getById/?id="+id,
    };

    $http(http_request).success(function(data) {
      if(data) { 
        $scope.tramite_insertar_editar = data; 
        accion_tramite = "editar";
        //get_cambios_servicios(data.ID);
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
    //Id del trámite
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
      url: global_apiserver + "/cotizaciones_tramites_tur/getSitios/?id="+id +"&cotizacion="+global_id_cotizacion,
    };
    $http(http_request).success(function(data) {
      if(data) { 
        $scope.obj_cotizacion_tramite = data;
        $scope.arr_sitios_cotizacion = data.COTIZACION_SITIOS;
        $scope.arr_tarifa_adicional_cotizacion = data.COTIZACION_TARIFA_ADICIONAL;

        $scope.tipo_auditoria_e1 = false;
        if($scope.arr_sitios_cotizacion.length > 0){
          if($scope.arr_sitios_cotizacion[0].ETAPA == 'E1') {//Etapa 1 ocultar datos
            $scope.tipo_auditoria_e1 = true;
          } else {
            $scope.tipo_auditoria_e1 = false;
          }
        }        
        $('#sitio_tramite').show();
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
      ID_TIPO_AUDITORIA : $scope.tramite_insertar_editar.ID_TIPO_AUDITORIA,
      VIATICOS : $scope.tramite_insertar_editar.VIATICOS,
      DESCUENTO : $scope.tramite_insertar_editar.DESCUENTO,
      AUMENTO : $scope.tramite_insertar_editar.AUMENTO,
  //    REDUCCION : $scope.tramite_insertar_editar.REDUCCION,
	  REVISION_DOCUMENTAL : $scope.tramite_insertar_editar.REVISION_DOCUMENTAL,
      ID_USUARIO : sessionStorage.getItem("id_usuario"),

    }
    
    if (accion_tramite == 'insertar') {
       var http_request = {
        method: 'POST',
        url: global_apiserver + "/cotizaciones_tramites_tur/insert/",
        data: angular.toJson(tramite)
      };
    }
    else if (accion_tramite == 'editar'){
      var http_request = {
        method: 'POST',
        url: global_apiserver + "/cotizaciones_tramites_tur/update/",
        data: angular.toJson(tramite)
      };
    }
   
    $http(http_request).success(function(data) {
      if(data) { 
        if (data.resultado == "ok") {
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

 
 

  $scope.cotizacion_sitio_guardar = function(){
    $scope.obj_sitio.ID_COTIZACION = current_tramite;
    $scope.obj_sitio.ID_USUARIO= sessionStorage.getItem("id_usuario");
	if($scope.obj_cotizacion.NORMAS[0].ID_NORMA=='NMX-AA-120-SCFI-2006'||$scope.obj_cotizacion.NORMAS[0].ID_NORMA=='NMX-AA-120-SCFI-2016'){
	}
	else{
		$scope.obj_sitio.LONGITUD_PLAYA = 0;
	}
    if (accion_sitio == 'insertar') {
      var http_request = {
        method: 'POST',
        url: global_apiserver + "/cotizacion_sitios_tur/insert/",
        data: angular.toJson($scope.obj_sitio)
      };
    }
    else if (accion_sitio == 'editar'){
      var http_request = {
        method: 'POST',
        url: global_apiserver + "/cotizacion_sitios_tur/update/",
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
    url: global_apiserver + "/cotizacion_sitios_tur/delete/?id="+id_cotizacion_sitio,
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
      url: global_apiserver + "/cotizacion_sitios_tur/updateSeleccionado/?id="+id_sitio,
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
  function get_cambios_servicios(id_tramite){
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