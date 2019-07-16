<span ng-controller="solicitudes_facturacion_detalles_controller">
    <style>
.expander
{
    margin-left: 10px;
}
.expander-h3
{
    border-radius: 5px;
    padding:5px 5px 5px 20px;
    margin: 3px;
    font-size: 18px;
    cursor:pointer;
    -moz-box-shadow:0 1px 2px #CCC;
    -webkit-box-shadow:0 1px 2px #CCC;
    box-shadow:0 1px 2px #CCC;


}
.expanded{
    background-color: rgba(177,131,11,0.32);
}
.collapsed{
    background-color: white;
}
.expander-div
{
    color:#000000;
    background-color: white;
    border-radius: 10px;
    border: rgba(156, 178, 182, 0.32) 1px solid;
    padding:20px;
    width: 98%;
    margin: auto;
}
}
</style>
<div class="right_col" role="main" >
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>Solicitud de Facturación</h2></p>

        
          <div class="clearfix"></div>
        </div>

        <div class="x_content">
			
				<ul class="list-unstyled user_data" style="display: inline-block !important;">
                     <li><b>
					Comprobante fiscal:<i>
                         <a style="margin-left: 10px;" class="btn btn-sm" ng-if="solicitud.DOCUMENTO.EXIST" href="{{ solicitud.DOCUMENTO.RUTA }}" target="_blank"> {{solicitud.DOCUMENTO.NOMBRE}}</a>
                             <span ng-if="!solicitud.DOCUMENTO.EXIST"> No se encuentra el documento en la ruta</span>
                             </i></b>
                     </li>

					<li ng-if="solicitud.DESCRIPCION" ><b>
					Descripción: <i> {{solicitud.DESCRIPCION}} </i></b>
					</li>

                    <li ng-if="solicitud.CONTACTO_FACTURACION"><b>
					Contacto de Facturación: <i> {{solicitud.CONTACTO_FACTURACION.NOMBRE_CONTACTO}} </i></b>
                        <button class="btn btn-sm" style="margin-left: 20px;" ng-click="openModalContacto()">Detalles</button>
					</li>
                    <li ng-if="!solicitud.CONTACTO_FACTURACION"><b>
					Contacto de Facturación: <i> No se encontró </i></b>
					</li>

				</ul>


				<div class="" role="tabpanel" data-example-id="togglable-tabs">
							<ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
								<li role="presentation" class="active">
									<a href="#tab_informacion" id="tab_informacion-tab"  role="tab" data-toggle="tab" aria-expanded="true" ng-click="DatosInformacion()">
                          			Informaci&oacuten adicional</a>
								</li>
								
								<li role="presentation">
									<a href="#tab_historico" id="tab_historico-tab"  role="tab"  data-toggle="tab" aria-expanded="false">
									Histórico</a>
								</li>						
								
								<li role="presentation"> <!-- ng-if="DatosServicio.ID_SERVICIO == 1">-->
								<a href="#tab_documentos" id="tab_documentos-tab"  role="tab" data-toggle="tab" aria-expanded="true" ng-click="loadDocumentos(id_solicitud)" >
                          			Documentos</a>
								</li>
							</ul>
							<div id="myTabContent" class="tab-content">
								<div role="tabpanel" class="tab-pane fade active in" id="tab_informacion" aria-labelledby="home-tab">
									<div class="x_title">
										<p><h2>Informaci&oacuten adicional</h2></p>
                                        <div class="clearfix"></div>
                                    </div>
								</div>
								<div role="tabpanel" class="tab-pane fade" id="tab_historico" aria-labelledby="profile-tab">
									<div class="x_title">
										<p><h2>Histórico</h2></p>
										<div class="clearfix"></div>
									</div>
									<table class="table">
									<thead>
										<tr class="headings">
											<th class="column-title">Clave del sector</th>
											<th class="column-title">Nombre del sector</th>
								<!--			<th class="column-title">Principal</th>	-->
											<th class="column-title"></th>
										</tr>
									</thead>
								</table>
								</div>
                                <div role="tabpanel" class="tab-pane fade" id="tab_documentos" aria-labelledby="profile-tab">
									<div class="x_title">
                                        <p><h2>Documentos</h2></p>
                                        <div class="clearfix"></div>
									</div>
                                    <div id="expander" class="expander">
                                    </div>
                                </div>
      </div>
    </div>
  </div>
    </div>
   </div>
    <div class="modal fade"  id="modalContacto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="true">
    <div class="modal-dialog" role="document" id="modal-size" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalTituloInst">Contacto de Facturación</h4>
            </div>
            <div class="modal-body" id="body-modalIns" style="overflow: auto;">
                <table class="table table-striped" style="background: white">
                    <tr ng-if="solicitud.CONTACTO_FACTURACION.NOMBRE_CONTACTO">
                        <td>Nombre del Contacto:</td>
                        <th>{{solicitud.CONTACTO_FACTURACION.NOMBRE_CONTACTO}}</th>
                    </tr>
                    <tr ng-if="solicitud.CONTACTO_FACTURACION.ID_TIPO_CONTACTO">
                        <td>Tipo de contacto:</td>
                        <th>{{solicitud.CONTACTO_FACTURACION.ID_TIPO_CONTACTO}}</th>
                    </tr>
                    <tr ng-if="solicitud.CONTACTO_FACTURACION.CARGO">
                        <td>Cargo:</td>
                        <th>{{solicitud.CONTACTO_FACTURACION.CARGO}}</th>
                    </tr>
                    <tr ng-if="solicitud.CONTACTO_FACTURACION.EMAIL">
                        <td>Correo Electrónico:</td>
                        <th>{{solicitud.CONTACTO_FACTURACION.EMAIL}}</th>
                    </tr>
                    <tr ng-if="solicitud.CONTACTO_FACTURACION.TELEFONO_FIJO">
                        <td>Telefono Fijo:</td>
                        <th>{{solicitud.CONTACTO_FACTURACION.TELEFONO_FIJO}}</th>
                    </tr>
                    <tr ng-if="solicitud.CONTACTO_FACTURACION.TELEFONO_MOVIL">
                        <td>Telefono Móvil:</td>
                        <th>{{solicitud.CONTACTO_FACTURACION.TELEFONO_MOVIL}}</th>
                    </tr>
                </table>
            </div>
        </div>

    </div>
</div>
</div>

</span>
