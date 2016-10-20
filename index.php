<?php 
/**
* @author Francisco Fernando
* @copyright 2016 LATECS
*/
require_once 'empregado.class.php';
require_once 'livro.class.php';
require_once 'config.php';
/**
 * function of request routing 
 * @return array array of response by function requested
 * @param array $request 
 */
function IndexController($request){
	$empregado = new Empregado;
	$livro = new Livro;
	switch ($request[0]) {
		case "empregado.IE":
			return $empregado->InserirEmpregado($database, $request[1]);
			break;
		case "empregado.BEPL":
			return $empregado->BuscaEmpregadoPorLivro($database, $request[1]);
			break;
		case "empregado.PNR":
			return $empregado->PesquisaNomeRapida($database, $request[1]);
			break;
		case "empregado.PC":
			return $empregado->PesquisaCPF($database, $request[1]);
			break;
		case "empregado.IF":
			return $empregado->InserirFerias($database, $request[1]);
			break;
		case "empregado.NS":
			return $empregado->NovoSalario($database, $request[1]);
			break;
		case "empregado.NC":
			return $empregado->NovoCargo($database, $request[1]);
			break;
		case "empregado.ICS":
			return $empregado->InserirContribSindical($database, $request[1]);
			break;
		case "empregado.IADP":
			return $empregado->InserirADP($database, $request[1]);
			break;
		case "empregado.RE":
			return $empregado->RetornaEmpregado($database, $request[1]);
			break;
		case "livro.IL":
			return $livro->InserirLivro($database, $request[1]);
			break;
		case "livro.REPL":
			return $livro->RelacaoEmpregPorLivro($database);
			break;
		case "livro.DL":
			return $livro->DadosLivro($database);
			break;
		case "livro.EL":
			return $livro->EncerraLivro($database, $request[1]);
			break;		
		default:
			return "Erro - requisição não encontrada";
			break;
	}
}

if (!empty($_POST)){
	$request = json_decode($_POST["request"], true);
	//test if happened an error in parsing of request
	if (json_last_error() == 0){ 
		$request = json_encode(IndexController($request));
		//test if happened an error in parsing of response
		if (json_last_error() == 0){ 
			echo $request;
		}else {
			$err["status"] = "erro";
			$err["resposta"] = "parsing erro";
		}
	} else {
		$err["status"] = "erro";
		$err["resposta"] = "parsing erro";
	}
}
?>