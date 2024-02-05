<?php
include 'db.php';

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $query = "SELECT * FROM categorias 
        WHERE nome_categoria LIKE '%$search%'";
} else {
    $query = "SELECT * FROM categorias";
}
$result = sqlsrv_query($conn, $query);
if ($result === false) {
    die(print_r(sqlsrv_errors(), true));
}

if(isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM categorias WHERE id_categoria = ?";
    $params = array($delete_id);
    $delete_stmt = sqlsrv_query($conn, $delete_query, $params);
    if ($delete_stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    header("Location: categorias.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Categorias</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Lista de Categorias</h2>
    <a href="index.php" class="btn btn-info mb-3">Voltar</a>
    <form class="form-inline mb-2" method="get" action="">
        <div class="form-group mr-2">
            <label for="search" class="sr-only">Buscar Produto:</label>
            <input type="text" class="form-control" id="search" name="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" placeholder="Buscar Categoria">
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
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) { ?>
                <tr>
                    <td><?php echo $row['id_categoria']; ?></td>
                    <td><?php echo $row['nome_categoria']; ?></td>
                    <td>
                        <a href="editar_categoria.php?id=<?php echo $row['id_categoria']; ?>" class="btn btn-warning">Editar</a>
                        <a href="categorias.php?delete_id=<?php echo $row['id_categoria']; ?>" class="btn btn-danger" onclick="return confirm('Deseja realmente excluir esta categoria?')">Excluir</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php
    } else {
        echo "<p>Nenhuma categoria encontrada.</p>";
    }
    ?>
    <a href="adicionar_categoria.php" class="btn btn-primary">Adicionar Categoria</a>
</div>

</body>
</html>
