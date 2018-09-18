
// ================================================================================
// *****                        Al cargar la página                           *****
// ================================================================================
  
  Date.prototype.yyyymmdd = function() {
    var mm = this.getMonth() + 1; // getMonth() is zero-based
    var dd = this.getDate();

    return [this.getFullYear(),
            (mm>9 ? '' : '0') + mm,
            (dd>9 ? '' : '0') + dd
           ].join('');
  };

  Date.prototype.ddmmyyy = function() {
    var mm = this.getMonth() + 1; // getMonth() is zero-based
    var dd = this.getDate();

    return [(dd>9 ? '' : '0') + dd,"/",
            (mm>9 ? '' : '0') + mm,"/",
            this.getFullYear()
            
           ].join('');
  };

  $( window ).load(function() {
      draw_perfil();
      draw_tabla_servicios();
      listener_btn_nuevo();
      listener_btn_guardar();
      listener_btn_guardar_certificado();
      listener_btn_guardar_sector();
      listener_btn_guardar_sitio();
      listener_btn_guardar_auditoria();
      listener_btn_guardar_sitio_auditoria();
      listener_btn_guardar_grupo_auditoria();
      listener_btn_generar_notificacion_pdf();
      listener_cmb_estatus_certificado();
      listener_btn_reemplazar_archivo_certificado();
      setup_subir_paso_1_1();
      setup_subir_paso_1_2();
      setup_subir_paso_4();
      setup_subir_paso_5();
      setup_subir_paso_6();
      setup_subir_paso_7_1();
      setup_subir_paso_7_2();
      setup_subir_paso_8_1();
      setup_subir_paso_8_2();
      setup_subir_paso_8_3();
      setup_subir_paso_8_4();
       
  });

  $(document).ready(function() {
    // Smart Wizard
    $('#wizard').smartWizard({
      labelNext:'Siguiente', // label for Next button
      labelPrevious:'Anterior', // label for Previous button
      labelFinish:'Fin',  // label for Finish button
      reverseButtonsOrder: true,
      onShowStep:onShowStepCallback,
    });

    function onFinishCallback() {
      $('#wizard').smartWizard('showMessage', 'Finish Clicked');
      //alert('Finish Clicked');
    }

    function onShowStepCallback() {
      //alert('Finish Clicked');
      $(".txtFechaInicio").val($("#txtFechaInicio").val());
      $(".txtFechaInicio").attr("disabled", "true");
    }
  });

// ================================================================================
// *****                       Funciones de uso común                         *****
// ================================================================================

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

  function alerta(titulo, texto, tipo){
    new TabbedNotification({
        title: titulo,
        text: texto,
        type: tipo,
        sound: false
    })
  }

  function init_btn_subir_archivos(btn){
    $(".ajax-file-upload-statusbar").hide();
    $(btn).uploadFile({
        url:global_apiserver + "/repositorio/archivos/subir/",
        multiple:false,
        dragDrop:true,
        maxFileCount:1,
        acceptFiles:".pdf",
        allowedTypes:"pdf",
        fileName:"archivo_certificado",
        dragDropStr: "<span><b>Arrastra el archivo</b></span>",
        abortStr:"abortar",
        cancelStr:"cancelar",
        doneStr:"carga completa",
        multiDragErrorStr: "Error al arrastrar los archivos",
        extErrorStr:"Error: faltan extensiones",
        sizeErrorStr:"n'est pas autorisé. Admis taille max:",
        uploadErrorStr:"Error al cargar archvio",
        uploadStr:"Explorar",
        onSuccess:function(files,data,xhr,pd)
        {
          console.log(data);
          data = JSON.parse(data);
          if (data.resultado == "ok") {
            notify("Aviso", "El archivo se ha subido al servidor", "dark");
            $(btn).hide();
            $(btn).attr("nombre_archivo", data.nombre_archivo);  
          }
          else{
            notify("Error", data.mensaje, "error");
          } 
        },
    }); 
  }

  function draw_perfil(){
    $.getJSON( global_apiserver + "/servicio_cliente_etapa/getById/?id="+global_id_servicio_cliente_et+"&domicilios=false", function( objSClienteEt ) {
      $("#lbCliente").html("Cliente: " + objSClienteEt.NOMBRE_CLIENTE);
      $("#lbServicio").html("Servicio: " + objSClienteEt.NOMBRE_SERVICIO);
      if(objSClienteEt.CLAVE_SERVICIO=='CSG'){
        console.log("entra");
        $("#nombreProcesos").show();
      }else{
        $("#nombreProcesos").hide();
        console.log("no entra");
      }
      $("#lbEtapa").html("Trámite: " + objSClienteEt.NOMBRE_ETAPA);
      $("#lbReferencia").html("Referencia: " + objSClienteEt.REFERENCIA);
      if (objSClienteEt.SG_INTEGRAL == 'S') {
        $("#lbIntegral").html( "Es SG integral");
      }
      else if (objSClienteEt.SG_INTEGRAL == 'N') {
        $("#lbIntegral").html( "NO es SG integral");
      }

      if(objSClienteEt.COTIZACION >= 1){
        $("#btnNuevo").hide();
      }
      else{
        $("#btnNuevo").show();
      }
      
    });
  }

// ================================================================================
// *****                       Tipo de servicio                               *****
// ================================================================================

  function fill_cmb_tipoServicio(seleccionado){
    $.getJSON(  global_apiserver + "/tipos_servicio/getAll/?filtro=vigentes", function( response ) {
      $("#claveTipoServicio").html('<option value="elige" selected disabled>-elige una opción-</option>');
      $.each(response, function( indice, objTserv ) {
        $("#claveTipoServicio").append('<option norma="'+objTserv.ID_NORMA+'" value="'+objTserv.ID+'">'+objTserv.NOMBRE+'</option>'); 
      });
      listener_select_tiposervicio_change();
      $("#claveTipoServicio").val(seleccionado);
      //$("#txtClaveNorma").val($("#claveTipoServicio option:selected").attr("norma"));
      $("#claveTipoServicio").change(function(){
        if($(this).val() == "CSGA"){
          $("#complejidadForm").show();
        }
        else{
          $("#txtcomplejidad").val("");
          $("#complejidadForm").hide(); 
        }
      });
      $("#claveTipoServicio").prop("disabled", true);
      if (seleccionado == "elige") {
        $("#claveTipoServicio").prop("disabled", false);
      }
    });
  }

  function listener_select_tiposervicio_change(){
    $( "#claveTipoServicio" ).change(function() {
      $("#txtClaveNorma").val($("#claveTipoServicio option:selected").attr("norma"));
    });
  }

  function fill_cmb_multisitios(seleccionado){
    $("#multisitios").html('<option value="elige" selected disabled>-elige una opción-</option>');
    $("#multisitios").append('<option value="S">Si</option>'); 
    $("#multisitios").append('<option value="N">No</option>'); 
    $("#multisitios").val(seleccionado);
  }

  function clear_modal_insertar_actualizar(){
    $("#txtClave").val("");
    $("#txtClave").removeAttr("readonly");
    $("#txtTotalEmpleados").val("");
    $("#txtTotalEmpleadosCertificacion").val("");
    $("#txtTurnos").val("");
    $("#txtAlcance").val("");
    $("#txtClaveSCE").val("");
    $("#txtClaveSCE").removeAttr("readonly");
    $("#txtcomplejidad").val("");
    $("#complejidadForm").hide();
    fill_cmb_tipoServicio("elige");
    ////fill_cmb_norma("elige");
    $("#txtClaveNorma").val("");
    fill_cmb_multisitios("elige");
    $("#condicionesSeguridad").val("");
  }

  function fill_modal_insertar_actualizar(id_servicio){
    $.getJSON(  global_apiserver + "/sg_tipos_servicio/getById/?id="+id_servicio+"&domicilios=true", function( response ) {
      $("#txtClave").val(response.ID);
      $("#txtTotalEmpleados").val(response.TOTAL_EMPLEADOS);
      $("#txtTotalEmpleadosCertificacion").val(response.TOTAL_EMPLEADOS_PARA_CERTIFICACION);
      $("#txtTurnos").val(response.TURNOS);
      $("#txtAlcance").val(response.ALCANCE);
      $("#txtClaveSCE").val(response.ID_SERVICIO_CLIENTE_ETAPA);
      fill_cmb_tipoServicio(response.ID_TIPO_SERVICIO);
      if(response.ID_TIPO_SERVICIO == "CSGA"){
        $("#txtcomplejidad").val(response.COMPLEJIDAD);
        $("#complejidadForm").show();
      }
      else{
        $("#txtcomplejidad").val("");
        $("#complejidadForm").hide(); 
      }
      $("#txtClaveNorma").val(response.ID_NORMA);
      //fill_cmb_norma(response.ID_NORMA);
      fill_cmb_multisitios(response.MULTISITIOS);
      $("#condicionesSeguridad").val(response.CONDICIONES_SEGURIDAD);
    });
  }

  function draw_row_servicio(objServicio){
    var strHtml = "";
    strHtml += '<tr class="even pointer">';
    strHtml += '  <td>Tipo de servicio: '+objServicio.ID_TIPO_SERVICIO+'<br>Norma: '+objServicio.ID_NORMA+'</td>';
    strHtml += '  <td>'
    if (global_diffname != "onac") {
      strHtml += '# total de empleados por turno: '+objServicio.TOTAL_EMPLEADOS+'<br>';
    }
    strHtml += '# total de empleados para certificación: '+objServicio.TOTAL_EMPLEADOS_PARA_CERTIFICACION+'<br>';
    if (global_diffname != "onac") {
      strHtml += 'Turnos: '+objServicio.TURNOS+'<br>';
    }

    strHtml += 'Multisitios: '+objServicio.MULTISITIOS+'<br>';
    strHtml += 'Condiciones de seguridad: '+objServicio.CONDICIONES_SEGURIDAD+'<br>';
    strHtml += '  </td>'
    strHtml += '  <td style="text-align: justify;">'+objServicio.ALCANCE+'</td>';
    strHtml += '  <td style="width: 180px; text-align: center;">';
    if (global_permisos["SERVICIOS"]["editar"] == 1) {
      strHtml += '<div class="btn-group" style="float: right;">';
      strHtml += '  <button type="button" class="btn btn-primary btn-sm btnEditar" id_servicio="'+objServicio.ID+'" ><i class="fa fa-edit"></i>  Editar servicio</button>';
      strHtml += '  <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
      strHtml += '    <span class="caret"></span>';
      strHtml += '    <span class="sr-only">Toggle Dropdown</span>';
      strHtml += '  </button>';
    }
    else{
      strHtml += '<div class="btn-group">';
      strHtml += '  <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
      strHtml += '    Ver opciones <span class="caret"></span>';
      strHtml += '    <span class="sr-only">Toggle Dropdown</span>';
      strHtml += '  </button>';
    }
    
    strHtml += '  <ul class="dropdown-menu">';
    strHtml += '    <li class="btnSectores" id="'+objServicio.ID+'" > ';
    strHtml += '      <a> <span class="labelAcordeon">Mostrar</span> sectores</a>';
    strHtml += '    </li>';
    strHtml += '    <li class="btnSitios" id="'+objServicio.ID+'" tipo_servicio="'+objServicio.ID_TIPO_SERVICIO+'" > ';
    strHtml += '      <a> <span class="labelAcordeon">Mostrar</span> sitios</a>';
    strHtml += '    </li>';
    strHtml += '    <li class="btnAuditorias" id="'+objServicio.ID+'" tipo_servicio="'+objServicio.ID_TIPO_SERVICIO+'"> ';
    strHtml += '     <a> <span class="labelAcordeon">Mostrar</span> '+global_str_auditorias.toLowerCase()+' </a>';
    strHtml += '    </li>';
    /*strHtml += '    <li role="separator" class="divider"></li>';
    strHtml += '    <li class="btnProceso" id="'+objServicio.ID+'" > ';
    strHtml += '      <a> <span class="labelAcordeon">Mostrar</span> proceso</a>';
    strHtml += '    </li>';*/
    strHtml += '    <li role="separator" class="divider"></li>';
    strHtml += '    <li class="btnCertificado" id="'+objServicio.ID+'" > ';
    strHtml += '      <a> <span class="labelAcordeon">Mostrar</span> certificado</a>';
    strHtml += '    </li>';
    strHtml += '  </ul>';
    strHtml += '</div>';
    strHtml += '</td>';
    strHtml += '</tr>';

    strHtml += '<tr class="collapse out" id="collapse-'+objServicio.ID+'-certificado">';
    strHtml += '  <td colspan="13">';
    strHtml += '    <table class="table subtable">';
    strHtml += '      <caption>Certificado <button type="button" class="btn btn-primary btn-xs btn-imnc btnInsertaEditaCertificado" id_tipo_servicio="'+objServicio.ID_TIPO_SERVICIO+'" id_serv="'+objServicio.ID+'" style="float: right;"> <i class="fa fa-plus"></i> Agregar certificado </button> </caption>';
    strHtml += '      <tbody id="tbody-'+objServicio.ID+'-certificado">';
    strHtml += '      </tbody>';
    strHtml += '    </table>';
    strHtml += '  </td>';
    strHtml += '</tr>';

    strHtml += '<tr class="collapse out" id="collapse-'+objServicio.ID+'-auditoria">';
    strHtml += '  <td colspan="13">';
    strHtml += '    <table class="table subtable">';
    strHtml += '      <caption>'+ global_str_auditorias;
    if (global_permisos["SERVICIOS"]["registrar"] == 1) {
      strHtml += '        <button type="button" class="btn btn-primary btn-xs btn-imnc btnInsertaAuditoria" id_serv="'+objServicio.ID+'" style="float: right;"> <i class="fa fa-plus"></i> Agregar '+global_str_auditorias.toLowerCase()+' </button>';
    }
    strHtml += '      </caption>';
    strHtml += '      <thead id="thead-'+objServicio.ID+'-auditoria">';
    strHtml += '      </thead>';
    strHtml += '      <tbody id="tbody-'+objServicio.ID+'-auditoria">';
    strHtml += '      </tbody>';
    strHtml += '    </table>';
    strHtml += '  </td>';
    strHtml += '</tr>';
    strHtml += '<tr class="collapse out" id="collapse-'+objServicio.ID+'-sector">';
    strHtml += '  <td colspan="13">';
    strHtml += '    <table class="table subtable">';
    strHtml += '      <caption>Sectores del servicio';
    if (global_permisos["SERVICIOS"]["registrar"] == 1) {
      strHtml += '        <button type="button" class="btn btn-primary btn-xs btn-imnc btnInsertaServSector" id_tipo_servicio="'+objServicio.ID_TIPO_SERVICIO+'" id_serv="'+objServicio.ID+'" style="float: right;"> <i class="fa fa-plus"></i> Agregar sector </button>'; 
    }
    strHtml += '      </caption>';
    strHtml += '      <thead id="thead-'+objServicio.ID+'-sector">';
    strHtml += '      </thead>';
    strHtml += '      <tbody id="tbody-'+objServicio.ID+'-sector">';
    strHtml += '      </tbody>';
    strHtml += '    </table>';
    strHtml += '  </td>';
    strHtml += '</tr>';
    strHtml += '<tr class="collapse out" id="collapse-'+objServicio.ID+'-sitio">';
    strHtml += '  <td colspan="13">';
    strHtml += '    <table class="table subtable">';
    strHtml += '      <caption>Sitios del servicio';
    if (global_permisos["SERVICIOS"]["registrar"] == 1) {
      strHtml += '        <button type="button" class="btn btn-primary btn-xs btn-imnc btnInsertaSitio" id_serv="'+objServicio.ID+'" tipo_servicio="'+objServicio.ID_TIPO_SERVICIO+'" style="float: right;"> <i class="fa fa-plus"></i> Agregar sitio </button>';
    }
    strHtml += '      </caption>';
    strHtml += '      <thead id="thead-'+objServicio.ID+'-sitio">';
    strHtml += '      </thead>';
    strHtml += '      <tbody id="tbody-'+objServicio.ID+'-sitio">';
    strHtml += '      </tbody>';
    strHtml += '    </table>';
    strHtml += '  </td>';
    strHtml += '</tr>';
    return strHtml;
  }

  function draw_tabla_servicios(){
    $.getJSON(  global_apiserver + "/sg_tipos_servicio/getByIdServClieEta/?id_serv_cli_eta=" + global_id_servicio_cliente_et, function( response ) {
      $("#tbodyServicios").html("");
      $.each(response, function( index, objServicio ) {
        $("#tbodyServicios").append(draw_row_servicio(objServicio));  
      });
      listener_btn_editar();
      listener_btn_sectores();
      listener_btn_sitios();
      listener_btn_proceso();
      listener_btn_auditoria();
      listener_btn_certificado();
      listener_btn_nuevo_serv_sector();
      listener_btn_nuevo_sitio();
      listener_btn_nueva_auditoria();
      // if (global_sg_tipo_servicio != "") {
      //   abrir_auditorias(global_sg_tipo_servicio);
      // }
    });
  }

  function listener_btn_editar(){
    $( ".btnEditar" ).click(function() {
      $("#btnGuardar").attr("accion","editar");
      $("#txtClave").attr("readonly","true");
      $("#txtClaveSCE").attr("readonly","true");
      $("#btnGuardar").attr("id_servicio",$(this).attr("id_servicio"));
      $("#modalTituloServicio").html("Editar servicio");
      fill_modal_insertar_actualizar($(this).attr("id_servicio"));
      $("#modalInsertarActualizarTServ").modal("show");
    });
  }

  function listener_btn_nuevo(){
    $( "#btnNuevo" ).click(function() {
      $("#btnGuardar").attr("accion","insertar");
      $("#modalTituloServicio").html("Insertar servicio");
      clear_modal_insertar_actualizar();
      $("#txtClave").attr("readonly","true");
      $("#txtClaveSCE").attr("readonly","true");
      $("#txtClaveSCE").val(global_id_servicio_cliente_et);
      $("#modalInsertarActualizarTServ").modal("show");
    });
  }

  function listener_btn_guardar(){
    $( "#btnGuardar" ).click(function() {
      if ($("#btnGuardar").attr("accion") == "insertar")
      {
        insertar();
      }
      else if ($("#btnGuardar").attr("accion") == "editar")
      {
        editar();
      }
    });
  }

  function insertar(){
    var servicio = {
      ID_SERVICIO_CLIENTE_ETAPA:$("#txtClaveSCE").val(),
      ID_TIPO_SERVICIO:$("#claveTipoServicio").val(),
      ID_NORMA:$("#txtClaveNorma").val(),
      TOTAL_EMPLEADOS:$("#txtTotalEmpleados").val(),
      TOTAL_EMPLEADOS_PARA_CERTIFICACION:$("#txtTotalEmpleadosCertificacion").val(),
      TURNOS:$("#txtTurnos").val(),
      MULTISITIOS:$("#multisitios").val(),
      CONDICIONES_SEGURIDAD:$("#condicionesSeguridad").val(),
      ALCANCE:$("#txtAlcance").val(),
      COMPLEJIDAD : $("#txtcomplejidad").val(),
      ID_USUARIO:sessionStorage.getItem("id_usuario")
    };
    $.post( global_apiserver + "/sg_tipos_servicio/insert/", JSON.stringify(servicio), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
          $("#modalInsertarActualizarTServ").modal("hide");
          notify("Éxito", "Se ha insertado un nuevo registro", "success");
          draw_tabla_servicios();
        }
        else{
          notify("Error", respuesta.mensaje, "error");
        }
    });
  }

  function editar(){
    var servicio = {
      ID:$("#txtClave").val(),
      ID_SERVICIO_CLIENTE_ETAPA:$("#txtClaveSCE").val(),
      ID_TIPO_SERVICIO:$("#claveTipoServicio").val(),
      ID_NORMA:$("#txtClaveNorma").val(),
      TOTAL_EMPLEADOS:$("#txtTotalEmpleados").val(),
      TOTAL_EMPLEADOS_PARA_CERTIFICACION:$("#txtTotalEmpleadosCertificacion").val(),
      TURNOS:$("#txtTurnos").val(),
      MULTISITIOS:$("#multisitios").val(),
      CONDICIONES_SEGURIDAD:$("#condicionesSeguridad").val(),
      ALCANCE:$("#txtAlcance").val(),
      COMPLEJIDAD : $("#txtcomplejidad").val(),
      ID_USUARIO:sessionStorage.getItem("id_usuario")
    };
      $.post( global_apiserver + "/sg_tipos_servicio/update/", JSON.stringify(servicio), function(respuesta){
          respuesta = JSON.parse(respuesta);
          if (respuesta.resultado == "ok") {
             $("#modalInsertarActualizarTServ").modal("hide");
            notify("Éxito", "Se han actualizado los datos", "success");
            draw_tabla_servicios();
          }
          else{
            notify("Error", respuesta.mensaje, "error");
          }
      });
  }

// ================================================================================
// *****                       Sectores                                       *****
// ================================================================================

  function fill_cmb_sector(seleccionado){
    $.getJSON(  global_apiserver + "/sectores/getByIdTipoServicio/?id_tipo_servicio="+$("#btnGuardarSector").attr("id_tipo_servicio"), function( response ) {
      $("#ClaveSec").html('<option value="elige" selected disabled>-elige una opción-</option>');
      $.each(response, function( indice, objTserv ) {
        var label_sector = objTserv.ID + "-" + objTserv.ID_TIPO_SERVICIO + "-" + objTserv.ANHIO + ": " + objTserv.NOMBRE;
        $("#ClaveSec").append('<option value="'+objTserv.ID_SECTOR+'">'+label_sector+'</option>'); 
      });
      $("#ClaveSec").val(seleccionado);
    });
  }

  function fill_cmb_principal_sec(seleccionado){
    $("#PrincipalSec").html('<option value="elige" selected disabled>-elige una opción-</option>');
    $("#PrincipalSec").append('<option value="S">Si</option>'); 
    $("#PrincipalSec").append('<option value="N">No</option>');
    $("#PrincipalSec").val(seleccionado);
  }

  function clear_modal_insertar_actualizar_sectores(){
    fill_cmb_sector("elige");
    $("#ClaveSgTServ").val($("#btnGuardarSector").attr("id_serv"));
    $("#ClaveSgTServ").attr("readonly","true");
    fill_cmb_principal_sec("elige");
  }

  function fill_modal_insertar_actualizar_serv_sector(id_serv_sector){
    $.getJSON(  global_apiserver + "/sg_sectores/getById/?id="+id_serv_sector, function( response ) {
      fill_cmb_sector(response.ID_SECTOR);
      //$("#ClaveSec").attr("readonly","true");
      $("#ClaveSgTServ").val(response.ID_SG_TIPO_SERVICIO);
      fill_cmb_principal_sec(response.PRINCIPAL);
    });
  }

  function draw_head_serv_sectores(){
    var strHtml = "";
    strHtml += '      <tr>';
    strHtml += '        <th style="width: 140px;">Clave del sector</th>';
    strHtml += '        <th>Nombre del sector</th>';
    strHtml += '        <th>Principal</th>';
    strHtml += '        <th></th>';
    strHtml += '      </tr>';
    return strHtml;
  }

  function draw_serv_sectores(objServSector){
    var strHtml = "";
    strHtml += '      <tr>';
    strHtml += '        <td>' + objServSector.CLAVE_COMPUESTA + '</td>';
    strHtml += '        <td>' + objServSector.NOMBRE_SECTOR + '</td>';
    strHtml += '        <td>' + objServSector.PRINCIPAL + '</td>';
    strHtml += '        <td colspan="2">';
    if (global_permisos["SERVICIOS"]["editar"] == 1) {
      strHtml += '          <button type="button" class="btn btn-primary btn-xs btn-imnc btnEditarServSector" id_serv_sector="'+objServSector.ID+'" id_tipo_servicio="' + objServSector.ID_TIPO_SERVICIO + '" style="float: right;"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar sector </button>';
    }
    strHtml += '        </td>';
    strHtml += '      </tr>';
    return strHtml;
  }

  function draw_sectores(id_serv, objLabelAcordeon,colapsar){
    $.getJSON( global_apiserver + "/sg_sectores/getBySgTipoServicio/?id_sg_tipo_servicio="+id_serv, function( response ) {
      $("#thead-"+id_serv+"-sector").html("");
      if (response.length > 0) {
        $("#thead-"+id_serv+"-sector").html(draw_head_serv_sectores());
      }
      $("#tbody-"+id_serv+"-sector").html("");
      $.each(response, function( index, objServSector ) {
        $("#tbody-"+id_serv+"-sector").append(draw_serv_sectores(objServSector));
      });
      if(colapsar){
        $("#collapse-"+id_serv+"-sector").collapse("toggle");
        if (objLabelAcordeon.html() == "Mostrar") {objLabelAcordeon.html("Ocultar");}
        else{objLabelAcordeon.html("Mostrar");}
      }
      listener_btn_editar_serv_sector();
    });
  }

  function redraw_sectores(){
    var id_serv = $( "#btnGuardarSector").attr("id-sector");
    var objLabelAcordeon = $("#"+id_serv).find(".labelAcordeon");
    draw_sectores(id_serv, objLabelAcordeon, false);
  }

  function listener_btn_sectores(){
    $( ".btnSectores" ).click(function() {
      var id_serv = $(this).attr("id");
      var objLabelAcordeon = $(this).find(".labelAcordeon");
      $( "#btnGuardarSector" ).attr("id-sector", id_serv);
      draw_sectores(id_serv, objLabelAcordeon, true);
    });
  }

  function listener_btn_editar_serv_sector(){
    $( ".btnEditarServSector" ).click(function() {
      $("#btnGuardarSector").attr("accion","editar");
      $("#btnGuardarSector").attr("id_serv_sector",$(this).attr("id_serv_sector"));
      $("#btnGuardarSector").attr("id_tipo_servicio",$(this).attr("id_tipo_servicio"));
      $("#ClaveSgTServ").attr("readonly","true");
      $("#modalTituloSector").html("Editar sector de servicio");
      fill_modal_insertar_actualizar_serv_sector($(this).attr("id_serv_sector"));
      $("#modalInsertarActualizarTServSector").modal("show");
    });
  }

  function listener_btn_nuevo_serv_sector(){
    $( ".btnInsertaServSector" ).click(function() {
      $("#btnGuardarSector").attr("accion","insertar");
      $("#btnGuardarSector").attr("id_serv",$(this).attr("id_serv"));
      $("#btnGuardarSector").attr("id_tipo_servicio",$(this).attr("id_tipo_servicio"));
      $("#modalTituloSector").html("Insertar sector de servicio");
      clear_modal_insertar_actualizar_sectores();
      $("#modalInsertarActualizarTServSector").modal("show");
    });
  }

  function listener_btn_guardar_sector(){
    $( "#btnGuardarSector" ).click(function() {
      if ($("#btnGuardarSector").attr("accion") == "insertar")
      {
        insertar_sector();
      }
      else if ($("#btnGuardarSector").attr("accion") == "editar")
      {
        editar_sector();
      }
    });
  }

  function insertar_sector(){
    var sector = {
      ID_SG_TIPO_SERVICIO:$("#ClaveSgTServ").val(),
      ID_SECTOR:$("#ClaveSec").val(),
      PRINCIPAL:$("#PrincipalSec").val(),
       ID_USUARIO:sessionStorage.getItem("id_usuario")
    };
    $.post( global_apiserver + "/sg_sectores/insert/", JSON.stringify(sector), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
          $("#modalInsertarActualizarTServSector").modal("hide");
          notify("Éxito", "Se ha insertado un nuevo sector", "success");
          //draw_tabla_servicios();
          redraw_sectores();
        }
        else{
          notify("Error", respuesta.mensaje, "error");
        }
    });
  }

  function editar_sector(){
    var sector = {
      ID_SG_TIPO_SERVICIO:$("#ClaveSgTServ").val(),
      ID_SECTOR:$("#ClaveSec").val(),
      PRINCIPAL:$("#PrincipalSec").val(),
      ID:$( "#btnGuardarSector" ).attr("id_serv_sector"),
       ID_USUARIO:sessionStorage.getItem("id_usuario")
    };
      $.post( global_apiserver + "/sg_sectores/update/", JSON.stringify(sector), function(respuesta){
          respuesta = JSON.parse(respuesta);
          if (respuesta.resultado == "ok") {
             $("#modalInsertarActualizarTServSector").modal("hide");
            notify("Éxito", "Se han actualizado los datos del sector", "success");
            //draw_tabla_servicios();
            redraw_sectores();
          }
          else{
            notify("Error", respuesta.mensaje, "error");
          }
      });
  }

// ================================================================================
// *****                       Sitios                                         *****
// ================================================================================

  function fill_cmb_duracion_sitio(seleccionado){
    $("#cmbDuracion").html('<option value="elige" selected disabled>-elige una opción-</option>');
    $("#cmbDuracion").append('<option value="temporal">Temporal</option>'); 
    $("#cmbDuracion").append('<option value="fijo">Fijo</option>'); 
    $("#cmbDuracion").val(seleccionado);
  }

  function fill_cmb_clave_cliente_domi_sitio(seleccionado){
    $.getJSON(  global_apiserver + "/servicio_cliente_etapa/getById/?id="+global_id_servicio_cliente_et+"&domicilios=true", function( response ) {
      $("#cmbClaveClienteDomSitio").html('<option value="elige" selected disabled>-elige una opción-</option>');
      
      $.each(response.DOMICILIOS_CLIENTE, function( indice, objClieDom ) {
        $("#cmbClaveClienteDomSitio").append('<option value="'+objClieDom.ID+'">'+objClieDom.NOMBRE_DOMICILIO+'</option>'); 
      });
      $("#cmbClaveClienteDomSitio").val(seleccionado);
    });
  }

  function clear_modal_insertar_actualizar_sitios(tipo_servicio){
    $("#txtClaveSitio").val("");
    $("#txtClaveTServSitio").val($("#btnGuardarSitio").attr("id_sitio_serv"));
    $("#txtClaveTServSitio").attr("readonly","true");
    fill_cmb_clave_cliente_domi_sitio("elige");
    $("#txtCantPerso").val("");
    if (tipo_servicio == 'CSAST'){
      $("#formCantidadPersonas").show();
    }
    else{
      $("#formCantidadPersonas").hide(); 
    }
    $("#txtCantTurn").val("");
    $("#txtNoTotalEmplea").val("");
    $("#txtNoEmpleaCertif").val("");
    $("#txtCantProce").val("");
    $("#txtNombreProcesos").val("");
    

    fill_cmb_actividad("");
    
    fill_cmb_duracion_sitio("elige");
    $("#cmbMatrizPrincipal").val("elige");
  }

  function fill_modal_insertar_actualizar_sitios(id_sitio, tipo_servicio){
    $.getJSON(  global_apiserver + "/sg_sitios/getById/?id="+id_sitio, function( response ) {
      $("#txtClaveSitio").val(response.ID);
      $("#txtClaveTServSitio").val(response.ID_SG_TIPO_SERVICIO);
      fill_cmb_clave_cliente_domi_sitio(response.ID_CLIENTE_DOMICILIO);
      $("#txtCantPerso").val(response.CANTIDAD_PERSONAS);
      if (tipo_servicio == 'CSAST'){
      $("#formCantidadPersonas").show();
      }
      else{
        $("#formCantidadPersonas").hide(); 
      }
      $("#txtCantTurn").val(response.CANTIDAD_TURNOS);
      $("#txtNoTotalEmplea").val(response.NUMERO_TOTAL_EMPLEADOS);
      $("#txtNoEmpleaCertif").val(response.NUMERO_EMPLEADOS_CERTIFICACION);
      $("#txtCantProce").val(response.CANTIDAD_DE_PROCESOS);
      $("#txtNombreProcesos").val(response.NOMBRE_PROCESOS);


      fill_cmb_actividad(response.ID_ACTIVIDAD);

      fill_cmb_duracion_sitio(response.TEMPORAL_O_FIJO);
      $("#cmbMatrizPrincipal").val(response.MATRIZ_PRINCIPAL);
    });
  }

  function draw_head_row_sitios(tipo_servicio){
    var strHtml = "";
    strHtml += '        <tr>';
    // strHtml += '         <th>Clave</th>';
    strHtml += '         <th>Tipo de servicio y domicilio</th>';
    // if (tipo_servicio == "CSAST") {
    //   strHtml += '         <th>Cantidad de Personas</th>';  
    // }
    strHtml += '         <th>Información del sitio</th>';
    // strHtml += '         <th>Numero Total de Empleados</th>';
    // strHtml += '         <th>Numero de Empleados con Certificacion</th>';
    // strHtml += '         <th>Cantidad de Procesos</th>';
    // strHtml += '         <th>Duracion</th>';
    strHtml += '         <th></th>';
    strHtml += '        </tr>';
    return strHtml;
  }

  function draw_row_sitios(objSitio, tipo_servicio, id_sitio_serv){
    var strHtml = "";
    strHtml += '      <tr>';
    // strHtml += '        <td>' + objSitio.ID + '</td>';
    strHtml += '        <td>' + objSitio.CLAVE_TIPO_SERVICIO + '<br>';
    strHtml += '        <i>' + objSitio.NOMBRE_DOMICILIO + '</i></td>';
    strHtml += '        <td>No. turnos: ' + objSitio.CANTIDAD_TURNOS + '<br>';
    if (tipo_servicio == "CSAST") {
      strHtml += '        No. personas: ' + objSitio.CANTIDAD_PERSONAS + '<br>';
    }
    strHtml += '        No. total de empleados: ' + objSitio.NUMERO_TOTAL_EMPLEADOS + '<br>';
    strHtml += '        No. empleados para certificación: ' + objSitio.NUMERO_EMPLEADOS_CERTIFICACION + '<br>';
    strHtml += '        Nombre de Procesos: ' + objSitio.NOMBRE_PROCESOS + '<br>';
    strHtml += '        Cantidad de procesos: ' + objSitio.CANTIDAD_DE_PROCESOS + '<br>';
    strHtml += '        ¿Temporal o Fijo?: ' + objSitio.TEMPORAL_O_FIJO + '</td>';
    strHtml += '  <td>';
    if (global_permisos["SERVICIOS"]["editar"] == 1) {
      strHtml += '    <button type="button" class="btn btn-primary btn-xs btn-imnc btnEditarSitio" id_sitio_serv="'+id_sitio_serv+'" tipo_servicio="'+tipo_servicio+'" id_sitio="'+objSitio.ID+'" style="float: right;"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar sitio </button>';
    }
    strHtml += '  </td>';
    strHtml += '      </tr>';
    return strHtml;
  }
  function draw_sitios(id_sitio,tipo_servicio,objLabelAcordeon, colapsar ){
     $.getJSON( global_apiserver + "/sg_sitios/getBySgTipoServicio/?id_sg_tipo_servicio="+id_sitio, function( response ) {
          ////console.log(response);
          if (response.length > 0) {
            $("#thead-"+id_sitio+"-sitio").html(draw_head_row_sitios(tipo_servicio));
          }
          $("#tbody-"+id_sitio+"-sitio").html("");
          $.each(response, function( index, objSitio ) {
              $("#tbody-"+id_sitio+"-sitio").append(draw_row_sitios(objSitio, tipo_servicio, id_sitio));
              $("#tbody-"+id_sitio+"-sitio").append('<span class="sitio-domicilio-'+ id_sitio +'" hidden>' + objSitio.ID_CLIENTE_DOMICILIO + '</span>');
          });
          if(colapsar){
            $("#collapse-"+id_sitio+"-sitio").collapse("toggle");
            if (objLabelAcordeon.html() == "Mostrar") {objLabelAcordeon.html("Ocultar");}
            else{objLabelAcordeon.html("Mostrar");}
          }
          listener_btn_editar_sitio();
    });
  }
  function listener_btn_sitios(){
    $( ".btnSitios" ).click(function() {
      var id_sitio_serv = $(this).attr("id");
      var tipo_servicio = $(this).attr("tipo_servicio");
      var objLabelAcordeon = $(this).find(".labelAcordeon");
      $( "#btnGuardarSitio" ).attr("id_sitio_serv", id_sitio_serv);
      draw_sitios(id_sitio_serv,tipo_servicio,objLabelAcordeon, true);
    });
  }

  function redraw_sitios(){
    var id_sitio = $("#btnGuardarSitio").attr("id_sitio_serv");
    var tipo_servicio = $("#"+id_sitio).attr("tipo_servicio");
    var objLabelAcordeon = $("#"+id_sitio).find(".labelAcordeon");
    draw_sitios(id_sitio,tipo_servicio,objLabelAcordeon, false);
  }

  function listener_btn_editar_sitio(){
    $( ".btnEditarSitio" ).click(function() {
      $("#btnGuardarSitio").attr("accion","editar");
      $("#txtClaveSitio").attr("readonly","true");
      $("#txtClaveTServSitio").attr("readonly","true");
      $("#btnGuardarSitio").attr("id_sitio_serv", $(this).attr("id_sitio_serv"));
      $("#modalTituloSitios").html("Editar sitio");
      fill_modal_insertar_actualizar_sitios($(this).attr("id_sitio"), $(this).attr("tipo_servicio"));
      $("#modalInsertarActualizarSitios").modal("show");
    });
  }

  function listener_btn_nuevo_sitio(){
    $( ".btnInsertaSitio" ).click(function() {
      var tipo_servicio = $(this).attr("tipo_servicio");
      $("#btnGuardarSitio").attr("accion","insertar");
      $("#btnGuardarSitio").attr("id_sitio_serv",$(this).attr("id_serv"));
      $("#modalTituloSitios").html("Insertar sitio");
      $("#txtClaveSitio").attr("readonly","true");
      clear_modal_insertar_actualizar_sitios(tipo_servicio);
      $("#modalInsertarActualizarSitios").modal("show");
    });
  }

  function  fill_cmb_actividad(seleccionado){
    $.getJSON(  global_apiserver + "/sg_actividad/getAll/" , function( response ) {
      $("#txtActividad").html('<option value="" selected disabled>-elige una opción-</option>');
      $.each(response, function( indice, obj ) {
        $("#txtActividad").append('<option value="'+obj.ID+'">'+obj.ACTIVIDAD+'</option>'); 
      });
      $("#txtActividad").val(seleccionado);
      $("#txtActividad").prop('disabled', false);
      $("#newActivity").hide();
      $("#nuevaActividad").val("");
      $( "#chkActv" ).prop("checked", false);
    });
  }

  function actividad_checkbox(){
    $( "#chkActv" ).change(function() {
      if($(this).prop("checked")){
        $("#txtActividad").val("");
        $("#txtActividad").prop('disabled', true);
        $("#newActivity").show();
      }
      else{
        $("#txtActividad").prop('disabled', false);
        $("#nuevaActividad").val(""); 
        $("#newActivity").hide();
      }
    });
  }

  function insertar_actividad(sitio_callback){
    if($("#chkActv").prop("checked")){
      var actividad = {
          ACTIVIDAD : $("#nuevaActividad").val(),
          ID_USUARIO:sessionStorage.getItem("id_usuario")
      };
      $.post( global_apiserver + "/sg_actividad/insert/", JSON.stringify(actividad), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
          notify("Éxito", "Se ha insertado una nueva actividad", "success");
          $("#txtActividad").append('<option value="'+respuesta.ID+'">'+actividad.ACTIVIDAD+'</option>');
          $("#txtActividad").val(respuesta.ID);
          $("#txtActividad").prop('disabled', false);
          $("#newActivity").hide();
          $("#nuevaActividad").val("");
          $( "#chkActv" ).prop("checked", false);
          sitio_callback();
        }
        else{
          notify("Error", respuesta.mensaje, "error");
        }
      });
    }
    else{
      sitio_callback();
    }
  }

  function listener_btn_guardar_sitio(){
    actividad_checkbox();
    $( "#btnGuardarSitio" ).click(function() {
      if ($("#btnGuardarSitio").attr("accion") == "insertar")
      {
        insertar_actividad(insertar_sitio);
        //insertar_sitio();
      }
      else if ($("#btnGuardarSitio").attr("accion") == "editar")
      {
        insertar_actividad(editar_sitio);
        //editar_sitio();
      }
    });
  }

  function insertar_sitio(){
    var sitio = {
      ID_SG_TIPO_SERVICIO:$("#txtClaveTServSitio").val(),
      ID_CLIENTE_DOMICILIO:$("#cmbClaveClienteDomSitio").val(),
      CANTIDAD_PERSONAS:$("#txtCantPerso").val(),
      CANTIDAD_TURNOS:$("#txtCantTurn").val(),
      NUMERO_TOTAL_EMPLEADOS:$("#txtNoTotalEmplea").val(),
      NUMERO_EMPLEADOS_CERTIFICACION:$("#txtNoEmpleaCertif").val(),
      CANTIDAD_DE_PROCESOS:$("#txtCantProce").val(),
      NOMBRE_PROCESOS:$("#txtNombreProcesos").val(),
      TEMPORAL_O_FIJO:$("#cmbDuracion").val(),
      MATRIZ_PRINCIPAL:$("#cmbMatrizPrincipal").val(),
      ID_ACTIVIDAD : $("#txtActividad").val(),
      ID_USUARIO:sessionStorage.getItem("id_usuario")
    };
    $.post( global_apiserver + "/sg_sitios/insert/", JSON.stringify(sitio), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
          $("#modalInsertarActualizarSitios").modal("hide");
          notify("Éxito", "Se ha insertado un nuevo sitio", "success");
          //draw_tabla_servicios();
          redraw_sitios();
        }
        else{
          notify("Error", respuesta.mensaje, "error");
        }
    });
  }

  function editar_sitio(){
    var sitio = {
      ID:$("#txtClaveSitio").val(),
      ID_SG_TIPO_SERVICIO:$("#txtClaveTServSitio").val(),
      ID_CLIENTE_DOMICILIO:$("#cmbClaveClienteDomSitio").val(),
      CANTIDAD_PERSONAS:$("#txtCantPerso").val(),
      CANTIDAD_TURNOS:$("#txtCantTurn").val(),
      NUMERO_TOTAL_EMPLEADOS:$("#txtNoTotalEmplea").val(),
      NUMERO_EMPLEADOS_CERTIFICACION:$("#txtNoEmpleaCertif").val(),
      CANTIDAD_DE_PROCESOS:$("#txtCantProce").val(),
      NOMBRE_PROCESOS:$("#txtNombreProcesos").val(),
      TEMPORAL_O_FIJO:$("#cmbDuracion").val(),
      MATRIZ_PRINCIPAL:$("#cmbMatrizPrincipal").val(),
      ID_ACTIVIDAD : $("#txtActividad").val(),
      ID_USUARIO:sessionStorage.getItem("id_usuario")
    };
      $.post( global_apiserver + "/sg_sitios/update/", JSON.stringify(sitio), function(respuesta){
          respuesta = JSON.parse(respuesta);
          if (respuesta.resultado == "ok") {
             $("#modalInsertarActualizarSitios").modal("hide");
            notify("Éxito", "Se han actualizado los datos del sitio", "success");
            if(respuesta.dia_auditor == "ok")
              draw_tabla_servicios();
            else
              redraw_sitios();
          }
          else{
            notify("Error", respuesta.mensaje, "error");
          }
      });
  }
 
// ================================================================================
// *****                        Auditorías                                    *****
// ================================================================================

  function fill_cmb_tipo_auditoria(seleccionado){
    $.getJSON(  global_apiserver + "/sg_auditorias_tipos/getAll/", function( response ) {
      $("#cmbTipoAuditoria").html('<option value="elige" selected disabled>-elige una opción-</option>');
      $.each(response, function( indice, obj ) {
        $("#cmbTipoAuditoria").append('<option value="'+obj.ID+'">'+obj.TIPO+'</option>'); 
      });
      $("#cmbTipoAuditoria").val(seleccionado);
    });
  }

  function fill_cmb_status_auditoria(seleccionado){
    $.getJSON(  global_apiserver + "/sg_auditorias_status/getAll/", function( response ) {
      $("#cmbStatusAuditoria").html('<option value="elige" selected disabled>-elige una opción-</option>');
      $.each(response, function( indice, obj ) {
        $("#cmbStatusAuditoria").append('<option value="'+obj.ID+'">'+obj.STATUS+'</option>'); 
      });
      $("#cmbStatusAuditoria").val(seleccionado);
    });
  }
  function get_dias_auditoria(){
    var servicio = $("#btnGuardarAuditoria").attr("id_serv");
    $.getJSON(  global_apiserver + "/cotizaciones/getByServicio/?id="+global_id_servicio_cliente_et+"&servicio="+servicio, function( response ) {
      if(response.INICIAL == 1){
        $("#txtDuracionAuditoria").val("");
      }
      else{
        $("#txtDuracionAuditoria").val(response.DIAS);
      }
    });
  }
  
  function clear_modal_insertar_actualizar_auditoria(){
    $("#txtClaveAuditoria").val("");
    $("#txtClaveTipoServicioAuditoria").val($("#btnGuardarAuditoria").attr("id_serv"));
    $("#txtClaveAuditoria").attr("readonly","true");
    $("#txtClaveTipoServicioAuditoria").attr("readonly","true")
    //$("#txtFechaInicioAuditoria").val("");
    $("#txtDuracionAuditoria").val("");
    $("#txtSitiosAuditoria").val("");
    $("#chkNoMetodo").prop("checked", false);
    check_no_usa_metodo_auditoria(false);
    fill_cmb_tipo_auditoria("elige");
    fill_cmb_status_auditoria("elige");
  }

  function fill_modal_insertar_actualizar_auditoria(id_auditoria){
    $.getJSON(  global_apiserver + "/sg_auditorias/getById/?id="+id_auditoria, function( response ) {
      // var fecha = response.FECHA_INICIO;
      // fecha = fecha.substring(6,8)+"/"+fecha.substring(4,6)+"/"+fecha.substring(0,4);
      $("#txtClaveAuditoria").val(response.ID);
      $("#txtClaveTipoServicioAuditoria").val(response.ID_SG_TIPO_SERVICIO);
      $("#txtClaveAuditoria").attr("readonly","true");
      $("#txtClaveTipoServicioAuditoria").attr("readonly","true");
      //$("#txtFechaInicioAuditoria").val(fecha);
      $("#txtDuracionAuditoria").val(response.DURACION_DIAS);
      $("#txtSitiosAuditoria").val(response.SITIOS_AUDITAR);
      $("#chkNoMetodo").prop("checked",response.NO_USA_METODO == 1? true : false);
      check_no_usa_metodo_auditoria(response.NO_USA_METODO == 1? true : false);
      fill_cmb_tipo_auditoria(response.TIPO_AUDITORIA);
      fill_cmb_status_auditoria(response.STATUS_AUDITORIA);
    });
  }

  function set_check_no_usa_metodo(){
     $("#chkNoMetodo").change(function(){
        $("#txtSitiosAuditoria").val("");
        check_no_usa_metodo_auditoria($(this).prop("checked"));
     });
  }

  function check_no_usa_metodo_auditoria(value){
    if(value){
      $("#divSitiosAuditoria").show();
    }
    else{
      $("#divSitiosAuditoria").hide();
    }
  }

  function draw_head_row_auditorias(tipo_servicio){
    var strHtml = "";
    strHtml += '        <tr>';
    strHtml += '         <th>ID</th>';
    strHtml += '         <th>Fechas</th>';
    strHtml += '         <th>Días '+global_str_personal_tecnico_singular.toLowerCase()+'</th>';
    strHtml += '         <th>Tipo y status de '+global_str_auditoria.toLowerCase()+'</th>';
    strHtml += '         <th>Sitios de '+global_str_auditoria.toLowerCase()+'</th>';
    strHtml += '         <th>Grupo de '+global_str_personal_tecnico.toLowerCase()+'</th>';
    strHtml += '         <th></th>';
    strHtml += '         <th></th>';
    strHtml += '        </tr>';
    return strHtml;
  }

  function draw_row_auditorias(objAuditoria, tipo_servicio){
    // var fecha = objAuditoria.FECHA_INICIO;
    // fecha = fecha.substring(6,8)+"/"+fecha.substring(4,6)+"/"+fecha.substring(0,4);
    var restricciones_sitios = "";
    for (var i = objAuditoria.RESTRICCIONES_SITIOS.length - 1; i >= 0; i--) {
      restricciones_sitios += "<br> *" + objAuditoria.RESTRICCIONES_SITIOS[i];
    }
    var restricciones_grupos = "";
    for (var i = objAuditoria.RESTRICCIONES_GRUPOS.length - 1; i >= 0; i--) {
      restricciones_grupos += "<br> *" + objAuditoria.RESTRICCIONES_GRUPOS[i];
    }
    var strHtml = "";
    strHtml += '      <tr>';
    strHtml += '        <td>' + objAuditoria.ID + '</td>';
    strHtml += '        <td> <input type="text" class="txtFechasAuditoria" id="txtFechasAuditoria-'+ objAuditoria.ID +'" placeholder="Selecciona las fechas" id_auditoria="'+objAuditoria.ID+'" readonly>'; 
    strHtml += '        <span id="labelFechasAuditoria-'+ objAuditoria.ID +'"></span>';
    strHtml += '        </td>';
    strHtml += '        <td>' + objAuditoria.DURACION_DIAS + '</td>';
    strHtml += '        <td>Tipo: ' + objAuditoria.TIPO_AUDITORIA_NOMBRE + '<br>';
    strHtml += '            Status: ' + objAuditoria.STATUS_AUDITORIA_NOMBRE + '</td>';
    strHtml += '        <td><button class="btn btn-success btn-xs btnSitiosAuditoria" tipo_servicio="'+tipo_servicio+'" id_auditoria="'+objAuditoria.ID+'">'+objAuditoria.SITIOS_ASOCIADOS+' sitios</button>'+restricciones_sitios+'</td>';
    strHtml += '        <td><button class="btn btn-success btn-xs btnGrupoAuditoria" id_auditoria="'+objAuditoria.ID+'">'+objAuditoria.AUDITORES_ASOCIADOS+' '+global_str_personal_tecnico.toLowerCase()+'</button>'+restricciones_grupos+'</td>';
    strHtml += '        <td>';
    if (global_permisos["SERVICIOS"]["editar"] == 1) {
      strHtml += '          <button type="button" class="btn btn-primary btn-xs btn-imnc btnEditarAuditoria" id_auditoria="'+objAuditoria.ID+'" style="float: right;"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar '+global_str_auditorias.toLowerCase()+' </button></td>';      
    }
    // strHtml += '        <td><a type="button" target="_blank" href="./generar/pdf/notificacion_servicio/?id='+objAuditoria.ID + '" class="btn btn-primary btn-xs btn-imnc btnDescargaNotificacion" id_auditoria="'+objAuditoria.ID+'" style="float: right;"> <i class="fa fa-download" aria-hidden="true"></i> Notificación </a>';
    strHtml += '        <td><button type="button" target="_blank" class="btn btn-primary btn-xs btn-imnc btnNotificacionPDF" id_auditoria="'+objAuditoria.ID+'" style="float: right;"> <i class="fa fa-download" aria-hidden="true"></i> Notificación </button>';
    strHtml += '        </td>';
    strHtml += '      </tr>';
    strHtml += '<tr class="collapse out tercernivel" id="collapse-'+objAuditoria.ID+'-sitios-auditoria">';
    strHtml += '  <td colspan="13">';
    strHtml += '    <table class="table tercernivel">';
    strHtml += '      <caption>Sitios de '+global_str_auditoria.toLowerCase();
    if (global_permisos["SERVICIOS"]["registrar"] == 1) {
      strHtml += '         <button type="button" class="btn btn-success btn-xs btnInsertaSitiosAuditoria" tipo_servicio="'+tipo_servicio+'"  id_auditoria="'+objAuditoria.ID+'" id_serv="'+objAuditoria.ID_SG_TIPO_SERVICIO+'" style="float: right;"> <i class="fa fa-plus"></i> Agregar sitio de ' + global_str_auditorias.toLowerCase() + ' </button>'; 
    }
    strHtml += '       </caption>';
    strHtml += '      <thead id="thead-'+objAuditoria.ID+'-sitios-auditoria">';
    strHtml += '      </thead>';
    strHtml += '      <tbody id="tbody-'+objAuditoria.ID+'-sitios-auditoria">';
    strHtml += '      </tbody>';
    strHtml += '    </table>';
    strHtml += '  </td>';
    strHtml += '</tr>';
    strHtml += '<tr class="collapse out tercernivel" id="collapse-'+objAuditoria.ID+'-grupo-auditoria">';
    strHtml += '  <td colspan="13">';
    strHtml += '    <table class="table tercernivel">';
    strHtml += '      <caption>Grupo de '+global_str_personal_tecnico.toLowerCase();
    if (global_permisos["SERVICIOS"]["registrar"] == 1 ) {
      strHtml += '       <button type="button" class="btn btn-success btn-xs btnInsertaGrupoAuditoria" id_auditoria="'+objAuditoria.ID+'" id_serv="'+objAuditoria.ID_SG_TIPO_SERVICIO+'" style="float: right;"> <i class="fa fa-plus"></i> Agregar '+ global_str_personal_tecnico_singular.toLowerCase() + ' a grupo </button>';
    }
    strHtml += '     </caption>';
    strHtml += '      <thead id="thead-'+objAuditoria.ID+'-grupo-auditoria">';
    strHtml += '      </thead>';
    strHtml += '      <tbody id="tbody-'+objAuditoria.ID+'-grupo-auditoria">';
    strHtml += '      </tbody>';
    strHtml += '    </table>';
    strHtml += '  </td>';
    strHtml += '</tr>';
    return strHtml;
  }

  function abrir_auditorias(id_sg_tipo_servicio, objLabelAcordeon, tipo_servicio, colapsar){
     $.getJSON( global_apiserver + "/sg_auditorias/getBySgTipoServicio/?id_sg_tipo_servicio="+id_sg_tipo_servicio, function( response ) {
        ////console.log(response);
        if (response.length > 0) {
          $("#thead-"+id_sg_tipo_servicio+"-auditoria").html(draw_head_row_auditorias());
        }
        $("#tbody-"+id_sg_tipo_servicio+"-auditoria").html("");
        $.each(response, function( index, objauditoria ) {
            $("#tbody-"+id_sg_tipo_servicio+"-auditoria").append(draw_row_auditorias(objauditoria, tipo_servicio));
            listener_btn_modal_notificacion_pdf();    
            var _id_auditoria = objauditoria.ID;
            $.getJSON( global_apiserver + "/sg_auditoria_fechas/getBySgAuditoria/?id_sg_auditoria="+_id_auditoria, function( response ) {
                if (response.FECHAS_MULTIDATEPICKER.length > 0) {
                  $("#txtFechasAuditoria-" + _id_auditoria).multiDatesPicker(
                    {
                      addDates: response.FECHAS_MULTIDATEPICKER,
                      onSelect: function(dateText) {
                        editar_fechas_auditoria(this.value, $(this).attr("id_auditoria"));
                      }
                    }
                  );
                  $("#labelFechasAuditoria-" + _id_auditoria).html("");
                }
                else { //Si no hay fechas
                  $("#txtFechasAuditoria-" + _id_auditoria).multiDatesPicker(
                    {
                      onSelect: function(dateText) {
                        editar_fechas_auditoria(this.value, $(this).attr("id_auditoria"));
                      }
                    }
                  );
                  $("#labelFechasAuditoria-" + _id_auditoria).html("<p style='font-size: 10px; color: #dc0d0d;'>Atención: Es necesario agregar fechas</p>");
                }                
            })            
        });
        if(colapsar){
          $("#collapse-"+id_sg_tipo_servicio+"-auditoria").collapse("toggle");
          if (objLabelAcordeon.html() == "Mostrar") {objLabelAcordeon.html("Ocultar");}
          else{objLabelAcordeon.html("Mostrar");}
        }
        listener_btn_editar_auditoria();
        listener_btn_sitios_auditoria();
        listener_btn_grupo_auditoria();
        listener_btn_explorar_sitios();
        listener_btn_explorar_grupo();
        listener_btn_nuevo_sitio_auditoria();
        listener_btn_nuevo_grupo_auditoria();
     });
  }
  
  function redraw_auditorias(){
    var id_sg_tipo_servicio = $("#btnGuardarAuditoria").attr("id-auditoria");
    var tipo_servicio = $("#"+id_sg_tipo_servicio).attr("tipo_servicio");
    var objLabelAcordeon = $("#"+id_sg_tipo_servicio).find(".labelAcordeon");
    abrir_auditorias(id_sg_tipo_servicio, objLabelAcordeon, tipo_servicio, false);
  }

  function fill_modal_modal_notificacion_pdf(id_auditoria){
    $("#inputIdAuditoria").val(id_auditoria);
    $("#inputNombreUsuario").val(sessionStorage.getItem("nombre_usuario"));

    
    $.getJSON( global_apiserver + "/sg_auditoria_notificacion/getBySgAuditoria/?id_sg_auditoria="+id_auditoria, function( response ) {
      ////console.log(response);
      if (!response) { // No existe notificacion
        $("select[name=cmbTipoNotificacionPDF] :nth-child(1)").prop('selected', true);
        $("select[name=cmbTipoCambiosPDF] :nth-child(1)").prop('selected', true);
        $("select[name=cmbCertificacionMantenimientoPDF] :nth-child(1)").prop('selected', true);
        $("textarea[name=txtNota1PDF]").val("");
        $("textarea[name=txtNota2PDF]").val("");
        $("textarea[name=txtNota3PDF]").val("");
        $("input[name=txtNombreAutorizaPDF]").val("");
        $("input[name=txtCargoAutorizaPDF]").val("");
		get_domicilio_cliente(global_id_servicio_cliente_et,0);
        $("#btnGenerarNotificacionPDF").attr("accion", "insertar");
		
      }
      else {
        console.log(response);
        $("select[name=cmbTipoNotificacionPDF]").val(response.TIPO_NOTIFICACION);
        $("select[name=cmbTipoCambiosPDF]").val(response.TIPO_CAMBIOS);
        $("select[name=cmbCertificacionMantenimientoPDF]").val(response.CERTIFICACION_MANTENIMIENTO);
        $("textarea[name=txtNota1PDF]").val(response.NOTA1);
        $("textarea[name=txtNota2PDF]").val(response.NOTA2);
        $("textarea[name=txtNota3PDF]").val(response.NOTA3);
        $("input[name=txtNombreAutorizaPDF]").val(response.QUIEN_AUTORIZA);
        $("input[name=txtCargoAutorizaPDF]").val(response.CARGO_AUTORIZA);
		get_domicilio_cliente(global_id_servicio_cliente_et,response.ID_DOMICILIO);

        $("#btnGenerarNotificacionPDF").attr("accion", "editar");
      }
     
    });
  }

  function listener_btn_modal_notificacion_pdf(){
    try {
       $( ".btnNotificacionPDF" ).unbind( "click");
    } catch(err) {}
   
    $( ".btnNotificacionPDF" ).click(function() {
      var id_auditoria = $(this).attr("id_auditoria");
      
      fill_modal_modal_notificacion_pdf(id_auditoria);
      $("#modalGeneraNotificacion").modal("show");
    });
  }
  
  function get_domicilio_cliente(id_cliente,seleccionado){
	$.getJSON(  global_apiserver + "/servicio_cliente_etapa/getDomicilioByIDSCE/?id="+id_cliente, function( response ) {
	$("#cmbDomicilioNotificacionPDF").html('<option value="elige" selected disabled>-elige una opción-</option>');

	$.each(response, function( indice, objDom ) {
        $("#cmbDomicilioNotificacionPDF").append('<option value="'+objDom.ID_DOMICILIO+'">'+objDom.NOMBRE_DOMICILIO+'</option>'); 
      });
	  if(seleccionado != 0){
      $("#cmbDomicilioNotificacionPDF").val(seleccionado);
	  }
	});
  }

    function listener_btn_generar_notificacion_pdf(){
    $('#btnGenerarNotificacionPDF').click(function(e) {
      if ($("#btnGenerarNotificacionPDF").attr("accion") == "insertar")
      {
        insertar_auditoria_notificacion();
      }
      else if ($("#btnGenerarNotificacionPDF").attr("accion") == "editar")
      {
        editar_auditoria_notificacion();
      }
      window.open('', 'VentanaNotificacionPDF');
      document.getElementById('formGeneraNotificacionPDF').submit();
    });
  }

  function insertar_auditoria_notificacion(){
    var sg_auditoria_notificacion = {
      ID_SG_AUDITORIA:$("#inputIdAuditoria").val(),
      TIPO_NOTIFICACION:$("select[name=cmbTipoNotificacionPDF]").val(),
	  ID_DOMICILIO:$("select[name=cmbDomicilioNotificacionPDF]").val(),
      TIPO_CAMBIOS:$("select[name=cmbTipoCambiosPDF]").val(),
      CERTIFICACION_MANTENIMIENTO:$("select[name=cmbCertificacionMantenimientoPDF]").val(),
      NOTA1:$("textarea[name=txtNota1PDF]").val(),
      NOTA2:$("textarea[name=txtNota2PDF]").val(),
      NOTA3:$("textarea[name=txtNota3PDF]").val(),
      QUIEN_AUTORIZA:$("input[name=txtNombreAutorizaPDF]").val(),
      CARGO_AUTORIZA:$("input[name=txtCargoAutorizaPDF]").val(),
    };
    $.post( global_apiserver + "/sg_auditoria_notificacion/insert/", JSON.stringify(sg_auditoria_notificacion), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
          //notify("Éxito", "Se ha insertado la auditoría", "success");
        }
        else{
          notify("Error", respuesta.mensaje, "error");
        }
    });
  }

  function editar_auditoria_notificacion(){
    var sg_auditoria_notificacion = {
      ID_SG_AUDITORIA:$("#inputIdAuditoria").val(),
	  ID_DOMICILIO:$("select[name=cmbDomicilioNotificacionPDF]").val(),
      TIPO_NOTIFICACION:$("select[name=cmbTipoNotificacionPDF]").val(),
      TIPO_CAMBIOS:$("select[name=cmbTipoCambiosPDF]").val(),
      CERTIFICACION_MANTENIMIENTO:$("select[name=cmbCertificacionMantenimientoPDF]").val(),
      NOTA1:$("textarea[name=txtNota1PDF]").val(),
      NOTA2:$("textarea[name=txtNota2PDF]").val(),
      NOTA3:$("textarea[name=txtNota3PDF]").val(),
      QUIEN_AUTORIZA:$("input[name=txtNombreAutorizaPDF]").val(),
      CARGO_AUTORIZA:$("input[name=txtCargoAutorizaPDF]").val(),
    };
    $.post( global_apiserver + "/sg_auditoria_notificacion/update/", JSON.stringify(sg_auditoria_notificacion), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
          //notify("Éxito", "Se ha insertado la auditoría", "success");
        }
        else{
          notify("Error", respuesta.mensaje, "error");
        }
    });
  }

  function listener_btn_auditoria(){
    $( ".btnAuditorias" ).click(function() {
      var id_sg_tipo_servicio = $(this).attr("id");
      var tipo_servicio = $(this).attr("tipo_servicio");
      var objLabelAcordeon = $(this).find(".labelAcordeon");
      $( "#btnGuardarAuditoria" ).attr("id-auditoria", id_sg_tipo_servicio);
      abrir_auditorias(id_sg_tipo_servicio, objLabelAcordeon, tipo_servicio, true);
    });
  }
 
  function listener_btn_editar_auditoria(){
    $( ".btnEditarAuditoria" ).click(function() {
      $("#btnGuardarAuditoria").attr("accion","editar");
      $("#btnGuardarAuditoria").attr("id_auditoria",$(this).attr("id_auditoria"));
      $("#modalTituloAuditoria").html("Editar " + global_str_auditorias.toLowerCase());
      fill_modal_insertar_actualizar_auditoria($(this).attr("id_auditoria"));
      $("#modalInsertarActualizarAuditoria").modal("show");
    });
  }
  
  function listener_btn_nueva_auditoria(){
    $( ".btnInsertaAuditoria" ).click(function() {
      //console.log($(this).attr("id_serv"));
      $("#btnGuardarAuditoria").attr("accion","insertar");
      $("#btnGuardarAuditoria").attr("id_serv",$(this).attr("id_serv"));
      $("#modalTituloAuditoria").html("Insertar " + global_str_auditorias.toLowerCase());
      $("#txtClaveAuditoria").attr("readonly","true");
      clear_modal_insertar_actualizar_auditoria();
      get_dias_auditoria();
      $("#modalInsertarActualizarAuditoria").modal("show");
    });
  }

  function listener_btn_guardar_auditoria(){
    set_check_no_usa_metodo();
    $( "#btnGuardarAuditoria" ).click(function() {
      if ($("#btnGuardarAuditoria").attr("accion") == "insertar")
      {
        insertar_auditoria();
      }
      else if ($("#btnGuardarAuditoria").attr("accion") == "editar")
      {
        editar_auditoria();
      }
    });
  }

  function insertar_auditoria(){
    // var fecha = $("#txtFechaInicioAuditoria").val();
    // fecha = fecha.substring(6,10)+fecha.substring(3,5)+fecha.substring(0,2);
    
    var auditoria = {
      ID_SG_TIPO_SERVICIO:$("#txtClaveTipoServicioAuditoria").val(),
      // FECHA_INICIO:fecha,
      DURACION_DIAS:$("#txtDuracionAuditoria").val(),
      TIPO_AUDITORIA:$("#cmbTipoAuditoria").val(),
      STATUS_AUDITORIA:$("#cmbStatusAuditoria").val(),
      ID_USUARIO:sessionStorage.getItem("id_usuario"),
      NO_USA_METODO : $("#chkNoMetodo").prop("checked"),
      SITIOS_AUDITAR :  $("#txtSitiosAuditoria").val()
    };
    $.post( global_apiserver + "/sg_auditorias/insert/", JSON.stringify(auditoria), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
          $("#modalInsertarActualizarAuditoria").modal("hide");
          notify("Éxito", "Se ha insertado la auditoría", "success");
          //draw_tabla_servicios();
          redraw_auditorias();
        }
        else{
          notify("Error", respuesta.mensaje, "error");
        }
    });
  }

  function editar_auditoria(){
    // var fecha = $("#txtFechaInicioAuditoria").val();
    // fecha = fecha.substring(6,10)+fecha.substring(3,5)+fecha.substring(0,2);

    var auditoria = {
      ID:$("#txtClaveAuditoria").val(),
      ID_SG_TIPO_SERVICIO:$("#txtClaveTipoServicioAuditoria").val(),
      // FECHA_INICIO:fecha,
      DURACION_DIAS:$("#txtDuracionAuditoria").val(),
      TIPO_AUDITORIA:$("#cmbTipoAuditoria").val(),
      STATUS_AUDITORIA:$("#cmbStatusAuditoria").val(),
      ID_USUARIO:sessionStorage.getItem("id_usuario"),
      NO_USA_METODO : $("#chkNoMetodo").prop("checked"),
      SITIOS_AUDITAR :  $("#txtSitiosAuditoria").val()
    };
    $.post( global_apiserver + "/sg_auditorias/update/", JSON.stringify(auditoria), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
           $("#modalInsertarActualizarAuditoria").modal("hide");
          notify("Éxito", "Se han actualizado los datos de la auditoría", "success");
          //draw_tabla_servicios();
          redraw_auditorias();
        }
        else{
          notify("Error", respuesta.mensaje, "error");
        }
    });
  }

  function editar_fechas_auditoria(fechas, id_auditoria){
    console.log(fechas);
    console.log(id_auditoria);
  
    var auditoria = {
      ID_SG_AUDITORIA:id_auditoria,
      FECHAS:fechas,
      ID_USUARIO:sessionStorage.getItem("id_usuario"),
    };

    $.post( global_apiserver + "/sg_auditoria_fechas/updateBySgAuditoria/", JSON.stringify(auditoria), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
          if ($("#txtFechasAuditoria-"+ id_auditoria).val() == "") {
            $("#labelFechasAuditoria-" + id_auditoria).html("<p style='font-size: 10px; color: #dc0d0d;'>Atención: Es necesario agregar fechas</p>");
          }
          else{
            $("#labelFechasAuditoria-" + id_auditoria).html("");
          }
          //notify("Éxito", "Se han actualizado las fechas de auditoría", "success");
          //redraw_auditorias();
        }
        else{
          notify("Error", respuesta.mensaje, "error");
        }
        $("#txtFechasAuditoria-" + id_auditoria).blur();
    });
  }

// ================================================================================
// *****                       Grupo de auditores                             *****
// ================================================================================

  function fill_calendario_auditor(id_sg_auditoria) {
    $.getJSON(  global_apiserver + "/sg_auditoria_fechas/getBySgAuditoria/?id_sg_auditoria="+id_sg_auditoria, function( response ) {
      var arreglo_fechas = response.FECHAS_MULTIDATEPICKER; // Formato dd/mm/yyyy
      $("#txtFechasGrupoAuditor").multiDatesPicker('destroy');
      if (arreglo_fechas.length <= 0) {
        alerta("AVISO", "No se han seleccionado fechas para la auditoría, no podrá insertar auditores", "warning");
        return;
      }
      
      $("#txtFechasGrupoAuditor").multiDatesPicker(
      {
        defaultDate: arreglo_fechas[0],
        onSelect: function(dateText) {
          $("#txtFechasGrupoAuditor").blur();
        },
        beforeShowDay: function (date) {
          console.log(arreglo_fechas);
          console.log(date.ddmmyyy());
          if (arreglo_fechas.indexOf(date.ddmmyyy()) > -1) {
            console.log(date);
            return [true, ''];
          }
          else {
            return [false, ''];
          }
        }
      });
    });
  }

  function fill_cmb_roles(seleccionado){
    $.getJSON(  global_apiserver + "/personal_tecnico_roles/getAll/", function( response ) {
      $("#cmbRol").html('<option value="elige" selected disabled>-elige una opción-</option>');
      $.each(response, function( indice, objRol ) {
        $("#cmbRol").append('<option value="'+objRol.ID+'">'+objRol.ROL+'</option>'); 
      });
      $("#cmbRol").val(seleccionado);
    });
  }
  function fill_cmb_roles_jerarquia(jerarquia){
    $.getJSON(  global_apiserver + "/personal_tecnico_roles/getByJerarquia/?id="+jerarquia, function( response ) {
      $("#cmbRol").html('<option value="elige" selected disabled>-elige una opción-</option>');
      $.each(response, function( indice, objRol ) {
        $("#cmbRol").append('<option value="'+objRol.ID+'">'+objRol.ROL+'</option>'); 
      });
      $("#cmbRol").val(seleccionado);
    });
  }

  function clear_modal_insertar_actualizar_grupo_auditoria(){
    $("#txtClave-Grupo").val("");
    $("#txtClaveAuditoria-Grupo").val($("#btnGuardarGrupoAuditoria").attr("id_auditoria"));
    $("#txtClavePTCalif-Grupo").val("");
    $("#txtClavePTCalif-Grupo").attr("id_calif");
    $("#txtClaveAuditoria-Grupo").attr("readonly","true");
    $("#txtClavePTCalif-Grupo").attr("readonly","true");
    $("#txtClave-Grupo").attr("readonly","true");

    fill_cmb_roles("elige");
    //fill_cmb_roles_jerarquia(1);
  }

  function draw_head_row_grupo_auditoria(){
    var strHtml = "";
    strHtml += '        <tr>';
    strHtml += '         <th>Nombre completo</th>';
    strHtml += '         <th>Email</th>';
    strHtml += '         <th>Registro</th>';
    strHtml += '         <th>Rol en auditoría</th>';
    strHtml += '         <th>Tipo de servicio</th>';
    strHtml += '         <th>Fechas asignadas</th>';
    strHtml += '         <th></th>';
    strHtml += '        </tr>';
    return strHtml;
  }

  function draw_row_grupo_auditoria(objPT, id_sg_grupo_aditoria){
      var nombre_completo = objPT.PERSONAL_TECNICO.NOMBRE + " " + objPT.PERSONAL_TECNICO.APELLIDO_PATERNO + " " + objPT.PERSONAL_TECNICO.APELLIDO_MATERNO;
      var fecha_asignadas = "";
      for (var i = 0; i < objPT.FECHAS_ASIGNADAS.length; i++) {
        fecha_asignadas += objPT.FECHAS_ASIGNADAS[i].substring(6,8)+"/"+objPT.FECHAS_ASIGNADAS[i].substring(4,6)+"/"+objPT.FECHAS_ASIGNADAS[i].substring(0,4)+"<br>";
      }

      var strHtml = '';
      strHtml += '      <tr>';
      strHtml += '        <td>' + nombre_completo + '</td>';
      strHtml += '        <td>' + objPT.PERSONAL_TECNICO.EMAIL + '</td>';
      strHtml += '        <td>' + objPT.PERSONAL_TECNICO_CALIFICACIONES.REGISTRO + '</td>';
      strHtml += '        <td>' + objPT.ID_ROL + '</td>';
      strHtml += '        <td>' + objPT.PERSONAL_TECNICO_CALIFICACIONES.ID_TIPO_SERVICIO + '</td>';
      strHtml += '        <td>' + fecha_asignadas + '</td>';
      strHtml += '        <td>';
      if (global_permisos["SERVICIOS"]["editar"] == 1) {
        strHtml += '          <button class="btn btn-primary btn-xs btnEliminaGrupoAuditoria" id_sg_grupo_aditoria="'+id_sg_grupo_aditoria+'"><i class="fa fa-trash" aria-hidden="true"></i></button>';
      }
      strHtml += '        </td>';
      strHtml += '      </tr>';
      return strHtml;
  }

  function draw_head_row_modal_grupo_auditoria(){
    var strHtml = "";
    strHtml += '        <tr>';
    strHtml += '         <th>Datos del '+global_str_personal_tecnico_singular.toLowerCase();+'</th>';
    strHtml += '         <th>Sectores que cubre</th>';
    strHtml += '         <th></th>';
    strHtml += '        </tr>';
    return strHtml;
  }

  function draw_row_modal_grupo_auditoria(objAuditoriaGrupo, calificaciones){
    var strHtml = "";
    strHtml += '      <tr>';
    strHtml += '        <td style="font-size: 12px;">';
    strHtml += '        ' + objAuditoriaGrupo.NOMBRE_COMPLETO + '<br>';
    strHtml += '        ' + objAuditoriaGrupo.REGISTRO + '<br>';
    strHtml += '        ' + objAuditoriaGrupo.STATUS + '<br>';
    strHtml += '        ' + objAuditoriaGrupo.ID_TIPO_SERVICIO + '</td>';
    strHtml += '        ' + objAuditoriaGrupo.JERARQUIA + '</td>';
    strHtml += '        ' + objAuditoriaGrupo.JERARQUIA + '</td>';
    
    if (calificaciones != null) {
      strHtml += '        <td style="font-size: 11px;">' + objAuditoriaGrupo.TOTAL + '<br>'; 
      for (var i = 0; i < calificaciones.length; i++) {
        strHtml += calificaciones[i].ID_SECTOR + ' - ' + calificaciones[i].NOMBRE_SECTOR + ' (' + calificaciones[i].ROL + ') Sector NACE: '+calificaciones[i].SECTOR_NACE+'<br>';  
      }
      strHtml += '</td>';
    }
    else{
      strHtml += '        <td style="font-size: 12px;">' + objAuditoriaGrupo.TOTAL + '<br>'; 
      strHtml += '        (' + objAuditoriaGrupo.ROL + ')</td>';  
    }
    strHtml += '        <td></td>';
    strHtml += '  <td>'
    if (objAuditoriaGrupo.EN_GRUPO) {
      strHtml += '    <button type="button" class="btn btn-default btn-xs" style="float: right;" disabled> en auditoria </button>';
    }
    else if (objAuditoriaGrupo.STATUS != "activo") {
      strHtml += '    <button type="button" class="btn btn-default btn-xs" style="float: right;" disabled> '+objAuditoriaGrupo.STATUS+' </button>';
    }
    else{
      strHtml += '    <button type="button" class="btn btn-primary btn-xs btn-imnc btnSeleccionarGrupo" id_pt_calif="'+objAuditoriaGrupo.PT_CALIF_ID+'" nombre_auditor="'+objAuditoriaGrupo.NOMBRE_COMPLETO+'" style="float: right;"> seleccionar </button>';
    }
    strHtml += '  </td>';
    strHtml += '      </tr>';
    return strHtml;
  }

  function listener_btn_grupo_auditoria(){
    $( ".btnGrupoAuditoria" ).click(function() {
      var id_auditoria = $(this).attr("id_auditoria");
      $( "#btnGuardarGrupoAuditoria" ).attr("id_auditoria",id_auditoria);
      draw_grupo_auditoria(id_auditoria, true);
    });
  }

  function listener_btn_nuevo_grupo_auditoria(){
    $( ".btnInsertaGrupoAuditoria" ).click(function() {
      var _id_sg_auditoria = $(this).attr("id_auditoria");
      $("#btnGuardarGrupoAuditoria").attr("accion","insertar");
      $("#btnGuardarGrupoAuditoria").attr("id_auditoria",_id_sg_auditoria);
      $("#btnExplorarGrupo").attr("id_serv",$(this).attr("id_serv"));
      $("#btnExplorarGrupo").attr("id_auditoria",_id_sg_auditoria);
      $("#modalTituloGrupoAuditoria").html("Insertar  "+global_str_personal_tecnico_singular+" a grupo");
      $("#txtClaveAuditoria-Grupo").attr("readonly","true");
      $("#txtClavePTCalif-Grupo").attr("readonly","true");
      clear_modal_insertar_actualizar_grupo_auditoria();
      fill_calendario_auditor(_id_sg_auditoria);
      $("#modalInsertarActualizarGrupoAuditoria").modal("show");
    });
  }
  function draw_grupo_auditoria(id_auditoria, colapsar){
    $.getJSON( global_apiserver + "/sg_auditoria_grupos/getBySgAuditoria/?id_sg_auditoria="+id_auditoria, function( response ) {
        //console.log(response);
        if (response.length > 0) {
          $("#thead-"+id_auditoria+"-grupo-auditoria").html(draw_head_row_grupo_auditoria()); 
        }
        $("#tbody-"+id_auditoria+"-grupo-auditoria").html("");
        $.each(response, function( index, objAuditoriaGrupo ) {
          $("#tbody-"+id_auditoria+"-grupo-auditoria").append(draw_row_grupo_auditoria(objAuditoriaGrupo, objAuditoriaGrupo.ID));
        });
        if(colapsar){
          $("#collapse-"+id_auditoria+"-grupo-auditoria").collapse("toggle");
        }
        else{
          $("#collapse-"+id_auditoria+"-grupo-auditoria").collapse("show");
        }
        listener_btn_elimina_grupo_auditoria(); //
    });
  }

  function redraw_grupo_auditoria(){
    redraw_auditorias();
    var id_auditoria = $("#btnGuardarGrupoAuditoria").attr("id_auditoria");
    draw_grupo_auditoria(id_auditoria, false);
  }


  function listener_btn_explorar_grupo(){
    $( "#btnExplorarGrupo" ).click(function() {
      var id_sg_tipo_servicio = $(this).attr("id_serv");
      var id_auditoria = $(this).attr("id_auditoria");
       $.getJSON( global_apiserver + "/personal_tecnico/getAllWithSectorCalif/?id_sg_tipo_servicio="+id_sg_tipo_servicio+"&id_sg_auditoria="+id_auditoria, function( response ) {
          //console.log(response);
          if ($.isArray(response.CON_CALIFICACION)) {
            $("#thead-modal-explora-grupo").html(draw_head_row_modal_grupo_auditoria());
            $("#tbody-modal-explora-grupo").html("");
            $.each(response.CON_CALIFICACION, function( indice, objAuditoriaGrupo ) {
              $("#tbody-modal-explora-grupo").append(draw_row_modal_grupo_auditoria(objAuditoriaGrupo, objAuditoriaGrupo.CALIFICACIONES)); 
            });
            $("#modalInsertarActualizarGrupoAuditoria").modal("hide");
            $("#modalExplorarGrupo").modal("show");
            $.each(response.SIN_CALIFICACION, function( indice, objAuditoriaGrupo ) {
              $("#tbody-modal-explora-grupo").append(draw_row_modal_grupo_auditoria(objAuditoriaGrupo, null)); 
            });

          }
          else{
            notify("Error", response.mensaje, "error");
          }
          listener_btn_seleccionar_grupo();
       });
    });
  }

  function listener_btn_elimina_grupo_auditoria(){
    $( ".btnEliminaGrupoAuditoria" ).click(function() {
      var id_sg_grupo_aditoria = $(this).attr("id_sg_grupo_aditoria");
       $.getJSON( global_apiserver + "/sg_auditoria_grupos/delete/?id="+id_sg_grupo_aditoria, function( response ) {
          if (response.resultado == "ok") {
            notify("Éxito", "Se ha eliminado un auditor del grupo", "success");
            redraw_grupo_auditoria();
          }
          else{
            notify("Error", response.mensaje, "error");
          }
       });
    });
  }

  function listener_btn_seleccionar_grupo(){
    $( ".btnSeleccionarGrupo" ).click(function() {
      var id_pt_calif = $(this).attr("id_pt_calif");
      var nombre_auditor = $(this).attr("nombre_auditor");
       $("#txtClavePTCalif-Grupo").attr("id_calif",id_pt_calif);
      $("#txtClavePTCalif-Grupo").val(nombre_auditor);
      $("#modalExplorarGrupo").modal("hide");
      $("#modalInsertarActualizarGrupoAuditoria").modal("show");
    });
  }

  function listener_btn_guardar_grupo_auditoria(){
    $( "#btnGuardarGrupoAuditoria" ).click(function() {
      if ($("#btnGuardarGrupoAuditoria").attr("accion") == "insertar")
      {
        insertar_grupo_auditoria();
      }
      else if ($("#btnGuardarGrupoAuditoria").attr("accion") == "editar")
      {
        //editar_grupo_auditoria();
      }
    });
  }

  function insertar_grupo_auditoria(){
   
    var grupo = {
      ID_SG_AUDITORIA:$("#txtClaveAuditoria-Grupo").val(),
      ID_PERSONAL_TECNICO_CALIF:$("#txtClavePTCalif-Grupo").attr("id_calif"),
      ID_ROL:$("#cmbRol").val(),
      FECHAS_ASIGNADAS:$("#txtFechasGrupoAuditor").multiDatesPicker('value'),
      ID_USUARIO:sessionStorage.getItem("id_usuario")
    };
    console.log(JSON.stringify(grupo));
    $.post( global_apiserver + "/sg_auditoria_grupos/insert/", JSON.stringify(grupo), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
          $("#modalInsertarActualizarGrupoAuditoria").modal("hide");
          notify("Éxito", "Se ha insertado un auditor al grupo", "success");
          redraw_grupo_auditoria();
        }
        else{
          notify("Error", respuesta.mensaje, "error");
        }
    });
  }

// ================================================================================
// *****                       Sitios de auditoría                             *****
// ================================================================================
  
  function clear_modal_insertar_actualizar_sitio_auditoria(){
    $("#txtClave-Audit").val("");
    $("#txtClaveAuditoria-Audit").val($("#btnGuardarSitiosAuditoria").attr("id_auditoria"));
    $("#txtClaveSitio-Audit").val("");
    $("#txtNombreSitio-Audit").val("");
    $("#txtClaveAuditoria-Audit").attr("readonly","true");
    $("#txtClaveSitio-Audit").attr("readonly","true");
    $("#txtClave-Audit").attr("readonly","true");
  }

  function draw_head_row_sitios_auditoria(tipo_servicio){
    var strHtml = "";
    strHtml += '        <tr>';
    strHtml += '         <th>Clave del Tipo de Servicio</th>';
    strHtml += '         <th>Clave Domicilio del Cliente</th>';
    if (tipo_servicio == 'CSAST') {
      strHtml += '         <th>Cantidad de Personas</th>';  
    }
    strHtml += '         <th>Cantidad de Turnos</th>';
    strHtml += '         <th>Numero Total de Empleados</th>';
    strHtml += '         <th>Numero de Empleados con Certificacion</th>';
    strHtml += '         <th>Cantidad de Procesos</th>';
    strHtml += '         <th>Duracion</th>';
    strHtml += '         <th>Dias de Auditoria</th>';
    strHtml += '         <th></th>';
    strHtml += '        </tr>';
    return strHtml;
  }
  
  function draw_row_sitios_auditoria(objSitio, dias_auditoria, id_sg_sitio_aditoria, tipo_servicio){
      var strHtml = "";
      strHtml += '      <tr>';
      strHtml += '        <td>' + objSitio.CLAVE_TIPO_SERVICIO + '</td>';
      strHtml += '        <td>' + objSitio.NOMBRE_DOMICILIO + '</td>';
      if (tipo_servicio == 'CSAST') {
        strHtml += '        <td>' + objSitio.CANTIDAD_PERSONAS + '</td>';
      }
      strHtml += '        <td>' + objSitio.CANTIDAD_TURNOS + '</td>';
      strHtml += '        <td>' + objSitio.NUMERO_TOTAL_EMPLEADOS + '</td>';
      strHtml += '        <td>' + objSitio.NUMERO_EMPLEADOS_CERTIFICACION + '</td>';
      strHtml += '        <td>' + objSitio.CANTIDAD_DE_PROCESOS + '</td>';
      strHtml += '        <td>' + objSitio.TEMPORAL_O_FIJO + '</td>';
      if(dias_auditoria > 0){
        strHtml += '        <td>' + dias_auditoria + '</td>';
      }
      else{
        strHtml += '        <td>N/A</td>';
      }
      strHtml += '        <td>'; 
      if (global_permisos["SERVICIOS"]["editar"] == 1) {
        strHtml += '        <button class="btn btn-primary btn-xs btnEliminaSitioAuditoria" id_sg_sitio_aditoria="'+id_sg_sitio_aditoria+'"><i class="fa fa-trash" aria-hidden="true"></i></button>'; 
      }
      strHtml += '        </td>';
      strHtml += '      </tr>';
      return strHtml;
  }

  function draw_head_row_modal_sitio_auditoria(tipo_servicio){
    var strHtml = "";
    strHtml += '        <tr>';
    strHtml += '         <th>Clave</th>';
    strHtml += '         <th>Clave del Tipo de Servicio</th>';
    strHtml += '         <th>Clave Domicilio del Cliente</th>';
    if (tipo_servicio == 'CSAST') {
        strHtml += '         <th>Cantidad de Personas</th>';
    }
    strHtml += '         <th>Actividad</th>';
    strHtml += '         <th>Cantidad de Turnos</th>';
    strHtml += '         <th>Numero Total de Empleados</th>';
    strHtml += '         <th>Numero de Empleados con Certificacion</th>';
    strHtml += '         <th>Cantidad de Procesos</th>';
    strHtml += '         <th>Duracion</th>';
    strHtml += '         <th></th>';
    strHtml += '        </tr>';
    return strHtml;
  }

  function draw_row_modal_sitio_auditoria(objSitio, tipo_servicio){
    var strHtml = "";
    strHtml += '      <tr>';
    strHtml += '        <td>' + objSitio.ID + '</td>';
    strHtml += '        <td>' + objSitio.CLAVE_TIPO_SERVICIO + '</td>';
    strHtml += '        <td>' + objSitio.NOMBRE_DOMICILIO + '</td>';
    if (tipo_servicio == 'CSAST') {
        strHtml += '        <td>' + objSitio.CANTIDAD_PERSONAS + '</td>';
    }
    strHtml += '        <td>' + objSitio.ACTIVIDAD + '</td>';
    strHtml += '        <td>' + objSitio.CANTIDAD_TURNOS + '</td>';
    strHtml += '        <td>' + objSitio.NUMERO_TOTAL_EMPLEADOS + '</td>';
    strHtml += '        <td>' + objSitio.NUMERO_EMPLEADOS_CERTIFICACION + '</td>';
    strHtml += '        <td>' + objSitio.CANTIDAD_DE_PROCESOS + '</td>';
    strHtml += '        <td>' + objSitio.TEMPORAL_O_FIJO + '</td>';
    strHtml += '  <td>'
    if (objSitio.EXISTE_EN_AUDITORIA) {
      strHtml += '    <button type="button" class="btn btn-default btn-xs" style="float: right;" disabled> en auditoria </button>';
    }
    else{
      strHtml += '    <button type="button" class="btn btn-primary btn-xs btn-imnc btnSeleccionarSitio" nom_sitio="'+objSitio.NOMBRE_DOMICILIO+'" id_sitio="'+objSitio.ID+'" style="float: right;"> seleccionar </button>';
    }
    strHtml += '  </td>';
    strHtml += '      </tr>';
    return strHtml;
  }

  function draw_sitios_auditoria( id_auditoria, tipo_servicio, colapsar){
       $.getJSON( global_apiserver + "/sg_auditoria_sitios/getBySgAuditoria/?id_sg_auditoria="+id_auditoria, function( response ) {
          //console.log(response);
          if (response.length > 0) {
            $("#thead-"+id_auditoria+"-sitios-auditoria").html(draw_head_row_sitios_auditoria(tipo_servicio));
          }
          $("#tbody-"+id_auditoria+"-sitios-auditoria").html("");
          $.each(response, function( index, objauditoria ) {
              $("#tbody-"+id_auditoria+"-sitios-auditoria").append(draw_row_sitios_auditoria(objauditoria.SG_SITIOS, objauditoria.DIAS_AUDITORIAS, objauditoria.ID, tipo_servicio));
          });
          if(colapsar){
            $("#collapse-"+id_auditoria+"-sitios-auditoria").collapse("toggle");
          }
          else{
            $("#collapse-"+id_auditoria+"-sitios-auditoria").collapse("show");
          }
          listener_btn_elimina_sitio_auditoria();
       });
  }

  function redraw_sitios_auditoria(){
    redraw_auditorias();
    var id_auditoria = $("#btnGuardarSitiosAuditoria").attr("id_auditoria");
    var tipo_servicio = $("#btnGuardarSitiosAuditoria").attr("tipo_servicio");
    draw_sitios_auditoria(id_auditoria, tipo_servicio, false);
  }

  function listener_btn_sitios_auditoria(){
    $( ".btnSitiosAuditoria" ).click(function() {
      var id_auditoria = $(this).attr("id_auditoria");
      var tipo_servicio = $(this).attr("tipo_servicio");
      $( "#btnGuardarSitiosAuditoria" ).attr("id_auditoria", id_auditoria);
      $( "#btnGuardarSitiosAuditoria" ).attr("tipo_servicio", tipo_servicio);
      draw_sitios_auditoria(id_auditoria, tipo_servicio, true);
    });
  }

  function listener_btn_nuevo_sitio_auditoria(){
    $( ".btnInsertaSitiosAuditoria" ).click(function() {
      $("#btnGuardarSitiosAuditoria").attr("accion","insertar");
      $("#btnGuardarSitiosAuditoria").attr("id_auditoria",$(this).attr("id_auditoria"));
      $("#btnExplorarSitios").attr("id_serv",$(this).attr("id_serv"));
      $("#btnExplorarSitios").attr("id_auditoria",$(this).attr("id_auditoria"));
      $("#btnExplorarSitios").attr("tipo_servicio",$(this).attr("tipo_servicio"));
      $("#modalTituloSitiosAuditoria").html("Insertar sitio de  " + global_str_auditorias.toLowerCase());
      $("#txtClaveAuditoria-Audit").attr("readonly","true");
      $("#txtClaveSitio-Audit").attr("readonly","true")
      clear_modal_insertar_actualizar_sitio_auditoria();
      $("#modalInsertarActualizarSitiosAuditoria").modal("show");
    });
  }

  function listener_btn_explorar_sitios(){
    $( "#btnExplorarSitios" ).click(function() {
      var id_sg_tipo_servicio = $(this).attr("id_serv");
      var id_auditoria = $(this).attr("id_auditoria");
      var tipo_servicio = $(this).attr("tipo_servicio");
       $.getJSON( global_apiserver + "/sg_sitios/getBySgTipoServicio/?id_sg_tipo_servicio="+id_sg_tipo_servicio+"&id_auditoria="+id_auditoria, function( response ) {
          if ($.isArray(response)) {
            $("#thead-modal-explora-sitios").html(draw_head_row_modal_sitio_auditoria(tipo_servicio));
            $("#tbody-modal-explora-sitios").html("");
            $.each(response, function( indice, objSitio ) {
              $("#tbody-modal-explora-sitios").append(draw_row_modal_sitio_auditoria(objSitio, tipo_servicio)); 
            });
            $("#modalInsertarActualizarSitiosAuditoria").modal("hide");
            $("#modalExplorarSitios").modal("show");
          }
          else{
            notify("Error", response.mensaje, "error");
          }
          listener_btn_seleccionar_sitio();
       });
    });
  }

  function listener_btn_elimina_sitio_auditoria(){
    $( ".btnEliminaSitioAuditoria" ).click(function() {
      var id_sg_sitio_aditoria = $(this).attr("id_sg_sitio_aditoria");
       $.getJSON( global_apiserver + "/sg_auditoria_sitios/delete/?id="+id_sg_sitio_aditoria, function( response ) {
          if (response.resultado == "ok") {
            notify("Éxito", "Se ha eliminado un sitio-auditoria", "success");
            redraw_sitios_auditoria();
          }
          else{
            notify("Error", response.mensaje, "error");
          }
       });
    });
  }

  function listener_btn_seleccionar_sitio(){
    $( ".btnSeleccionarSitio" ).click(function() {
      var id_sitio = $(this).attr("id_sitio");
      var nom_sitio = $(this).attr("nom_sitio");
      $("#txtClaveSitio-Audit").val(id_sitio);
      $("#txtNombreSitio-Audit").val(nom_sitio);
      $("#modalExplorarSitios").modal("hide");
      $("#modalInsertarActualizarSitiosAuditoria").modal("show");
    });
  }

  function listener_btn_guardar_sitio_auditoria(){
    $( "#btnGuardarSitiosAuditoria" ).click(function() {
      if ($("#btnGuardarSitiosAuditoria").attr("accion") == "insertar")
      {
        insertar_sitio_auditoria();
      }
      // else if ($("#btnGuardarSitiosAuditoria").attr("accion") == "editar")
      // {
      //   editar_sitio_auditoria();
      // }
    });
  }

  function insertar_sitio_auditoria(){
    var sitio = {
      ID_SG_AUDITORIA:$("#txtClaveAuditoria-Audit").val(),
      ID_SG_SITIO:$("#txtClaveSitio-Audit").val(),
      ID_USUARIO:sessionStorage.getItem("id_usuario")
    };
    $.post( global_apiserver + "/sg_auditoria_sitios/insert/", JSON.stringify(sitio), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
          $("#modalInsertarActualizarSitiosAuditoria").modal("hide");
          notify("Éxito", "Se ha insertado una relación sitio-auditoria", "success");
          redraw_sitios_auditoria();
        }
        else{
          notify("Error", respuesta.mensaje, "error");
        }
    });
  }

// ================================================================================
// *****                        Certificado                                   *****
// ================================================================================

  // Regresa html para pintar los datos del certificado en la intefaz dentro del collapse
  function draw_row_certificado(objCertificado){ 
    var fecha_inicio = objCertificado.FECHA_INICIO;
    fecha_inicio = fecha_inicio.substring(6,8)+"/"+fecha_inicio.substring(4,6)+"/"+fecha_inicio.substring(0,4);
    var fecha_fin = objCertificado.FECHA_FIN;
    fecha_fin = fecha_fin.substring(6,8)+"/"+fecha_fin.substring(4,6)+"/"+fecha_fin.substring(0,4);
    var fecha_renov = objCertificado.FECHA_RENOVACION;
    fecha_renov = fecha_renov.substring(6,8)+"/"+fecha_renov.substring(4,6)+"/"+fecha_renov.substring(0,4);
    var fecha_ini_acred = objCertificado.FECHA_INICIO_ACREDITACION;
    fecha_ini_acred = fecha_ini_acred.substring(6,8)+"/"+fecha_ini_acred.substring(4,6)+"/"+fecha_ini_acred.substring(0,4);
    var fecha_fin_acred = objCertificado.FECHA_FIN_ACREDITACION;
    fecha_fin_acred = fecha_fin_acred.substring(6,8)+"/"+fecha_fin_acred.substring(4,6)+"/"+fecha_fin_acred.substring(0,4);
    var fecha_suspension = objCertificado.FECHA_SUSPENSION;
    fecha_suspension = fecha_suspension.substring(6,8)+"/"+fecha_suspension.substring(4,6)+"/"+fecha_suspension.substring(0,4);
    var fecha_cancelacion = objCertificado.FECHA_CANCELACION;
    fecha_cancelacion = fecha_cancelacion.substring(6,8)+"/"+fecha_cancelacion.substring(4,6)+"/"+fecha_cancelacion.substring(0,4);

    var status = '';
    if (  objCertificado.STATUS == 'vigente' ) {
      status = '<span style="background-color:#b9f3b1;">' + objCertificado.STATUS + '</span>';
    }
    if (  objCertificado.STATUS == 'suspendido' ) {
      status = '<span style="background-color:#f3f3b1;">' + objCertificado.STATUS + '</span>';
    }
    if (  objCertificado.STATUS == 'cancelado' ) {
      status = '<span style="background-color:#f3bcb1;">' + objCertificado.STATUS + '</span>';
    }

    var strHtml = "";
    strHtml += '<tr>';
    strHtml += '  <td colspan="13" style="border: none;padding: 15px 20px; box-shadow: 2px 2px 10px #a5a5a5;">';
    strHtml += '    <div class="row">';
    strHtml += '      <div class="col-md-12">';
    strHtml += '        Clave de certificado: ' + objCertificado.CLAVE;
    strHtml += '      </div>';
    strHtml += '    </div>';
    strHtml += '    <div class="row">';
    strHtml += '      <div class="col-md-3">';
    strHtml += '        Periodicidad: ' + objCertificado.PERIODICIDAD + ' meses';
    strHtml += '      </div>';
    strHtml += '      <div class="col-md-3">';
    strHtml += '        Fecha de inicio: ' + fecha_inicio;
    strHtml += '      </div>';
    strHtml += '      <div class="col-md-3">';
    strHtml += '        Fecha de fin: ' + fecha_fin;
    strHtml += '      </div>';
    strHtml += '      <div class="col-md-3">';
    strHtml += '        Fecha de renovación: ' + fecha_renov;
    strHtml += '      </div>';
    strHtml += '    </div><br>';
    strHtml += '    <div class="row">';
    strHtml += '      <div class="col-md-12">';
    strHtml += '        Acreditación: ' + objCertificado.ACREDITACION;
    strHtml += '      </div>';
    strHtml += '    </div>';
    strHtml += '    <div class="row">';
    strHtml += '      <div class="col-md-4">';
    strHtml += '        Fecha de inicio de acreditación: ' + fecha_ini_acred;
    strHtml += '      </div>';
    strHtml += '      <div class="col-md-4">';
    strHtml += '        Fecha de fin de acreditación: ' + fecha_fin_acred;
    strHtml += '      </div>';
    strHtml += '      <div class="col-md-4">';
    strHtml += '      </div>';
    strHtml += '    </div><br>';
    strHtml += '    <div class="row">';
    strHtml += '      <div class="col-md-12">';
    strHtml += '        Estatus: ' + status;
    strHtml += '      </div>';
    strHtml += '    </div>';
    if (objCertificado.STATUS == 'suspendido') {
      strHtml += '    <div class="row">';
      strHtml += '      <div class="col-md-12">';
      strHtml += '        Fecha de supensión: ' + fecha_suspension;
      strHtml += '      </div>';
      strHtml += '    </div>';
      strHtml += '    <div class="row">';
      strHtml += '      <div class="col-md-12">';
      strHtml += '        Motivo de supensión: ' + objCertificado.MOTIVO_SUSPENSION;
      strHtml += '      </div>';
      strHtml += '    </div>';
    }
    if (objCertificado.STATUS == 'cancelado') {
      strHtml += '    <div class="row">';
      strHtml += '      <div class="col-md-12">';
      strHtml += '        Fecha de cancelación: ' + fecha_cancelacion;
      strHtml += '      </div>';
      strHtml += '    </div>';
      strHtml += '    <div class="row">';
      strHtml += '      <div class="col-md-12">';
      strHtml += '        Motivo de cancelación: ' + objCertificado.MOTIVO_CANCELACION;
      strHtml += '      </div>';
      strHtml += '    </div>';
    }global_diffname
    if (objCertificado.EXISTE_ARCHIVO == 'si') {
      strHtml += '    <div class="row">';
      strHtml += '      <div class="col-md-12 text-center">';
      strHtml += '        <a target="_blank" href="'+ global_apiserver + objCertificado.RUTA_ARCHIVO+'">';
      strHtml += '          <img src="diff/'+global_diffname+'/pdf-icon.png" width="50px">';
      strHtml += '          <br>';
      strHtml += '           Ver certificado';
      strHtml += '        </a>';
      strHtml += '      </div>';
      strHtml += '    </div>';
    }
    strHtml += '  </td>';
    strHtml += '</tr>';
    
    return strHtml;
  }

  // Obtiene y pinta certificado 
  function get_certificado_from_api_and_draw(id_sg_tipo_servicio){
    $(".btnInsertaEditaCertificado[id_serv='"+id_sg_tipo_servicio+"']").hide();
    $.getJSON( global_apiserver + "/sg_certificado/getBySgTipoServicio/?id_sg_tipo_servicio="+id_sg_tipo_servicio, function( objCertificado ) {
        $("#tbody-"+id_sg_tipo_servicio+"-certificado").html("");
         
        if (objCertificado.ID) {  
          $(".btnInsertaEditaCertificado[id_serv='"+id_sg_tipo_servicio+"']").html('<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar certificado');
          $(".btnInsertaEditaCertificado[id_serv='"+id_sg_tipo_servicio+"']").attr("opcion", "editar");
          $("#tbody-"+id_sg_tipo_servicio+"-certificado").append(draw_row_certificado(objCertificado));   
          if (global_permisos["SERVICIOS"]["editar"] == 1) {
            $(".btnInsertaEditaCertificado[id_serv='"+id_sg_tipo_servicio+"']").show();
          }
        }
        else{
          $(".btnInsertaEditaCertificado[id_serv='"+id_sg_tipo_servicio+"']").html('<i class="fa fa-plus"></i> Agregar certificado');
          $(".btnInsertaEditaCertificado[id_serv='"+id_sg_tipo_servicio+"']").attr("opcion", "insertar");
          if (global_permisos["SERVICIOS"]["registrar"] == 1) {
            $(".btnInsertaEditaCertificado[id_serv='"+id_sg_tipo_servicio+"']").show();
          }
        }
        listener_btn_insertar_editar_certificado();
     });
  }

  // Escuchador del botón que activa y esconde el collapse
  function listener_btn_certificado(){
    $( ".btnCertificado" ).click(function() {
      var id_sg_tipo_servicio = $(this).attr("id");
      var objLabelAcordeon = $(this).find(".labelAcordeon");
      var objCollapseCertificado = $("#collapse-"+id_sg_tipo_servicio+"-certificado");

      if (objCollapseCertificado.hasClass("in")) 
      {
        objLabelAcordeon.html("Mostrar");
        objCollapseCertificado.collapse("hide");
      }
      else 
      {
        objLabelAcordeon.html("Ocultar");
        objCollapseCertificado.collapse("show");
        get_certificado_from_api_and_draw(id_sg_tipo_servicio);
      }
    });
  }

  // Escuchador del botón que permite reemplazar un archivo de certificado
  function listener_btn_reemplazar_archivo_certificado(){
    $( "#btnReemplazarArchivoCertificado" ).click(function() {
      $(".editarArchivoCertificado").hide();
      init_btn_subir_archivos(".subirArchivoCertificado");
      $(".subirArchivoCertificado").show();
    });
  }

  // Escuchador del cambio del combobox estatus en el modal del certificado
  function  listener_cmb_estatus_certificado(){
    $( "#cmbEstatusCertificado" ).change(function() {
        $(".form-certificado-suspension").hide();
        $("#txtFechaSuspensionCertificado").val("");
        $("#txtMotivoSuspensionCertificado").val("");
        $(".form-certificado-cancelacion").hide();
        $("#txtFechaCancelacionCertificado").val("");
        $("#txtMotivoCancelacionCertificado").val("");

        if (  $(this).val() == 'suspendido' ) {
            $(".form-certificado-suspension").show();
        }
        else if (  $(this).val() == 'cancelado' ) {
            $(".form-certificado-cancelacion").show();
        }
    });
  }

  // Limpia el modal de certificado y activa al botón para subir archivos
  function  clear_modal_insertar_actualizar_certificado(){
    $("#txtIdCertificado").val("");
    $("#txtClaveCertificado").val("");
    $("#txtFechaInicioCertificado").val("");
    $("#txtFechaFinCertificado").val("");
    $("#txtFechaRenovacionCertificado").val("");
    $("#cmbPeriodicidadCertificado").val("");
    $("#txtAcreditacionCertificado").val("");
    $("#txtFechaInicioAcreditacion").val("");
    $("#txtFechaFinAcreditacion").val("");
    $("#cmbEstatusCertificado").val("");
    $("#cmbEstatusCertificado").change();
    $(".subirArchivoCertificado").show();
    $(".subirArchivoCertificado").attr("nombre_archivo", "");
    $(".editarArchivoCertificado").hide();
    init_btn_subir_archivos(".subirArchivoCertificado");
  }

  // Llena el modal de certificado con los datos recuparados del API dado un id_sg_tipo_servicio
  function  fill_modal_insertar_actualizar_certificado(id_sg_tipo_servicio){
    $.getJSON(  global_apiserver + "/sg_certificado/getBySgTipoServicio/?id_sg_tipo_servicio="+id_sg_tipo_servicio, function( objCertificado ) {
        var fecha_inicio = objCertificado.FECHA_INICIO;
        fecha_inicio = fecha_inicio.substring(6,8)+"/"+fecha_inicio.substring(4,6)+"/"+fecha_inicio.substring(0,4);
        var fecha_fin = objCertificado.FECHA_FIN;
        fecha_fin = fecha_fin.substring(6,8)+"/"+fecha_fin.substring(4,6)+"/"+fecha_fin.substring(0,4);
        var fecha_renov = objCertificado.FECHA_RENOVACION;
        fecha_renov = fecha_renov.substring(6,8)+"/"+fecha_renov.substring(4,6)+"/"+fecha_renov.substring(0,4);
        var fecha_ini_acred = objCertificado.FECHA_INICIO_ACREDITACION;
        fecha_ini_acred = fecha_ini_acred.substring(6,8)+"/"+fecha_ini_acred.substring(4,6)+"/"+fecha_ini_acred.substring(0,4);
        var fecha_fin_acred = objCertificado.FECHA_FIN_ACREDITACION;
        fecha_fin_acred = fecha_fin_acred.substring(6,8)+"/"+fecha_fin_acred.substring(4,6)+"/"+fecha_fin_acred.substring(0,4);
        var fecha_suspension = objCertificado.FECHA_SUSPENSION;
        fecha_suspension = fecha_suspension.substring(6,8)+"/"+fecha_suspension.substring(4,6)+"/"+fecha_suspension.substring(0,4);
        var fecha_cancelacion = objCertificado.FECHA_CANCELACION;
        fecha_cancelacion = fecha_cancelacion.substring(6,8)+"/"+fecha_cancelacion.substring(4,6)+"/"+fecha_cancelacion.substring(0,4);

        $("#txtIdCertificado").val(objCertificado.ID);
        $("#txtClaveCertificado").val(objCertificado.CLAVE);
        $("#txtFechaInicioCertificado").val(fecha_inicio);
        $("#txtFechaFinCertificado").val(fecha_fin);
        $("#txtFechaRenovacionCertificado").val(fecha_renov);
        $("#cmbPeriodicidadCertificado").val(objCertificado.PERIODICIDAD);
        $("#txtAcreditacionCertificado").val(objCertificado.ACREDITACION);
        $("#txtFechaInicioAcreditacion").val(fecha_ini_acred);
        $("#txtFechaFinAcreditacion").val(fecha_fin_acred);
        $("#cmbEstatusCertificado").val(objCertificado.STATUS);
        $( "#cmbEstatusCertificado" ).change();
       
        $("#txtFechaSuspensionCertificado").val(fecha_suspension);
        $("#txtMotivoSuspensionCertificado").val(objCertificado.MOTIVO_SUSPENSION);
        $("#txtFechaCancelacionCertificado").val(fecha_cancelacion);
        $("#txtMotivoCancelacionCertificado").val(objCertificado.MOTIVO_CANCELACION);


        var nombre_archivo = objCertificado.RUTA_ARCHIVO.split("/");
        nombre_archivo = nombre_archivo[nombre_archivo.length-1];

        $(".subirArchivoCertificado").attr("nombre_archivo", nombre_archivo);

        if (objCertificado.EXISTE_ARCHIVO == 'no') {
          $(".subirArchivoCertificado").show();
          $(".editarArchivoCertificado").hide();
          init_btn_subir_archivos(".subirArchivoCertificado");
        }
        else if (objCertificado.EXISTE_ARCHIVO == 'si') {
          $(".subirArchivoCertificado").hide();
          $(".editarArchivoCertificado").show();
          $(".hrefVerArchivoCertificado").attr("href", global_apiserver + objCertificado.RUTA_ARCHIVO);
        }

        $(".ajax-file-upload-statusbar").hide();
    });
  }

  // Escuchador para el botón editar/insertar certificado (dentro del collapse). Despliega el modal certificado
  function listener_btn_insertar_editar_certificado(){
    $( ".btnInsertaEditaCertificado" ).click(function() {
      $("#btnGuardarCertificado").attr("accion",$(this).attr("opcion"));
      $("#btnGuardarCertificado").attr("id_serv",$(this).attr("id_serv"));
      $("#btnGuardarCertificado").attr("id_tipo_servicio",$(this).attr("id_tipo_servicio"));
      $("#txtIdCertificado").attr("readonly","true");
      $("#modalTituloCertificado").html($(this).html());
      if ($(this).attr("opcion") == "editar") {
          fill_modal_insertar_actualizar_certificado($(this).attr("id_serv"));
      }
      else if ($(this).attr("opcion") == "insertar") {
          clear_modal_insertar_actualizar_certificado();
      }
      $("#modalInsertarActualizarCertificado").modal("show");
    });
  }

  // Escuchador del botón guardar certificado dentro del modal certificado
  function listener_btn_guardar_certificado(){
    $( "#btnGuardarCertificado" ).click(function() {
      var id_sg_tipo_servicio = $(this).attr("id_serv");
      if ($(this).attr("accion") == "insertar")
      {
        insertar_certificado(id_sg_tipo_servicio);
      }
      else if ($(this).attr("accion") == "editar")
      {
        editar_certificado(id_sg_tipo_servicio);
      }
    });
  }

  // Funcion que inserta al API un certificado con los datos del modal certificado
  function insertar_certificado(id_sg_tipo_servicio){
    var fech_ini_cert = $("#txtFechaInicioCertificado").val();
    fech_ini_cert = fech_ini_cert.substring(6,10)+fech_ini_cert.substring(3,5)+fech_ini_cert.substring(0,2);
    var fec_fin_cert = $("#txtFechaFinCertificado").val();
    fec_fin_cert = fec_fin_cert.substring(6,10)+fec_fin_cert.substring(3,5)+fec_fin_cert.substring(0,2);
    var fec_renov_cert = $("#txtFechaRenovacionCertificado").val();
    fec_renov_cert = fec_renov_cert.substring(6,10)+fec_renov_cert.substring(3,5)+fec_renov_cert.substring(0,2);
    var fec_ini_acred = $("#txtFechaInicioAcreditacion").val();
    fec_ini_acred = fec_ini_acred.substring(6,10)+fec_ini_acred.substring(3,5)+fec_ini_acred.substring(0,2);
    var fecha_fin_acred = $("#txtFechaFinAcreditacion").val();
    fecha_fin_acred = fecha_fin_acred.substring(6,10)+fecha_fin_acred.substring(3,5)+fecha_fin_acred.substring(0,2);
    var fecha_susp = $("#txtFechaSuspensionCertificado").val();
    fecha_susp = fecha_susp.substring(6,10)+fecha_susp.substring(3,5)+fecha_susp.substring(0,2);
    var fecha_cancel = $("#txtFechaCancelacionCertificado").val();
    fecha_cancel = fecha_cancel.substring(6,10)+fecha_cancel.substring(3,5)+fecha_cancel.substring(0,2);

    // if (!$(".subirArchivoCertificado").attr("nombre_archivo")) 
    // {
    //   notify("Error", "Es necesario subir el archivo del certificado", "error");
    //   return;
    // }

    var certificado = {
      CLAVE:$("#txtClaveCertificado").val(),
      ID_SG_TIPOS_SERVICIO:id_sg_tipo_servicio,
      FECHA_INICIO:fech_ini_cert,
      FECHA_FIN:fec_fin_cert,
      FECHA_RENOVACION:fec_renov_cert,
      PERIODICIDAD:$("#cmbPeriodicidadCertificado").val(),
      NOMBRE_ARCHIVO:$(".subirArchivoCertificado").attr("nombre_archivo"),
      ACREDITACION:$("#txtAcreditacionCertificado").val(),
      FECHA_INICIO_ACREDITACION:fec_ini_acred,
      FECHA_FIN_ACREDITACION:fecha_fin_acred,
      STATUS:$("#cmbEstatusCertificado").val(),
      FECHA_SUSPENSION:fecha_susp,
      MOTIVO_SUSPENSION:$("#txtMotivoSuspensionCertificado").val(),
      FECHA_CANCELACION:fecha_cancel,
      MOTIVO_CANCELACION:$("#txtMotivoCancelacionCertificado").val(),
       ID_USUARIO:sessionStorage.getItem("id_usuario")
    };
    $.post( global_apiserver + "/sg_certificado/insert/", JSON.stringify(certificado), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
          get_certificado_from_api_and_draw(certificado.ID_SG_TIPOS_SERVICIO);
          $("#modalInsertarActualizarCertificado").modal("hide");
          notify("Éxito", "Se ha insertado el certificado", "success");
        }
        else{
          notify("Error", respuesta.mensaje, "error");
        }
    });
  }

  // Funcion que actualiza al API un certificado con los datos del modal certificado
  function editar_certificado(id_sg_tipo_servicio){
    var fech_ini_cert = $("#txtFechaInicioCertificado").val();
    fech_ini_cert = fech_ini_cert.substring(6,10)+fech_ini_cert.substring(3,5)+fech_ini_cert.substring(0,2);
    var fec_fin_cert = $("#txtFechaFinCertificado").val();
    fec_fin_cert = fec_fin_cert.substring(6,10)+fec_fin_cert.substring(3,5)+fec_fin_cert.substring(0,2);
    var fec_renov_cert = $("#txtFechaRenovacionCertificado").val();
    fec_renov_cert = fec_renov_cert.substring(6,10)+fec_renov_cert.substring(3,5)+fec_renov_cert.substring(0,2);
    var fec_ini_acred = $("#txtFechaInicioAcreditacion").val();
    fec_ini_acred = fec_ini_acred.substring(6,10)+fec_ini_acred.substring(3,5)+fec_ini_acred.substring(0,2);
    var fecha_fin_acred = $("#txtFechaFinAcreditacion").val();
    fecha_fin_acred = fecha_fin_acred.substring(6,10)+fecha_fin_acred.substring(3,5)+fecha_fin_acred.substring(0,2);
    var fecha_susp = $("#txtFechaSuspensionCertificado").val();
    fecha_susp = fecha_susp.substring(6,10)+fecha_susp.substring(3,5)+fecha_susp.substring(0,2);
    var fecha_cancel = $("#txtFechaCancelacionCertificado").val();
    fecha_cancel = fecha_cancel.substring(6,10)+fecha_cancel.substring(3,5)+fecha_cancel.substring(0,2);

    // if (!$(".subirArchivoCertificado").attr("nombre_archivo")) 
    // {
    //   notify("Error", "Es necesario subir el archivo del certificado", "error");
    //   return;
    // }

    var certificado = {
      ID:$("#txtIdCertificado").val(),
      CLAVE:$("#txtClaveCertificado").val(),
      ID_SG_TIPOS_SERVICIO:id_sg_tipo_servicio,
      FECHA_INICIO:fech_ini_cert,
      FECHA_FIN:fec_fin_cert,
      FECHA_RENOVACION:fec_renov_cert,
      PERIODICIDAD:$("#cmbPeriodicidadCertificado").val(),
      NOMBRE_ARCHIVO:$(".subirArchivoCertificado").attr("nombre_archivo"),
      ACREDITACION:$("#txtAcreditacionCertificado").val(),
      FECHA_INICIO_ACREDITACION:fec_ini_acred,
      FECHA_FIN_ACREDITACION:fecha_fin_acred,
      STATUS:$("#cmbEstatusCertificado").val(),
      FECHA_SUSPENSION:fecha_susp,
      MOTIVO_SUSPENSION:$("#txtMotivoSuspensionCertificado").val(),
      FECHA_CANCELACION:fecha_cancel,
      MOTIVO_CANCELACION:$("#txtMotivoCancelacionCertificado").val(),
       ID_USUARIO:sessionStorage.getItem("id_usuario")
    };
      $.post( global_apiserver + "/sg_certificado/update/", JSON.stringify(certificado), function(respuesta){
          respuesta = JSON.parse(respuesta);
          if (respuesta.resultado == "ok") {
            get_certificado_from_api_and_draw(certificado.ID_SG_TIPOS_SERVICIO);
            $("#modalInsertarActualizarCertificado").modal("hide");
            notify("Éxito", "Se han actualizado el certificado", "success");
          }
          else{
            notify("Error", respuesta.mensaje, "error");
          }
      });
  }

// ================================================================================
// *****                       Para demo de proceso                           *****
// ================================================================================

  function listener_btn_proceso(){
    $( ".btnProceso" ).click(function() {
      $("#modalProceso").modal("show");
    });
  }

  function setup_subir_paso_1_1(){
      $("#file_paso_1_1").hide()
      var _paso_proceso = "1_1";
      var uploadObj = $("#singleupload_paso1_1").uploadFile({
        url:global_apiserver + "/sg_proceso/uploadFile/",
        multiple:false,
        dragDrop:false,
        maxFileCount:1,
        acceptFiles:"pdf/*.pdf",
        fileName:"myfile",
        uploadStr:"Subir documento solicitud",
        formData: {"paso_proceso":_paso_proceso}, 
        onSuccess:function(files,data,xhr,pd)
        {
          data = JSON.parse(data);
          console.log(data);
          if (data.resultado == "ok") {
            notify("Éxito", "Se ha subido el archivo", "success"); 
            $("#file_paso_1_1").attr("href", global_apiserver + "/sg_proceso/uploadFile/uploads/" + data.filename); 
            $("#file_paso_1_1").show(); 
          }
          else{
             notify("Error", data.mensaje, "error");  
             $("#file_paso_1_1").hide();
             uploadObj.reset();
          }
          //uploadObj.reset();
        }
      });
  }

  function setup_subir_paso_1_2(){
      $("#file_paso_1_2").hide()
      var _paso_proceso = "1_2";
      var uploadObj = $("#singleupload_paso1_2").uploadFile({
        url:global_apiserver + "/sg_proceso/uploadFile/",
        multiple:false,
        dragDrop:false,
        maxFileCount:1,
        acceptFiles:"pdf/*.pdf",
        fileName:"myfile",
        uploadStr:"Subir comprobante de pago",
        formData: {"paso_proceso":_paso_proceso}, 
        onSuccess:function(files,data,xhr,pd)
        {
          data = JSON.parse(data);
          console.log(data);
          if (data.resultado == "ok") {
            notify("Éxito", "Se ha subido el archivo", "success"); 
            $("#file_paso_1_2").attr("href", global_apiserver + "/sg_proceso/uploadFile/uploads/" + data.filename); 
            $("#file_paso_1_2").show(); 
          }
          else{
             notify("Error", data.mensaje, "error");  
             $("#file_paso_1_2").hide();
             uploadObj.reset();
          }
          //uploadObj.reset();
        }
      });
  }

  function setup_subir_paso_4(){
      $("#file_paso_4").hide()
      var _paso_proceso = "4";
      var uploadObj = $("#singleupload_paso4").uploadFile({
        url:global_apiserver + "/sg_proceso/uploadFile/",
        multiple:false,
        dragDrop:false,
        maxFileCount:1,
        acceptFiles:"pdf/*.pdf",
        fileName:"myfile",
        uploadStr:"Subir documento de cotización",
        formData: {"paso_proceso":_paso_proceso}, 
        onSuccess:function(files,data,xhr,pd)
        {
          data = JSON.parse(data);
          console.log(data);
          if (data.resultado == "ok") {
            notify("Éxito", "Se ha subido el archivo", "success"); 
            $("#file_paso_4").attr("href", global_apiserver + "/sg_proceso/uploadFile/uploads/" + data.filename); 
            $("#file_paso_4").show(); 
          }
          else{
             notify("Error", data.mensaje, "error");  
             $("#file_paso_4").hide();
             uploadObj.reset();
          }
          //uploadObj.reset();
        }
      });
    }

   function setup_subir_paso_5(){
      $("#file_paso_5").hide()
      var _paso_proceso = "5";
      var uploadObj = $("#singleupload_paso5").uploadFile({
        url:global_apiserver + "/sg_proceso/uploadFile/",
        multiple:false,
        dragDrop:false,
        maxFileCount:1,
        acceptFiles:"pdf/*.pdf",
        fileName:"myfile",
        uploadStr:"Subir documento de cotización",
        formData: {"paso_proceso":_paso_proceso}, 
        onSuccess:function(files,data,xhr,pd)
        {
          data = JSON.parse(data);
          console.log(data);
          if (data.resultado == "ok") {
            notify("Éxito", "Se ha subido el archivo", "success"); 
            $("#file_paso_5").attr("href", global_apiserver + "/sg_proceso/uploadFile/uploads/" + data.filename); 
            $("#file_paso_5").show(); 
          }
          else{
             notify("Error", data.mensaje, "error");  
             $("#file_paso_5").hide();
             uploadObj.reset();
          }
          //uploadObj.reset();
        }
      });
    }

    function setup_subir_paso_6(){
      $("#file_paso_6").hide()
      var _paso_proceso = "6";
      var uploadObj = $("#singleupload_paso6").uploadFile({
        url:global_apiserver + "/sg_proceso/uploadFile/",
        multiple:false,
        dragDrop:false,
        maxFileCount:1,
        acceptFiles:"pdf/*.pdf",
        fileName:"myfile",
        uploadStr:"Subir documento de plan de evaluación",
        formData: {"paso_proceso":_paso_proceso}, 
        onSuccess:function(files,data,xhr,pd)
        {
          data = JSON.parse(data);
          console.log(data);
          if (data.resultado == "ok") {
            notify("Éxito", "Se ha subido el archivo", "success"); 
            $("#file_paso_6").attr("href", global_apiserver + "/sg_proceso/uploadFile/uploads/" + data.filename); 
            $("#file_paso_6").show(); 
          }
          else{
             notify("Error", data.mensaje, "error");  
             $("#file_paso_6").hide();
             uploadObj.reset();
          }
          //uploadObj.reset();
        }
      });
    }

    function setup_subir_paso_7_1(){
      $("#file_paso_7_1").hide()
      var _paso_proceso = "7_1";
      var uploadObj = $("#singleupload_paso7_1").uploadFile({
        url:global_apiserver + "/sg_proceso/uploadFile/",
        multiple:false,
        dragDrop:false,
        maxFileCount:1,
        acceptFiles:"pdf/*.pdf",
        fileName:"myfile",
        uploadStr:"Subir documento de Informe de Evaluación Etapa 1",
        formData: {"paso_proceso":_paso_proceso}, 
        onSuccess:function(files,data,xhr,pd)
        {
          data = JSON.parse(data);
          console.log(data);
          if (data.resultado == "ok") {
            notify("Éxito", "Se ha subido el archivo", "success"); 
            $("#file_paso_7_1").attr("href", global_apiserver + "/sg_proceso/uploadFile/uploads/" + data.filename); 
            $("#file_paso_7_1").show(); 
          }
          else{
             notify("Error", data.mensaje, "error");  
             $("#file_paso_7_1").hide();
             uploadObj.reset();
          }
          //uploadObj.reset();
        }
      });
  }

    function setup_subir_paso_7_2(){
      $("#file_paso_7_2").hide()
      var _paso_proceso = "7_2";
      var uploadObj = $("#singleupload_paso7_2").uploadFile({
        url:global_apiserver + "/sg_proceso/uploadFile/",
        multiple:false,
        dragDrop:false,
        maxFileCount:1,
        acceptFiles:"pdf/*.pdf",
        fileName:"myfile",
        uploadStr:"Subir comprobante de Plan de Evaluación para Etapa 2",
        formData: {"paso_proceso":_paso_proceso}, 
        onSuccess:function(files,data,xhr,pd)
        {
          data = JSON.parse(data);
          console.log(data);
          if (data.resultado == "ok") {
            notify("Éxito", "Se ha subido el archivo", "success"); 
            $("#file_paso_7_2").attr("href", global_apiserver + "/sg_proceso/uploadFile/uploads/" + data.filename); 
            $("#file_paso_7_2").show(); 
          }
          else{
             notify("Error", data.mensaje, "error");  
             $("#file_paso_7_2").hide();
             uploadObj.reset();
          }
          //uploadObj.reset();
        }
      });
  }

  function setup_subir_paso_8_1(){
      $("#file_paso_8_1").hide()
      var _paso_proceso = "8_1";
      var uploadObj = $("#singleupload_paso8_1").uploadFile({
        url:global_apiserver + "/sg_proceso/uploadFile/",
        multiple:false,
        dragDrop:false,
        maxFileCount:1,
        acceptFiles:"pdf/*.pdf",
        fileName:"myfile",
        uploadStr:"Subir el documento de Informe de Evaluación Etapa 2",
        formData: {"paso_proceso":_paso_proceso}, 
        onSuccess:function(files,data,xhr,pd)
        {
          data = JSON.parse(data);
          console.log(data);
          if (data.resultado == "ok") {
            notify("Éxito", "Se ha subido el archivo", "success"); 
            $("#file_paso_8_1").attr("href", global_apiserver + "/sg_proceso/uploadFile/uploads/" + data.filename); 
            $("#file_paso_8_1").show(); 
          }
          else{
             notify("Error", data.mensaje, "error");  
             $("#file_paso_8_1").hide();
             uploadObj.reset();
          }
          //uploadObj.reset();
        }
      });
  }

    function setup_subir_paso_8_2(){
      $("#file_paso_8_2").hide()
      var _paso_proceso = "8_2";
      var uploadObj = $("#singleupload_paso8_2").uploadFile({
        url:global_apiserver + "/sg_proceso/uploadFile/",
        multiple:false,
        dragDrop:false,
        maxFileCount:1,
        acceptFiles:"pdf/*.pdf",
        fileName:"myfile",
        uploadStr:"Subir el documento de Lista de asistencia Apertura",
        formData: {"paso_proceso":_paso_proceso}, 
        onSuccess:function(files,data,xhr,pd)
        {
          data = JSON.parse(data);
          console.log(data);
          if (data.resultado == "ok") {
            notify("Éxito", "Se ha subido el archivo", "success"); 
            $("#file_paso_8_2").attr("href", global_apiserver + "/sg_proceso/uploadFile/uploads/" + data.filename); 
            $("#file_paso_8_2").show(); 
          }
          else{
             notify("Error", data.mensaje, "error");  
             $("#file_paso_8_2").hide();
             uploadObj.reset();
          }
          //uploadObj.reset();
        }
      });
  }

  function setup_subir_paso_8_3(){
      $("#file_paso_8_3").hide()
      var _paso_proceso = "8_3";
      var uploadObj = $("#singleupload_paso8_3").uploadFile({
        url:global_apiserver + "/sg_proceso/uploadFile/",
        multiple:false,
        dragDrop:false,
        maxFileCount:1,
        acceptFiles:"pdf/*.pdf",
        fileName:"myfile",
        uploadStr:"Subir el documento de Lista de asistencia Cierre",
        formData: {"paso_proceso":_paso_proceso}, 
        onSuccess:function(files,data,xhr,pd)
        {
          data = JSON.parse(data);
          console.log(data);
          if (data.resultado == "ok") {
            notify("Éxito", "Se ha subido el archivo", "success"); 
            $("#file_paso_8_3").attr("href", global_apiserver + "/sg_proceso/uploadFile/uploads/" + data.filename); 
            $("#file_paso_8_3").show(); 
          }
          else{
             notify("Error", data.mensaje, "error");  
             $("#file_paso_8_3").hide();
             uploadObj.reset();
          }
          //uploadObj.reset();
        }
      });
  }

    function setup_subir_paso_8_4(){
      $("#file_paso_8_4").hide()
      var _paso_proceso = "8_4";
      var uploadObj = $("#singleupload_paso8_4").uploadFile({
        url:global_apiserver + "/sg_proceso/uploadFile/",
        multiple:false,
        dragDrop:false,
        maxFileCount:1,
        acceptFiles:"pdf/*.pdf",
        fileName:"myfile",
        uploadStr:"Subir el documento de trabajo (si aplica)",
        formData: {"paso_proceso":_paso_proceso}, 
        onSuccess:function(files,data,xhr,pd)
        {
          data = JSON.parse(data);
          console.log(data);
          if (data.resultado == "ok") {
            notify("Éxito", "Se ha subido el archivo", "success"); 
            $("#file_paso_8_4").attr("href", global_apiserver + "/sg_proceso/uploadFile/uploads/" + data.filename); 
            $("#file_paso_8_4").show(); 
          }
          else{
             notify("Error", data.mensaje, "error");  
             $("#file_paso_8_4").hide();
             uploadObj.reset();
          }
          //uploadObj.reset();
        }
      });
  }