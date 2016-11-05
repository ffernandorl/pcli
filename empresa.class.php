<?php
/**
* @author Francisco Fernando
* @copyright 2016 LATECS
*/
require_once 'retorno.class.php';
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
		return Retorno::MedooErrorTest($e, $data);
		
	}
}
?>