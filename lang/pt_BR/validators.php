<?php

return [
    'auth' => [
        'invalid_credentials' => 'Credenciais inválidas.',
    ],
    'device' => [
        'user_not_owner' => 'Somente o proprietário do dispositivo pode realizar esta ação.',
        'invalid_device_state' => 'Este dispositivo está em um estado inválido para realizar esta operação.',
    ],
    'device_transfer' => [
        'self_transfer_not_allowed' => 'Não é possível transferir um dispositivo para si mesmo.',
        'has_pending_transfer' => 'O dispositivo possui uma transferência pendente.',
        'cannot_be_modified' => 'Esta transferência de dispositivo não pode ser modificada.',
        'recipient_mismatch' => 'Somente o destinatário desta transferência pode realizar esta ação.',
        'sender_mismatch' => 'Somente o usuário de origem desta transferência pode realizar esta ação.',
    ],
];
