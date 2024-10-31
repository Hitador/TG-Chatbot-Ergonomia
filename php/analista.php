<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
 
    <link rel="icon" href="../images/fevicon.png" type="image/gif"/>
    <title>Artigos Cadastrados</title>
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
    $filter_id = isset($_POST['id']) ? intval($_POST['id']) : null;

    // Adicionando o id à consulta
    if ($filter_id) {
        $query = "SELECT id, author, publish_date, preview_content, article_link, article_file FROM artigos WHERE id = $1";
        $result = pg_query_params($conn, $query, array($filter_id));
    } else {
        $query = "SELECT id, author, publish_date, preview_content, article_link, article_file FROM artigos";
        $result = pg_query($conn, $query);
    }
    
    if (!$result) {
        die("Erro na consulta: " . pg_last_error());
    }
 
    ?>
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
    </style>
</head>
<body>


    <!-- BANNER MENU ORGANIZADOR -->
    <?php include 'menuanalista.php'; ?>
    <!-- BANNER MENU ORGANIZADOR FINAL -->
     
    <?php include 'chatbot.php'; ?>
    

    <!-- RODAPÉ MENU -->
    <?php include 'footer.php'; ?>
    <!-- RODAPÉ MENU FINAL -->

</body>
</html>
