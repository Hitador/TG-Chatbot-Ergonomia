<?php
include 'conectabd.php';
// Inicie a sessão, se ainda não estiver iniciada
session_start();

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

    // Perguntas do questionário: Sobrecarga Física
    $sobrecarga1_1 = $_POST['sobrecarga1_1'] ?? 0;
    $sobrecarga1_2 = $_POST['sobrecarga1_2'] ?? 0;
    $sobrecarga1_3 = $_POST['sobrecarga1_3'] ?? 0;
    $sobrecarga1_4 = $_POST['sobrecarga1_4'] ?? 0;
    $sobrecarga1_5 = $_POST['sobrecarga1_5'] ?? 0;

    // Perguntas do questionário: Força com as Mãos
    $forca2_1 = $_POST['forca2_1'] ?? 0;
    $forca2_2 = $_POST['forca2_2'] ?? 0;
    $forca2_3 = $_POST['forca2_3'] ?? 0;
    $forca2_4 = $_POST['forca2_4'] ?? 0;

    // Perguntas do questionário: Postura no Trabalho
    $postura3_1 = $_POST['postura3_1'] ?? 0;
    $postura3_2 = $_POST['postura3_2'] ?? 0;
    $postura3_3 = $_POST['postura3_3'] ?? 0;
    $postura3_4 = $_POST['postura3_4'] ?? 0;
    $postura3_5 = $_POST['postura3_5'] ?? 0;
    $postura3_6 = $_POST['postura3_6'] ?? 0;
    $postura3_7 = $_POST['postura3_7'] ?? 0;

    // Perguntas do questionário: Posto de Trabalho
    $posto4_1 = $_POST['posto4_1'] ?? 0;
    $posto4_2 = $_POST['posto4_2'] ?? 0;

    // Perguntas do questionário: Repetitividade
    $repetitividade5_1 = $_POST['repetitividade5_1'] ?? 0;
    $repetitividade5_2 = $_POST['repetitividade5_2'] ?? 0;
    $repetitividade5_3 = $_POST['repetitividade5_3'] ?? 0;
    $repetitividade5_4 = $_POST['repetitividade5_4'] ?? 0;
    $repetitividade5_5 = $_POST['repetitividade5_5'] ?? 0;

    // Perguntas do questionário: Ferramentas de Trabalho
    $prensao6_1 = $_POST['prensao6_1'] ?? 0;
    $ferramenta6_1 = $_POST['ferramenta6_1'] ?? 0;
    $ferramenta6_2 = $_POST['ferramenta6_2'] ?? 0;
    $ferramenta6_3 = $_POST['ferramenta6_3'] ?? 0;

    // Cálculo da pontuação total
    $pontuacao = $sobrecarga1_1 + $sobrecarga1_2 + $sobrecarga1_3 + $sobrecarga1_4 + $sobrecarga1_5 + 
                 $forca2_1 + $forca2_2 + $forca2_3 + $forca2_4 + 
                 $postura3_1 + $postura3_2 + $postura3_3 + $postura3_4 + $postura3_5 + $postura3_6 + $postura3_7 +
                 $posto4_1 + $posto4_2 +
                 $repetitividade5_1 + $repetitividade5_2 + $repetitividade5_3 + $repetitividade5_4 + $repetitividade5_5 +
                 $prensao6_1 + $ferramenta6_1 + $ferramenta6_2 + $ferramenta6_3;

    // Interpretação da pontuação
    if ($pontuacao >= 23) {
        $classificacao = "baixíssimo";
    } elseif ($pontuacao >= 19) {
        $classificacao = "baixo";
    } elseif ($pontuacao >= 15) {
        $classificacao = "moderado";
    } elseif ($pontuacao >= 11) {
        $classificacao = "alto";
    } else {
        $classificacao = "altíssimo";
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
        'lerdort', // O tipo de relatório conforme necessário
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
    echo "<p><strong>Pontuação:</strong> $pontuacao</p>";
    echo "<p><strong>Condição ergonômica:</strong> $classificacao risco de LER/DORT</p>";

} else {
    echo "Nenhum dado enviado.";
}
?>
