
function modalStatico(){
	$(".modal.fade").attr({"data-backdrop":"static", "data-keyboard":"false"});
	$("button.btn-default:contains('Cerrar'), button.close").removeAttr( "data-dismiss" );
	 $("button.btn-default:contains('Cerrar') ,#btnCerrar, .close").click(function(){
      $.confirm({
        title: 'Confirmación',
        content: 'Esta seguro de salir sin guardar los datos?',
        buttons: {
            Salir: function () {
                $(".modal.fade").modal("hide");
            },
            Cancelar: function () {
              console.log("cancel");
            }
        }
    });
    }); 
};