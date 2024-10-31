<?php
session_start(); // Inicialização da sessão

include 'conexãobd.php';

// Função para inserir dados do usuário
function inserirUsuario($conn) {
    // Recuperando os dados do formulário
    $cpf = $_POST['idcpfusuario'];
    $nome = $_POST['txnomeusuario'];
    $email = $_POST['txemailusuario'];
    $tipo = $_POST['aotipo'];
    $senha = $_POST['senha'];

    // Preparando a query SQL para inserção
    $query = "INSERT INTO usuario (idcpf, txnomeusuario, txemailusuario, aotipo, senha) VALUES ('$cpf', '$nome', '$email', '$tipo', '$senha')";

    // Executando a query
    $result = pg_query($conn, $query);
    if (!$result) {
        die("Erro ao executar a query: " . pg_last_error());
    }

    // Verificando se a inserção foi bem-sucedida
    if (pg_affected_rows($result) > 0) {
        echo "<script> alert('Usuario cadastrado com sucesso!'); </script>";
        echo "<script> window.location.href = '../html/entrar.html'; </script>";
    } else {
        echo "<script> alert('Erro ao cadastrar usuário!'); </script>";
        echo "<script> window.location.href = '../html/cadastro.html'; </script>";
    }
}

// Verifica se o formulário de cadastro foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_usuario'])) {
    // Exibir os dados recebidos do formulário
    var_dump($_POST);
    
    inserirUsuario($conn);
}

// Fechando a Conexão com o Banco de Dados
pg_close($conn);
?>