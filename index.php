<?php 
require_once 'empregado.class.php';
require_once 'livro.class.php';
require_once 'config.php';

//Empregado
$json = '{
	"RegistroEmpregado":{
		"idRegistro" : 3,
		"numFolha": 3,
		"numLivro": 1,
		"data" : null,
		"cidade" : null,
		"assinaturaEmpregado": null,
		"observacao": null,
		"dataDemissao": null,
		"docsRecebidos": null
	},
	"Contrato":{
		"idContrato": 3,
		"idRegistro": 3,
		"nomeEmpregado": "nome bonito 3",
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
$empregado->InserirEmpregado($json, $database);
$livro = "1";
$empregado->BuscaRegistroEmpregado($database, $livro);

?>
