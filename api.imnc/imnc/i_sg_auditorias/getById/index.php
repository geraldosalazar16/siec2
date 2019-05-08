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
		$mailerror->send("I_SG_AUDITORIAS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
}

$id_sce = $_REQUEST["id_sce"];
$id_tipo_auditoria = $_REQUEST["id_ta"];
$ciclo = $_REQUEST["ciclo"];
$id_domicilio = $_REQUEST["id_domicilio"];
$completo = $_REQUEST["completo"];
$i_sg_auditorias = $database->get("I_SG_AUDITORIAS", "*", ["AND"=>["ID_SERVICIO_CLIENTE_ETAPA"=>$id_sce,"TIPO_AUDITORIA"=>$id_tipo_auditoria,"CICLO"=>$ciclo]]);
valida_error_medoo_and_die();
// SG_TIPOS_SERVICIO
//$sg_tipo_servicio = $database->get("SG_TIPOS_SERVICIO", "*", ["ID"=>$sg_auditorias["ID_SG_TIPO_SERVICIO"]]);
$sg_tipo_servicio = $database->select("I_TIPOS_SERVICIOS",
											["[><]I_META_SCE"=>["I_TIPOS_SERVICIOS.ID_META_SCE"=>"ID"]],
										
											["I_TIPOS_SERVICIOS.ID_SERVICIO_CLIENTE_ETAPA","I_TIPOS_SERVICIOS.ID_META_SCE","I_TIPOS_SERVICIOS.VALOR","I_META_SCE.NOMBRE(NOMBRE_META_SCE)","I_META_SCE.TIPO(TIPO_META_SCE)"],
											["AND"=>["ID_SERVICIO_CLIENTE_ETAPA"=>$id_sce,"I_META_SCE.NOMBRE"=>"Número de registro"]]);
valida_error_medoo_and_die(); 
// SG_TIPOS_SERVICIO => SERVICIO_CLIENTE_ETAPA
$servicio_cliente_etapa = $database->get("SERVICIO_CLIENTE_ETAPA", "*", ["ID"=>$id_sce]);
valida_error_medoo_and_die();

// SG_TIPOS_SERVICIO => SERVICIO_CLIENTE_ETAPA => ETAPAS_PROCESO
$etapa_proceso = $database->get("ETAPAS_PROCESO", "*", ["ID_ETAPA"=>$servicio_cliente_etapa["ID_ETAPA_PROCESO"]]);
valida_error_medoo_and_die();
$servicio_cliente_etapa["ETAPA_PROCESO"] = $etapa_proceso;
if($etapa_proceso["ETAPA"] == 'Inicial'){
	$i_sg_auditorias["INICIAL"] = 1;
}
else{
	$i_sg_auditorias["INICIAL"] = 0;
}

if ($completo == "true") { // Realiza consultas adicionales para regresar un reporte completo

		// SG_TIPOS_SERVICIO => SG_SECTORES
		$sg_sectores = $database->select("I_SG_SECTORES", "*", ["ID_SERVICIO_CLIENTE_ETAPA"=>$id_sce]);
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

		$servicio_cliente_etapa["SG_SECTORES_ARRAY"] = $sg_sectores_array;
		$servicio_cliente_etapa["SG_SECTORES"] = $sg_sectores;

		// SG_TIPOS_SERVICIO => NORMAS
		$norma = $database->query("SELECT DISTINCT `ID_NORMA` FROM `SCE_NORMAS` WHERE `ID_SCE`= ".$id_sce)->fetchAll(PDO::FETCH_ASSOC);
		valida_error_medoo_and_die();
		$servicio_cliente_etapa["NORMA"] = $norma;

		// SG_TIPOS_SERVICIO => TIPOS_SERVICIO
		$tipo_servicio = $database->get("TIPOS_SERVICIO", "*", ["ID"=>$servicio_cliente_etapa["ID_TIPO_SERVICIO"]]);
		valida_error_medoo_and_die();
		$servicio_cliente_etapa["TIPO_SERVICIO"] = $tipo_servicio;

		

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
			$servicio_cliente_etapa["INFO_SERVICIO"] = $sg_tipo_servicio;
		//$sg_tipo_servicio["SERVICIO_CLIENTE_ETAPA"] = $servicio_cliente_etapa;

	$i_sg_auditorias["SERVICIO_CLIENTE_ETAPA"] = $servicio_cliente_etapa;	


	// SG_AUDITORIA_GRUPO
	//$i_sg_auditoria_grupo = $database->select("I_SG_AUDITORIA_GRUPOS", "*", ["AND"=>["ID_SERVICIO_CLIENTE_ETAPA" => $id_sce,"TIPO_AUDITORIA"=>$id_tipo_auditoria,"CICLO"=>$ciclo]]);
	$i_sg_auditoria_grupo = $database->query("SELECT * FROM I_SG_AUDITORIA_GRUPOS WHERE ID_SERVICIO_CLIENTE_ETAPA= ".$id_sce." AND TIPO_AUDITORIA=".$id_tipo_auditoria." AND CICLO=".$ciclo."  ORDER BY FIELD(ID_ROL,'3','1','6','4','2','8','5','7','11','9','10','12','13','14','15') ;")->fetchAll();
	//$i_sg_auditoria_grupo = $i_sg_auditoria_grupo[0];
	valida_error_medoo_and_die();
	
		// SG_AUDITORIA_GRUPO => PT_CALIF y PT_ROL
		for ($i=0; $i < count($i_sg_auditoria_grupo) ; $i++) { 
			// SG_AUDITORIA_GRUPO => PT_CALIF
			$pt_calificacion = $database->get("PERSONAL_TECNICO_CALIFICACIONES", "*", ["ID"=>$i_sg_auditoria_grupo[$i]["ID_PERSONAL_TECNICO_CALIF"]]);
			valida_error_medoo_and_die();

				// SG_AUDITORIA_GRUPO => PT_CALIF => PERSONAL_TECNICO_CALIFICACION_SECTOR
				$pt_calif_sectores = $database->select("PERSONAL_TECNICO_CALIF_SECTOR", "ID_SECTOR", ["AND" => ["ID_PERSONAL_TECNICO_CALIFICACION"=>$pt_calificacion["ID"], "ID_SECTOR" => $servicio_cliente_etapa["SG_SECTORES_ARRAY"]]]);
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

			$i_sg_auditoria_grupo[$i]["PERSONAL_TECNICO_CALIFICACION"] = $pt_calificacion;

			// SG_AUDITORIA_GRUPO =>  PT_ROL
			$pt_rol = $database->get("PERSONAL_TECNICO_ROLES", "*", ["ID"=>$i_sg_auditoria_grupo[$i]["ID_ROL"]]);
			valida_error_medoo_and_die();
			$i_sg_auditoria_grupo[$i]["PERSONAL_TECNICO_ROL"] = $pt_rol;

			
		}

	$i_sg_auditorias["SG_AUDITORIA_GRUPO"] = $i_sg_auditoria_grupo;	

	// SG_AUDITORIA_SITIOS
	$i_sg_auditoria_sitios = $database->select("I_SG_AUDITORIA_SITIOS", "*", ["AND"=>["ID_SERVICIO_CLIENTE_ETAPA" => $id_sce,"TIPO_AUDITORIA"=>$id_tipo_auditoria,"CICLO"=>$ciclo]]);
	valida_error_medoo_and_die();
	$i_sg_auditorias["SG_AUDITORIA_SITIOS"] = $i_sg_auditoria_sitios;	

	$i_sg_auditoria_fechas = $database->select("I_SG_AUDITORIA_FECHAS", "*", ["AND"=>["ID_SERVICIO_CLIENTE_ETAPA" => $id_sce,"TIPO_AUDITORIA"=>$id_tipo_auditoria,"CICLO"=>$ciclo], "ORDER"=>"FECHA"]);
	valida_error_medoo_and_die();
	$i_sg_auditorias["SG_AUDITORIA_FECHAS"] = $i_sg_auditoria_fechas;

	//$i_sg_auditoria_certificado = $database->get("SG_CERTIFICADO", "*", ["ID_SG_TIPOS_SERVICIO"=>$sg_tipo_servicio["ID"]]);
	//valida_error_medoo_and_die();
	//$sg_auditorias["SG_AUDITORIA_CERTIFICADO"] = $sg_auditoria_certificado;
	$i_sg_auditoria_tipo =  $database->get("I_SG_AUDITORIAS_TIPOS",["TIPO"],["ID"=>$id_tipo_auditoria]);
	$i_sg_auditorias["TIPO_AUDITORIA"] = $i_sg_auditoria_tipo["TIPO"];

}

print_r(json_encode($i_sg_auditorias));
?> 
