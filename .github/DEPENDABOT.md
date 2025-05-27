# Configuração do Dependabot

Este projeto utiliza o GitHub Dependabot para manter as dependências PHP/Laravel atualizadas automaticamente.

## Como Funciona

O Dependabot verifica semanalmente (todas as segundas-feiras às 9h, horário de Brasília) por atualizações em:

- **Dependências PHP/Composer** - Laravel, pacotes PHP do projeto

## Configurações

- **Frequência**: Semanal (segundas-feiras às 9:00)
- **Limite de PRs**: 5 pull requests abertos simultaneamente
- **Labels automáticas**: `dependencies`, `php`, `composer`
- **Reviewer/Assignee**: lucas
- **Timezone**: America/Sao_Paulo

## Pull Requests Automáticos

O Dependabot criará automaticamente Pull Requests quando detectar:

1. **Atualizações de segurança** (prioridade alta)
2. **Atualizações de versão menor** (compatíveis)
3. **Atualizações de versão maior** (podem ter breaking changes)

## Mensagens de Commit

- **Composer**: `deps(composer): update [package] to [version]`

## Como Revisar

1. O Dependabot criará um PR automaticamente
2. Verifique as notas de versão do pacote atualizado
3. Execute os testes localmente se necessário
4. Aprove e faça merge se tudo estiver funcionando

## Configuração de Segurança

O Dependabot também monitora vulnerabilidades de segurança e criará PRs de alta prioridade para correções críticas.

## Desabilitar Temporariamente

Para pausar o Dependabot temporariamente:

1. Vá para Settings > Security & analysis no GitHub
2. Configure "Dependabot version updates" para disabled

## Arquivo de Configuração

A configuração completa está em `.github/dependabot.yml`
