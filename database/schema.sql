-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS opovo_noticias
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE opovo_noticias;

-- Tabela de notícias
CREATE TABLE IF NOT EXISTS noticias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    subtitulo VARCHAR(255),
    conteudo TEXT NOT NULL,
    autor VARCHAR(100) NOT NULL,
    categoria VARCHAR(50),
    data_publicacao DATETIME NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);