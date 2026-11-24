# Confirmação de e-mail

O site usa Brevo SMTP com o remetente contact@zealot.website. A credencial fica no VPS, fora do diretório público do site, em `/opt/zealot/canary/docker/.zealot-brevo-smtp`, lida pelo container em `/canary/docker/.zealot-brevo-smtp`. O arquivo deve pertencer a root, ter grupo 33 e permissão 0640. Não registre seu conteúdo em logs ou no Git.

O arquivo deve estar disponível antes de ativar a verificação no login-server. Na ausência dele, o site não pode enviar links de confirmação. Não ative a restrição no login enquanto o envio não estiver validado.

Contas existentes podem entrar no site e usar o link de verificação no gerenciamento da conta. A conta admin@zealot.website é isenta da exigência no login do jogo. A alteração do endereço de uma conta invalida a confirmação anterior.
