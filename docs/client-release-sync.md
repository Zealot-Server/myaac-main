# Sincronização dos downloads do cliente

O workflow `Sync verified client release` verifica a cada 15 minutos o último release público do repositório `Zealot-Server/otclient`. Também pode ser iniciado manualmente em GitHub Actions.

O site publica uma nova versão apenas quando:

1. O release é estável, aponta para um commit SHA exato e é mais novo que o manifesto atual.
2. Os quatro pacotes de Linux, Windows, macOS Apple Silicon e macOS Intel existem, têm tamanho positivo e digest SHA256.
3. Uma execução bem sucedida do workflow `ci.yml` no branch `main`, para o mesmo commit, aprovou os quatro jobs `Release E2E`.

O workflow atualiza `system/client-release.php` com os links e hashes do release. Esse arquivo alimenta a página de downloads e a API de atualização do cliente. Depois do commit automático, o workflow aciona explicitamente `Production CI and deploy`, pois commits feitos com `GITHUB_TOKEN` não disparam outros workflows por `push`.

Se a validação falhar, o manifesto e o site permanecem na versão anterior. Consulte o log do workflow para identificar o pacote ou teste pendente. Não é necessário guardar um token entre os dois repositórios. A publicação do release do OTClient continua sendo uma etapa separada.
