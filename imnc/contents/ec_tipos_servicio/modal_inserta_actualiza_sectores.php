<!--Modal insertar/actualizar Sectores-->
<div class="modal fade" id="modalInsertarActualizarTServSector" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTituloSector">{{modal_titulo_sector}}</h4>
      </div>
      <div class="modal-body">
		<form name="exampleFormSector" >
			<div class='form-group'>
				<label for="claveNombreSector">Nombre del sector<span class="required">*</span></label>
                <select ng-model="formDataSector.Id_Sector" ng-options="claveNombreSector.ID_SECTOR as claveNombreSector.NOMBRE for claveNombreSector in SectoresTipoServicio"  class="form-control" id="claveNombreSector" name="claveNombreSector" ng-change='cambioclaveNombreSector()' required ng-class="{ error: exampleFormSector.claveNombreSector.$error.required && !exampleForm.$pristine}"></select>
            </div>
			<div class='form-group' ng-show="ocultar">
				<label for="PrincipalSec">Nombre del sector<span class="required">*</span></label>
                <select ng-model="formDataSector.Principal" ng-options="PrincipalSec.ID as PrincipalSec.NOMBRE for PrincipalSec in PrincipalSectores"  class="form-control" id="PrincipalSec" name="PrincipalSec" ng-change='cambioPrincipalSec()' required ng-class="{ error: exampleFormSector.PrincipalSec.$error.required && !exampleForm.$pristine}" ></select>
            </div>
 
			<input type="submit" class="btn btn-success pull-right mt-2" ng-click="submitFormSector(formDataSector)" ng-disabled="!exampleFormSector.$valid" value="Guardar"/>
          </form>
      </div>
      <div class="modal-footer">
        
      </div>
    </div>
  </div>
</div>