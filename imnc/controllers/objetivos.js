app.controller('objetivos_controller',['$scope',function($scope){
  //Titulo que aparece en el html
    $scope.titulo = 'Objetivos';
    $scope.formData = {};
    $scope.accion = 'insertar';
    $scope.periodicidad = [];
    $scope.objetivos = [];
    $scope.meses = [{"id":1,"nombre" : "Enero"},{"id":2,"nombre" : "Febrero"},{"id":3,"nombre" : "Marzo"},{"id":4,"nombre" : "Abril"},{"id":5,"nombre" : "Mayo"},{"id":6,"nombre" : "Junio"},{"id":7,"nombre" : "Julio"},{"id":8,"nombre" : "Agosto"},{"id":9,"nombre" : "Septiembre"},{"id":10,"nombre" : "Octubre"},{"id":11,"nombre" : "Noviembre"},{"id":12,"nombre" : "Diciembre"}];
	$scope.Propuestas = [{"NOMBRE":"Propuestas Emitidas"},{"NOMBRE":"Propuestas Ganadas"}];

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
        $.post(  global_apiserver + "/i_objetivos/getAllPeriodicidad/", function( response ) {
            $scope.periodicidad = JSON.parse(response);
            $scope.$apply();

        });
    }
// ===========================================================================
// ***** 	             FUNCION PARA CAMBIA PERIODICIDAD			 *****
// ===========================================================================
    $scope.loadOjetivos = function() {
        $.post(  global_apiserver + "/i_objetivos/getAll/", function( response ) {
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
		$scope.accion = 'insertar';
		$("#btnGuardar").attr("accion","insertar");
		$("#modalTitulo").html("Insertar Objetivo");
        $scope.limpiaCampos();
        $scope.loadPeriodicidad();
		$scope.formData.anhio = parseInt(moment().format('YYYY'));
		$scope.formData.montoEmitidasEnero = 0;
		$scope.formData.montoEmitidasFebrero = 0;
		$scope.formData.montoEmitidasMarzo = 0;
		$scope.formData.montoEmitidasAbril = 0;
		$scope.formData.montoEmitidasMayo = 0;
		$scope.formData.montoEmitidasJunio = 0;
		$scope.formData.montoEmitidasJulio = 0;
		$scope.formData.montoEmitidasAgosto = 0;
		$scope.formData.montoEmitidasSeptiembre = 0;
		$scope.formData.montoEmitidasOctubre = 0;
		$scope.formData.montoEmitidasNoviembre = 0;
		$scope.formData.montoEmitidasDiciembre = 0;
		$scope.formData.montoGanadasEnero = 0;
		$scope.formData.montoGanadasFebrero = 0;
		$scope.formData.montoGanadasMarzo = 0;
		$scope.formData.montoGanadasAbril = 0;
		$scope.formData.montoGanadasMayo = 0;
		$scope.formData.montoGanadasJunio = 0;
		$scope.formData.montoGanadasJulio = 0;
		$scope.formData.montoGanadasAgosto = 0;
		$scope.formData.montoGanadasSeptiembre = 0;
		$scope.formData.montoGanadasOctubre = 0;
		$scope.formData.montoGanadasNoviembre = 0;
		$scope.formData.montoGanadasDiciembre = 0;
      $("#modalInsertar").modal("show");
    
    }

//=======================================================
    /* Accion para mostrar el modal  */

    $scope.openModalEditar = function(objetivo){
		$scope.accion = 'editar';
        $("#btnGuardar").attr("accion","editar");
        $("#modalTitulo").html("Editar Objetivo");
        $scope.limpiaCampos();
        $scope.loadPeriodicidad();
		$scope.formData.propuestas = objetivo.NOMBRE;
        $scope.formData.id = objetivo.ID;
        $scope.formData.anhio = parseInt(objetivo.ANHIO);
		$scope.formData.mes = parseInt(objetivo.MES);
        $scope.formData.periodicidad = objetivo.ID_PERIODICIDAD;
       
        $scope.formData.monto = parseFloat(objetivo.VALOR_OBJETIVO);

        $("#modalActualizar").modal("show");

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
	
	if($scope.formData.propuestas == 'Propuestas Emitidas'){
		var objetivo = {
						PROPUESTAS:$scope.formData.propuestas,
						ANHIO:$scope.formData.anhio,
						MONTO_ENERO:$scope.formData.montoEmitidasEnero,
						MONTO_FEBRERO:$scope.formData.montoEmitidasFebrero,
						MONTO_MARZO:$scope.formData.montoEmitidasMarzo,
						MONTO_ABRIL:$scope.formData.montoEmitidasAbril,
						MONTO_MAYO:$scope.formData.montoEmitidasMayo,
						MONTO_JUNIO:$scope.formData.montoEmitidasJunio,
						MONTO_JULIO:$scope.formData.montoEmitidasJulio,
						MONTO_AGOSTO:$scope.formData.montoEmitidasAgosto,
						MONTO_SEPTIEMBRE:$scope.formData.montoEmitidasSeptiembre,
						MONTO_OCTUBRE:$scope.formData.montoEmitidasOctubre,
						MONTO_NOVIEMBRE:$scope.formData.montoEmitidasNoviembre,
						MONTO_DICIEMBRE:$scope.formData.montoEmitidasDiciembre
					};
	}
	if($scope.formData.propuestas == 'Propuestas Ganadas'){
		var objetivo = {
						PROPUESTAS:$scope.formData.propuestas,
						ANHIO:$scope.formData.anhio,
						MONTO_ENERO:$scope.formData.montoGanadasEnero,
						MONTO_FEBRERO:$scope.formData.montoGanadasFebrero,
						MONTO_MARZO:$scope.formData.montoGanadasMarzo,
						MONTO_ABRIL:$scope.formData.montoGanadasAbril,
						MONTO_MAYO:$scope.formData.montoGanadasMayo,
						MONTO_JUNIO:$scope.formData.montoGanadasJunio,
						MONTO_JULIO:$scope.formData.montoGanadasJulio,
						MONTO_AGOSTO:$scope.formData.montoGanadasAgosto,
						MONTO_SEPTIEMBRE:$scope.formData.montoGanadasSeptiembre,
						MONTO_OCTUBRE:$scope.formData.montoGanadasOctubre,
						MONTO_NOVIEMBRE:$scope.formData.montoGanadasNoviembre,
						MONTO_DICIEMBRE:$scope.formData.montoGanadasDiciembre
					};
	}
	/*var objetivo = {
      NOMBRE_OBJETIVO:$scope.formData.nombre_objetivo,
	  PRIORICIDAD:$scope.formData.periodicidad,
	  
  };*/

  $.post( global_apiserver + "/i_objetivos/insert/", JSON.stringify(objetivo), function(respuesta){
      respuesta = JSON.parse(respuesta);
      if (respuesta.resultado == "ok") {
          $scope.loadOjetivos();
        $("#modalInsertar").modal("hide");
        notify("Éxito", "Se ha insertado un nuevo objetivo", "success");

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
        ANHIO:$scope.formData.anhio,
        MONTO:$scope.formData.monto
    };
  console.log(JSON.stringify(objetivo));
  $.post( global_apiserver + "/i_objetivos/update/", JSON.stringify(objetivo), function(respuesta){
      respuesta = JSON.parse(respuesta);
      if (respuesta.resultado == "ok") {
          $scope.loadOjetivos();
          $("#modalActualizar").modal("hide");
          notify("Éxito", "Se ha editado un objetivo", "success");

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

