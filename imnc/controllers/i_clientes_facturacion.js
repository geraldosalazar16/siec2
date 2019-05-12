
/*
	Creación del controlador con el nombre "clientes_facturacion_controller".
*/

app.controller('clientes_facturacion_controller',['$scope','$http',function($scope,$http){
//Titulo que aparece en el html
	
	$scope.formData = {};
	$scope.accion_fact = 0;
/*		
		Función para actualizar la tabla con los registros en la BD.
*/

$scope.clientes_datos_facturacion = function() {

	var tablaDatos1 = new Array();
	var indice1=0;
	
	$http.get(  global_apiserver + "/i_clientes_datos_facturacion/getByIdCliente/?id="+global_id_cliente)
		.then(function( response ){
            $scope.CLIENTES_DATOS_FACTURACION = response.data[0];
			$scope.accion_fact=response.data.length;
			if($scope.accion_fact != 1){
				$scope.textoBoton= 'Agregar datos de facturación';
			}
			else{
				$scope.textoBoton= 'Editar datos de facturación';
				
			}
			
		});
		
    
}	
	
/*		
		Función para limpiar la información del módelo y que no se quede guardada
		después de realizar alguna transacción.
*/
$scope.limpiaCampos = function(){
	$scope.formData.formadePago="";
	$scope.formData.metododePago ="";
	$scope.formData.usodelaFactura="";
	
}

/*		
		Función para hacer que aparezca el formulario de agregar datos de facturacion. Cambiamos el
		atributo de "accion" del boton guardar para tener una referencia a que tipo
		transacción se va a hacer (actualizar o insertar)
*/
$scope.InsertarDatosFacturacion = function(){
	$scope.limpiaCampos();
	if($scope.accion_fact == 0){
		$scope.modal_titulo = 'Insertar Datos Facturacion';
		$scope.accion_fact =0;
	}
	if($scope.accion_fact == 1){
		$scope.modal_titulo = 'Editar Datos Facturacion';
		$scope.accion_fact =1;
		$scope.formData.formadePago=$scope.CLIENTES_DATOS_FACTURACION.ID_FORMA_D_PAGO;
		$scope.formData.metododePago =$scope.CLIENTES_DATOS_FACTURACION.ID_METODO_D_PAGO;
		$scope.formData.usodelaFactura=$scope.CLIENTES_DATOS_FACTURACION.ID_USO_D_L_FACTURA;
		
	}
	
	$("#modalInsertarActualizar").modal("show");

}
// ==============================================================================
// ***** 	FUNCION PARA EL BOTON GUARDAR DEL MODAL	INSERTAR/ACTUALIZAR 	*****
// ==============================================================================
$scope.submitForm = function (formData) {
	var datos = {
            CLIENTE: global_id_cliente ,
            FORMA_DE_PAGO:	$scope.formData.formadePago ,
			METODO_DE_PAGO:	$scope.formData.metododePago,
            USO_DE_LA_FACTURA: $scope.formData.usodelaFactura,
			ID_USUARIO:sessionStorage.getItem("id_usuario")
          };
		  if($scope.accion_fact == 0){
			$http.post(global_apiserver + "/i_clientes_datos_facturacion/insert/",datos).
				then(function(response){
			
                if(response.data.resultado=="ok"){
                    notify('&Eacutexito','Se han insertado los datos de facturacion','success');
                    
					$scope.clientes_datos_facturacion();
				
				}
                else{
                    notify('Error',response.data.mensaje,'error');
                }
                $("#modalInsertarActualizar").modal("hide");
            });
		  }
		  if($scope.accion_fact == 1){
			$http.post(global_apiserver + "/i_clientes_datos_facturacion/update/",datos).
				then(function(response){
			
                if(response.data.resultado=="ok"){
                    notify('&Eacutexito','Se han editado los datos de facturacion','success');
                    
					$scope.clientes_datos_facturacion();
				
				}
                else{
                    notify('Error',response.data.mensaje,'error');
                }
                $("#modalInsertarActualizar").modal("hide");
            });
		  }
}
/*		
		Función para traer las Formas de Pago.
*/
$scope.funcionFormasDePago = function(){
	$.ajax({
		type:'GET',
		url:global_apiserver+"/i_formas_d_pago/getAll/",
		success:function(data){
			$scope.$apply(function(){
			
				$scope.formadePagos=angular.fromJson(data);
			})

		}
	});
}
/*		
		Función para traer los Metodos de Pago.
*/
$scope.funcionMetodosDePago = function(){
	$.ajax({
		type:'GET',
		url:global_apiserver+"/i_metodos_d_pago/getAll/",
		success:function(data){
			$scope.$apply(function(){
			
				$scope.metododePagos=angular.fromJson(data);
			})

		}
	});
}
/*		
		Función para traer los Usos de la Factura.
*/
$scope.funcionUsoDeLaFactura = function(){
	$.ajax({
		type:'GET',
		url:global_apiserver+"/i_uso_d_l_factura/getAll/",
		success:function(data){
			$scope.$apply(function(){
			
				$scope.usodelaFacturas=angular.fromJson(data);
			})

		}
	});
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
	$scope.funcionFormasDePago();
	$scope.funcionMetodosDePago();
	$scope.funcionUsoDeLaFactura();
	$scope.clientes_datos_facturacion();
	//$scope.limpiaCampos();


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
