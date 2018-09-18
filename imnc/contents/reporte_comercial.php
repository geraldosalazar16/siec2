<link href="https://fonts.googleapis.com/css?family=Dosis|Roboto+Slab" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="css/jquery-ui-themes/jquery-ui.min.css">
<div class="right_col" role="main" ng-controller="reporte_comercial_controller">
	<div class="row">
		<div class="col-md-12">
			<div class="x_panel">
				<div class="x_title">
					<p>
						<h1>{{titulo}}</h1>
					</p>
				</div>
				<div class="x_content">
					<div class="col-md-4">
						<form class="form-horizontal form-label-left ng-pristine ng-valid">
							<div class="input-group">
								<span class="input-group-addon">Buscar:</span>
								<input type="text" class="form-control" ng-model="search.CONTRATO" placeholder="Por tipo de contrato">
							</div>
						</form>
					</div>
					<div class="col-md-4">
						<form class="form-horizontal form-label-left ng-pristine ng-valid">
							<div class="input-group">
								<span class="input-group-addon">Buscar:</span>
								<input type="text" class="form-control" ng-model="search.NOMBRE" placeholder="Por nombre de prospecto o cliente">
							</div>
						</form>
					</div>
					<div class="col-md-4">
						<form class="form-horizontal form-label-left ng-pristine ng-valid">
							<div class="input-group">
								<span class="input-group-addon">Buscar:</span>
								<input type="text" class="form-control" ng-model="search.COTIZACION" placeholder="Por # de cotización">
							</div>
						</form>
					</div>

					<div class="col-md-4">
						<form class="form-horizontal form-label-left ng-pristine ng-valid">
							<div class="input-group">
								<span class="input-group-addon">Buscar:</span>
								<input type="text" class="form-control" ng-model="search.INICIALES" placeholder="Por iniciales del ejecutivo">
							</div>
						</form>
					</div>
					<div class="col-md-4">
						<div class="input-group">
							<span class="input-group-addon">Fecha inicio mayor a:</span>
							<input class="form-control" id="datepicker" type="text" ng-model="search.iFecha">
						</div>
					</div>
					<div class="col-md-4">
						<div class="input-group">
							<span class="input-group-addon">Fecha final menor a:</span>
							<input id="datepicker2" type="text" class="form-control" ng-model="search.fFecha">
						</div>
					</div>
					
					<div class="col-md-4">
						<div class="input-group">
							<span class="input-group-addon">Estatus del seguimiento</span>
							<select ng-model="ESTATUS" id="ESTATUS" class="form-control col-md-7 col-xs-12">
        <option ng-repeat="item in estatus" value="{{item.ID}}">{{item.DESCRIPCION}}</option>
        </select>
						</div>
					</div>
					
					<button ng-click="envia()">Crear reporte</button>
				</div>
			</div>
		</div>
	</div>
</div>




	<script type="text/javascript" src="js/moment/moment.min.js"></script>

	<style type="text/css">
		.bo {
			font-family: 'Roboto Slab', serif;
			text-decoration: none;
			list-style: none;
		}
		
		.bo2 {
			font-family: font-family: 'Dosis', sans-serif;
		}
	</style>