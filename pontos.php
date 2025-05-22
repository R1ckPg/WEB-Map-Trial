<?php
include 'db.php';
$dados = $pdo->query("SELECT * FROM pontos_doacao")->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($dados);
?>