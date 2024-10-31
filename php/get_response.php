<?php
header('Content-Type: application/json');

include 'conectabd.php';

// Receber a pergunta do usuário
$question = isset($_POST['user_question']) ? $_POST['user_question'] : '';

// Função para obter a resposta
function getResponse($conn, $question) {
    // Exibe o SQL e o parâmetro para fins de depuração
    error_log("Executando SQL: SELECT resposta FROM faq WHERE pergunta ILIKE $1, com o parâmetro: " . $question);
    
    $query = "SELECT resposta FROM faq WHERE pergunta ILIKE $1";
    $result = pg_query_params($conn, $query, array('%' . $question . '%'));

    if (pg_num_rows($result) > 0) {
        $row = pg_fetch_assoc($result);
        return $row['resposta'];
    } else {
        return 'Desculpe, não entendi sua pergunta. Pode reformular?';
    }
}

// Função para obter sugestões de perguntas
function getSuggestions($conn, $question) {
    // Exibe o SQL e o parâmetro para fins de depuração
    error_log("Executando SQL para sugestões: SELECT id FROM faq WHERE pergunta ILIKE $1, com o parâmetro: " . $question);
    
    $query = "SELECT id FROM faq WHERE pergunta ILIKE $1";
    $result = pg_query_params($conn, $query, array('%' . $question . '%'));

    if (pg_num_rows($result) > 0) {
        $row = pg_fetch_assoc($result);
        $pergunta_id = $row['id'];

        $querySugestoes = "
            SELECT f.pergunta 
            FROM faq_sugestoes fs 
            JOIN faq f ON fs.pergunta_relacionada_id = f.id 
            WHERE fs.pergunta_id = $1";
        $resultSugestoes = pg_query_params($conn, $querySugestoes, array($pergunta_id));

        $sugestoes = [];
        while ($rowSugestao = pg_fetch_assoc($resultSugestoes)) {
            $sugestoes[] = $rowSugestao['pergunta'];
        }
        return $sugestoes;
    } else {
        return [];
    }
}

// Obter a resposta e sugestões
$response = getResponse($conn, $question);
$suggestions = getSuggestions($conn, $question);

// Retornar a resposta e sugestões em formato JSON
echo json_encode([
    "response" => $response,
    "suggestions" => $suggestions
]);

// Fechar a conexão
pg_close($conn);
?>