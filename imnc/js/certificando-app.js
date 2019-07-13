var app = angular.module("certificandoApp", [
	'multipleSelect',
	'checklist-model',
	'720kb.datepicker',
	'multipleDatePicker',
	'ui.grid.autoFitColumns',
	'ui.grid',
	'ui.grid.pagination',
	'ui.grid.selection',
	'ui.grid.cellNav',
	'ui.grid.resizeColumns',
	'ui.grid.pinning'
])
.config(function ($httpProvider) {     
	$httpProvider.defaults.useXDomain = true;   
    delete $httpProvider.defaults.headers.common['X-Requested-With'];
    $httpProvider.defaults.headers.post['Content-Type'] =  'application/x-www-form-urlencoded';
})
.directive('jqdatepicker', function () {
	 return {
        restrict: 'A',
		require: 'ngModel',
		scope: {
			'ngModel': '@',
			'daysAllowed': '@',
			'dateMinLimit': '@',
			'fechaInicio': '@'
          
        },
         link: function (scope, element, attrs, ngModelCtrl) {
			$(element).datepicker_1({
                language: 'es',
				startDate: new Date(scope.fechaInicio),
				
			//    multipleDates: 'true',
			//	dateFormat: 'yyyy-mm-dd',
			//	minDate: new Date()
				onRenderCell: function (date, cellType) {
					 var f1 = "";
					 var d = date.getDate();
					 var m =date.getMonth()+1;
					 var y = date.getFullYear();
					if(m>0 && m<10){
						 m = '0'+m;
					}
					
					if(d>0 && d<10){
						 d = '0'+d;
					 }
					 
					 f1 = y +'/'+m+'/'+d;
					if(scope.daysAllowed){
						var index = -1;
						index = scope.daysAllowed.indexOf(f1);
						if (cellType == 'day' && index == -1 ) {
							return {
								disabled: true
							}
						}
											
						 
					}
					 // VERIFICO QUE SE HALLA CARGADO UNA FECHA LIMITE
					 if(scope.dateMinLimit){
						 
						if (cellType == 'day' && f1 < scope.dateMinLimit) {
							return {
								disabled: true
							}
						}
					 }
				},
				
				
			});
			scope.ngModel=$(element).data('datepicker_1').selectDate(new Date(scope.fechaInicio));
				
				
        }
    };
})
.directive('validFile',function(){
    return {
        require:'ngModel',
        link:function(scope,el,attrs,ctrl){
            ctrl.$setValidity('validFile', el.val() != '');
            //change event is fired when file is selected
            el.bind('change',function(){
                ctrl.$setValidity('validFile', el.val() != '');
                scope.$apply(function(){
                    ctrl.$setViewValue(el.val());
                    ctrl.$render();
                });
            });
        }
    }
})
.directive('dblClickRow', ['$compile', '$parse',  function($compile, $parse) {
	return {
		priority : -190, // run after default uiGridCell directive
		restrict : 'A',
		scope : false,

		compile: function($element, attr) {

			// Get the function at ng-dblclick for ui-grid
			var fn = $parse(attr['ngDblclick'], /* interceptorFn */ null, /* expensiveChecks */ true);

			return function ngEventHandler(scope, element) {

				element.on('dblclick', function(event) {

						var callback = function() {

							if ($scope.gridApi.grid.selection.lastSelectedRow)
							{
								fn($scope, {$event:event, row: $scope.gridApi.grid.selection.lastSelectedRow.entity });
							}
						};

						$scope.$apply(callback);

					}
				)};

		}
	}
}])

