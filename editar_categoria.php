<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_categoria = $_POST['id_categoria'];
    $nome_categoria = strtoupper($_POST['nome_categoria']);

    $query = "UPDATE categorias SET nome_categoria = ? WHERE id_categoria = ?";
    $params = array($nome_categoria, $id_categoria);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    header("Location: categorias.php");
}

if (isset($_GET['id'])) {
    $id_categoria = $_GET['id'];
    $select_query = "SELECT * FROM categorias WHERE id_categoria = ?";
    $params = array($id_categoria);
    $select_stmt = sqlsrv_query($conn, $select_query, $params);
    $categoria = sqlsrv_fetch_array($select_stmt, SQLSRV_FETCH_ASSOC);
} else {
    header("Location: categorias.php");
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
    <h2>Editar Categoria</h2>
    <form method="post" action="">
        <div class="form-group">
            <label for="nome_categoria">Nome da Categoria:</label>
            <input type="text" class="form-control" id="nome_categoria" name="nome_categoria" value="<?php echo $categoria['nome_categoria']; ?>" required>
        </div>
        <input type="hidden" name="id_categoria" value="<?php echo $categoria['id_categoria']; ?>">
        <button type="submit" class="btn btn-primary">Salvar Edição</button>
    </form>
    <a href="categorias.php" class="btn btn-info mt-3">Voltar</a>
</div>

</body>
</html>
