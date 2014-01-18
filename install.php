        <?php
		$cn = new mysqli("192.168.1.100", "root", "8511965");

		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());   
			exit();
		}

		if (!$cn->set_charset("utf8")) {
		   printf("Error loading character set utf8: %s\n", $cn->error);
		}

        $cn->query("CREATE DATABASE IF NOT EXISTS hortifruti DEFAULT COLLATE utf8_general_ci;");

		$cn->select_db("hortifruti");

        $vetor = vetor();
        $conta = count($vetor);

        for ($i = 0; $i < $conta; $i++) {

            //print $vetor[$i]; 
            if ($cn->query($vetor[$i])) {
                //echo "Tabela " . $i . ", criada com sucesso <br>";
            } else {
                echo $cn->error;
		die;
            }
        }

        function vetor() {

            return array(
				"CREATE TABLE tab_produto(
					id_produto bigint not null auto_increment,
					id_setor int not NULL,
					produto varchar(50) not null,
					tipo varchar(2) not null,
					preco decimal(8,2) not NULL,
					imagem varchar(100) not NULL,
					ativo TINYINT(1) NOT NULL DEFAULT 0,
					sub_produto bigint NOT NULL DEFAULT 0,
					sub_qtd decimal(7,3) NOT NULL DEFAULT 0,
					PRIMARY KEY(id_produto)
				) ENGINE = InnoDB;",

				"CREATE TABLE tab_sangria(
					id_sangria bigint not null auto_increment,
					id_caixa bigint NOT NULL,
					id_entidade bigint not NULL,
					id_especie bigint not NULL,
					data datetime not NULL,
					valor decimal(8,2) not NULL,
					obs varchar(255) not NULL,
					conferido tinyint(1) not NULL DEFAULT 0,
					PRIMARY KEY(id_sangria)
				) ENGINE = InnoDB;",

				"CREATE TABLE tab_setor(
					id_setor bigint NOT NULL auto_increment,
					setor varchar(50) NOT NULL,
					PRIMARY KEY(id_setor)
				) ENGINE = InnoDB;",

				"CREATE TABLE tab_pdvstatus(
					id_status bigint not NULL auto_increment,
					status varchar(50) not NULL,
					PRIMARY KEY(id_status)
				) ENGINE = InnoDB;",

				"CREATE TABLE tab_caixa(
					id_caixa bigint NOT NULL auto_increment,
					id_entidade bigint not NULL,
					pdv int NOT NULL,
					dataini datetime not NULL,
					trocoini decimal(8,2) not NULL,
					datafim datetime NULL,
					trocofim decimal(8,2) NULL,
					PRIMARY KEY(id_caixa)
				) ENGINE = InnoDB;",

				"CREATE TABLE tab_tmppreco(
					id_tmppreco bigint NOT NULL auto_increment,
					id_produto bigint not null,
					custo decimal(8,2) NOT NULL,
					venda decimal(8,2) NOT NULL default 0,
					PRIMARY KEY(id_tmppreco)
				) ENGINE = InnoDB;",
				
				"CREATE TABLE tab_ordemcompra(
					id_oc bigint NOT NULL auto_increment,
					id_entidade bigint not null,
					aberto tinyint(1) not null default 1,
					descricao varchar(40) not null,
					data datetime not NULL,
					obs varchar(255) NOT NULL,
					PRIMARY KEY(id_oc)
				) ENGINE = InnoDB;",

				"CREATE TABLE tab_ordemcompraitem(
					id_ocitem bigint NOT NULL auto_increment,
					id_oc bigint not null,
					id_produto bigint NOT NULL,
					qtdvol decimal(7,3) NOT NULL,
					vol1 decimal(7,3) NOT NULL,
					vol2 decimal(7,3) NOT NULL,
					tipo varchar(2) NOT NULL,
					custo decimal(8,2) NOT NULL,
					obs varchar(100) default '' not null,
					PRIMARY KEY(id_ocitem)
				) ENGINE = InnoDB;",

				"CREATE TABLE tab_listacompra(
					id_lc bigint NOT NULL auto_increment,
					descricao varchar(40) not null,
					PRIMARY KEY(id_lc)
				) ENGINE = InnoDB;",

				"CREATE TABLE tab_listacompraitem(
					id_lcitem bigint NOT NULL auto_increment,
					id_lc bigint not null,
					id_produto bigint NOT NULL,
					obs varchar(100) default '' not null,
					PRIMARY KEY(id_lcitem)
				) ENGINE = InnoDB;",
				
				"CREATE TABLE tab_catctspag(
					id_catctspag bigint NOT NULL auto_increment,
					catctspag varchar(40) not NULL,
					PRIMARY KEY(id_catctspag)
				) ENGINE = InnoDB;",

				"CREATE TABLE tab_entidade(
					id_entidade bigint NOT NULL auto_increment,
					tipo tinyint(2) not null default 0,
					datacad datetime not null,
					cpf varchar(14) not NULL default '',
					nome varchar(50) not NULL,
					endereco varchar(50) not NULL default '',
					bairro varchar(50) not NULL default '',
					cidade varchar(50) not NULL default '',
					telefone varchar(20) not NULL default '',
					obs varchar(255) not NULL default '',
					prazo tinyint(1) not NULL default 0,
					limite decimal(8,2) not NULL default 0,

					PRIMARY KEY(id_entidade)
				) ENGINE = InnoDB;",

				"CREATE TABLE tab_acesso(
					id_entidade bigint NOT NULL,
					usuario varchar(10) not NULL default '',
					senha varchar(32) not NULL default '',
					sessao varchar(32) not null default '';
					autorizado tinyint(1) NOT NULL DEFAULT 0,
					cancelaItem tinyint(1) NOT NULL DEFAULT 0,
					cancelaCupom tinyint(1) NOT NULL DEFAULT 0,
					sangria tinyint(1) NOT NULL DEFAULT 0,
					fechaCaixa tinyint(1) NOT NULL DEFAULT 0,
					desconto tinyint(1) NOT NULL DEFAULT 0,
					servidor tinyint(1) NOT NULL DEFAULT 0,
					PRIMARY KEY(id_entidade)
				) ENGINE = InnoDB;",

				"CREATE TABLE tab_codbar(
					id_produto bigint NOT NULL,
					codbar varchar(13) NOT NULL ,
					PRIMARY KEY(codbar)
				) ENGINE = InnoDB;",

				"CREATE TABLE tab_ctspag(
					id_ctspag bigint NOT NULL auto_increment,
					id_catctspag bigint NOT NULL,
					id_entidade bigint NOT NULL,
					datacad datetime NOT NULL,
					descricao varchar(100) NOT NULL,
					vencimento datetime NOT NULL,
					valor decimal(8,2) NOT NULL,
					datapago datetime NULL,
					valorpago decimal(8,2) NOT NULL,
					PRIMARY KEY(id_ctspag)
				) ENGINE = InnoDB;",

				"CREATE TABLE tab_empresa(
					empresa varchar(40) NOT NULL,
					telefone varchar(40) NOT NULL,
					endereco varchar(40) NOT NULL,
					linha1 varchar(40) NOT NULL,
					linha2 varchar(40) NOT NULL
				) ENGINE = InnoDB;",

				"CREATE TABLE tab_especie(
					id_especie bigint NOT NULL auto_increment,
					especie varchar(20) NOT NULL,
					ativo tinyint(1) NOT NULL DEFAULT 0,
					PRIMARY KEY(id_especie)
				) ENGINE = InnoDB;",

				"CREATE TABLE tab_estent(
					id_estent bigint NOT NULL auto_increment,
					id_entidade bigint NOT NULL,
					id_produto bigint NOT NULL,
					qtdvol decimal(7,3) NOT NULL,
					vol decimal(7,3) NOT NULL,
					tipo varchar(2) NOT NULL,
					data datetime NOT NULL,
					custo decimal(8,2) NOT NULL,
					PRIMARY KEY(id_estent)
				) ENGINE = InnoDB;",

				"CREATE TABLE tab_print(
					id_print bigint NOT NULL auto_increment,
					id_produto bigint NOT NULL,
					PRIMARY KEY(id_print)
				) ENGINE = InnoDB;",

				"CREATE TABLE tab_logestorno(
					id_estorno bigint NOT NULL auto_increment,
					id_entidade bigint NOT NULL,
					id_venda bigint NOT NULL,
					id_vendaitem int NOT NULL,
					data datetime NOT NULL,
					PRIMARY KEY(id_estorno)
				) ENGINE = InnoDB;",

				"CREATE TABLE tab_logdesconto(
					id_desconto bigint NOT NULL auto_increment,
					id_entidade bigint not NULL,
					id_venda int NULL,
					data datetime NULL,
					valor decimal(8,2) NULL,
					PRIMARY KEY(id_desconto)
				) ENGINE = InnoDB;",

				"CREATE TABLE tab_logpreco(
					id_preco bigint NOT NULL auto_increment,
					id_produto bigint NOT NULL,
					id_entidade bigint NOT NULL,
					data datetime NOT NULL,
					oldpreco decimal(8,2) NOT NULL,
					newpreco decimal(8,2) NOT NULL default 0,
					PRIMARY KEY(id_preco)
				) ENGINE = InnoDB;",

				"CREATE TABLE tab_vendapay(
					id_venda bigint not NULL,
					id_especie bigint not NULL,
					valor decimal(8,2) not NULL
				) ENGINE = InnoDB;",

				"CREATE TABLE tab_venda(
					id_venda bigint NOT NULL auto_increment,
					id_status bigint not NULL default 0,
					id_caixa bigint NOT NULL default 0,
					id_entidade bigint NOT NULL default 0,
					data datetime not NULL,
					total decimal(8,2) NULL,
					obs varchar(255) NULL,
					PRIMARY KEY(id_venda)
				) ENGINE = InnoDB;",

				"CREATE TABLE tab_vendaitem(
					id_vendaitem int NOT NULL,
					id_venda bigint NOT NULL,
					id_produto bigint NOT NULL,
					qtd decimal(7,3) NOT NULL,
					preco decimal(8,2) NOT NULL,
					PRIMARY KEY(id_vendaitem, id_venda)
				) ENGINE = InnoDB;"
            );
        }

	echo "Instalação Concluída!";
?>

