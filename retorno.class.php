<?php
/**
* @author Francisco Fernando
* @copyright 2016 LATECS
*/
class Retorno{
	function MedooErrorTest($e, $response){
		if($e[1] == null){
			$retorno["status"] = "200";
			$retorno["data"] = $response; 
			return $retorno;
		} else {
			$retorno["status"] = "508";
			$retorno["data"] = "Error at try to set/get data"; 
			return $retorno;
		}
	}
	function ValidationJson($v_err){
		foreach ($v_err as $k => $v)
			if($v){
				$retorno["status"] = "509";
				$retorno["data"] =  "JSON values not validated"; 
				return $retorno;
			}
		return false;
	}
}
?>