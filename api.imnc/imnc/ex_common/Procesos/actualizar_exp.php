
<?php
include  '../update.php';

$fechas = date('Y-m-d');

function calificacion_expediente($registro_exp, $database){

	
	
	$tipo_requisitos = $database->query("select 	arc_exp.ID as ID_ARCHIVO_EXPEDIENTE,
	  				exp_doc.ID as ID_EXPEDIENTE_DOCUMENTO,
	  				exp_doc.OBLIGATORIO as OBLIGATORIO,
	  				tipo_doc.NOMBRE as NOMBRE_DOCUMENTO,
	  				arc_doc.ID as ID_ULT_ARCHIVO,
	  				arc_doc.NOMBRE_ARCHIVO as ULT_NOMBRE_ARCHIVO,
	  				arc_doc.FECHA_VENCIMIENTO_INICIAL as ULT_FECHA_VENCIMIENTO_INICIAL,
	  				arc_doc.FECHA_VENCIMIENTO_FINAL as ULT_FECHA_VENCIMIENTO_FINAL,
	  				arc_doc.VALIDACION as ULT_VALIDACION
	  		from EX_ARCHIVO_EXPEDIENTE as arc_exp
	  		left join EX_ARCHIVO_DOCUMENTO as arc_doc
	  		on (arc_doc.ID_ARCHIVO_EXPEDIENTE = arc_exp.ID )
	  		left join EX_EXPEDIENTE_DOCUMENTO as exp_doc
	  		on (exp_doc.ID = arc_exp.ID_EXPEDIENTE_DOCUMENTO)
	  		left join EX_TIPO_DOCUMENTO as tipo_doc
	  		on (tipo_doc.ID = exp_doc.ID_DOCUMENTO)
	  		where (arc_doc.ID IS NULL or arc_doc.ID in (
			select max(ID) from EX_ARCHIVO_DOCUMENTO
			group by ID_ARCHIVO_EXPEDIENTE)) and 
			arc_exp.ID_REGISTRO_EXPEDIENTE =".$database->quote($registro_exp))->fetchAll(PDO::FETCH_ASSOC);
	
	$valido=0;	
		
	for($k = 0 ; $k < count($tipo_requisitos); $k++){
		$validacion=$tipo_requisitos[$k]["ULT_VALIDACION"];	
		$obligatorio=$tipo_requisitos[$k]["OBLIGATORIO"];
		$ultimo_archivo=$tipo_requisitos[$k]["ID_ULT_ARCHIVO"];
		$fecha=$tipo_requisitos[$k]["ULT_FECHA_VENCIMIENTO_FINAL"];
		
		echo "ID ".$registro_exp.'<br/>';
		echo "Validación ".$validacion.'<br/>';
		echo "Ultimo Archivo ".$ultimo_archivo.'<br/>';
		echo "Obligatorio ".$obligatorio.'<br/>';
		
		if ($obligatorio != 0 && $ultimo_archivo != null && $validacion == 1 &&strtotime($fecha)> $fechas ){
				$valido=1;
			}else if($obligatorio == 0){
				$valido=1;
			}
			else{
				$valido=0;
				break;
		}
		
		
		
	}
	$id = $database->update("EX_REGISTRO_EXPEDIENTE", [
		"VALIDO" => $valido,
		"FECHA_MODIFICACION" => date('Y/m/d H:i:s')
		], ["ID"=>$registro_exp]);
	
}
 
$tipo_expedientes =$database->query("SELECT ex_registro_expediente.ID FROM ex_registro_expediente")->fetchAll(PDO::FETCH_ASSOC);
	for($i=0; $i < count ($tipo_expedientes);$i++){
		$id_exp=$tipo_expedientes[$i]["ID"];
		echo $id_exp.'<br/>';
			
			calificacion_expediente($id_exp, $database);
}
?>