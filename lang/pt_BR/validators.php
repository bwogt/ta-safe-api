<?php

return [
    'auth' => [
        'invalid_credentials' => 'Credenciais inválidas.',
    ],
    'device' => [
        'user_not_owner' => 'Somente o proprietário do dispositivo pode realizar esta ação.',
        'invalid_device_state' => 'Este dispositivo está em um estado inválido para realizar esta operação.',
        'status' => [
            'in_analysis' => 'O registro de validação do dispositivo precisa estar com status em análise.',
        ],
        'transfer' => [
            'pending' => 'O dispositivo possui uma transferência pendente.',
            'not_source_user' => 'Somente o usuário de origem da transferência pode realizar esta ação.',
            'not_target_user' => 'Somente o usuário de destino da transferência pode realizar esta ação.',
        ],
    ],
    'device_transfer' => [
        'self_transfer_not_allowed' => 'Não é possível transferir um dispositivo para si mesmo.',
        'has_pending_transfer' => 'O dispositivo possui uma transferência pendente.',
        'cannot_be_modified' => 'Esta transferência de dispositivo não pode ser modificada.',
        'recipient_mismatch' => 'Somente o destinatário desta transferência pode realizar esta ação.',
    ],
];
