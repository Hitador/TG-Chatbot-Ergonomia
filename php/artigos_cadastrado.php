<!DOCTYPE html>
<html lang="pt-br">
<head>
<style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        .article {
            background-color: #f9f9f9;
            border: 2px solid #4CAF50;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        h4 {
            color: #4CAF50;
        }

        .article p span {
            color: #4CAF50;
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
    </style>
    <meta charset="UTF-8">
 
    <link rel="icon" href="../images/fevicon.png" type="image/gif"/>
    <title>Artigos Cadastrados</title>
<?php
include 'conectabd.php';

$filter_id = isset($_POST['id']) ? intval($_POST['id']) : null;

// Adicionando o id à consulta
if ($filter_id) {
    $query = "SELECT id, author, publish_date, preview_content, article_link, article_file FROM artigos WHERE id = $1";
    $result = pg_query_params($conn, $query, array($filter_id));
} else {
    $query = "SELECT id, author, publish_date, preview_content, article_link, article_file FROM artigos";
    $result = pg_query($conn, $query);
}

if (!$result) {
    die("Erro na consulta: " . pg_last_error());
}
?>

</head>
<body>


<div class="container">
    <h1 class="fashion_taital">Artigos Cadastrados</h1>

    <?php
    $base_url = 'http://localhost/Prevnergo1.0/uploads/';

    if (pg_num_rows($result) > 0) {
        while ($row = pg_fetch_assoc($result)) {
            $artigo_id = $row['id']; // Armazena o id do artigo
            ?>
            <div class="article">
                <p><span>Autor: <?php echo htmlspecialchars($row['author']); ?></span></p>
                <p><span>Data de Publicação:</span> <?php echo htmlspecialchars($row['publish_date']); ?></p>
                <p><span>Prévia:</span></p>
                <div class="preview">
                    <?php echo html_entity_decode($row['preview_content']); ?>
                </div>

                <!-- Botão de feedback -->
                <a href="pagina_comentario.php?artigo_id=<?php echo $artigo_id; ?>" class="btn">Feedback / Comentários</a>

                <?php if (!empty($row['article_file'])) { ?>
                    <br>
                    <a class="btn" href="<?php echo htmlspecialchars($base_url . basename($row['article_file'])); ?>" download>Baixar Artigo</a>
                <?php } elseif (!empty($row['article_link'])) { ?>
                    <a class="btn" href="<?php echo htmlspecialchars($row['article_link']); ?>" target="_blank">Ver Artigo</a>
                <?php } else { ?>
                    <p>Arquivo ou link não disponível.</p>
                <?php } ?>
            </div>
            <?php
        }
    } else {
        ?>
        <div class="col-lg-12 col-sm-12">
            <p>Nenhum artigo encontrado.</p>
        </div>
        <?php
    }
    ?>
</div>
<!-- SEÇÃO ARTIGOS CADASTRADOS FINAL -->
</body>
</html>