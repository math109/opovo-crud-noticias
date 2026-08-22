<?php
require_once __DIR__ . '/../src/Noticia.php';

$id = (int) ($_GET['id'] ?? 0);
$noticia = new Noticia();
$item = $noticia->buscarPorId($id);

// Se não encontrar a notícia, redireciona para a listagem
if (!$item) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($item['titulo']) ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <p><a href="index.php">&larr; Voltar</a></p>

    <article>
        <h1><?= htmlspecialchars($item['titulo']) ?></h1>
        <?php if ($item['subtitulo']): ?>
            <p class="subtitulo"><?= htmlspecialchars($item['subtitulo']) ?></p>
        <?php endif; ?>
        <p class="meta">
            Por <?= htmlspecialchars($item['autor']) ?>
            · <?= date('d/m/Y H:i', strtotime($item['data_publicacao'])) ?>
        </p>
        <div class="conteudo">
            <?= nl2br(htmlspecialchars($item['conteudo'])) ?>
        </div>
    </article>

    <p>
        <a href="editar.php?id=<?= $item['id'] ?>">Editar</a> ·
        <a href="excluir.php?id=<?= $item['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir esta notícia?')">Excluir</a>
    </p>
</body>
</html>