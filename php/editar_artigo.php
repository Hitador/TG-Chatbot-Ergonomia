<?php
session_start(); // Inicia a sessão

include 'conectabd.php';
// Verifica se o ID do artigo foi passado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']); // Obtém o ID do artigo
} else {
    die("ID do artigo inválido.");
}

// Função para obter os detalhes do artigo
function obter_artigo($conn, $id) {
    $query = "SELECT * FROM artigos WHERE id = $1"; // Certifique-se de usar o ID correto
    $result = pg_query_params($conn, $query, array($id));

    if (!$result) {
        die("Erro na consulta do artigo: " . pg_last_error($conn));
    }

    return pg_fetch_assoc($result);
}

// Obtém os detalhes do artigo
$artigo = obter_artigo($conn, $id);

// Verifica se o artigo foi encontrado
if (!$artigo) {
    die("Artigo não encontrado.");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Artigo</title>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f4; }
        form { background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); max-width: 600px; margin: auto; }
        label { font-weight: bold; }
        input, #editor-container { width: 100%; padding: 10px; margin-top: 5px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 4px; }
        button { background-color: #2E8B57; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #45a049; }
        #editor-container { height: 200px; background-color: white; }
    </style>
</head>
<body>

<center><h1>Editar Artigo</h1></center>

<form action="../php/atualizar_artigo.php" method="POST" enctype="multipart/form-data" onsubmit="handleSubmit(event)">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($artigo['id']); ?>"> <!-- ID do artigo -->
    <label for="author">Nome do Autor:</label><br>
    <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($artigo['author']); ?>" required><br><br>

    <label for="publishDate">Data de Publicação:</label><br>
    <input type="date" id="publishDate" name="publishDate" value="<?php echo htmlspecialchars($artigo['publish_date']); ?>" required><br><br>

    <label for="previewContent">Prévia do Artigo:</label><br>
    <div id="editor-container"><?php echo $artigo['preview_content']; ?></div> <!-- Mantendo a prévia com as tags HTML -->
    <textarea id="previewContent" name="previewContent" style="display:none;"></textarea><br><br>

    <label for="articleLink">Link do Artigo (opcional):</label><br>
    <input type="url" id="articleLink" name="articleLink" value="<?php echo htmlspecialchars($artigo['article_link']); ?>"><br><br>

    <label for="articleFile">Anexar Documento do Artigo (PDF ou DOC):</label><br>
    <input type="file" id="articleFile" name="articleFile" accept=".pdf,.doc,.docx"><br><br>

    <?php if (!empty($artigo['article_file'])): ?>
        <p>Arquivo atual: <strong><?php echo htmlspecialchars(basename($artigo['article_file'])); ?></strong></p>
    <?php endif; ?>

    <button type="submit">Atualizar Artigo</button>
</form>

<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    var quill = new Quill('#editor-container', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, false] }],
                ['bold', 'italic', 'underline'],
                ['link', 'blockquote', 'code-block'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }]
            ]
        }
    });

    // Carrega o conteúdo da prévia no editor mantendo a formatação
    quill.root.innerHTML = `<?php echo addslashes($artigo['preview_content']); ?>`;

    function handleSubmit(event) {
        event.preventDefault();
        var previewContent = document.querySelector('textarea[name=previewContent]');
        previewContent.value = quill.root.innerHTML;
        event.target.submit();
    }
</script>

</body>
</html>

<?php
pg_close($conn);
?>
