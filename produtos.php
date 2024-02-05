<?php
include 'db.php';

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $query = "SELECT * FROM produtos 
        INNER JOIN categorias ON produtos.id_categoria = categorias.id_categoria
        WHERE nome_produto LIKE '%$search%'";
} else {
    $query = "SELECT produtos.*, categorias.nome_categoria 
        FROM produtos
        INNER JOIN categorias ON produtos.id_categoria = categorias.id_categoria
        WHERE 1=1";
}

if (isset($_GET['categoria']) && $_GET['categoria'] != '') {
    $categoria_filtro = $_GET['categoria'];
    $query .= " AND categorias.id_categoria = $categoria_filtro";
}

$result = sqlsrv_query($conn, $query);
if ($result === false) {
    die(print_r(sqlsrv_errors(), true));
}

if(isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM produtos WHERE id_produto = ?";
    $params = array($delete_id);
    $delete_stmt = sqlsrv_query($conn, $delete_query, $params);
    if ($delete_stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    header("Location: produtos.php");
}


$result = sqlsrv_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Produtos</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Lista de Produtos</h2>
    <a href="index.php" class="btn btn-info mb-3">Voltar</a>
    <form class="form-inline mb-2" method="get" action="">
        <div class="form-group mr-2">
            <label for="search" class="sr-only">Buscar Produto:</label>
            <input type="text" class="form-control" id="search" name="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" placeholder="Buscar Produto">
        </div>
        <div class="form-group mr-2">
            <label for="categoria" class="mr-2">Filtrar por Categoria:</label>
            <select class="form-control" id="categoria" name="categoria">
                <option value="" <?php echo empty($_GET['categoria']) ? 'selected' : ''; ?>>Todas as Categorias</option>
                <?php
                $queryCategorias = "SELECT * FROM categorias";
                $resultCategorias = sqlsrv_query($conn, $queryCategorias);

                while ($rowCategoria = sqlsrv_fetch_array($resultCategorias, SQLSRV_FETCH_ASSOC)) {
                    $selected = ($_GET['categoria'] == $rowCategoria['id_categoria']) ? 'selected' : '';
                    echo "<option value=\"{$rowCategoria['id_categoria']}\" $selected>{$rowCategoria['nome_categoria']}</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>
    <?php
    if (sqlsrv_has_rows($result)) {
    ?>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Preço</th>
                <th>Categoria</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) { ?>
                <tr>
                    <td><?php echo $row['id_produto']; ?></td>
                    <td><?php echo $row['nome_produto']; ?></td>
                    <td><?php echo $row['descricao_produto']; ?></td>
                    <td><?php echo $row['preco_produto']; ?></td>
                    <td><?php echo $row['nome_categoria']; ?></td>
                    <td>
                        <a href="editar_produto.php?id=<?php echo $row['id_produto']; ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="produtos.php?delete_id=<?php echo $row['id_produto']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Deseja realmente excluir este produto?')">Excluir</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php
    } else {
        echo "<p>Nenhum produto encontrado.</p>";
    }
    ?>
    <a href="adicionar_produto.php" class="btn btn-success mb-3">Adicionar Produto</a>
</div>

</body>
</html>
