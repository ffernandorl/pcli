<?php 
/**
* @author Francisco Fernando
* @copyright 2016 LATECS
*/
require_once 'empregado.class.php';
require_once 'livro.class.php';
require_once 'empresa.class.php';
require_once 'config.php';
/**
 * function of request routing 
 * @return array array of response by function requested
 * @param array $request 
 */
function IndexController($request, $database){
	$empregado = new Empregado;
	$livro = new Livro;
	$empresa = new Empresa;
	switch ($request["method"]){
		case "empregado.IE":
			return $empregado->InserirEmpregado($database, $request["data"]);
			break;
		case "empregado.BEPL":
			return $empregado->BuscaEmpregadoPorLivro($database, $request["data"]);
			break;
		case "empregado.PNR":
			return $empregado->PesquisaNomeRapida($database, $request["data"]);
			break;
		case "empregado.PC":
			return $empregado->PesquisaCPF($database, $request["data"]);
			break;
		case "empregado.IF":
			return $empregado->InserirFerias($database, $request["data"]);
			break;
		case "empregado.NS":
			return $empregado->NovoSalario($database, $request["data"]);
			break;
		case "empregado.NC":
			return $empregado->NovoCargo($database, $request["data"]);
			break;
		case "empregado.ICS":
			return $empregado->InserirContribSindical($database, $request["data"]);
			break;
		case "empregado.IADP":
			return $empregado->InserirADP($database, $request["data"]);
			break;
		case "empregado.RE":
			return $empregado->RetornaEmpregado($database, $request["data"]);
			break;
		case "livro.IL":
			return $livro->InserirLivro($database, $request["data"][0]);
			break;
		case "livro.REPL":
			return $livro->RelacaoEmpregPorLivro($database);
			break;
		case "livro.DL":
			return $livro->DadosLivro($database);
			break;
		case "livro.EL":
			return $livro->EncerraLivro($database, $request["data"][0]);
			break;
		case "empresa.DE":
			return $empresa->DadosEmpresa($database);
			break;	
		default:
			$retorno["status"] = "400";
			$retorno["data"] = "request not found"; 
			return $retorno;
			break;
	}
}

	$request = json_decode(file_get_contents("php://input"), true);
	//test if happened an error in parsing of request
	if (json_last_error() == 0){ 
		$request = json_encode(IndexController($request,$database));
		//test if happened an error in parsing of response
		if (json_last_error() == 0){ 
			echo $request;
		}else {
			$err["status"] = "507";
			$err["data"] = "parsing error from Server JSON";
			echo json_encode($err);
		}
	} else {
		$err["status"] = "506";
		$err["data"] = "parsing error from Client JSON";
		echo json_encode($err);
	}


?>