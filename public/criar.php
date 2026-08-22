<?php
require_once __DIR__ . '/../src/Noticia.php';

$erro = '';
$sucesso = false;

// Se o formulário foi enviado (método POST), processa o cadastro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validação simples: título, conteúdo e autor são obrigatórios
    $titulo = trim($_POST['titulo'] ?? '');
    $subtitulo = trim($_POST['subtitulo'] ?? '');
    $conteudo = trim($_POST['conteudo'] ?? '');
    $autor = trim($_POST['autor'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $dataPublicacao = $_POST['data_publicacao'] ?? '';

    if ($titulo === '' || $conteudo === '' || $autor === '' || $dataPublicacao === '') {
        $erro = 'Preencha os campos obrigatórios: título, conteúdo, autor e data de publicação.';
    } else {
        $noticia = new Noticia();
        $sucesso = $noticia->criar([
            'titulo' => $titulo,
            'subtitulo' => $subtitulo,
            'conteudo' => $conteudo,
            'autor' => $autor,
            'categoria' => $categoria,
            'data_publicacao' => $dataPublicacao,
        ]);
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Notícia</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Cadastrar Notícia</h1>

    <?php if ($sucesso): ?>
        <p class="mensagem-sucesso">Notícia cadastrada com sucesso! <a href="index.php">Ver todas as notícias</a></p>
    <?php endif; ?>

    <?php if ($erro): ?>
        <p class="mensagem-erro"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>

    <form method="POST" action="criar.php">
        <label for="titulo">Título *</label>
        <input type="text" id="titulo" name="titulo" required>

        <label for="subtitulo">Subtítulo</label>
        <input type="text" id="subtitulo" name="subtitulo">

        <label for="conteudo">Conteúdo *</label>
        <textarea id="conteudo" name="conteudo" rows="8" required></textarea>

        <label for="autor">Autor *</label>
        <input type="text" id="autor" name="autor" required>

        <label for="categoria">Categoria</label>
        <input type="text" id="categoria" name="categoria" placeholder="Ex: Política, Esportes, Cultura">

        <label for="data_publicacao">Data de publicação *</label>
        <input type="datetime-local" id="data_publicacao" name="data_publicacao" required>

        <button type="submit">Cadastrar</button>
    </form>

    <p><a href="index.php">Voltar para a listagem</a></p>
    <script src="js/script.js"></script>
</body>
</html>