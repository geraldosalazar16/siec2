<span ng-controller="certi_auditores_controller">
<link href="css/certi/certi.css" rel="stylesheet">
<div class="right_col" role="main">
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>{{titulo}}</h2></p>
        
             <button type="button" id="btnfiltro" class="btn btn-primary btn-xs btn-imnc" style="float: right;" ng-click="showFiltrar()">
                 <i class="fa fa-filter"> </i> Filtro</button>
             <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <div id="divFitrar" class="col-md-12"  hidden>
              <form>
                 <div id="divInputContainer">
                    <div class="form-group w_25">
                         <label for="nombreServicio">Servicio:<span class="required"></span></label> &nbsp; &nbsp;
                         <select class="form-control border-dark" style="margin-top: 10px;" id="selectServicio" ng-model="selectServicio" ng-options="servicio as servicio.NOMBRE for servicio in Servicios" ng-change="changeServicio()">
				             <option value="" selected>--	Seleccione un Campo  --</option>
                         </select>
                     </div>
              
                     <div class="form-group w_25" ng-show="selectServicioValor" >
                         <label for="nombreTiposServicio">Tipo de Servicio:<span class="required"></span></label> &nbsp; &nbsp;
                         <select class="form-control border-dark" style="margin-top: 10px;" id="selectTiposServicio" ng-model="selectTiposServicio" ng-options="tiposservicio as tiposservicio.NOMBRE for tiposservicio in tiposServicios" ng-change="changeTipoServicio()">
				             <option value="" selected>--	Seleccione un Campo  --</option>
                         </select>
                     </div>

                     <div class="form-group w_25" ng-show="selectTipoServicioValor" >
                         <label for="nombreRol">Rol:<span class="required"></span></label> &nbsp; &nbsp;
                         <select class="form-control border-dark" style="margin-top: 10px;" id="selectRol" ng-model="selectRol" ng-options="rol as rol.ROL for rol in Roles" ng-change="changeRol()">
				             <option value="" selected>--	Seleccione un Campo  --</option>
                         </select>
                     </div>
                     <div class="form-group w_25" ng-show="selectSectorValor" >
                         <label for="nombreSector">Sector:<span class="required"></span></label> &nbsp; &nbsp;
                         <select class="form-control border-dark" style="margin-top: 10px;" id="selectSector" ng-model="selectSector" ng-options="sector as sector.NOMBRE for sector in Sectores" ng-change="changeSector()">
				             <option value="" selected>--	Seleccione un Campo  --</option>
                         </select>
                     </div>
                     <hr>
                     <div class="col-md-3 col-sm-3 col-xs-12 mt-5" >
                        <button type="button" class="btn btn-success" id="btnclear" ng-click="cancelFilter()">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="btnFiltrar" ng-click="cargaDatosFiltrados()">Filtrar</button>
                     </div>
                 </div>
              </form>
             

              </div>
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
