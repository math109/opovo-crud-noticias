<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Noticia;
use PDO;

/**
 * Testes da classe Noticia.
 *
 * Importante: estes testes usam o banco de dados real (opovo_noticias),
 * já que o projeto não usa um banco separado de testes. Por isso, cada
 * teste limpa os dados que criou ao final (tearDown), para não deixar
 * "lixo" no banco nem afetar outros testes.
 */
class NoticiaTest extends TestCase
{
    private Noticia $noticia;
    private PDO $pdo;

    protected function setUp(): void
    {
        $this->noticia = new Noticia();

        // Conexão direta só para limpar os dados de teste depois
        $this->pdo = new PDO(
            'mysql:host=127.0.0.1;dbname=opovo_noticias;charset=utf8mb4',
            'root',
            ''
        );
    }

    protected function tearDown(): void
    {
        // Remove qualquer notícia criada durante os testes,
        // identificando pelo autor fictício usado nos testes.
        $this->pdo->exec("DELETE FROM noticias WHERE autor = 'Autor de Teste'");
    }

    public function testCriarNoticiaComSucesso(): void
    {
        $dados = [
            'titulo' => 'Notícia de teste',
            'subtitulo' => 'Subtítulo de teste',
            'conteudo' => 'Conteúdo de teste',
            'autor' => 'Autor de Teste',
            'categoria' => 'Teste',
            'data_publicacao' => '2026-01-01 10:00:00',
        ];

        $resultado = $this->noticia->criar($dados);

        $this->assertTrue($resultado);
    }

    public function testListarTodasRetornaArray(): void
    {
        $resultado = $this->noticia->listarTodas();

        $this->assertIsArray($resultado);
    }

    public function testBuscarPorIdRetornaNullQuandoNaoExiste(): void
    {
        // Usamos um ID absurdamente alto, que dificilmente existirá no banco
        $resultado = $this->noticia->buscarPorId(999999);

        $this->assertNull($resultado);
    }

    public function testCriarEBuscarNoticia(): void
    {
        $dados = [
            'titulo' => 'Notícia para buscar',
            'subtitulo' => '',
            'conteudo' => 'Conteúdo para busca',
            'autor' => 'Autor de Teste',
            'categoria' => 'Teste',
            'data_publicacao' => '2026-01-01 10:00:00',
        ];

        $this->noticia->criar($dados);

        // Busca o ID da notícia recém-criada
        $stmt = $this->pdo->query(
            "SELECT id FROM noticias WHERE autor = 'Autor de Teste' ORDER BY id DESC LIMIT 1"
        );
        $id = $stmt->fetchColumn();

        $encontrada = $this->noticia->buscarPorId((int) $id);

        $this->assertNotNull($encontrada);
        $this->assertEquals('Notícia para buscar', $encontrada['titulo']);
    }

    public function testAtualizarNoticia(): void
    {
        // Cria uma notícia para depois atualizar
        $this->noticia->criar([
            'titulo' => 'Título original',
            'subtitulo' => '',
            'conteudo' => 'Conteúdo original',
            'autor' => 'Autor de Teste',
            'categoria' => 'Teste',
            'data_publicacao' => '2026-01-01 10:00:00',
        ]);

        $stmt = $this->pdo->query(
            "SELECT id FROM noticias WHERE autor = 'Autor de Teste' ORDER BY id DESC LIMIT 1"
        );
        $id = (int) $stmt->fetchColumn();

        $resultado = $this->noticia->atualizar($id, [
            'titulo' => 'Título atualizado',
            'subtitulo' => '',
            'conteudo' => 'Conteúdo atualizado',
            'autor' => 'Autor de Teste',
            'categoria' => 'Teste',
            'data_publicacao' => '2026-01-01 10:00:00',
        ]);

        $atualizada = $this->noticia->buscarPorId($id);

        $this->assertTrue($resultado);
        $this->assertEquals('Título atualizado', $atualizada['titulo']);
    }

    public function testExcluirNoticia(): void
    {
        $this->noticia->criar([
            'titulo' => 'Notícia para excluir',
            'subtitulo' => '',
            'conteudo' => 'Conteúdo',
            'autor' => 'Autor de Teste',
            'categoria' => 'Teste',
            'data_publicacao' => '2026-01-01 10:00:00',
        ]);

        $stmt = $this->pdo->query(
            "SELECT id FROM noticias WHERE autor = 'Autor de Teste' ORDER BY id DESC LIMIT 1"
        );
        $id = (int) $stmt->fetchColumn();

        $resultado = $this->noticia->excluir($id);
        $buscaAposExcluir = $this->noticia->buscarPorId($id);

        $this->assertTrue($resultado);
        $this->assertNull($buscaAposExcluir);
    }
}