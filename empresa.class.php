<?php
/**
* @author Francisco Fernando
* @copyright 2016 LATECS
*/
class Empresa{
	/**
	 * return data of all "Empresa"
	 * @return array array of response
	 * @param resource $database
	 */
	function DadosEmpresa($database){
		$data = $database->select("Empresa","*");
		//evaluation of possible error and return of function
		$e = $database->error();
		if($e[1] == null){
			$retorno["status"] = "200";
			$retorno["resposta"] = $data; 
			return $retorno;
		} else {
			$retorno["status"] = "erro";
			$retorno["resposta"] = $e; 
			return $retorno;
		}
	}
}
?>