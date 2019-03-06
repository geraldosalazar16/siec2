/**
 * @ngdoc controller
 * @name controller:empleado_perfil
 *
 * @description
 *
 *
 * @requires $scope
 * */
app.controller('empleado_perfil_controller',['$scope','$http',function($scope,$http) {
//$scope.optionsList=[];
    $scope.tab_selected= getQueryVariable("tab");
    $scope.id= getQueryVariable("id");
    $scope.empleado = null;

// ===================================================================
// ***** 			FUNCION PARA CARGAR LOS CURSOS				 *****
// ===================================================================
    $scope.cargarDatosEmpleado = function(){
        $(".loading").show();
        $http.get(  global_apiserver + "/personal_interno/getById/?id="+$scope.id)
            .then(function( response ){
                $scope.empleado = response.data;
                var fecha = $scope.empleado.FECHA_NACIMIENTO
                $scope.empleado.FECHA_NACIMIENTO = $scope.formatFecha(fecha);
                $(".loading").hide();
            });
    }
// ================================================================================
// *****                       Calcular edad                          *****
// ================================================================================
    $scope.calcular_edad = function(fecha){

        if(typeof fecha !== "undefined") {
        var birthday_arr = fecha.split("/");
        var birthday_date = new Date(birthday_arr[2], birthday_arr[1] - 1, birthday_arr[0]);
        var ageDifMs = Date.now() - birthday_date.getTime();
        var ageDate = new Date(ageDifMs);
        return Math.abs(ageDate.getUTCFullYear() - 1970);
        }else {return false;}
    }
// ================================================================================
// *****                       format fecha                          *****
// ================================================================================
    $scope.formatFecha = function(fecha)
    {
        return fecha.substring(8, 10) + "/" + fecha.substring(5, 7) + "/" + fecha.substring(0, 4);
    }

/////////////////////////////////////////////////////////////////////////
    function getQueryVariable(variable) {
        var query = window.location.search.substring(1);
        var vars = query.split("&");
        for (var i = 0; i < vars.length; i++) {
            var pair = vars[i].split("=");
            if (pair[0] == variable) {
                return pair[1];
            }
        }
    }

// ================================================================================
// *****                        Al cargar la página                           *****
// ================================================================================

    $(document).ready(function () {

        if ($scope.tab_selected == "ficha") {
            $('#myTab li a[href="#tab_ficha"]').tab("show");
        }
        if ($scope.tab_selected == "activos") {
            $('#myTab li a[href="#tab_activos_fijos"]').tab("show");
        }

        $scope.cargarDatosEmpleado();
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



