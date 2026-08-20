<?php
/**
 * Arquivo de conexão com o banco de dados.
 * Usa PDO (PHP Data Objects) porque é mais seguro contra
 * SQL Injection e funciona com prepared statements.
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
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // lança erro em vez de falhar silenciosamente
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // retorna arrays associativos
        PDO::ATTR_EMULATE_PREPARES => false, // usa prepared statements reais do MySQL
    ];

    try {
        return new PDO($dsn, $usuario, $senha, $opcoes);
    } catch (PDOException $e) {
        die('Erro na conexão com o banco: ' . $e->getMessage());
    }
}