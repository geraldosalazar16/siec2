<?php 
include  '../../common/conn-apiserver.php'; 
include  '../../common/conn-medoo.php'; 
include  '../../common/conn-sendgrid.php'; 

function valida_parametro_and_die($parametro, $mensaje_error){ 
	$parametro = "" . $parametro; 
	if ($parametro == "") { 
		$respuesta["resultado"] = "error"; 
		$respuesta["mensaje"] = $mensaje_error; 
		print_r(json_encode($respuesta)); 
		die(); 
	} 
} 

function valida_error_medoo_and_die(){ 
	global $database, $mailerror; 
	if ($database->error()[2]) { 
		$respuesta["resultado"]="error"; 
		$respuesta["mensaje"]="Error al ejecutar script: " . $database->error()[2]; 
		print_r(json_encode($respuesta)); 
		$mailerror->send("SG_AUDITORIAS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$id = $_REQUEST["id"];
$completo = $_REQUEST["completo"];
$sg_auditorias = $database->get("SG_AUDITORIAS", "*", ["ID"=>$id]);
valida_error_medoo_and_die();
// SG_TIPOS_SERVICIO
$sg_tipo_servicio = $database->get("SG_TIPOS_SERVICIO", "*", ["ID"=>$sg_auditorias["ID_SG_TIPO_SERVICIO"]]);
valida_error_medoo_and_die();
// SG_TIPOS_SERVICIO => SERVICIO_CLIENTE_ETAPA
$servicio_cliente_etapa = $database->get("SERVICIO_CLIENTE_ETAPA", "*", ["ID"=>$sg_tipo_servicio["ID_SERVICIO_CLIENTE_ETAPA"]]);
valida_error_medoo_and_die();

// SG_TIPOS_SERVICIO => SERVICIO_CLIENTE_ETAPA => ETAPAS_PROCESO
$etapa_proceso = $database->get("ETAPAS_PROCESO", "*", ["ID_ETAPA"=>$servicio_cliente_etapa["ID_ETAPA_PROCESO"]]);
valida_error_medoo_and_die();
$servicio_cliente_etapa["ETAPA_PROCESO"] = $etapa_proceso;
if($etapa_proceso["ETAPA"] == 'Inicial'){
	$sg_auditorias["INICIAL"] = 1;
}
else{
	$sg_auditorias["INICIAL"] = 0;
}

if ($completo == "true") { // Realiza consultas adicionales para regresar un reporte completo

		// SG_TIPOS_SERVICIO => SG_SECTORES
		$sg_sectores = $database->select("SG_SECTORES", "*", ["ID_SG_TIPO_SERVICIO"=>$sg_tipo_servicio["ID"]]);
		valida_error_medoo_and_die();
		$sg_sectores_array = array();

			// SG_TIPOS_SERVICIO => SG_SECTORES => SECTORES
			for ($i=0; $i < count($sg_sectores) ; $i++) { 
				$sectores = $database->get("SECTORES", "*", ["ID_SECTOR"=>$sg_sectores[$i]["ID_SECTOR"]]);
				valida_error_medoo_and_die();
				$sg_sectores[$i]["SECTORES"] = $sectores;
				if (!in_array($sectores["ID_SECTOR"], $sg_sectores_array)) {
					array_push($sg_sectores_array, $sectores["ID_SECTOR"]);
				}
			}

		$sg_tipo_servicio["SG_SECTORES_ARRAY"] = $sg_sectores_array;
		$sg_tipo_servicio["SG_SECTORES"] = $sg_sectores;

		// SG_TIPOS_SERVICIO => NORMAS
		$norma = $database->get("NORMAS", "*", ["ID"=>$sg_tipo_servicio["ID_NORMA"]]);
		valida_error_medoo_and_die();
		$sg_tipo_servicio["NORMA"] = $norma;

		// SG_TIPOS_SERVICIO => TIPOS_SERVICIO
		$tipo_servicio = $database->get("TIPOS_SERVICIO", "*", ["ID"=>$sg_tipo_servicio["ID_TIPO_SERVICIO"]]);
		valida_error_medoo_and_die();
		$sg_tipo_servicio["TIPO_SERVICIO"] = $tipo_servicio;

		

			// SG_TIPOS_SERVICIO => SERVICIO_CLIENTE_ETAPA => SERVICIOS
			$servicio = $database->get("SERVICIOS", "*", ["ID"=>$servicio_cliente_etapa["ID_SERVICIO"]]);
			valida_error_medoo_and_die();
			$servicio_cliente_etapa["SERVICIO"] = $servicio;

			// SG_TIPOS_SERVICIO => SERVICIO_CLIENTE_ETAPA => CLIENTES
			$cliente = $database->get("CLIENTES", "*", ["ID"=>$servicio_cliente_etapa["ID_CLIENTE"]]);
			valida_error_medoo_and_die();

				// SG_TIPOS_SERVICIO => SERVICIO_CLIENTE_ETAPA => CLIENTE => DOMICILIOS
				$cliente_domicilios = $database->select("CLIENTES_DOMICILIOS", "*", ["ID_CLIENTE"=>$cliente["ID"]]);
				valida_error_medoo_and_die();
				
					// SG_TIPOS_SERVICIO => SERVICIO_CLIENTE_ETAPA => CLIENTE => DOMICILIOS => CONTACTOS
					for ($i=0; $i < count($cliente_domicilios) ; $i++) { 
						$cliente_domicilio_contactos = $database->select("CLIENTES_CONTACTOS", "*", ["ID_CLIENTE_DOMICILIO"=>$cliente_domicilios[$i]["ID"]]);
						valida_error_medoo_and_die();
						$cliente_domicilios[$i]["CLIENTE_DOMICILIO_CONTACTOS"] = $cliente_domicilio_contactos;
					}
					
				$cliente["CLIENTE_DOMICILIOS"] = $cliente_domicilios;

				// SG_TIPOS_SERVICIO => SERVICIO_CLIENTE_ETAPA => CLIENTE => DOMICILIOS (FISCAL)
				if(isset($_REQUEST["id_domicilio"])){
					$cliente_domicilio_fiscal = $database->get("CLIENTES_DOMICILIOS", "*", ["AND"=>["ID_CLIENTE"=>$cliente["ID"], "ID"=>$_REQUEST["id_domicilio"]]]);
				}else{
					$cliente_domicilio_fiscal = $database->get("CLIENTES_DOMICILIOS", "*", ["AND"=>["ID_CLIENTE"=>$cliente["ID"], "ES_FISCAL"=>"si"]]);
				}
				valida_error_medoo_and_die();
				
					// SG_TIPOS_SERVICIO => SERVICIO_CLIENTE_ETAPA => CLIENTE => DOMICILIOS => CONTACTOS (PRINCIPAL)
					$cliente_domicilio_contacto_principal = $database->get("CLIENTES_CONTACTOS", "*",  ["AND"=>["ID_CLIENTE_DOMICILIO"=>$cliente_domicilio_fiscal["ID"], "ES_PRINCIPAL"=>"si"]]);
					valida_error_medoo_and_die();
					$cliente_domicilio_fiscal["CLIENTE_DOMICILIO_CONTACTO_PRINCIPAL"] = $cliente_domicilio_contacto_principal;
					
					
				$cliente["CLIENTE_DOMICILIO_FISCAL"] = $cliente_domicilio_fiscal;

			$servicio_cliente_etapa["CLIENTE"] = $cliente;

		$sg_tipo_servicio["SERVICIO_CLIENTE_ETAPA"] = $servicio_cliente_etapa;

	$sg_auditorias["SG_TIPO_SERVICIO"] = $sg_tipo_servicio;	


	// SG_AUDITORIA_GRUPO
	$sg_auditoria_grupo = $database->select("SG_AUDITORIA_GRUPOS", "*", ["ID_SG_AUDITORIA"=>$id]);
	valida_error_medoo_and_die();
	
		// SG_AUDITORIA_GRUPO => PT_CALIF y PT_ROL
		for ($i=0; $i < count($sg_auditoria_grupo) ; $i++) { 
			// SG_AUDITORIA_GRUPO => PT_CALIF
			$pt_calificacion = $database->get("PERSONAL_TECNICO_CALIFICACIONES", "*", ["ID"=>$sg_auditoria_grupo[$i]["ID_PERSONAL_TECNICO_CALIF"]]);
			valida_error_medoo_and_die();

				// SG_AUDITORIA_GRUPO => PT_CALIF => PERSONAL_TECNICO_CALIFICACION_SECTOR
				$pt_calif_sectores = $database->select("PERSONAL_TECNICO_CALIF_SECTOR", "ID_SECTOR", ["AND" => ["ID_PERSONAL_TECNICO_CALIFICACION"=>$pt_calificacion["ID"], "ID_SECTOR" => $sg_tipo_servicio["SG_SECTORES_ARRAY"]]]);
				valida_error_medoo_and_die();

				if (count($pt_calif_sectores) != 0) {
					$pt_calif_sectores_aif = $database->select("SECTORES", "ID", ["ID_SECTOR"=>$pt_calif_sectores]);
					valida_error_medoo_and_die();
					$pt_calificacion["PERSONAL_TECNICO_CALIF_SECTORES"] = $pt_calif_sectores_aif;
				}else{
					$pt_calificacion["PERSONAL_TECNICO_CALIF_SECTORES"] = $pt_calif_sectores;
				}
				

				// SG_AUDITORIA_GRUPO => PT_CALIF => PERSONAL_TECNICO
				$pt = $database->get("PERSONAL_TECNICO", ["NOMBRE", "APELLIDO_PATERNO", "APELLIDO_MATERNO"], ["ID"=>$pt_calificacion["ID_PERSONAL_TECNICO"]]);
				valida_error_medoo_and_die();
				$pt_calificacion["PERSONAL_TECNICO"] = $pt;

			$sg_auditoria_grupo[$i]["PERSONAL_TECNICO_CALIFICACION"] = $pt_calificacion;

			// SG_AUDITORIA_GRUPO =>  PT_ROL
			$pt_rol = $database->get("PERSONAL_TECNICO_ROLES", "*", ["ID"=>$sg_auditoria_grupo[$i]["ID_ROL"]]);
			valida_error_medoo_and_die();
			$sg_auditoria_grupo[$i]["PERSONAL_TECNICO_ROL"] = $pt_rol;

			
		}

	$sg_auditorias["SG_AUDITORIA_GRUPO"] = $sg_auditoria_grupo;	

	// SG_AUDITORIA_SITIOS
	$sg_auditoria_sitios = $database->select("SG_AUDITORIA_SITIOS", "*", ["ID_SG_AUDITORIA"=>$id]);
	valida_error_medoo_and_die();
	$sg_auditorias["SG_AUDITORIA_SITIOS"] = $sg_auditoria_sitios;	

	$sg_auditoria_fechas = $database->select("SG_AUDITORIA_FECHAS", "*", ["ID_SG_AUDITORIA"=>$id, "ORDER"=>"FECHA"]);
	valida_error_medoo_and_die();
	$sg_auditorias["SG_AUDITORIA_FECHAS"] = $sg_auditoria_fechas;

	$sg_auditoria_certificado = $database->get("SG_CERTIFICADO", "*", ["ID_SG_TIPOS_SERVICIO"=>$sg_tipo_servicio["ID"]]);
	valida_error_medoo_and_die();
	$sg_auditorias["SG_AUDITORIA_CERTIFICADO"] = $sg_auditoria_certificado;

}

print_r(json_encode($sg_auditorias)); 
?> 
