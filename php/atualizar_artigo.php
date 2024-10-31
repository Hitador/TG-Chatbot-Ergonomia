<?php
session_start(); // Inicia a sessão

include 'conectabd.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $author = $_POST['author'];
    $publishDate = $_POST['publishDate'];
    $previewContent = $_POST['previewContent'];
    $articleLink = isset($_POST['articleLink']) ? $_POST['articleLink'] : null;
    $articleFile = null;

    // Verifica se um novo arquivo foi enviado
    if (isset($_FILES['articleFile']) && $_FILES['articleFile']['error'] == 0) {
        $fileTmpPath = $_FILES['articleFile']['tmp_name'];
        $fileName = $_FILES['articleFile']['name'];
        $uploadDir = __DIR__ . '/../uploads/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $destinationPath = $uploadDir . $fileName;

        if (move_uploaded_file($fileTmpPath, $destinationPath)) {
            $articleFile = $destinationPath;  // Salva o caminho do arquivo no banco de dados
        } else {
            echo "<script>alert('Erro ao carregar o arquivo.');</script>";
        }
    }

    // Atualiza os dados do artigo no banco de dados
    if ($articleFile) {
        $query = "UPDATE artigos SET author = $1, publish_date = $2, preview_content = $3, article_link = $4, article_file = $5 WHERE id = $6";
        $params = array($author, $publishDate, $previewContent, $articleLink, $articleFile, $id);
    } else {
        $query = "UPDATE artigos SET author = $1, publish_date = $2, preview_content = $3, article_link = $4 WHERE id = $5";
        $params = array($author, $publishDate, $previewContent, $articleLink, $id);
    }

    $result = pg_query_params($conn, $query, $params);

    if ($result) {
        echo "<script>alert('Artigo atualizado com sucesso!'); window.location.href = '../listagem_artigos.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar o artigo.');</script>";
    }
} else {
    die("Dados inválidos.");
}

// Fechando a conexão com o banco de dados
pg_close($conn);
?>
