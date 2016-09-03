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
		return $data;
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
			$empregado["test".$v]["RegistroEmpregado"] = $data;
			$data = $database->select("Contrato", "*", ["idRegistro" => $v]);
			$empregado["test".$v]["Contrato"] = $data;
		}
		$empregado = json_encode($empregado);
		var_dump($empregado);
	}
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
	public function InserirNovoSalario($database, $json){
		$insertSalario = json_decode($json); //Decodificando o JSON
		if (json_last_error() != 0) return json_last_error(); //testa se houve erro no parsing
		$database->insert("Salario", $insertSalario);	
		$e = $database->error();
		if($e[1] == null) 
			return true;
		else 
			return json_encode($e);
	}
	public function InserirNovoCargo($database, $json){
		$insertCargo = json_decode($json); //Decodificando o JSON
		if (json_last_error() != 0) return json_last_error(); //testa se houve erro no parsing
		$database->insert("Cargo", $insertCargo);
		$e = $database->error();
		if($e[1] == null) 
			return true;
		else 
			return json_encode($e);	
	}
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
}

?>