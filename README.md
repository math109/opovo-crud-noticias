# Sistema de Notícias — CRUD em PHP

Projeto desenvolvido como teste prático para o processo seletivo de Aprendiz Back-end do Grupo de Comunicação O POVO. Consiste em um CRUD (Create, Read, Update, Delete) de notícias, construído em PHP puro com MySQL.

## Tecnologias utilizadas

- PHP (com PDO para acesso ao banco de dados)
- MySQL
- HTML5
- CSS3
- JavaScript (validação de formulários no navegador)
- Git / GitHub

## Estrutura do projeto

opovo-crud-noticias/
├── config/
│ └── database.php # Conexão com o banco (PDO)
├── public/ # Arquivos acessíveis pelo navegador
│ ├── index.php # Listagem de notícias
│ ├── criar.php # Formulário de cadastro
│ ├── editar.php # Formulário de edição
│ ├── excluir.php # Ação de exclusão
│ ├── visualizar.php # Detalhe de uma notícia
│ ├── css/style.css
│ └── js/script.js
├── src/
│ └── Noticia.php # Classe de acesso a dados da entidade Notícia
└── database/
└── schema.sql # Script de criação do banco e da tabela


## Como rodar o projeto localmente

### Pré-requisitos

- [XAMPP](https://www.apachefriends.org/) (ou qualquer ambiente com Apache + PHP + MySQL)

### Passo a passo

1. Clone este repositório dentro da pasta `htdocs` do XAMPP:
```bash
   git clone https://github.com/math109/opovo-crud-noticias.git
```
2. Inicie o **Apache** e o **MySQL** pelo painel do XAMPP.
3. Acesse o phpMyAdmin (`http://localhost/phpmyadmin`) e execute o script `database/schema.sql` para criar o banco de dados e a tabela.
4. Acesse o projeto no navegador: http://localhost/opovo-crud-noticias/public/index.php



Por padrão, a conexão em `config/database.php` usa o usuário `root` sem senha, que é a configuração padrão do XAMPP. Caso seu ambiente use outra configuração, ajuste esse arquivo.

## Decisões técnicas

- **PDO em vez de mysqli**: escolhido por oferecer uma API mais consistente para prepared statements, prevenindo SQL Injection.
- **Separação em camadas**: a classe `Noticia` (em `src/`) concentra toda a lógica de acesso ao banco, enquanto os arquivos em `public/` cuidam apenas da apresentação (HTML) e do fluxo da requisição. Isso facilita manutenção e testes.
- **Padrão Post/Redirect/Get**: após operações de escrita (criar, editar, excluir), o usuário é redirecionado via `header('Location: ...')`, evitando reenvio duplicado de formulário ao atualizar a página.
- **Validação em duas camadas**: JavaScript valida no navegador para dar feedback imediato, mas o PHP revalida no servidor — a validação client-side pode ser contornada, então nunca é a única linha de defesa.
- **`htmlspecialchars()` em todo dado exibido**: previne ataques de XSS (Cross-Site Scripting) ao renderizar conteúdo digitado por usuários.

## Testes

Veja o arquivo [`TESTES.md`](./TESTES.md) com o roteiro de testes manuais realizados.

## Autor

Matheus Martins
