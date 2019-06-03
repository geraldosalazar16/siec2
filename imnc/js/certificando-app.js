var app = angular.module("certificandoApp", ['multipleSelect','checklist-model','720kb.datepicker','multipleDatePicker','ui.grid','ui.grid.pinning','ui.grid.autoFitColumns'])
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
});