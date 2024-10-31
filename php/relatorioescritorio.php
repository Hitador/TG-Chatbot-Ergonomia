<?php
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

    // Avaliação da Cadeira
    $cadeira1 = $_POST['cadeira1'] ?? 0;
    $cadeira2 = $_POST['cadeira2'] ?? 0;
    $cadeira3 = $_POST['cadeira3'] ?? 0;
    $cadeira4 = $_POST['cadeira4'] ?? 0;
    $cadeira5 = $_POST['cadeira5'] ?? 0;
    $cadeira6 = $_POST['cadeira6'] ?? 0;
    $cadeira7 = $_POST['cadeira7'] ?? 0;
    $cadeira8 = $_POST['cadeira8'] ?? 0;
    $cadeira9 = $_POST['cadeira9'] ?? 0;
    $cadeira10 = $_POST['cadeira10'] ?? 0;
    $cadeira11 = $_POST['cadeira11'] ?? 0;
    $cadeira12 = $_POST['cadeira12'] ?? 0;
    $cadeira13 = $_POST['cadeira13'] ?? 0;
    $cadeira14 = $_POST['cadeira14'] ?? 0;
    $cadeira15 = $_POST['cadeira15'] ?? 0;
    $cadeira16 = $_POST['cadeira16'] ?? 0;
    $cadeira17 = $_POST['cadeira17'] ?? 0;
    $cadeira18 = $_POST['cadeira18'] ?? 0;
    $cadeira19 = $_POST['cadeira19'] ?? 0;

    // Avaliação da Mesa
    $movel1_1 = $_POST['movel1_1'] ?? 0;
    $movel1_2 = $_POST['movel1_2'] ?? 0;
    $movel1_3 = $_POST['movel1_3'] ?? 0;
    $movel1_4 = $_POST['movel1_4'] ?? 0;
    $movel1_5 = $_POST['movel1_5'] ?? 0;
    $movel1_6 = $_POST['movel1_6'] ?? 0;
    $movel1_7 = $_POST['movel1_7'] ?? 0;
    $movel1_8 = $_POST['movel1_8'] ?? 0;
    $movel1_9 = $_POST['movel1_9'] ?? 0;
    $movel1_10 = $_POST['movel1_10'] ?? 0;
    $movel1_11 = $_POST['movel1_11'] ?? 0;
    $movel1_12 = $_POST['movel1_12'] ?? 0;

    // Avaliação do Suporte do Teclado
    $teclado1_1 = $_POST['teclado1_1'] ?? 0;
    $teclado1_2 = $_POST['teclado1_2'] ?? 0;
    $teclado1_3 = $_POST['teclado1_3'] ?? 0;
    $teclado1_4 = $_POST['teclado1_4'] ?? 0;
    $teclado1_5 = $_POST['teclado1_5'] ?? 0;
    $teclado1_6 = $_POST['teclado1_6'] ?? 0;
    $teclado1_7 = $_POST['teclado1_7'] ?? 0;
    $teclado1_8 = $_POST['teclado1_8'] ?? 0;

    // Avaliação do Monitor de Vídeo
    $monitor1 = $_POST['monitor1'] ?? 0;
    $monitor2 = $_POST['monitor2'] ?? 0;
    $monitor3 = $_POST['monitor3'] ?? 0;
    $monitor4 = $_POST['monitor4'] ?? 0;
    $monitor5 = $_POST['monitor5'] ?? 0;
    $monitor6 = $_POST['monitor6'] ?? 0;
    $monitor7 = $_POST['monitor7'] ?? 0;
    $monitor8 = $_POST['monitor8'] ?? 0;

    // Avaliação do Notebook
    $notebook1 = $_POST['notebook1'] ?? 0;
    $notebook2 = $_POST['notebook2'] ?? 0;
    $notebook3 = $_POST['notebook3'] ?? 0;
    $notebook4 = $_POST['notebook4'] ?? 0;
    $notebook5 = $_POST['notebook5'] ?? 0;
    $notebook6 = $_POST['notebook6'] ?? 0;
    $notebook7 = $_POST['notebook7'] ?? 0;
    $notebook8 = $_POST['notebook8'] ?? 0;
    $notebook9 = $_POST['notebook9'] ?? 0;
    $notebook10 = $_POST['notebook10'] ?? 0;

    // Avaliação do Ambiente de Trabalho
    $ambiente1 = $_POST['ambiente1'] ?? 0;
    $ambiente2 = $_POST['ambiente2'] ?? 0;
    $ambiente3 = $_POST['ambiente3'] ?? 0;
    $ambiente4 = $_POST['ambiente4'] ?? 0;
    $ambiente5 = $_POST['ambiente5'] ?? 0;
    $ambiente6 = $_POST['ambiente6'] ?? 0;
    $ambiente7 = $_POST['ambiente7'] ?? 0;
    $ambiente8 = $_POST['ambiente8'] ?? 0;
    $ambiente9 = $_POST['ambiente9'] ?? 0;
    $ambiente10 = $_POST['ambiente10'] ?? 0;
    $ambiente11 = $_POST['ambiente11'] ?? 0;

    // Outros fatores relacionados
    $uso_computador = $_POST['uso_computador'] ?? 0;
    $digitacao = $_POST['digitacao'] ?? 0;
    $pausa_trabalho = $_POST['pausa_trabalho'] ?? 0;
    $software = $_POST['software'] ?? 0;
    $iluminacao = $_POST['iluminacao'] ?? 0;
    $iluminacao_sup = $_POST['iluminacao_sup'] ?? 0;
    $reflexos = $_POST['reflexos'] ?? 0;
    $deslumbramento = $_POST['deslumbramento'] ?? 0;
    $postos = $_POST['postos'] ?? 0;
    $persianas = $_POST['persianas'] ?? 0;
    $brilho = $_POST['brilho'] ?? 0;
    $legibilidade = $_POST['legibilidade'] ?? 0;
    $acesso_trabalhos = $_POST['acesso_trabalhos'] ?? 0;
    $acesso_areas_comuns = $_POST['acesso_areas_comuns'] ?? 0;
    $acesso_sanitarios = $_POST['acesso_sanitarios'] ?? 0;
    $evacuacao = $_POST['evacuacao'] ?? 0;
    $botoes_alcance = $_POST['botoes_alcance'] ?? 0;

    // Soma das pontuações de cada seção
    $pontuacao_cadeira = $cadeira1 + $cadeira2 + $cadeira3 + $cadeira4 + $cadeira5 + $cadeira6 + $cadeira7 + $cadeira8 + 
    $cadeira9 + $cadeira10 + $cadeira11 + $cadeira12 + $cadeira13 + $cadeira14 + $cadeira15 + $cadeira16 + 
    $cadeira17 + $cadeira18 + $cadeira19;

    $pontuacao_movel = $movel1_1 + $movel1_2 + $movel1_3 + $movel1_4 + $movel1_5 + $movel1_6 + $movel1_7 + $movel1_8 + $movel1_9 + 
    $movel1_10 + $movel1_11 + $movel1_12;

    $pontuacao_teclado = $teclado1_1 + $teclado1_2 + $teclado1_3 + $teclado1_4 + $teclado1_5 + $teclado1_6 + $teclado1_7 + $teclado1_8;

    $pontuacao_monitor = $monitor1 + $monitor2 + $monitor3 + $monitor4 + $monitor5 + $monitor6 + $monitor7 + $monitor8;

    $pontuacao_notebook = $notebook1 + $notebook2 + $notebook3 + $notebook4 + $notebook5 + $notebook6 + $notebook7 + $notebook8 + 
    $notebook9 + $notebook10;

    $pontuacao_ambiente = $ambiente1 + $ambiente2 + $ambiente3 + $ambiente4 + $ambiente5 + $ambiente6 + $ambiente7 + $ambiente8 + 
    $ambiente9 + $ambiente10 + $ambiente11;

    $pontuacao_outros_fatores = $uso_computador + $digitacao + $pausa_trabalho + $software + $iluminacao + $iluminacao_sup + 
        $reflexos + $deslumbramento + $postos + $persianas + $brilho + $legibilidade + $acesso_trabalhos + 
        $acesso_areas_comuns + $acesso_sanitarios + $evacuacao + $botoes_alcance;

    // Soma total de todas as pontuações
    $pontuacao_total = $pontuacao_cadeira + $pontuacao_movel + $pontuacao_teclado + $pontuacao_monitor + $pontuacao_notebook + 
    $pontuacao_ambiente + $pontuacao_outros_fatores;

    // Define a pontuação máxima possível
    $pontuacao_maxima = (19 + 12 + 8 + 8 + 10 + 11 + 17); // Soma do número de questões em cada seção

    // Calcula a porcentagem da pontuação total
    $porcentagem_total = ($pontuacao_total / $pontuacao_maxima) * 100;

    if ($pontuacao >= 91) {
        $classificacao = "excelente";
    } elseif ($pontuacao >= 71) {
        $classificacao = "boa";
    } elseif ($pontuacao >= 51) {
        $classificacao = "razoável";
    } elseif ($pontuacao >= 31) {
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
        'escritorio', // O tipo de relatório conforme necessário
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

    echo "<h2>Pontuações por Seção:</h2>";
    echo "<p><strong>Avaliação da Cadeira:</strong> $pontuacao_cadeira pontos</p>";
    echo "<p><strong>Avaliação da Mesa:</strong> $pontuacao_movel pontos</p>";
    echo "<p><strong>Avaliação do Suporte do Teclado:</strong> $pontuacao_teclado pontos</p>";
    echo "<p><strong>Avaliação do Monitor:</strong> $pontuacao_monitor pontos</p>";
    echo "<p><strong>Avaliação do Notebook:</strong> $pontuacao_notebook pontos</p>";
    echo "<p><strong>Avaliação do Ambiente de Trabalho:</strong> $pontuacao_ambiente pontos</p>";
    echo "<p><strong>Outros Fatores Relacionados:</strong> $pontuacao_outros_fatores pontos</p>";
    echo "<p><strong>Pontuação Total:</strong> $pontuacao_total pontos</p>";

    // Interpretação do resultado baseado na porcentagem total
    if ($porcentagem_total >= 91) {
    echo "<p><strong>Classificação:</strong> Condição ergonômica excelente</p>";
    } elseif ($porcentagem_total >= 71) {
    echo "<p><strong>Classificação:</strong> Boa condição ergonômica</p>";
    } elseif ($porcentagem_total >= 51) {
    echo "<p><strong>Classificação:</strong> Condição ergonômica razoável</p>";
    } elseif ($porcentagem_total >= 31) {
    echo "<p><strong>Classificação:</strong> Condição ergonômica ruim</p>";
    } else {
    echo "<p><strong>Classificação:</strong> Condição ergonômica péssima</p>";
    }

} else {
    echo "Nenhum dado enviado.";
}
?>
