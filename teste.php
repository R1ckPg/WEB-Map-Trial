<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Teste de Conexão</title>
  <link rel="shortcut icon" href="Map.ico" type="image/x-icon">
  <style>
    body {
      font-family: Arial, sans-serif;
      transition: background 0.3s, color 0.3s;
      background-color: #f4f4f4;
      color: #333;
      padding: 30px;
      text-align: center;
    }

    .dark-mode {
      background-color: #121212;
      color: #eee;
    }

    button {
      margin-top: 20px;
      padding: 10px 20px;
      font-size: 16px;
      cursor: pointer;
      border: none;
      border-radius: 5px;
    }

    .dark-mode button {
      background-color: #333;
      color: #fff;
    }

    .light-mode button {
      background-color: #ddd;
      color: #000;
    }

    .switch {
      margin-top: 30px;
    }
  </style>
</head>
<body>

  <h1>Teste de Conexão com o Banco de Dados</h1>

  <?php
  try {
    $pdo->query("SELECT 1");
    echo "<p style='color:green;'>✅ Conexão com o banco de dados bem-sucedida!</p>";
  } catch (PDOException $e) {
    echo "<p style='color:red;'>❌ Erro ao conectar: " . $e->getMessage() . "</p>";
  }
  ?>

  <button onclick="history.back()">🔙 Voltar</button>

  <div class="switch">
    <button onclick="toggleDarkMode()">🌙 Ativar/Desativar Dark Mode</button>
  </div>

  <script>
    function toggleDarkMode() {
      document.body.classList.toggle("dark-mode");
    }
  </script>

</body>
</html>
