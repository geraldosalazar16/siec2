<?php
	function insertHistorial($database, $ID, $USUARIO){
	    $ultimo_id_historial = $database->max("PROSPECTO_CITA_HISTORIAL","ID");
	    $FECHA = date('Y/m/d H:i:s');
		$id = $database->insert("PROSPECTO_CITA_HISTORIAL", [
			"ID"=>$ultimo_id_historial+1,
	        "ID_CITA"=>$ID,
	        "FECHA"=>$FECHA,
	        "USUARIO"=>$USUARIO
	        ]);
			
			return $ultimo_id_historial+1;
	}
?>