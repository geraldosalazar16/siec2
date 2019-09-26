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
                <h2>Relaci&oacuten de documentos registrados</h2>
                <br>
                <div id="expander" class="expander"></div>
				    
					
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
