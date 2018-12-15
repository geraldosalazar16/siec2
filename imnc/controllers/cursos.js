app.controller('cursos_controller',['$scope',function($scope){
  //Titulo que aparece en el html
    $scope.titulo = 'Cursos';
    $scope.formData = {};
    $scope.accion = 'insertar';
    $scope.id_curso = 0;

 //=======================================================       
 //Aqui se cargan los cursos para mostrarlos e las tablas 
    $scope.cursos = function() {

      var tablaDatos1 = new Array();
      var indice1=0;
      $.post(  global_apiserver + "/cursos/getAll/", function( response ) {
        response = JSON.parse(response);
        $.each(response, function( indice, datos ) {

          tablaDatos1[indice1] = angular.fromJson(datos);
          indice1+=1;
         
         });
         $scope.tablaDatos =  tablaDatos1;
         $scope.$apply();
      });
    }
 //=======================================================  
    /* funcion para Limpiar formulario */

    $scope.limpiaCampos = function(){
      $scope.formData.txtNombre = "";
      $scope.formData.tipoServicio = "";
      $scope.formData.selectNorma = "";
      $scope.formData.checkActivo = true;
      $("#selectedListerror").text("");
      $("#tipoServicioerror").text("");
      $("#txtNombreerror").text("");
      
    }
 //=======================================================  
    /* Accion para mostrar el modal  */

    $scope.InsertarTipoServicio = function(){

      $("#btnGuardar").attr("accion","insertar");
      $("#modalTitulo").html("Insertar Curso");
        $scope.limpiaCampos();
        $scope.funcionTipoServicio();
      $("#modalInsertarActualizar").modal("show");
    
    }

    $scope.cerrar = function() {		
      $("#txtNombreerror").text("");		
      $("#tipoServicioerror").text("");
      $scope.limpiaCampos();
      $("#modalInsertarActualizar").modal("hide");
      
  };

/*
		Función para hacer que aparezca el formulario de editar. Recibe de parámetro
		el id del tipo de servicio que se va a editar. Cambiamos el
		atributo de "accion" del boton guardar para tener una referencia a que tipo
		transacción se va a hacer (actualizar o insertar) y obtenemos la información
		del registro que se va a obtener para cambiar los valores en el módelo.
		
*/
$scope.EditarCurso	=	function(curso_id){
  $("#btnGuardar").attr("accion","editar");
	$("#modalTitulo").html("Editar servicio");
  $scope.limpiaCampos();
  $scope.id_curso = curso_id;
     
	
	$.getJSON( global_apiserver + "/cursos/getById/?id="+curso_id, function( response ) {

	  $scope.formData.txtNombre = response.NOMBRE;
      $scope.formData.tipoServicio = response.ID_TIPO_SERVICIO;
      $scope.formData.selectNorma = response.ID_NORMA;
      if(response.ISACTIVO==1)
      $scope.formData.checkActivo = true;
      else
      $scope.formData.checkActivo = false;

      $scope.$apply();
      $scope.funcionTipoServicio();

      cargarNormastipoServicio(response.ID_TIPO_SERVICIO);
      

       });	
     $("#selectNorma").attr("readonly",false);

  
	$("#modalInsertarActualizar").modal("show");
}

 //=======================================================  
  /*
		Valida si la información que tiene el módelo es suficiente apra agregar
		el nuevo registro. Aquí se modifica el valor de "$scope.respuesta" para checar
		la validez del módelo.
		Primero se verifica que los campos no sean nulos y en el caso del Acronimo
		se verifica que no se repita para ese Servicio.
		Además se muestra el error conrrespondiente en las etiquetas con los
		id "txtAcronimoerror","txtNombreerror","clavesServicioerror" y "txtTextoReferror".
*/
$scope.valida_agregar = function(op){
  $scope.respuesta = 1;

  if($scope.formData.txtNombre.length > 0 && op =='insertar'){
    $.ajax({
      type:'GET',
      dataType: 'json',
      async: false,
      url:global_apiserver + "/cursos/getIfExist/?nombre="+$scope.formData.txtNombre,
      success: function(data){
        if(data.cantidad > 0){
          $scope.respuesta =  0;	
          notify("Error","Ya existe este curso","error");			
        }else{
          $("#txtNombreerror").text("");
        }
      }
    });
  }

  if($scope.formData.txtNombre.length == 0){
    $scope.respuesta =  0;
    $("#txtNombreerror").text("No debe estar vacio");
  }else{
    $("#txtNombreerror").text("");
  }
  if($scope.formData.tipoServicio.length == 0){
    $scope.respuesta =  0;
    $("#tipoServicioerror").text("No debe estar vacio");
  }else{
    $("#tipoServicioerror").text("");
  }


  if(typeof $scope.formData.selectNorma !== "undefined")
  {
    $("#selectedListerror").text("");
   if( $scope.formData.selectNorma.length == 0){
    $scope.respuesta =  0;
    $("#selectedListerror").text("No debe estar vacio");
    
  }else{
    $("#selectedListerror").text("");
  }
  }
  else{
    $scope.respuesta =  0;
    $("#selectedListerror").text("No debe estar vacio");
    
  }
  
   
  
}

// ==============================================================================
//  	Funcion para traer las Normas de este tipo de Servicio			*****

function cargarNormastipoServicio(id_tipo_servicio){
		//Agregue normas a mostrar para cuando sea edición 
		//Se muestre en el multiselect las que tiene seleccionadas
    //Mientras que en las sugerencias no se muestren estas
  
    $.ajax({
      type:'GET',
      url:global_apiserver+"/normas_tiposervicio/getNormabyIdTipoServicioList/?id="+id_tipo_servicio,
      success:function(data){
        $scope.$apply(function(){
        
          $scope.optionsList=angular.fromJson(data);
        })
  
      }
    });
		
  }	
  
 //=======================================================  
/* Esta funcion es para el evento onchange del select tipo de servicio */
$scope.cambio_tipoServicio = function(tipoServicio){
  $scope.formData.TipoServicio = tipoServicio;
  cargarNormastipoServicio(tipoServicio);
}

 //=======================================================  
/* Esta funcion es para el evento onchange del select tipo de servicio */
$scope.cambio_Norma = function(valor){
  $scope.formData.selectNorma = valor;
 
  
  
}
 
 //=======================================================  
/*		
		Esta función nos sirve para hacer el insert o update. Se checa cual de las
		dos transacciones se debe de hacer. Al finalizar la transacción se actualiza
		la tabla.
*/
$scope.guardarCurso = function(){
	if ($("#btnGuardar").attr("accion") == "insertar")
    {
		$scope.valida_agregar("insertar");
		if($scope.respuesta == 1){
      $scope.insertar();
      
		}
		
    }
    else if ($("#btnGuardar").attr("accion") == "editar")
    {
		$scope.valida_agregar("editar");
		if($scope.respuesta == 1){
			$scope.editar();
		}
      
    }
	
//	$("#modalInsertarActualizar").modal("hide");
}

 //=======================================================  
  /*		
		Función para traer las tipo de servicio.
*/
$scope.funcionTipoServicio = function(){
	$.ajax({
		type:'GET',
		url:global_apiserver+"/tipos_servicio/getByService/?id=3",
		success:function(data){
			$scope.$apply(function(){
			
				$scope.tipo_Servicio=angular.fromJson(data);
			})

		}
	});
}

 //=======================================================  
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

//=======================================================  
/*	Funcion para insertar los datos	*/
$scope.insertar	=	function(){
  
	var curso = {
      NOMBRE:		          	$scope.formData.txtNombre,
	  ID_TIPO_SERVICIO:			$scope.formData.tipoServicio,
	  ID_NORMA:	                $scope.formData.selectNorma,
      ISACTIVO:                 $scope.formData.checkActivo

  };
	alert($scope.formData.checkActivo);
  $.post( global_apiserver + "/cursos/insert/", JSON.stringify(curso), function(respuesta){
      respuesta = JSON.parse(respuesta);
      if (respuesta.resultado == "ok") {
        $("#modalInsertarActualizar").modal("hide");
        notify("Éxito", "Se ha insertado un nuevo curso: "+curso.NOMBRE, "success");
        $scope.cursos();
        //document.location = "./?pagina=auditores";
      }
      else{
          notify("Error", respuesta.mensaje, "error");
        }
      
  });

}
    
//=======================================================  
/*	Funcion para editar los datos	*/
$scope.editar	=	function(){
  
	var curso = {
      ID_CURSO:             $scope.id_curso,
      NOMBRE:		        $scope.formData.txtNombre,
	  ID_TIPO_SERVICIO:		$scope.formData.tipoServicio,
	  ID_NORMA:	            $scope.formData.selectNorma,
      ISACTIVO:                 $scope.formData.checkActivo
  };
  $.post( global_apiserver + "/cursos/update/", JSON.stringify(curso), function(respuesta){
      respuesta = JSON.parse(respuesta);
      if (respuesta.resultado == "ok") {
        $("#modalInsertarActualizar").modal("hide");
        notify("Éxito", "Se ha editado el curso: "+curso.NOMBRE, "success");
        $scope.cursos();
        //document.location = "./?pagina=auditores";
      }
      else{
          notify("Error", respuesta.mensaje, "error");
        }
      
  });

}

$(document).ready(function () {
  $scope.cursos();
  //$scope.funcionTipoServicio();
 
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

