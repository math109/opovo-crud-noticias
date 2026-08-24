
<?php
require_once __DIR__ . '/../src/Noticia.php';

// Este arquivo não renderiza nenhuma página — ele só processa a exclusão
// e redireciona de volta. A confirmação ("Tem certeza?") já acontece antes,
// via JavaScript (confirm()) no link que trouxe o usuário até aqui.
use App\Noticia;

$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {
    $noticia = new Noticia();
    $noticia->excluir($id);
}

// Depois de excluir, volta para a listagem
header('Location: index.php');
exit;
