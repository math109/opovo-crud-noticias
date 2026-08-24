<?php
require_once __DIR__ . '/../src/Noticia.php';

use App\Noticia;

$noticia = new Noticia();

// Lê os parâmetros da URL (?busca=x&categoria=y&pagina=2).
// Se não vierem, usamos valores padrão (busca vazia, página 1).
$termoBusca = trim($_GET['busca'] ?? '');
$categoriaFiltro = trim($_GET['categoria'] ?? '');
$paginaAtual = max(1, (int) ($_GET['pagina'] ?? 1));
$porPagina = 5;

$resultado = $noticia->buscar($termoBusca, $categoriaFiltro, $paginaAtual, $porPagina);
$noticias = $resultado['noticias'];
$totalPaginas = (int) ceil($resultado['total'] / $porPagina);
$categorias = $noticia->listarCategorias();

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Notícias</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Notícias</h1>

    <p><a href="criar.php">+ Nova notícia</a></p>

        <form method="GET" action="index.php" class="form-busca">
        <input type="text" name="busca" placeholder="Buscar por título..." value="<?= htmlspecialchars($termoBusca) ?>">

        <select name="categoria">
            <option value="">Todas as categorias</option>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= htmlspecialchars($cat) ?>" <?= $cat === $categoriaFiltro ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Filtrar</button>
        <?php if ($termoBusca || $categoriaFiltro): ?>
            <a href="index.php">Limpar filtro</a>
        <?php endif; ?>
    </form>

    <?php if (empty($noticias)): ?>
        <p>Nenhuma notícia cadastrada ainda.</p>
    <?php else: ?>
        <div class="lista-noticias">
            <?php foreach ($noticias as $item): ?>
                <article class="card-noticia">
                    <h2><a href="visualizar.php?id=<?= $item['id'] ?>"><?= htmlspecialchars($item['titulo']) ?></a></h2>
                    <?php if ($item['subtitulo']): ?>
                        <p class="subtitulo"><?= htmlspecialchars($item['subtitulo']) ?></p>
                    <?php endif; ?>
                    <p class="meta">
                        Por <?= htmlspecialchars($item['autor']) ?>
                        <?php if ($item['categoria']): ?>
                            · <?= htmlspecialchars($item['categoria']) ?>
                        <?php endif; ?>
                        · <?= date('d/m/Y H:i', strtotime($item['data_publicacao'])) ?>
                    </p>
                    <div class="acoes">
                        <a href="editar.php?id=<?= $item['id'] ?>">Editar</a>
                        <a href="excluir.php?id=<?= $item['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir esta notícia?')">Excluir</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
        <?php if ($totalPaginas > 1): ?>
        <nav class="paginacao">
            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <a href="index.php?pagina=<?= $i ?>&busca=<?= urlencode($termoBusca) ?>&categoria=<?= urlencode($categoriaFiltro) ?>"
                   class="<?= $i === $paginaAtual ? 'pagina-ativa' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </nav>
    <?php endif; ?>
</body>
</html>
