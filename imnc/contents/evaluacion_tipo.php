<span ng-controller="evaluacion_tipo_controller">
  <div class="right_col" role="main">
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
          <p><h2 ng-cloak>{{encabezado}}</h2></p>
          <p>
            <button type="button" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc" style="float: right;"> 
              <i class="fa fa-plus"> </i> Agregar 
            </button>
          </p>
            <div class="clearfix"></div>
          </div>

          <div class="x_content">

            <table class="table table-striped responsive-utilities jambo_table bulk_action">
              <thead>
                <tr class="headings">
                  <!--<th>
                    <input type="checkbox" id="check-all" class="flat">
                  </th>-->
                  <th class="column-title">Tipo de servicio</th>
                  <th class="column-title" style="width: 290px;">Norma</th>
                  <th class="column-title">Servicio</th>
                  <th class="column-title">Comienza</th>
                  <th class="column-title">Termina</th>
                  <th class="column-title"></th>
                </tr>
              </thead>

              <tbody id="tbodyServT">

              </tbody>

            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</span>