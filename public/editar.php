<?php
require_once __DIR__ . '/../src/Noticia.php';

use App\Noticia;

$id = (int) ($_GET['id'] ?? 0);
$noticia = new Noticia();
$item = $noticia->buscarPorId($id);

if (!$item) {
    header('Location: index.php');
    exit;
}

$erro = '';

// Se o formulário foi enviado, processa a atualização
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $subtitulo = trim($_POST['subtitulo'] ?? '');
    $conteudo = trim($_POST['conteudo'] ?? '');
    $autor = trim($_POST['autor'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $dataPublicacao = $_POST['data_publicacao'] ?? '';

    if ($titulo === '' || $conteudo === '' || $autor === '' || $dataPublicacao === '') {
        $erro = 'Preencha os campos obrigatórios: título, conteúdo, autor e data de publicação.';
        // Mantém os dados digitados na tela mesmo com erro
        $item = array_merge($item, $_POST);
    } else {
        $noticia->atualizar($id, [
            'titulo' => $titulo,
            'subtitulo' => $subtitulo,
            'conteudo' => $conteudo,
            'autor' => $autor,
            'categoria' => $categoria,
            'data_publicacao' => $dataPublicacao,
        ]);

        header('Location: visualizar.php?id=' . $id);
        exit;
    }
}

// Formata a data para o formato esperado pelo input datetime-local
$dataFormatada = date('Y-m-d\TH:i', strtotime($item['data_publicacao']));
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Notícia</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Editar Notícia</h1>

    <?php if ($erro): ?>
        <p class="mensagem-erro"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>

    <form method="POST" action="editar.php?id=<?= $id ?>">
        <label for="titulo">Título *</label>
        <input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($item['titulo']) ?>" required>

        <label for="subtitulo">Subtítulo</label>
        <input type="text" id="subtitulo" name="subtitulo" value="<?= htmlspecialchars($item['subtitulo'] ?? '') ?>">

        <label for="conteudo">Conteúdo *</label>
        <textarea id="conteudo" name="conteudo" rows="8" required><?= htmlspecialchars($item['conteudo']) ?></textarea>

        <label for="autor">Autor *</label>
        <input type="text" id="autor" name="autor" value="<?= htmlspecialchars($item['autor']) ?>" required>

        <label for="categoria">Categoria</label>
        <input type="text" id="categoria" name="categoria" value="<?= htmlspecialchars($item['categoria'] ?? '') ?>">

        <label for="data_publicacao">Data de publicação *</label>
        <input type="datetime-local" id="data_publicacao" name="data_publicacao" value="<?= $dataFormatada ?>" required>

        <button type="submit">Salvar alterações</button>
    </form>

    <p><a href="visualizar.php?id=<?= $id ?>">Cancelar</a></p>
    <script src="js/script.js"></script>
</body>
</html>