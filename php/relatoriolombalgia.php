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

    // Perguntas do questionário
    $posicionamento1 = $_POST['posicionamento1'] ?? 0;
    $atingirchao2 = $_POST['atingirchao2'] ?? 0;
    $pegacargas3 = $_POST['pegacargas3'] ?? 0;
    $cargas4 = $_POST['cargas4'] ?? 0;
    $esforcos5 = $_POST['esforcos5'] ?? 0;
    $manusear6 = $_POST['manusear6'] ?? 0;
    $posicao7 = $_POST['posicao7'] ?? 0;
    $pesadas8 = $_POST['pesadas8'] ?? 0;
    $cargas9 = $_POST['cargas9'] ?? 0;
    $cargas10 = $_POST['cargas10'] ?? 0;
    $bracos11 = $_POST['bracos11'] ?? 0;
    $tronco12 = $_POST['tronco12'] ?? 0;

    // Cálculo da pontuação total
    $pontuacao = $posicionamento1 + $atingirchao2 + $pegacargas3 + $cargas4 + $esforcos5 + $manusear6 + $posicao7 + $pesadas8 + $cargas9 + $cargas10 + $bracos11 + $tronco12;

    // Interpretação da pontuação
    $classificacao = "";
    if ($pontuacao >= 11) {
        $classificacao = "baixíssimo";
    } elseif ($pontuacao >= 8) {
        $classificacao = "baixo";
    } elseif ($pontuacao >= 6) {
        $classificacao = "moderado";
    } elseif ($pontuacao >= 4) {
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
        'lombalgia', // O tipo de relatório conforme necessário
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
    echo "<p><strong>Pontuação Total:</strong> $pontuacao</p>";
    echo "<p><strong>Condição ergonômica:</strong> $classificacao riso de lombalgia</p>";

    // Exibe as respostas
    echo "<h2>Respostas do Questionário</h2>";
    echo "<p>1. O trabalho envolve posicionamento estático do tronco em posição fletida entre 30 e 60 graus? " . ($posicionamento1 == 1 ? 'Não (1)' : 'Sim (0)') . "</p>";
    echo "<p>2. O trabalhador tem que frequentemente atingir o chão com as mãos, independente de carga? " . ($atingirchao2 == 1 ? 'Não (1)' : 'Sim (0)') . "</p>";
    echo "<p>3. O trabalho envolve pegar cargas maiores que 10kg em frequência maior que uma vez a cada 5 minutos? " . ($pegacargas3 == 1 ? 'Não (1)' : 'Sim (0)') . "</p>";
    echo "<p>4. O trabalho envolve pegar cargas do chão, independente de peso, em frequência maior que 1 vez por minuto? " . ($cargas4 == 1 ? 'Não (1)' : 'Sim (0)') . "</p>";
    echo "<p>5. O trabalho envolve fazer esforço com ferramenta ou com as mãos estando o tronco encurvado? " . ($esforcos5 == 1 ? 'Não (1)' : 'Sim (0)') . "</p>";
    echo "<p>6. O trabalho envolve a necessidade de manusear (levantar ou puxar ou empurrar) cargas que estejam longe do tronco? " . ($manusear6 == 1 ? 'Não (1)' : 'Sim (0)') . "</p>";
    echo "<p>7. O trabalho envolve a necessidade de manusear cargas com o tronco em posição assimétrica? " . ($posicao7 == 1 ? 'Não (1)' : 'Sim (0)') . "</p>";
    echo "<p>8. O trabalho envolve a necessidade de carregar mais pesadas que 20kg mesmo ocasionalmente? " . ($pesadas8 == 1 ? 'Não (1)' : 'Sim (0)') . "</p>";
    echo "<p>9. O trabalho envolve a necessidade de carregar cargas mais pesadas que 10kg frequentemente? " . ($cargas9 == 1 ? 'Não (1)' : 'Sim (0)') . "</p>";
    echo "<p>10. O trabalho envolve a necessidade de carregar cargas na cabeça? " . ($cargas10 == 1 ? 'Não (1)' : 'Sim (0)') . "</p>";
    echo "<p>11. O trabalho envolve a necessidade de ficar constantemente com os braços longe do tronco em posição suspensa? " . ($bracos11 == 1 ? 'Não (1)' : 'Sim (0)') . "</p>";
    echo "<p>12. O trabalho exige que o trabalhador fique com o tronco em posição estática, sem apoio? " . ($tronco12 == 1 ? 'Não (1)' : 'Sim (0)') . "</p>";

} else {
    echo "Nenhum dado enviado.";
}
?>
