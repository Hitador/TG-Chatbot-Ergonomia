<?php
session_start(); // Inicia a sessão
include 'conectabd.php';

// Verifica se o formulário foi enviado e se o ID do artigo está presente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']); // Obtém o ID do artigo

    // Função para excluir o artigo
    function excluir_artigo($conn, $id) {
        $query = "DELETE FROM artigos WHERE id = $1"; // Certifique-se de usar o ID correto
        $result = pg_query_params($conn, $query, array($id));

        return $result;
    }

    // Excluindo o artigo
    if (excluir_artigo($conn, $id)) {
        echo "<script>alert('Artigo excluído com sucesso!'); window.location.href = '../listagem_artigos.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir o artigo.');</script>";
    }
} else {
    die("ID do artigo inválido.");
}

// Fechando a conexão com o banco de dados
pg_close($conn);
?>
