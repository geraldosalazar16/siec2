<div class="right_col" role="main" ng-controller="servicio_contratado_historico_controller">
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>{{titulo}}</h2></p>
        
        
          <div class="clearfix"></div>
        </div>

        <div class="x_content">
			<ul class="list-unstyled user_data">
                <li >
                  Cliente: {{DatosServicio.NOMBRE_CLIENTE}}
                </li>

                <li >
                  Servicio: {{DatosServicio.NOMBRE_SERVICIO}}
                </li>

                <li >
                  Etapa: {{DatosServicio.NOMBRE_ETAPA}}
                </li>

                <li >
                  Referencia: {{DatosServicio.REFERENCIA}}
                </li>

              </ul>
			  
			  
			 <table class="table table-striped responsive-utilities jambo_table bulk_action">
                      <thead>
                        <tr class="headings">
                          <th class="column-title">ID</th>
                          <th class="column-title">Descripci&oacuten</th>
                          
						</tr>
                      </thead>

                      <tbody >
							<tr ng-repeat="x in tablaDatos" class="ng-scope  even pointer">
								<td>{{$index+1}}</td>
                                <td ng-if="x.MODIFICACION == 'NUEVO SERVICIO'"> El d&iacutea {{FuncionFecha(x.FECHA_USUARIO)}} el usuario {{x.NOMBRE_USUARIO}} ha creado el servicio  <ul class="list-unstyled" style="font-size: 10px;"><li><strong>[ {{x.ESTADO_ACTUAL}} ]</strong></li></ul></td>
								<td ng-if="x.MODIFICACION == 'MODIFICACION DE ETAPA'">El d&iacutea {{FuncionFecha(x.FECHA_USUARIO)}} el usuario {{x.NOMBRE_USUARIO}} cambio de {{NombreEtapa(x.ESTADO_ANTERIOR)}} a {{NombreEtapa(x.ESTADO_ACTUAL)}} el servicio </td>
								<td ng-if="x.MODIFICACION == 'INSERCION CAMBIO'"> El d&iacutea {{FuncionFecha(x.FECHA_USUARIO)}} el usuario {{x.NOMBRE_USUARIO}} ha insertado el cambio {{TipoCambio(x.ESTADO_ACTUAL)}} con la descripcion {{MostrarDescripcion(x.ESTADO_ACTUAL)}}  en la etapa {{MostrarEtapa(x.ESTADO_ACTUAL)}} del ciclo {{MostrarCiclo(x.ESTADO_ACTUAL)}}</td>
								<td ng-if="x.MODIFICACION == 'MODIFICACION CAMBIO'"> El d&iacutea {{FuncionFecha(x.FECHA_USUARIO)}} el usuario {{x.NOMBRE_USUARIO}} ha modificado el cambio {{TipoCambio(x.ESTADO_ACTUAL)}} con la descripcion {{MostrarDescripcion(x.ESTADO_ANTERIOR)}} a {{MostrarDescripcion(x.ESTADO_ACTUAL)}}  en la etapa {{MostrarEtapa(x.ESTADO_ACTUAL)}} del ciclo {{MostrarCiclo(x.ESTADO_ACTUAL)}} </td>	
								<td ng-if="x.MODIFICACION == 'MODIFICANDO CIFA'"> El d&iacutea {{FuncionFecha(x.FECHA_USUARIO)}} el usuario {{x.NOMBRE_USUARIO}} ha modificado los datos:  <ul class="list-unstyled" style="font-size: 10px;"><li><strong>Estado actual:</strong> [ {{x.ESTADO_ACTUAL}} ]</li><li style="color: #919191;"><strong>Estado anterior:</strong> [ {{x.ESTADO_ANTERIOR}} ]</li></ul>  </td>
								<td ng-if="x.MODIFICACION == 'MODIFICANDO PARTICIPANTE'"> El d&iacutea {{FuncionFecha(x.FECHA_USUARIO)}} el usuario {{x.NOMBRE_USUARIO}} ha modificado los datos del participante:  <ul class="list-unstyled" style="font-size: 10px;"><li><strong>Estado actual:</strong> [ {{x.ESTADO_ACTUAL}} ]</li><li style="color: #919191;"><strong>Estado anterior:</strong> [ {{x.ESTADO_ANTERIOR}} ]</li></ul>  </td>
								<td ng-if="x.MODIFICACION == 'NUEVO PARTICIPANTE'"> El d&iacutea {{FuncionFecha(x.FECHA_USUARIO)}} el usuario {{x.NOMBRE_USUARIO}} ha agregado un nuevo participante:  <ul class="list-unstyled" style="font-size: 10px;"><li><strong>Estado actual:</strong> [ {{x.ESTADO_ACTUAL}} ]</li></ul>  </td>
								<td ng-if="x.MODIFICACION == 'MODIFICANDO CONFIGURACION'"> El d&iacutea {{FuncionFecha(x.FECHA_USUARIO)}} el usuario {{x.NOMBRE_USUARIO}} ha modificado la configuracion del curso:  <ul class="list-unstyled" style="font-size: 10px;"><li><strong>Estado actual:</strong> [ {{x.ESTADO_ACTUAL}} ]</li></ul>  </td>
							</tr>
                      </tbody>

                    </table>  
			
          
        </div>
      </div>
    </div>
  </div>
 </div> 