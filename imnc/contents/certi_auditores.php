<span ng-controller="certi_auditores_controller">
<link href="css/certi/certi.css" rel="stylesheet">
<div class="right_col" role="main">
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>{{titulo}}</h2></p>
        
        
          <div class="clearfix"></div>
        </div>

        <div class="x_content">
			<input type='text' id='txtDate' class='txtFechasAuditoria' />
		
		<br><br>
			<div id="tntGridServices" ui-grid="gridOptions" ui-grid-auto-fit-columns ui-grid-pagination ui-grid-move-columns class='grid' ></div>
        </div>
      </div>
    </div>
  </div>
</div>
    <!-- Modal insertar/actualizar solicitud-->
<div class="modal fade" id="modalDatosAuditoria" tabindex="false" role="dialog" aria-labelledby="myModalLabel"
     data-backdrop="static" data-keyboard="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalTitulo">Descripción</h4>
            </div>
            <div class="modal-body">
                <table class="table">
                    <tr>
                        <td><strong>Referencia:</strong></td>
                        <td>{{REFERENCIA}}</td>
                    </tr>
                     <tr>
                        <td> <strong>Cliente:</strong></td>
                        <td>{{CLIENTE}}</td>
                    </tr>
                    <tr>
                        <td> <strong>Equipo de Auditores:</strong></td>
                        <td><p ng-repeat="x in AUDITORES" > - {{x.NOMBRE+" "+x.APELLIDO_PATERNO+" "+x.APELLIDO_MATERNO+" ("+x.ACRONIMO+")"}}</p></td>
                    </tr>
                    <tr>
                        <td><a	href="./?pagina=ec_tipos_servicio&id_serv_cli_et={{ID_SCE}}">
							<span class="btn btn-sm"	>Ver detalles del servicio</span></a></td>
                        <td>
                            <a	href="./?pagina=auditor_perfil&tab=agenda&id={{ID_AUDITOR}}">
							<span class="btn btn-sm"	>Agenda del Auditor</span></a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

</span>
