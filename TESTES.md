# 🧪 Roteiro de Testes

Este documento descreve os testes manuais realizados para validar o funcionamento do CRUD de notícias. Os testes cobrem os quatro fluxos principais (Create, Read, Update, Delete) e alguns casos de validação e erro.

> Testes automatizados não foram implementados nesta versão do projeto (ver seção "Melhorias futuras" no README). Os testes abaixo foram executados manualmente, através da interface do sistema.

## 1. Cadastro de notícia (CREATE)

| Cenário | Passos | Resultado esperado | Resultado obtido |
|---|---|---|---|
| Cadastro com todos os campos preenchidos | Acessar `criar.php`, preencher título, conteúdo, autor, categoria e data, e enviar | Notícia salva no banco e mensagem de sucesso exibida | ✅ Passou |
| Cadastro sem campos obrigatórios | Tentar enviar o formulário com título ou conteúdo vazio | Formulário não deve ser enviado (bloqueio via JavaScript) e, se enviado mesmo assim, o PHP deve rejeitar e exibir mensagem de erro | ✅ Passou |

## 2. Listagem e visualização (READ)

| Cenário | Passos | Resultado esperado | Resultado obtido |
|---|---|---|---|
| Listagem de notícias cadastradas | Acessar `index.php` | Todas as notícias cadastradas aparecem, ordenadas da mais recente para a mais antiga | ✅ Passou |
| Listagem vazia | Acessar `index.php` sem nenhuma notícia no banco | Mensagem "Nenhuma notícia cadastrada ainda" é exibida | ⬜ A testar |
| Visualização de notícia existente | Clicar no título de uma notícia na listagem | Página de detalhe exibe todos os dados da notícia corretamente | ✅ Passou |
| Visualização de ID inexistente | Acessar `visualizar.php?id=9999` (um ID que não existe) | Usuário é redirecionado de volta para `index.php` | ⬜ A testar |

## 3. Edição de notícia (UPDATE)

| Cenário | Passos | Resultado esperado | Resultado obtido |
|---|---|---|---|
| Edição com dados válidos | Acessar `editar.php?id=X`, alterar algum campo e salvar | Dados atualizados no banco e usuário redirecionado para a visualização | ✅ Passou |
| Formulário pré-preenchido | Acessar `editar.php?id=X` | Todos os campos aparecem preenchidos com os dados atuais da notícia | ✅ Passou |
| Edição com campo obrigatório vazio | Apagar o título e tentar salvar | Formulário bloqueado pelo JavaScript / PHP rejeita e mantém dados digitados | ⬜ A testar |

## 4. Exclusão de notícia (DELETE)

| Cenário | Passos | Resultado esperado | Resultado obtido |
|---|---|---|---|
| Exclusão confirmada | Clicar em "Excluir" e confirmar na caixa de diálogo | Notícia removida do banco e não aparece mais na listagem | ✅ Passou |
| Exclusão cancelada | Clicar em "Excluir" e cancelar na caixa de diálogo | Notícia permanece no banco, nenhuma alteração ocorre | ⬜ A testar |

## 5. Segurança e validação

| Cenário | Passos | Resultado esperado | Resultado obtido |
|---|---|---|---|
| Proteção contra XSS | Cadastrar uma notícia com `<script>alert('teste')</script>` no título | O texto é exibido como texto puro na tela (escapado), o script não é executado | ⬜ A testar |
| Proteção contra SQL Injection | Tentar inserir `' OR '1'='1` em algum campo do formulário | O dado é tratado como texto comum pelo prepared statement, sem afetar a consulta | ⬜ A testar |

---

**Legenda:** ✅ Passou · ❌ Falhou · ⬜ A testar