<?php
$host = 'localhost';
$db   = 'mapa_doacoes';
$user = 'root';
$pass = 'aluno@etep';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Mostra erro
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
  $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
  echo "<h2>Erro ao conectar com o banco!</h2>";
  echo "<p>" . $e->getMessage() . "</p>";
  exit;
}
?>
