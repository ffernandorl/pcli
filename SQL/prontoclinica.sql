CREATE DATABASE prontoclinica;

USE prontoclinica;

-- TABELAS --

CREATE TABLE Empresa (
	nome varchar(40) NOT NULL,
	endereco varchar(100) NOT NULL,
	numResidencia int(11) NOT NULL,
	cidade varchar(30) NOT NULL,
	estado varchar(30) NOT NULL,
	areaAtuacao varchar(30) NOT NULL,
	inscEstadual varchar(50) NOT NULL,
	cnpj varchar(14) NOT NULL,
	inss int(20) NOT NULL,
	PRIMARY KEY (cnpj)
) ENGINE=InnoDB;

CREATE TABLE Livro(
	numLivro int(11) NOT NULL AUTO_INCREMENT,
	numFolhas int(11),
	drtLocal varchar(50),
	livroAnterior int(11),
	data date,
	assinaturaEmpregador varchar(50),
	status char(1),
	numEmpregados int(11) NOT NULL,
	PRIMARY KEY (numLivro)
) ENGINE=InnoDB;

CREATE TABLE TermoDeEncerramento (
	idTE int(11) AUTO_INCREMENT,
	data date,
	numLivro int(11),
	cidade varchar(30),
	assinaturaEmpregador varchar(50),
	PRIMARY KEY (idTE),
	FOREIGN KEY (numLivro) REFERENCES Livro (numLivro)
) ENGINE=InnoDB;

-- REGISTRO DE EMPREGAODS --

CREATE TABLE RegistroEmpregado (
	idRegistro int(11) NOT NULL AUTO_INCREMENT,
	numFolha int(11),
	numLivro int(11),
	data date,
	cidade varchar(40),
	assinaturaEmpregadoEntrada varchar(50),
	assinaturaEmpregadoSaida varchar(50),
	observacao varchar(140),
	dataDemissao date,
	docsRecebidos varchar(140),
	beneficiarios varchar(140),
	PRIMARY KEY (idRegistro),
	FOREIGN KEY (numLivro) REFERENCES Livro (numLivro)
) ENGINE=InnoDB;

-- TABELAS DEPENDENTES DO REGISTRO DE EMPREGADO --

CREATE TABLE CaracFisicas (
	idCF int(11) NOT NULL AUTO_INCREMENT,
	idRegistro int(11),
	cor varchar(30),
	cabelo varchar(30),
	olhos varchar(30),
	altura varchar(10),
	peso varchar(10),
	sinais varchar(50),
	PRIMARY KEY (idCF),
	FOREIGN KEY (idRegistro) REFERENCES RegistroEmpregado (idRegistro)
) ENGINE=InnoDB;

CREATE TABLE Contrato (
	idContrato int(11) NOT NULL AUTO_INCREMENT,
	idRegistro int(11),
	nomeEmpregado varchar(100),
	numCtps varchar(15),
	serieCtps varchar(4),
	ctpsRural varchar(15),
	serieCtpsRural varchar(15),
	Cpf varchar(11),
	tituloEleitor varchar(12),
	zona varchar(5),
	rg varchar(7),
	dataAdmissao date,
	cargo varchar(140),
	salario varchar(15),
	salarioExtenso varchar(140),
	periodoSalarial varchar(15),
	horaEntrada varchar(15),
	horaSaida varchar(15),
	horaIntervalo varchar(15),
	PRIMARY KEY (idContrato),
	FOREIGN KEY (idRegistro) REFERENCES RegistroEmpregado (idRegistro)
) ENGINE=InnoDB;

CREATE TABLE SituacaoFGTS (
	idSFGTS int(11) NOT NULL AUTO_INCREMENT,
	idRegistro int(11),
	optante char(1),
	dataOpcao date,
	dataRetratacao date,
	bancoDepositario varchar(140),
	nacionalidade varchar(20),
	nomePai varchar(75),
	nomeMae varchar(75),
	cidadeOrigem varchar(40),
	estado varchar(40),
	dataNascimento date,
	estadoCivil varchar(8),
	nomeConjuge varchar(50),
	grauInstrucao varchar(30),
	residencia varchar(140),
	cnh varchar(15),
	numCM int(11),
	serieCM int(4),
	categCM int(4),	
	PRIMARY KEY (idSFGTS),
	FOREIGN KEY (idRegistro) REFERENCES RegistroEmpregado (idRegistro)
) ENGINE=InnoDB;

CREATE TABLE Estrangeiros (
	idEstrangeiro int(11) NOT NULL AUTO_INCREMENT,
	idRegistro int(11),
	numCarteira int(5),
	numRG varchar(15),
	conjugeBrasileiro char(1),
	nomeConjuge varchar(40),
	filhosBrasileiros char(1),
	qtsFilhos int(11),
	dataChegada date,
	naturalizado char(1),
	numDecreto int(11),
	PRIMARY KEY (idEstrangeiro),
	FOREIGN KEY (idRegistro) REFERENCES RegistroEmpregado (idRegistro)
) ENGINE=InnoDB;

CREATE TABLE PIS (
	idPIS int(11) NOT NULL AUTO_INCREMENT,
	idRegistro int(11),
	dataCadastro date,
	numCadastro int(11),
	depBanco varchar(40),
	endereco varchar(40),
	codBanco int(11),
	codAgencia int(11),
	enderecoAgencia varchar(40),
	observacao varchar(140),
	PRIMARY KEY (idPIS),
	FOREIGN KEY (idRegistro) REFERENCES RegistroEmpregado (idRegistro)
) ENGINE=InnoDB;

CREATE TABLE Salario (
	idSalario int(11) NOT NULL AUTO_INCREMENT,
	idRegistro int(11),
	data date,
	salario varchar(15),
	salarioAnterior varchar(15),
	frequencia varchar(25),
	PRIMARY KEY (idSalario),
	FOREIGN KEY (idRegistro) REFERENCES RegistroEmpregado (idRegistro)
) ENGINE=InnoDB;

CREATE TABLE Cargo (
	idCargo int(11) NOT NULL AUTO_INCREMENT,
	idRegistro int(11),
	data date,
	cargo varchar(50),
	PRIMARY KEY (idCargo),
	FOREIGN KEY (idRegistro) REFERENCES RegistroEmpregado (idRegistro)
) ENGINE=InnoDB;
	
CREATE TABLE ContribSindical (
	idContrib int(11) NOT NULL AUTO_INCREMENT,
	idRegistro int(11),
	numGuia int(5),
	data date,
	sindicato varchar(50),
	PRIMARY KEY (idContrib),
	FOREIGN KEY (idRegistro) REFERENCES RegistroEmpregado (idRegistro)
) ENGINE=InnoDB;

CREATE TABLE ADP (
	idADP int(11) NOT NULL AUTO_INCREMENT,
	idRegistro int(11),
	descricao varchar(140),
	data date,
	dataAlta date,
	PRIMARY KEY (idADP),
	FOREIGN KEY (idRegistro) REFERENCES RegistroEmpregado (idRegistro)
) ENGINE=InnoDB;

CREATE TABLE Ferias (
	idFerias int(11) NOT NULL AUTO_INCREMENT,
	idRegistro int(11),
	dataInicio date,
	dataTermino date,
	inicioPeriodo date,
	terminoPeriodo date,
	PRIMARY KEY (idFerias),
	FOREIGN KEY (idRegistro) REFERENCES RegistroEmpregado (idRegistro)
) ENGINE=InnoDB;


-- login e senha --

CREATE TABLE Login (
	user varchar(12),
	k varchar(60),
	PRIMARY KEY (user)
) ENGINE=InnoDB;

-- insercoes de dados --

insert into Empresa values ("Prontoclinica", "Rua das Abobrinhas", "2030", "Parnaíba", "Piauí", "Odontologia", "123456", "123456789", 9876543210 );
insert into Livro values (NULL , 50, "123456789", NULL, "2016/10/02", "jose", "1", 3);
insert into RegistroEmpregado values (NULL, "2", "1", "2016/10/02", "Parnaíba", "jose", NULL, NULL, NULL, NULL, "josezinho" );
insert into CaracFisicas values (NULL, 1, "azul", "longo", "vesgo", "1,72", "65kg", "de transito");
insert into Contrato values (NULL, 1, "senhor das neves", "123456789", "1234", NULL, NULL, "123456789", "1234567890", "20523", "1234567", "2016/12/08", "consultor", "10", "dez", "mensal", "08:00", "16:00", "01:00" );
