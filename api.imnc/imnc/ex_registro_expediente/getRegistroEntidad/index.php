<?php 
include  '../../ex_common/query.php';
$entidad = $_REQUEST["id_entidad"];

$id_registro = $_REQUEST["id"];
$tipo_tabla = $database->query("SELECT EX_TABLA_ENTIDADES.ID, EX_TABLA_ENTIDADES.DESCRIPCION AS Tipo_Entidad, EX_TABLA_ENTIDADES.TABLA AS Tabla_Consultar FROM EX_TABLA_ENTIDADES where EX_TABLA_ENTIDADES.ID=".$database->quote($entidad))->fetchAll(PDO::FETCH_ASSOC);
$consulta_tabla=$tipo_tabla[0]["Tabla_Consultar"];
if($entidad != 4 && $entidad != 5){
	$tipo_clientes = $database->query("SELECT $consulta_tabla.NOMBRE from $consulta_tabla WHERE ID = ".$database->quote($id_registro))->fetchAll(PDO::FETCH_ASSOC);
	echo '{"NOMBRE":"'.$tipo_clientes[0]["NOMBRE"].'" , "ENTIDAD":"'.$tipo_tabla[0]["Tipo_Entidad"].'"}';
}
else if($entidad == 4){
	$tipo_clientes = $database->query("SELECT * from $consulta_tabla WHERE ID = ".$database->quote($id_registro))->fetchAll(PDO::FETCH_ASSOC);
	$CONSECUTIVO = str_pad("".$tipo_clientes[0]["FOLIO_CONSECUTIVO"], 5, "0", STR_PAD_LEFT);
	$FOLIO = $tipo_clientes[0]["FOLIO_INICIALES"].$tipo_clientes[0]["FOLIO_SERVICIO"].$CONSECUTIVO.$tipo_clientes[0]["FOLIO_MES"].$tipo_clientes[0]["FOLIO_YEAR"];
	if( !is_null($tipo_clientes[0]["FOLIO_UPDATE"]) && $tipo_clientes[0]["FOLIO_UPDATE"] != ""){
		$FOLIO .= "-".$tipo_clientes[0]["FOLIO_UPDATE"];
	}
	echo '{"NOMBRE":" '.$FOLIO.'" , "ENTIDAD":"'.$tipo_tabla[0]["Tipo_Entidad"].'"}';
}
else{	
	$tipo_clientes = $database->query("SELECT $consulta_tabla.REFERENCIA from $consulta_tabla WHERE ID = ".$database->quote($id_registro))->fetchAll(PDO::FETCH_ASSOC);
	echo '{"NOMBRE":" '.$tipo_clientes[0]["REFERENCIA"].'" , "ENTIDAD":"'.$tipo_tabla[0]["Tipo_Entidad"].'"}';
}
?>