
/*
	Creación del controlador con el nombre "tipo_servicios_controller".
*/

app.controller('tipo_servicio_controller',['$scope',function($scope){
//Titulo que aparece en el html
	
	$scope.titulo = 'Tipos de servicio';
	$scope.opcioninsertareditar = 0;
	$scope.opcioninsertareditar1 = 0;
	$scope.id_tipo_servicio = 0;
	$scope.idServicio = 0;
	$scope.txtAcronimo = "";
	$scope.txtNombre = "";
	$scope.claveServicio = "";
	$scope.claveServicio1 = "";
	$scope.selectedList=[];
	
	
	$scope.valores = "123";
	$scope.txtTextoRef = "";
	//Se usa para checar si el módelo esta válido o no. Válido = 1 , no válido = 0.
	$scope.respuesta = 1;
	$scope.claveServicio2="";
/*		
		Función para actualizar la tabla con los registros en la BD.
*/
$scope.tipos_servicio = function() {

	var tablaDatos1 = new Array();
	var indice1=0;
	$.post(  global_apiserver + "/tipos_servicio/getAll/", function( response ) {
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
	$scope.id_tipo_servicio = 0;
	$scope.idServicio = 0;
	$scope.selectedList.splice(0);
	$scope.txtAcronimo = "";
	$scope.txtNombre = "";
	$scope.claveServicio = "";
	$scope.claveServicio1 = "";
	$scope.txtTextoRef = "";
	
}

/*		
		Función para hacer que aparezca el formulario de agregar tipos_servicio. Cambiamos el
		atributo de "accion" del boton guardar para tener una referencia a que tipo
		transacción se va a hacer (actualizar o insertar)
*/
$scope.InsertarTipoServicio = function(){

	$("#btnGuardar").attr("accion","insertar");
	$("#modalTitulo").html("Insertar servicio");
    $scope.limpiaCampos();
	$("#txtAcronimo").attr("readonly",false);
	$scope.opcioninsertareditar = 1;
	$scope.opcioninsertareditar1 = 0;
    $("#modalInsertarActualizar").modal("show");

}
/*
		Función para hacer que aparezca el formulario de editar. Recibe de parámetro
		el id del tipo de servicio que se va a editar. Cambiamos el
		atributo de "accion" del boton guardar para tener una referencia a que tipo
		transacción se va a hacer (actualizar o insertar) y obtenemos la información
		del registro que se va a obtener para cambiar los valores en el módelo.
		
*/
$scope.EditarTipoServicio	=	function(tipo_servicio_id){
	$("#btnGuardar").attr("accion","editar");
	$("#modalTitulo").html("Editar servicio");
	$scope.limpiaCampos();
	
	$.getJSON( global_apiserver + "/tipos_servicio/getById/?id="+tipo_servicio_id, function( response ) {
			$scope.id_tipo_servicio = response.ID;
			$scope.txtAcronimo = response.ACRONIMO;
			$scope.claveServicio1 = response.NOMBRE_SERVICIO;
			$scope.idServicio = response.ID_SERVICIO;
			$scope.txtNombre = response.NOMBRE;
			$scope.txtTextoRef = response.ID_REFERENCIA;
			$scope.$apply(); 
       });	
	   $("#txtAcronimo").attr("readonly",true);
	   $scope.opcioninsertareditar = 0;
	   $scope.opcioninsertareditar1 = 1;
	   $("#claveServicio1").attr("readonly",true);
	   $scope.funciontiposervicionormas(tipo_servicio_id);
	$("#modalInsertarActualizar").modal("show");
}
/*
		Función para hacer que desaparezca el formulario de agregar o editar y
		limpiamos los campos del módelo.
*/
$scope.cerrar = function() {		
		$("#txtAcronimoerror").text("");		
		$("#txtNombreerror").text("");
		$("#claveServicioerror").text("");
		$("#txtTextoReferror").text("");
		$scope.limpiaCampos();
		$("#modalInsertarActualizar").modal("hide");
		
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
		if($scope.txtAcronimo.length > 0 && $scope.claveServicio.length > 0){	
			$.ajax({
				type:'GET',
				dataType: 'json',
				async: false,
				url:global_apiserver + "/tipos_servicio/getIfExist/?acronimo="+$scope.txtAcronimo+"&id_servicio="+$scope.claveServicio,
				success: function(data){
					if(data.cantidad > 0){
						$scope.respuesta =  0;	
						notify("Error","Ya esta registrado este tipo de servicio para este Servicio","error");						
						//$("#nombreerror").text("Ya esta registrado este tipo de servicio para este Servicio");
						
					}else{
						$("#nombreerror").text("");
					}
				}
			});
		}else{
			
			
		}
		if($scope.txtNombre.length == 0){
			$scope.respuesta =  0;
			$("#txtNombreerror").text("No debe estar vacio");
		}else{
			$("#txtNombreerror").text("");
		}
		if($scope.txtTextoRef.length == 0){
			$scope.respuesta =  0;
			$("#txtTextoReferror").text("No debe estar vacio");
		}else{
			$("#txtTextoReferror").text("");
		}
		if($scope.txtAcronimo.length == 0){
			$scope.respuesta =  0;
			$("#txtAcronimoerror").text("No debe estar vacio");
		}else{
			$("#txtAcronimoerror").text("");
		}
		if($scope.claveServicio.length == 0){
			$scope.respuesta =  0;
			$("#claveServicioerror").text("No debe estar vacio");
		}else{
			$("#claveServicioerror").text("");
		}
	}

/*
		Se checa si es válida la modificación. 
		Con los id "xxxerror" mostramos
		el error correspondiente.
*/
	
	$scope.valida_editar = function(){
		$scope.respuesta = 1;
		if($scope.txtAcronimo.length == 0){
			$scope.respuesta =  0;
			$("#txtAcronimoerror").text("No debe estar vacio");
		}else{
			$("#txtAcronimoerror").text("");
		}
		if($scope.txtNombre.length == 0){
			$scope.respuesta =  0;
			$("#txtNombreerror").text("No debe estar vacio");
		}else{
			$("#txtNombreerror").text("");
		}
		if($scope.claveServicio1.length == 0){
			$scope.respuesta =  0;
			$("#claveServicioerror").text("No debe estar vacio");
		}else{
			$("#claveServicioerror").text("");
		}
		if($scope.txtTextoRef.length == 0){
			$scope.respuesta =  0;
			$("#txtTextoReferror").text("No debe estar vacio");
		}else{
			$("#txtTextoReferror").text("");
		}
	}	
/*		
		Esta función nos sirve para hacer el insert o update. Se checa cual de las
		dos transacciones se debe de hacer. Al finalizar la transacción se actualiza
		la tabla.
*/
$scope.guardarTipoServicios = function(){
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

	var tipo_servicio = {
    
    ID_SERVICIO:	$scope.claveServicio,
	ACRONIMO:		$scope.txtAcronimo,
    NOMBRE:			$scope.txtNombre,
	NORMA:			$scope.selectedList,
	ID_REFERENCIA:	$scope.txtTextoRef,
    ID_USUARIO:		sessionStorage.getItem("id_usuario")
  };
  $.post( global_apiserver + "/tipos_servicio/insert/", JSON.stringify(tipo_servicio), function(respuesta){
      respuesta = JSON.parse(respuesta);
      if (respuesta.resultado == "ok") {
        $("#modalInsertarActualizar").modal("hide");
        notify("Éxito", "Se ha insertado un nuevo registro", "success");
        $scope.tipos_servicio();
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
		ID:				$scope.id_tipo_servicio,
		ID_SERVICIO:	$scope.idServicio,
		ACRONIMO:		$scope.txtAcronimo,
		NOMBRE:			$scope.txtNombre,
		NORMA:			$scope.selectedList,
		ID_REFERENCIA:	$scope.txtTextoRef,
		ID_USUARIO:		sessionStorage.getItem("id_usuario")
  };
  $.post( global_apiserver + "/tipos_servicio/update/", JSON.stringify(tipo_servicio), function(respuesta){
      respuesta = JSON.parse(respuesta);
      if (respuesta.resultado == "ok") {
        $("#modalInsertarActualizar").modal("hide");
        notify("Éxito", "Se han actualizado los datos", "success");
        $scope.tipos_servicio();
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

/*
	Funcion para traer las normas de este servicio
*/
$scope.funcionparalistanormas = function(){
    $.ajax({
		type:'GET',
		url:global_apiserver+"/normas/getAll/",
		success:function(data){
			$scope.$apply(function(){
				$scope.optionsList=angular.fromJson(data);
				
			})

		}
	});
	
}
/*
	Funcion para traer las normas que ya estan asociadas a ese servicio
*/
$scope.funciontiposervicionormas = function(id_tipo_servicio){
	 $.ajax({
		type:'GET',
		url:global_apiserver+"/normas_tiposervicio/getNormabyIdTipoServicio/?id="+id_tipo_servicio,
		success:function(data){
		data=JSON.parse(data);
			$.each(data, function( index, data1 ) {
				$scope.$apply(function(){
				//$scope.selectedList.push(angular.fromJson(data));
				data1['ID']=data1['ID_NORMA'];
				$scope.selectedList.push(data1);
				 
				 })
			})

		}
	});
}


$(document).ready(function () {
	$scope.tipos_servicio();
	$scope.funcionClaveServicio();
	$scope.funcionparalistanormas(); 
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
