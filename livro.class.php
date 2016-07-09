<?php
class Livro {
	//Função para avaliar se todos os livros existentes estão fechados
	public function AvaliaStatus($database){
		$data = $database->select("Livro", "status");
		foreach ($data as $k => $v) {
			if($v == 1) return false;
		}
		return true;
	}

	//Validação do  JSON
	public function ValidaJson($insertLivro, $database){
		
		/*$v_err["numLivro"] = $insertLivro["numLivro"] ? null : "err numLivro";*/
		$v_err["cnpj"] = preg_match("\d{2}\.\d{3}\.\d{3}\/\d{4}\-\d{2}",$insertLivro["cnpj"])? null : "err cnpj";
		$v_err["numFolhas"] = is_numeric($insertLivro["numFolhas"]) ? null : "err numFolhas";
		$v_err["drtLocal"] = $insertLivro["drtLocal"] ? null : "err drtLocal";
		$v_err["livroAnterior"] = is_numeric($insertLivro["livroAnterior"]) ? null : "err livroAnterior";
		$v_err["data"] = preg_match("\d{4}\/\d{2}\/\d{1,2}\/",$insertLivro["data"])? null : "err data";
		/*$v_err["assinaturaEmpregador"] = $insertLivro["assinaturaEmpregador"] ? null : "err assinaturaEmpregador";*/
		$v_err["status"] = $this->AvaliaStatus($database) ? null : "err - existe um livro aberto";

		return $v_err;
	}

	//Função para inserir o Livro
	public function InserirLivro($jsonLivro, $database){
		$insertLivro = json_decode($jsonLivro); //Decodificando o JSON
		$insertLivro = (array) $insertLivro->Livro; //Criando array para o insert
		//Validando o JSON
		$v_err = $this->ValidaJson($insertLivro, $database);
		foreach ($v_err as $k => $v) {
			if($v) return $v_err;
		}
		//Inserindo no BD
		$database->pdo->beginTransaction(); //Inicio de uma Transaction
			$database->insert("Livro",$insertLivro); //Inserindo
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

	//Função para selecionar todos os livros existentes no BD
	public function BuscaLivros($database){
		$data = $database->select("Livro", "*");
		$data = json_encode($data);
		return $data;
	}

}
?>