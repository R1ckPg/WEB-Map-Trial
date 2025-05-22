<?php
include 'db.php';

// Recebe dados do formulário
$nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
$cep = isset($_POST['cep']) ? preg_replace('/[^0-9]/', '', $_POST['cep']) : '';

// Pega array de tipos do checkbox, junta numa string separada por vírgula
if (isset($_POST['tipo_doacao']) && is_array($_POST['tipo_doacao'])) {
    $tipos = array_map('trim', $_POST['tipo_doacao']);
    $tipo = implode(', ', $tipos);
} else {
    $tipo = '';
}

if ($nome === '' || $cep === '' || $tipo === '') {
    die("Erro: Todos os campos são obrigatórios.");
}

// Consulta o ViaCEP
$endereco_api = @file_get_contents("https://viacep.com.br/ws/" . $cep . "/json/");
if ($endereco_api === false) {
    die("Erro: Falha ao consultar o CEP.");
}

$data = json_decode($endereco_api, true);
if (!isset($data['logradouro'], $data['localidade'], $data['uf'])) {
    die("Erro: CEP inválido ou não encontrado.");
}

// Endereços alternativos para geolocalização
$endereco_completo = $data['logradouro'] . ', ' . $data['localidade'] . ', ' . $data['uf'] . ', Brasil';
$endereco_por_cep = $cep . ', Brasil';

// Configura User-Agent
$context = stream_context_create([
    'http' => [
        'header' => "User-Agent: mapa-doacoes-local/1.0\r\n"
    ]
]);

// Função para obter coordenadas
function buscarCoordenadas($endereco, $context) {
    $url = "https://nominatim.openstreetmap.org/search?format=json&limit=1&q=" . urlencode($endereco);
    $resultado = @file_get_contents($url, false, $context);
    if ($resultado === false) return false;

    $dados = json_decode($resultado, true);
    if (empty($dados) || !isset($dados[0]['lat'], $dados[0]['lon'])) return false;

    return [
        'lat' => $dados[0]['lat'],
        'lon' => $dados[0]['lon']
    ];
}

// Tenta com o endereço completo, depois com o CEP
$coords = buscarCoordenadas($endereco_completo, $context);
if ($coords === false) {
    $coords = buscarCoordenadas($endereco_por_cep, $context);
}
if ($coords === false) {
    die("Erro: Não foi possível localizar o endereço no mapa.");
}

// Insere no banco
try {
    $stmt = $pdo->prepare("INSERT INTO pontos_doacao (nome, endereco, tipo_doacao, latitude, longitude) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nome, $endereco_completo, $tipo, $coords['lat'], $coords['lon']]);
} catch (PDOException $e) {
    die("Erro ao salvar no banco: " . $e->getMessage());
}

header("Location: index.html");
exit;
