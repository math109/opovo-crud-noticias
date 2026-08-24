<?php
/**
 * Cria e retorna uma conexão PDO com o banco de dados MySQL.
 *
 * Usamos PDO em vez de mysqli porque:
 * - Suporta prepared statements de forma mais consistente, prevenindo SQL Injection
 * - Tem uma API orientada a objetos mais limpa
 * - Facilita trocar de banco de dados no futuro, se necessário (PDO suporta vários SGBDs)
 */

function conectarBanco(): PDO
{
    $host = '127.0.0.1';
    $banco = 'opovo_noticias';
    $usuario = 'root';
    $senha = ''; // XAMPP por padrão não tem senha no root
    $charset = 'utf8mb4';

    $dsn = "mysql:host={$host};dbname={$banco};charset={$charset}";

    $opcoes = [
        // Faz o PDO lançar exceções em erros, em vez de retornar false silenciosamente —
        // assim os erros não passam despercebidos.
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

        // Retorna os resultados como arrays associativos (['coluna' => 'valor']),
        // em vez do padrão que mistura índice numérico e nome da coluna.
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

        // Desativa a emulação de prepared statements do PDO, forçando o uso
        // dos prepared statements REAIS do MySQL — mais seguro contra SQL Injection.
        PDO::ATTR_EMULATE_PREPARES => false, 
    ];

    try {
        return new PDO($dsn, $usuario, $senha, $opcoes);
    } catch (PDOException $e) {
        die('Erro na conexão com o banco: ' . $e->getMessage());
    }
}
