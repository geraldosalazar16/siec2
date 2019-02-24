<span ng-controller="reportes_controller">
<div class="right_col" role="main">
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>Reportes</h2></p>
             <?php
             if ($modulo_permisos["REPORTES"]["registrar"] == 1) {
                 echo '<p>';
                 echo '  <button type="button" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc" style="float: right;" ng-click="openModalInsertUpdate()">  <i class="fa fa-plus"> </i> Nuevo Reporte </button>';
                 echo '</p>';
             }
             ?>


          <div class="clearfix"></div>
        </div>

        <div class="x_content">

          <table class="table table-striped responsive-utilities jambo_table bulk_action">
            <thead>
              <tr class="headings">
                <th class="column-title" style="width:40%">Nombre</th>
				<th class="column-title" style="width:30%">Área </th>
                <th class="column-title" style="width:30%">Creado</th>
                <th class="column-title" style="width:30%">Público</th>
                <th class="column-title"></th>
              </tr>
            </thead>

            <tbody>
				<tr ng-repeat="(key, item) in reportes" class="ng-scope even pointer">
					<td>{{item.NOMBRE}}</td>
					<td>{{item.AREA}}</td>
					<td>{{item.FECHA_CREACION}}</td>
					<td><i class="fa fa-check-circle" ng-show="item.COMPARTIDO == 1"></i></td>
					<td >
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" > Opciones
											<span class="caret"></span>
											<span class="sr-only">Toggle Dropdown</span>
										</button>
										<ul class="dropdown-menu pull-right">
											<li ng-if='<?php echo $modulo_permisos["REPORTES"]["ver"] == 1 ?>' ng-show="item.ID_USUARIO==id_usuario">
												<a	ng-click="generarEXCEL(key)">
												<span class="labelAcordeon"	><i class="fa fa-list"></i> Ver</span></a>
											</li>
                                            <li ng-if='<?php echo $modulo_permisos["REPORTES"]["editar"] == 1 ?>' ng-show="item.ID_USUARIO==id_usuario">
												<a	ng-click="openModalInsertUpdate(key)">
												<span class="labelAcordeon"	><i class="fa fa-pencil"></i> Editar</span></a>
											</li>
                                             <li ng-if='<?php echo $modulo_permisos["REPORTES"]["eliminar"] == 1 ?>' ng-show="item.ID_USUARIO==id_usuario">
												<a	ng-click="eliminar(key)">
												<span class="labelAcordeon"	><i class="fa fa-remove"></i> Eliminar</span></a>
											</li>
                                        </ul>
                    </div>
					</td>
					
					
				</tr>
            </tbody>

          </table>



      </div>
    </div>
  </div>
</div>
 <form  id="formReporte" target="VentanaReporteXLS"  method="POST" action="./generar/xls/reportes/index.php" enctype="multipart/form-data" >
     <input type="hidden" id="hiddenNombre" name="NOMBRE" value=""  />
	 <input type="hidden" id="hiddenArea" name="AREA" value="" />
     <input type="hidden" id="hiddenColumnas" name="COLUMNAS" value="" />
 </form>
<!-- Modal insertar/actualizar-->
<div class="modal fade" id="modalInsertarActualizar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" >{{titulo}}</h4>
      </div>
      <div class="modal-body">
              <form>
              <div class="form-group">
                <label for="nombre_participante">Nombre<span class="required">*</span></label>
                <div>
                    <input type="text" class="form-control" id="nombre" name="nombre" ng-model="formData.nombre"  required
                      ng-change="error_nombre = (formData.nombre?'':'Complete este campo')"    >
                    <span class="text-danger" >{{error_nombre}}</span>
                </div>
			</div>
             <div class="form-group">
                <label for="nombre_participante">Areas<span class="required">*</span></label>
                <div>
                    <select ng-model="formData.select_area" ng-options="area.NOMBRE for area in areas track by area.ID_AREA"
                            class="form-control" id="select_area" name="select_area"  required
                             ng-change="showDualListBox();error_select_area = (formData.select_area?'':'Complete este campo')" ">
                                    <option value="">---Seleccione un área---</option>
                            </select>
                    <span class="text-danger" >{{error_select_area}}</span>
                </div>
			</div>
            <div class="form-group">
             <div  style="margin-top: 10px;">
                <label >
                  <input  id="tiene_cliente" type="checkbox" ng-model="formData.publico"   value="true"  > Este reporte será público?
                </label>
             </div>
            </div>

            <div class="form-group"  ng-show= "formData.select_area">
               <select ng-model="formData.column" id="column" name="colunm" multiple="multiple" size="10" name="duallistbox_demo1[]" title="duallistbox_demo1[]" ></select>
                <span class="text-danger" >{{error_column}}</span>
             </div>

           
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="btnCerrar">Cancelar</button>
        <button type="button" class="btn btn-primary" ng-click="submitGuardar(false)" id="btnGuardar">Generar</button>
        <button type="button" class="btn btn-primary" ng-click="submitGuardar(true)" id="btnGuardar">Guardar y Generar</button>
      </div>
    </div>
  </div>
</div>
</span>
