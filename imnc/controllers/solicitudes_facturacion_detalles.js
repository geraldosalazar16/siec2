// =======================================================================================
// ***** Creación del controlador con el nombre "solicitudes_facturacion_detalles_controller". *****
// =======================================================================================
app.controller('solicitudes_facturacion_detalles_controller', ['$scope', '$http', function($scope, $http) {
    $scope.solicitud = [];
    $scope.id_solicitud = getQueryVariable("id");

    // cargar datos de la solicitud
    function loadSolicitud(id_solicitud) {
        $http.get(`${global_apiserver}/facturacion_solicitudes/getByIdDetalles?id=${id_solicitud}`)
            .then(response => {
            if (response.data.resultado === 'error') {
            notify('Error', response.data.mensaje, 'error')
        } else {
            $scope.solicitud = response.data;
            console.log($scope.solicitud);
        }
    })
    .catch(error => notify('Error', error.message, 'error'))
    }

    $scope.openModalContacto = function () {
        $("#modalContacto").modal("show");
    }

    $(document).ready(function () {
        loadSolicitud($scope.id_solicitud);
    });
}])
function getQueryVariable(variable) {
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split("=");
        if (pair[0] == variable) {
            return pair[1];
        }
    }
    alert('Query Variable ' + variable + ' not found');
}
