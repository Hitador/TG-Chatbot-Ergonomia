<?php
session_start(); // Inicia a sessão para acessar os dados do usuário logado

include 'conectabd.php'

// Verifica se o usuário está logado
if (!isset($_SESSION['idcpfusuario'])) {
    die("Usuário não logado.");
}

// Obtem os dados do formulário
$artigo_id = intval($_POST['artigo_id']);
$cpf = $_SESSION['idcpfusuario']; // Use o CPF do usuário da sessão
$comentario = $_POST['comentario'];

// Inserir o comentário no banco de dados
$query_insert = "INSERT INTO comentarios (artigo_id, cpfid, comentario, data_comentario) VALUES ($1, $2, $3, date_trunc('second', NOW()))";
$result_insert = pg_query_params($conn, $query_insert, array($artigo_id, $cpf, $comentario));

if ($result_insert) {
    header("Location: pagina_comentario.php?artigo_id=$artigo_id"); // Redireciona para a página de comentários
    exit();
} else {
    echo "Erro ao adicionar comentário: " . pg_last_error($conn);
}
?>
