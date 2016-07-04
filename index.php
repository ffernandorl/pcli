<?php 
require_once 'inserir.class.php';
require_once 'livro.class.php';
require_once 'config.php';
/*
//Empregado
$json = "";
$empregado = new Empregado;
$empregado->InserirEmpregado($json, $database);
*/
//Livro
$json = '{
	"Livro":{
		"numLivro" : 1,
		"cnpj": null,
		"numFolhas": null,
		"drtLocal" : null,
		"livroAnterior" : null,
		"data": null,
		"assinaturaEmpregador": null,
		"status": "1"
	}
}';
$livro = new Livro;
$livro->InserirLivro($json, $database);
$livro->BuscaLivros($database);
$livro->AvaliaStatus($database);

?>