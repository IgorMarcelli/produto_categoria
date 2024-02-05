<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_produto = strtoupper($_POST['nome_produto']);
    $descricao_produto = strtoupper($_POST['descricao_produto']);
    $preco_produto = $_POST['preco_produto'];
    $id_categoria = $_POST['id_categoria'];

    // Verifica se a categoria foi selecionada
    if (empty($id_categoria)) {
        die("Erro: Por favor, selecione uma categoria.");
    }

    // Verifica se o preço do produto é um número válido
    if (!is_numeric($preco_produto)) {
        die("Erro: O preço do produto deve ser um número válido.");
    }

    if ($conn === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $query = "INSERT INTO produtos (nome_produto, descricao_produto, preco_produto, id_categoria) VALUES (?, ?, ?, ?)";
    $params = array($nome_produto, $descricao_produto, $preco_produto, $id_categoria);

    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    header("Location: produtos.php");
}

// Busca todas as categorias para preencher o menu suspenso
$queryCategorias = "SELECT * FROM categorias";
$resultCategorias = sqlsrv_query($conn, $queryCategorias);
if ($resultCategorias === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Produto</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Adicionar Produto</h2>
    <a href="produtos.php" class="btn btn-info mt-3">Voltar</a>
    <form method="post" action="">
        <div class="form-group">
            <label for="nome_produto">Nome do Produto:</label>
            <input type="text" class="form-control" id="nome_produto" name="nome_produto" required>
        </div>
        <div class="form-group">
            <label for="descricao_produto">Descrição do Produto:</label>
            <textarea class="form-control" id="descricao_produto" name="descricao_produto" rows="3"></textarea>
        </div>
        <div class="form-group">
            <label for="preco_produto">Preço do Produto:</label>
            <input type="text" class="form-control" id="preco_produto" name="preco_produto" required oninput="validarPreco(this)">
        </div>
        <div class="form-group">
            <label for="id_categoria">Categoria:</label>
            <select class="form-control" id="id_categoria" name="id_categoria" required>
                <?php while ($rowCategoria = sqlsrv_fetch_array($resultCategorias, SQLSRV_FETCH_ASSOC)) { ?>
                    <option value="" disabled selected>Selecione uma Categoria</option>
                    <option value="<?php echo $rowCategoria['id_categoria']; ?>"><?php echo $rowCategoria['nome_categoria']; ?></option>
                <?php } ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Adicionar Produto</button>
    </form>
</div>
<script>
        function validarPreco(input) {
            var valor = input.value.replace(/\D/g, '');

            if (isNaN(valor) || valor === '') {
                valor = '';
            } else {
                valor = (parseFloat(valor) / 100).toFixed(2);
            }
            input.value = valor;
        }
    </script>
</body>
</html>
