/* cria banco de dados */
create database sustenta
default character set utf8
default collate utf8_general_ci;

use sustenta;

/* cria tabela de usuarios */
create table usuarios (
	id int(11) not null auto_increment,
    nome varchar(256) not null,
    usuario varchar(100) not null unique,
    senha varchar(256) not null,
    tipo varchar(100) not null,
    primary key (id)
) default charset = utf8;

/* cria tabela de estoque */
create table estoque (
	id int not null auto_increment,
    item varchar(100) not null unique,
    quantidade int not null,
    unidade varchar(20) not null,
    validade date not null,
    primary key (id)
) default charset = utf8;

/* cria tabela de histórico*/
create table historico (
	id int not null auto_increment,
    diaMesAno date not null,
    item varchar(100) not null,
    quantidade int not null,
    unidade varchar(20) not null,
    primary key (id)
) default charset = utf8;

select * from historico;


SELECT item, SUM(t.quantidade) AS total, t.unidade
FROM (
	SELECT diaMesAno, item, quantidade, unidade FROM historico WHERE quantidade > 0 AND (diaMesAno >= '2017-12-13' AND diaMesAno <= '2017-12-13')
) t
GROUP BY item, unidade;
