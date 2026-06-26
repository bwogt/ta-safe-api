<?php

return [
    'auth' => [
        'invalid_credentials' => 'Credenciais inválidas.',
        'email_not_exists' => 'O e-mail informado não existe.',
    ],
    'password_reset' => [
        'cooldown' => 'Você já solicitou um código para redefinição de senha. Tente novamente em alguns minutos.',
        'blocked' => 'A redefinição de senha está temporariamente bloqueada. Tente novamente em alguns minutos.',
        'invalid_code' => 'Código inválido ou expirado.',
        'attempts_exceeded' => 'Você excedeu o limite de tentativas. Tente novamente em alguns minutos.',
    ],
    'device' => [
        'user_not_owner' => 'Somente o proprietário do dispositivo pode realizar esta ação.',
        'invalid_device_state' => 'Este dispositivo está em um estado inválido para realizar esta operação.',
        'active_share' => 'Este dispositivo já possui um código de compartilhamento ativo.',
        'share_code_not_found' => 'Código inválido ou expirado.',
    ],
    'device_transfer' => [
        'self_transfer_not_allowed' => 'Não é possível transferir um dispositivo para si mesmo.',
        'has_pending_transfer' => 'O dispositivo possui uma transferência pendente.',
        'cannot_be_modified' => 'Esta transferência de dispositivo não pode ser modificada.',
        'recipient_mismatch' => 'Somente o destinatário desta transferência pode realizar esta ação.',
        'sender_mismatch' => 'Somente o usuário de origem desta transferência pode realizar esta ação.',
    ],
];
