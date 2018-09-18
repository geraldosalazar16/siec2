<link href="https://fonts.googleapis.com/css?family=Dosis|Roboto+Slab" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="css/jquery-ui-themes/jquery-ui.min.css">
<div class="right_col" role="main" ng-controller="reporte_prospecto_controller">
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
								<input type="text" class="form-control" ng-model="search.ASUNTO" placeholder="Por asunto">
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
								<input type="text" class="form-control" ng-model="search.DESCRIPCION" placeholder="Por tipo de asunto">
							</div>
						</form>
					</div>

					<div class="col-md-4">
						<form class="form-horizontal form-label-left ng-pristine ng-valid">
							<div class="input-group">
								<span class="input-group-addon">Buscar:</span>
								<input type="text" class="form-control" ng-model="search.FOLIO" placeholder="Por folio">
							</div>
						</form>
					</div>
					<div class="col-md-4">
						<div class="input-group">
							<span class="input-group-addon">Fecha inicio mayor a:</span>
							<input class="form-control" id="datepicker" type="text" ng-model="iFecha">
						</div>
					</div>
					<div class="col-md-4">
						<div class="input-group">
							<span class="input-group-addon">Fecha final menor a:</span>
							<input id="datepicker2" type="text" class="form-control" ng-model="fFecha">
						</div>
					</div>
					<div class="input-group" style="display:none;">
						<span class="input-group-addon">Fecha:</span>
						<select class="form-control" ng-model="search.MES" ng-options="option.id as option.nombre for option in fechas" placeholder="Por Nombre">
							<option value="">--Seleccione--</option>
						</select>
					</div>

				</div>
			</div>
		</div>
	</div>


	<div class="row">
		<div class="col-md-12">
			<div class="x_panel">
				<div class="x_content">
					<div class="col-md-6 col-sm-6 col-xs-12 animated fadeInDown" ng-repeat="x in calendario | filter: range">
						<div class="well profile_view">
							<div class="col-sm-12" style="height: 200px;">
								<h2 class="boo">{{fecha.nombre}}</h2>

								<h4 class="brief"><i>{{x.ASUNTO}}</i></h4>
								<div class="left col-xs-10">
									<ul class="list-unstyled">
										<li ng-show="x.ENTIDAD == 1"><strong>Prospecto:</strong> {{x.NOMBRE}} </li>
										<li ng-show="x.ENTIDAD == 2"><strong>Cliente:</strong> {{x.NOMBRE}} </li>
										<li><strong>Tipo de asunto:</strong> {{x.DESCRIPCION}}</li>
										<li><strong>Fecha de inicio:</strong> {{x.FECHA_INICIO}}</li>
										<li><strong>Fecha de fin:</strong> {{x.FECHA_FIN}}</li>
										<li ng-show="x.FOLIO > 0"><strong>Folio:</strong> {{x.FOLIO}}</li>
										<li><strong>Observaciones:</strong> {{x.OBSERVACIONES}}</li>
										<li ng-show="x.ID_COTIZACION > 0" ><a href="./?pagina=ver_cotizacion&id_cotizacion={{x.ID_COTIZACION}}"><strong>Ver cotización</strong> </a></li>
									</ul>
								</div>
							</div>
						</div>
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