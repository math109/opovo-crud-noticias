<?php
require_once __DIR__ . '/../src/Noticia.php';

use App\Noticia;

$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {
    $noticia = new Noticia();
    $noticia->excluir($id);
}

// Depois de excluir, volta para a listagem
header('Location: index.php');
exit;