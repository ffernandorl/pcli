<?php
class Empregado{
	//Validação do  JSON
	public function ValidaJson($insertEmpregado){
		$v_err["RegistroEmpregado"] = method_exists($insertEmpregado, "RegistroEmpregado") ? null : "err RegistroEmpregado";
		$v_err["CaracFisicas"] = method_exists($insertEmpregado, "CaracFisicas") ? null : "err CaracFisicas";
		$v_err["Contrato"] = method_exists($insertEmpregado, "Contrato") ? null : "err Contrato";
		$v_err["SituacaoFGTS"] = method_exists($insertEmpregado, "SituacaoFGTS") ? null : "err SituacaoFGTS";
		$v_err["Estrangeiros"] = method_exists($insertEmpregado, "Estrangeiros") ? null : "err Estrangeiros";
		$v_err["PISBeneficiarios"] = method_exists($insertEmpregado, "PISBeneficiarios") ? null : "err PISBeneficiarios";
		$v_err["Beneficiarios"] = method_exists($insertEmpregado, "Beneficiarios") ? null : "err Beneficiarios";
		$v_err["Salario"] = method_exists($insertEmpregado, "Salario") ? null : "err Salario";
		$v_err["Cargo"] = method_exists($insertEmpregado, "Cargo") ? null : "err Cargo";
		$v_err["ContribSindical"] = method_exists($insertEmpregado, "ContribSindical") ? null : "err ContribSindical";
		$v_err["ADP"] = method_exists($insertEmpregado, "ADP") ? null : "err ADP";
		$v_err["Ferias"] = method_exists($insertEmpregado, "Ferias") ? null : "err Ferias";
		return $v_err;
	}
	//Inserção de todas as 12 tabelas do empregado
	public function InserirEmpregado($database, $jsonEmpregado){
		$insertEmpregado = json_decode($jsonEmpregado); //Decodificando o JSON
		if (json_last_error() != 0){ //testa se houve erro no parsing
			$retorno["status"] = "erro";
			$retorno["resposta"] = json_last_error(); 
			return $retorno;
		}
		//Validando o JSON
		$v_err = $this->ValidaJson($insertEmpregado);
		foreach ($v_err as $k => $v)
			if($v){
				$retorno["status"] = "erro";
				$retorno["resposta"] = $v_err; 
				return $retorno;
			}
		//Inserindo no BD
		$database->pdo->beginTransaction(); //Inicio de uma Transaction
			//Todos os Inserts
			$database->insert("RegistroEmpregado",(array) $insertEmpregado->RegistroEmpregado);
			$database->insert("CaracFisicas", (array) $insertEmpregado->CaracFisicas);
			$database->insert("Contrato", (array) $insertEmpregado->Contrato);
			$database->insert("SituacaoFGTS", (array) $insertEmpregado->SituacaoFGTS);
			$database->insert("Estrangeiros", (array) $insertEmpregado->Estrangeiros);
			$database->insert("PIS", (array) $insertEmpregado->PIS);
			$database->insert("Beneficiarios", (array) $insertEmpregado->Beneficiarios);
			$database->insert("Salario", (array) $insertEmpregado->Salario);
			$database->insert("Cargo", (array) $insertEmpregado->Cargo);
			$database->insert("ContribSindical", (array) $insertEmpregado->ContribSindical);
			$database->insert("ADP", (array) $insertEmpregado->ADP);
			$database->insert("Ferias", (array) $insertEmpregado->Ferias);
		//Avaliação de possivel erro e retorno da função
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
	//Consulta de um empregado pelo livro
	public function BuscaEmpregadoPorLivro($database, $livro){
		$data = $database->select(
			"RegistroEmpregado", 
			array("[><]Contrato" => "idRegistro"),
			array("RegistroEmpregado.numFolha", "Contrato.nomeEmpregado"),
			array("RegistroEmpregado.numLivro" => $livro)
			);
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
	//Pesquisa por nome
	public function PesquisaNomeRapida($database, $nome){
		$data = $database->select(
			"Contrato", 
			["nomeEmpregado", "Cpf", "idRegistro"],
			["nomeEmpregado[~]" => $nome."_"]
			);
		$data = json_encode($data);
		return $data;
	}
	//Pesquisa por CPF
	public function PesquisaCPF($database, $cpf){
		$data = $database->select(
			"Contrato",
			["nomeEmpregado", "idRegistro"],
			["Cpf" => $cpf]
			);
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
	//Inserir Ferias
	public function InserirFerias($database, $json){
		$insertFerias = json_decode($json); //Decodificando o JSON
		if (json_last_error() != 0){ //testa se houve erro no parsing
			$retorno["status"] = "erro";
			$retorno["resposta"] = json_last_error(); 
			return $retorno;
		}
		$database->pdo->beginTransaction(); //Inicio de uma Transaction
			$database->insert("Ferias", $insertFerias);	
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
	//Alterar Salario
	public function NovoSalario($database, $json){
		$insertSalario = json_decode($json); //Decodificando o JSON
		if (json_last_error() != 0){ //testa se houve erro no parsing
			$retorno["status"] = "erro";
			$retorno["resposta"] = json_last_error(); 
			return $retorno;
		}
		$database->pdo->beginTransaction(); //Inicio de uma Transaction
			$database->insert("Salario", $insertSalario);	
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
	//Alterar Cargo
	public function NovoCargo($database, $json){
		$insertCargo = json_decode($json); //Decodificando o JSON
		if (json_last_error() != 0){ //testa se houve erro no parsing
			$retorno["status"] = "erro";
			$retorno["resposta"] = json_last_error(); 
			return $retorno;
		}
		$database->pdo->beginTransaction(); //Inicio de uma Transaction		
			$database->insert("Cargo", $insertCargo);
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
	//Adicionar Contribuição fiscal
	public function InserirContribSindical($database, $json){
		$insertContribSindical = json_decode($json); //Decodificando o JSON
		if (json_last_error() != 0){ //testa se houve erro no parsing
			$retorno["status"] = "erro";
			$retorno["resposta"] = json_last_error(); 
			return $retorno;
		}
		$database->pdo->beginTransaction(); //Inicio de uma Transaction		
			$database->insert("ContribSindical", $insertContribSindical);
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
	//Adicionar Acidentes ou doenças profissionais
	public function InserirADP($database, $json){
		$insertADP = json_decode($json); //Decodificando o JSON
		if (json_last_error() != 0){ //testa se houve erro no parsing
			$retorno["status"] = "erro";
			$retorno["resposta"] = json_last_error(); 
			return $retorno;
		}
		$database->pdo->beginTransaction(); //Inicio de uma Transaction	
			$database->insert("ADP", $insertADP);
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
	//função retorna todos os dados do Empregado
	public function RetornaEmpregado($database, $idRegistro){
		$data = [];
			$data["RegistroEmpregado"] = $database->select("RegistroEmpregado", "*", ["idRegistro" => $idRegistro]);
			$data["CaracFisicas"] = $database->select("CaracFisicas", "*", ["idRegistro" => $idRegistro]);
			$data["Contrato"] = $database->select("Contrato", "*", ["idRegistro" => $idRegistro]);
			$data["SituacaoFGTS"] = $database->select("SituacaoFGTS", "*", ["idRegistro" => $idRegistro]);
			$data["Estrangeiros"] = $database->select("Estrangeiros", "*", ["idRegistro" => $idRegistro]);
			$data["PIS"] = $database->select("PIS", "*", ["idRegistro" => $idRegistro]);
			$data["Beneficiarios"] = $database->select("Beneficiarios", "*", ["idRegistro" => $idRegistro]);
			$data["Salario"] = $database->select("Salario", "*", ["idRegistro" => $idRegistro]);
			$data["Cargo"] = $database->select("Cargo", "*", ["idRegistro" => $idRegistro]);
			$data["ContribSindical"] = $database->select("ContribSindical", "*", ["idRegistro" => $idRegistro]);
			$data["ADP"] = $database->select("ADP", "*", ["idRegistro" => $idRegistro]);
			$data["Ferias"] = $database->select("Ferias", "*", ["idRegistro" => $idRegistro]);
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