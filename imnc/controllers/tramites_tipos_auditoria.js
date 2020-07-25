
/*
	Creación del controlador con el nombre "tramites_tipos_auditoria".
*/

app.controller('tramites_tipos_auditoria_controller',['$scope','$http',function($scope,$http){
//Titulo que aparece en el html
	
	$scope.titulo = 'Tipos de auditoría';
	$scope.opcioninsertareditar = 0;
	$scope.opcioninsertareditar1 = 0;
	$scope.id_tipo_auditoria = 0;
	$scope.idServicio = 0;
	$scope.txtAcronimo = "";
	$scope.txtNombre = "";
	$scope.claveServicio = "";
	$scope.claveServicio1 = "";
	$scope.selectedList=[];
	
	
	$scope.txtTextoRef = "";
	//Se usa para checar si el módelo esta válido o no. Válido = 1 , no válido = 0.
	$scope.respuesta = 1;
	$scope.claveServicio2="";
/*		
		Función para actualizar la tabla con los registros en la BD.
*/
$scope.tipos_auditoria = function() {

	var tablaDatos1 = new Array();
	var indice1=0;
	$.post(  global_apiserver + "/i_sg_auditorias_tipos/getAll/", function( response ) {
		response = JSON.parse(response);
		$.each(response, function( indice, datos ) {
			

			tablaDatos1[indice1] = angular.fromJson(datos);
			indice1+=1;
		 
	   });
	   $scope.tablaDatos =  tablaDatos1;
	   $scope.$apply();
	});
}	
	
/*		
		Función para limpiar la información del módelo y que no se quede guardada
		después de realizar alguna transacción.
*/
$scope.limpiaCampos = function(){
	
	$scope.selectedList.splice(0);
	
	$scope.id_tipo_auditoria = 0;
	$scope.txtAcronimo = '';
	$scope.claveServicio = '';
	$scope.idServicio = 0;
	$scope.txtNombre = '';
	$scope.etapa = '';
	
}

/*		
		Función para hacer que aparezca el formulario de agregar tipos_auditoria. Cambiamos el
		atributo de "accion" del boton guardar para tener una referencia a que tipo
		transacción se va a hacer (actualizar o insertar)
*/
$scope.InsertarTipoAuditoria = function(){

	$("#btnGuardar").attr("accion","insertar");
	$("#modalTitulo").html("Insertar tipo auditor&iacutea");
    $scope.limpiaCampos();
	$("#txtAcronimo").attr("readonly",false);
	$scope.opcioninsertareditar = 1;
	$scope.opcioninsertareditar1 = 0;
    $("#modalInsertarActualizar").modal("show");

}
/*
		Función para hacer que aparezca el formulario de editar. Recibe de parámetro
		el id del tipo de auditoria que se va a editar. Cambiamos el
		atributo de "accion" del boton guardar para tener una referencia a que tipo
		transacción se va a hacer (actualizar o insertar) y obtenemos la información
		del registro que se va a obtener para cambiar los valores en el módelo.
		
*/
$scope.EditarTipoAuditoria	=	function(tipo_auditoria_id){
	$("#btnGuardar").attr("accion","editar");
	$("#modalTitulo").html("Editar tipo auditor&iacutea");
	$scope.limpiaCampos();
	
	$.getJSON( global_apiserver + "/i_sg_auditorias_tipos/getById/?id="+tipo_auditoria_id, function( response ) {
			$scope.id_tipo_auditoria = response[0].ID;
			$scope.txtAcronimo = response[0].ACRONIMO_AUDITORIA;
			$scope.claveServicio = response[0].ID_SERVICIO;
			cargarEtapas($scope.claveServicio);
			$scope.idServicio = response[0].ID_SERVICIO;
			$scope.txtNombre = response[0].TIPO_AUDITORIA;
			$scope.etapa = response[0].ID_ETAPA;
			$scope.$apply(); 
       });	
	   //$("#txtAcronimo").attr("readonly",true);
	   $scope.opcioninsertareditar = 0;
	   $scope.opcioninsertareditar1 = 1;
	   //$("#claveServicio1").attr("readonly",true);
	$("#modalInsertarActualizar").modal("show");
}
// ==================================================================
    // ***** 	Función para traer las claves de servicio.			*****
    // ==================================================================
    function cargarServicios() {
        $http.get(global_apiserver + "/servicios/getAll/")
            .then(function(response) {
                $scope.claveServicios = response.data;
            });
    }
	 // ==============================================================================
        // ***** 	Funcion para traer los tipos de Servicios para este Servicio	*****
        // ==============================================================================
    // ==============================================================================
    // ***** 	Funcion para traer las etapas	*****
    // ==============================================================================
    function cargarEtapas(id_servicio, seleccion) {
        var inicial = null;
        $http.get(global_apiserver + "/etapas_proceso/getByIdServicio/?id=" + id_servicio + "&insertar=N")
            .then(function(response) { //se ejecuta cuando la petición fue correcta
                    $scope.Etapas = response.data.map(function(item) {
                        if (item.ETAPA == "INSCRITO")
                            inicial = item.ID_ETAPA;
                        return {
                            ID_ETAPA: item.ID_ETAPA,
                            ETAPA: item.ETAPA
                        }
                    });
                   
                },
                function(response) {});
    }
	// ==================================================================
    // ***** 				Cambio clave Servicio					*****
    // ==================================================================
    $scope.cambioclaveServicio = function(id_servicio) {
          cargarEtapas(id_servicio);
	}
/*
		Función para hacer que desaparezca el formulario de agregar o editar y
		limpiamos los campos del módelo.
*/
$scope.cerrar = function() {		

	$.confirm({
		title: 'Confirmación',
		content: 'Esta seguro de salir sin guardar los datos?',
		buttons: {
			Salir: function () {

				$(".modal.fade").modal("hide");
				$("#txtAcronimoerror").text("");
				$("#txtNombreerror").text("");
				$("#claveServicioerror").text("");
				$("#txtTextoReferror").text("");
				$scope.limpiaCampos();

			},
			Cancelar: function () {
				console.log("cancel");

			}
		}
	});

		//$("#modalInsertarActualizar").modal("hide");
		
};	
/*
		Valida si la información que tiene el módelo es suficiente apra agregar
		el nuevo registro. Aquí se modifica el valor de "$scope.respuesta" para checar
		la validez del módelo.
		Primero se verifica que los campos no sean nulos y en el caso del Acronimo
		se verifica que no se repita para ese Servicio.
		Además se muestra el error conrrespondiente en las etiquetas con los
		id "txtAcronimoerror","txtNombreerror","clavesServicioerror" y "txtTextoReferror".
*/
$scope.valida_agregar = function(){
		$scope.respuesta = 1;
		if($scope.etapa.length > 0 && $scope.claveServicio.length > 0){	
			$.ajax({
				type:'GET',
				dataType: 'json',
				async: false,
				url:global_apiserver + "/i_sg_auditorias_tipos/getIfExist/?etapa="+$scope.etapa+"&id_servicio="+$scope.claveServicio,
				success: function(data){
					if(data.cantidad > 0){
						$scope.respuesta =  0;	
						notify("Error","Ya esta registrado este tipo de auditoria para este Servicio y esta etapa","error");						
						//$("#nombreerror").text("Ya esta registrado este tipo de servicio para este Servicio");
						
					}else{
						$("#nombreerror").text("");
					}
				}
			});
		}else{
			
			
		}
		if($scope.txtAcronimo === undefined){
			$scope.respuesta =  0;
			$("#txtAcronimoerror").text("No debe estar vacio");
		}else{
			if($scope.txtAcronimo.length == 0){
				$scope.respuesta =  0;
				$("#txtAcronimoerror").text("No debe estar vacio");
			}else{
				$("#txtAcronimoerror").text("");	
			}
			
		}
		if($scope.txtNombre === undefined){
			$scope.respuesta =  0;
			$("#txtNombreerror").text("No debe estar vacio");
		}else{
			if($scope.txtNombre.length == 0){
				$scope.respuesta =  0;
				$("#txtNombreerror").text("No debe estar vacio");
			}else{
				$("#txtNombreerror").text("");;	
			}
			
		}
		if( $scope.claveServicio === undefined){
			$scope.respuesta =  0;
			$("#claveServicioerror").text("No debe estar vacio");
		}else{
			if($scope.claveServicio.length == 0){
				$scope.respuesta =  0;
				$("#claveServicioerror").text("No debe estar vacio");
			}else{
				$("#claveServicioerror").text("");
			}
			
		}
		if($scope.etapa === undefined){
			$scope.respuesta =  0;
			$("#etapaerror").text("No debe estar vacio");
		}else{
			if($scope.etapa.length == 0){
				$scope.respuesta =  0;
				$("#etapaerror").text("No debe estar vacio");
			}else{
				$("#etapaerror").text("");
			}
			
		}
	}

/*
		Se checa si es válida la modificación. 
		Con los id "xxxerror" mostramos
		el error correspondiente.
*/
	
	$scope.valida_editar = function(){
		$scope.respuesta = 1;
		if($scope.txtAcronimo === undefined){
			$scope.respuesta =  0;
			$("#txtAcronimoerror").text("No debe estar vacio");
		}else{
			if($scope.txtAcronimo.length == 0){
				$scope.respuesta =  0;
				$("#txtAcronimoerror").text("No debe estar vacio");
			}else{
				$("#txtAcronimoerror").text("");	
			}
			
		}
		if($scope.txtNombre === undefined){
			$scope.respuesta =  0;
			$("#txtNombreerror").text("No debe estar vacio");
		}else{
			if($scope.txtNombre.length == 0){
				$scope.respuesta =  0;
				$("#txtNombreerror").text("No debe estar vacio");
			}else{
				$("#txtNombreerror").text("");;	
			}
			
		}
		if( $scope.claveServicio === undefined){
			$scope.respuesta =  0;
			$("#claveServicioerror").text("No debe estar vacio");
		}else{
			if($scope.claveServicio.length == 0){
				$scope.respuesta =  0;
				$("#claveServicioerror").text("No debe estar vacio");
			}else{
				$("#claveServicioerror").text("");
			}
			
		}
		if($scope.etapa === undefined){
			$scope.respuesta =  0;
			$("#etapaerror").text("No debe estar vacio");
		}else{
			if($scope.etapa.length == 0){
				$scope.respuesta =  0;
				$("#etapaerror").text("No debe estar vacio");
			}else{
				$("#etapaerror").text("");
			}
			
		}
	}	
/*		
		Esta función nos sirve para hacer el insert o update. Se checa cual de las
		dos transacciones se debe de hacer. Al finalizar la transacción se actualiza
		la tabla.
*/
$scope.guardarTipoAuditoria = function(){
	if ($("#btnGuardar").attr("accion") == "insertar")
    {
		$scope.valida_agregar();
		if($scope.respuesta == 1){
			$scope.insertar();
		}
		
    }
    else if ($("#btnGuardar").attr("accion") == "editar")
    {
		$scope.valida_editar();
		if($scope.respuesta == 1){
			$scope.editar();
		}
      
    }
	
//	$("#modalInsertarActualizar").modal("hide");
}
/*	Funcion para insertar los datos	*/
$scope.insertar	=	function(){

	var tipo_auditoria = {
    
    ID_SERVICIO:	$scope.claveServicio,
	ACRONIMO:		$scope.txtAcronimo,
    TIPO:			$scope.txtNombre,
	ETAPA:			$scope.etapa,
    ID_USUARIO:		sessionStorage.getItem("id_usuario")
  };
  $.post( global_apiserver + "/i_sg_auditorias_tipos/insert/", JSON.stringify(tipo_auditoria), function(respuesta){
      respuesta = JSON.parse(respuesta);
      if (respuesta.resultado == "ok") {
        $("#modalInsertarActualizar").modal("hide");
        notify("Éxito", "Se ha insertado un nuevo registro", "success");
        $scope.tipos_auditoria();
        //document.location = "./?pagina=auditores";
      }
      else{
          notify("Error", respuesta.mensaje, "error");
        }
      
  });
}
/*	Funcion para editar los datos	*/
$scope.editar	=	function(){

	var tipo_servicio = {
		ID:				$scope.id_tipo_auditoria,
		ID_SERVICIO:	$scope.claveServicio,
		ACRONIMO:		$scope.txtAcronimo,
		TIPO:			$scope.txtNombre,
		ETAPA:			$scope.etapa,
		ID_USUARIO:		sessionStorage.getItem("id_usuario")
  };
  $.post( global_apiserver + "/i_sg_auditorias_tipos/update/", JSON.stringify(tipo_servicio), function(respuesta){
      respuesta = JSON.parse(respuesta);
      if (respuesta.resultado == "ok") {
        $("#modalInsertarActualizar").modal("hide");
        notify("Éxito", "Se han actualizado los datos", "success");
        $scope.tipos_auditoria();
        //document.location = "./?pagina=auditores";
      }
      else{
          notify("Error", respuesta.mensaje, "error");
        }
      
  });
}
/*		
		Función para traer las claves de servicio.
*/
$scope.funcionClaveServicio = function(){
	$.ajax({
		type:'GET',
		url:global_apiserver+"/servicios/getAll/",
		success:function(data){
			$scope.$apply(function(){
			
				$scope.clave_Servicio=angular.fromJson(data);
			})

		}
	});
}




$(document).ready(function () {
	$scope.tipos_auditoria();
	cargarServicios();
	$scope.funcionClaveServicio(); 
	$scope.limpiaCampos();


});
	
}]);

function notify(titulo, texto, tipo){
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
