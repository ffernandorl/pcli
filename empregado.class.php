<?php
class Empregado{
	//Validação do  JSON
	public function ValidaJson($insertEmpregado, $database){
		method_exists($insertEmpregado, "RegistroEmpregado");
		$v_err["numLivro"] = $insertLivro["numLivro"] ? null : "err numLivro";
		

		return $v_err;
	}
	//Função para inserir todas as 12 tabelas do empregado
	public function InserirEmpregado($jsonEmpregado, $database){
		$insertEmpregado = json_decode($jsonEmpregado); //Decodificando o JSON
		if (json_last_error() != 0) return json_last_error(); //testa se houve erro no parsing
		//Validando o JSON
		$v_err = $this->ValidaJson($insertEmpregado, $database);
		foreach ($v_err as $k => $v)
			if($v) return $v_err;
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
			return true;
		} else {
			$database->pdo->rollBack();
			return $e;
		}
	}
}
?>