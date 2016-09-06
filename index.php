<?php 
require_once 'empregado.class.php';
require_once 'livro.class.php';
require_once 'config.php';

//Empregado
$json1 = '{
	"RegistroEmpregado":{
		"idRegistro" : 4,
		"numFolha": 4,
		"numLivro": 1,
		"data" : null,
		"cidade" : null,
		"assinaturaEmpregado": null,
		"observacao": null,
		"dataDemissao": null,
		"docsRecebidos": null
	},
	"Contrato":{
		"idContrato": 4,
		"idRegistro": 4,
		"nomeEmpregado": "nome bonito",
		"numCtps": null,
		"serieCtps": null,
		"ctpsRural": null,
		"serieCtpsRural": null,
		"Cpf": null,
		"tituloEleitor": null,
		"zona": null,
		"rg": null,
		"dataAdmissao": null,
		"cargo": null,
		"salario": null,
		"salarioExtenso": null,
		"periodoSalarial": null,
		"horaEntrada": null,
		"horaSaida": null,
		"horaIntervalo": null
	}
}';

$empregado = new Empregado;
$livro = new Livro;
$nome = "3";
$empregado->PesquisaNomeRapida($database, $nome);
//$empregado->InserirEmpregado($json1, $database);
//$nome = "nome bonito";
//$empregado->BuscaEmpregadoPorNome($database, $nome);
//$livro->RelacaoEmpregPorLivro($database);

?>