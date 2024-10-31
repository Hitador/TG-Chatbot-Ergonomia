<?php
session_start();

include 'conectabd.php';

// Função para excluir a conta do usuário
function excluirConta($conn, $cpf, $senha) {
    // Verifica se o CPF fornecido no formulário corresponde ao CPF do usuário autenticado
    $cpfAutenticado = $_SESSION['idcpfusuario'];
    if ($cpf != $cpfAutenticado) {
        echo "<script> alert('Erro: CPF fornecido não corresponde ao CPF do usuário autenticado.'); </script>";
        echo "<script> window.location.href = '../html/deletarconta.html'; </script>";
    }

    // Verifica se a senha fornecida corresponde à senha do usuário no banco de dados
    $query = "SELECT * FROM usuario WHERE idcpfusuario = '$cpf' AND senha = '$senha'";
    $result = pg_query($conn, $query);
    if (!$result) {
        die("Erro ao executar a consulta: " . pg_last_error());
    }

    // Verifica se a consulta retornou um resultado
    if (pg_num_rows($result) == 1) {
        // Preparando a query SQL para exclusão da conta
        $query_delete = "DELETE FROM usuario WHERE idcpfusuario = '$cpf'";

        // Executando a query
        $result_delete = pg_query($conn, $query_delete);
        if (!$result_delete) {
            die("Erro ao excluir a conta: " . pg_last_error());
        }

        // Verificando se a exclusão foi bem-sucedida
        if (pg_affected_rows($result_delete) > 0) {
            echo "<script> alert('Conta excluída com sucesso!'); </script>";
            echo "<script> window.location.href = '../php/index.php'; </script>";
        } else {
            echo "<script> alert('Erro ao excluir conta!'); </script>";
            echo "<script> window.location.href = '../html/deletarconta.html'; </script>";
        }
    } else {
        // Senha incorreta
        echo "<script> alert('Senha incorreta!'); </script>";
        echo "<script> window.location.href = '../html/deletarconta.html'; </script>";
    }
}




// Verifica se o usuário está logado
if (!isset($_SESSION['idcpfusuario'])) {
    die("Erro: Usuário não está logado.");
}

// Verifica se o formulário de exclusão de conta foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Recupera o CPF do usuário autenticado
    $cpfAutenticado = $_SESSION['idcpfusuario'];
    // Recupera o CPF e a senha fornecidos pelo formulário
    $cpf = $_POST['idcpfusuario']; // Supondo que o CPF seja enviado pelo formulário
    $senha = $_POST['senha']; // Supondo que a senha seja enviada pelo formulário
    excluirConta($conn, $cpf, $senha);
}



// Fechando a Conexão com o Banco de Dados
pg_close($conn);
?>