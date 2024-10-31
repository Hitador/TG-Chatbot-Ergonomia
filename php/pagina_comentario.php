<?php
// Conexão com o banco de dados
include 'conectabd.php';

// Recupera o ID do artigo
$artigo_id = isset($_GET['artigo_id']) ? intval($_GET['artigo_id']) : 0;

// Recupera os dados do artigo
$query_artigo = "SELECT author, publish_date, preview_content, article_link, article_file FROM artigos WHERE id = $artigo_id";
$result_artigo = pg_query($conn, $query_artigo);
$artigo = pg_fetch_assoc($result_artigo);

if (!$artigo) {
    die("Artigo não encontrado.");
}

// Recupera os comentários do artigo
$query_comentarios = "SELECT c.comentario, c.data_comentario, u.txnomeusuario AS nome 
                      FROM comentarios c 
                      JOIN usuario u ON c.cpfid = u.idcpfusuario 
                      WHERE c.artigo_id = $artigo_id 
                      ORDER BY c.data_comentario DESC";
$result_comentarios = pg_query($conn, $query_comentarios);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comentários do Artigo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f0f0f0; /* Fundo mais claro para contraste */
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        .artigo, .comentario {
            background-color: #ffffff;
            border: 2px solid #4CAF50; /* Borda verde */
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sombra suave */
        }

        

        h4 {
            color: #4CAF50;
        }

        .artigo p span {
            color: #4CAF50; /* Manter o texto de autoria e data na cor verde */
        }

        /* Estilo para a prévia do artigo */
        .preview {
            color: #333; /* Cor padrão para o texto da prévia */
        }

        .btn {
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            background-color: #45a049;
        }

        /* Estilo para o formulário de comentário */
        form {
            display: flex;
            flex-direction: column;
            margin-top: 20px;
        }

        textarea {
            height: 100px;
            border: 2px solid #4CAF50; /* Borda verde */
            border-radius: 5px;
            padding: 10px;
            resize: none; /* Desabilitar redimensionamento */
        }

        textarea:focus {
            border-color: #45a049; /* Mudança de cor ao focar */
        }

        .comentario {
            border: 1px solid #ccc; /* Borda leve para comentários */
        }

        .comentario p {
            margin: 5px 0; /* Margem entre as linhas */
        }

        .comentario strong {
            color: #4CAF50; /* Nome do usuário em verde */
        }

        /* Estilo para o botão de envio */
        button[type="submit"] {
            margin-top: 10px; /* Margem acima do botão */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Comentários para o Artigo</h1>

        <!-- Exibir informações do artigo -->
        <div class="artigo">
            <p><strong>Autor:</strong> <?php echo htmlspecialchars($artigo['author']); ?></p>
            <p><strong>Data de Publicação:</strong> <?php echo htmlspecialchars($artigo['publish_date']); ?></p>
            <p><strong>Prévia:</strong></p>
            <div class="preview">
                <?php echo html_entity_decode($artigo['preview_content']); ?>
            </div>
            <?php if (!empty($artigo['article_file'])) { ?>
                <br>
                <a class="btn" href="<?php echo htmlspecialchars($base_url . basename($artigo['article_file'])); ?>" download>Baixar Artigo</a>
            <?php } elseif (!empty($artigo['article_link'])) { ?>
                <a class="btn" href="<?php echo htmlspecialchars($artigo['article_link']); ?>" target="_blank">Ver Artigo</a>
            <?php } else { ?>
                <p>Arquivo ou link não disponível.</p>
            <?php } ?>
        </div>

        <!-- Formulário para adicionar um comentário -->
        <form action="adicionar_comentario.php" method="POST">
            <input type="hidden" name="artigo_id" value="<?php echo $artigo_id; ?>">
            <input type="hidden" name="cpfid" value="<?php echo $_SESSION['usuario_id']; ?>"> <!-- ID do usuário logado -->
            <textarea name="comentario" required placeholder="Digite seu comentário aqui..."></textarea>
            <button type="submit" class="btn">Enviar Comentário</button>
        </form>

        <!-- Exibir comentários -->
        <?php while ($comentario = pg_fetch_assoc($result_comentarios)) { ?>
            <div class="comentario">
                <p><strong><?php echo htmlspecialchars($comentario['nome']); ?></strong> - <?php echo htmlspecialchars($comentario['data_comentario']); ?></p>
                <p><?php echo nl2br(htmlspecialchars($comentario['comentario'])); ?></p>
            </div>
        <?php } ?>
    </div>
</body>
</html>
