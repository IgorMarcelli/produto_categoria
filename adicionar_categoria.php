<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_categoria = strtoupper($_POST['nome_categoria']);

    $query = "INSERT INTO categorias (nome_categoria) VALUES (?)";
    $params = array($nome_categoria);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt === false) {
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
    <title>Adicionar Produto</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Adicionar Categoria</h2>
    <a href="categorias.php" class="btn btn-info mt-3">Voltar</a>
    <form method="post" action="">
        <div class="form-group">
            <label for="nome_categoria">Nome da Categoria:</label>
            <input type="text" class="form-control" id="nome_categoria" name="nome_categoria" required>
        </div>
        <button type="submit" class="btn btn-primary">Adicionar Categoria</button>
    </form>
</div>

</body>
</html>
