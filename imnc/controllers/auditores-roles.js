
  function clear_modal_insertar_actualizar(){
	 $("#txtID").val("");
    $("#txtRol").val("");
    $("#txtClave").val("");
   // $("#txtClave").removeAttr("readonly");
    $("#txtJerarquia").val("");
  }

  function fill_modal_insertar_actualizar(id_personal_tecnico_rol){
    $.getJSON( global_apiserver + "/personal_tecnico_roles/getById/?id="+id_personal_tecnico_rol, function( response ) {
		$("#txtID").val(response.ID);
          $("#txtClave").val(response.ACRONIMO);
         // $("#txtClave").attr("readonly","true");
          $("#txtRol").val(response.ROL);
          $("#txtJerarquia").val(response.JERARQUIA);
       });
  }

  function notify_success(titulo, texto) {
      new PNotify({
          title: titulo,
          text: texto,
          type: 'success',
          nonblock: {
              nonblock: true,
              nonblock_opacity: .2
          },
          delay: 2500
      });
  }

  function draw_row_rol(objRol){
    var strHtml = "";
    strHtml += '<tr class="even pointer">';
    strHtml += '  <td>'+objRol.ACRONIMO+'</td>';
    strHtml += '  <td>'+objRol.ROL+'</td>';
    strHtml += '  <td>';
    if (global_permisos["AUDITORES"]["editar"] == 1) {
       strHtml += '    <button type="button" class="btn btn-primary btn-xs btn-imnc btnEditar" id_rol="'+objRol.ID+'" style="float: right;"> ';
      strHtml += '      <i class="fa fa-edit"> </i> Editar rol';
      strHtml += '    </button>';
    }
    strHtml += '  </td>';
    strHtml += '</tr>';
    return strHtml;
  }


  function draw_tabla_roles(){
   $.getJSON( global_apiserver + "/personal_tecnico_roles/getAll/", function( response ) {
        //console.log(response);
        $("#tbodyRoles").html("");
        $.each(response, function( index, objRol ) {
          $("#tbodyRoles").append(draw_row_rol(objRol));  
        });
        listener_btn_editar();
     });
  }

  function listener_btn_nuevo(){
    $( "#btnNuevo" ).click(function() {
      $("#btnGuardar").attr("accion","insertar");
      $("#modalTitulo").html("Insertar rol");
      clear_modal_insertar_actualizar();
      $("#modalInsertarActualizar").modal("show");
    });
  }

  function listener_btn_editar(){
    $( ".btnEditar" ).click(function() {
      $("#btnGuardar").attr("accion","editar");
      $("#btnGuardar").attr("id_rol",$(this).attr("id_rol"));
      $("#modalTitulo").html("Editar rol");
      fill_modal_insertar_actualizar($(this).attr("id_rol"));
      $("#modalInsertarActualizar").modal("show");
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
    var personal_tecnico_rol = {
      ACRONIMO:$("#txtClave").val(),
      ROL:$("#txtRol").val(),
      JERARQUIA:$("#txtJerarquia").val(),
      ID_USUARIO:sessionStorage.getItem("id_usuario")
    };
    $.post(global_apiserver + "/personal_tecnico_roles/insert/", JSON.stringify(personal_tecnico_rol), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
          $("#modalInsertarActualizar").modal("hide");
          notify_success("&Eacutexito", "Se ha insertado un nuevo registro");
          draw_tabla_roles();
          //document.location = "./?pagina=auditores";
        }
        //console.log("insertar: " + respuesta);
    });
  }

  function editar(){
    var personal_tecnico_rol = {
		ID:$("#txtID").val(),
		ACRONIMO:$("#txtClave").val(),
		ROL:$("#txtRol").val(),
		JERARQUIA:$("#txtJerarquia").val(),
		ID_USUARIO:sessionStorage.getItem("id_usuario")
    };
      $.post(global_apiserver + "/personal_tecnico_roles/update/", JSON.stringify(personal_tecnico_rol), function(respuesta){
          respuesta = JSON.parse(respuesta);
          if (respuesta.resultado == "ok") {
             $("#modalInsertarActualizar").modal("hide");
            notify_success("&Eacutexito", "Se han actualizado los datos");
            draw_tabla_roles();
            //document.location = "./?pagina=auditores";
          }
          console.log("insertar: " + respuesta);
      });
  }

   $( window ).load(function() {
      draw_tabla_roles();
      listener_btn_nuevo();
      listener_btn_guardar();
  });