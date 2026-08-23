# 🧪 Roteiro de Testes

Este documento descreve os testes manuais realizados para validar o funcionamento do CRUD de notícias. Os testes cobrem os quatro fluxos principais (Create, Read, Update, Delete), busca/filtro, paginação e casos de validação e segurança.

> Além dos testes manuais abaixo, o projeto também conta com testes automatizados feitos com PHPUnit, cobrindo os métodos da classe `Noticia` (criar, buscar por ID, listar, atualizar, excluir). Para rodá-los, veja as instruções no `README.md`.

## 1. Cadastro de notícia (CREATE)

| Cenário | Passos | Resultado esperado | Resultado obtido |
|---|---|---|---|
| Cadastro com todos os campos preenchidos | Acessar `criar.php`, preencher todos os campos e enviar | Notícia salva no banco e mensagem de sucesso exibida | ✅ Passou |
| Cadastro sem campos obrigatórios | Tentar enviar o formulário com título ou conteúdo vazio | Formulário bloqueado pelo JavaScript antes do envio | ✅ Passou |

## 2. Listagem, busca e visualização (READ)

| Cenário | Passos | Resultado esperado | Resultado obtido |
|---|---|---|---|
| Listagem de notícias cadastradas | Acessar `index.php` | Notícias aparecem, ordenadas da mais recente para a mais antiga | ✅ Passou |
| Listagem vazia | Acessar `index.php` sem nenhuma notícia no banco | Mensagem "Nenhuma notícia cadastrada ainda" é exibida | ✅ Passou |
| Busca por título | Digitar parte de um título no campo de busca | Apenas notícias com aquele termo no título aparecem | ✅ Passou |
| Filtro por categoria | Selecionar uma categoria no filtro | Apenas notícias daquela categoria aparecem | ✅ Passou |
| Paginação | Cadastrar mais de 5 notícias e navegar entre páginas | Cada página mostra até 5 notícias, navegação funciona corretamente | ✅ Passou |
| Visualização de notícia existente | Clicar no título de uma notícia na listagem | Página de detalhe exibe os dados corretamente | ✅ Passou |
| Visualização de ID inexistente | Acessar `visualizar.php?id=9999` | Usuário é redirecionado para `index.php` | ✅ Passou |

## 3. Edição de notícia (UPDATE)

| Cenário | Passos | Resultado esperado | Resultado obtido |
|---|---|---|---|
| Edição com dados válidos | Alterar algum campo em `editar.php?id=X` e salvar | Dados atualizados no banco, redireciona para a visualização | ✅ Passou |
| Formulário pré-preenchido | Acessar `editar.php?id=X` | Campos aparecem preenchidos com os dados atuais | ✅ Passou |
| Edição com campo obrigatório vazio | Apagar o título e tentar salvar | Formulário bloqueado pelo JavaScript antes do envio | ✅ Passou |

## 4. Exclusão de notícia (DELETE)

| Cenário | Passos | Resultado esperado | Resultado obtido |
|---|---|---|---|
| Exclusão confirmada | Clicar em "Excluir" e confirmar na caixa de diálogo | Notícia removida do banco e da listagem | ✅ Passou |
| Exclusão cancelada | Clicar em "Excluir" e cancelar na caixa de diálogo | Notícia permanece no banco, sem alterações | ✅ Passou |

## 5. Segurança e validação

| Cenário | Passos | Resultado esperado | Resultado obtido |
|---|---|---|---|
| Proteção contra XSS | Cadastrar notícia com `<script>alert('teste')</script>` no título | Texto exibido como texto puro na tela, sem executar o script | ✅ Passou |
| Proteção contra SQL Injection | Inserir `' OR '1'='1` em um campo do formulário | Dado tratado como texto comum pelo prepared statement, sem afetar a consulta | ✅ Passou |

---

**Legenda:** ✅ Passou · ❌ Falhou

## Testes automatizados (PHPUnit)

Além dos testes manuais acima, a classe `Noticia` conta com 6 testes automatizados (`tests/NoticiaTest.php`), cobrindo:

- Criação de notícia com sucesso
- Listagem retorna sempre um array
- Busca por ID inexistente retorna `null`
- Criar e depois buscar a mesma notícia
- Atualizar uma notícia existente
- Excluir uma notícia existente

Para rodar: `vendor\bin\phpunit`