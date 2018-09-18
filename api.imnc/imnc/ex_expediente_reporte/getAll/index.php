<?php 
include  '../../ex_common/query.php';
	
	$nombre_tabla = "EX_EXPEDIENTE_ENTIDAD";
	$correo = "galeanaisauro19@gmail.com";
	
	$respuesta=array();
  $tabla_temporal = array();

  $tipo_tabla = $database->query("SELECT EX_TABLA_ENTIDADES.ID as TIPO, EX_TABLA_ENTIDADES.DESCRIPCION AS ENTIDAD, EX_TABLA_ENTIDADES.TABLA AS TABLA FROM EX_TABLA_ENTIDADES;")->fetchAll(PDO::FETCH_ASSOC);

  for($i=0;$i<count($tipo_tabla);$i++){
      $consulta_tipo=$tipo_tabla[$i]["TIPO"];
      $consulta_nombre=$tipo_tabla[$i]["ENTIDAD"];
      $consulta_tabla=$tipo_tabla[$i]["TABLA"];
    
        $tabla_temporal = $database->query(
         "SELECT DISTINCT EX_REGISTRO_EXPEDIENTE.ID_REGISTRO AS ID, EX_EXPEDIENTE_ENTIDAD.ID_ENTIDAD
          AS ID_ENTIDAD, '$consulta_nombre' AS TIPO, $consulta_tabla.NOMBRE 
          AS NOMBRE_TIPO, EX_TIPO_EXPEDIENTE.NOMBRE AS EXPEDIENTE, EX_REGISTRO_EXPEDIENTE.VALIDO 
          AS VALIDO 
         FROM $consulta_tabla
         JOIN EX_REGISTRO_EXPEDIENTE
         ON $consulta_tabla.ID= EX_REGISTRO_EXPEDIENTE.ID_REGISTRO
         JOIN  EX_EXPEDIENTE_ENTIDAD 
         ON  EX_EXPEDIENTE_ENTIDAD.ID=EX_REGISTRO_EXPEDIENTE.ID_EXPEDIENTE_ENTIDAD
         JOIN EX_TIPO_EXPEDIENTE 
         ON EX_EXPEDIENTE_ENTIDAD.ID_TIPO_EXPEDIENTE=EX_TIPO_EXPEDIENTE.ID
         WHERE EX_EXPEDIENTE_ENTIDAD.ID_ENTIDAD=$consulta_tipo AND 
         EX_EXPEDIENTE_ENTIDAD.TIPO=1;")->fetchAll(PDO::FETCH_ASSOC);

        $respuesta=array_merge($respuesta,$tabla_temporal);

    }

	valida_error_medoo_and_die($nombre_tabla,$correo); 
	print_r(json_encode($respuesta)); 
?> 
