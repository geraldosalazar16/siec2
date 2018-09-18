<?php
function encriptar($cadena){
		$key='EXPEDIENTEDHT2016CMLJJJAI';
		$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $cadena, MCRYPT_MODE_CBC, md5(md5($key))));
		return $encrypted; //Devuelve el string encriptado
	}
	function desencriptar($cadena){
		 $key='EXPEDIENTEDHT2016CMLJJJAI';	 
		 $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($cadena), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
		return $decrypted;  //Devuelve el string desencriptado
	}
	$codigo = $_REQUEST["codigo"];
	$var = encriptar($codigo);
	echo  urlencode($var)."<BR>";
	echo desencriptar(html_entity_decode($var));
?>