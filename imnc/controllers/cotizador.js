app.controller("cotizador_controller", ['$scope','$window', '$http','$document', function($scope,$window,$http,$document){
  
  $scope.modulo_permisos =  global_permisos["COTIZADOR"];
  $scope.arr_cotizaciones = [];
  $scope.arr_prospectos = [];
  $scope.arr_clientes = [];
  $scope.arr_tramites = [];
  $scope.formData = {};
  $scope.cotizacion_insertar_editar = {};
  $scope.bandera = 0;
  $scope.Normas = [];
  $scope.select_pos = -1;
  $scope.Usuarios=new Array();
  $scope.fechInicial="";
  $scope.ascendentemente=true;
  var desde = $('#fechInicial').datepicker({    
    dateFormat: "dd/mm/yy",   
    language: "es", 
   // startDate: new Date(),
  //  range: true,     
   // autoClose: true,  
    onSelect: function (dateText, ins) {       
       $scope.fechInicial = dateText;      
    }
    }).css("display", "inline-block");
  
    var hasta = $('#fechFinal').datepicker({      
      dateFormat: "dd/mm/yy",     
      language: "es",     
      onSelect: function (dateText, ins) {                  
         $scope.fechFinal = dateText;//dateText.substr(6,4)+dateText.substr(3,2)+dateText.substr(0,2);     
      }
      }).css("display", "inline-block");  


  //bandera que sirve para saber el modo: edición o inserción y actuar acorde en el modal de cotizador
  $scope.editando=1;

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
    if(servicio.ID == 3){
      $scope.lblTipoServicio = "Módulo";
    } else {
      $scope.lblTipoServicio = "Tipo de servicio";
    }
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

  $scope.UsuarioCreacFiltroChange = function () {    
    const id_usuario = $scope.selectFiltroUsuarioCreac;
    $scope.filtra_cotizaciones_usuarioCreac(id_usuario);
  }

  $scope.EstadoFiltroChange = function () {    
    const id_estado = $scope.selectFiltroEstado;
    $scope.filtra_estados(id_estado);
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
    $scope.onChangeModalidades($scope.cotizacion_insertar_editar.ID_TIPO_SERVICIO.ID);
	$scope.fill_select_tarifa_id_tipo_servicio($scope.cotizacion_insertar_editar.ID_TIPO_SERVICIO.ID);
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
   $scope.fill_select_tarifa_id_tipo_servicio = function(idts){
    //recibe la url del php que se ejecutará
    $http.get(  global_apiserver + "/tarifa_cotizacion/getByIdTipoServicio/?id="+idts)
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
        $("#selectCliente").html('<option value="" selected disabled>---Seleccione un cliente---</option>');
        $.each(data, function( indice, obj ) {
          if (seleccionado == obj.ID) {
            $("#selectCliente").append('<option value="'+obj.ID+'" selected>'+obj.NOMBRE+'</option>');
          }else{
            $("#selectCliente").append('<option value="'+obj.ID+'">'+obj.NOMBRE+'</option>');
          }

        });
        $('#selectCliente').select2();
        $("#selectCliente").val(seleccionado);
        $("#selectCliente" ).change();
        $("#selectCliente" ).on('change',function (e) {
          if(this.selectedIndex>0)
          {
            $scope.select_pos = this.selectedIndex;
            $scope.cambioCliente();
          }
        });
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
        $("#selectProspecto").html('<option value="" selected disabled>---Seleccione un prospecto---</option>');
        $.each(data, function( indice, obj ) {
          if (seleccionado == obj.ID) {
            $("#selectProspecto").append('<option value="'+obj.ID+'" selected>'+obj.NOMBRE+'</option>');
          }else{
            $("#selectProspecto").append('<option value="'+obj.ID+'">'+obj.NOMBRE+'</option>');
          }

        });
        $('#selectProspecto').select2();
        $("#selectProspecto").val(seleccionado);
        $("#selectProspecto" ).change();
        $("#selectProspecto" ).on('change',function (e) {
          if(this.selectedIndex>0)
          {
            $scope.select_pos = this.selectedIndex;
            $scope.cambioProspecto();
          }
        });

      }
      else  {
        console.log("No hay datos");
      }
    }).error(function(response) {
      console.log("Error al generar petición: " + response);
    });
  }

  $scope.ordenarXfecha=function(){    
    if ($scope.arr_cotizaciones.length<2)
      { notify("Ordenando","No tiene sentido el ordenamiento","error"); 
        return ;
      }
    $scope.ascendentemente=!$scope.ascendentemente;
    $scope.ordenaXfech($scope.arr_cotizaciones);
    if ($scope.ascendentemente)
      notify("Ordenando","Se ha ordenado iniciando por el más antiguo","success"); 
     else
      notify("Ordenando","Se ha ordenado iniciando por el más reciente","success");  
  }

  $scope.muestra=function(){    
    if (($scope.fechInicial!="") && ($scope.fechFinal!=""))
       {
        dateText=$scope.fechInicial;  
        fde=dateText.substr(6,4)+dateText.substr(3,2)+dateText.substr(0,2); 
        dateText=$scope.fechFinal;         
        fhasta=dateText.substr(6,4)+dateText.substr(3,2)+dateText.substr(0,2);    
        $scope.filtra_fechas(fde,fhasta);
       }
     else 
       notify("Error", "Seleccione fecha inicial y final", "error");
    }

  // Pinta tabla de cotizaciones
  $scope.despliega_cotizaciones = function () {   
    $scope.fill_select_prospectos("");
    $scope.fill_select_clientes("");
    $scope.fill_select_estatus("");
    //$scope.fill_select_tarifa();
    fill_select_servicio();
    fill_select_tipo_servicio();
    $scope.titulo_columna_tarifa = 'Tarifa día auditor';
    $scope.titulo_columna_info = 'Prospecto, tipo de servicio y norma';

    //$scope.CursosLista(3,null);
    //$scope.CursosProgramadoLista(3,null);

    var http_request = {
      method: 'GET',
      url: global_apiserver + "/cotizaciones/getAll/",
    };

    $http(http_request).success(function(data) {
      var UsuariosTodos =[];
      $scope.Usuarios=[];
      $scope.Estados=[];
      if(data) {
        $scope.formatFecha(data); 
        $scope.arr_cotizaciones = data;  
        UsuariosTodos=data;
        var esta=false;
        var agregado=false;
        var miembro={};
        var estado={};
        UsuariosTodos.forEach(function(valor,pos)
        {
            esta=false;
            agregado=false;
            miembro={ID: valor.ID_USUARIO_CREACION,
                 NOMBRE : valor.UsuarioCreac
                  }            
            estado={ID:valor.ESTADO_COTIZACION,
                  NOMBRE:valor.ESTATUS_SEGUIMIENTO}
            nombEnc=valor.UsuarioCreac;
            nombEst=valor.ESTATUS_SEGUIMIENTO;            
            $scope.Usuarios.forEach(function(valor1){
            otronomb=valor1.NOMBRE;            
            if (nombEnc==otronomb)
              esta=true;              
          })

          $scope.Estados.forEach(function(valor1){
            otronomb=valor1.NOMBRE;            
            if (nombEst==otronomb)
            agregado=true;              
          })

          if (!esta)
            $scope.Usuarios.push(miembro);
            // $scope.Usuarios.push({ID:valor.ID_USUARIO_CREACION, NOMBRE:valor.UsuarioCreac})  

          if (!agregado)
            $scope.Estados.push(estado);
                      
        })
                       
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
        $scope.formatFecha(data); 
        $scope.arr_cotizaciones = data;
      }
      else  {
        console.log("No hay datos");
      }
    }).error(function(response) {
      console.log("Error al generar petición: " + response);
    });
  }

  //Filtrar por el id del usuario creador
  $scope.filtra_cotizaciones_usuarioCreac = function (id_usuario) {   
    var http_request = {
      method: 'GET',
      url: global_apiserver + "/cotizaciones/getAllByIdServicio/?id_usuario="+id_usuario,
    };

    $http(http_request).success(function(data) {
      if(data) {
        $scope.formatFecha(data); 
        $scope.arr_cotizaciones = data;
      }
      else  {
        console.log("No hay datos");
      }
    }).error(function(response) {
      console.log("Error al generar petición: " + response);
    });
  }
  
//Filtrar por el id del estado
$scope.filtra_estados = function (id_estado) {   
  var http_request = {
    method: 'GET',
    url: global_apiserver + "/cotizaciones/getAllByIdServicio/?id_estado="+id_estado,
  };

  $http(http_request).success(function(data) {
    if(data) {      
      $scope.formatFecha(data);     
      $scope.arr_cotizaciones = data;
    }
    else  {
      console.log("No hay datos");
    }
  }).error(function(response) {
    console.log("Error al generar petición: " + response);
  });
}

$scope.ordenaXfech=function(datos){
  datos.sort(function(el1,el2){
    fech1=el1.FECHA_CREACION;
    fech2=el2.FECHA_CREACION;
    // llevar la fecha del formato 04/08/2019 a 20190804 para facilitar el ordenamiento
    fech1=fech1.substr(6,4)+fech1.substr(3,2)+fech1.substr(0,2);
    fech2=fech2.substr(6,4)+fech2.substr(3,2)+fech2.substr(0,2);
    if ($scope.ascendentemente)
      return fech1>fech2
     else 
      return fech1<fech2
  })
}

$scope.formatFecha=function(datos)
{
  datos.forEach(function(elto)
  {
    // desglosar la fecha para luego componerla en un formato visual más legible
    anio=elto.FECHA_CREACION.substr(0,4);
    mes=elto.FECHA_CREACION.substr(4,2);
    dia=elto.FECHA_CREACION.substr(6,2);
    elto.FECHA_CREACION=dia+'/'+mes+'/'+anio;
  });
} 
//Filtrar por rango de fechas
$scope.filtra_fechas = function (inicio,fin) {
  var http_request = {
    method: 'GET',
    url: global_apiserver + "/cotizaciones/getAllByIdServicio/?fech_inic="+inicio+"&fech_fin="+fin,
  };

  $http(http_request).success(function(data) {
    if(data) {
      $scope.formatFecha(data); 
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
    $scope.editando=0;
    
    //Limpiar el listado de normas sugeridas
    $scope.Normas = [];
    //Limpiar el control de normas
    $scope.normas_cotizacion = [];

    //Inicial para auditorias
    $scope.lblTipoServicio = "Tipo de servicio";

    $('#modalTituloCotizacion').html("Agregar cotización");
    //$('#btnGuardarUsuario').attr("opcion", "insertar");
    $scope.opcion_guardar_cotizacion = "insertar";
    $scope.cotizacion_insertar_editar = {};
    $scope.bandera = 0;
    $scope.fill_select_prospectos("");
    $scope.fill_select_clientes("");
    $scope.fill_select_estatus("");
    //$scope.fill_select_tarifa();
    fill_select_tipo_servicio();
    $scope.changeReferencia();
    $scope.modalidades = "";
    $scope.opciones_participantes = "";
    $scope.cantidad_participantes = 0;
    $('#modalInsertarActualizarCotizacion').modal('show');
  }

  // Abrir modal para editar
  $scope.modal_cotizacion_editar = function(id_cotizacion){
    $scope.editando=1;    
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
		case 18:
            $scope.cotizacion_insertar_editar.DICTAMEN_CONSTANCIA = data[0].DETALLES[0].VALOR;
            break;
          default:
            break;
        }
        //Cotización CIFA
        if($scope.cotizacion_insertar_editar.ID_SERVICIO.ID == 3){          
          $scope.tipo_persona = "";
          $scope.modalidades = $scope.cotizacion_insertar_editar.MODALIDAD; 
          if($scope.modalidades == 'insitu'){
            validar_cursos_cargados($scope.modalidades,$scope.cotizacion_insertar_editar.ID_CURSO,$scope.cotizacion_insertar_editar.ID_TIPO_SERVICIO.ID);
          } else if($scope.modalidades == 'programado') {
            validar_cursos_cargados($scope.modalidades,$scope.cotizacion_insertar_editar.ID_CURSO_PROGRAMADO,$scope.cotizacion_insertar_editar.ID_TIPO_SERVICIO.ID);
          }        
          $scope.cantidad_participantes = $scope.cotizacion_insertar_editar.CANT_PARTICIPANTES;
          if($scope.cotizacion_insertar_editar.SOLO_CLIENTE == 0){
            $scope.opciones_participantes = 'participantes';
          }else if($scope.cotizacion_insertar_editar.SOLO_CLIENTE == 1){
            $scope.opciones_participantes = 'solo_cliente';
          }           
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

  function validar_cursos_cargados(modalidad,id_curso,id_tipo_servicio){
    if($scope.Cursos){
      if($scope.Cursos.length > 0){
        $scope.cursos_programados = id_curso;        
      } else {
        if(modalidad == 'programado'){
          $scope.CursosProgramadoLista(id_tipo_servicio,id_curso);
        } else {
          $scope.CursosLista(id_tipo_servicio,id_curso);
        }
      }              
    } else {
      if(modalidad == 'programado'){
        $scope.CursosProgramadoLista(id_tipo_servicio,id_curso);
      } else {
        $scope.CursosLista(id_tipo_servicio,id_curso);
      }
    }
  }
  $scope.cotizacion_guardar = function(){
    var cotizacion;
    var id_entidad = 0;
    var solo_cliente = 0;
    if($scope.opciones_participantes == 'solo_cliente'){
      solo_cliente = 1;
      $scope.cantidad_participantes = 1;
    } else if($scope.opciones_participantes == 'participantes'){
      solo_cliente = 0;      
    }
    var id_curso = 0;
    if($scope.modalidades == 'programado'){
      id_curso = $scope.cursos_programados;
    } else if($scope.modalidades == 'insitu'){
      id_curso = $scope.cursos_insitu;
    }
    if($scope.bandera == 0){
      id_entidad = $("#selectProspecto").val();
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
		    DICTAMEN_CONSTANCIA: $scope.cotizacion_insertar_editar.DICTAMEN_CONSTANCIA,
        MODALIDAD: $scope.modalidades,
        ID_CURSO: id_curso,
        CANT_PARTICIPANTES: $scope.cantidad_participantes,
        SOLO_CLIENTE: solo_cliente,
        ID_USUARIO : sessionStorage.getItem("id_usuario")
      }
    }else{
      //id_entidad = $scope.cotizacion_insertar_editar.CLIENTE.ID;
      id_entidad = $("#selectCliente").val();
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
		    DICTAMEN_CONSTANCIA: $scope.cotizacion_insertar_editar.DICTAMEN_CONSTANCIA,
        MODALIDAD: $scope.modalidades,
        ID_CURSO: id_curso,
        CANT_PARTICIPANTES: $scope.cantidad_participantes,
        SOLO_CLIENTE: solo_cliente,
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
   $http.get(  global_apiserver + "/servicio_cliente_etapa/getReferenciaByClient/?cliente="+$("#selectCliente").val())
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
    //Determinar si es persona fisica o moral
   if($scope.select_pos>0)
   {
     $scope.tipo_persona = $scope.arr_clientes[$scope.select_pos-1].TIPO_PERSONA;
   }
 }

 $scope.cambioProspecto = function(){
   if($scope.select_pos>0)
   {
     $scope.tipo_persona = $scope.arr_prospectos[$scope.select_pos-1].TIPO_PERSONA;
   }

 }
  $scope.cambioRatio = function(bandera)
  {
    if(bandera==1)
    {
      $scope.fill_select_clientes('');
    }
    if(bandera==0)
    {
      $scope.fill_select_prospectos('')
    }
  }
 $scope.onChangeModalidades = function(id_tipo_servicio,seleccionado)
    {

        if($scope.modalidades == "programado")
        {
          $("#labelCurso").text("Cursos Programados");
        	$scope.CursosProgramadoLista(id_tipo_servicio,seleccionado);
        }

        if($scope.modalidades == "insitu")
        {
            $("#labelCurso").text("Cursos");
        	$scope.CursosLista(id_tipo_servicio,seleccionado);
        }

    }
    $scope.CursosProgramadoLista = function(id,seleccionado){
      //recibe la url del php que se ejecutará
          $scope.Cursos = {};
          $http.get(  global_apiserver + "/cursos_programados/getByModulo/?id="+id)
          .then(function( response ) {//se ejecuta cuando la petición fue correcta
            $scope.Cursos = response.data.map(function(item){
              if(item!=null)
              {
                return{
                  id : item.ID,
                  nombre : item.NOMBRE +" ["+item.FECHAS+"]",
                }
              }  
            });
            if(seleccionado){
            $scope.cursos_programados = seleccionado;
          }
        },
        function (response){});
    }
      $scope.CursosLista = function(id,seleccionado){
          //recibe la url del php que se ejecutará
          $scope.Cursos = {};
          $http.get(  global_apiserver + "/cursos/getByModulo/?id="+id)
              .then(function( response ) {//se ejecuta cuando la petición fue correcta
                      $scope.CursosInsitu = response.data.map(function(item){
                          return{
                              id : item.ID_CURSO,
                              nombre : item.NOMBRE,
                          }
                      });
                      if(seleccionado){
                          $scope.cursos_insitu = seleccionado;
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
			$scope.fill_select_tarifa_id_tipo_servicio($scope.cotizacion_insertar_editar.ID_TIPO_SERVICIO.ID);
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


  $scope.generar_servicio = function(cotizacion) {
      //para cursos programados uso otro end point
      if (cotizacion.ID_SERVICIO == 3 && cotizacion.CURSO.MODALIDAD == 'programado') {

          if (cotizacion.BANDERA != 0) {
              $scope.insertaCliente(cotizacion);
          }
          else {
              if(cotizacion.ID_PROSPENTO_SERVICIO != 0)
              {
                  $scope.insertaCliente(cotizacion);
              }else {
                  notify('Error', "Para crear un servicio curso programado se necesita un cliente, este prospecto no tiene cliente", 'error');
              }

          }

      } else {

          var id_cliente = cotizacion.ID_PROSPECTO;
          var cliente_prospecto = '';
          //Determinar si la cotización es para un prospecto o cliente
          if (cotizacion.BANDERA != 0) {
              cliente_prospecto = 'cliente';
          } else {
              cliente_prospecto = 'prospecto';
          }

          const id_servicio = cotizacion.ID_SERVICIO;
          const id_tipo_servicio = cotizacion.ID_TIPO_SERVICIO;
          //Información del servicio a insertar para prospecto
          var datos = {
              ID_COTIZACION: cotizacion.ID,
              CLIENTE_PROSPECTO: cliente_prospecto,
              ID_CLIENTE: id_cliente,
              ID_SERVICIO: id_servicio,
              ID_TIPO_SERVICIO: id_tipo_servicio,
              ID_ETAPA_PROCESO: 31, //confirmado
              CAMBIO: "N",
              MODALIDAD: cotizacion.CURSO.MODALIDAD,
              ID_CURSO: cotizacion.CURSO.ID_CURSO,
              ID_CURSO_PROGRAMADO: cotizacion.CURSO.ID_CURSO_PROGRAMADO,
              ID_USUARIO: sessionStorage.getItem("id_usuario")
          };
          $http.post(global_apiserver + "/servicio_cliente_etapa/insertDesdeCotizador/", datos).then(function (response) {
              if (response.data.resultado == 'ok') {
                  notify('Éxito', 'Se ha insertado un nuevo registro', 'success');
                  $scope.despliega_cotizaciones();
              } else {
                  notify('Error', response.data.mensaje, 'error');
              }
          });
      }


  }

    $scope.insertaCliente= function (cotizacion) {


        var id_cliente = cotizacion.ID_PROSPECTO;
        var cliente_prospecto = '';
        //Determinar si la cotización es para un prospecto o cliente
        if (cotizacion.BANDERA != 0) {
            cliente_prospecto = 'cliente';
        } else {
            cliente_prospecto = 'prospecto';
        }

        var add = {
            ID_COTIZACION: cotizacion.ID,
            ID_CURSO:cotizacion.CURSO.ID_CURSO,
            ID_CURSO_PROGRAMADO:cotizacion.CURSO.ID_CURSO_PROGRAMADO,
            CLIENTE_PROSPECTO: cliente_prospecto,
            ID_CLIENTE: id_cliente,
            CANTIDAD_PARTICIPANTES:cotizacion.CURSO.CANT_PARTICIPANTES,
            SOLO_PARA_CLIENTE:cotizacion.CURSO.SOLO_CLIENTE
        }
        $.post(global_apiserver + "/cursos_programados/insertClienteDesdeCotizacion/", JSON.stringify(add), function (respuesta) {
            respuesta = JSON.parse(respuesta);
            if (respuesta.resultado == "ok") {
                notify('Éxito','Se ha insertado un nuevo registro','success');
                $scope.despliega_cotizaciones();
            }
            else {
                notify("Error", respuesta.mensaje, "error");
            }
        });

    }


  $scope.mostrar_enlace = function(url){
    $.dialog({
      title: 'Enlace para cargar participantes',
      content: url,
      columnClass: 'col-md-8 col-md-offset-2'
    });    
  }
  
  $scope.cambio_dictamen_constancia = function() {
    if($scope.cotizacion_insertar_editar.DICTAMEN_CONSTANCIA == "Dictamen"){
		$scope.Tarifa_Cotizacion.forEach(tarifa => {
		if(tarifa.id == 19){
			$scope.cotizacion_insertar_editar.TARIFA = tarifa.id;
        }
		});

	}
	if($scope.cotizacion_insertar_editar.DICTAMEN_CONSTANCIA == "Constancia"){
		$scope.Tarifa_Cotizacion.forEach(tarifa => {
		if(tarifa.id == 20){
			$scope.cotizacion_insertar_editar.TARIFA = tarifa.id;
        }
		});
	}
  }

}]);