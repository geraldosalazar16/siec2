/**
 * @ngdoc controller
 * @name controller:cursos_programados
 *
 * @description
 *
 *
 * @requires $scope
 * */
app.controller('cursos_programados_controller',['$scope','$http',function($scope,$http){

    $scope.formData = {};
    $scope.formDataParticipante = {};
    $scope.global_calendar;
    $scope.id_evento_select = '';
    $scope.date_evento_select = '';
    $scope.selectedInst = '';
    $scope.instructoresCursos=[];
    $scope.id_instructor = "";
    $scope.objCursoProgramado = [];
    $scope.participantes = [];
    $scope.salir = false;
    $scope.cantidad_participante = "";



// ===================================================================
// ***** 			FUNCION PARA CARGAR LOS INSTRUCTORES    	 *****
// ===================================================================
    $scope.cargarInstructores= function(seletcCurso){

        var option = "no";
        if($scope.formData.chckVerTodos)
            option = "si";
		$http.get(global_apiserver + "/cursos_programados/getInstructores/?id="+seletcCurso+"&option="+option)
		.then(function( response ){
			$scope.instructoresCursos = response.data;
		});
	}



// ===================================================================
// ***** 			FUNCION PARA CARGAR LOS CURSOS				 *****
// ===================================================================
    $scope.cargarCursos = function(){
		$http.get(  global_apiserver + "/cursos/getAllHabilitados/")
		.then(function( response ){
			$scope.cursos = response.data;
		});
	}
// ===================================================================
// ***** 			FUNCION PARA CARGAR HISTORICO    	 *****
// ===================================================================
    $scope.cargarHistorico= function(id){
        if(id)
        {
            $http.get(global_apiserver + "/cursos_programados/getHistoricoById/?id="+id)
                .then(function( response ){
                    $scope.Historial = response.data;
                });
        }
        else
        {
            $http.get(global_apiserver + "/cursos_programados/getHistoricoEliminados/")
                .then(function( response ){
                    $scope.Historial = response.data;
                });
        }

    }
// ==============================================================================
// ***** 	Funcion para traer las etapas	*****
// ==============================================================================
    $scope.cargarEtapas =function (id_servicio,seleccion ){
        var inicial = null;
        $http.get(  global_apiserver + "/etapas_proceso/getByIdServicio/?id="+id_servicio)
            .then(function( response ) {//se ejecuta cuando la petición fue correcta
                    $scope.Etapas = response.data.map(function(item){
                        if(item.ETAPA=="INSCRITO")
                            inicial = item.ID_ETAPA;
                        return{
                            ID : item.ID_ETAPA,
                            NOMBRE : item.ETAPA
                        }
                    });

                    if(inicial){
                        $scope.formData.selectEtapa = seleccion ? seleccion: inicial;
                    }
                },
                function (response){});
    }
// ===================================================================
// ***** 			FUNCION PARA ELIMINAR EVENTO				 *****
// ===================================================================
    $scope.eliminaEvento = function(){
        if(confirm("¿Estás seguro que desea eliminar este evento?"))
        {

            var curso = { ID: $scope.id_evento_select, ID_USUARIO:sessionStorage.getItem("id_usuario")}
            $.post( global_apiserver + "/cursos_programados/delete/", JSON.stringify(curso), function(respuesta){
                respuesta = JSON.parse(respuesta);
                if (respuesta.resultado == "ok") {
                    $("#modalMostrar").modal("hide");
                    notify("Éxito", "Se ha eliminado el evento", "success");
                    $scope.onAgenda();
                    //document.location = "./?pagina=auditores";
                }
                else{
                    notify("Error", respuesta.mensaje, "error");
                }

            });
        }else{

        }
    }

// =======================================================================================
// ***** 			FUNCION PARA ACTUALIZAR DATOS MODAL MOSTRAR		                 *****
// =======================================================================================
    $scope.actualizaDatos = function() {
        $.getJSON(global_apiserver + "/cursos_programados/getById/?id=" + $scope.id_evento_select, function (response) {
            var dias = parseInt(response.DIAS)+1;
            if (response.DIAS == 0)
                dias = dias+' día ';
            else
                dias = dias + ' días ';
            $scope.txtDuracion = dias;
            $scope.txtCurso = response.NOMBRE_CURSO;
            $scope.txtReferencia=response.REFERENCIA;
            $scope.txtInstructor = response.NOMBRE_AUDITOR.NOMBRE + " " + response.NOMBRE_AUDITOR.APELLIDO_PATERNO + " " + response.NOMBRE_AUDITOR.APELLIDO_MATERNO;
            var fechas = response.FECHA_INICIO + " - " + response.FECHA_FIN;
            if (response.FECHA_INICIO == response.FECHA_FIN)
                fechas = response.FECHA_INICIO;
            $scope.txtFechas = fechas;
            $scope.txtMinimo = response.PERSONAS_MINIMO;
            $scope.txtEtapa = response.NOMBRE_ETAPA;
            $scope.cantidad_participante = response.CANTIDAD_PARTICIPANTES,
            $scope.$apply();

        });
        if ($scope.accion == "editar")
        {
            $scope.mytoggle("divMostrar");
            $scope.mytoggle("divInsertar");
        }


        if ($scope.accion == "mostrar")
        {
            $("#divInsertar").hide();
            $("#divInstructor").hide();
            $("#divVerHistorico").hide();
            $("#divVerParticipantes").hide();
            $("#divVerInsertParticipantes").hide();
            $("#divMostrar").show();
        }



        //$("#divInsertar").hide();
        //$("#divMostrar").show();
    }
// =======================================================================================
// ***** 			FUNCION PARA ABRIR MODAL MOSTRAR		                         *****
// =======================================================================================
    $scope.openModalMostar = function(){
        $scope.accion = "mostrar";
        $scope.actualizaDatos();
        $("#modalMostrar").modal("show");

    }
// =======================================================================================
// ***** 			FUNCION PARA ABRIR MODAL INSERTAR/MODIFICAR		 *****
// =======================================================================================
$scope.openModalInsertarModificar = function(accion){
        $scope.modal_titulo = "Agregar Curso";
		$scope.accion = accion;
        clear_modal_insertar_actualizar()
        $scope.cargarCursos();

        $scope.formData.fecha_inicio = $scope.date_evento_select;
        $scope.enVerde = false;
        if(accion == 'insertar')
        {
            $scope.cargarEtapas(3);
            onCalendario();
        }
        if(accion == 'editar')
        {

            $scope.modal_titulo = "Modificando Curso";
            $.getJSON( global_apiserver + "/cursos_programados/getById/?id="+$scope.id_evento_select, function( response ) {
                $scope.objCursoProgramado = response;
                $scope.formData.selectCurso = response.ID_CURSO;
                $scope.formData.instructor = response.ID_INSTRUCTOR;
                $scope.formData.fecha_inicio = response.FECHA_INICIO;
                $scope.formData.fecha_fin = response.FECHA_FIN;
                $scope.formData.minimo = response.PERSONAS_MINIMO;
                $scope.id_instructor = response.ID_INSTRUCTOR;
                $scope.formData.referencia = response.REFERENCIA;
                $("#btnInstructor").attr("value",response.NOMBRE_AUDITOR.NOMBRE+" "+response.NOMBRE_AUDITOR.APELLIDO_PATERNO+" "+response.NOMBRE_AUDITOR.APELLIDO_MATERNO)
                $("#btnInstructor").attr("class", "form-control ");
                if(parseInt(response.CANTIDAD_PARTICIPANTES_REAL) == parseInt(response.PERSONAS_MINIMO))
                $scope.enVerde = true;
                $scope.cargarEtapas(3,response.ETAPA);
                onCalendario(response.FECHA_INICIO);
                // $scope.formData.selectEtapa = parseInt(response.ETAPA);
                $scope.$apply();


            });

            $scope.mytoggle("divMostrar");
            $scope.mytoggle("divInsertar");
            /*$("#divMostrar").hide();
            $("#divInsertar").show();*/


        }
        else
        {
            generar_referencia_cifa();
            $("#divInsertar").show();
            $("#divMostrar").hide();
            $("#divInstructor").hide();
            $("#divVerHistorico").hide();
            $("#modalMostrar").modal("show");
        }




	}

// =======================================================================================
// ***** 			FUNCION PARA ABRIR MODAL MOSTRAR INSTRUCTOR             		 *****
// =======================================================================================
$scope.openModalMostarInst = function() {
    $scope.formData.searchText = "";
    if (typeof $scope.formData.selectCurso !== "undefined") {
        $("#txtinstructorerror").text("");
        if ($scope.formData.selectCurso.length != 0 && $scope.formData.fecha_inicio.length != 0 && $scope.formData.fecha_fin.length != 0) {
            $.getJSON(global_apiserver + "/cursos/getById/?id="+ $scope.formData.selectCurso, function (response) {
                $scope.id_curso = response.ID_CURSO;
                $scope.nombre_curso = response.NOMBRE;

            })
            $("#modal-size").attr("class","modal-dialog modal-lg");
            $scope.mytoggle("divInstructor")
            $scope.mytoggle("divInsertar");

            $scope.cargarInstructores($scope.formData.selectCurso);
            $("#txtinstructorerror").text("");
        }
        else {
            $("#txtinstructorerror").text("Debe seleccionar un curso y las fechas");
        }
    }
    else {
        $("#txtinstructorerror").text("Debe seleccionar un curso");
    }


}
// =======================================================================================
// ***** 			FUNCION PARA ABRIR MODAL MOSTRAR Participantes             		 *****
// =======================================================================================
    $scope.openModalParticipantes = function() {
        $scope.cargaParticipantes();
        $("#modal-size").attr("class","modal-dialog modal-lg");
        $scope.mytoggle("divMostrar");
        $scope.mytoggle("divVerParticipantes");

    }
// =======================================================================================
// ***** 			FUNCION PARA ABRIR MODAL MOSTRAR Agregar Participantes             		 *****
// =======================================================================================
    $scope.openModalInsertParticipantes = function(key) {
        if(esDespuesHoy($scope.date_evento_select)) {
            $scope.titulo_participante_modal = "Agregar Participante";
            $("#modal-size").attr("class", "modal-dialog");
            $scope.cargarEstados();
            $scope.cargarClientes();
            $scope.mytoggle("divVerParticipantes");
            $scope.mytoggle("divVerInsertParticipantes");
            if (typeof key !== "undefined") {
                $scope.titulo_participante_modal = "Editar Participante";
                $scope.accion_p = 'editar';
                $scope.id_participante  = $scope.participantes[key].ID;;
                $scope.formDataParticipante.nombre_participante = $scope.participantes[key].NOMBRE;
                $scope.formDataParticipante.email_participante = $scope.participantes[key].EMAIL;
                $scope.formDataParticipante.telefono_participante = $scope.participantes[key].TELEFONO;
                $scope.formDataParticipante.curp_participante = $scope.participantes[key].CURP;
                $scope.formDataParticipante.perfil_participante = $scope.participantes[key].PERFIL;
                $scope.formDataParticipante.estado_participante = $scope.participantes[key].ID_ESTADO;
                if($scope.participantes[key].ID_CLIENTE!=0)
                {
                    $scope.formDataParticipante.tiene_cliente = true;
                    $scope.formDataParticipante.select_cliente = $scope.participantes[key].ID_CLIENTE;


                }

            }
        } else{
            notify("Error", "No puede agregar un participante a un curso con fecha menor que la actual", "error");
        }



    }
// =======================================================================================
// ***** 			FUNCION PARA ABRIR MODAL MOSTRAR HISTORICOS             		 *****
// =======================================================================================
    $scope.openModalHistorico = function() {
        $("#modal-size").attr("class","modal-dialog modal-lg");
        $scope.mytoggle("divMostrar");
        $scope.mytoggle("divVerHistorico");
        $scope.cargarHistorico($scope.id_evento_select);
    }
// =======================================================================================
// ***** 			FUNCION PARA ABRIR MODAL MOSTRAR HISTORICOS             		 *****
// =======================================================================================
    $scope.openModalHistoricoEliminados = function() {
        $("#modal-size").attr("class","modal-dialog modal-lg");
        $("#divInsertar").hide();
        $("#divMostrar").hide();
        $("#divInstructor").hide();
        $("#divVerHistorico").show();
        $("#modalMostrar").modal("show");
        $scope.cargarHistorico();
        $scope.salir = true;
    }
// ===========================================================================
// ***** 		      Funcion button select instructor 	            	 *****
// ===========================================================================
$scope.onSelectInstructor = function(instructor)
{
    var validar = {
        ID:		          	        instructor,
        FECHAS:			            $scope.formData.fecha_inicio+","+$scope.formData.fecha_fin
    };
    $("#btn-"+instructor).attr("disabled",true);
    $("#btn-"+instructor).text("verificando...");
    $.post( global_apiserver + "/personal_tecnico/isDisponible/", JSON.stringify(validar), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.disponible == "si") {

            $scope.id_instructor = instructor;
            $("#btnInstructor").attr("value", $("#lb-"+instructor).val());
            $("#btnInstructor").attr("class", "form-control btn btn-primary");
            $("#btn-"+instructor).attr("disabled",false);
            $("#btn-"+instructor).text("seleccionar");
            $scope.cerrarInstructores();
        }
        else
        {
            if (respuesta.disponible == "no")
            {
                    notify("Error", respuesta.razon, "error");
                    $("#btn-"+instructor).attr("disabled",true);
                    $("#error-"+instructor).text(respuesta.razon);
                    $("#error-"+instructor).show();
                    $("#btn-"+instructor).text("seleccionar");

            }else
            {
                notify("Error", respuesta.mensaje, "error");
                $("#btn-"+instructor).attr("disabled",false);
                $("#btn-"+instructor).text("seleccionar");
            }

        }
    })
}
$scope.cerrarInstructores = function()
{
    $("#modal-size").attr("class","modal-dialog");
    $scope.mytoggle("divInsertar");
    $scope.mytoggle("divInstructor");
}
$scope.cerrarInsertParticipante = function()
{
    $('#modal-size').attr('class','modal-dialog modal-lg');
    $scope.mytoggle("divVerInsertParticipantes");
    $scope.mytoggle("divVerParticipantes");
    clear_form_participante();

}


$scope.cerrarHistorico = function()
{
    $("#modal-size").attr("class","modal-dialog");
    if(!$scope.salir)
    {

        $scope.mytoggle("divVerHistorico");
        $scope.mytoggle("divMostrar");
    }
    else {
        $scope.salir = false;
        $("#modalMostrar").modal("hide");
    }

}

$scope.cerrar = function(div)
{
    $("#modal-size").attr("class","modal-dialog");


        $scope.mytoggle(div);
        $scope.mytoggle("divMostrar");



}

// ===========================================================================
// ***** 		Funcion para limpiar las variables del modal			 *****
// ===========================================================================
function clear_modal_insertar_actualizar(){
    $scope.formData.selectCurso = '';
    $scope.formData.instructor = '';
    $scope.formData.minimo = '';
    $scope.formData.fecha_inicio = '';
    $scope.formData.fecha_fin = '';
    $scope.selectedInst = '';
    $scope.id_instructor = "";
    $scope.formData.referencia ="";
    $scope.formData.chckVerTodos = "";
    //$scope.formData.selectEtapa = "INSCRITO";
    $("#btnInstructor").attr("value","Selecciona un Instructor");
    $("#btnInstructor").attr("class", "form-control btn ");

    $("#txtcursoerror").text("");
    $("#txtinstructorerror").text("");
    $("#txtminimoerror").text("");
    $("#referenciaerror").text("");
    $("#fechainicioerror").text("");
    $("#fechafinerror").text("");

	}


// ===========================================================================
// ***** 		Funcion llamada cuando se selec un curso del modal			 *****
// ===========================================================================
    $scope.soloNumeros = function(e){
        var key = e.charCode;
        return key>= 48 && key <=57;
    }
// =======================================================================================
// *****               Función para eliminar espacios a una cadena          		 *****
// =======================================================================================
    function eliminaEspacios(cadena)
    {
        // Funcion equivalente a trim en PHP
        var x=0, y=cadena.length-1;
        while(cadena.charAt(x)==" ") x++;
        while(cadena.charAt(y)==" ") y--;
        return cadena.substr(x, y-x+1);
    }
// =======================================================================================
// *****               Función para observar el campo del formulario         		 *****
// =======================================================================================
    $scope.$watch('formData.minimo',function(nuevo, anterior) {
        if(!nuevo)return;
        if(!$scope.validar_numeros())
            $scope.formData.minimo = anterior;
    })

// =======================================================================================
// *****               Función para validar que entren solo numeros         		 *****
// =======================================================================================
 $scope.validar_numeros = function()
{
    var valor = $scope.formData.minimo;
    valor = eliminaEspacios(valor);
    reg=/(^[0-9]{1,4}$)/;
    if(!reg.test(valor))
    {
        $scope.formData.minimo = "";
        // Si hay error muestro el div que contiene el error
        $("#minimo").focus();
        return false;
    }
    else
        return true;
}

// =======================================================================================
// *****     Función para validar los campos del formulario antes de Guardar		 *****
// =======================================================================================
function validar_formulario()
{
 $scope.respuesta =  1;

    if ($scope.formData.referencia.length == 0) {
        $scope.respuesta = 0;
        $("#referenciaerror").text("No debe estar vacio");
    } else {
        $("#referenciaerror").text("");
    }


if(typeof $scope.formData.selectCurso !== "undefined") {
    $("#txtcursoerror").text("");
    if ($scope.formData.selectCurso.length == 0) {
        $scope.respuesta = 0;
        $("#txtcursoerror").text("No debe estar vacio");
    } else {
        $("#txtcursoerror").text("");
    }
}else {
    $scope.respuesta = 0;
    $("#txtcursoerror").text("No debe estar vacio");
}


    if ($scope.id_instructor.length == 0) {
        $scope.respuesta = 0;
        $("#txtinstructorerror").text("No debe estar vacio");
    } else {
        $("#txtinstructorerror").text("");
    }


 if(typeof $scope.formData.fecha_inicio !== "undefined")
    {
      $("#fechainicioerror").text("");
 if($scope.formData.fecha_inicio.length == 0){
      $scope.respuesta =  0;
      $("#fechainicioerror").text("No debe estar vacio");
    }else{
      $("#fechainicioerror").text("");
    }
    }else{
      $scope.respuesta =  0;
      $("#fechainicioerror").text("No debe estar vacio");
    }

 if(typeof $scope.formData.fecha_fin !== "undefined")
      {
        $("#fechafinerror").text("");
 if($scope.formData.fecha_fin.length == 0){
      $scope.respuesta =  0;
      $("#fechafinerror").text("No debe estar vacío");
    }else{
      $("#fechafinerror").text("");
    }
    }else{
          $scope.respuesta =  0;
          $("#fechafinerror").text("No debe estar vacío");
        }

if(typeof $scope.formData.minimo !== "undefined") {
    $("#txtminimoerror").text("");
    if ($scope.formData.minimo.length == 0 || $scope.formData.minimo <= 0) {
        $scope.respuesta = 0;
        $("#txtminimoerror").text("No debe estar vacío");
    } else {
        $("#txtminimoerror").text("");
    }
}else {
    $scope.respuesta =  0;
    $("#txtminimoerror").text("Debe introducir un número");
}

 if(Boolean($scope.formData.fecha_inicio) && Boolean($scope.formData.fecha_fin)){
  var aux_fechainicio = stringToDate($scope.formData.fecha_inicio,"dd/mm/yyyy","/");
  var aux_fechafin = stringToDate($scope.formData.fecha_fin,"dd/mm/yyyy","/");
  if(aux_fechainicio>aux_fechafin)
  {
   $scope.respuesta =  0;
   $("#fechainicioerror").text("Esta fecha no puede ser mayor que la final");
  }else{
   $("#fechainicioerror").text("");
  }
  }

}
    function stringToDate(_date,_format,_delimiter)
    {
        var formatLowerCase=_format.toLowerCase();
        var formatItems=formatLowerCase.split(_delimiter);
        var dateItems=_date.split(_delimiter);
        var monthIndex=formatItems.indexOf("mm");
        var dayIndex=formatItems.indexOf("dd");
        var yearIndex=formatItems.indexOf("yyyy");
        var month=parseInt(dateItems[monthIndex]);
        month-=1;
        var formatedDate = new Date(dateItems[yearIndex],month,dateItems[dayIndex]);
        return formatedDate;
    }
// ===========================================================================
// ***** 			FUNCION PARA EL BOTON GUARDAR DEL MODAL				 *****
// ===========================================================================
	$scope.submitForm = function (formData) {

            validar_formulario();
      		if($scope.respuesta == 1){
      		    if($scope.accion == "insertar")
                {
                    insertar(formData);
                }
                if($scope.accion == "editar")
                {
                    $scope.validaEditar(formData);
                }


      		}
	}
// ===========================================================================
// ***** 			FUNCION PARA INSERTAR UNA PROGRAMACION				 *****
// ===========================================================================
function insertar(formData) {

    var validar = {
        ID:		          	        $scope.id_instructor,
        FECHAS:			            formData.fecha_inicio+","+formData.fecha_fin
    };
    $.post( global_apiserver + "/personal_tecnico/isDisponible/", JSON.stringify(validar), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.disponible == "si") {
            var curso = {
                ID_CURSO: formData.selectCurso,
                NOMBRE_CURSO:$scope.NombreCurso(formData.selectCurso),
                FECHAS: formData.fecha_inicio + "-" + formData.fecha_fin,
                ID_INSTRUCTOR: $scope.id_instructor,
                NOMBRE_INSTRUCTOR:$("#btnInstructor").val(),
                PERSONAS_MINIMO: formData.minimo,
                REFERENCIA:formData.referencia,
                ETAPA: formData.selectEtapa,
                ID_USUARIO:sessionStorage.getItem("id_usuario")
            };

            $.post(global_apiserver + "/cursos_programados/insert/", JSON.stringify(curso), function (respuesta) {
                respuesta = JSON.parse(respuesta);
                if (respuesta.resultado == "ok") {
                    $("#modalMostrar").modal("hide");
                    notify("Éxito", "Se ha insertado un nuevo evento", "success");
                    $scope.onAgenda(formData.fecha_inicio);
                   // irFechaCalendario(formData.fecha_inicio);
                    //document.location = "./?pagina=auditores";
                }
                else {
                    notify("Error", respuesta.mensaje, "error");
                }

            });

        }
        else
        {
            if (respuesta.disponible == "no")
            {

                notify("Error", respuesta.razon, "error");
                return false;

            }else
            {
                notify("Error", respuesta.mensaje, "error");
                return false;
            }

        }
    })
    $scope.date_evento_select = "";
}

// ===========================================================================
// ***** 		       Validar editar programación             			 *****
// ===========================================================================
    $scope.validaEditar = function(formData)
    {

        if($scope.objCursoProgramado.ID_INSTRUCTOR == $scope.id_instructor && $scope.objCursoProgramado.ID_CURSO == formData.selectCurso && $scope.objCursoProgramado.FECHA_INICIO == formData.fecha_inicio && $scope.objCursoProgramado.FECHA_FIN == formData.fecha_fin)
        {
            editar(formData);
        }else {
        var validar = {
            ID:		          	        $scope.id_instructor,
            FECHAS:			            formData.fecha_inicio+","+formData.fecha_fin,
            ID_CURSO_PROGRAMADO:        $scope.id_evento_select
        };
        $.post( global_apiserver + "/personal_tecnico/isDisponible/", JSON.stringify(validar), function(respuesta){
            respuesta = JSON.parse(respuesta);
            if (respuesta.disponible == "si") {

                editar(formData);
            }
            else
            {
                if (respuesta.disponible == "no")
                {
                    if(respuesta.id == $scope.id_evento_select)
                    {
                        editar(formData);
                    }
                    else {
                        notify("Error", respuesta.razon, "error");
                        return false;
                    }


                }else
                {
                    notify("Error", respuesta.mensaje, "error");
                    return false;
                }

            }
        })
        }
    }


// ===========================================================================
// ***** 			FUNCION PARA EDITAR UNA PROGRAMACION				 *****
// ===========================================================================
    function editar(formData) {

            var curso = {
                ID: $scope.id_evento_select,
                ID_CURSO: formData.selectCurso,
                NOMBRE_CURSO:$scope.NombreCurso(formData.selectCurso),
                FECHAS: formData.fecha_inicio + "-" + formData.fecha_fin,
                ID_INSTRUCTOR: $scope.id_instructor,
                NOMBRE_INSTRUCTOR:$("#btnInstructor").val(),
                PERSONAS_MINIMO: formData.minimo,
                ETAPA:formData.selectEtapa,
                ID_USUARIO:sessionStorage.getItem("id_usuario")
            };
            $.post(global_apiserver + "/cursos_programados/update/", JSON.stringify(curso), function (respuesta) {
                respuesta = JSON.parse(respuesta);
                if (respuesta.resultado == "ok") {
                    //$("#modalMostrar").modal("hide");
                    $scope.actualizaDatos();
                    notify("Éxito", "Se ha modificado el evento", "success");
                    $scope.onAgenda(formData.fecha_inicio);
                    //document.location = "./?pagina=auditores";
                }
                else {
                    notify("Error", respuesta.mensaje, "error");
                }

            });

        $scope.objCursoProgramado = [];
    }
// =======================================================================================================
// *****   Funcion para limpiar las variables del formulario INSERTAR PARTICIPANTES			 *****
// =======================================================================================================
    function clear_form_participante(){
        $scope.formDataParticipante.nombre_participante = '';
        $scope.formDataParticipante.email_participante = '';
        $scope.formDataParticipante.telefono_participante = '';
        $scope.formDataParticipante.curp_participante = "";
        $scope.formDataParticipante.perfil_participante = "";
        $scope.formDataParticipante.estado_participante = "";
        $scope.formDataParticipante.select_cliente = "";
        $scope.formDataParticipante.tiene_cliente = false;
        $scope.error_nombre_participante = "";
        $scope.error_email_participante = "";
        $scope.error_telefono_participante = "";
        $scope.error_curp_participante = "";
        $scope.error_perfil_participante = "";
        $scope.error_tiene_cliente = "";
    }

// =======================================================================================
// *****               Función para validar que entren solo numeros         		 *****
// =======================================================================================
    $scope.validar_telefono = function (telefono)
    {
        var caract = new RegExp(/(^[0-9]{1,10}$)/);

        if (caract.test(telefono) == false){
            return false;
        }else{
            return true;
        }
    }
// =======================================================================================
// *****               Función para validar que entren solo numeros         		 *****
// =======================================================================================
    $scope.validar_email = function (email)
    {
        var caract = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);

        if (caract.test(email) == false){
            return false;
        }else{
            return true;
        }
    }
// =======================================================================================
// *****                       Función para validar CURP                    		 *****
// =======================================================================================
    $scope.curpValida = function(input) {
        var curp = input;
        var re = /^([A-Z][AEIOUX][A-Z]{2}\d{2}(?:0\d|1[0-2])(?:[0-2]\d|3[01])[HM](?:AS|B[CS]|C[CLMSH]|D[FG]|G[TR]|HG|JC|M[CNS]|N[ETL]|OC|PL|Q[TR]|S[PLR]|T[CSL]|VZ|YN|ZS)[B-DF-HJ-NP-TV-Z]{3}[A-Z\d])(\d)$/,
            validado = curp.match(re);

        if (!validado)  //Coincide con el formato general?
            return false;

        //Validar que coincida el dígito verificador
        function digitoVerificador(curp17) {
            //Fuente https://consultas.curp.gob.mx/CurpSP/
            var diccionario  = "0123456789ABCDEFGHIJKLMNÑOPQRSTUVWXYZ",
                lngSuma      = 0.0,
                lngDigito    = 0.0;
            for(var i=0; i<17; i++)
                lngSuma= lngSuma + diccionario.indexOf(curp17.charAt(i)) * (18 - i);
            lngDigito = 10 - lngSuma % 10;
            if(lngDigito == 10)
                return 0;
            return lngDigito;
        }
        if (validado[2] != digitoVerificador(validado[1]))
            return false;

        return true; //Validado
    }
// =======================================================================================================
// *****    Accion al presionar button agregar participantes		 *****
// =======================================================================================================
    $scope.cargaParticipantes = function()
    {
        $.get(global_apiserver + "/cursos_programados/getAllParticipante?id="+$scope.id_evento_select, function (respuesta) {
            respuesta = JSON.parse(respuesta);
            $scope.participantes = respuesta;
            $scope.cantidad_insertados = respuesta.length;
            $scope.$apply();
        });

    }
// ===================================================================
// ***** 	  FUNCION PARA CARGAR LOS CLIENTES_CURSO_PROGRAMADo	 *****
// ===================================================================
    $scope.cargarClientes = function(){
        $.get(global_apiserver + "/cursos_programados/getAllClienteByIdCurso/?id="+$scope.id_evento_select, function (respuesta) {
            respuesta = JSON.parse(respuesta);
            $scope.clientes= respuesta;
            if($scope.clientes.length == 0)
                $scope.visible_check = false;
            else
                $scope.visible_check = true;
            $scope.$apply();

        });
    }
// ===================================================================
// ***** 	  FUNCION PARA CARGAR LOS ESTADOSS		 *****
// ===================================================================
    $scope.cargarEstados = function(){
        $.post(global_apiserver + "/cursos_programados/getAllEstados/", function (respuesta) {
            respuesta = JSON.parse(respuesta);
            $scope.estados= respuesta;
            $scope.$apply();

        });
    }
// ===================================================================
// ***** 	  FUNCION PARA SELECT CLIENTE		 *****
// ===================================================================
    /*$scope.onCliente = function() {
        $scope.formDataParticipante.nombre_participante = "";
        if(typeof $scope.formDataParticipante.select_cliente !== "undefined")
        {
            if($scope.formDataParticipante.select_cliente.SOLO_PARA_CLIENTE == 1)
            {
                $scope.formDataParticipante.nombre_participante = $scope.formDataParticipante.select_cliente.NOMBRE;
                $scope.nombredisabled = true;
            }
            else {
                $scope.nombredisabled = false;
            }
        }else {
            $scope.nombredisabled = false;
        }

    }*/
// ===================================================================
// ***** 	             FUNCION PARA CHECK CLIENTE	        	 *****
// ===================================================================
    $scope.onTieneCliente = function() {

        if($scope.formDataParticipante.tiene_cliente == false)
        {
            $scope.formDataParticipante.nombre_participante ="";
            $scope.nombredisabled = false;
        }
    }
// =======================================================================================================
// *****    Accion al presionar button agregar participantes		 *****
// =======================================================================================================
    $scope.insertaParticipantes= function () {
        var id_cliente = 0;
        if($scope.formDataParticipante.tiene_cliente == true)
        {
            if($scope.formDataParticipante.select_cliente)
            {
                id_cliente = $scope.formDataParticipante.select_cliente;
            }
        }

        var add = {
            NOMBRE: $scope.formDataParticipante.nombre_participante,
            EMAIL: $scope.formDataParticipante.email_participante,
            TELEFONO: $scope.formDataParticipante.telefono_participante,
            CURP: $scope.formDataParticipante.curp_participante,
            PERFIL: $scope.formDataParticipante.perfil_participante,
            ESTADO: $scope.formDataParticipante.estado_participante,
            ID:$scope.id_evento_select,
            ID_CLIENTE: id_cliente,
            CANTIDAD_PARTICIPANTES:($scope.cantidad_participante?$scope.cantidad_participante:0)

        }
            $.post(global_apiserver + "/cursos_programados/insertParticipante/", JSON.stringify(add), function (respuesta) {
                respuesta = JSON.parse(respuesta);
                if (respuesta.resultado == "ok") {
                    $scope.cargaParticipantes();
                    notify("Éxito", "Agregado el participante", "success");
                    $scope.onAgenda($scope.date_evento_select);
                    $scope.cerrarInsertParticipante();

                }
                else {
                    notify("Error", respuesta.mensaje, "error");
                }
            });

    }
// =======================================================================================================
// *****    Accion al presionar button editar participantes		 *****
// =======================================================================================================
    $scope.editaParticipantes= function () {
        var id_cliente = 0;
        if($scope.formDataParticipante.tiene_cliente == true)
        {
            if($scope.formDataParticipante.select_cliente)
            {
                id_cliente = $scope.formDataParticipante.select_cliente;
            }
        }
        var add = {
            NOMBRE: $scope.formDataParticipante.nombre_participante,
            EMAIL: $scope.formDataParticipante.email_participante,
            TELEFONO: $scope.formDataParticipante.telefono_participante,
            CURP: $scope.formDataParticipante.curp_participante,
            PERFIL: $scope.formDataParticipante.perfil_participante,
            ESTADO: $scope.formDataParticipante.estado_participante,
            ID:$scope.id_participante,
            ID_CURSO: $scope.id_evento_select,
            ID_CLIENTE: id_cliente


        }
        alert(JSON.stringify(add));
        $.post(global_apiserver + "/cursos_programados//updateParticipante/", JSON.stringify(add), function (respuesta) {
            respuesta = JSON.parse(respuesta);
            if (respuesta.resultado == "ok") {
                $scope.cargaParticipantes();
                notify("Éxito", "Editado el participante", "success");
                $scope.onAgenda($scope.date_evento_select);
                $scope.cerrarInsertParticipante();
            }
            else {
                notify("Error", respuesta.mensaje, "error");
            }

        });
    }
// =======================================================================================
// *****     Función para submit formulario participante Guardar		 *****
// =======================================================================================
   $scope.submitParticipante = function(accion){

       validar_formulario_participante();
       if($scope.respuesta == 1){

           if(accion=='insertar')
           {
                   $scope.insertaParticipantes();

           }

           if(accion=='editar')
           {
               $scope.editaParticipantes();
           }


       }
   }

// =======================================================================================
// *****     Función para validar los campos del formulario antes de Guardar		 *****
// =======================================================================================
    function validar_formulario_participante() {
        $scope.respuesta = 1;
        var setfocus = null;

        if (typeof $scope.formDataParticipante.estado_participante !== "undefined") {
            if ($scope.formDataParticipante.estado_participante.length == 0) {
                $scope.respuesta = 0;
                $scope.error_estado_participante = "Complete este campo";
                setfocus = "perfil_participante";
            } else {
                $scope.error_estado_participante = "";
            }
        } else {
            $scope.respuesta = 0;
            $scope.error_estado_participante = "Complete este campo";
            setfocus = "estado_participante";
        }
//////////////////////////////////////////////////////////////////////////////////////////
        if (typeof $scope.formDataParticipante.perfil_participante !== "undefined") {
            if ($scope.formDataParticipante.perfil_participante.length == 0) {
                $scope.respuesta = 0;
                $scope.error_perfil_participante = "Complete este campo";
                setfocus = "perfil_participante";
            } else {
                $scope.error_perfil_participante = "";
            }
        } else {
            $scope.respuesta = 0;
            $scope.error_perfil_participante = "Complete este campo";
            setfocus = "perfil_participante";
        }
////////////////////////////////////////////////////////////////////////////////////////////////
        if(typeof $scope.formDataParticipante.curp_participante !== "undefined") {
            if ($scope.formDataParticipante.curp_participante.length == 0) {
                $scope.respuesta = 0;
                $scope.error_curp_participante = "Complete este campo";
                setfocus = "curp_participante";
            } else {

                if($scope.curpValida($scope.formDataParticipante.curp_participante))
                {
                    $scope.error_curp_participante = "";
                }
                else
                {
                    $scope.respuesta = 0;
                    $scope.error_curp_participante = "CURP inválido";
                    setfocus = "curp_participante";
                }
            }
        }else {
            $scope.respuesta = 0;
            $scope.error_curp_participante = "Complete este campo";
            setfocus = "curp_participante";
        }
        /////////////////////////////////////////////////////////////////////////////////////////
        if(typeof $scope.formDataParticipante.telefono_participante !== "undefined") {
            if ($scope.formDataParticipante.telefono_participante.length == 0) {
                $scope.respuesta = 0;
                $scope.error_telefono_participante = "Complete este campo";
                setfocus = "telefono_participante";
            } else {
                if($scope.validar_telefono($scope.formDataParticipante.telefono_participante))
                {
                    $scope.error_telefono_participante = "";
                }
                else
                {
                    $scope.respuesta = 0;
                    $scope.error_telefono_participante = "Correo electrónico inválido";
                    setfocus = "telefono_participante";
                }

            }
        }else {
            $scope.respuesta = 0;
            $scope.error_telefono_participante = "Complete este campo";
            setfocus = "telefono_participante";
        }
        /////////////////////////////////////////////////////////////////////////////////////////
        if(typeof $scope.formDataParticipante.email_participante !== "undefined") {
            if ($scope.formDataParticipante.email_participante.length == 0) {
                $scope.respuesta = 0;
                $scope.error_email_participante = "Complete este campo";
                setfocus = "email_participante";
            } else {
                if($scope.validar_email($scope.formDataParticipante.email_participante))
                {
                    $scope.error_email_participante = "";
                }
                else
                {
                    $scope.respuesta = 0;
                    $scope.error_email_participante = "Correo electrónico inválido";
                    setfocus = "email_participante";
                }

            }
        }else {
            $scope.respuesta = 0;
            $scope.error_email_participante = "Complete este campo";
            setfocus = "email_participante";
        }
////////////////////////////////////////////////////////////////////////////////////////////
        if (typeof $scope.formDataParticipante.nombre_participante !== "undefined") {
            if ($scope.formDataParticipante.nombre_participante.length == 0) {
                $scope.respuesta = 0;
                //$("#error_nombre_participante").text("Complete este campo");
                $scope.error_nombre_participante = "Complete este campo";
                setfocus = "nombre_participante";
            } else {
                $scope.error_nombre_participante = "";
            }
        } else {
            $scope.respuesta = 0;
            $scope.error_nombre_participante = "Complete este campo";
            setfocus = "nombre_participante";
        }
///////////////////////////////////////////////////////////////////////////////////////////
        if($scope.formDataParticipante.tiene_cliente == true)
        {
            if (typeof $scope.formDataParticipante.select_cliente !== "undefined") {
                if ($scope.formDataParticipante.select_cliente.length == 0) {
                    $scope.respuesta = 0;
                    $scope.error_tiene_cliente = "Complete este campo";
                    setfocus = "select_cliente";
                } else {
                    $scope.error_tiene_cliente = "";
                }
            } else {
                $scope.respuesta = 0;
                $scope.error_tiene_cliente = "Complete este campo";
                setfocus = "select_cliente";
            }
        }

/////////////////////////////////////////////////////////////////////////////////////////

        if(setfocus != null)
        {
            $('#'+setfocus).focus();
        }
    }
// ===========================================================================
// ***** 	    FUNCION PARA CARGAR LOS DATEPICKER DEL MODAL			 *****
// ===========================================================================
function onCalendario(inicio) {

 var dateInicial = $('#fecha_inicio').datepicker({
    dateFormat: "dd/mm/yy",
    minDate: "+0D",
    language: "es",
    onSelect: function (dateText, ins) {
        $scope.formData.fecha_inicio = dateText;
        dateFinal.datepicker("option", "minDate", dateText)
        $scope.formData.fecha_fin = dateText;
    }
}).css("display", "inline-block");

var dateFinal =$('#fecha_fin').datepicker({
    dateFormat: "dd/mm/yy",
    language: "es",
    minDate: "+0D",
    onSelect: function (dateText, ins) {
        $scope.formData.fecha_fin = dateText;
    }
}).css("display", "inline-block");

   /* if(inicio)
    {np
        dateInicial.datepicker("option", "minDate", inicio);
    }
    else {
        dateInicial.datepicker("option", "minDate", "+0D");
    }*/


    if($scope.date_evento_select!="")
    {
        $scope.formData.fecha_inicio = $scope.date_evento_select;
        // dateInicial.datepicker("option", "minDate", $scope.date_evento_select);
        dateFinal.datepicker("option", "minDate", $scope.date_evento_select);
    }
}

// ===========================================================================
// ***** 	              FUNCION PARA CARGAR LA AGENDA			         *****
// ===========================================================================
$scope.onAgenda = function(fecha) {
    var date = new Date();
    if(typeof fecha !== "undefined" && fecha.length != 0)
    {
        var partes = fecha.split("/");
        date = new Date(partes[2],parseInt(partes[1])-1,partes[0]);
    }
    var eventos = [];
    //Codigo que carga los cursos programador
    $.post(global_apiserver + "/cursos_programados/getAll/", function(response){
        response = JSON.parse(response);
        var i = 7;
        $.each(response, function( indice, objEvento ) {

            var array = objEvento.FECHA_INICIO.split('/');
            var anhio_ini = array[2];
            var mes_ini = array[1]-1; //En js los meses comienzan en 0
            var dia_ini =array[0];

            var array = objEvento.FECHA_FIN.split('/');
            var anhio_fin = array[2];
            var mes_fin = array[1]-1; //En js los meses comienzan en 0
            var dia_fin =array[0];
            var dias = objEvento.DIAS;
            if(objEvento.DIAS==1)
                dias = dias +' día';
            else
                dias = dias +' días';
            var color =  "#7e1916"

            if(parseInt(objEvento.CANTIDAD_PARTICIPANTES_REAL) == 1)
                color =  "#a5a207";

            if(parseInt(objEvento.CANTIDAD_PARTICIPANTES_REAL) > 1 && parseInt(objEvento.PERSONAS_MINIMO) > parseInt(objEvento.CANTIDAD_PARTICIPANTES_REAL))
                color =  "#bd6d0a";

            if(parseInt(objEvento.PERSONAS_MINIMO) == parseInt(objEvento.CANTIDAD_PARTICIPANTES_REAL))
                color =  "#0d681c";


                eventos.push(
                    {
                        title: ' '+dias+' - ' + objEvento.NOMBRE_CURSO + " - Por: " + objEvento.NOMBRE_AUDITOR ,
                        start: new Date(anhio_ini, mes_ini, dia_ini, 07, 0),
                        end: new Date(anhio_fin, mes_fin, dia_fin, 18, 30),
                        allDay: false,
                        id: objEvento.ID,
                        color: color,
                    }
                )
            if(i==0)
                i=7;
                else
                i--;
        });

        if($scope.global_calendar !== undefined)
        {
            $scope.global_calendar.fullCalendar('destroy');
        }

        $scope.global_calendar = $('#calendar').fullCalendar({
            customButtons: {
                historico: {
                    text: '- Histórico',
                    click: function() {

                        $scope.openModalHistoricoEliminados();
                    }
                },
                newEvent: {
                    text: '+ Agregar Curso',
                    click: function() {

                        $scope.openModalInsertarModificar('insertar');
                    }
                }

            },
            header: {
                left: 'newEvent prev,next today ',
                center: 'title',
                right: 'month,agendaWeek,agendaDay,historico'
            },
            minTime:"07:00:00",
            selectable: false,
            navLinks: true,
            editable: true,
            eventLimit: true,
            eventTextColor:"#141414",
            locale: 'es',
            defaultDate:date,
            events: eventos,
            eventClick: function (calEvent, jsEvent, view) {
                if(calEvent.id)
                {
                    $scope.date_evento_select = calEvent.start.format("DD/MM/YYYY");
                    $scope.id_evento_select = calEvent.id;
                    $scope.openModalMostar();
                }

            }
            ,
            dayClick: function(date, jsEvent, view) {

                if(esDespuesHoy(date.format("DD/MM/YYYY")))
                {
                    $scope.date_evento_select = date.format("DD/MM/YYYY");
                    $(this).css('background-color', '#F1FFFF');
                    $scope.openModalInsertarModificar("insertar");
                }


            }

        });


    });
}

// ================================================================================
// *****                  Funcion comparar fecha                  *****
// ================================================================================
function esDespuesHoy(fecha) {

    var hoy = new Date();
    var partes = fecha.split("/");
    var select = new Date(partes[2],parseInt(partes[1])-1,partes[0],hoy.getHours(),hoy.getMinutes(),hoy.getSeconds(),hoy.getMilliseconds());
    if(hoy<=select){return true;}else {return false;}
}
// ==============================================================================
// ***** 		Funcion para buscar el nombre de curso  a partir del ID		*****
// ==============================================================================
    $scope.NombreCurso	=	function(id){

        if(typeof $scope.cursos != "undefined"){
            var datos_curso	=			$scope.cursos.find(function(element,index,array){
                return element.ID_CURSO == id
            });


            return datos_curso.NOMBRE ;
        }
    }
// ================================================================================
// *****                  Funcion Mostrar/Ocultar elementos                   *****
// ================================================================================
$scope.mytoggle = function (id)
{
    $("#"+id).toggle(function(){

    },function(){

    });
}
// ================================================================================
// *****           Funcion llamada cuando se selecciona un curso              *****
// ================================================================================
$scope.onSelectedCurso = function(){
     //$scope.cargarInstructores(selectCursos);
    $scope.id_instructor = "";
    $("#btnInstructor").attr("value","Selecciona un Instructor");
    $("#btnInstructor").attr("class", "form-control btn ");

    }
// ==============================================================================
// ***** 			Funcion para acomodar la fecha para mostrarla			*****
// ==============================================================================
    $scope.FuncionFecha	=	function(fecha){
        var ano	=	fecha.substring(0,4);
        var mes	=	fecha.substring(4,6);
        var dia	=	fecha.substring(6,8);
        var mestexto = "";
        switch(mes){
            case "01":
                mestexto = "Enero";
                break;
            case "02":
                mestexto = "Febrero";
                break;
            case "03":
                mestexto = "Marzo";
                break;
            case "04":
                mestexto = "Abril";
                break;
            case "05":
                mestexto = "Mayo";
                break;
            case "06":
                mestexto = "Junio";
                break;
            case "07":
                mestexto = "Julio";
                break;
            case "08":
                mestexto = "Agosto";
                break;
            case "09":
                mestexto = "Septiembre";
                break;
            case "10":
                mestexto = "Octubre";
                break;
            case "11":
                mestexto = "Noviembre";
                break;
            case "12":
                mestexto = "Diciembre";
                break;
            default:
                mestexto	= " ";
                break;
        }
        return dia+" de "+mestexto+" de "+ano;
    }

// ==============================================================================
// ***** 		Funcion para generar referencia	para CIFA		*****
// ==============================================================================
    function generar_referencia_cifa(){
        $http.get(  global_apiserver + "/cursos/getReferencia/?id=3&tipo=P")
            .then(function( response ){
                $scope.formData.referencia	= response.data;
            });
    }

$(document).ready(function () {
$scope.onAgenda();
    $("button.btn-default:contains('Cerrar') ,#btnCerrar, .close").click(function(){
        $scope.date_evento_select = '';
        $scope.objCursoProgramado = [];
    })
});
}]);

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