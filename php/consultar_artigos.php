<?php
// consultar_artigos.php
include 'conectabd.php';

$query = "SELECT id, author, publish_date, preview_content, article_link, article_file FROM artigos";
$result = pg_query($conn, $query);

if (!$result) {
    die("Erro na consulta: " . pg_last_error());
}

// Retorna os resultados para uso posterior
return $result;
?>
s