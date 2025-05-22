<?php
include 'db.php';
header('Content-Type: application/json');

// Captura e divide os filtros
$tipos = isset($_GET['tipos']) && $_GET['tipos'] !== '' ? explode(',', $_GET['tipos']) : [];

// Base da query
$sql = "SELECT * FROM pontos_doacao";
$params = [];

if (count($tipos) > 0) {
    $condicoes = [];

    foreach ($tipos as $tipo) {
        // Para garantir que cada tipo esteja presente no campo
        $condicoes[] = "tipo_doacao LIKE ?";
        $params[] = '%' . $tipo . '%';
    }

    // Concatena todas as condições com AND
    $sql .= " WHERE " . implode(" AND ", $condicoes);
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

// Retorna os pontos encontrados
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
