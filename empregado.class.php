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
	public function InserirEmpregado($jsonEmpregado, $database){
		$insertEmpregado = json_decode($jsonEmpregado); //Decodificando o JSON
		if (json_last_error() != 0) return json_last_error(); //testa se houve erro no parsing
		//Validando o JSON
		/*$v_err = $this->ValidaJson($insertEmpregado);
		foreach ($v_err as $k => $v)
			if($v) return json_encode($v_err);*/
		//Inserindo no BD
		$database->pdo->beginTransaction(); //Inicio de uma Transaction
			//Todos os Inserts
			$database->insert("RegistroEmpregado",(array) $insertEmpregado->RegistroEmpregado);
			//$database->insert("CaracFisicas", (array) $insertEmpregado->CaracFisicas);
			$database->insert("Contrato", (array) $insertEmpregado->Contrato);
			/*$database->insert("SituacaoFGTS", (array) $insertEmpregado->SituacaoFGTS);
			$database->insert("Estrangeiros", (array) $insertEmpregado->Estrangeiros);
			$database->insert("PIS", (array) $insertEmpregado->PIS);
			$database->insert("Beneficiarios", (array) $insertEmpregado->Beneficiarios);
			$database->insert("Salario", (array) $insertEmpregado->Salario);
			$database->insert("Cargo", (array) $insertEmpregado->Cargo);
			$database->insert("ContribSindical", (array) $insertEmpregado->ContribSindical);
			$database->insert("ADP", (array) $insertEmpregado->ADP);
			$database->insert("Ferias", (array) $insertEmpregado->Ferias);	*/
		//Avaliação de possivel erro e retorno da função
		$e = $database->error();
		if($e[1] == null){
			$database->pdo->commit();
			return true;
		} else {
			$database->pdo->rollBack();
			return json_encode($e);
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
		$data = json_encode($data);
		var_dump($data);
	}
	//Consulta de um empregado pelo nome
	public function BuscaEmpregadoPorNome($database, $nome){
		$registros = [];
		$data = $database->select(
			"RegistroEmpregado", 
			array("[><]Contrato" => "idRegistro"),
			array("idRegistro"),
			array("Contrato.nomeEmpregado" => $nome)
			);
		foreach ($data as $k => $v)
			array_push($registros, $v["idRegistro"]);
		foreach ($registros as $k => $v) {
			$data = $database->select("RegistroEmpregado", "*", ["idRegistro" => $v]);
			$empregado[$v]["RegistroEmpregado"] = $data;
			$data = $database->select("Contrato", "*", ["idRegistro" => $v]);
			$empregado[$v]["Contrato"] = $data;
		}
		$empregado = json_encode($empregado);
		var_dump($empregado);
	}
	//Pesquisa por nome
	public function PesquisaNomeRapida($database, $nome){
		$data = $database->select(
			"Contrato", 
			["nomeEmpregado", "Cpf", "idRegistro"],
			["nomeEmpregado[~]" => $nome]
			);
		$data = json_encode($data);
		return $data;
	}
	//Inserir Ferias
	public function InserirFerias($database, $json){
		$insertFerias = json_decode($json); //Decodificando o JSON
		if (json_last_error() != 0) return json_last_error(); //testa se houve erro no parsing
		$database->insert("Ferias", $insertFerias);	
		$e = $database->error();
		if($e[1] == null) 
			return true;
		else 
			return json_encode($e);
	}
	//Alterar Salario
	public function NovoSalario($database, $json){
		$insertSalario = json_decode($json); //Decodificando o JSON
		if (json_last_error() != 0) return json_last_error(); //testa se houve erro no parsing
		$database->insert("Salario", $insertSalario);	
		$e = $database->error();
		if($e[1] == null) 
			return true;
		else 
			return json_encode($e);
	}
	//Alterar Cargo
	public function NovoCargo($database, $json){
		$insertCargo = json_decode($json); //Decodificando o JSON
		if (json_last_error() != 0) return json_last_error(); //testa se houve erro no parsing
		$database->insert("Cargo", $insertCargo);
		$e = $database->error();
		if($e[1] == null) 
			return true;
		else 
			return json_encode($e);	
	}
	//Adicionar Contribuição fiscal
	public function InserirContribSindical($database, $json){
		$insertContribSindical = json_decode($json); //Decodificando o JSON
		if (json_last_error() != 0) return json_last_error(); //testa se houve erro no parsing
		$database->insert("ContribSindical", $insertContribSindical);
		$e = $database->error();
		if($e[1] == null) 
			return true;
		else 
			return json_encode($e);	
	}
	//Adicionar Acidentes ou doenças profissionais
	public function InserirADP($database, $json){
		$insertADP = json_decode($json); //Decodificando o JSON
		if (json_last_error() != 0) return json_last_error(); //testa se houve erro no parsing
		$database->insert("ADP", $insertADP);
		$e = $database->error();
		if($e[1] == null) 
			return true;
		else 
			return json_encode($e);	
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
		$json = json_encode($data);
		if (json_last_error() != 0) return json_last_error(); //testa se houve erro no parsing
		return $json;
	}
}
 
?>