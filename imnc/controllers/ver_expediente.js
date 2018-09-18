 $( window ).load(function() {

	Integral();
	botonetapascambio();
	botonciclocambio();
	listener_btn_guardar();		
	draw_chk_box();
	
});

//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
function Integral(){

var textoselect="Todas las etapas";
	$.getJSON( global_apiserver + "/ver_expedientes/getIntegralById/?id="+global_id_servicio_cliente_etapa, function( response ){
		//////////////////////////////////////////////////////////////
		//	PARA NOMBRE ETAPAS
		//////////////////////////////////////////////////////////////
		if(response[0].length!=1){
			$("#NombreEtapas").html('<option value="todas" selected="enable">'+textoselect+'</option>');
			$("#NombreEtapas").val("todas");
		}
		$.each(response[0], function( indice, objTserv ) {
			$("#NombreEtapas").append('<option value="'+objTserv.ETAPA+'">'+objTserv.ETAPA+' </option>');
			
		});
		//////////////////////////////////////////////////////////////
		//		PARA NOMBRE SERVICIO
		//////////////////////////////////////////////////////////////
		$("#Referencia").html(""+response[1].REFERENCIA);$("#Referencia").val(response[1].REFERENCIA);
		$("#Cliente").html(""+response[1].NOMBRE_CLIENTE);$("#Cliente").val(response[1].NOMBRE_CLIENTE);
        $("#Servicio").html(""+response[1].NOMBRE_SERVICIO);$("#Servicio").val(response[1].NOMBRE_SERVICIO);
		///////////////////////////////////////////////////////
		var cadena=response[1].REFERENCIA;
		var ciclo="";
		var ciclo1= new Array();
		var cadena1= new Array();
		cadena1=cadena.split("-");
		if(cadena1.length != 4){
			ciclo="C1"; // Esto ahi que arreglarlo pq siempre debe ser longitud 4
		}
		else{
			ciclo=cadena1[0];
		}
		ciclo1= ciclo.split("C");
		for(var i=0;i<ciclo1[1];i++){
			$("#NombreCiclo").append('<option value="'+(i+1)+'">C'+(i+1)+'</option>');
		}
		//////////////////////////////////////////////////////////////
		//		NOMBRE SECCIONES
		//////////////////////////////////////////////////////////////
		$("#chkbox").html("");
		$("#NombreSeccion").html("");
		$("#myTabContent").html("");
		$.each(response[2], function( indice, objTsecc ) {
			$("#NombreSeccion").append(draw_tab_secciones(objTsecc));
			$("#myTabContent").append(draw_tab_content(objTsecc));
			
		});
		$("#myTabContent").append(draw_tab_content1(response[2]));
		$("#chkbox").append(draw_chk_box());
		//////////////////////////////////////////////////////////////
		//			AHORA A BUSCAR TODOS DOCUMENTOS
		//////////////////////////////////////////////////////////////
		$("#tbodyDocumentos").html("");
			$.each(response[3], function( index, objDocum ) {
				$("#tbodyDocumentos").append(draw_row_documentos(objDocum)); 
					
			});
			
			listener_btn_revision();
			listener_btn_noaprobado();
			listener_btn_aprobado();
			listener_btn_cargar_documento();
			listener_btn_ver_documento();
			listener_btn_eliminar_documento();
			listener_chk_seccion_aprobada();
			listener_btn_planificar_tarea_documento();
			listener_chk_documento_noaplica();
		//////////////////////////////////////////////////////////////
	});
}
////////////////////////////////////////////////////////////////////// 	
/////////////////////////////////////////////
//  	
////////////////////////////////////////////
function draw_tab_secciones(objSeccion){
  var strHtml = "";
  	if(objSeccion.ID=="1"){
		$("#ValorSeccion").val(objSeccion.NOMBRE_SECCION);
		strHtml += "<li role='presentation' class='active' ><a href='#"+objSeccion.NOMBRE_SECCION+"' id='home-tab' role='tab' data-toggle='tab' aria-expanded='true' onClick='EVENTOCLICK("+objSeccion.NOMBRE_SECCION+")'>"+objSeccion.NOMBRE_SECCION+"</a>";
		}
	else
		strHtml += "<li role='presentation' class=''><a href='#"+objSeccion.NOMBRE_SECCION+"' role='tab' id='profile-tab' data-toggle='tab' aria-expanded='false' onClick='EVENTOCLICK("+objSeccion.NOMBRE_SECCION+")'>"+objSeccion.NOMBRE_SECCION+"</a>";
	strHtml += '</li>';
	
   return strHtml;
}
//////////////////////////////////////////////
function draw_tab_content(objSeccion){
  var strHtml = "";
	if(objSeccion.ID=="1"){
		strHtml += "<div role='tabpanel' class='tab-pane fade active in' id='"+objSeccion.NOMBRE_SECCION+"' aria-labelledby='profile-tab'>";
		
	}	
	else{
		strHtml += "<div role='tabpanel' class='tab-pane fade' id='"+objSeccion.NOMBRE_SECCION+"' aria-labelledby='profile-tab'>";
		
	}
		
	strHtml += "</div>";
   return strHtml;
}
///////////////////////////////////////////////
function draw_tab_content1(objSeccion){
 var strHtml = "";
	strHtml += "	<br>";
	strHtml += "<div class='checkbox' id='chkbox'>";
   
    strHtml += "</div>";
	strHtml += "<br>";
	strHtml += '				<p><h2>Documentos</h2></p>';
	strHtml += '<table class="table table-striped responsive-utilities jambo_table bulk_action">';
	strHtml += '		<thead>';
	strHtml += '			<tr class="headings">';
//	strHtml += '				<th class="column-title">Id</th>';
	strHtml += '				<th class="column-title" style="width: 300px;">Nombre</th>';
	strHtml += '				<th class="column-title" style="width: 300px;">Descripci&oacuten</th>';
	strHtml += '				<th class="column-title">Etapa</th>';
	strHtml += '				<th class="column-title">Seccion</th>';
	strHtml += '				<th class="column-title">Documento</th>';
	strHtml += '				<th class="column-title">Estado</th>';
	strHtml += '				<th class="column-title"></th>';
	strHtml += '				<th class="column-title"></th>';
	strHtml += '			</tr>';
	strHtml += '		</thead>';
	strHtml += '	<tbody id="tbodyDocumentos">';
	strHtml += '	</tbody>';
	strHtml += '</table>';
 return strHtml;
}
//////////////////////////////////////////////
function draw_chk_box(){
	var strHtml = "";
	if($("#NombreEtapas").val()!="todas"){
		$.getJSON(  global_apiserver + "/ver_expedientes/getestadoSeccion/?id="+global_id_servicio_cliente_etapa+"&nombre_etapa="+$("#NombreEtapas").val()+"&nombre_seccion="+$("#ValorSeccion").val()+"&nombre_ciclo="+$("#NombreCiclo").val(), function( response ) {
		if(response !=false)
			$("#chkSeccionAprobada").prop("checked", true);
		
	});	
		
		strHtml += "	<label class=''>";
		strHtml += "		<input type='checkbox' id='chkSeccionAprobada' value=''> Seccion Aprobada";
		strHtml += "    </label>";
		
	}
return strHtml;
}
//////////////////////////////////////////////
//////////////////////////////////////////

function draw_tabla_documentos(nombre_etapa,nombre_seccion,nombre_ciclo){
 $.getJSON(  global_apiserver + "/ver_expedientes/getDocumentosAll/?id="+global_id_servicio_cliente_etapa+"&nombre_etapa="+nombre_etapa+"&nombre_seccion="+nombre_seccion+"&nombre_ciclo="+nombre_ciclo, function( response ) {
      $("#tbodyDocumentos").html("");
	  $("#chkbox").html("");
	  $("#chkbox").append(draw_chk_box());
      $.each(response, function( index, objDocum ) {
        $("#tbodyDocumentos").append(draw_row_documentos(objDocum));  
      });
	    listener_chk_documento_noaplica();
		listener_btn_revision();
		listener_btn_noaprobado();
		listener_btn_aprobado();
		listener_btn_cargar_documento();
		listener_btn_ver_documento();
		listener_btn_eliminar_documento();
		listener_chk_seccion_aprobada();
		listener_btn_planificar_tarea_documento();
		
				
   });
}
//////////////////////////////////////////
function draw_row_documentos(objDocum){
  var text_bot="";
  var btn_class="";
  var strHtml = "";
  /////////////////////////////////////////////////
  /////////////////////////////////////////////////
  
  strHtml += '<tr class="even pointer">';
//  strHtml += '  <td>'+objDocum.ID+'</td>';
  strHtml += '  <td>'+objDocum.NOMBRE+'</td>';
  strHtml += '  <td>'+objDocum.DESCRIPCION+'</td>';
  strHtml += '  <td>'+objDocum.ETAPA+'</td>'; 
  strHtml += '  <td>'+objDocum.NOMBRE_SECCION+'</td>';
  
  if(objDocum.ESTADO=="No Aplica"){
	strHtml += "	<td>";
	strHtml += '<input type="checkbox" class="chkDocumentoNoAplica" value="" id_documento="'+objDocum.ID+'"  id_servicio="'+global_id_servicio_cliente_etapa+'" disabled=true checked=true> No Aplica';
	strHtml += '</td>';
	strHtml	+=	'<td>'+objDocum.ESTADO+'</td>';
	strHtml	+=	'<td></td>';
  }
  else{
 
  //BOTON EDITAR ESTADO DOCUMENTO
  if(objDocum.ESTADO!=""){
		strHtml += "	<td>";
		strHtml += '<input type="checkbox" class="chkDocumentoNoAplica" value="" id_documento="'+objDocum.ID+'"  id_servicio="'+global_id_servicio_cliente_etapa+'" disabled=true> No Aplica';
		strHtml += '</td>';
		strHtml += '  <td>';
		 if ((global_permisos["EXPEDIENTES"]["documentos"] == 1)) {
			if(objDocum.ESTADO!="Aprobado"){
				strHtml += '<div class="btn-group">';
				strHtml += '  <button type="button" class="btn btn-primary btn-xs btn-imnc " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
				strHtml += '  Estados   <span class="caret"></span>';
				strHtml += '    <span class="sr-only">Toggle Dropdown</span>';
				strHtml += '  </button>';
				strHtml += '  <ul class="dropdown-menu">';
				strHtml += '    <li class="btnRevision" id="'+objDocum.ID+'"> ';
				strHtml += '      <a> <span class="labelAcordeon">En Revision</span> </a>';
				strHtml += '    </li>';
				strHtml += '    <li class="btnNoAprobado" id="'+objDocum.ID+'"> ';
				strHtml += '      <a> <span class="labelAcordeon">No Aprobado</span></a>';
				strHtml += '    </li>';
				strHtml += '    <li class="btnAprobado" id="'+objDocum.ID+'"> ';
				strHtml += '     <a> <span class="labelAcordeon">Aprobado</span>  </a>';
				strHtml += '    </li>';
				strHtml += '  </ul>';
				strHtml += '  </div>';
				strHtml += '  <br>';
			}
				
		}
		strHtml	+=	objDocum.ESTADO+'</td>';
		text_bot="Abrir";
		btn_class="btnverdocumento";
  }
  else{
		 strHtml += "	<td>";
		strHtml += '<input type="checkbox" class="chkDocumentoNoAplica" value="" id_documento="'+objDocum.ID+'" id_servicio="'+global_id_servicio_cliente_etapa+'"> No Aplica';
		strHtml += '</td>';
		strHtml += '  <td> No se ha cargado</td>';
		text_bot="Cargar";
		btn_class="btncargardocumento";
 }
//BOTON Cargar Documentos
	
  strHtml += '  <td>';
  
  if (global_permisos["EXPEDIENTES"]["documentos"] == 1) {
	if(text_bot == "Cargar"){
		strHtml += '    <button type="button" class="btn btn-primary btn-xs btn-imnc '+btn_class+'"  id_documento="'+objDocum.ID+'" nombre_documento="'+objDocum.NOMBRE+'"  nombre_etapa="'+objDocum.ETAPA+'" id_servicio="'+global_id_servicio_cliente_etapa+'" style="float: right;"> ';
		strHtml += '      <i class="fa fa-edit"> </i>'+text_bot;
		strHtml += '    </button>';
	}
	else{
		strHtml += '<div class="btn-group">';
		strHtml += '  <button type="button" class="btn btn-primary btn-xs btn-imnc " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
		strHtml += '  Opciones   <span class="caret"></span>';
		strHtml += '    <span class="sr-only">Toggle Dropdown</span>';
		strHtml += '  </button>';
		strHtml += '  <ul class="dropdown-menu">';
		if(objDocum.ESTADO!="Aprobado"){
			strHtml += '    <li class="btncargardocumento" id_documento="'+objDocum.ID+'" nombre_documento="'+objDocum.NOMBRE+'"  nombre_etapa="'+objDocum.ETAPA+'" id_servicio="'+global_id_servicio_cliente_etapa+'"> ';
			strHtml += '      <a> <span class="labelAcordeon">Reemplazar Documento</span> </a>';
			strHtml += '    </li>';
			strHtml += '    <li class="btneliminardocumento" id_documento="'+objDocum.ID+'" nombre_documento="'+objDocum.NOMBRE+'"  nombre_etapa="'+objDocum.ETAPA+'" id_servicio="'+global_id_servicio_cliente_etapa+'"> ';
			strHtml += '      <a> <span class="labelAcordeon">Eliminar Documento</span></a>';
			strHtml += '    </li>';
		}
		strHtml += '    <li class="btnverdocumento" id_documento="'+objDocum.ID+'" nombre_documento="'+objDocum.NOMBRE+'"  nombre_etapa="'+objDocum.ETAPA+'" id_servicio="'+global_id_servicio_cliente_etapa+'"> ';
		strHtml += '     <a> <span class="labelAcordeon">Ver Documento</span>  </a>';
		strHtml += '    </li>';
	
		if(objDocum.ESTADO!="Aprobado"){
			strHtml += '    <li  > ';
			strHtml += '     <a href="./?pagina=calendario_documento&id='+global_id_servicio_cliente_etapa+'&id_docum='+objDocum.ID+'&ciclo='+$("#NombreCiclo").val()+'"> <span class="labelAcordeon" >Planificar Tarea</span>  </a>';
			strHtml += '    </li>';
		}
		strHtml += '  </ul>';
		strHtml += '  </div>';
		strHtml += '  <br>';
	
	}	
  }
 
  strHtml += '  </td>';
 } 
  strHtml += '</tr>';

  return strHtml;
}
///////////////////////////////////////////////////////////////////
function botonetapascambio(){
	$("#NombreEtapas").change(function() {
		draw_tabla_documentos($("#NombreEtapas").val(),$("#ValorSeccion").val(),$("#NombreCiclo").val());
		
  });
}
///////////////////////////////////////////////////////////////////
function botonciclocambio(){
	$("#NombreCiclo").change(function() {
		draw_tabla_documentos($("#NombreEtapas").val(),$("#ValorSeccion").val(),$("#NombreCiclo").val());
		
  });
}
///////////////////////////////////////////////////////////////////
function EVENTOCLICK(seccnomb){
	$("#ValorSeccion").val(seccnomb.id);	
	draw_tabla_documentos($("#NombreEtapas").val(),$("#ValorSeccion").val(),$("#NombreCiclo").val());
	
}
////////////////////////////////////////////////////////////////////
function listener_btn_revision(){
    $( ".btnRevision" ).click(function() {
      var id_catag_docum = $(this).attr("id");
      var objLabelAcordeon = $(this).find(".labelAcordeon");
	  var estado = "En Revision";
	  
	  editar_estado_documento(estado,id_catag_docum,$("#NombreCiclo").val());
    });
  }
function listener_btn_noaprobado(){
    $( ".btnNoAprobado" ).click(function() {
      var id_catag_docum = $(this).attr("id");
      var objLabelAcordeon = $(this).find(".labelAcordeon");
	  var estado = "No Aprobado";
	  
	  editar_estado_documento(estado,id_catag_docum,$("#NombreCiclo").val());
    });
}  
function listener_btn_aprobado(){
    $( ".btnAprobado" ).click(function() {
      var id_catag_docum = $(this).attr("id");
      var objLabelAcordeon = $(this).find(".labelAcordeon");
	  var estado = "Aprobado";
	  
	  editar_estado_documento(estado,id_catag_docum,$("#NombreCiclo").val());
    });
}
function listener_btn_cargar_documento(){
    $( ".btncargardocumento" ).click(function() {
	
		$("#btnGuardar").attr("accion","editar");
	   $("#txtIdServicio").val($(this).attr("id_servicio"));
	   $("#txtIdDocumento").val($(this).attr("id_documento"));
	   $("#txtNombreCiclo").val($("#NombreCiclo").val());
	   $("#txtNombreEtapa").val($(this).attr("nombre_etapa"));
	   $("#txtNombreSeccion").val($("#ValorSeccion").val());
	   $("#fileToUpload").val("");
	  $("#modalSubirArchivo").modal("show");
    });
}
function listener_btn_ver_documento(){
   $( ".btnverdocumento" ).click(function() {
		var abc = "";
		var direccion	=	"";
		var cadena		=	$("#Referencia").val();		
		var cadena1		=	cadena.split('-');
		direccion 		= global_apiserver+"/arch_expediente/"+cadena1[1]+cadena1[2]+"/"+$("#NombreCiclo").val()+"/"+$(this).attr("nombre_etapa")+"/"+$("#ValorSeccion").val()+"/"+$(this).attr("id_documento")+".pdf";
      
	     abc =	window.open( direccion);
		// abc.document.title="daniel";
	  
    });
}
function listener_btn_eliminar_documento(){
   $( ".btneliminardocumento" ).click(function() {
	$("#btnEliminar").attr("id_servicio",$(this).attr("id_servicio"));	
	$("#btnEliminar").attr("id_documento",$(this).attr("id_documento"));
//	$("#btnEliminar").attr("nombre_etapa",$(this).attr("nombre_etapa"));	
//	$("#btnEliminar").attr("nombre_seccion",$("#ValorSeccion").val());
	$("#btnEliminar").attr("nombre_ciclo",$("#NombreCiclo").val());	
	 $("#modalConfirmacion").modal("show");
	  
    });
}
function listener_btn_planificar_tarea_documento(){
   $( ".btnplanificartarea" ).click(function() {
	var aaaa="";
	alert("Daniel");
	  
    });
}

$( "#btnEliminar" ).click(function() {

    eliminar($("#btnEliminar").attr("id_servicio"),$("#btnEliminar").attr("id_documento"),$("#btnEliminar").attr("nombre_ciclo"));
    });
	
function eliminar(id_serv,id_docum,nomb_ciclo){
   $.post( global_apiserver + "/ver_expedientes/delete/?id_serv="+id_serv+"&id_docum="+id_docum+"&nomb_ciclo="+nomb_ciclo, function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
		
           $("#modalConfirmacion").modal("hide");
          notify("&Eacutexito", "Se han eliminado los datos", "success");
          draw_tabla_documentos($("#NombreEtapas").val(),$("#ValorSeccion").val(),$("#NombreCiclo").val());
          
        }
        else{
        notify("Error", respuesta.mensaje, "error");
      }
    });
////////////////////////////////////////////////////////////////////////////////////////////////////
 $.post( global_apiserver + "/cita_calendario_documentos/delete/?id_serv="+id_serv+"&id_docum="+id_docum+"&nomb_ciclo="+nomb_ciclo, function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
		
           $("#modalConfirmacion").modal("hide");
          notify("&Eacutexito", "Se han eliminado las tareas del documento", "success");
          draw_tabla_documentos($("#NombreEtapas").val(),$("#ValorSeccion").val(),$("#NombreCiclo").val());
          
        }
        else{
        notify("Error", respuesta.mensaje, "error");
      }
    });	
}	
///////////////////////////////////////////////////////////////////
//		FUNCION EDITAR ESTADO DOCUMENTO
//////////////////////////////////////////////////////////////////// 
  function editar_estado_documento(estado,id_catag_docum,nombre_ciclo){
  $.getJSON(  global_apiserver + "/ver_expedientes/getDocumentoById/?id="+global_id_servicio_cliente_etapa+"&id_catag_docum="+id_catag_docum+"&nombre_ciclo="+nombre_ciclo, function( response ) {
	
	var docum = {
		ID:response[0].ID,
		UBICACION_DOCUMENTOS:response[0].UBICACION_DOCUMENTOS,
		ID_CATALOGO_DOCUMENTOS:response[0].ID_CATALOGO_DOCUMENTOS,
		CICLO:response[0].CICLO,
		ID_SERVICIO:response[0].ID_SERVICIO,
		ESTADO_DOCUMENTO:estado,
		ID_USUARIO:sessionStorage.getItem("id_usuario")
	};
  
  	$.post( global_apiserver + "/ver_expedientes/update/", JSON.stringify(docum), function(respuesta){
          respuesta = JSON.parse(respuesta);
          if (respuesta.resultado == "ok") {
			notify("&Eacutexito", "Se ha actualizado el estado documento", "success");
            draw_tabla_documentos($("#NombreEtapas").val(),$("#ValorSeccion").val(),$("#NombreCiclo").val());
            //redraw_sectores();
          }
          else{
            notify("Error", respuesta.mensaje, "error");
          }
      });
	 }); 
 
  }
////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
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
//////////////////////////////////////////
function listener_btn_guardar(){
  $( "#btnGuardar" ).click(function() {
    if ($("#btnGuardar").attr("accion") == "insertar")
    {
      //insertar();
    }
    else if ($("#btnGuardar").attr("accion") == "editar")
    {
		upload_file();
		$("#modalSubirArchivo").modal("hide");
    }
  });
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function upload_file(){//Funcion encargada de enviar el archivo via AJAX
				
		var inputFile = document.getElementById("fileToUpload");
		if(inputFile.value != ""){
				var file = inputFile.files[0];
				var data = new FormData();
				data.append('fileToUpload',file);
				data.append('Referencia',$("#Referencia").val());
				data.append('IdServicio',$("#txtIdServicio").val());
				data.append('IdDocumento',$("#txtIdDocumento").val());
				data.append('NombreCiclo',$("#txtNombreCiclo").val());
				data.append('NombreEtapa',$("#txtNombreEtapa").val());
				data.append('NombreSeccion',$("#txtNombreSeccion").val());
				data.append('ID_USUARIO',sessionStorage.getItem("id_usuario"));
				
											
				$.ajax({
					url: global_apiserver + "/ver_expedientes/upload/upload.php",        // Url to which the request is send
					type: "POST",             // Type of request to be send, called as method
					data: data, 			  // Data sent to server, a set of key/value pairs (i.e. form fields and values)
					contentType: false,       // The content type used when sending data to the server.
					cache: false,             // To unable request pages to be cached
					processData:false,        // To send DOMDocument or non processed data file it is set to false
					success: function(data)   // A function to be called if request succeeds
					{
						 data = JSON.parse(data);
						if (data.resultado == "ok") {
							notify("&Eacutexito", data.nota, "success");
							draw_tabla_documentos($("#NombreEtapas").val(),$("#ValorSeccion").val(),$("#NombreCiclo").val());
						}
						else{
							notify("Error", data.nota, "error");
						}
						draw_tabla_documentos($("#NombreEtapas").val(),$("#ValorSeccion").val(),$("#NombreCiclo").val());
						
					}
				});
		}
		else{
			notify("Notificacion", "No se selecciono ningun archivo para subir", "success");
		}	
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//chkDocumentoNoAplica
 function listener_chk_documento_noaplica(){
    $('.chkDocumentoNoAplica').change(function() {
        if($(this).is(":checked")) {
			$("#chkDocumentoNoAplica").val("No Aplica");
			//$(this).prop("checked", false);
			$("#btnNoAplica").attr("id_servicio",$(this).attr("id_servicio"));	
			$("#btnNoAplica").attr("id_documento",$(this).attr("id_documento"));
			$("#btnNoAplica").attr("nombre_ciclo",$("#NombreCiclo").val());	
			
			$("#modalConfirmacionNoAplica").modal("show");
			
        }
       
		//insertar_actualizar_estado_documento(id_catag_docum,id_serv,ciclo,estado);	
    });
  }
 ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
$( "#btnNoAplica" ).click(function() {

    insertar_actualizar_estado_documento($("#btnNoAplica").attr("id_servicio"),$("#btnNoAplica").attr("id_documento"),$("#btnNoAplica").attr("nombre_ciclo"),"No Aplica");
 }); 
 ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function insertar_actualizar_estado_documento(id_servicio,id_docum,ciclo,estado){

	var docum = {
	//	ID:response[0].ID,
		UBICACION_DOCUMENTOS:"",
		ID_CATALOGO_DOCUMENTOS:id_docum,
		CICLO:ciclo,
		ID_SERVICIO:id_servicio,
		ESTADO_DOCUMENTO:estado,
		ID_USUARIO:sessionStorage.getItem("id_usuario")
	};
  
  	$.post( global_apiserver + "/ver_expedientes/insert/", JSON.stringify(docum), function(respuesta){
          respuesta = JSON.parse(respuesta);
          if (respuesta.resultado == "ok") {
			$("#modalConfirmacionNoAplica").modal("hide");
			//$(this).prop("checked", true);
			notify("&Eacutexito", "Se ha insertado el estado documento", "success");
            draw_tabla_documentos($("#NombreEtapas").val(),$("#ValorSeccion").val(),$("#NombreCiclo").val());
            //redraw_sectores();
          }
          else{
            notify("Error", respuesta.mensaje, "error");
          }
      });

	
	}  
//chkSeccionAprobada
 function listener_chk_seccion_aprobada(){
    $('#chkSeccionAprobada').change(function() {
        if($(this).is(":checked")) {
			$("#chkSeccionAprobada").val("Aprobada");					
        }
        else{
			$("#chkSeccionAprobada").val("No Aprobada");				
        }
		insertar_actualizar_estado_seccion();	
    });
  }


function insertar_actualizar_estado_seccion(){

	var estado_secc = {
		
		SECCION:$("#ValorSeccion").val(),
		ETAPA:$("#NombreEtapas").val(),
		CICLO:$("#NombreCiclo").val(),
		ID_SERVICIO:global_id_servicio_cliente_etapa,
		ESTADO_SECCION:$("#chkSeccionAprobada").val()
		
	};
  
  	$.post( global_apiserver + "/ver_expedientes/estadoSeccion/", JSON.stringify(estado_secc), function(respuesta){
          respuesta = JSON.parse(respuesta);
          if (respuesta.resultado == "ok") {
			notify("&Eacutexito", "Se ha actualizado el estado de la seccion", "success");
            
          }
          else{
            notify("Error", respuesta.mensaje, "error");
          }
      });

	
	}
	