<?php

include 'conectabd.php';

// Função para atualizar dados do usuário
function atualizarUsuario($conn) {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])){
        // Recuperando os dados do formulário
        $cpf = pg_escape_string($_POST['cpf']);
        $nome = pg_escape_string($_POST['txnomeusuario']);
        $email = pg_escape_string($_POST['txemailusuario']);
        $senha = pg_escape_string($_POST['senha']);

        // Preparando a query SQL para atualização
        $query = "UPDATE usuario SET txnomeusuario = '$nome', txemailusuario = '$email', senha = '$senha' WHERE idcpf = '$cpf'";

        // Executando a query
        $result = pg_query($conn, $query);
        if (!$result) {
            die("Erro ao executar a query: " . pg_last_error());
        }

        // Verificando se a atualização foi bem-sucedida
        if (pg_affected_rows($result) > 0) {
            echo "<script> alert('Dados do usuário atualizados com sucesso!')  </script>";
            echo "<script> window.history.go(-2); </script>";
        } else {
            echo "<script> alert('Erro ao atualizar dados do usuário!'); </script>";
            echo "<script> window.history.go(-1); </script>";
        }
    }
}

// Verifica se o formulário de atualização de usuário foi submetido
atualizarUsuario($conn);

// Fechando a Conexão com o Banco de Dados
pg_close($conn);
?>
