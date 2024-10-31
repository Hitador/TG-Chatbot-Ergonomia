<?php
session_start(); // Inicialização da sessão

include 'conectabd.php';

// Recuperando o Usuário, Senha e Tipo Digitado
$usuario_digitado = $_POST['cpf']; 
$senha_usuario = $_POST['senha'];

// Consulta SQL para Verificar se o Usuário Existe e Obter seu Tipo
$query = "SELECT * FROM usuario WHERE idcpfusuario = '$usuario_digitado'";
$result = pg_query($conn, $query);
if (!$result) {
    die("Erro ao executar a consulta: " . pg_last_error());
}

// Verificando se o Usuário Existe
if (pg_num_rows($result) > 0) {
    $row = pg_fetch_assoc($result);
    $tipo_usuario = $row['aotipo'];

    // Verificar se a Senha está Correta
    if ($row['senha'] === $senha_usuario) {
        // Se a senha estiver correta, definir a variável de sessão para o CPF do usuário
        $_SESSION['idcpfusuario'] = $usuario_digitado;

        if ($tipo_usuario == "A") {
            echo "<script>alert('Login efetuado com sucesso!');</script>";
            echo "<script>window.location.href = '../php/analista.php';</script>";
        } else {
            echo "<script>alert('Login efetuado com sucesso!');</script>";
            echo "<script>window.location.href = '../php/cliente.php';</script>";
        }
    } else {
        echo "<script>alert('Senha incorreta!');</script>";
        echo "<script>window.location.href = '../html/entrar.html';</script>";
    }
} else {
    echo "<script>alert('Usuário não encontrado!');</script>";
    echo "<script>window.location.href = '../html/entrar.html';</script>";
}

// Fechando a Conexão com o Banco de Dados
pg_close($conn);
?>
