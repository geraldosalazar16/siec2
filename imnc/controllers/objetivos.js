app.controller('objetivos_controller',['$scope',function($scope){
  //Titulo que aparece en el html
    $scope.titulo = 'Objetivos';
    $scope.formData = {};
    $scope.accion = 'insertar';
    $scope.periodicidad = [];
    $scope.objetivos = [];
    $scope.meses = [{"nombre" : "Enero"},{"nombre" : "Febrero"},{"nombre" : "Marzo"},{"nombre" : "Abril"},{"nombre" : "Mayo"},{"nombre" : "Junio"},{"nombre" : "Julio"},{"nombre" : "Agosto"},{"nombre" : "Septiembre"},{"nombre" : "Octubre"},{"nombre" : "Noviembre"},{"nombre" : "Diciembre"}];

// ===========================================================================
// ***** 	    FUNCION PARA CARGAR LOS DATEPICKER DEL MODAL			 *****
// ===========================================================================
    $scope.cambio_periodicidad = function(valor) {
        $scope.formData.valor_periodicidad = "";
      if(valor == 1)
      {
          $scope.formData.anno = "";

          // setTimeout(function () {
          //     onCalendario();
          // }, 1000);
      }
    }

// ===========================================================================
// ***** 	             FUNCION PARA CAMBIA PERIODICIDAD			 *****
// ===========================================================================
    $scope.loadPeriodicidad = function() {
        $.post(  global_apiserver + "/objetivos/getAllPeriodicidad/", function( response ) {
            $scope.periodicidad = JSON.parse(response);
            $scope.$apply();

        });
    }
// ===========================================================================
// ***** 	             FUNCION PARA CAMBIA PERIODICIDAD			 *****
// ===========================================================================
    $scope.loadOjetivos = function() {
        $.post(  global_apiserver + "/objetivos/getAll/", function( response ) {
            $scope.objetivos = JSON.parse(response);
            $scope.$apply();

        });
    }

// ===========================================================================
// ***** 	    FUNCION PARA CARGAR LOS DATEPICKER DEL MODAL			 *****
// ===========================================================================
    function onCalendario() {

        var dateInicial = $('#valor_periodicidad').datepicker({
            dateFormat: "years",
            minDate: "+0D",
            language: "es",
            onSelect: function (dateText, ins) {
            }
        }).css("display", "inline-block");


    }
 //=======================================================  
    /* funcion para Limpiar formulario */

    $scope.limpiaCampos = function(){
      $scope.formData = {};
      
    }
 //=======================================================  
    /* Accion para mostrar el modal  */

    $scope.openModalInsertar = function(){

      $("#btnGuardar").attr("accion","insertar");
      $("#modalTitulo").html("Insertar Objetivo");
        $scope.limpiaCampos();
        $scope.loadPeriodicidad();
      $("#modalInsertarActualizar").modal("show");
    
    }

//=======================================================
    /* Accion para mostrar el modal  */

    $scope.openModalEditar = function(objetivo){

        $("#btnGuardar").attr("accion","editar");
        $("#modalTitulo").html("Editar Objetivo");
        $scope.limpiaCampos();
        $scope.loadPeriodicidad();
        $scope.formData.id = objetivo.ID;
        $scope.formData.nombre_objetivo = objetivo.NOMBRE;
        $scope.formData.periodicidad = objetivo.ID_PERIODICIDAD;
        if(objetivo.ID_PERIODICIDAD==1)
        {
            $scope.formData.valor_periodicidad =  parseInt(objetivo.VALOR_PERIODICIDAD);
        }else {
            $scope.formData.valor_periodicidad =  objetivo.VALOR_PERIODICIDAD;
        }

        $scope.formData.monto = parseInt(objetivo.VALOR_OBJETIVO);

        $("#modalInsertarActualizar").modal("show");

    }



 //=======================================================  
/*		
		Esta función nos sirve para hacer el insert o update. Se checa cual de las
		dos transacciones se debe de hacer. Al finalizar la transacción se actualiza
		la tabla.
*/
$scope.guardarObjetivo = function(){
	if ($("#btnGuardar").attr("accion") == "insertar")
    {
      $scope.insertar();
    }
    else if ($("#btnGuardar").attr("accion") == "editar")
    {
			$scope.editar();
	}

}

//=======================================================  
/*	Funcion para insertar los datos	*/
$scope.insertar	=	function(){
  
	var objetivo = {
      NOMBRE_OBJETIVO:$scope.formData.nombre_objetivo,
	  PRIORICIDAD:$scope.formData.periodicidad,
	  VALOR_PERIODICIDAD:$scope.formData.valor_periodicidad,
	  MONTO:$scope.formData.monto,
  };

  $.post( global_apiserver + "/objetivos/insert/", JSON.stringify(objetivo), function(respuesta){
      respuesta = JSON.parse(respuesta);
      if (respuesta.resultado == "ok") {
          $scope.loadOjetivos();
        $("#modalInsertarActualizar").modal("hide");
        notify("Éxito", "Se ha insertado un nuevo objetivo: "+objetivo.NOMBRE_OBJETIVO+" de "+objetivo.VALOR_PERIODICIDAD+": "+objetivo.MONTO, "success");

      }
      else{
          notify("Error", respuesta.mensaje, "error");
        }
      
  });

}
    
//=======================================================  
/*	Funcion para editar los datos	*/
$scope.editar	=	function(){

    var objetivo = {
        ID:$scope.formData.id,
        NOMBRE_OBJETIVO:$scope.formData.nombre_objetivo,
        PRIORICIDAD:$scope.formData.periodicidad,
        VALOR_PERIODICIDAD:$scope.formData.valor_periodicidad,
        MONTO:$scope.formData.monto
    };
  console.log(JSON.stringify(objetivo));
  $.post( global_apiserver + "/objetivos/update/", JSON.stringify(objetivo), function(respuesta){
      respuesta = JSON.parse(respuesta);
      if (respuesta.resultado == "ok") {
          $scope.loadOjetivos();
          $("#modalInsertarActualizar").modal("hide");
          notify("Éxito", "Se ha editado un nuevo objetivo: "+objetivo.NOMBRE_OBJETIVO+" de "+objetivo.VALOR_PERIODICIDAD+": "+objetivo.MONTO, "success");

      }
      else{
          notify("Error", respuesta.mensaje, "error");
      }
      
  });

}

$(document).ready(function () {
    $scope.loadOjetivos();

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

