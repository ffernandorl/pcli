<?php
/**
* @author Francisco Fernando
* @copyright 2016 LATECS
*/
require_once 'retorno.class.php';
class Livro {
	/**
	 * Function to evaluate if all books are closed
	 * @return boolean true: all books are closed
	 * @param resource $database
	 */
	public function AvaliaStatus($database){
		$data = $database->select("Livro", "status");
		foreach ($data as $k => $v)
			if($v == 'a') return false;
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
		$v_err["data"] = preg_match("/\d{4}\-\d{2}-\d{2}/",$insertLivro["data"])? null : "err data";
		$v_err["status"] = $this->AvaliaStatus($database) ? null : "err - existe um livro aberto";
		return $v_err;
	}
	/**
	 * Insertion of books
	 * @return array array of response
	 * @param resource array $database $insertLivro
	 */
	public function InserirLivro($database, $insertLivro){
		//JSON Validation
		$v = Retorno::ValidationJson($this->ValidaJson($database, $insertLivro));
		if ($v) return $v;	
		//Insertion in Database
		$database->pdo->beginTransaction(); //begining a Transaction
			$database->insert("Livro",$insertLivro); //Insertion
		//evaluation of possible error and return of function
		$e = $database->error();
		return Retorno::MedooErrorTest($e, true);
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
		return Retorno::MedooErrorTest($e, $data);
	}
	/**
	 * return data of all books
	 * @return array array of response
	 * @param resource $database
	 */
	public function DadosLivro($database){
		$data = $database->select("Livro","*");
		//evaluation of possible error and return of function
		$e = $database->error();
		return Retorno::MedooErrorTest($e, $data);
	}
	/**
	 * Add new 'TermoDeEncerramento' and close the book
	 * @return array array of response
	 * @param resource array $database $data
	 */
	public function EncerraLivro($database, $data){
		$database->insert("TermoDeEncerramento", 
				[
				"idTE" => null,
				"data" => $data["data"],
				"numLivro" => $data["numLivro"],
				"cidade" => "Parnaíba"
				]
			);
		$database->update(
			"Livro",
			["status" => "f"],
			["numLivro" => $data["numLivro"]]
			);
		//evaluation of possible error and return of function
		$e = $database->error();
		return Retorno::MedooErrorTest($e, true);
	}
	/**
	 * Return data from the table 'TermoDeEncerramento' by 'idLivro'
	 * @return array array of response
	 * @param resource string $database $livro
	 */
	public function TermoDeEncerramento($database, $livro){
		$data = $database->select("TermoDeEncerramento", "*", ["numLivro" => $livro]);
		$e = $database->error();
		return Retorno::MedooErrorTest($e, $data);
	}
}
?>