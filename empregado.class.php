<?php
/**
* @author Francisco Fernando
* @copyright 2016 LATECS
*/
class Empregado{
	/**
	 * JSON Validation
	 * @return array array of errors
	 * @param object $insertEmpregado
	 */
	public function ValidaJson($insertEmpregado){
		$v_err["RegistroEmpregado"] = array_key_exists($insertEmpregado, "RegistroEmpregado") ? null : "err RegistroEmpregado";
		$v_err["CaracFisicas"] = array_key_exists($insertEmpregado, "CaracFisicas") ? null : "err CaracFisicas";
		$v_err["Contrato"] = array_key_exists($insertEmpregado, "Contrato") ? null : "err Contrato";
		$v_err["SituacaoFGTS"] = array_key_exists($insertEmpregado, "SituacaoFGTS") ? null : "err SituacaoFGTS";
		$v_err["Estrangeiros"] = array_key_exists($insertEmpregado, "Estrangeiros") ? null : "err Estrangeiros";
		$v_err["PIS"] = array_key_exists($insertEmpregado, "PIS") ? null : "err PIS";
		$v_err["Salario"] = array_key_exists($insertEmpregado, "Salario") ? null : "err Salario";
		$v_err["Cargo"] = array_key_exists($insertEmpregado, "Cargo") ? null : "err Cargo";
		$v_err["ContribSindical"] = array_key_exists($insertEmpregado, "ContribSindical") ? null : "err ContribSindical";
		$v_err["ADP"] = method_exists($insertEmpregado, "ADP") ? null : "err ADP";
		$v_err["Ferias"] = method_exists($insertEmpregado, "Ferias") ? null : "err Ferias";
		return $v_err;
	}
	/**
	 * insertion of all twelve tables of employee
	 * @return array array of response 
	 * @param resource object $database $insertEmpregado 
	 */
	public function InserirEmpregado($database, $insertEmpregado){
		
		//JSON Validation
		$v_err = $this->ValidaJson($insertEmpregado);
		foreach ($v_err as $k => $v)
			if($v){
				$retorno["status"] = "erro";
				$retorno["resposta"] = $v_err; 
				return $retorno;
			}
		//Insertion in Database
		$database->pdo->beginTransaction(); //begining a Transaction
			//all the inserts
			$database->insert("RegistroEmpregado",$insertEmpregado["RegistroEmpregado"]);
			$database->insert("CaracFisicas", $insertEmpregado["CaracFisicas"]);
			$database->insert("Contrato", $insertEmpregado["Contrato"]);
			$database->insert("SituacaoFGTS", $insertEmpregado["SituacaoFGTS"]);
			$database->insert("Estrangeiros", $insertEmpregado["Estrangeiros"]);
			$database->insert("PIS", $insertEmpregado["PIS"]);
			$database->insert("Salario", $insertEmpregado["Salario"]);
			$database->insert("Cargo", $insertEmpregado["Cargo"]);
			$database->insert("ContribSindical", $insertEmpregado["ContribSindical"]);
			$database->insert("ADP", $insertEmpregado["ADP"]);
			$database->insert("Ferias", $insertEmpregado["Ferias"]);
		//Increase "numEmpregados" in "Livro"
		$livro = (array) $insertEmpregado["RegistroEmpregado"]["numLivro"];
		$database->update(
			"Livro",
			["numEmpregados[+]" => 1],
			["numLivro" => $livro]);
		//evaluation of possible error and return of function	
		$e = $database->error();
		if($e[1] == null){
			$database->pdo->commit();
			$retorno["status"] = "ok";
			$retorno["resposta"] = true; 
			return $retorno;
		} else {
			$database->pdo->rollBack();
			$retorno["status"] = "erro";
			$retorno["resposta"] = $e; 
			return $retorno;
		}
	}
	/**
	 * query of an employee by book
	 * @return array array of response
	 * @param resource string $database $livro 
	 */ 
	public function BuscaEmpregadoPorLivro($database, $livro){
		$data = $database->select(
			"RegistroEmpregado", 
			array("[><]Contrato" => "idRegistro"),
			array("RegistroEmpregado.numFolha", "Contrato.nomeEmpregado"),
			array("RegistroEmpregado.numLivro" => $livro)
			);
		//evaluation of possible error and return of function
		$e = $database->error();
		if($e[1] == null){
			$retorno["status"] = "ok";
			$retorno["resposta"] = $data; 
			return $retorno;
		} else {
			$retorno["status"] = "erro";
			$retorno["resposta"] = $e; 
			return $retorno;
		}
	}
	/**
	 * query by name
	 * @return array array of response
	 * @param resource string $database $nome 
	 */
	public function PesquisaNomeRapida($database, $nome){
		$data = $database->select(
			"Contrato", 
			["nomeEmpregado", "Cpf", "idRegistro"],
			["nomeEmpregado[~]" => $nome."_"]
			);
		$data = json_encode($data);
		return $data;
	}
	/**
	 * query by CPF
	 * @return array array of response
	 * @param resource string $database $cpf 
	 */
	public function PesquisaCPF($database, $cpf){
		$data = $database->select(
			"Contrato",
			["nomeEmpregado", "idRegistro"],
			["Cpf" => $cpf]
			);
		//evaluation of possible error and return of function
		$e = $database->error();
		if($e[1] == null){
			$retorno["status"] = "ok";
			$retorno["resposta"] = $data; 
			return $retorno;
		} else {
			$retorno["status"] = "erro";
			$retorno["resposta"] = $e; 
			return $retorno;
		}
	}
	/**
	 * insertion in Ferias
	 * @return array array of response
	 * @param resource object $database $insertFerias 
	 */
	public function InserirFerias($database, $insertFerias){
		$database->pdo->beginTransaction(); //begining a Transaction
			$database->insert("Ferias", $insertFerias);	
		//evaluation of possible error and return of function
		$e = $database->error();
		if($e[1] == null){
			$database->pdo->commit();
			$retorno["status"] = "ok";
			$retorno["resposta"] = true; 
			return $retorno;
		} else {
			$database->pdo->rollBack();
			$retorno["status"] = "erro";
			$retorno["resposta"] = $e; 
			return $retorno;
		}
	}
	/**
	 * /salary altering
	 * @return array array of response
	 * @param resource object $database $insertSalario
	 */
	public function NovoSalario($database, $insertSalario){
		$database->pdo->beginTransaction(); //begining a Transaction
			$database->insert("Salario", $insertSalario);	
		//evaluation of possible error and return of function
		$e = $database->error();
		if($e[1] == null){
			$database->pdo->commit();
			$retorno["status"] = "ok";
			$retorno["resposta"] = true; 
			return $retorno;
		} else {
			$database->pdo->rollBack();
			$retorno["status"] = "erro";
			$retorno["resposta"] = $e; 
			return $retorno;
		}
	}
	/**
	 * altering office
	 * @return array array of response
	 * @param resource object $database $insertCargo
	 */
	public function NovoCargo($database, $insertCargo){
		$database->pdo->beginTransaction(); //begining a Transaction
			$database->insert("Cargo", $insertCargo);
		//evaluation of possible error and return of function
		$e = $database->error();
		if($e[1] == null){
			$database->pdo->commit();
			$retorno["status"] = "ok";
			$retorno["resposta"] = true; 
			return $retorno;
		} else {
			$database->pdo->rollBack();
			$retorno["status"] = "erro";
			$retorno["resposta"] = $e; 
			return $retorno;
		}	
	}
	/**
	 * add contribution fiscal 
	 * @return array array of response
	 * @param resource object $database $insertContribSindical 
	 */
	public function InserirContribSindical($database, $insertContribSindical){
		$database->pdo->beginTransaction(); //Inicio de uma Transaction		
			$database->insert("ContribSindical", $insertContribSindical);
		//evaluation of possible error and return of function
		$e = $database->error();
		if($e[1] == null){
			$database->pdo->commit();
			$retorno["status"] = "ok";
			$retorno["resposta"] = true; 
			return $retorno;
		} else {
			$database->pdo->rollBack();
			$retorno["status"] = "erro";
			$retorno["resposta"] = $e; 
			return $retorno;
		}	
	}
	/**
	 * add accident or disease professional
	 * @return array array of response
	 * @param resource object $database $insertADP 
	 */
	public function InserirADP($database, $insertADP){
		$database->pdo->beginTransaction(); //Inicio de uma Transaction		
			$database->insert("ADP", $insertADP);
		//evaluation of possible error and return of function
		$e = $database->error();
		if($e[1] == null){
			$database->pdo->commit();
			$retorno["status"] = "ok";
			$retorno["resposta"] = true; 
			return $retorno;
		} else {
			$database->pdo->rollBack();
			$retorno["status"] = "erro";
			$retorno["resposta"] = $e; 
			return $retorno;
		}
	}
	/**
	 * return all data of employee
	 * @return array array of response
	 * @param resource string $database $idRegistro 
	 */
	public function RetornaEmpregado($database, $idRegistro){
		$data = [];
			$data["RegistroEmpregado"] = $database->select("RegistroEmpregado", "*", ["idRegistro" => $idRegistro]);
			$data["CaracFisicas"] = $database->select("CaracFisicas", "*", ["idRegistro" => $idRegistro]);
			$data["Contrato"] = $database->select("Contrato", "*", ["idRegistro" => $idRegistro]);
			$data["SituacaoFGTS"] = $database->select("SituacaoFGTS", "*", ["idRegistro" => $idRegistro]);
			$data["Estrangeiros"] = $database->select("Estrangeiros", "*", ["idRegistro" => $idRegistro]);
			$data["PIS"] = $database->select("PIS", "*", ["idRegistro" => $idRegistro]);
			$data["Salario"] = $database->select("Salario", "*", ["idRegistro" => $idRegistro]);
			$data["Cargo"] = $database->select("Cargo", "*", ["idRegistro" => $idRegistro]);
			$data["ContribSindical"] = $database->select("ContribSindical", "*", ["idRegistro" => $idRegistro]);
			$data["ADP"] = $database->select("ADP", "*", ["idRegistro" => $idRegistro]);
			$data["Ferias"] = $database->select("Ferias", "*", ["idRegistro" => $idRegistro]);
		//evaluation of possible error and return of function
		$e = $database->error();
		if($e[1] == null){
			$database->pdo->commit();
			$retorno["status"] = "ok";
			$retorno["resposta"] = $data; 
			return $retorno;
		} else {
			$database->pdo->rollBack();
			$retorno["status"] = "erro";
			$retorno["resposta"] = $e; 
			return $retorno;
		}
	}
}
?>