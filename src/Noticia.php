<?php

namespace App;

use PDO;
use PDOException;

require_once __DIR__ . '/../config/database.php';

/**
 * Classe responsável por toda a lógica de acesso a dados da entidade Notícia.
 *
 * Centralizar aqui todas as operações no banco (em vez de escrever SQL
 * espalhado pelas páginas em public/) segue o princípio de Separação de
 * Responsabilidades: esta classe cuida só de "falar com o banco", enquanto
 * as páginas em public/ cuidam só de "mostrar a interface".
 */
class Noticia
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = conectarBanco();
    }

    /**
     * Insere uma nova notícia no banco de dados.
     *
     * Usa um prepared statement (com parâmetros nomeados, como :titulo)
     * em vez de concatenar os valores direto na query SQL — isso é o que
     * previne ataques de SQL Injection: o banco trata os valores sempre
     * como dado, nunca como parte do comando SQL.
     *
     * @param array $dados Dados da notícia (titulo, subtitulo, conteudo, autor, categoria, data_publicacao)
     * @return bool true se a inserção foi bem-sucedida
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
     * Busca notícias por termo e/ou categoria, com paginação.
     * Retorna um array com 'noticias' (os itens da página atual)
     * e 'total' (quantidade total de registros, sem considerar o limite).
     */
    public function buscar(string $termo = '', string $categoria = '', int $pagina = 1, int $porPagina = 5): array
    {
        $condicoes = "WHERE 1=1"; // "1=1" permite sempre usar "AND" depois, sem checar se é a primeira condição
        $parametros = [];

        if ($termo !== '') {
            $condicoes .= " AND titulo LIKE :termo";
            $parametros[':termo'] = '%' . $termo . '%'; // % antes e depois = busca por trecho, em qualquer parte do título
        }

        if ($categoria !== '') {
            $condicoes .= " AND categoria = :categoria";
            $parametros[':categoria'] = $categoria;
        }

        // Primeiro, contamos quantos registros existem no TOTAL (sem paginação),
        // para calcular depois quantas páginas serão necessárias.
        $sqlTotal = "SELECT COUNT(*) FROM noticias {$condicoes}";
        $stmtTotal = $this->pdo->prepare($sqlTotal);
        $stmtTotal->execute($parametros);
        $total = (int) $stmtTotal->fetchColumn();

        // Calcula quantos registros pular (OFFSET) com base na página solicitada.
        // Ex: página 2 com 5 por página = pula os 5 primeiros (OFFSET 5).
        $offset = ($pagina - 1) * $porPagina;
        $sql = "SELECT * FROM noticias {$condicoes} ORDER BY data_publicacao DESC LIMIT :limite OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);

        foreach ($parametros as $chave => $valor) {
            $stmt->bindValue($chave, $valor);
        }

        // LIMIT e OFFSET precisam ser vinculados explicitamente como inteiros (PARAM_INT),
        // senão o MySQL pode rejeitar a query (o PDO trataria como string por padrão).
        $stmt->bindValue(':limite', $porPagina, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return [
            'noticias' => $stmt->fetchAll(),
            'total' => $total,
        ];
    }

    /**
     * Retorna a lista de categorias distintas já cadastradas,
     * para popular um filtro (select) na tela.
     */
    public function listarCategorias(): array
    {
        $sql = "SELECT DISTINCT categoria FROM noticias WHERE categoria != '' ORDER BY categoria";
        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
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
