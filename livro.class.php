<?php
class Livro {
	public function InserirLivro($jsonLivro, $database){
		$insertLivro = json_decode($jsonLivro); //Decodificando o JSON
		$database->pdo->beginTransaction(); //Inicio de uma Transaction
			$database->insert("Livro",(array) $insertLivro->Livro); //Inserindo
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
		//var_dump($data);
	}
	//Função para avaliar se todos os livros existentes estão fechados
	public function AvaliaStatus($database){
		$data = $database->select("Livro", "status");
		var_dump($data);
		foreach ($data as $k => $v) {
			if($v == 1){
				return false;
			}
		}
		return true;
	}
}

?>