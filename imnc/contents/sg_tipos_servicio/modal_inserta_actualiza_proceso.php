<!-- Modal proceso wizard-->
<div class="modal fade" id="modalProceso" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTitulo">Proceso</h4>
      </div>
      <div class="modal-body">
          <div id="wizard" class="form_wizard wizard_horizontal">
              <ul class="wizard_steps">
                <li>
                  <a href="#step-1">
                    <span class="step_no">
                      1
                    </span>
                    <span class="step_descr">
                      Paso 1<br><xsmall>Recepción de la solicitud</xsmall>
                    </span>
                  </a>
                </li>
                <li>
                  <a href="#step-2">
                    <span class="step_no">
                      2
                    </span>
                    <span class="step_descr">
                      Paso 2<br><xsmall>&nbsp;&nbsp;&nbsp;Revisión preeliminar&nbsp;&nbsp;</xsmall>
                    </span>
                  </a>
                </li>
                <li>
                  <a href="#step-3">
                    <span class="step_no">
                      3
                    </span>
                    <span class="step_descr">
                      Paso 3<br><xsmall>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Revisión técnica&nbsp;&nbsp;&nbsp;&nbsp;</xsmall>
                    </span>
                  </a>
                </li>
                <li>
                  <a href="#step-4">
                    <span class="step_no">
                      4
                    </span>
                    <span class="step_descr">
                      Paso 4<br><xsmall>Elaborar cotización</xsmall>
                    </span>
                  </a>
                  <li>
                  <a href="#step-5">
                    <span class="step_no">
                      5
                    </span>
                    <span class="step_descr">
                      Paso 5<br><xsmall>Revisar la cotización</xsmall>
                    </span>
                  </a>
                  <li>
                  <a href="#step-6">
                    <span class="step_no">
                      6
                    </span>
                    <span class="step_descr">
                      Paso 6<br><xsmall>Programar evaluación</xsmall>
                    </span>
                  </a>
                  <li>
                  <a href="#step-7">
                    <span class="step_no">
                      7
                    </span>
                    <span class="step_descr">
                      Paso 7<br><xsmall>Evaluación etapa 1 </xsmall>
                    </span>
                  </a>
                  <li>
                  <a href="#step-8">
                    <span class="step_no">
                      8
                    </span>
                    <span class="step_descr">
                      Paso 8<br><xsmall>Evaluación etapa 2 </xsmall>
                    </span>
                  </a>
              </ul>

              <div id="step-1">
                <form class="form-horizontal form-label-left">
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Fecha de ingreso 
                      <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input placeholder="Formato: dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" type="text" required="required" class="form-control col-md-7 col-xs-12">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-offset-3 col-md-5 col-sm-5 col-xs-12">
                      <div id="singleupload_paso1_1">
                        <div class="ajax-file-upload" style="position: relative; overflow: hidden; cursor: default;">
                          Upload
                          <form method="POST" action=global_apiserver + "/sg_proceso/uploadFile/" enctype="multipart/form-data" style="margin: 0px; padding: 0px;">
                              <input type="file" id="ajax-upload-id-1460599196294" name="myfile" accept="*" style="position: absolute; cursor: pointer; top: 0px; width: 100%; height: 100%; left: 0px; z-index: 100; opacity: 0;">
                          </form>
                        </div>
                        <div>
                        <!--es necesario este div-->
                        </div>
                      </div>
                      <a href="" target="_blank" id="file_paso_1_1">Ver archivo</a>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-offset-3 col-md-5 col-sm-5 col-xs-12">
                      <div id="singleupload_paso1_2">
                        <div class="ajax-file-upload" style="position: relative; overflow: hidden; cursor: default;">
                          Upload
                          <form method="POST" action=global_apiserver + "/sg_proceso/uploadFile/" enctype="multipart/form-data" style="margin: 0px; padding: 0px;">
                              <input type="file" id="ajax-upload-id-1460599196294" name="myfile" accept="*" style="position: absolute; cursor: pointer; top: 0px; width: 100%; height: 100%; left: 0px; z-index: 100; opacity: 0;">
                          </form>
                        </div>
                        <div>
                        <!--es necesario este div-->
                        </div>
                      </div>
                      <a href="" target="_blank" id="file_paso_1_2">Ver archivo</a>
                    </div>
                  </div>
                </form>
              </div> 
              <!-- Fin de step-1 -->

              <div id="step-2">
                <form class="form-horizontal form-label-left">
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Plazo <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" value="2 días hábiles" required="required" class="form-control col-md-7 col-xs-12" disabled="">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Fecha de inicio 
                      <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input placeholder="Formato: dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" type="text" required="required" class="form-control col-md-7 col-xs-12" id="txtFechaInicio">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Fecha de finalización 
                      <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input placeholder="Formato: dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" type="text" required="required" class="form-control col-md-7 col-xs-12">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-offset-3 col-md-4 col-sm-4 col-xs-12"><input type="checkbox" value="">&nbsp;&nbsp;&nbsp; Documentación completa</label>
                  </div>
                  
                </form>
              </div> 

              <div id="step-3">
                <form class="form-horizontal form-label-left">
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Plazo <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" value="10 días hábiles" required="required" class="form-control col-md-7 col-xs-12" disabled="">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Fecha de inicio 
                      <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input placeholder="Formato: dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" type="text" required="required" class="form-control col-md-7 col-xs-12 txtFechaInicio">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-offset-3 col-md-4 col-sm-4 col-xs-12"><input type="checkbox" value="">&nbsp;&nbsp;&nbsp; ¿Se cuenta con los recursos?</label>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Fecha de finalización 
                      <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input placeholder="Formato: dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" type="text" required="required" class="form-control col-md-7 col-xs-12">
                    </div>
                  </div>
                  
                  
                </form>
              </div>

              <div id="step-4">
                <form class="form-horizontal form-label-left">
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Plazo <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" value="3 días hábiles" required="required" class="form-control col-md-7 col-xs-12" disabled="">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Fecha de inicio 
                      <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input placeholder="Formato: dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" type="text" required="required" class="form-control col-md-7 col-xs-12 txtFechaInicio">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Fecha de finalización 
                      <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input placeholder="Formato: dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" type="text" required="required" class="form-control col-md-7 col-xs-12">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-offset-3 col-md-5 col-sm-5 col-xs-12">
                      <div id="singleupload_paso4">
                        <div class="ajax-file-upload" style="position: relative; overflow: hidden; cursor: default;">
                          Upload
                          <form method="POST" action=global_apiserver + "/sg_proceso/uploadFile/" enctype="multipart/form-data" style="margin: 0px; padding: 0px;">
                              <input type="file" id="ajax-upload-id-1460599196294" name="myfile" accept="*" style="position: absolute; cursor: pointer; top: 0px; width: 100%; height: 100%; left: 0px; z-index: 100; opacity: 0;">
                          </form>
                        </div>
                        <div>
                        <!--es necesario este div-->
                        </div>
                      </div>
                      <a href="" target="_blank" id="file_paso_4">Ver archivo</a>
                    </div>
                  </div>
                  
                </form>
              </div>

              <div id="step-5">
                <form class="form-horizontal form-label-left">
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Plazo <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" value="1 día hábiles" required="required" class="form-control col-md-7 col-xs-12" disabled="">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Fecha de inicio 
                      <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input placeholder="Formato: dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" type="text" required="required" class="form-control col-md-7 col-xs-12 txtFechaInicio">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Fecha de finalización 
                      <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input placeholder="Formato: dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" type="text" required="required" class="form-control col-md-7 col-xs-12">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-offset-3 col-md-5 col-sm-5 col-xs-12">
                      <div id="singleupload_paso5">
                        <div class="ajax-file-upload" style="position: relative; overflow: hidden; cursor: default;">
                          Upload
                          <form method="POST" action=global_apiserver + "/sg_proceso/uploadFile/" enctype="multipart/form-data" style="margin: 0px; padding: 0px;">
                              <input type="file" id="ajax-upload-id-1460599196294" name="myfile" accept="*" style="position: absolute; cursor: pointer; top: 0px; width: 100%; height: 100%; left: 0px; z-index: 100; opacity: 0;">
                          </form>
                        </div>
                        <div>
                        <!--es necesario este div-->
                        </div>
                      </div>
                      <a href="" target="_blank" id="file_paso_5">Ver archivo</a>
                    </div>
                  </div>
                  
                </form>
              </div>

              <div id="step-6">
                <form class="form-horizontal form-label-left">
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Plazo <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" value="3 días hábiles" required="required" class="form-control col-md-7 col-xs-12" disabled="">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Fecha de inicio 
                      <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input placeholder="Formato: dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" type="text" required="required" class="form-control col-md-7 col-xs-12 txtFechaInicio">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Fecha de finalización 
                      <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input placeholder="Formato: dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" type="text" required="required" class="form-control col-md-7 col-xs-12">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-offset-3 col-md-5 col-sm-5 col-xs-12">
                      <div id="singleupload_paso6">
                        <div class="ajax-file-upload" style="position: relative; overflow: hidden; cursor: default;">
                          Upload
                          <form method="POST" action=global_apiserver + "/sg_proceso/uploadFile/" enctype="multipart/form-data" style="margin: 0px; padding: 0px;">
                              <input type="file" id="ajax-upload-id-1460599196294" name="myfile" accept="*" style="position: absolute; cursor: pointer; top: 0px; width: 100%; height: 100%; left: 0px; z-index: 100; opacity: 0;">
                          </form>
                        </div>
                        <div>
                        <!--es necesario este div-->
                        </div>
                      </div>
                      <a href="" target="_blank" id="file_paso_6">Ver archivo</a>
                    </div>
                  </div>
                  
                </form>
              </div>

              <div id="step-7">
                <form class="form-horizontal form-label-left">
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Fecha de evaluación 
                      <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input placeholder="Formato: dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" type="text" required="required" class="form-control col-md-7 col-xs-12">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Fecha de finalización 
                      <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input placeholder="Formato: dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" type="text" required="required" class="form-control col-md-7 col-xs-12">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-offset-3 col-md-12 col-sm-12 col-xs-12">
                      <div id="singleupload_paso7_1">
                        <div class="ajax-file-upload" style="position: relative; overflow: hidden; cursor: default;">
                          Upload
                          <form method="POST" action=global_apiserver + "/sg_proceso/uploadFile/" enctype="multipart/form-data" style="margin: 0px; padding: 0px;">
                              <input type="file" id="ajax-upload-id-1460599196294" name="myfile" accept="*" style="position: absolute; cursor: pointer; top: 0px; width: 100%; height: 100%; left: 0px; z-index: 100; opacity: 0;">
                          </form>
                        </div>
                        <div>
                        <!--es necesario este div-->
                        </div>
                      </div>
                      <a href="" target="_blank" id="file_paso_7_1">Ver archivo</a>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-offset-3 col-md-12 col-sm-12 col-xs-12">
                      <div id="singleupload_paso7_2">
                        <div class="ajax-file-upload" style="position: relative; overflow: hidden; cursor: default;">
                          Upload
                          <form method="POST" action=global_apiserver + "/sg_proceso/uploadFile/" enctype="multipart/form-data" style="margin: 0px; padding: 0px;">
                              <input type="file" id="ajax-upload-id-1460599196294" name="myfile" accept="*" style="position: absolute; cursor: pointer; top: 0px; width: 100%; height: 100%; left: 0px; z-index: 100; opacity: 0;">
                          </form>
                        </div>
                        <div>
                        <!--es necesario este div-->
                        </div>
                      </div>
                      <a href="" target="_blank" id="file_paso_7_2">Ver archivo</a>
                    </div>
                  </div>
                </form>
              </div> 

              <div id="step-8">
                <form class="form-horizontal form-label-left">
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Fecha de evaluación 
                      <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input placeholder="Formato: dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" type="text" required="required" class="form-control col-md-7 col-xs-12">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Fecha de finalización 
                      <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input placeholder="Formato: dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" type="text" required="required" class="form-control col-md-7 col-xs-12">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-offset-3 col-md-12 col-sm-12 col-xs-12">
                      <div id="singleupload_paso8_1">
                        <div class="ajax-file-upload" style="position: relative; overflow: hidden; cursor: default;">
                          Upload
                          <form method="POST" action=global_apiserver + "/sg_proceso/uploadFile/" enctype="multipart/form-data" style="margin: 0px; padding: 0px;">
                              <input type="file" id="ajax-upload-id-1460599196294" name="myfile" accept="*" style="position: absolute; cursor: pointer; top: 0px; width: 100%; height: 100%; left: 0px; z-index: 100; opacity: 0;">
                          </form>
                        </div>
                        <div>
                        <!--es necesario este div-->
                        </div>
                      </div>
                      <a href="" target="_blank" id="file_paso_8_1">Ver archivo</a>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-offset-3 col-md-12 col-sm-12 col-xs-12">
                      <div id="singleupload_paso8_2">
                        <div class="ajax-file-upload" style="position: relative; overflow: hidden; cursor: default;">
                          Upload
                          <form method="POST" action=global_apiserver + "/sg_proceso/uploadFile/" enctype="multipart/form-data" style="margin: 0px; padding: 0px;">
                              <input type="file" id="ajax-upload-id-1460599196294" name="myfile" accept="*" style="position: absolute; cursor: pointer; top: 0px; width: 100%; height: 100%; left: 0px; z-index: 100; opacity: 0;">
                          </form>
                        </div>
                        <div>
                        <!--es necesario este div-->
                        </div>
                      </div>
                      <a href="" target="_blank" id="file_paso_8_2">Ver archivo</a>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-offset-3 col-md-12 col-sm-12 col-xs-12">
                      <div id="singleupload_paso8_3">
                        <div class="ajax-file-upload" style="position: relative; overflow: hidden; cursor: default;">
                          Upload
                          <form method="POST" action=global_apiserver + "/sg_proceso/uploadFile/" enctype="multipart/form-data" style="margin: 0px; padding: 0px;">
                              <input type="file" id="ajax-upload-id-1460599196294" name="myfile" accept="*" style="position: absolute; cursor: pointer; top: 0px; width: 100%; height: 100%; left: 0px; z-index: 100; opacity: 0;">
                          </form>
                        </div>
                        <div>
                        <!--es necesario este div-->
                        </div>
                      </div>
                      <a href="" target="_blank" id="file_paso_8_3">Ver archivo</a>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-offset-3 col-md-12 col-sm-12 col-xs-12">
                      <div id="singleupload_paso8_4">
                        <div class="ajax-file-upload" style="position: relative; overflow: hidden; cursor: default;">
                          Upload
                          <form method="POST" action=global_apiserver + "/sg_proceso/uploadFile/" enctype="multipart/form-data" style="margin: 0px; padding: 0px;">
                              <input type="file" id="ajax-upload-id-1460599196294" name="myfile" accept="*" style="position: absolute; cursor: pointer; top: 0px; width: 100%; height: 100%; left: 0px; z-index: 100; opacity: 0;">
                          </form>
                        </div>
                        <div>
                        <!--es necesario este div-->
                        </div>
                      </div>
                      <a href="" target="_blank" id="file_paso_8_4">Ver archivo</a>
                    </div>
                  </div>
                </form>
              </div> 

          </div>
      </div>
    </div>
  </div>
</div>