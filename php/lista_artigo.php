<?php
session_start(); // Inicia a sessão

// Conexão com o banco de dados PostgreSQL
include 'conectabd.php';

// Função para obter artigos do banco de dados
function obter_artigos($conn, $idcpfusuario) {
    $query = "SELECT * FROM artigos WHERE idcpfusuario = $1"; // Certifique-se de filtrar pelos artigos do usuário logado
    $result = pg_query_params($conn, $query, array($idcpfusuario));

    if (!$result) {
        die("Erro na consulta dos artigos: " . pg_last_error($conn));
    }

    return pg_fetch_all($result);
}

// Verifica se o usuário está logado e obtém o idcpfusuario
$idcpfusuario = $_SESSION['idcpfusuario'] ?? null; // Altere conforme o método que usa para autenticação

if ($idcpfusuario === null) {
    die("Usuário não autenticado.");
}

// Obtendo artigos do banco de dados
$artigos = obter_artigos($conn, $idcpfusuario);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Artigos</title>
    <style>
        /* Estilo básico */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #2E8B57;
            color: white;
        }

        button {
            background-color: #d9534f;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            padding: 5px 10px;
        }

        button:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>

<h1>Lista de Artigos</h1>

<table>
    <tr>
        <th>ID</th>
        <th>Nome do Autor</th>
        <th>Data de Publicação</th>
        <th>Ações</th>
    </tr>
    <?php if ($artigos): ?>
        <?php foreach ($artigos as $artigo): ?>
            <tr>
                <td><?php echo htmlspecialchars($artigo['id']); ?></td>
                <td><?php echo htmlspecialchars($artigo['author']); ?></td>
                <td><?php echo htmlspecialchars($artigo['publish_date']); ?></td>
                <td>
                    <form action="editar_artigo.php" method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($artigo['id']); ?>">
                        <button type="submit">Editar</button>
                    </form>
                    <form action="excluir_artigo.php" method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($artigo['id']); ?>">
                        <button type="submit">Excluir</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="4">Nenhum artigo encontrado.</td>
        </tr>
    <?php endif; ?>
</table>

</body>
</html>

<?php
pg_close($conn);
?>
