<div class="modal fade"  id="modalSelectInstructor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="true">
    <div class="modal-dialog" role="document" id="modal-size" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalTituloInst">Seleccione un Instructor</h4>
            </div>
            <div class="modal-body" id="body-modalIns" style="overflow: auto;">
                <h2 style="color: #1c1c1c;">Para Curso: {{ DatosServicio.NOMBRE_CURSO }}</h2>
                <div class="form-group pull-right">
                    <label for="select_instructor">Buscar:</label>
                    <input class="form-control" type="search" ng-model="formDataConfiguracion.searchText">
                </div>
                <table class="table table-hover" style="background-color: transparent">
                    <thead id="thead-modal-explora-sitios">
                    <tr>
                        <th style="width: 40%;">Datos del Instructor</th>
                        <th style="width: 25%;">Roles</th>
                        <th style="width: 25%;">Calif. Cursos</th>
                        <th style="width: 10%;"></th>
                    </tr>
                    </thead>
                    <tbody id="tbody-modal-explora-sitios">
                    <tr ng-repeat="instructor in instructoresCursos | filter:formDataConfiguracion.searchText">
                        <td td style="font-size: 12px;">
                            <table style="background-color: transparent">
                                <tr>
                                    <td>
                                        <strong><label ng-if="id_instructor == instructor.ID" style="color: #1c1c1c;">{{instructor.NOMBRE}} <span class="glyphicon glyphicon-ok" aria-hidden="true"></span></label></strong>
                                        <strong><label ng-if="id_instructor != instructor.ID" style="color: #1c1c1c;">{{instructor.NOMBRE}}</label></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{instructor.STATUS}}<br>
                                    </td>
                                </tr>
                            </table>

                            <input type="text" id="lb-{{instructor.ID}}" value="{{instructor.NOMBRE}}" hidden>


                        </td>
                        <td style="font-size: 11px;">

                            <div ng-repeat="rol in instructor.ROLES">
                                <label> {{rol.ROL}} <span ng-if="rol.ID_ROL == 7"  class="glyphicon glyphicon-ok" aria-hidden="true"></span></label>
                            </div>
                        </td>
                        <td style="font-size: 11px;">
                            <div ng-repeat="curso in instructor.CURSOS">
                                <label>{{curso.NOMBRE_CURSO}} <span  ng-if="DatosServicio.ID_CURSO == curso.ID_CURSO" class="glyphicon glyphicon-ok" aria-hidden="true"></span></label>
                            </div>
                        </td>

                        <td>
                            <button  type="button"  class="btn btn-default btn-xs" style="float: right;" disabled  ng-if="instructor.STATUS=='inactivo' || instructor.ISROL==false || instructor.ISCURSO == false"> seleccionar </button>
                            <button  id="btn-{{instructor.ID}}" type="button" class="btn btn-primary btn-xs btn-imnc " style="float: right;" ng-if="instructor.STATUS=='activo' && instructor.ISROL==true && instructor.ISCURSO == true" ng-click="onSelectInstructor(instructor.ID)" ng-disabled="id_instructor == instructor.ID"> seleccionar</button>
                            <div  style="font-size: 9px;" id="error-{{instructor.ID}}" hidden></div>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>