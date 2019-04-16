var app = angular.module("certificandoApp", ['multipleSelect','checklist-model','720kb.datepicker'])
.config(function ($httpProvider) {     
	$httpProvider.defaults.useXDomain = true;   
    delete $httpProvider.defaults.headers.common['X-Requested-With'];
    $httpProvider.defaults.headers.post['Content-Type'] =  'application/x-www-form-urlencoded';
})
.directive('jqdatepicker', function () {
    return {
        restrict: 'A',
        require: 'ngModel',
         link: function (scope, element, attrs, ngModelCtrl) {
            $(element).datepicker({
                language: 'es',
                multipleDates: 'true'
            });
        }
    };
});


