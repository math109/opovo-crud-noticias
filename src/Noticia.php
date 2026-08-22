<?php

namespace App;

use PDO;
use PDOException;

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

        /**
     * Retorna todas as notícias, das mais recentes para as mais antigas.
     */
    public function listarTodas(): array
    {
        $sql = "SELECT * FROM noticias ORDER BY data_publicacao DESC";
        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll();
    }

    /**
     * Busca uma única notícia pelo ID.
     * Retorna null se não encontrar (evita erro ao acessar array vazio).
     */
    public function buscarPorId(int $id): ?array
    {
        $sql = "SELECT * FROM noticias WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        $resultado = $stmt->fetch();

        return $resultado ?: null;


        
    }

        /**
     * Atualiza uma notícia existente pelo ID.
     */
    public function atualizar(int $id, array $dados): bool
    {
        $sql = "UPDATE noticias SET
                    titulo = :titulo,
                    subtitulo = :subtitulo,
                    conteudo = :conteudo,
                    autor = :autor,
                    categoria = :categoria,
                    data_publicacao = :data_publicacao
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':titulo' => $dados['titulo'],
            ':subtitulo' => $dados['subtitulo'],
            ':conteudo' => $dados['conteudo'],
            ':autor' => $dados['autor'],
            ':categoria' => $dados['categoria'],
            ':data_publicacao' => $dados['data_publicacao'],
            ':id' => $id,
        ]);
    }

        /**
     * Remove uma notícia do banco pelo ID.
     */
    public function excluir(int $id): bool
    {
        $sql = "DELETE FROM noticias WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }


}