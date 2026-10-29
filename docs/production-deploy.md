# Deploy de produção

O deploy do MyAAC é acionado por um push na branch `main`. O workflow executa
o lint PHP e só então publica o mesmo commit no VPS.

## Secrets obrigatórios

Configure estes secrets no repositório `Zealot-Server/myaac-main`:

- `VPS_HOST`: endereço IP ou hostname do VPS.
- `VPS_USER`: usuário de deploy do VPS.
- `VPS_SSH_KEY`: chave privada SSH exclusiva para o deploy.
- `VPS_HOST_FINGERPRINT`: fingerprint SSH do VPS no formato aceito pela ação
  `appleboy/ssh-action`.

Nunca coloque chaves, senhas ou o arquivo `config.local.php` no Git.

## O que o workflow faz

1. Executa o lint PHP.
2. Busca no VPS o commit exato que foi validado no GitHub.
3. Executa as migrações SQL ainda não aplicadas.
4. Reinicia somente o serviço `myaac`.
5. Verifica a página localmente no VPS.

Se a atualização de código ou a verificação HTTP falhar, o código volta para o
commit anterior. As migrações são somente aditivas e idempotentes; por isso não
há rollback automático de schema.
