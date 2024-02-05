<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_produto = $_POST['id_produto'];
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

    $query = "UPDATE produtos SET nome_produto = ?, descricao_produto = ?, preco_produto = ?, id_categoria = ? WHERE id_produto = ?";
    $params = array($nome_produto, $descricao_produto, $preco_produto, $id_categoria, $id_produto);

    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    header("Location: produtos.php");
}

if (isset($_GET['id'])) {
    $id_produto = $_GET['id'];

    $queryProduto = "SELECT * FROM produtos WHERE id_produto = ?";
    $paramsProduto = array($id_produto);

    $stmtProduto = sqlsrv_query($conn, $queryProduto, $paramsProduto);

    if ($stmtProduto === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $rowProduto = sqlsrv_fetch_array($stmtProduto, SQLSRV_FETCH_ASSOC);
} else {
    echo "ID do produto não fornecido.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Editar Produto</h2>
    <form method="post" action="">
        <input type="hidden" name="id_produto" value="<?php echo $rowProduto['id_produto']; ?>">
        <div class="form-group">
            <label for="nome_produto">Nome do Produto:</label>
            <input type="text" class="form-control" id="nome_produto" name="nome_produto" value="<?php echo $rowProduto['nome_produto']; ?>" required>
        </div>
        <div class="form-group">
            <label for="descricao_produto">Descrição do Produto:</label>
            <textarea class="form-control" id="descricao_produto" name="descricao_produto" rows="3"><?php echo $rowProduto['descricao_produto']; ?></textarea>
        </div>
        <div class="form-group">
            <label for="preco_produto">Preço do Produto:</label>
            <input type="text" class="form-control" id="preco_produto" name="preco_produto" value="<?php echo $rowProduto['preco_produto']; ?>" required oninput="validarPreco(this)">
        </div>
        <div class="form-group">
            <label for="id_categoria">Categoria:</label>
            <select class="form-control" id="id_categoria" name="id_categoria" required>
                <?php
                $queryCategorias = "SELECT * FROM categorias";
                $resultCategorias = sqlsrv_query($conn, $queryCategorias);

                while ($rowCategoria = sqlsrv_fetch_array($resultCategorias, SQLSRV_FETCH_ASSOC)) {
                    $selected = ($rowCategoria['id_categoria'] == $rowProduto['id_categoria']) ? 'selected' : '';
                    echo "<option value=\"{$rowCategoria['id_categoria']}\" $selected>{$rowCategoria['nome_categoria']}</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    </form>
    <a href="produtos.php" class="btn btn-secondary mt-3">Voltar para Listar Produtos</a>
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
