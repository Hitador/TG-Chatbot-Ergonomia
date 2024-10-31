<?php

/*
    Objetivo: Essa função busca a resposta de uma pergunta feita pelo usuário ao chatbot, utilizando uma consulta ao banco de dados.
    Conexão com o banco: A variável global $conn contém a conexão com o banco de dados PostgreSQL.
    Consulta: Usa a função pg_query_params para prevenir SQL Injection, buscando a resposta de uma tabela faq onde a pergunta é similar ao parâmetro $question.

    Resultado: Se a consulta retornar uma linha, a resposta é extraída e retornada. Caso contrário, uma mensagem padrão é enviada.
*/


    // Função para obter a resposta com base na pergunta
    function getResponse($question) {

        global $conn;
        $query = "SELECT resposta FROM faq WHERE pergunta ILIKE $1";
        $result = pg_query_params($conn, $query, array($question));

            if (pg_num_rows($result) > 0) {
                $row = pg_fetch_assoc($result);
                return $row['resposta'];
            } else {
                return 'Desculpe, não entendi sua pergunta. Pode reformular?';
                }

    }

// Objetivo: Busca sugestões de perguntas relacionadas à pergunta feita pelo usuário.
/*Etapas:
- Obtém o ID da pergunta principal.
- Com o ID em mãos, realiza uma nova consulta para buscar as perguntas relacionadas a essa pergunta.
- As sugestões são coletadas e retornadas como um array. Caso não encontre nada, retorna um array vazio.
*/    

    // Função para obter sugestões de perguntas relacionadas
    function getSuggestions($question) {

        global $conn;
        // Busca o ID da pergunta principal
        $query = "SELECT id FROM faq WHERE pergunta ILIKE $1";
        $result = pg_query_params($conn, $query, array($question));

            if (pg_num_rows($result) > 0) {
                $row = pg_fetch_assoc($result);
                $pergunta_id = $row['id'];

                // Busca as perguntas relacionadas
                $querySugestoes = "
                    SELECT f.pergunta 
                    FROM faq_sugestoes fs 

                    JOIN faq f ON fs.pergunta_relacionada_id = f.id 
                    WHERE fs.pergunta_id = $1";
                $resultSugestoes = pg_query_params($conn, $querySugestoes, array($pergunta_id));

                $sugestoes = [];
                    if (pg_num_rows($resultSugestoes) > 0) {
                        while ($rowSugestao = pg_fetch_assoc($resultSugestoes)) {
                            $sugestoes[] = $rowSugestao['pergunta'];
                        }
                    }
                return $sugestoes;
                    } else {
                        return [];
            }
    }
    ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="icon" href="../images/fevicon.png" type="image/gif"/>
    <style>
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
      <!-- Ícone do Chatbot com balão de mensagem -->
   <div id="chatbot-icon-container" onclick="openChat()">
        <img src="../images/logobranca.png" alt="Chatbot">
        <p>Olá, Sou o Preveni. Posso te ajudar?</p>
    </div>

    <!-- Janela do Chatbot -->
    <div id="chatbot">
        <div id="chatbot-header">
            <span>Chatbot PREVNERGO</span>
            <img src="../images/logobranca.png" alt="Fechar" onclick="closeChat()">
        </div>
        <div id="chat-content">
            <p><strong>Prevnergo:</strong> Olá, <?php echo $nomeUsuario; ?>! Como posso te ajudar hoje? Escolha uma categoria abaixo:</p>

            <!-- Categorias de perguntas -->
            <div class="chat-question" onclick="sendQuestion('ergonomia')">Ergonomia</div>
            <div class="chat-question" onclick="sendQuestion('postura')">Melhorar Postura</div>
            <div class="chat-question" onclick="sendQuestion('beneficios')">Benefícios da Ergonomia</div>
        </div>
    </div>

    <script>
           function openChat() {
            document.getElementById('chatbot').style.display = 'block'; // Mostra a janela do chatbot
            document.getElementById('chatbot-icon-container').style.display = 'none'; // Esconde o ícone
        }

        function closeChat() {
            document.getElementById('chatbot').style.display = 'none'; // Esconde a janela do chatbot
            document.getElementById('chatbot-icon-container').style.display = 'flex'; // Mostra o ícone novamente
        }

/*Objetivo: Envia a pergunta selecionada pelo usuário para o backend e recebe a resposta.
Etapas:
Adiciona a pergunta na interface do chat (frontend).
Faz uma requisição POST para get_response.php, enviando a pergunta.
Exibe a resposta retornada no chat. Se houver sugestões de perguntas relacionadas, elas são exibidas abaixo. */


        // Função para enviar a pergunta selecionada
        function sendQuestion(question) {
            const chatContent = document.getElementById('chat-content');

            // Adiciona a pergunta na interface com o nome do usuário
            chatContent.innerHTML += `<p class="chat-response"><strong><?php echo $nomeUsuario; ?>:</strong> ${question}</p>`;

            // Envia a pergunta para o backend (get_response.php)
            fetch('get_response.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    'user_question': question
                })
            })
            .then(response => response.json())
            .then(data => {
                // Exibe a resposta do bot logo abaixo da pergunta
                chatContent.innerHTML += `<p class="chat-response"><strong>Prevnergo:</strong> ${data.response}</p>`;

                // Se houver sugestões de perguntas relacionadas, exiba abaixo da resposta
                if (data.suggestions && data.suggestions.length > 0) {
                    chatContent.innerHTML += `<p class="chat-response">Perguntas relacionadas:</p>`;
                    data.suggestions.forEach(function(suggestion) {
                        chatContent.innerHTML += `<div class="chat-question" onclick="sendQuestion('${suggestion}')">${suggestion}</div>`;
                    });
                }

                // Botão "Listar Categorias Iniciais"
                chatContent.innerHTML += `
                    <div class="chat-question" onclick="goBack()">Listar Categorias Iniciais</div>
                    <div class="chat-question" onclick="resetChat()">Limpar Conversa</div>
                `;

                chatContent.scrollTop = chatContent.scrollHeight;
            })
            .catch(error => {
                console.error('Erro:', error);
            });
        }
/**goBack(): Retorna às categorias iniciais de perguntas sem limpar o histórico de conversas.
resetChat(): Limpa todo o conteúdo da conversa e reinicia o chat com as opções de categorias iniciais. */
        function goBack() {
    const chatContent = document.getElementById('chat-content');
    
    // Adiciona as categorias iniciais e o botão de limpar conversa abaixo das interações anteriores
    chatContent.innerHTML += `
        <p><strong>Prevnergo:</strong> Posso te ajudar com algo mais? Aqui estão as categorias iniciais novamente:</p>
        <div class="chat-question" onclick="sendQuestion('ergonomia')">Ergonomia</div>
        <div class="chat-question" onclick="sendQuestion('postura')">Melhorar Postura</div>
        <div class="chat-question" onclick="sendQuestion('beneficios')">Benefícios da Ergonomia</div>
        <div class="chat-question" onclick="resetChat()">Limpar Conversa</div>
    `;
    
    // Rolagem automática para o final da conversa
    chatContent.scrollTop = chatContent.scrollHeight;
}



        // Função para resetar o chat
        function resetChat() {
            const chatContent = document.getElementById('chat-content');
            chatContent.innerHTML = `
                <p><strong>Prevnergo:</strong> Olá, <?php echo $nomeUsuario; ?>! Como posso te ajudar hoje? Escolha uma categoria abaixo:</p>
                <div class="chat-question" onclick="sendQuestion('ergonomia')">Ergonomia</div>
                <div class="chat-question" onclick="sendQuestion('postura')">Melhorar Postura</div>
                <div class="chat-question" onclick="sendQuestion('beneficios')">Benefícios da Ergonomia</div>
            `;
        }
/**O backend em PHP lida com a busca de respostas e sugestões de perguntas relacionadas em um banco de dados PostgreSQL.
O frontend utiliza HTML, CSS e JavaScript para criar uma interface interativa de chatbot que responde a perguntas e sugere tópicos relacionados.
As interações são enviadas para o backend via fetch, que retorna as respostas em formato JSON, exibidas dinamicamente no chat. */        
    </script>

</body>
</html>

