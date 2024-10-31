<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="icon" href="../images/fevicon.png" type="image/gif"/>
    <style>
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
    
        /* Estilos gerais para o chatbot */
        #chatbot {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            width: 400px;
            background-color: white;
            border: 4px solid #2E8B57; /* Borda verde */
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            border-radius: 15px; /* Borda arredondada para todo o chatbot */
            display: none; /* Inicialmente, o chatbot estará oculto */
        }

        /* Cabeçalho ajustado do chatbot */
        #chatbot-header {
            background-color: #2E8B57;
            color: white;
            padding: 10px; /* Reduzido o tamanho do cabeçalho */
            font-size: 16px; /* Ajusta o texto */
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Botão de fechar com logo, ajustada largura maior que altura */
        #chatbot-header img {
            width: 100px; 
            height: 50px; 
            cursor: pointer;
        }

        /* Área de conteúdo do chat */
        #chat-content {
            padding: 15px;
            height: 300px;
            overflow-y: auto;
            background-color: #f9f9f9;
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
        }

        .chat-question {
            background-color: #2E8B57;
            color: white;
            padding: 10px;
            margin-bottom: 5px;
            border-radius: 5px;
            cursor: pointer;
        }

        .chat-response {
            background-color: #d5e8d4;
            color: #333;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .chat-question:hover {
            background-color: #267347;
        }

        /* Ícone do chatbot com balão de mensagem */
        #chatbot-icon-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            background-color: #2E8B57;
            color: white;
            padding: 15px;
            border-radius: 25px;
            display: flex;
            align-items: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            cursor: pointer;
        }

        #chatbot-icon-container img {
            width: 100px; 
            height: 50px; 
            margin-right: 10px;
        }

        #chatbot-icon-container p {
            margin: 0;
            font-size: 16px;
        }
    </style>
</head>
<body>

    <!-- CONEXAO E BUSCA NO BD-->
    <?php
    session_start();
    
    include 'conectabd.php';

    // Recuperar o CPF do usuário logado da sessão
    $cpf = isset($_SESSION['idcpfusuario']) ? $_SESSION['idcpfusuario'] : null;

    if ($cpf) {
        // Consulta SQL para obter o nome do usuário com base no CPF
        $queryNome = "SELECT txnomeusuario FROM usuario WHERE idcpfusuario = $1";
        $resultNome = pg_query_params($conn, $queryNome, array($cpf));

        if ($resultNome && pg_num_rows($resultNome) > 0) {
            $rowNome = pg_fetch_assoc($resultNome);
            $nomeUsuario = $rowNome['txnomeusuario'];
        } else {
            $nomeUsuario = 'Usuário'; // Valor padrão caso o nome não seja encontrado
        }
    } else {
        $nomeUsuario = 'Usuário'; // Valor padrão se não houver CPF na sessão
    }

 
    ?>

    <!-- BANNER MENU CLIENTE -->
    <?php include 'menucliente.php'; ?>
    <!-- BANNER MENU CLIENTE FINAL -->

 
  
    <?php include 'chatbot.php'; ?>
 

</body>
</html>
