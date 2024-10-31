<?php
// Conexão com o banco de dados PostgreSQL
include 'conectabd.php';

// Função para inserir dados do artigo no banco de dados
function inserir_artigo($conn, $author, $publishDate, $previewContent, $articleLink, $articleFile, $idcpfusuario) {
    // Query de inserção no banco de dados
    $query = "INSERT INTO artigos (author, publish_date, preview_content, article_link, article_file, idcpfusuario) 
              VALUES ($1, $2, $3, $4, $5, $6)";

    // Executando a query
    $result = pg_query_params($conn, $query, array($author, $publishDate, $previewContent, $articleLink, $articleFile, $idcpfusuario));

    return $result; // Retorne o resultado da inserção
}

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta os dados do formulário
    $author = $_POST['author'];
    $publishDate = $_POST['publishDate'];
    $previewContent = $_POST['previewContent'];
    $articleLink = isset($_POST['articleLink']) ? $_POST['articleLink'] : null;
    $articleFile = null;

    // Aqui você deve obter o idcpfusuario. Por exemplo, se ele está na sessão:
    session_start(); // Certifique-se de iniciar a sessão
    $idcpfusuario = $_SESSION['idcpfusuario']; // Altere isso de acordo com sua implementação

    // Verifica se um arquivo foi enviado
    if (isset($_FILES['articleFile']) && $_FILES['articleFile']['error'] == 0) {
        // Caminho temporário do arquivo e nome do arquivo
        $fileTmpPath = $_FILES['articleFile']['tmp_name'];
        $fileName = $_FILES['articleFile']['name'];

        // Certifica-se de que o diretório de destino existe
        $uploadDir = __DIR__ . '/../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Cria o diretório se não existir
        }

        // Caminho de destino para salvar o arquivo
        $destinationPath = $uploadDir . $fileName;

        // Movendo o arquivo para o diretório de uploads
        if (move_uploaded_file($fileTmpPath, $destinationPath)) {
            $articleFile = $destinationPath;  // Salvando o caminho do arquivo no banco de dados
        } else {
            echo "<script>alert('Erro ao carregar o arquivo.');</script>";
        }
    }

    // Inserindo o artigo no banco de dados
    if (inserir_artigo($conn, $author, $publishDate, $previewContent, $articleLink, $articleFile, $idcpfusuario)) {
        // Redireciona para a página analista.php
        header("Location: analista.php");
        exit(); // Encerra o script após o redirecionamento
    } else {
        echo "<script>alert('Erro ao salvar a prévia do artigo.');</script>";
    }

    // Fechando a conexão com o banco de dados
    pg_close($conn);
}
?>
