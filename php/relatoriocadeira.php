<?php
// Inicie a sessão, se ainda não estiver iniciada
session_start();
include 'conectabd.php';

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coleta os dados do formulário
    $supervisor = $_POST['txrepresentante'] ?? '';
    $predio = $_POST['txnomepredio'] ?? '';
    $setor = $_POST['txcc'] ?? '';
    $cargo = $_POST['txestacao'] ?? '';
    
    // Recupera o CPF da sessão
    $cpfid = '';
    if (isset($_SESSION['idcpfusuario'])) {
        $cpfid = $_SESSION['idcpfusuario']; // Recupera o CPF da sessão
    } else {
        echo "CPF não encontrado na sessão.";
        exit; // Sai do script se o CPF não estiver na sessão
    }

    // Perguntas do questionário: Avaliação da Cadeira
    $cadeira1_1 = $_POST['cadeira1_1'] ?? 0;
    $cadeira1_2 = $_POST['cadeira1_2'] ?? 0;
    $cadeira1_3 = $_POST['cadeira1_3'] ?? 0;
    $cadeira1_4 = $_POST['cadeira1_4'] ?? 0;
    $cadeira1_5 = $_POST['cadeira1_5'] ?? 0;
    $cadeira1_6 = $_POST['cadeira1_6'] ?? 0;
    $cadeira1_7 = $_POST['cadeira1_7'] ?? 0;
    $cadeira1_8 = $_POST['cadeira1_8'] ?? 0;
    $cadeira1_9 = $_POST['cadeira1_9'] ?? 0;
    $cadeira1_10 = $_POST['cadeira1_10'] ?? 0;
    $cadeira1_11 = $_POST['cadeira1_11'] ?? 0;
    $cadeira1_12 = $_POST['cadeira1_12'] ?? 0;
    $cadeira1_13 = $_POST['cadeira1_13'] ?? 0;
    $cadeira1_14 = $_POST['cadeira1_14'] ?? 0;
    $cadeira1_15 = $_POST['cadeira1_15'] ?? 0;
    $cadeira1_16 = $_POST['cadeira1_16'] ?? 0;
    $cadeira1_17 = $_POST['cadeira1_17'] ?? 0;

    // Perguntas do questionário: Avaliação da Mesa
    $mesa2_1 = $_POST['mesa2_1'] ?? 0;
    $mesa2_2 = $_POST['mesa2_2'] ?? 0;
    $mesa2_3 = $_POST['mesa2_3'] ?? 0;
    $mesa2_4 = $_POST['mesa2_4'] ?? 0;
    $mesa2_5 = $_POST['mesa2_5'] ?? 0;
    $mesa2_6 = $_POST['mesa2_6'] ?? 0;
    $mesa2_7 = $_POST['mesa2_7'] ?? 0;
    $mesa2_8 = $_POST['mesa2_8'] ?? 0;

    // Cálculo da pontuação total
    $pontuacao_cadeira = $cadeira1_1 + $cadeira1_2 + $cadeira1_3 + $cadeira1_4 + $cadeira1_5 + $cadeira1_6 + $cadeira1_7 + $cadeira1_8 + 
                         $cadeira1_9 + $cadeira1_10 + $cadeira1_11 + $cadeira1_12 + $cadeira1_13 + $cadeira1_14 + $cadeira1_15 + 
                         $cadeira1_16 + $cadeira1_17;

    $pontuacao_mesa = $mesa2_1 + $mesa2_2 + $mesa2_3 + $mesa2_4 + $mesa2_5 + $mesa2_6 + $mesa2_7 + $mesa2_8;

    $total_pontuacao = $pontuacao_cadeira + $pontuacao_mesa;
    $total_possivel = 17 + 8;

    // Porcentagem da pontuação
    $porcentagem = ($total_pontuacao / $total_possivel) * 100;

    // Classificação com base na porcentagem
    if ($porcentagem >= 91) {
        $classificacao = "excelente";
    } elseif ($porcentagem >= 71) {
        $classificacao = "boa";
    } elseif ($porcentagem >= 51) {
        $classificacao = "razoável";
    } elseif ($porcentagem >= 31) {
        $classificacao = "ruim";
    } else {
        $classificacao = "péssima";
    }


    // Obtém o último idrelatorio
    $result = pg_query($conn, "SELECT MAX(idrelatorio) AS last_id FROM public.relatorioergonomico");
    $row = pg_fetch_assoc($result);
    $idrelatorio = $row['last_id'] + 1; // Incrementa para o próximo id

    // Prepara a inserção dos dados
    $sql = "INSERT INTO public.relatorioergonomico (idrelatorio, cpfid, tiporelatorio, txnomesupervisor, txpredio, txsetor, txcargo, resultado) 
            VALUES ($1, $2, $3, $4, $5, $6, $7, $8)";

    $insert_result = pg_query_params($conn, $sql, [
        $idrelatorio,
        $cpfid,
        'cadeira', // O tipo de relatório conforme necessário
        $supervisor,
        $predio,
        $setor,
        $cargo,
        $classificacao, // Pode armazenar a classificação como resultado
    ]);

    if ($insert_result) {
        echo "Dados inseridos com sucesso!";
    } else {
        echo "Erro ao inserir dados: " . pg_last_error($conn);
    }

    // Fecha a conexão
    pg_close($conn);

    // Exibe os resultados
    echo "<h1>Relatório do Questionário</h1>";
    echo "<p><strong>Supervisor:</strong> $supervisor</p>";
    echo "<p><strong>Prédio:</strong> $predio</p>";
    echo "<p><strong>Setor:</strong> $setor</p>";
    echo "<p><strong>Cargo:</strong> $cargo</p>";
    echo "<p><strong>Pontuação Total:</strong> $total_pontuacao de $total_possivel pontos</p>";
    echo "<p><strong>Porcentagem:</strong> " . round($porcentagem, 2) . "%</p>";
    echo "<p><strong>Condição ergonômica:</strong> $classificacao</p>";
    echo "<p><strong>CPF:</strong> $cpfid</p>";

} else {
    echo "Nenhum dado enviado.";
}
?>
