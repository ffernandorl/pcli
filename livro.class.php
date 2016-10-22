<?php
/**
* @author Francisco Fernando
* @copyright 2016 LATECS
*/
class Livro {
	/**
	 * Function to evaluate if all books are closed
	 * @return boolean true: all books are closed
	 * @param resource $database
	 */
	public function AvaliaStatus($database){
		$data = $database->select("Livro", "status");
		foreach ($data as $k => $v)
			if($v == 1) return false;
		return true;
	}
	/**
	 * JSON Validation
	 * @return array array of errors
	 * @param resource array $database $insertLivro
	 */
	public function ValidaJson($database, $insertLivro){
		$v_err["numFolhas"] = is_numeric($insertLivro["numFolhas"]) ? null : "err numFolhas";
		$v_err["drtLocal"] = $insertLivro["drtLocal"] ? null : "err drtLocal";
		$v_err["livroAnterior"] = is_numeric($insertLivro["livroAnterior"]) ? null : "err livroAnterior";
		$v_err["data"] = preg_match("\d{4}\/\d{2}\/\d{1,2}\/",$insertLivro["data"])? null : "err data";
		$v_err["status"] = $this->AvaliaStatus($database) ? null : "err - existe um livro aberto";
		return $v_err;
	}
	/**
	 * Insertion of books
	 * @return array array of response
	 * @param resource object $database $jsonLivro
	 */
	public function InserirLivro($database, $jsonLivro){
		$insertLivro = json_decode($jsonLivro); //Decoding JSON
		if (json_last_error() != 0){ //test if happened an error in parsing
			$retorno["status"] = "erro";
			$retorno["resposta"] = json_last_error(); 
			return $retorno;
		}
		$insertLivro = (array) $insertLivro->Livro; //array creating for insertion
		//JSON Validation
		$v_err = $this->ValidaJson($insertLivro, $database);
		foreach ($v_err as $k => $v)
			if($v){
				$retorno["status"] = "erro";
				$retorno["resposta"] = $v_err; 
				return $retorno;
			}
		//Insertion in Database
		$database->pdo->beginTransaction(); //begining a Transaction
			$database->insert("Livro",$insertLivro); //Insertion
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
	 * return relation of employee separated by books
	 * @return array array of response
	 * @param resource $database
	 */
	public function RelacaoEmpregPorLivro($database){
		$data = $database->select("Livro","numLivro");
		foreach ($data as $k => $v) {
			$livro[$v] = $database->select(
				"RegistroEmpregado", 
				array("[><]Contrato" => "idRegistro"),
				array("RegistroEmpregado.numFolha", "Contrato.nomeEmpregado"),
				array("RegistroEmpregado.numLivro" => $v)
				);
		}
		//evaluation of possible error and return of function
		$e = $database->error();
		if($e[1] == null){
			$retorno["status"] = "ok";
			$retorno["resposta"] = $livro; 
			return $retorno;
		} else {
			$retorno["status"] = "erro";
			$retorno["resposta"] = $e; 
			return $retorno;
		}
	}
	/**
	 * return data of all books
	 * @return array array of response
	 * @param resource $database
	 */
	public function DadosLivro($database){
		$data = $database->select("Livro", "*");
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
	 * close the book
	 * @return array array of response
	 * @param resource string $database $livro
	 */
	public function EncerraLivro($database, $livro){
		$database->update(
			"Livro",
			["status" => "0"],
			["numLivro" => $livro]
			);
		//evaluation of possible error and return of function
		$e = $database->error();
		if($e[1] == null){
			$retorno["status"] = "ok";
			$retorno["resposta"] = $database->select("Livro", "status", ["numLivro" => $livro] ); 
			return $retorno;
		} else {
			$retorno["status"] = "erro";
			$retorno["resposta"] = $e; 
			return $retorno;
		}
	}
}
?>