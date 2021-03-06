<?php
include  '../../common/conn-apiserver.php';
include  '../../common/conn-medoo.php';
include  '../../common/conn-sendgrid.php';
include '../../ex_common/funciones.php';
include '../../ex_common/archivos.php';
function valida_parametro_and_die($parametro, $mensaje_error){
	$parametro = "".$parametro;
	if ($parametro == "") {
		$respuesta['resultado'] = 'error';
		$respuesta['mensaje'] = $mensaje_error;
		print_r(json_encode($respuesta));
		die();
	}
}

function valida_error_medoo_and_die(){
	global $database, $mailerror;
	if ($database->error()[2]) { //Aqui está el error
		$respuesta['resultado']="error";
		$respuesta['mensaje']="Error al ejecutar script: " . $database->error()[2];
		print_r(json_encode($respuesta));
		$mailerror->send("certificando", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx");
		die();
	}
}
	
	$nombre_tabla = "PROSPECTO";

	
	$respuesta=array(); 
	$json = file_get_contents('php://input'); //Obtiene lo que se envía vía POST
	$objeto = json_decode($json); // Lo transforma de JSON a un objeto de PHP


	$NOMBRE = $objeto->NOMBRE; 
	valida_parametro_and_die($NOMBRE, "Es necesario capturar un nombre");
	$RFC= $objeto->RFC;
    if(!$RFC)
    {
        $RFC = " ";
    }
    $GIRO=$objeto->GIRO;
	if(!$GIRO)
    {
        $GIRO = " ";
    }
    /*
	$TIPO_SERVICIO = $objeto->TIPO_SERVICIO;
	valida_parametro_and_die1($TIPO_SERVICIO, "Es necesario capturar el tipo de servicio");
	*/
    $ID_CLIENTE=$objeto->ID_CLIENTE;
    valida_parametro_and_die($ID_CLIENTE, "Es necesario capturar el cliente");
	$FECHA_CREACION = date('Y/m/d H:i:s');
	$ID_USUARIO_CREACION = $objeto->ID_USUARIO_CREACION; 
	$FECHA_MODIFICACION = date('Y/m/d H:i:s'); 
	$ID_USUARIO_MODIFICACION = $objeto->ID_USUARIO_MODIFICACION; 
	$ACTIVO = $objeto->ACTIVO;
    valida_parametro_and_die($ACTIVO, "Es necesario seleccionar si está activo");
	$ORIGEN = $objeto->ORIGEN;
    valida_parametro_and_die($ORIGEN, "Es necesario seleccionar un origen");
	$COMPETENCIA =$objeto->COMPETENCIA;
	if(!$COMPETENCIA)
    {
        $COMPETENCIA = 0;
    }
	$ESTATUS_SEGUIMIENTO =$objeto->ESTATUS_SEGUIMIENTO;
    valida_parametro_and_die($ESTATUS_SEGUIMIENTO, "Es necesario seleccionar un estatus");
	$TIPO_CONTRATO =$objeto->TIPO_CONTRATO;
    valida_parametro_and_die($TIPO_CONTRATO, "Es necesario seleccionar un tipo de contrato");
	$ID_USUARIO_PRINCIPAL = $objeto->ID_USUARIO;
	$ID_USUARIO_SECUNDARIO = $objeto->ID_USUARIO_SECUNDARIO;
	//valida_parametro_and_die($ID_USUARIO_SECUNDARIO, "Es necesario seleccionar u usuario secundario");
	if(!$ID_USUARIO_PRINCIPAL)
    {
        $ID_USUARIO_PRINCIPAL = 0;
    }
	if(!$ID_USUARIO_SECUNDARIO)
    {
        $ID_USUARIO_SECUNDARIO = 0;
    }
	$TIPO_PERSONA = $objeto->TIPO_PERSONA;
    $DESDE_CLIENTE = $objeto->DESDE_CLIENTE;

	/*
	$DEPARTAMENTO = $objeto->DEPARTAMENTO;
	valida_parametro_and_die1($DEPARTAMENTO, "Es necesario seleccionar un departamento");
	*/

	$ID = $database->insert("PROSPECTO",
		[
			"ID_CLIENTE"=>$ID_CLIENTE,
			"RFC"=>$RFC,
			"NOMBRE"=>$NOMBRE,
			"GIRO"=>$GIRO,
			"FECHA_CREACION"=>$FECHA_CREACION,
			"USUARIO_CREACION"=>$ID_USUARIO_CREACION,
			"FECHA_MODIFICACION"=>$FECHA_MODIFICACION,
			"USUARIO_MODIFICACION"=>$ID_USUARIO_MODIFICACION,
			"ACTIVO"=>$ACTIVO,
			"ORIGEN"=>$ORIGEN,
			"ID_COMPETENCIA"=>$COMPETENCIA,
			"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS_SEGUIMIENTO,
			"ID_TIPO_CONTRATO"=>$TIPO_CONTRATO,
			"ID_USUARIO_PRINCIPAL"=>$ID_USUARIO_PRINCIPAL,
			"ID_USUARIO_SECUNDARIO"=>$ID_USUARIO_SECUNDARIO,
			"ID_PORCENTAJE"=>3,
			"TIPO_PERSONA"=>$TIPO_PERSONA

		]);
     valida_error_medoo_and_die();


	
	

	$respuesta["resultado"]="ok";
	$respuesta["id"]=$ID; 
	/*		CODIGO PARA AGREGAR FECHAS EN QUE SE CAMBIAN LOS ESTADOS		*/
	
	
		if($ESTATUS_SEGUIMIENTO==1){
			$cons2	=	$database->insert("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS_SEGUIMIENTO, "FECHA_SOLICITUD_COTIZACION"=>date('Y-m-d')]);
		
		}
		if($ESTATUS_SEGUIMIENTO==2){
			$cons2	=	$database->insert("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS_SEGUIMIENTO, "FECHA_ENVIO_COTIZACION"=>date('Y-m-d')]);
		
		}
		if($ESTATUS_SEGUIMIENTO==3){
			$cons2	=	$database->insert("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS_SEGUIMIENTO]);
		
		}
		if($ESTATUS_SEGUIMIENTO==4){
			$cons2	=	$database->insert("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS_SEGUIMIENTO, "FECHA_FIRMADO"=>date('Y-m-d')]);	
			
		}
		if($ESTATUS_SEGUIMIENTO==5){
			$cons2	=	$database->insert("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS_SEGUIMIENTO]);
		
		}
		if($ESTATUS_SEGUIMIENTO==6){
			$cons2	=	$database->insert("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS_SEGUIMIENTO]);
		
		}
		if($ESTATUS_SEGUIMIENTO==7){
			$cons2	=	$database->insert("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS_SEGUIMIENTO]);
		
		}
		if($ESTATUS_SEGUIMIENTO==8){
			$cons2	=	$database->insert("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS_SEGUIMIENTO, "FECHA_ENVIO_CUESTIONARIO"=>date('Y-m-d')]);	
			
		}
		if($ESTATUS_SEGUIMIENTO==9){
			$cons2	=	$database->insert("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS_SEGUIMIENTO, "FECHA_RECEPCION_CUESTIONARIO"=>date('Y-m-d')]);	
		
		}
		
	if($ID &&  $DESDE_CLIENTE)
	{
		$cliente_domicilios = $database->select("CLIENTES_DOMICILIOS",
			[
				"[>]CLIENTES_CONTACTOS" =>["ID"=>"ID_CLIENTE_DOMICILIO"]
			],
			[
				"CLIENTES_DOMICILIOS.ID",
				"CLIENTES_DOMICILIOS.NOMBRE_DOMICILIO",
				"CLIENTES_DOMICILIOS.PAIS",
				"CLIENTES_DOMICILIOS.ENTIDAD_FEDERATIVA",
				"CLIENTES_DOMICILIOS.DELEGACION_MUNICIPIO",
				"CLIENTES_DOMICILIOS.COLONIA_BARRIO",
				"CLIENTES_DOMICILIOS.CP",
				"CLIENTES_DOMICILIOS.CALLE",
				"CLIENTES_DOMICILIOS.NUMERO_INTERIOR",
				"CLIENTES_DOMICILIOS.NUMERO_EXTERIOR",
				"CLIENTES_DOMICILIOS.ES_FISCAL",
				"CLIENTES_DOMICILIOS.ID_CLIENTE",

				"CLIENTES_CONTACTOS.NOMBRE_CONTACTO",
				"CLIENTES_CONTACTOS.EMAIL",
				"CLIENTES_CONTACTOS.EMAIL2",
				"CLIENTES_CONTACTOS.TELEFONO_FIJO",
				"CLIENTES_CONTACTOS.TELEFONO_MOVIL",
				"CLIENTES_CONTACTOS.CARGO",
				"CLIENTES_CONTACTOS.DATOS_ADICIONALES",
			],["ID_CLIENTE"=>$ID_CLIENTE]);
		valida_error_medoo_and_die();

        $id_d = null;
		foreach ($cliente_domicilios as $item)
		{
			if($id_d!=$item["ID"])
			{
				$c = $database->insert("PROSPECTO_DOMICILIO",
					[
						"ID_PROSPECTO" => $ID,
						"NOMBRE" => $item["NOMBRE_DOMICILIO"],
						"PAIS" => $item["PAIS"],
						"ESTADO" => $item["ENTIDAD_FEDERATIVA"],
						"MUNICIPIO" => $item["DELEGACION_MUNICIPIO"],
						"COLONIA" => $item["COLONIA_BARRIO"],
						"CODIGO_POSTAL" => $item["CP"],
						"CALLE" => $item["CALLE"],
						"NUMERO_INTERIOR" => $item["NUMERO_INTERIOR"],
						"NUMERO_EXTERIOR" => $item["NUMERO_EXTERIOR"],
						"FISCAL" => $item["ES_FISCAL"],
						"ID_CLIENTE_DOMICILIO" => $item["ID_CLIENTE"],
					]);
				valida_error_medoo_and_die();
				$id_d=$item["ID"];
			}

			$c = $database->insert("PROSPECTO_CONTACTO",
				[
					"ID_PROSPECTO" => $ID,
					"ID_PROSPECTO_DOMICILIO" => $item["ID"],
					"NOMBRE" => $item["NOMBRE_CONTACTO"],
					"CORREO" => $item["EMAIL"],
					"CORREO2" => $item["EMAIL2"],
					"TELEFONO" => $item["TELEFONO_FIJO"],
					"CELULAR" => $item["TELEFONO_MOVIL"],
					"PUESTO" => $item["CARGO"],
					"DATOS_ADICIONALES" => $item["DATOS_ADICIONALES"],
					"ACTIVO" => 1,
				]);
			valida_error_medoo_and_die();

		}


	}
	/*////////////////////////////////////////////////////////////////////////////////////////////*/

	creacion_expediente_registro($ID, 3, $rutaExpediente, $database);
	crea_instancia_expedientes_registro($ID, 3, $database);
	
	print_r(json_encode($respuesta)); 
?> 
