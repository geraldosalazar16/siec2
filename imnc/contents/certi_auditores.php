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
			<div id="tntGridServices" ui-grid="gridOptions" ui-grid-pinning ui-grid-auto-fit-columns ></div>
  
        </div>
      </div>
    </div>
  </div>
</div>

</span>