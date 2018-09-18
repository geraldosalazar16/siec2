<span ng-controller="expediente_entidades_controller">
<div class="right_col" role="main">
  <div class="row">
    <div class="col-md-9 col-sm-9 col-xs-9">
      <div class="x_panel">
        <div class="x_title">
          <div class="input-group">
           <span class="input-group-addon">Tipo&nbsp;:&nbsp;&nbsp;&nbsp;</span>
           <select   class="form-control" ng-model="search.TIPO" placeholder="Por Nombre"/>
            <option value="">Ver todo</option>
            <option ng-repeat="option in entidades" value="{{option.DESCRIPCION}}">{{option.DESCRIPCION}}</option>
          </select>
          </div>
          <div class="input-group">
           <span class="input-group-addon">Validez:</span>
           <select class="form-control" ng-model="search.VALIDO" placeholder="Por Nombre">
           <option value="">Ver Todo</option>
           <option value="0">No valido</option>
           <option value="1">Valido</option>
           </select>
         </div>
          <div class="input-group">
           <span class="input-group-addon">Buscar:</span>
           <input type="text" class="form-control" ng-model="search.NOMBRE_TIPO" placeholder="Por Nombre">
         </div>
          <div class="input-group">
           <span class="input-group-addon">Buscar:</span>
           <input type="text" class="form-control" ng-model="search.EXPEDIENTE" placeholder="Por Expediente">
         </div>
         <div class="input-group">
           <span class="input-group-addon">Fecha:</span>
           <select   class="form-control" ng-model="search.EXPEDIENTE" placeholder="Por Nombre"/>
            <option ng-repeat="option in fecha" value="{{fechas.id}}">{{fechas.nombre}}</option>
          </select>
          </div>
         
        <p><h2>{{titulo}}</h2></p>
        <p>
        
          <div class="clearfix"></div>
        </div>

        <div class="x_content">

          <table class="table table-striped responsive-utilities jambo_table bulk_action">
            <thead>
              <tr class="headings">
                <th class="column-title">Tipo</th>
                <th class="column-title">Nombre</th>
                <th class="column-title">Expediente</th>
                <th class="column-title">Validez&nbsp;&nbsp;</th>
                <th>&nbsp;&nbsp;</th>
              </tr>
            </thead>

            <tbody>
        <tr ng-repeat="x in expedienteentidades | filter:search">
		
          <td>{{x.TIPO}}</td>
          <td>{{x.NOMBRE_TIPO}}</td>
          <td>{{x.EXPEDIENTE}}</td>
          <td ng-if="x.VALIDO == 1">Valido</td>
          <td ng-if="x.VALIDO == 0">No Valido</td> 
          <td>
            <a href="./?pagina=registro_expediente&id={{x.ID}}&id_entidad={{x.ID_ENTIDAD}}" class="btn btn-primary btn-xs btn-imnc" style="float: left;">
              <i class="fa fa-edit"> </i> EXPEDIENTES
            </a>
          </td> 
        </tr>
            </tbody>

          </table>  
        </div>
      </div>
    </div>
  </div>
</div>
</span>
