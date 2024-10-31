<?php
include 'conectabd.php';

// Verificando se o formulário foi enviado
if (isset($_POST['submit'])) {
    // Recuperando os dados do formulário e sanitizando-os
    $email = pg_escape_string($conn, $_POST['txemailusuario']);
    $cpf = pg_escape_string($conn, $_POST['cpf']);
    $novaSenha = pg_escape_string($conn, $_POST['senha']); // Nova senha

    // Verificando se o e-mail existe no banco de dados
    $sql_verificar_email = "SELECT * FROM usuario WHERE idcpf = '$cpf' AND txemailusuario = '$email'";
    $result_verificar_email = pg_query($conn, $sql_verificar_email);
    
    if (!$result_verificar_email) {
        echo "<script> alert('Erro ao verificar o e-mail: " . pg_last_error() . "'); </script>";
    } else {
        if (pg_num_rows($result_verificar_email) > 0) {
            // E-mail encontrado, atualiza a senha
            $sql_atualizar_senha = "UPDATE usuario SET senha = '$novaSenha' WHERE idcpf = '$cpf' AND txemailusuario = '$email'";
            $result_atualizar_senha = pg_query($conn, $sql_atualizar_senha);
            if (!$result_atualizar_senha) {
                echo "<script> alert('Erro ao atualizar a senha: " . pg_last_error() . "'); </script>";
            } else {
                echo "<script> alert('Senha atualizada com sucesso.'); </script>";
                echo "<script> window.location.href = '../html/entrar.html'; </script>";
            }
        } else {
            // E-mail não encontrado
            echo "<script> alert('E-mail não encontrado no banco de dados.'); </script>";
        }
    }
}

// Fechando a conexão com o banco de dados
pg_close($conn);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="content-language" content="pt-br" />

    <!-- LINKS DOCS -->
    <link rel="stylesheet" type="text/css" href="../css/reset.css">
    <link rel="stylesheet" type="text/css" href="../css/cadastro.css">
    <link rel="stylesheet" type="text/css" href="../css/fonts-icones.css">
    <link rel="icon" href="../images/fevicon.png" type="image/gif" />

    <title>TicketFy</title>
</head>

<body>

    <header class="main_header container">
        <div class="content">
            <div class="main_header_logo">
                <img src="../images/logocad.png" alt="logo.png" />
            </div>
        </div>
    </header>

    <main class="main_content container">

        <section class="section-seu-codigo container">
            <div class="content">
                <div class="box-artigo">
                    <!-- FORM COMEÇO -->
                    <div class="container_form">
                        <h1>Formulário de troca de senha</h1>
                        <form class="form" action="" method="post" onsubmit="return validateForm()">

                            <div class="form_grupo">
                                <label for="cpf" class="form_label">CPF</label>
                                <input type="text" name="cpf" class="form_input" id="cpf" placeholder="Somente números"required oninput="formatCPF(this)">
                            </div>

                            <div class="form_grupo">
                                <label for="e-mail" class="form_label">Email</label>
                                <input type="email" name="txemailusuario" class="form_input" id="txemailusuario"
                                    placeholder="seuemail@email.com" required>
                            </div>

                            <div class="form_grupo">
                                <label for="senha" class="form_label">Nova Senha</label>
                                <input type="password" name="senha" class="form_input" id="senha" placeholder="Senha"
                                    required>
                            </div>

                            <div class="form_grupo">
                                <label for="confirmacaosenha" class="form_label">Digite novamente a senha</label>
                                <input type="password" name="confirmacaosenha" class="form_input" id="confirmacaosenha" placeholder="Senha"
                                    required>
                            </div>

                            <div class="form_grupo">
                                <span id="error-message" style="color:red;"></span>
                            </div>

                            <div class="submit">
                                <button type="submit" name="submit" class="submit_btn">Finalizar</button>
                            </div>
                        </form>
                    </div>
                    <!-- FORM FINAL -->

                </div>
                <div class="clear"></div>
            </div>
        </section>
    </main>

    <script>
        function formatCPF(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.length > 11) {
                value = value.slice(0, 11);
            }

            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

            input.value = value;
        }

        function removeCPFFormatting() {
            const cpfInput = document.getElementById('cpf');
            cpfInput.value = cpfInput.value.replace(/\D/g, '');
        }

        function validateForm() {
            const senha = document.getElementById('senha').value;
            const confirmacaosenha = document.getElementById('confirmacaosenha').value;
            const errorMessage = document.getElementById('error-message');

            if (senha !== confirmacaosenha) {
                errorMessage.textContent = 'As senhas não coincidem.';
                return false;
            }

            // Senhas coincidem, então remove a formatação do CPF
            removeCPFFormatting();
            errorMessage.textContent = '';
            return true;
        }
    </script>   

</body>

</html>
