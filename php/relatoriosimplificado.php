<?php
// Inicie a sessão, se ainda não estiver iniciada
include 'conectabd.php';
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

    // Perguntas do questionário
    $corpo1 = $_POST['corpo1'] ?? 0;
    $bracos2 = $_POST['bracos2'] ?? 0;
    $esforco3 = $_POST['esforco3'] ?? 0;
    $posicoes4 = $_POST['posicoes4'] ?? 0;
    $forca5 = $_POST['forca5'] ?? 0;
    $repetitividade6 = $_POST['repetitividade6'] ?? 0;
    $pes7 = $_POST['pes7'] ?? 0;
    $esforco8 = $_POST['esforco8'] ?? 0;
    $flexibilidade9 = $_POST['flexibilidade9'] ?? 0;
    $pausa10 = $_POST['pausa10'] ?? 0;

    // Cálculo da pontuação total
    $pontuacao = $corpo1 + $bracos2 + $esforco3 + $posicoes4 + $forca5 + $repetitividade6 + $pes7 + $esforco8 + $flexibilidade9 + $pausa10;

    // Classificação com base na pontuação
    $classificacao = "";
    if ($pontuacao == 10) {
        $classificacao = "excelente";
    } elseif ($pontuacao >= 7) {
        $classificacao = "boa";
    } elseif ($pontuacao >= 5) {
        $classificacao = "razoável";
    } elseif ($pontuacao >= 3) {
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
        'simplificado', // O tipo de relatório conforme necessário
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
    echo "<p><strong>Condição ergonômica:</strong> $classificacao</p>";

} else {
    echo "Nenhum dado enviado.";
}
?>
