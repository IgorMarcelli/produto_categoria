# produto_categoria
Projeto básico de CRUD

Este projeto contém um sistema simples de CRUD para gerenciar categorias e produtos.

## Configuração do Banco de Dados

1. Crie as seguintes tabelas em um banco de dados:

    ```sql
    CREATE TABLE categorias (
        id_categoria INT PRIMARY KEY AUTO_INCREMENT,
        nome_categoria VARCHAR(255) NOT NULL
    );

    CREATE TABLE produtos (
        id_produto INT PRIMARY KEY AUTO_INCREMENT,
        nome_produto VARCHAR(255) NOT NULL,
        descricao_produto TEXT,
        preco_produto DECIMAL(10,2),
        id_categoria INT,
        FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria)
    );
    ```

2. Crie um arquivo chamado `config.php` na raiz do projeto com o seguinte conteúdo:

    ```php
    <?php

    // Definindo constantes para as configurações do banco de dados
    define('DB_SERVER', 'seu_servidor');
    define('DB_DATABASE', 'seu_banco_de_dados');
    define('DB_USERNAME', 'seu_usuario');
    define('DB_PASSWORD', 'sua_senha');

    // Conectar ao banco de dados
    $conn = sqlsrv_connect(DB_SERVER, array(
        'Database' => DB_DATABASE,
        'Uid' => DB_USERNAME,
        'PWD' => DB_PASSWORD,
    ));

    if ($conn === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    ?>
    ```

    **Nota:** Substitua os valores de 'seu_servidor', 'seu_banco_de_dados', 'seu_usuario' e 'sua_senha' pelos seus valores de configuração.
