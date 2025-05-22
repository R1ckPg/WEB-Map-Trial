<?php
// Garante que o cookie de dark mode esteja disponível nas páginas
if (!isset($_COOKIE['darkmode'])) {
    setcookie('darkmode', '0', time() + 3600 * 24 * 365, '/');
}
?>
