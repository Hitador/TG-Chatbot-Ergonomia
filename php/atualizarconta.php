<?php
include 'conectabd.php';

// Dados do usuário
$cpf = $nome = $email = $senha = "";

// Recuperar o CPF do usuário logado da sessão
session_start();
if(isset($_SESSION['idcpfusuario'])) {
    $cpf = $_SESSION['idcpfusuario'];

    // Consulta no banco de dados
    $query = "SELECT idcpf, txnomeusuario, txemailusuario, senha FROM usuario WHERE idcpf = '$cpf'";

    $result = pg_query($conn, $query);

    if (!$result) {
        echo "Ocorreu um erro na consulta.\n";
        exit;
    }

    // Verifica se encontrou algum resultado
    if (pg_num_rows($result) > 0) {
        // Exibe os dados do usuário
        $row = pg_fetch_assoc($result);
        $nome = $row['txnomeusuario'];
        $email = $row['txemailusuario'];
        $senha = $row['senha'];
    } else {
        echo "Erro ao carregar dados do usuário.";
        exit;
    }
} else {
    echo "Usuário não está logado.";
    exit;
}
?>

<!-- Código HTML abaixo -->


<!DOCTYPE html>
<html lang="pt-br">

    <head>
	    <meta charset="utf-8">
        <meta http-equiv="content-language" content="pt-br" />      

        <!-- LINKS DOCS -->
	    <link rel="stylesheet" type="text/css" href="../css/reset.css">
	    <link rel="stylesheet" type="text/css" href="../css/cadastro.css">
        <link rel="stylesheet" type="text/css" href="../css/fonts-icones.css">
        <link rel="stylesheet" type="text/css" href="../css/style.css">
        <link rel="icon" href="../images/fevicon.png" type="image/gif"/>

	    <title>TicketFy</title>

        

    </head>
    <body>
        <header class="main_header container">        
            <div class="content">
                <div class="main_header_logo">
                    <img src="../images/logocad.png" alt="logo.png"/>
                </div>
            </div>
        </header>

        <main class="main_content container">
        
            <section class="section-seu-codigo container">
                <div class="content">          
                    <div class="box-artigo">
                    <!-- FORM COMEÇO -->
                        <div class="container_form">
                            <h1>Atualizar Dados da Conta</h1>
                                <form class="form" action="../php/updateusuariobd.php" method="post"> <!-- Não Esquecer Caminho do Arquivo .php -->

                                    <div class="form_grupo">
                                        <label for="cpf" class="form_label">CPF do Usuario</label><br>
                                        <?php echo $cpf; ?>
                                      <input type="hidden" name="cpf" class="form_input" id="cpf" placeholder="Somente números" required value="<?php echo $cpf; ?>">
                                    </div>

                                    <div class="form_grupo">
                                        <label for="nome" class="form_label">Novo Nome</label>
                                        <input type="text" name="txnomeusuario" class="form_input" id="txnomeusuario" placeholder="Nome" required  value="<?php echo $nome; ?>">
                                    </div>
                                    
                                    <div class="form_grupo">
                                        <label for="e-mail" class="form_label">Novo Email</label>
                                        <input type="email" name="txemailusuario" class="form_input" id="txemailusuario" placeholder="seuemail@email.com" required  value="<?php echo $email; ?>">
                                    </div>
                                    <div class="form_grupo">
                                        <label for="senha" class="form_label">Nova Senha</label>
                                        <input type="password" name="senha" class="form_input" id="senha" placeholder="Senha" required value="<?php echo $senha; ?>">
                                       
                                    </div>
                                    <div class="submit">
                                        <input type="hidden" name="acao" value="enviar">
                                        <button type="submit" name="submit" class="submit_btn" >Alterar</button>
                                    </div>
                                </div> 
                                    
                                </form>
                        </div>
                    <!-- FORM FINAL -->

                    </div>
                    <div class="clear"></div>
                </div>
            </section>
        </main>
                    
    </body>
</html>
?>