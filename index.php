<?php 
require_once 'empregado.class.php';
require_once 'livro.class.php';
require_once 'config.php';

//Empregado
$json = '{
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
//$empregado->InserirEmpregado($json, $database);
//$livro = "1";
//$empregado->BuscaEmpregadoPorLivro($database, $livro);
$nome = "nome bonito";
$empregado->BuscaEmpregadoPorNome($database, $nome);

?>
