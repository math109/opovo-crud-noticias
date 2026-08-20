<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Classe responsável por toda a lógica de acesso a dados
 * da entidade Notícia. Centralizar aqui evita espalhar
 * consultas SQL pelas páginas do site.
 */
class Noticia
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = conectarBanco();
    }

    /**
     * Insere uma nova notícia no banco.
     * Usa prepared statement (:parametros) para evitar SQL Injection.
     */
    public function criar(array $dados): bool
    {
        $sql = "INSERT INTO noticias (titulo, subtitulo, conteudo, autor, categoria, data_publicacao)
                VALUES (:titulo, :subtitulo, :conteudo, :autor, :categoria, :data_publicacao)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':titulo' => $dados['titulo'],
            ':subtitulo' => $dados['subtitulo'],
            ':conteudo' => $dados['conteudo'],
            ':autor' => $dados['autor'],
            ':categoria' => $dados['categoria'],
            ':data_publicacao' => $dados['data_publicacao'],
        ]);
    }
}